<?php

require_once 'func/query.php';
require_once 'func/functions.php';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $user_log=$_SERVER['PHP_AUTH_USER'];
  $user_pass=$_SERVER['PHP_AUTH_PW'];
  
  if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      !admin_login_check($user_log) ||
      !admin_password_check($user_log, $user_pass)) {

    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  }

  print('<div class="">Вы успешно авторизовались и видите защищенные паролем данные.<div>');

  $language_table = language_stats();
  $user_table = users_table();

  include('table.php');
}
else {

  if(!empty($_POST['del_by_uid']) && !empty($_SERVER['PHP_AUTH_USER'])){
    delete_by_uid($_POST['del_by_uid']);
    /*
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
    */
  } 

  header('Location: admin.php');
}
