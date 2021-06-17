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

 $itemid = $_POST["itemid"];

//connect with the WP database
global $wpdb;

// creates order_form_entries table in database if not exists
$table = $wpdb->prefix . "elevator_form_entries";

$prefix = 
$sql = "SELECT * FROM ' . $table . ' where el_cart_item_id ='$itemid'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {


  $t = date("Y-m-d h:i:s");

  $id = $row['el_user_id'];
  $ss = $row['el_session_id'];
  $it = bin2hex(random_bytes(9));;
  //$it = $row['el_cart_item_id'];
  $da = json_encode($row['el_item_data']);

      $sqls = "insert into ' . $table . ' (created,el_user_id,el_session_id,el_cart_item_id,el_item_data) values ('$t','$id','$ss','$it',$da)";

    if ($conn->query($sqls) === TRUE) {
      echo "insert";
    } else {
      echo "Error: " . $sqls . "<br>" . $conn->error;
    }
  
  }
}  


$conn->close();
?>