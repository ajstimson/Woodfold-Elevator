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
  $it = $_POST["add"];

//connect with the WP database
global $wpdb;

// creates order_form_entries table in database if not exists
$table = $wpdb->prefix . "elevator_form_entries";

$sql = "SELECT * FROM ' . $table . ' where id ='$it'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  $s = $row["status"];
 
  	 $sqls = "update ' . $table . ' set status = '1' where id='$it'";

		if ($conn->query($sqls) === TRUE) {
		  echo "Successfully Saved Configuration";
		} else {
		  echo "Error: " . $sqls . "<br>" . $conn->error;
		}
	
  }
}

 $itemid = $_POST["itemid"];
     
$sql = "SELECT * FROM ' . $table . ' where el_cart_item_id ='$itemid'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
  $s = $row["status"];
 
     $sqls = "update ' . $table . ' set status = '1' where el_cart_item_id='$itemid'";

    if ($conn->query($sqls) === TRUE) {
      echo "Successfully Saved Configuration";
    } else {
      echo "Error: " . $sqls . "<br>" . $conn->error;
    }
  
  }
}  
$conn->close();
?>