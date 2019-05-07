<?php
require_once 'user.php';

//establish connection
$conn = new Mysqli ('localhost','root','','bookings');
if($conn -> connect_error){
    die ("connection failed:" .$conn -> connect_error);
}
?>