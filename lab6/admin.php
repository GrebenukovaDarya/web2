<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
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
