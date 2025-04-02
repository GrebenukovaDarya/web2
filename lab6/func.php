<?php

global $db;
$user = 'u68607';
$pass = '7232008';
$db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


function language_stats(){
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

?>