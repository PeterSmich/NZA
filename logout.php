<?php
   session_start();
   $_SESSION["valid"] = false;
   $_SESSION["username"] = "";
   $_SESSION["password"] = "";

   header("Location:" . $_SESSION['redirect_url'] );
?>
