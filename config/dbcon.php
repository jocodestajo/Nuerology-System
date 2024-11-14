<?php

// This is a connection that is always require in every page everytime may db connection.
$conn = mysqli_connect("localhost","root","","ihoms_inventory");

if(!$conn){
    die('Connection Failed'. mysqli_connect_error());
}
?>