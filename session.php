<?php
   require_once 'sessionhelpers.php';
   
   if(!checkSession()){
      header("location: /WhxyteWeddingDB/login.php");
   }
?>