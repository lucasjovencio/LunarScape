<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Cadastrobem sucedido!</title>
<meta name="description" content="Jogos para deficientes visuais">
<meta name="keywords" content="Jogos Deficientes Visuais">
<meta name="author" content="Delermando Branquinho Filho">
<meta name="categories" content="Jogos">
<meta name="generator" content="WYSIWYG Web Builder 11 - http://www.wysiwygwebbuilder.com">
<link href="LunarScape.css" rel="stylesheet">
<link href="cadastro_sucesso.css" rel="stylesheet">
</head>
<body>
<div id="wb_Text1" style="position:absolute;left:15px;top:26px;width:345px;height:96px;z-index:0;text-align:left;">
<span style="color:#000000;font-family:Arial;font-size:13px;">Você recebeu um e-mail contendo instruções de acesso.<br>Certifique-se que seu antispam não bloqueou a mensagem.<br><br>Sucesso em sua nova viagem!!!<br></span>
<?php

$error_page = './login_err.html';
session_start();
if((!isset ($_SESSION['username']) == true) and (!isset ($_SESSION['password']) == true))
{
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        header('Location: '.$error_page);
        exit;
}

$username = $_SESSION['username'];
$password = $_SESSION['password'];

include 'config.php';

try {
	$db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );
        }
catch(PDOException $ex) {
	echo 'Erro ao conectar com o MySQL: ' . $ex->getMessage();
        }

$sql = "SELECT * FROM usuarios WHERE username = :username";
try {
	$stmt = $db -> prepare( $sql );
	$stmt->bindParam( ':username', $username );
	$stmt->execute();
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
	echo 'Erro ao ler o MySQL: ' . $ex->getMessage();
}

if($stmt->rowCount() > 0) {
   $usuario_id = $results['usuario_id'];
   echo "Identificação: " .$usuario_id. " ";
   $sql_pos = "SELECT * FROM posicao WHERE usuario_id = :usuario_id";
   try {
   	$stmt_pos = $db -> prepare( $sql_pos );
   	$stmt_pos->bindParam( ':username', $username );
   	$stmt_pos->execute();
   	$results_pos = $stmt_pos->fetch(PDO::FETCH_ASSOC);
      } catch(PDOException $ex_pos) {
	   echo 'Erro ao ler o MySQL: ' . $ex_pos->getMessage();
      }
   if($stmt_pos->rowCount() == 0) {
      echo "Username: " .$username. " ";
      }   
   }

?>
</div>
<div id="wb_Text2" style="position:absolute;left:32px;top:167px;width:250px;height:16px;z-index:1;text-align:left;">
<span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./login.php">Entrar no Jogo</a></span></div>
</body>
</html>