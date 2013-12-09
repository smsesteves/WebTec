


<?php

session_start();


// validar os dados
$defaultype="2";
$db = new PDO('sqlite:db/db_t1.db');
$sql = "INSERT INTO users (username,password,nome, email, morada, contacto,role_id) VALUES (?,?,?,?,?,?,?);";

//echo $sql;
$stmt = $db->prepare($sql);
$stmt->execute(array($_POST['username'],md5($_POST['senha']),$_POST['nome'],$_POST['email'],$_POST['morada'],$_POST['contacto'],$defaultype));

header("Location:index.php?pagina=admin");
?>
