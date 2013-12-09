<?php



session_start();


$enable_edit = false;
$adm = false;

$db = new PDO('sqlite:db/db_t1.db');

if($_SESSION['role_id'] == 0)
	$adm = true;


if($adm)
	$usuario = $_POST['username'];
else
{
if($_POST['username'] == $_SESSION['username'])
	$enable_edit = true;
}



if($enable_edit || $adm)
{
	$sql = "UPDATE users SET nome=?,email=?,morada=?,contacto=?";

	if($adm){
	    $sql .= ",role_id=?";
	}

	$sql .= " WHERE username=?";

	//echo $sql;



	$stmt = $db->prepare($sql);
	if($adm)
	{
		if($_SESSION['username']==$_POST['username'])
		{
			$stmt->execute(array($_POST['nome'],$_POST['email'],$_POST['morada'],$_POST['contacto'],$_POST['role_id'],$usuario));
			$_SESSION['role_id']=$_POST['role_id'];
		}
			
		else
			$stmt->execute(array($_POST['nome'],$_POST['email'],$_POST['morada'],$_POST['contacto'],$_POST['role_id'],$usuario));
	}
		
	else 
		$stmt->execute(array($_POST['nome'],$_POST['email'],$_POST['morada'],$_POST['contacto'], $usuario));
}


if ($usuario == $_SESSION['username']) {
    header("Location:index.php?pagina=painel_usuario");
}
else{
    header("Location:index.php?pagina=admin");
}

?>