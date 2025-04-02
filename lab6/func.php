<?php

global $db;
$db = new PDO('mysql:host=localhost;dbname=u68607', 'u68607', '7232008',
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