<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] == FALSE) {
    header('Location:index.php');
}
?>

<?php  
  function getDB(){
    return new PDO('sqlite:../db/db_t1.db');   
  }
?>
