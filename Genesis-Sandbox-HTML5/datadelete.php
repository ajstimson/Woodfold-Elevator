<?php
//include 'wp-config.php';
$servername = "localhost";
$username = "woodfolddev";
$password = "025nd5REISXa_l-e0BfY";
$dbname = "wp_woodfolddev";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
  $id = $_POST["del"];

  //connect with the WP database
  global $wpdb;

	// creates order_form_entries table in database if not exists
	$table = $wpdb->prefix . "elevator_form_entries";

    
  	 $sqls = "update ' . $table . ' set status = '0' where id='$id'";

		if ($conn->query($sqls) === TRUE) {
		  echo "Delete Configuration";
		} else {
		  echo "Error: " . $sqls . "<br>" . $conn->error;
		}
	 
$conn->close();
?>