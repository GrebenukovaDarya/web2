<!DOCTYPE html>
<html lang="ru">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="table_style.css"> 
    <meta http-equiv="refresh"/>
    <title> ADMIN </title>
  </head>

  <body>

    <table>
        <thead> 
            <tr>
                <td>ID</td><td>LOGIN</td><td>FIO</td><td>NUMBER</td><td>EMAIL</td><td>GENDER</td><td>BIO</td><td>DATA</td><td>CHECK</td><td>LANGUAGES</td><td>CHANGE</td>
            </tr>
        </thead> 

        <tbody>
            <?php
                $user = 'u68607';
                $pass = '7232008';
                $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
                  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                try{

                    $stmt = $db->prepare("SELECT login, id, role FROM users ORDER BY (id) desc");
                    $stmt->execute();
                    $log;
                    $uid;
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                        if($row->role=='user'){
                        $log=$row->login;
                        $uid=$row->id;
                        echo "<tr><td>$uid</td><td>$log</td>";

                        $form_data = $db->prepare("SELECT fio, number, email, gender AS gen, biography AS bio, bdate, checkbox FROM application WHERE id = ?");
                        $form_data->execute([$uid]);
                        $mas = $form_data->fetch(PDO::FETCH_ASSOC);
                        foreach($mas as $field) {
                            echo "<td>$field</td>";
                        }

                        $sql = "select pl.lang_name from prog_lang pl JOIN user_lang ul ON pl.id_lang=ul.id_lang where ul.id = :login;";
                        
                            $stmt_lang = $db->prepare($sql);
                            $stmt_lang->bindValue(':login', $uid, PDO::PARAM_STR);
                            $stmt_lang->execute();
                            $lang = $stmt_lang->fetchAll(PDO::FETCH_COLUMN, 0);
                            $langs_value1 =(implode(", ", $lang));
                            echo "<td>$langs_value1</td>";

                        echo '<td class="buttons">'

                        echo "</td></tr>";
                    }
                }
                } 
                catch (PDOException $e){
                    print('ERROR : ' . $e->getMessage());
                    exit();
                }
            ?>

        </tbody>

    </table>

  </body>
</html>
