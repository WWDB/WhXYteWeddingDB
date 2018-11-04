<?php
include('config.php');
function checkSession(){
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    session_start();
   
    $user_check = $_SESSION['login_user'];
   
    $ses_sql = mysqli_query($db,"SELECT Email FROM Person WHERE Email = '$user_check'");
   
    $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
    $login_session = $row['username'];
   
   return isset($_SESSION['login_user']);
      
   
}
?>