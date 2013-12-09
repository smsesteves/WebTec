<?php 
   // session_start();
    
    if(!(isset($_SESSION['login']) && $_SESSION['login'] == TRUE && $_SESSION['role_id'] == 0)){
        header('Location:index.php');
    }
?>

Em construção...