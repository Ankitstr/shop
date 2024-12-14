<?php
$conn = new mysqli("localhost","root","","ecom");

if($conn->connect_error){
    die("error".$conn->connect_error);
}else{
    echo "successfully";
}
?>