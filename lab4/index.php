<?php

header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (!empty($_GET['save'])) {
    print('Спасибо, результаты сохранены.');
  }

  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }



  include('form.php');
  exit();
}
else {

  $fio = $_POST['fio'];
  $num = $_POST['number'];
  $email = $_POST['email'];
  $bdate = $_POST['birthdate'];
  $biography = $_POST['biography'];
  $gen = $_POST['radio-group-1'];
  //$checkbox= $_POST['checkbox'];
  $languages = $_POST['languages'] ?? []; 

  $errors = FALSE;


  if (empty($fio)) {
    print('Заполните имя.<br/>');
    setcookie('fio_error', $fio, time() + 24 * 60 * 60);
    $errors = TRUE;
  } elseif (strlen($fio) > 128 || !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $fio)) {
    print('Введенное имя указано некорректно.<br/>');
    setcookie('fio_error', $num, time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (empty($num) || !is_numeric($num) || ) { //  +78\d{10}
    //print('Номер не указан, либо указан некорректно. Указываемый номер должен содерать 10 цифр.<br/>');
    setcookie('number_error', $email, time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //print('Введенный email указан некорректно.<br/>');
    setcookie('email_error', $bdate, time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (empty($_POST['biography'])) {
    //print('Заполните биографию.<br/>');
    setcookie('bio_error', $biography, time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if(empty($languages)) {
    //print('Укажите любимый(ые) язык(и) программирования.<br/>');
    setcookie('lang_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if(empty($bdate)) {
    //print('Введите дату рождения.<br/>');
    setcookie('bdate_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (!isset($_POST["checkbox"])) {
    //print('Подтвердите, что вы ознакомлены с контрактом.<br/>');
    setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if ($errors) {
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('number_error', '', 100000);
    setcookie('bdate_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('checkbox_error', '', 100000);
    setcookie('lang_error', '', 100000);
  }
  //

  $user = 'u68607';
  $pass = '7232008';
  $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  $table_app = 'application';
  $table_lang = 'prog_lang';
  $table_ul='user_lang';

  try{
    //$data = array( 'fio' => $fio, 'num' => $num, 'email' => $email, 'bdate' => $bdate, 'gen' => $gen, 'biography' => $biography, 'checkbox' => $_POST["checkbox"]); 
    $stmt = $db->prepare("INSERT INTO application (fio, number, email, bdate, gender, biography, checkbox ) values (?, ?, ?, ?, ?, ?, ? )");
    $stmt->execute([$_POST['fio'], $_POST['number'], $_POST['email'], $_POST['birthdate'], $_POST['radio-group-1'], $_POST['biography'], isset($_POST["checkbox"]) ? 1 : 0]);
  } 
  catch (PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }

  $id=$db->lastInsertId();
  try{
  
    $stmt_select = $db->prepare("SELECT id_lang FROM prog_lang WHERE lang_name = ?");
    $stmt_insert = $db->prepare("INSERT INTO user_lang (id, id_lang) VALUES (?, ?)");
    foreach ($languages as $language) {
      $stmt_select ->execute([$language]);
      $id_lang = $stmt_select->fetchColumn();
      
      if ($id_lang) {
        $stmt_insert->execute([$id, $id_lang]);
      }
    }
  } 
  catch (PDOException $e) {
    print('Error : ' . $e->getMessage());
    exit();
  }


  //header('Location: ?save=1');
  header('Location: index.php');
}