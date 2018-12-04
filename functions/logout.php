<?php
   session_start();
   unset($_SESSION["valid"]);
   unset($_SESSION["timestamp"]);
   unset($_SESSION["displayName"]);
   unset($_SESSION["username"]);

   echo '<body style="padding: 100px; text-align: center; font-family: Arial;">';
   echo '<img src="/functions/images/success.png" style="max-width: 500px;" />';
   echo "<p>You have logout. Your current session has been cleaned.</p>";
   echo '</body>';

   // Redirect back to index.php
   header("Location: ../index.php");
?>
