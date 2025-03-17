
<?php
header('Content-Type: text/html; charset=UTF-8');

function isValid($login, $db) {
  $count;
  try{
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $count = $stmt->fetchColumn();
  } 
  catch (PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  return $count > 0;
}

function password_check($login, $password, $db) {
  $passw;
  try{
    $stmt = $db->prepare("SELECT password FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $passw = $stmt->fetchColumn();
    //print($password);
    if($passw===false){
      return false;
    }
    //print(" ");
    //print($passw);
    return password_verify($password, $passw);
  } 
  catch (PDOException $e){
    print('Error : ' . $e->getMessage());
    return false;
  }
  
}

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
  
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="style.css">
    <title> LAB5 </title>
  </head>
  <body>

  <?php
      if (!empty($messages)) {
        print('<div id="login_messages">');
        foreach ($messages as $message) {
          print($message);
        }
        print('</div>');
      }
      ?>

    <form class="login_form" action="" method="post">
      <label> 
        Логин <br/>
        <input name="login" />
      </label> <br/>
      <label> 
        Пароль <br/>
        <input name="password" />
      </label> <br/>
      <input class="login_button" type="submit" value="Войти" />
    </form>

  </body>
</html>

<?php
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
    $messages[] = 'Неверный логин или пароль';
    //$login_messages="<div class='login_messages'>Неверный логин или пароль</div>";
    //header('Location: login.php');
    print('Неверный логин или пароль'); 
    //exit();
  }


}