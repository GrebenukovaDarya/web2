<?php
function isValid($login, $db) {
  $check = false;
  try{
    $stmt = $db->prepare("SELECT login FROM users WHERE role='admin'");
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
      if($login == $row->login){
        $check=true;
      }
    }
  } 
  catch (PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  return $check;
}

function password_check($login, $password, $db) {
  $passw;
  try{
    $stmt = $db->prepare("SELECT password FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $passw = $stmt->fetchColumn();
    if($passw===false){
      return false;
    }
    return password_verify($password, $passw);
  } 
  catch (PDOException $e){
    print('Error : ' . $e->getMessage());
    return false;
  }
  
}



if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $user = 'u68607';
  $pass = '7232008';
  $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    

  
  if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      !isValid($_SERVER['PHP_AUTH_USER']) ||
      !password_check($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'], $db)) {

    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  }

  print('<div class="">Вы успешно авторизовались и видите защищенные паролем данные.<div>');

  include('table.php');
}
else {

}
