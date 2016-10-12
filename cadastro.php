<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Planeta Klingon</title>
<meta name="description" content="Jogos para deficientes visuais">
<meta name="keywords" content="Jogos Deficientes Visuais">
<meta name="author" content="Delermando Branquinho Filho">
<meta name="categories" content="Jogos">
<meta name="generator" content="WYSIWYG Web Builder 11 - http://www.wysiwygwebbuilder.com">
<link href="LunarScape.css" rel="stylesheet">
<link href="cadastro.css" rel="stylesheet">
</head>
<body>
<div id="wb_signupform" style="position:absolute;left:22px;top:53px;width:288px;height:228px;z-index:11;">
<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['form_name'] == 'signupform')
{
$mysql_table = 'usuarios';
$success_page = './cadastro_sucesso.php';
$error_message = "";

   $newusername = $_POST['username'];
   $username = $_POST['username'];
   $newemail = $_POST['email'];
   $newpassword = $_POST['password'];
   $confirmpassword = $_POST['confirmpassword'];
   $newfullname = $_POST['fullname'];

   if ($newpassword != $confirmpassword)
   {
      $error_message = 'Senhas diferentes!';
      echo $error_message;
      echo '<BR><div id="wb_Text1" style="">
           <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
           </div>';
      exit;
   }
   if (!preg_match("/^[A-Za-z0-9_!@$]{1,50}$/", $newusername))
   {
      $error_message = 'Novo Cadastro de Viajante';
      echo $error_message;
      echo '<BR><div id="wb_Text1" style="">
           <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
           </div>';
      exit;
   }
   if (!preg_match("/^[A-Za-z0-9_!@$]{1,50}$/", $newpassword))
   {
      $error_message = 'Senha ruim, tente novamente!';
      echo $error_message;
      echo '<BR><div id="wb_Text1" style="">
           <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
           </div>';
      exit;
   }
   if (!preg_match("/^[A-Za-z0-9_!@$.' &]{1,50}$/", $newfullname))
   {
      $error_message = 'Nome Completo errado!';
      echo $error_message;
      echo '<BR><div id="wb_Text1" style="">
           <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
           </div>';
      exit;
   }

   if (!preg_match("/^.+@.+\..+$/", $newemail))
   {
      $error_message = 'E-mail indevido, verifique e tente novamente!';
      echo $error_message;
      echo '<BR><div id="wb_Text1" style="">
           <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
           </div>';
      exit;
   }


      try {
              $db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB_NAME, MYSQL_USER, MYSQL_PASSWORD );         
          } 
      catch(PDOException $ex) 
         {
              echo 'Erro ao conectar com o MySQL: ' . $ex->getMessage();
         }
      
      $sql = "SELECT username FROM LunarScape.usuarios WHERE username = :username";

      try {
          $stmt = $db -> prepare( $sql );
	  $stmt->bindParam( ':username', $username );
	  $stmt->execute();
	  $results = $stmt->fetch(PDO::FETCH_ASSOC);

      } catch(PDOException $ex) {
              echo 'Erro ao ler o MySQL: ' . $ex->getMessage();
      }

      if ($username == $results['username'])
      {
         $error_message = 'Viajante existente no planeta Klingon! <BR>Escolha outro nome de usuário';
         echo $error_message;
         echo '<BR><div id="wb_Text1" style="">
              <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
              </div>';
         exit;
      }

      $crypt_pass = md5($newpassword);
      $key_activate = rand(387,999);
      try {
         $sql = "INSERT INTO usuarios(usuario_id, fullname,email,username,senha,nivel_usuario,data_cadastro,data_ultimo_login,active,key_activate) VALUES                                       (NULL,:newfullname,:newemail,:newusername,:crypt_pass,'0','2016-01-01 00:00:00.000000', '2016-01-01 00:00:00.000000','0',:key_activate)";
         $stmt = $db->prepare($sql);
         $stmt->bindParam(':newfullname', $newfullname, PDO::PARAM_STR); 
         $stmt->bindParam(':newemail', $newemail, PDO::PARAM_STR); 
         $stmt->bindParam(':newusername', $newusername, PDO::PARAM_STR); 
         $stmt->bindParam(':crypt_pass', $crypt_pass, PDO::PARAM_STR); 
         $stmt->bindParam(':key_activate', $key_activate, PDO::PARAM_INT); 
         $result = $stmt->execute();
         } catch(PDOException $ex) {
               var_dump( $stmt->errorInfo() );
               echo "<BR> Erro ao inserir cadastro<BR>";
               echo '<BR><div id="wb_Text1" style="">
               <span style="color:#000000;font-family:Arial;font-size:13px;"><a href="./cadastro.php">Voltar ao Cadastro</a></span>
               </div>';
               exit;
               }
      $subject = 'Sua nova viagem';
      $message = 'Novo Viajangte Cadastrado, seguem suas credenciais de acesso ao mundo Klingon.';
      $message .= "\r\nUsername: ";
      $message .= $newusername;
      $message .= "\r\nPassword: ";
      $message .= $newpassword;
      $message .= "Você precisa ativar a sua conta.";
      $message .= "O número para fazer isso é:";
      $message .= $key_activate;
      $message .= "\r\n";
      $header  = "From: Cadastro@klingon.com.br"."\r\n";
      $header .= "Reply-To: Cadastro@klingon.com.br"."\r\n";
      $header .= "MIME-Version: 1.0"."\r\n";
      $header .= "Content-Type: text/plain; charset=utf-8"."\r\n";
      $header .= "Content-Transfer-Encoding: 8bit"."\r\n";
      $header .= "X-Mailer: PHP v".phpversion();
      mail($newemail, $subject, $message, $header);
      header('Location: '.$success_page);
}
?>

<form name="signupform" method="post" action="<?php echo basename(__FILE__); ?>" enctype="application/x-www-form-urlencoded" id="signupform">
<input type="hidden" name="form_name" value="signupform">
<div id="wb_Text6" style="position:absolute;left:12px;top:36px;width:113px;height:15px;text-align:right;z-index:0;">
<span style="color:#0000CD;font-family:Arial;font-size:12px;">Nome Completo:</span></div>
<input type="text" id="fullname" style="position:absolute;left:127px;top:37px;width:140px;height:18px;line-height:18px;z-index:1;" name="fullname" value="">
<div id="wb_Text7" style="position:absolute;left:12px;top:62px;width:113px;height:15px;text-align:right;z-index:2;">
<span style="color:#0000CD;font-family:Arial;font-size:12px;">Viajante:</span></div>
<input type="text" id="username" style="position:absolute;left:127px;top:61px;width:140px;height:18px;line-height:18px;z-index:3;" name="username" value="">
<div id="wb_Text8" style="position:absolute;left:12px;top:86px;width:113px;height:15px;text-align:right;z-index:4;">
<span style="color:#0000CD;font-family:Arial;font-size:12px;">Senha:</span></div>
<input type="password" id="password" style="position:absolute;left:127px;top:85px;width:140px;height:18px;line-height:18px;z-index:5;" name="password" value="">
<div id="wb_Text9" style="position:absolute;left:12px;top:110px;width:113px;height:15px;text-align:right;z-index:6;">
<span style="color:#0000CD;font-family:Arial;font-size:12px;">Confirme a Senha:</span></div>
<input type="password" id="confirmpassword" style="position:absolute;left:127px;top:109px;width:140px;height:18px;line-height:18px;z-index:7;" name="confirmpassword" value="">
<div id="wb_Text10" style="position:absolute;left:12px;top:136px;width:113px;height:15px;text-align:right;z-index:8;">
<span style="color:#0000CD;font-family:Arial;font-size:12px;">E-mail:</span></div>
<input type="text" id="email" style="position:absolute;left:127px;top:133px;width:140px;height:18px;line-height:18px;z-index:9;" name="email" value="">
<input type="submit" id="Button2" name="signup" value="Novo Viajante" style="position:absolute;left:125px;top:186px;width:90px;height:20px;z-index:10;">
</form>
</div>
<?php
   $file = fopen('counter.txt', 'r');
   $data = fread($file, filesize('counter.txt'));
   fclose($file);
   if ($data !== false)
   {
      $hits = intval($data);
      $hits++;
      $file = fopen('counter.txt', 'w');
      flock($file, LOCK_EX);
      fwrite($file, $hits);
      flock($file, LOCK_UN);
      fclose($file);
   }
   echo "$hits Viajantes passaram por aqui.<BR> Vamos fazer o seu cadastro agora:";
?>
</body>
</html>