<?php

header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();

  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';

    if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
          и паролем <strong>%s</strong> для изменения данных.',
          strip_tags($_COOKIE['login']),
          strip_tags($_COOKIE['pass']));
      }
  }

  $errors = array();
  $errors['fio1'] = !empty($_COOKIE['fio_error1']);
  $errors['fio2'] = !empty($_COOKIE['fio_error2']);
  $errors['fio3'] = !empty($_COOKIE['fio_error3']);
  $errors['number'] = !empty($_COOKIE['number_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['bio1'] = !empty($_COOKIE['bio_error1']);
  $errors['bio2'] = !empty($_COOKIE['bio_error2']);
  $errors['gen1'] = !empty($_COOKIE['gen_error1']);
  $errors['gen2'] = !empty($_COOKIE['gen_error2']);
  $errors['lang1'] = !empty($_COOKIE['lang_error1']);
  $errors['lang2'] = !empty($_COOKIE['lang_error2']);
  $errors['bdate'] = !empty($_COOKIE['bdate_error']);
  $errors['checkbox'] = !empty($_COOKIE['checkbox_error']);


   if (!empty($errors['fio1'])) {
    setcookie('fio_error1', '', 100000);
    setcookie('fio_value', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
   }
   if (!empty($errors['fio2'])) {
    setcookie('fio_error2', '', 100000);
    setcookie('fio_value', '', 100000);
    $messages[] = '<div class="error">Введенное имя указано некорректно. Имя не должно превышать 128 символов.</div>';
   }
   if (!empty($errors['fio3'])) {
    setcookie('fio_error3', '', 100000);
    setcookie('fio_value', '', 100000);
    $messages[] = '<div class="error">Введенное имя указано некорректно. Имя должно содержать только буквы и пробелы.</div>';
   }

  if (!empty($errors['number'])) {
    setcookie('number_error', '', 100000);
    setcookie('number_value', '', 100000);
    $messages[] = '<div class="error">Номер не указан, либо указан некорректно.</div>';
  }

  if (!empty($errors['email'])) {
    setcookie('email_error', '', 100000);
    setcookie('email_value', '', 100000);
    $messages[] = '<div class="error">Введенный email указан некорректно.</div>';
  }

  if (!empty($errors['gen1'])) {
    setcookie('gen_error1', '', 100000);
    setcookie('gen_value', '', 100000);
    $messages[] = '<div class="error">Укажите пол.</div>';
  }
  if (!empty($errors['gen2'])) {
    setcookie('gen_error2', '', 100000);
    setcookie('gen_value', '', 100000);
    $messages[] = '<div class="error">Указан недопустимый пол.</div>';
  }

  if (!empty($errors['bio1'])) {
    setcookie('bio_error1', '', 100000);
    setcookie('bio_value', '', 100000);
    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if (!empty($errors['bio2'])) {
    setcookie('bio_error2', '', 100000);
    setcookie('bio_value', '', 100000);
    $messages[] = '<div class="error">Количество символов в поле "биография" не должно превышать 512.</div>';
  }

  if (!empty($errors['lang1'])) {
    setcookie('lang_error1', '', 100000);
    setcookie('lang_value', '', 100000);
    $messages[] = '<div class="error">Укажите любимый(ые) язык(и) программирования.</div>';
  }
  if (!empty($errors['lang2'])) {
    setcookie('lang_error2', '', 100000);
    setcookie('lang_value', '', 100000);
    $messages[] = '<div class="error">Указан недопустимый язык.</div>';
  }

  if (!empty($errors['bdate'])) {
    setcookie('bdate_error', '', 100000);
    setcookie('bdate_value', '', 100000);
    $messages[] = '<div class="error">Введите дату рождения.</div>';
  }

  if (!empty($errors['checkbox'])) {
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

// Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  if (empty($errors) && !empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
    // TODO: загрузить данные пользователя из БД
    // и заполнить переменную $values,
    // предварительно санитизовав.
    // Для загрузки данных из БД делаем запрос SELECT и вызываем метод PDO fetchArray(), fetchObject() или fetchAll() 
    // См. https://www.php.net/manual/en/pdostatement.fetchall.php
    printf('Вход с логином %s, uid %d', $_SESSION['login'], $_SESSION['uid']);
  }

  include('form.php');

  //exit();
}
else {

  $fio = $_POST['fio'];
  $num = $_POST['number'];
  $email = $_POST['email'];
  $bdate = $_POST['birthdate'];
  $biography = $_POST['biography'];
  $gen = $_POST['radio-group-1'];
  //$checkbox= $_POST['checkbox'];
  $allowed_lang = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Clojure", "Haskel", "Prolog", "Scala", "Go"];
  $languages = $_POST['languages'] ?? []; 

  $errors = FALSE;


  if (empty($fio)) {
    //print('Заполните имя.<br/>');
    setcookie('fio_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } elseif (strlen($fio) > 128 ) {
    //print('Введенное имя указано некорректно. Имя не должно превышать 128 символов.<br/>');
    setcookie('fio_error2', '2', time() + 24 * 60 * 60);
    $errors = TRUE;
  } elseif ( !preg_match('/^[a-zA-Zа-яА-ЯёЁ\s]+$/u', $fio)) {
    //print('Введенное имя указано некорректно. Имя должно содержать только буквы и пробелы.<br/>');
    setcookie('fio_error3', '3', time() + 24 * 60 * 60);
    $errors = TRUE;
  } 
  setcookie('fio_value', $fio, time() + 365 * 24 * 60 * 60);


  if (empty($num) || !preg_match('/^\+7\d{10}$/', $num)) {
    //print('Номер не указан, либо указан некорректно.<br/>');
    setcookie('number_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('number_value', $num, time() + 365 * 24 * 60 * 60);

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //print('Введенный email указан некорректно.<br/>');
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('email_value', $email, time() + 365 * 24 * 60 * 60);

  if (empty($gen)){
    //print ('Укажите пол.<br/>');
    setcookie('gen_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else{
    $allowed_genders = ["male", "female"];
    if (!in_array($gen, $allowed_genders)) {
      setcookie('gen_error2', '2', time() + 24 * 60 * 60);
      //print('Указан недопустимый пол.<br/>');
      $errors = TRUE;
    }
  }
  setcookie('gen_value', $gen, time() + 365 * 24 * 60 * 60);

  if (empty($biography)) {
    //print('Заполните биографию.<br/>');
    setcookie('bio_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } elseif(strlen($biography) > 512){
    //print('Количество символов в поле "биография" не должно превышать 512.<br/>');
    setcookie('bio_error2', '2', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('bio_value', $biography, time() + 365 * 24 * 60 * 60);

  if(empty($languages)) {
    //print('Укажите любимый(ые) язык(и) программирования.<br/>');
    setcookie('lang_error1', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else {
    foreach ($languages as $lang) {
      if (!in_array($lang, $allowed_lang)) {
          //print('Указан недопустимый язык ($lang).<br/>');
          setcookie('lang_error2', '2', time() + 24 * 60 * 60);
          $errors = TRUE;
      }
    }
  }
  $langs_value =(implode(",", $languages));
  setcookie('lang_value', $langs_value, time() + 365 * 24 * 60 * 60);

  if(empty($bdate)) {
    //print('Введите дату рождения.<br/>');
    setcookie('bdate_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('bdate_value', $bdate, time() + 365 * 24 * 60 * 60);

  if (!isset($_POST["checkbox"])) {
    //print('Подтвердите, что вы ознакомлены с контрактом.<br/>');
    setcookie('checkbox_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  setcookie('checkbox_value', $_POST["checkbox"], time() + 365 * 24 * 60 * 60);

  if ($errors) {
    header('Location: index.php');
    exit();
  }
  else {
    setcookie('fio_error1', '', 100000);
    setcookie('fio_error2', '', 100000);
    setcookie('fio_error3', '', 100000);
    setcookie('number_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('bio_error1', '', 100000);
    setcookie('bio_error2', '', 100000);
    setcookie('gen_error1', '', 100000);
    setcookie('gen_error2', '', 100000);
    setcookie('lang_error1', '', 100000);
    setcookie('lang_error2', '', 100000);
    setcookie('checkbox_error', '', 100000);
    setcookie('bdate_error', '', 100000);
  }


    // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) &&
    session_start() && !empty($_SESSION['login'])) {
    
    //select 
    $user_id;
    try {
        $stmt_select = $db->prepare("SELECT id FROM users WHERE login=?");
        $stmt_select->execute([$login]);
        $user_id = $stmt_select->fetchColumn();
    } catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }

    $fio = $_POST['fio'];
  $num = $_POST['number'];
  $email = $_POST['email'];
  $bdate = $_POST['birthdate'];
  $biography = $_POST['biography'];
  $gen = $_POST['radio-group-1'];
  //$checkbox= $_POST['checkbox'];
  $allowed_lang = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Clojure", "Haskel", "Prolog", "Scala", "Go"];
  $languages = $_POST['languages'] ?? []; 
    //update
    try {
        $stmt_update = $db->prepare("UPDATE application SET fio=?, number=?, email=?, bdate=?, gender=?, biography=?, checkbox=? WHERE id=?");
        $stmt->execute([$_POST['fio'], $_POST['number'], $_POST['email'], $_POST['birthdate'], $_POST['radio-group-1'], $_POST['biography'], isset($_POST["checkbox"]) ? 1 : 0]);
    
        $stmt_select = $db->prepare("SELECT id_lang FROM prog_lang WHERE lang_name = ?");
        $stmt_insert = $db->prepare("INSERT INTO user_lang (id, id_lang) VALUES (?, ?)");
        foreach ($languages as $language) {
            $stmt_select ->execute([$language]);
            $id_lang = $stmt_select->fetchColumn();
      
            if ($id_lang) {
                $stmt__update_lang->execute([$id_lang, $id]);
            }
        }
    } catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }

  } 
  else {
  
  $login = //md5(string $fio, bool $binary = true);
  $password = //uniqid(string $prefix = "", bool $more_entropy = false);
  // Сохраняем в Cookies.
  setcookie('login', $login);
  setcookie('password', $password);
   
  /////
  $user = 'u68607';
  $pass = '7232008';
  $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  $table_app = 'application';
  $table_lang = 'prog_lang';
  $table_ul='user_lang';

  try{
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

  try{
    $stmt_insert = $db->prepare("INSERT INTO users (login, password, role, id ) values (?, ?, ?, ?)");
    $stmt_insert->execute([ $login, $password, "user", $id]);
  } 
  catch (PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
 }
 ////
  setcookie('save', '1');
 ///

  header('Location: index.php');
}
