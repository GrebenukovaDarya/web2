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
                <td>ID</td><td>LOGIN</td><td>FIO</td><td>NUMBER</td><td>EMAIL</td><td>LANGUAGES</td><td>GENDER</td><td>BIO</td><td>CHECK</td><td>CHANGE</td>
            </tr>
        </thead> 

        <tbody>
            <?php
                $user = 'u68607';
                $pass = '7232008';
                $db = new PDO('mysql:host=localhost;dbname=u68607', $user, $pass,
                  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);


                try{
                    /*
                    mysql_connect("hostname", "user", "password");
                    mysql_select_db("mydb");
                    $result = mysql_query("select * from mytable");
                    while ($row = mysql_fetch_object($result)) {
                        echo $row->user_id;
                        echo $row->fullname;
                    }
                    mysql_free_result($result);
              
                    $stmt = $db->prepare("SELECT fio, number, email, biography AS bio, gender AS gen, bdate, checkbox FROM application");
                    $stmt->execute();
                    $mas = $stmt->fetch(PDO::FETCH_OBJECT);
                    $fields = ['fio', 'number', 'email', 'bio', 'gen', 'bdate', 'checkbox'];
                    foreach($fields as $field) {
                        $values[$field] = strip_tags($mas[$field]);
                    }
                        */

                    $stmt = $db->prepare("SELECT login, id FROM users");
                    $stmt->execute();
                    //$mas = $stmt->fetch(PDO::FETCH_OBJECT);

                    //$select_login = mysql_query("select login, id from users");
                    while($row = $stmt->fetch(PDO::FETCH_OBJ)){
                        $log->login;
                        $uid->id;
                        echo "<tr><td>$uid</td><td>$log</td>";
                    }
                } 
                catch (PDOException $e){
                    print('ERROR : ' . $e->getMessage());
                    exit();
                }


            ?>

            <tr>
                <td>1</td><td>134ktb234jv34</td><td>w4e ty act</td><td>+9479562437</td><td>weyvtbch@havwrb.com</td><td>c</td><td>m</td><td>B wlrug wekjcbw webt 6ev IO</td><td>1</td><td>CHANGE</td>
            </tr>

        </tbody>

    </table>

  </body>
</html>
