<?php
   session_start();
   
   if(session_destroy()) {
	  $_SESSION = [];
      header("Location: /testview/login.php");
   }
?>