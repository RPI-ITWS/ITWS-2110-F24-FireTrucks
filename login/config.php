<?php
   @ $db = new mysqli('localhost', 'phpmyadmin', 'Marketplace18', 'marketplace');

   if ($db->connect_error) {
      die("Connection failed: " . $db->connect_error);
   }
?>