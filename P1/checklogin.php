<?php 

ob_start(); // bufferiza a mensagem

$db = new PDO('sqlite:db/db_t1.db');

$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

if (get_magic_quotes_gpc()) {
    $myusername = sqlite_escape_string($myusername);
    $mypassword = sqlite_escape_string($mypassword);
}

$sql = "SELECT * FROM users WHERE username='$myusername'";

$stmt = $db->prepare($sql);
$stmt->execute();
$result = $stmt->fetch();

if ($result != FALSE && $result['password'] == md5($mypassword)) {
    session_start();
    $_SESSION['login'] = TRUE;
    $_SESSION['username'] = $myusername;
    $_SESSION['role_id'] = $result['role_id'];
    header("Location:login_success.php");
} else {
    header("Location:index.php");
}

?>
