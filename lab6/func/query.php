<?php

global $db;
$user = 'u68607';
$pass = '7232008';
$db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

function language_stats(){
    global $db;
    $rows = array();
    try {
        $stmt = $db->prepare("SELECT lang_name, count(id) AS stat FROM user_lang JOIN prog_lang USING (id_lang) GROUP BY id_lang");
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
            $rows[] = "<tr><td>$row->lang_name</td><td>$row->stat</td></tr>";
        }
    }
    catch (PDOException $e){
        print('ERROR : ' . $e->getMessage());
        exit();
    }
    return $rows;
}

function users_table(){
    global $db;
    $rows = array();
    try{
        $stmt = $db->prepare("SELECT login, id, role FROM users WHERE role='user' ORDER BY (id) desc");
        $stmt->execute();
        $log;
        $uid;
        while($row = $stmt->fetch(PDO::FETCH_OBJ)){
            $log=$row->login;
            $uid=$row->id;
            $r = "<tr><td>$uid</td><td>$log</td>";

            $form_data = $db->prepare("SELECT fio, number, email, gender AS gen, biography AS bio, bdate, checkbox FROM application WHERE id = ?");
            $form_data->execute([$uid]);
            $mas = $form_data->fetch(PDO::FETCH_ASSOC);
            foreach($mas as $field) {
                $r.="<td>$field</td>";
            }

            $sql = "select pl.lang_name from prog_lang pl JOIN user_lang ul ON pl.id_lang=ul.id_lang where ul.id = :login;";
            
                $stmt_lang = $db->prepare($sql);
                $stmt_lang->bindValue(':login', $uid, PDO::PARAM_STR);
                $stmt_lang->execute();
                $lang = $stmt_lang->fetchAll(PDO::FETCH_COLUMN, 0);
                $langs_value1 =(implode(", ", $lang));
                $r.="<td>$langs_value1</td>";

            $r.="<td class=\"buttons\">
            <form action=\"admin.php\" method=\"POST\">
            <input type=\"hidden\" name=\"del_by_uid\" value=\"$uid\">
            <input class=\"delete_button\" type=\"submit\" value=\"удалить\">
            </form>";

            $r.="<br><div class=\"change_button\">
            <a href=\"index.php?uid=$row->id\">Изменить</a>
            </div></td></tr>";

            $rows[]=$r;
        }
    } 
    catch (PDOException $e){
        print('ERROR : ' . $e->getMessage());
        exit();
    }
    return $rows;
}

function del_by_uid($uid){
    global $db;
    try{
        $stmt_delete_lang = $db->prepare("DELETE FROM user_lang WHERE id=?");
        $stmt_delete_application = $db->prepare("DELETE FROM application WHERE id=?");
        $stmt_delete_user = $db->prepare("DELETE FROM users WHERE id=?");
        $stmt_delete_lang->execute([$uid]);
        $stmt_delete_user->execute([$uid]);
        $stmt_delete_application->execute([$uid]);
      }
    catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
}

function getUID($login){
    global $db;
    $uid;
    try {
        $stmt_select = $db->prepare("SELECT id FROM users WHERE login=?");
        $stmt_select->execute([$login]);
        $uid = $stmt_select->fetchColumn();
    } catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
    return $uid;
}
function getlogin($uid){
    global $db;
    $login;
    try {
        $stmt_select = $db->prepare("SELECT login FROM users WHERE id=?");
        $stmt_select->execute([$uid]);
        $login = $stmt_select->fetchColumn();
    } catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
    return $login;
}

function UPDATE($user_id, $fio, $number, $email, $bdate, $gen, $bio, $check, $languages){
    global $db;
    try {
        $stmt_update = $db->prepare("UPDATE application SET fio=?, number=?, email=?, bdate=?, gender=?, biography=?, checkbox=? WHERE id=?");
        $stmt_update->execute([$fio, $number, $email, $bdate, $gen, $bio, $check, $user_id ]);
    
        $stmt_delete = $db->prepare("DELETE FROM user_lang WHERE id=?");
        $stmt_delete -> execute([$user_id]);

        $stmt_select = $db->prepare("SELECT id_lang FROM prog_lang WHERE lang_name = ?");

        $stmt_lang_update = $db->prepare("INSERT INTO user_lang (id, id_lang) VALUES (?,?)");
        foreach ($languages as $language) {
            $stmt_select ->execute([$language]);
            $id_lang = $stmt_select->fetchColumn();
      
            if ($id_lang) {
                $stmt_lang_update->execute([$user_id, $id_lang]);
            }
        }
    } catch (PDOException $e){
        print('update Error : ' . $e->getMessage());
        exit();
    }
}

function INSERT($login, $hash_password){
    global $db;
    try{
        $stmt = $db->prepare("INSERT INTO application (fio, number, email, bdate, gender, biography, checkbox ) values (?, ?, ?, ?, ?, ?, ? )");
        $stmt->execute([$_POST['fio'], $_POST['number'], $_POST['email'], $_POST['birthdate'], $_POST['radio-group-1'], $_POST['biography'], isset($_POST["checkbox"]) ? 1 : 0]);
    } catch (PDOException $e){
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
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
    try {
        $stmt_insert = $db->prepare("INSERT INTO users (login, password, role, id ) VALUES (?, ?, ?, ?)");
        $stmt_insert->execute([ $login, $hash_password, "user", $id]);
    } catch (PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
}

function INSERTData($login){
    $uid=getUID($login);

    try{
        $mas=[];
        $stmt = $db->prepare("SELECT fio, number, email, biography AS bio, gender AS gen, bdate, checkbox FROM application WHERE id = ?");
        $stmt->execute($uid);
        $mas = $stmt->fetch(PDO::FETCH_ASSOC);
        $fields = ['fio', 'number', 'email', 'bio', 'gen', 'bdate', 'checkbox'];
        foreach($fields as $field) {
            $values[$field] = strip_tags($mas[$field]);
        }
    } catch (PDOException $e){
        print('ERROR : ' . $e->getMessage());
        exit();
    }
      $sql = "select pl.lang_name from prog_lang pl JOIN user_lang ul ON pl.id_lang=ul.id_lang where ul.id = :login;";
    try{
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $uid, PDO::PARAM_STR);
        $stmt->execute();
        $lang = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
        $langs_value1 =(implode(",", $lang));
        $values['lang']=$langs_value1;
    } catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
    }
}
?>