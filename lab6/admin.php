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
    
  $user_log=$_SERVER['PHP_AUTH_USER'];
  $user_pass=$_SERVER['PHP_AUTH_PW'];
  
  if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      !isValid($user_log, $db) ||
      !password_check($user_log, $user_pass, $db)) {

    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  }

  print('<div class="">Вы успешно авторизовались и видите защищенные паролем данные.<div>');

  include('table.php');
}
else {
  $user = 'u68607';
  $pass = '7232008';
  $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
  if(!empty($_POST['del_by_uid'])){
    try{
      $stmt_delete_lang = $db->prepare("DELETE FROM user_lang WHERE id=?");
      $stmt_delete_application = $db->prepare("DELETE FROM application WHERE id=?");
      $stmt_delete_user = $db->prepare("DELETE FROM users WHERE id=?");
      $stmt_delete_lang->execute([$_POST['del_by_uid']]);
      $stmt_delete_user->execute([$_POST['del_by_uid']]);
      $stmt_delete_application->execute([$_POST['del_by_uid']]);
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
  } 
}