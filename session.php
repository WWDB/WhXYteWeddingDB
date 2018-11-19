<?php
   require_once 'sessionhelpers.php';
   
   if(!checkSession()){
      header("location: /testview/login.php");
   }
?>