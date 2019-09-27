<?php declare( strict_types=1 );
/**
 * User: Alexander
 * Date: 9/27/19
 * Time: 01:06
 */

namespace Clarabridge\ApiTest;

/**
 * A class to represent canned responses.
 *
 * Class CannedReponse
 * @package Clarabridge\ApiTest
 */
class CannedReponse {

	// The variables for our object.
	private $id;
	private $name;
	private $message;
	private $type;
	private $labels;
	private $images;
	private $folder;
	private $data;


	/**
	 * CannedReponse constructor.
	 *
	 * @param string $id
	 * @param string $name
	 * @param string $message
	 * @param string $type
	 * @param array $labels
	 * @param array $images
	 * @param null $folder
	 * @param array|null $data
	 */
	public function __construct( string $id, string $name, string $message, string $type, array $labels = [], array $images = [], $folder = null, array $data = null ) {
		$this->id      = $id;
		$this->name    = $name;
		$this->message = $message;
		$this->type    = $type;
		$this->labels  = $labels;
		$this->images  = $images;
		$this->folder  = $folder;
		$this->data    = $data;
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param $id
	 */
	public function set_id( $id ): void {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @param $name
	 */
	public function set_name( $name ): void {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * @param $message
	 */
	public function set_message( $message ): void {
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param $type
	 */
	public function set_type( $type ): void {
		$this->type = $type;
	}

	/**
	 * @return array
	 */
	public function get_labels(): array {
		return $this->labels;
	}

	/**
	 * @param array $labels
	 */
	public function set_labels( array $labels ): void {
		$this->labels = $labels;
	}

	/**
	 * @return array
	 */
	public function get_images(): array {
		return $this->images;
	}

	/**
	 * @param array $images
	 */
	public function set_images( array $images ): void {
		$this->images = $images;
	}

	/**
	 * @return null
	 */
	public function get_folder() {
		return $this->folder;
	}

	/**
	 * @param $folder
	 */
	public function set_folder( $folder ): void {
		$this->folder = $folder;
	}

	/**
	 * @return array|null
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * @param $data
	 */
	public function set_data( $data ): void {
		$this->data = $data;
	}


}