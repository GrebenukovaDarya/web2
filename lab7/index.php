<?php

header('Content-Type: text/html; charset=UTF-8');
require_once 'func/query.php';
require_once 'func/functions.php';

$allowed_lang=getLangs();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();

  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('password', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';

    if (!empty($_COOKIE['password'])) {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
          и паролем <strong>%s</strong> для изменения данных.',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['password']));
      }
  }

  $errors = array();
  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['number'] = !empty($_COOKIE['number_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['gen'] = !empty($_COOKIE['gen_error']);
  $errors['lang'] = !empty($_COOKIE['lang_error']);
  $errors['bdate'] = !empty($_COOKIE['bdate_error']);
  $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);

  if ($errors['fio']) {
    if($_COOKIE['fio_error']=='1'){
      $messages[] = '<div class="error">Имя не указано.</div>';
    } elseif($_COOKIE['fio_error']=='2'){
      $messages[] = '<div class="error">Введенное имя указано некорректно. Имя не должно превышать 128 символов.</div>';
    } else{
      $messages[] = '<div class="error">Введенное имя указано некорректно. Имя должно содержать только буквы и пробелы.</div>';
    }
    setcookie('fio_error', '', 100000);
    setcookie('fio_value', '', 100000);
  }

  if ($errors['number']) {
    if($_COOKIE['number_error']=='1'){
      $messages[] = '<div class="error">Номер не указан.</div>';
    } elseif($_COOKIE['number_error']=='2'){
      $messages[] = '<div class="error">Номер указан некорректно.</div>';
    }
    setcookie('number_error', '', 100000);
    setcookie('number_value', '', 100000);
  }

  if ($errors['email']) {
    if($_COOKIE['email_error']=='1') {
      $messages[] = '<div class="error">Email не указан.</div>';
    } elseif($_COOKIE['email_error']=='2') {
      $messages[] = '<div class="error">Введенный email указан некорректно.</div>';
    }
    setcookie('email_error', '', 100000);
    setcookie('email_value', '', 100000);
  }

  if ($errors['gen']) {
    if($_COOKIE['gen_error']=='1'){
      $messages[] = '<div class="error">Пол не указан.</div>';
    } elseif($_COOKIE['gen_error']=='2'){
      $messages[] = '<div class="error">Поле "пол" содержит недопустимое значение.</div>';
    }
    setcookie('gen_error', '', 100000);
    setcookie('gen_value', '', 100000);
  }

  if ($errors['bio']) {
    if($_COOKIE['bio_error']=='1'){
      $messages[] = '<div class="error">Заполните биографию.</div>';
    } elseif($_COOKIE['bio_error']=='2'){
      $messages[] = '<div class="error">Количество символов в поле "биография" не должно превышать 512.</div>';
    } elseif($_COOKIE['bio_error']=='3'){
      $messages[] = '<div class="error">Поле "биография" содержит недопустимые символы.</div>';
    }
    setcookie('bio_error', '', 100000);
    setcookie('bio_value', '', 100000);
  }

  if ($errors['lang']) {
    if($_COOKIE['lang_error']=='1'){
      $messages[] = '<div class="error">Укажите любимый(ые) язык(и) программирования.</div>';
    } elseif($_COOKIE['lang_error']=='2'){
      $messages[] = '<div class="error">Указан недопустимый язык.</div>';
    }
    setcookie('lang_error', '', 100000);
    setcookie('lang_value', '', 100000);
  }

  if ($errors['bdate']) {
    setcookie('bdate_error', '', 100000);
    setcookie('bdate_value', '', 100000);
    $messages[] = '<div class="error">Дата рождения не указана.</div>';
  }

  if ($errors['checkbox']) {
    setcookie('checkbox_error', '', 100000);
    setcookie('checkbox_value', '', 100000);
    $messages[] = '<div class="error">Подтвердите, что вы ознакомлены с контрактом.</div>';
  }

  $values = array();
  $values['fio'] = empty($_COOKIE['fio_value']) ? '' : strip_tags($_COOKIE['fio_value']);
  $values['number'] = empty($_COOKIE['number_value']) ? '' : strip_tags($_COOKIE['number_value']);
  $values['email'] = empty($_COOKIE['email_value']) ? '' : strip_tags($_COOKIE['email_value']);
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : strip_tags($_COOKIE['bio_value']);
  $values['gen'] = empty($_COOKIE['gen_value']) ? '' : strip_tags($_COOKIE['gen_value']);
  $values['lang'] = empty($_COOKIE['lang_value']) ? '' : strip_tags($_COOKIE['lang_value']);
  $values['bdate'] = empty($_COOKIE['bdate_value']) ? '' : strip_tags($_COOKIE['bdate_value']);
  $values['checkbox'] = empty($_COOKIE['checkbox_value']) ? '' : strip_tags($_COOKIE['checkbox_value']);
  
  //insert values
  if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) &&
  admin_login_check($_SERVER['PHP_AUTH_USER']) && admin_password_check($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    if(!empty($_GET['uid']))
    {
      $update_id = $_GET['uid'];
      $log=getlogin($update_id);
      $values=INSERTData($log);
      $values['uid']=$update_id;
    }
  }
  if (isset($_COOKIE[session_name()]) && session_start() &&!empty($_SESSION['login'])) { 
    $_SESSION['uid']=getUID($_SESSION['login']);

    $values=INSERTData($_SESSION['login']);
    
    $login_message='Успешная авторизация'; //'Вход с логином: '. $_SESSION['login'] . ", uid: ". $_SESSION['uid'];
    $messages[] = $login_message;
  }
  /*

    if (!empty($_SESSION['login'])) {

      try{
        $mas=[];

        $stmt = $db->prepare("SELECT fio, number, email, biography AS bio, gender AS gen, bdate, checkbox FROM application WHERE id = ?");
        $stmt->execute([$_SESSION['uid']]);
        $mas = $stmt->fetch(PDO::FETCH_ASSOC);
        $fields = ['fio', 'number', 'email', 'bio', 'gen', 'bdate', 'checkbox'];
        foreach($fields as $field) {
            $values[$field] = strip_tags($mas[$field]);
        }
      } 
      catch (PDOException $e){
        print('ERROR : ' . $e->getMessage());
        exit();
      }

      $sql = "select pl.lang_name from prog_lang pl JOIN user_lang ul ON pl.id_lang=ul.id_lang where ul.id = :login;";
          try{
              $stmt = $db->prepare($sql);
              $stmt->bindValue(':login', $_SESSION['uid'], PDO::PARAM_STR);
              $stmt->execute();
              $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
              $langs_value1 =(implode(",", $lang));
              $values['lang']=$langs_value1;
          }
          catch(PDOException $e){
              print('Error : ' . $e->getMessage());
              exit();
          }

      $login_message='Вход с логином: '. $_SESSION['login'] . ", uid: ". $_SESSION['uid'];
      $messages[] = $login_message;
    }
  */

  include('pages/form_page.php');
}
else {

  if (!validateCsrfToken()) {
    http_response_code(403); 
   
  }

  $fio = $_POST['fio'];
  $num = $_POST['number'];
  $email = $_POST['email'];
  $bdate = $_POST['birthdate'];
  $biography = $_POST['biography'];
  $gen = $_POST['radio-group-1'];
  $languages = $_POST['languages'] ?? []; 

  $errors = FALSE;

  if (empty($fio)) {
    setcookie('fio_error', '1');
    $errors = TRUE;
  } elseif (strlen($fio) > 128 ) {
    setcookie('fio_error', '2');
    $errors = TRUE;
  } elseif ( !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $fio)) {
    setcookie('fio_error', '3');
    $errors = TRUE;
  }
  setcookie('fio_value', $fio, time() + 365 * 24 * 60 * 60);


  if (empty($num)) {
    setcookie('number_error', '1');
    $errors = TRUE;
  } elseif (!preg_match('/^\+7\d{10}$/', $num)) {
    setcookie('number_error', '2');
    $errors = TRUE;
  }
  setcookie('number_value', $num, time() + 365 * 24 * 60 * 60);

  if (empty($email) ) {
    setcookie('email_error', '1');
    $errors = TRUE;
  } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    setcookie('email_error', '2');
    $errors = TRUE;
  }
  setcookie('email_value', $email, time() + 365 * 24 * 60 * 60);

  if (empty($gen)){
    setcookie('gen_error', '1');
    $errors = TRUE;
  } else{
    $allowed_genders = ["male", "female"];
    if (!in_array($gen, $allowed_genders)) {
      setcookie('gen_error', '2');
      $errors = TRUE;
    }
  }
  setcookie('gen_value', $gen, time() + 365 * 24 * 60 * 60);

  if (empty($biography)) {
    setcookie('bio_error', '1');
    $errors = TRUE;
  } elseif(strlen($biography) > 512){
    setcookie('bio_error', '2');
    $errors = TRUE;
  } elseif(preg_match('/[<>{}\[\]]|<script|<\?php/i', $biography)){
    setcookie('bio_error', '3');
    $errors = TRUE;
  }
  setcookie('bio_value', $biography, time() + 365 * 24 * 60 * 60);

  if(empty($languages)) {
    setcookie('lang_error', '1');
    $errors = TRUE;
  } else {
    foreach ($languages as $lang) {
      if (!in_array($lang, $allowed_lang)) {
          setcookie('lang_error', '2');
          $errors = TRUE;
      }
    }
  }
  $langs_value =(implode(",", $languages));
  setcookie('lang_value', $langs_value, time() + 365 * 24 * 60 * 60);

  if(empty($bdate)) {
    setcookie('bdate_error', '1');
    $errors = TRUE;
  }
  setcookie('bdate_value', $bdate, time() + 365 * 24 * 60 * 60);

  if (!isset($_POST["checkbox"])) {
    setcookie('checkbox_error', '1');
    $errors = TRUE;
  }
  setcookie('checkbox_value', $_POST["checkbox"], time() + 365 * 24 * 60 * 60);


  if ($errors) {
    if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) &&
    admin_login_check($_SERVER['PHP_AUTH_USER']) && admin_password_check($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']))
  {
    header('Location: index.php?uid=' . $_POST['uid'] . '');
    exit();
  }
  else{
    header('Location: index.php');
      exit();
  }
}
  else {
    setcookie('fio_error', '', 100000);
    setcookie('number_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('gen_error', '', 100000);
    setcookie('lang_error', '', 100000);
    setcookie('checkbox_error', '', 100000);
    setcookie('bdate_error', '', 100000);
  }


    // Проверяем меняются ли ранее сохраненные данные или отправляются новые.

  if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW']) &&
  admin_login_check($_SERVER['PHP_AUTH_USER']) && admin_password_check($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
  
    if(!empty($_POST['uid'])) {
      $user_id = $_POST['uid'];
      $languages = $_POST['languages'] ?? [];
      UPDATE($user_id, $_POST['fio'], $_POST['number'], $_POST['email'], $_POST['birthdate'], $_POST['radio-group-1'], $_POST['biography'], isset($_POST["checkbox"]) ? 1 : 0, $languages);
      header('Location: admin.php');
      
      exit();
    } else{
      print('Пользователь для изменения не выбран');
      exit();
    }
  
  } else {

    if (isset($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
      try {
        $user_id=getUID($_SESSION['login']);
        UPDATE($user_id, $_POST['fio'], $_POST['number'], $_POST['email'], $_POST['birthdate'], $_POST['radio-group-1'], $_POST['biography'], isset($_POST["checkbox"]) ? 1 : 0, $languages);
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
      }
    } else {
      $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

      $login = substr(md5(time()), 0, 16);
      while(isValid($login)){
        $login = substr(md5(time()), 0, 16);
      }
      $password = substr(str_shuffle($permitted_chars), 0, 12);
      $hash_password = password_hash($password, PASSWORD_DEFAULT);
      
      setcookie('login', htmlspecialchars($login, ENT_QUOTES, 'UTF-8'));
      setcookie('password', htmlspecialchars($password, ENT_QUOTES, 'UTF-8'));
      try {
        INSERT($login, $hash_password);
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
      }
    }

  }

  setcookie('save', '1');
  header('Location: ./');
}
