<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="style.css">
    <title> LAB4 </title>
  </head>
  <body>

    <div class="error_messages" <?php if (empty($messages)) {print 'display="none"';} else {print 'display="block"';} ?>>

      <?php
      if (!empty($messages)) {
        print('<div id="messages">');
        foreach ($messages as $message) {
          print($message);
        }
        print('</div>');
      }
      ?>

    </div>
    

    <div class="formstyle" > 
      <form id="myform" class="formcarryForm" action="index.php" method="POST">

        <h2> ФОРМА </h2> 

      <label> 
        ФИО: <br/>
        <input name="fio" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" />
      </label> <br/>

      <label> 
        Номер телефона: <br />
        <input name="number" type="tel" 
        <?php if ($errors['number']) {print 'class="error"';} ?> value="<?php print $values['number']; ?>"/>
      </label> <br/>
      <p class="numtext"> *используйте телефонный код +7</p>

      <label>
        E-mail: <br/>
        <input name="email" type="email" 
        <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>"/>
      </label> <br/>

      <label> 
        Дата рождения: <br/>
        <input name="birthdate" type="date" 
        <?php if ($errors['bdate']) {print 'class="error"';} ?> value="<?php print $values['bdate']; ?>"/>
      </label> <br/>

      Пол: <br /> 
      <label> <input type="radio" checked="checked" name="radio-group-1" value="male" 
      <?php if ($errors['gen']) {print 'class="error"';} ?>/> Мужской </label>
      <label> <input type="radio"  name="radio-group-1" value="female" 
      <?php if ($errors['gen']) {print 'class="error"';} ?>/> Женский</label> <br/>

      <label > 
        Любимый язык программирования: <br/>
        <select  name="languages[]" multiple="multiple" 
        <?php if ($errors['lang']) {print 'class="error"';} ?> >
          <option value="Pascal"> Pascal</option>
          <option value="C"> C</option>
          <option value="C++"> C++ </option>
          <option value="Python"> Python</option>
          <option value="Java"> Java</option>
          <option value="JavaScript"> JavaScript</option>
          <option value="PHP"> PHP</option>
          <option value="Clojure"> Clojure</option>
          <option value="Haskel"> Haskel</option>
          <option value="Prolog"> Prolog</option>
          <option value="Scala"> Scala</option>
          <option value="Go"> Go</option>
        </select>
      </label> <br/>

      <label>
        Биография: <br/>
        <textarea name="biography" <?php if ($errors['bio']) {print 'class="error"';} ?>> 
         <?php print $values['bio']; ?>"
        </textarea>
      </label> <br/>

      <label class="form-checkbox pl-2">
        <input type="checkbox" name="checkbox"
        <?php if ($errors['checkbox']) {print 'class="error"';} ?> value="<?php print $values['checkbox']; ?>"/> 
        С контрактом ознакомлен 
      </label> <br/>

      <input type="submit" value="Сохранить"/> 
      </form>
    </div>

  
  </body>
</html>