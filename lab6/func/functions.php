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

?>