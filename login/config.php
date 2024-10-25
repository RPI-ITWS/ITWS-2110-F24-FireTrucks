<?php
   $db = new mysqli('firetrucks.eastus.cloudapp.azure.com', 'phpmyadmin', 'Marketplace18', 'marketplace');

   if ($db->connect_error) {
      die("Connection failed: " . $db->connect_error);
   }
?>