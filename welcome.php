<?php
   include('session.php');
?>
<html>
   
   <head>
      <title>Welcome </title>
   </head>
   
   <body>
      <h1>Welcome <?php echo $_SESSION['login_user']; ?></h1> 
	  <h2><a href = "/testview/testview.html">Test View</a></h2>
      <h3><a href = "/testview/logout.php">Sign Out</a></h3>
   </body>
   
</html>