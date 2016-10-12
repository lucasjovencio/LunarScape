<?php
$error_page = './login_err.html';
session_start();
if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['password']) == true))
{
        unset($_SESSION['username']);
        unset($_SESSION['crypt_pass']);
        header('Location: '.$error_page);
        exit;
}

$username = $_SESSION['username'];
$crypt_pass = $_SESSION['crypt_pass'];

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_name'] == 'activateform')
{
   $success_page = './Console.php';
   $error_page = './login_err.html';
   $mysql_table = 'usuarios';
   $key_activateForm =       $_POST['key_activate'];
   try {
           $db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );
       }
   catch(PDOException $ex)
      {
           echo 'Erro ao conectar com o MySQL: ' . $ex->getMessage();
      }
     
   $sql = "SELECT username, senha, key_activate, active FROM usuarios WHERE username = :username";
   try {
       $stmt = $db -> prepare( $sql );
       $stmt->bindParam( ':username', $username );
       $stmt->execute();
       $results = $stmt->fetch();
      } catch(PDOException $ex) {
              echo 'Erro ao ler o MySQL: ' . $ex->getMessage();
      }

   $usernameDB = $results['username'];
   $passwordDB = $results['senha'];
   $key_activateDB = $results['key_activate'];
   $activeDB = $results['active'];

   if($crypt_pass != $passwordDB) {
      header('Location: '.$error_page);
      exit;      
   }

   if($key_activateDB != $key_activateForm) {
      print("Você digitou um valor diferente do que está em seu e-mail<BR>");
      print("Mais tentativas erradas irá bloquear em definitivo seu cadastro ($key_activateForm)<BR>");
      }   
   if($key_activateDB == $key_activateForm) {
      $sql = "UPDATE usuarios SET active = '1' WHERE username = :username";
      try {
          $stmt = $db -> prepare( $sql );
          $stmt->bindParam( ':username', $username );
          $stmt->execute();
         } catch(PDOException $ex) {
                 echo 'Erro ao ler o MySQL: ' . $ex->getMessage();
         }
      $sql = "SELECT active FROM usuarios WHERE username = :username";
      try {
          $stmt = $db -> prepare( $sql );
          $stmt->bindParam( ':username', $username );
          $stmt->execute();
          $results = $stmt->fetch();
         } catch(PDOException $ex) {
                 echo 'Erro ao ler o MySQL: ' . $ex->getMessage();
         }
      $activeDB = $results['active'];
      if($activeDB == 0) {   
         print("ERRO: $username, sua conta não validada, tente novamente<BR>");
         print('<a href="login.php">Voltar ao Login</a>');
         exit;
         }
   }
}
header('Location: ./Console.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Ativação de conta de usuário</title>
<meta name="description" content="Jogos para deficientes visuais">
<meta name="keywords" content="Jogos Deficientes Visuais">
<meta name="author" content="Delermando Branquinho Filho">
<meta name="categories" content="Jogos">
<meta name="generator" content="WYSIWYG Web Builder 11 - http://www.wysiwygwebbuilder.com">
<link href="LunarScape.css" rel="stylesheet">
<link href="ativacao.css" rel="stylesheet">
<script>
function Validateactivateform(theForm)
{
   var regexp;
   regexp = /^[-+]?\d*\.?\d*$/;
   if (!regexp.test(theForm.key_activate.value))
   {
      alert("deve ser maior que zero e menor que 1000");
      theForm.key_activate.focus();
      return false;
   }
   if (theForm.key_activate.value != "" && !(theForm.key_activate.value > 0 && theForm.key_activate.value < 1000))
   {
      alert("deve ser maior que zero e menor que 1000");
      theForm.key_activate.focus();
      return false;
   }
   return true;
}
</script>
</head>
<body>
<div id="wb_activateform" style="position:absolute;left:21px;top:34px;width:421px;height:171px;z-index:3;">
<form name="activateform" method="post" action="<?php echo basename(__FILE__); ?>" enctype="application/x-www-form-urlencoded" id="activateform" onsubmit="return Validateactivateform(this)">
<input type="hidden" name="form_name" value="activateform">
<input type="number" id="key_activate" style="position:absolute;left:15px;top:37px;width:88px;height:18px;line-height:18px;z-index:0;" name="key_activate" value="" maxlength="1000">
<input type="submit" id="Button1" name="login" value="Entrar" style="position:absolute;left:15px;top:80px;width:70px;height:20px;z-index:1;">
<div id="wb_Text2" style="position:absolute;left:15px;top:12px;width:352px;height:16px;text-align:center;z-index:2;">
<span style="color:#0000CD;font-family:''Comic Sans MS'';font-size:13px;">Digite a chave de ativação que você recebeu em seu e-mail</span></div>
</form>
</div>
</body>
</html>