<?php
$servername = "ep-super-heart-b3bbj00j-pooler.c-4.ap-southeast-1.aws.neon.tech";
$username = "neondb_owner";
$password = "npg_FxZL1HuGnt6U";
$dbname = "neondb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
