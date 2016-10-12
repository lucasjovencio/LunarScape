<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_name'] == 'loginform')
{
   $success_page = './Console.php';
   $error_page = './login_err.html';
   $mysql_table = 'usuarios';
   $username =       $_POST['username'];
   $crypt_pass = md5($_POST['password']);
   $found = false;

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

   $linhas = $stmt->rowCount();
   $usernameDB = $results['username'];
   $passwordDB = $results['senha'];
   $activeDB = $results['active'];
   $key_activateDB = $results['key_activate'];

   if($usernameDB == "") {
         header('Location: '.$error_page);
         exit;
      }
 
   session_start();
   $_SESSION['username'] = $_POST['username'];
   $_SESSION['crypt_pass'] = $crypt_pass;
   $_SESSION['key_activate'] = $key_activateDB;
   $_SESSION['active'] = $activeDB;

   $rememberme = isset($_POST['rememberme']) ? true : false;
   if ($rememberme) {
      setcookie('username', $_POST['username'], time() + 3600*2);
      setcookie('password', $crypt_pass, time() + 3600*2);
   }

   if($crypt_pass != $results['senha']) {
         header('Location: '.$error_page);
         exit;
      }
   
   if($activeDB == 0) {
      print("<BR>Sua conta não está ativada ($activeDB)<BR>");
      print('<a href="ativacao.php">Ativar agora</a>');
      }
   if($activeDB == 0) {
      $username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
      $crypt_pass = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
      header('Location: '.$success_page);
      exit;
      }
   }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Entrando no Jogo</title>
<meta name="description" content="Jogos para deficientes visuais">
<meta name="keywords" content="Jogos Deficientes Visuais">
<meta name="author" content="Delermando Branquinho Filho">
<meta name="categories" content="Jogos">
<meta name="generator" content="WYSIWYG Web Builder 11 - http://www.wysiwygwebbuilder.com">
<link href="LunarScape.css" rel="stylesheet">
<link href="login.css" rel="stylesheet">
</head>
<body>
<div id="wb_loginform" style="position:absolute;left:18px;top:58px;width:219px;height:171px;z-index:8;">
<form name="loginform" method="post" action="<?php echo basename(__FILE__); ?>" enctype="application/x-www-form-urlencoded" id="loginform">
<input type="hidden" name="form_name" value="loginform">
<div id="wb_Text2" style="position:absolute;left:54px;top:8px;width:119px;height:16px;text-align:center;z-index:0;">
<span style="color:#0000CD;font-family:''Comic Sans MS'';font-size:13px;">Continuar a Viagem</span></div>
<div id="wb_Text3" style="position:absolute;left:40px;top:43px;width:56px;height:16px;text-align:right;z-index:1;">
<span style="color:#0000CD;font-family:''Comic Sans MS'';font-size:13px;">Viajante:</span></div>
<input type="text" id="username" style="position:absolute;left:100px;top:38px;width:88px;height:18px;line-height:18px;z-index:2;" name="username" value="">
<div id="wb_Text4" style="position:absolute;left:26px;top:74px;width:71px;height:16px;text-align:right;z-index:3;">
<span style="color:#0000CD;font-family:''Comic Sans MS'';font-size:13px;">Credencial:</span></div>
<input type="password" id="password" style="position:absolute;left:100px;top:69px;width:88px;height:18px;line-height:18px;z-index:4;" name="password" value="" autocomplete="off">
<div id="wb_Text5" style="position:absolute;left:113px;top:102px;width:73px;height:16px;z-index:5;text-align:left;">
<span style="color:#0000CD;font-family:''Comic Sans MS'';font-size:13px;">Lembrar-me</span></div>
<input type="checkbox" id="Checkbox1" name="rememberme" value="on" checked style="position:absolute;left:84px;top:101px;z-index:6;">
<input type="submit" id="Button1" name="login" value="Entrar" style="position:absolute;left:77px;top:134px;width:70px;height:20px;z-index:7;">
</form>
</div>
</body>
</html>