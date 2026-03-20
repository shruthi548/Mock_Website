<?php
include '../../config.php';
$admin=new Admin();

if(isset($_GET['del'])){
    $id = $_GET['del'];
    $admin->cud("Delete from `users` where `u_id`='$id'","deleted");
echo "<script>alert('User deleted successfully'); window.location.href='../students.php';</script>";
}