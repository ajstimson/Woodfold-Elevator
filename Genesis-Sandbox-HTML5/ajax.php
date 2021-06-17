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
 $it = $_POST["tt"];

//connect with the WP database
//TODO figure out how to get the prefix dynamically bc the line below is not working
global $wpdb;

// creates order_form_entries table in database if not exists
$table = $wpdb->prefix . "elevator_form_entries";

$sql = "SELECT * FROM ' $table ' where id =' $it '";
echo $sql;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    $item_data = $row["el_item_data"];
    $item = json_decode($item_data);
    echo '<li id="field_1_72" class="gfield review-panel gfield_html gfield_html_formatted gfield_no_follows_desc field_sublabel_below field_description_below gfield_visibility_visible">
            <h3>Review Order</h3>
            <div id="review-content">
              <ul class="your-address">
                <li><strong>Your Info</strong></li>
                <li>'.$item->first_name->value.' '.$item->last_name->value.'</li>
                <li>'.$item->email->value.'</li>
                
              </ul>
              <ul class="order-details">
                <li><strong>Order Details</strong></li>
                <li>P.O. Number: '.$item->po_number->value.'</li>
                <li>Sidemark: '.$item->sidemark->value.'</li>
                <li>Pricing Zone: '.$item->shipping->value.'</li>
              </ul>
              <ul class="config-details">
                <li><strong>Gate Configuration Details</strong></li>
                <li>Cab Width: '.$item->gate_width->value.'</li>
                <li>Number of Gate Panels: '.$item->number_of_gate_panels->value.'</li>
                <li>Full Opening Width: '.$item->full_opening_width->value.'</li>
                <li>Stack Collapsed Size: '.$item->stack_collapsed_size->value.'</li>
                <li>Wall to Lead Post Clearance: '.$item->wall_to_leadpost_clearance->value.'</li>
                <li>Height: '.$item->cab_height->value.'</li>
                <li>Height Option: '.$item->height_options->value.'</li>
                <li>Rivet to Rivet Height: '.$item->cab_height->value.'</li>
                  <li></li>
                  <li>Price: '.$item->quote->value.'</li>
              </ul>
            </div>
            <div id="item_data" style="display:none">'.$item_data.'</div>
          </li>';
  }
} 
$conn->close();
?>