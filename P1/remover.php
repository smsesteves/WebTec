<?php

if(isset($_GET['id'])){
    $sql = 'DELETE FROM users WHERE id = ' . $_GET['id'];
    
    $db = new PDO('sqlite:db/db_t1.db');
    $stmt = $db->prepare($sql);
    $stmt->execute();
}

header("Location:index.php?pagina=admin");

?>