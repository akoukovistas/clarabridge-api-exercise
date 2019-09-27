<?php declare( strict_types=1 );

namespace Clarabridge\ApiTest;

/**
 * Class ApiEndpoint
 *
 * An instance of the endpoint that captures and processes requests.
 *
 * @package Clarabridge\ApiTest
 */
class ApiEndpoint {

	// The variables for our object.
	private $client_id;
	private $client_secret;
	private $scope;
	private $params;
	private $canned_responses;
	private $user_accounts;


	/**
	 * ApiEndpoint constructor.
	 *
	 * @param $client_id
	 * @param $client_secret
	 * @param $scope
	 */
	public function __construct( $client_id, $client_secret, $scope ) {

		// Assign all required data to this instance.
		$this->client_id        = $client_id;
		$this->client_secret    = $client_secret;
		$this->scope            = $scope;
		$this->params           = $this->init_auth( $client_id, $client_secret, $scope );
		$this->user_accounts    = $this->get_current_user_accounts( $this->params );
		$this->canned_responses = $this->retrieve_canned_responses( $this->user_accounts, $this->params );

	}

	/**
	 * This will dump out the $canned_responses for you.
	 */
	public function display_canned_responses(): void {
		var_dump( $this->canned_responses );
	}

	/**
	 * This will grab the data from the endpoint and display them alongside the messages.
	 */
	public function send_and_display_sentiment_data(): void {

		$compiled_messages = $this->compile_messages( $this->canned_responses );

		$sentiment_data = $this->get_sentiment_data( json_encode( $compiled_messages ), $this->params );

		for ( $i = 0; $i < count( $compiled_messages ); $i ++ ) {
			echo '"' . $compiled_messages[ $i ] . '"' . ' is ' . $sentiment_data[ $i ] . '<br>';
		}
	}

	/**
	 * Helper function that compiles all the messages from the canned responses into an array.
	 *
	 * @param array $canned_responses the canned responses from our account.
	 *
	 * @return array
	 */
	public function compile_messages( $canned_responses ): array {
		$canned_messages = [];

		foreach ( $canned_responses as $canned_response ) {
			$canned_messages[] = $canned_response->get_message();
		}

		return $canned_messages;

	}

	/**
	 * Sends a request to the endpoint, grabs the reponse and saves it in a human-readable format.
	 * https://developers.engagor.com/documentation/endpoints/?url=/tools/sentiment
	 *
	 * @param string $compiled_messages This contains our compiled messages from the canned responses.
	 * @param array $params The parameters of our request.
	 *
	 * @return array
	 */
	public function get_sentiment_data( string $compiled_messages, array $params ): array {

		// Init our array of responses and set the path.
		$sentiment_response  = [];
		$sentiment_data_path = "https://api.engagor.com/tools/sentiment?access_token=" . $params['access_token'] . "&string=" . urlencode( $compiled_messages ) . "&language=en";

		// Grab and decode the data.
		$sentiment_data = file_get_contents( $sentiment_data_path );
		$sentiment_data = json_decode( $sentiment_data, true )['response'];

		// Loop through the data and assign a human-readable value.
		foreach ( $sentiment_data as $sentiment_datum ) {
			if ( $sentiment_datum == 0 ) {
				$sentiment_response[] = "neutral";
			} else if ( $sentiment_datum > 0 ) {
				$sentiment_response[] = "positive";
			} else if ( $sentiment_datum < 0 ) {
				$sentiment_response[] = "negative";
			} else {
				$sentiment_response[] = "erroneous";
			}
		}

		return $sentiment_response;
	}

	/**
	 * This will init a basic oauth2 and save token data so we can grab data from the various endpoints.
	 * Based on this: https://developers.engagor.com/documentation/auth/
	 *
	 * @param string $client_id The ID of the client app.
	 * @param string $client_secret The secret key of the app.
	 * @param string $scope The permissions required by the app.
	 *
	 * @return array
	 */
	public function init_auth( string $client_id, string $client_secret, string $scope ): array {
		session_start();
		$code = isset( $_REQUEST['code'] ) ? $_REQUEST['code'] : null;

		if ( isset( $_GET['error'] ) && $_GET['error'] && $_GET['error'] === 'access_denied' ) {
			echo 'The user did not authorize your application to use your Clarabridge Engage account.';
			exit();
		}

		if ( empty( $code ) ) {
			$_SESSION['state'] = md5( uniqid( (string) rand(), true ) ); // CSRF protection
			$authorize_url     = 'https://app.engagor.com/oauth/authorize/?client_id='
			                     . $client_id . '&state=' . $_SESSION['state'] . '&response_type=code';
			if ( $scope ) {
				$authorize_url .= '&scope=' . urlencode( $scope );
			}

			echo( "<script> top.location.href='" . $authorize_url . "'; </script>" ); // forward to auth page
			exit();
		}

		if ( isset( $_REQUEST['state'] ) && $_REQUEST['state'] !== $_SESSION['state'] ) {
			echo 'The state does not match. You may be a victim of CSRF.';
			exit();
		}

		$token_url = 'https://app.engagor.com/oauth/access_token/?'
		             . 'client_id=' . $client_id . '&client_secret=' . $client_secret
		             . '&grant_type=authorization_code' . '&code=' . $code;

		$response = @file_get_contents( $token_url );
		$params   = json_decode( $response, true );

		if ( ! $params['access_token'] ) {
			echo 'We could not validate your access token.';
			exit();
		}

		return $params;

	}

	/**
	 * Returns the current user.
	 *
	 * @param array $params
	 *
	 * @return array
	 */
	public function get_current_user_accounts( array $params ): array {
		$protected_source = 'https://api.engagor.com/me/accounts?access_token='
		                    . $params['access_token'];

		$content = json_decode( file_get_contents( $protected_source ), true );
		if ( ! is_array( $content ) || ! isset ( $content['response'] ) ) {
			die( print_r( $content, true ) );
		}

		$user_accounts = $content['response']['data'];

		return $user_accounts;
	}

	/**
	 * This loops through the accounts and accesses the available canned responses based on the account id.
	 *
	 * @param array $accounts
	 * @param array $params
	 *
	 * @return array
	 */
	public function retrieve_canned_responses( array $accounts, array $params ): array {

		$canned_responses_from_api = [];
		$canned_responses = [];

		// Loop through all the available accounts.
		foreach ( $accounts as $account ) {
			$canned_response_path = "https://api.engagor.com/" . $account['id'] . '/settings/canned_responses?access_token=' . $params['access_token'];
			$canned_responses_from_api[]   = json_decode( file_get_contents( $canned_response_path ), true );
		}

		// Grab the decoded data of the response only.
		$canned_responses_from_api = $canned_responses_from_api[0]['response']['data'];


		foreach ( $canned_responses_from_api as $canned_response_from_api ) {

			// Create a new canned response object and add it to the array.
			$canned_responses[] = new CannedReponse( $canned_response_from_api['id'],
				$canned_response_from_api['name'], $canned_response_from_api['message'], $canned_response_from_api['type'],
				$canned_response_from_api['labels'], $canned_response_from_api['images'], $canned_response_from_api['folder'],
				array_key_exists('data', $canned_response_from_api ) ? $canned_response_from_api['data'] : null );

		}

		return $canned_responses;
	}

	/**
	 * @return mixed
	 */
	public function get_client_id() {
		return $this->client_id;
	}

	/**
	 * @param mixed $client_id
	 */
	public function set_client_id( $client_id ): void {
		$this->client_id = $client_id;
	}

	/**
	 * @return mixed
	 */
	public function get_client_secret() {
		return $this->client_secret;
	}

	/**
	 * @param mixed $client_secret
	 */
	public function set_client_secret( $client_secret ): void {
		$this->client_secret = $client_secret;
	}

	/**
	 * @return mixed
	 */
	public function get_scope() {
		return $this->scope;
	}

	/**
	 * @param mixed $scope
	 */
	public function set_scope( $scope ): void {
		$this->scope = $scope;
	}

	/**
	 * @return mixed
	 */
	public function get_params() {
		return $this->params;
	}

	/**
	 * @param mixed $params
	 */
	public function set_params( $params ): void {
		$this->params = $params;
	}

	/**
	 * @return mixed
	 */
	public function get_canned_responses() {
		return $this->canned_responses;
	}

	/**
	 * @param mixed $canned_responses
	 */
	public function set_canned_responses( $canned_responses ): void {
		$this->canned_responses = $canned_responses;
	}

	/**
	 * @return array
	 */
	public function get_user_accounts(): array {
		return $this->user_accounts;
	}

	/**
	 * @param array $user_accounts
	 */
	public function set_user_accounts( array $user_accounts ): void {
		$this->user_accounts = $user_accounts;
	}


}

