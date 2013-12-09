<?php 
session_start();
if($_SESSION['login'] == TRUE){
header("location:index.php");
}
?>

<html>
<body>
Login Successful
</body>
</html>