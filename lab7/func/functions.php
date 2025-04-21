<?php

function password_check($login, $password) {
    global $db;
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
function admin_password_check($login, $password) {
    global $db;
    $passw;
    try{
      $stmt = $db->prepare("SELECT password FROM users WHERE login = ? AND role='admin' ");
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
function admin_login_check($login) {
    global $db;
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
function isValid($login) {
    global $db;
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

function getLangs(){
    global $db;
    try{
      $allowed_lang=[];
      $data = $db->query("SELECT lang_name FROM prog_lang")->fetchAll();
      foreach ($data as $lang) {
        $lang_name = $lang['lang_name'];
        $allowed_lang[$lang_name] = $lang_name;
      }
      return $allowed_lang;
    } catch(PDOException $e){
      print('Error: ' . $e->getMessage());
      exit();
    }
}



function generateCsrfToken() {
  if (function_exists('random_bytes')) {
    $token = bin2hex(random_bytes(32));
  } else {
    $token = md5(uniqid(rand(), true)); 
  }
  $_SESSION['csrf_token'] = $token;
  $_SESSION['csrf_token_time'] = time();
  return $token;
}

function validateCsrfToken() {
  if (empty($_POST['csrf_token'])) {
    return false; 
  }

  if (empty($_SESSION['csrf_token'])) {
    return false; 
  }

  if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    return false; 
  }

  $token_age = time() - $_SESSION['csrf_token_time'];
  if ($token_age > 3600) {
    return false; 
  }

  unset($_SESSION['csrf_token']); 
  unset($_SESSION['csrf_token_time']);

  return true;
}

function generateCsrfToken2($form_id) {
  if (!isset($_SESSION['csrf_tokens'])) {
      $_SESSION['csrf_tokens'] = [];
  }
if (empty($_SESSION['csrf_token'])){
  $_SESSION['csrf_tokens'][$form_id] = bin2hex(random_bytes(32));
}
  
  return $_SESSION['csrf_tokens'][$form_id];
}


function validateCsrfToken2($form_id, $token) {
  if (!isset($_SESSION['csrf_tokens'][$form_id])) {
      return false; // Нет токена для этой формы
  }

  if (!hash_equals($_SESSION['csrf_tokens'][$form_id], $token)) {
      return false; // Токены не совпадают
  }

  unset($_SESSION['csrf_tokens'][$form_id]); // Удаляем токен только для этой формы

  return true;
}

?>
