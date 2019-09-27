<?php 
/**
 * Author: Alexander
 * Version: 0.1.0
 */

 // TODO: Access them from somewhere secure rather than hardcoding them.
define( 'CLIENT_ID', 'b0bf9de6f7acae51f833e3458bef21dc' );
define( 'CLIENT_SECRET', 'ed494c64ed3a1404c2129b57c28996c3' );
define( 'SCOPE', 'accounts_read identify' );

// Make sure we include our class.
require_once( "lib/functions.php" );
require_once( "lib/classes/ApiEndpoint.php" );
require_once( "lib/classes/CannedReponse.php" );

// Create a new endpoint.
$api_endpoint = new Clarabridge\ApiTest\ApiEndpoint( CLIENT_ID, CLIENT_SECRET, SCOPE );

// This just echoes the messages and responses.
$api_endpoint->send_and_display_sentiment_data();

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Inconspicuous Index</title>
  </head>
  <body>
  
  </body>
</html>

<?php

?>