
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
  include('pages/login_page.php');
}
else {
  $login_messages='';
  $login = $_POST['login'];
  $password=$_POST['password'];

  if (!$session_started) {
    session_start();
  }

  if (isValid($login) && password_check($login, $password)){
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['uid'] = getUID([$_SESSION['login']]);

      header('Location: ./');
  }
  else {
    echo "<div class=\"login_error_message\"> Неверный логин или пароль </div>"; 
  }

}

//74bc3035e851f79e
//L216KTE3Ro0l

//e276f5ee3157eee3
//sdBjMmreS1Vo

//e73e6c89a1338a3e
//2TY4CB8RNjG6
