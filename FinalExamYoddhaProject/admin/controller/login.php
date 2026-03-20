<?php
include '../../config.php';
$admin=new Admin();

if(isset($_POST['login'])){
    $email=$_POST['email'];
    $password=$_POST['password'];

    $st=$admin->ret("select * from `admin` where `a_email`='$email' and `a_password`='$password'");
    $row=$st->fetch(PDO::FETCH_ASSOC);
    $count=$st->rowCount();
    if($count>0){
        $_SESSION['a_id']=$row['a_id'];
        $_SESSION['a_name']=$row['a_name'];
       
        echo "<script>
        alert('Admin Login Successfull');
        window.location.href='../index.php';
        </script>";
    }else{
        echo "<script>
        alert('Admin Login Failed');
        window.location.href='../login.php';
        </script>";
    }
}
?>
   