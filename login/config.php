<?php
   @ $db = new mysqli('localhost', 'root', '', 'project', 3306);

   if ($db->connect_error) {
      die("Connection failed: " . $db->connect_error);
   }
?>