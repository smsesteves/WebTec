<?php 
    session_start();
    
    if(!isset($_SESSION['login']) || $_SESSION['login'] == FALSE){
        header('Location:index.php');
    }
?>

<?php 
if(isset($_POST['email'])) {
     
    // CHANGE THE TWO LINES BELOW
    $email_to = "ltw2013T2G4@dispostable.com";
     
    $email_subject = "Sistema Faturação Online";
     
     
    function died($error) {
        // your error code can go here
        echo "Erros no envio da mensagem: <br>";
        echo $error."<br /><br />";
        echo "Ser&aacute; redirecionado para o site dentro de momentos.<br /><br />";
    }
     
    // validation expected data exists
    if(!isset($_POST['first_name']) ||
        !isset($_POST['last_name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['telephone']) ||
        !isset($_POST['comments'])) {
        died('Houve um problema com o envio da mensagem.');       
    }
     
    $first_name = $_POST['first_name']; // required
    $last_name = $_POST['last_name']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['telephone']; // not required
    $comments = $_POST['comments']; // required
     
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/'; // pesquisa online
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= '>> O email que introduziu n&atilde;o &eacute; v&aacute;lido.<br />';
  }
    $string_exp = "/^[A-Za-z .'-]+$/";
  if(!preg_match($string_exp,$first_name)) {
    $error_message .= '>> O nome que introduziu n&atilde;o &eacute; v&aacute;lido.<br />';
  }
  if(!preg_match($string_exp,$last_name)) {
    $error_message .= '>> O apelido que introduziu n&atilde;o &eacute; v&aacute;lido.<br />';
  }
  if(strlen($comments) < 2) {
    $error_message .= '>> A mensagem introduzida n&atilde;o &eacute; v&aacute;lida.<br />';
  }
  if(strlen($error_message) > 0) {
    died($error_message);
  }
    $email_message = "Detalhes:.\n\n";
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
     
    $email_message .= "Nome: ".clean_string($first_name)."\n";
    $email_message .= "Apelido: ".clean_string($last_name)."\n";
    $email_message .= "Email: ".clean_string($email_from)."\n";
    $email_message .= "Telefone: ".clean_string($telephone)."\n";
    $email_message .= "Mensagem: ".clean_string($comments)."\n";
     
     
// create email headers
$headers = 'De: '.$email_from."\r\n".
'Responder para: '.$email_from."\r\n" .
'X-Mailer: PHP/' . phpversion();
@mail($email_to, $email_subject, $email_message, $headers);  


?>
 <body onload="timer=setTimeout(function(){ window.location='http://paginas.fe.up.pt/~ei11090/Projeto1/index.php';}, 5000)">
<p>Obrigado pelo seu contato!</p>
</body>

 
<?php 
}


?>