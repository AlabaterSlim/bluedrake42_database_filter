<?php
// Script "Database 'xf_user_input_value' for particular user groups and "field_id"s
// v0.1
// Editors: J4nB1
//
// Description: Filters data from the bluedrake42.com database (tables "xf_user" and "xf_user_field_value").

// Config Variables
// IP of the Server
$servername = "127.0.0.1";

// Login name
$username = "admin";

// Login password
$password = "1234";

// Database name
$dbname = "drake";

// Groups ids to filter for
$group_ids = array(3, 7, 8, 11, 12);

// Field id to filter for
$field_id = 'projectreality';

// Name of the file where the data should get put to
$file_name = 'test.txt';

/*===============================================================*/

// System Variables (do not change)
$i1 = 0;
$i2 = 0;
$user_ids1 = array();
$user_ids2 = array();

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Join given group ids
$group_ids_joined = join(',', $group_ids);

// SQL-Query to filter for users in "xf_user" with given group id
$sql1 = "SELECT user_id FROM xf_user WHERE secondary_group_ids IN ($group_ids_joined)";

// Execute query
$result1 = mysqli_query($conn, $sql1);

// Restructure results of query (associative array) to numeric array
while ($row = mysqli_fetch_assoc($result1)) {
  $user_ids1[$i1] = $row['user_id'];
  $i1++;
}

// Join user id's fetched from previous query
$user_ids_joined = join(',', $user_ids1);

// SQL-Query to filter for users in "xf_user_field_value" for user ids fetched from previous query and given field id
$sql2 = "SELECT user_id FROM xf_user_field_value WHERE user_id IN ($user_ids_joined) AND field_id = '$field_id'";

// Execute query
$result2 = mysqli_query($conn, $sql2);

// Restructure results of query (associative array) to numeric array
while ($row = mysqli_fetch_assoc($result2)) {
  $user_ids2[$i2] = $row['user_id'];
  $i2++;
}

// Close SQL-Database connection
$conn->close();

// Serialize data
$data = serialize($user_ids2);

// Output data to file with given filename and ending
file_put_contents($file_name, $data);
?>
