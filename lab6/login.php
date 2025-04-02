
<?php
header('Content-Type: text/html; charset=UTF-8');

require_once 'func/query.php';
require_once 'func/functions.php';

$session_started = false;

if (isset($_COOKIE[session_name()]) && session_start()) {
  $session_started = true;
  if (!empty($_SESSION['login'])) {

    if(isset($_POST['logout'])){
      session_unset();
      session_destroy();
      header('Location: login.php');
      exit();
    }

    header('Location: ./');
    exit();
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  include('login_page.php');
}
else {
  $login_messages='';
  $login = $_POST['login'];
  //$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $password=$_POST['password'];

  $user = 'u68607';
  $pass = '7232008';
  $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  if (!$session_started) {
    session_start();
  }

  if (isValid($login, $db) && password_check($login, $password, $db)){
    $_SESSION['login'] = $_POST['login'];

    $_SESSION['uid'];
      try {
          $stmt_select = $db->prepare("SELECT id FROM users WHERE login=?");
          $stmt_select->execute([$_SESSION['login']]);
          $_SESSION['uid']  = $stmt_select->fetchColumn();
      } catch (PDOException $e){
          print('Error : ' . $e->getMessage());
          exit();
      }

      header('Location: ./');
  }
  else {
    print('Неверный логин или пароль'); 
  }


}
