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
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Console do jogo</title>
<meta name="description" content="Jogos para deficientes visuais">
<meta name="keywords" content="Jogos Deficientes Visuais">
<meta name="author" content="Delermando Branquinho Filho">
<meta name="categories" content="Jogos">
<meta name="generator" content="WYSIWYG Web Builder 11 - http://www.wysiwygwebbuilder.com">
<link href="LunarScape.css" rel="stylesheet">
<link href="Console.css" rel="stylesheet">
</head>
<body>
<div id="wb_Text1" style="position:absolute;left:27px;top:53px;width:504px;height:16px;z-index:0;text-align:left;" 
>
&nbsp;<?php

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

$sql = "SELECT usuario_id, username, active FROM usuarios WHERE username = :username";
try {
	$stmt = $db -> prepare( $sql );
	$stmt->bindParam( ':username', $username );
	$stmt->bindParam( ':active', $active );
	$stmt->execute();
	$results = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $ex) {
	echo 'Erro ao ler o MySQL: ' . $ex->getMessage();
}

if($stmt->rowCount() > 0) {
   echo "[" .$results['usuario_id']. "]";
}


echo "Durante sua viagem sua nave caiu no planeta Klingon.<BR>
Você precisa encontrar ajuda. <BR>
Em frente há uma base.<BR><BR>
Você precisa voltar a terra!<BR>";


?>
</div>
<div id="wb_Text2" style="position:absolute;left:26px;top:28px;width:31px;height:16px;z-index:1;text-align:left;">
<span style="color:#000000;font-family:Arial;font-size:13px;">Olá </span></div>
<div id="wb_username" style="position:absolute;left:60px;top:28px;width:312px;height:16px;z-index:2;text-align:left;">
<?php
echo $username;
?>
&nbsp;</div>
</body>
</html>