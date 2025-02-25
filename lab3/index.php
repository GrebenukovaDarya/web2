<?php

header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  if (!empty($_GET['save'])) {
    print('Спасибо, результаты сохранены.');
  }

  include('form.php');
  exit();
}

$user = 'u68607';
$pass = '7232008';
$db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

$fio = $_POST['fio'];
$num = $_POST['number'];
$email = $_POST['email'];
$bdate = $_POST['birthdate'];
$biography = $_POST['biography'];
$gen = $_POST['radio-group-1'];
//$checkbox= $_POST['checkbox'];
//$allowed_lang = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Clojure", "Haskel", "Prolog", "Scala", "Go"];
  $allowed_lang = [];
  try{
    $data = $db->query("SELECT lang_name FROM prog_lang")->fetchAll();
    foreach ($data as $lang) {
      $lang_name = $lang['lang_name'];
      $allowed_lang[$lang_name] = $lang_name;
    }
  } catch(PDOException $e){
    print('Error: ' . $e->getMessage());
    exit();
  }
$languages = $_POST['languages'] ?? []; 

$errors = FALSE;

if (empty($fio)) {
  print('Имя не указано.<br/>');
  $errors = TRUE;
} elseif (strlen($fio) > 128 ) {
  print('Введенное имя указано некорректно. Имя не должно превышать 128 символов.<br/>');
  $errors = TRUE;
} elseif ( !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $fio)) {
  print('Введенное имя указано некорректно. Имя должно содержать только буквы и пробелы.<br/>');
  $errors = TRUE;
}


if (empty($num)) {
  print('Номер не указан.<br/>');
  $errors = TRUE;
} elseif (!preg_match('/^\+7\d{10}$/', $num)) {
  print('Номер указан некорректно.<br/>');
  $errors = TRUE;
}

if (empty($email) ) {
  print('Email не указан.<br/>');
  $errors = TRUE;
} elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
  print('Введенный email указан некорректно.<br/>');
  $errors = TRUE;
}

if (empty($gen)){
  print ('Пол не указан.<br/>');
  $errors = TRUE;
}
else{
  $allowed_genders = ["male", "female"];
  if (!in_array($gen, $allowed_genders)) {
    print('Поле "пол" содержит недопустимое значение.<br/>');
    $errors = TRUE;
  }
}

if (empty($biography)) {
  print('Заполните биографию.<br/>');
  $errors = TRUE;
} elseif(strlen($biography) > 512){
  print('Количество символов в поле "биография" не должно превышать 512.<br/>');
  $errors = TRUE;
} elseif(!preg_match('/^[а-яА-Яa-zA-Z1-9.,: ]+$/u', $biography)){
  print('Поле "биография" содержит недопустимые символы.<br/>');
  $errors = TRUE;
}

if(empty($languages)) {
  print('Укажите любимый(ые) язык(и) программирования.<br/>');
  $errors = TRUE;
} else {
  foreach ($languages as $lang) {
    if (!in_array($lang, $allowed_lang)) {
        print('Указан недопустимый язык ($lang).<br/>');
        $errors = TRUE;
    }
  }
}

if(empty($bdate)) {
  print('Дата рождения не указана.<br/>');
  $errors = TRUE;
}

if (!isset($_POST["checkbox"])) {
  print('Подтвердите, что вы ознакомлены с контрактом.<br/>');
  $errors = TRUE;
}
  

if ($errors) {
  exit();
}
//

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


header('Location: ?save=1');