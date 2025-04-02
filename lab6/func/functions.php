<?php

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

?>