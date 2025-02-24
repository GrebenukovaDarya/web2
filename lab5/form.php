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
        <input name="fio" <?php if ($errors['fio'] ) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" />
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
      <label> <input type="radio" name="radio-group-1" value="male" 
      <?php if ($errors['gen']) {print 'class="error"';} ?>
      <?php if ($values['gen']=='male') {print 'checked="checked"';} ?>/> Мужской </label>
      <label> <input type="radio"  name="radio-group-1" value="female" 
      <?php if ($errors['gen']) {print 'class="error"';} ?>
      <?php if ($values['gen']=='female') {print 'checked="checked"';} ?>/> Женский</label> <br/>

      <?php 
      $user_languages = explode(",",  $values['lang']);
      ?>

      <label > 
        Любимый язык программирования: <br/>
        <select  name="languages[]" multiple="multiple" 
        <?php if ($errors['lang']) {print 'class="error"';} ?> >
          <option value="Pascal" <?php if(in_array('Pascal', $user_languages)) {print 'selected="selected"';}?>> Pascal</option>
          <option value="C" <?php if(in_array('C', $user_languages)) {print 'selected="selected"';}?>> C</option>
          <option value="C++" <?php if(in_array('C++', $user_languages)) {print 'selected="selected"';}?>> C++ </option>
          <option value="Python" <?php if(in_array('Python', $user_languages)) {print 'selected="selected"';}?>> Python</option>
          <option value="Java" <?php if(in_array('Java', $user_languages)) {print 'selected="selected"';}?>> Java</option>
          <option value="JavaScript" <?php if(in_array('JavaScript', $user_languages)) {print 'selected="selected"';}?>> JavaScript</option>
          <option value="PHP" <?php if(in_array('PHP', $user_languages)) {print 'selected="selected"';}?>> PHP</option>
          <option value="Clojure" <?php if(in_array('Clojure', $user_languages)) {print 'selected="selected"';}?>> Clojure</option>
          <option value="Haskel" <?php if(in_array('Haskel', $user_languages)) {print 'selected="selected"';}?>> Haskel</option>
          <option value="Prolog" <?php if(in_array('Prolog', $user_languages)) {print 'selected="selected"';}?>> Prolog</option>
          <option value="Scala" <?php if(in_array('Scala', $user_languages)) {print 'selected="selected"';}?>> Scala</option>
          <option value="Go" <?php if(in_array('Go', $user_languages)) {print 'selected="selected"';}?>> Go</option>
        </select>
      </label> <br/>

      <label>
        Биография: <br/>
        <textarea name="biography" <?php if ($errors['bio']) {print 'class="error"';} ?>><?php print $values['bio']; ?></textarea>
      </label> <br/>

      <label class="form-checkbox pl-2">
        <input type="checkbox" name="checkbox"
        <?php if ($errors['checkbox']) {print 'class="error"';} ?>  <?php if (!$errors['checkbox']) {print 'checked="checked"';} ?>/> 
        С контрактом ознакомлен 
      </label> <br/>

      <input type="submit" value="Сохранить"/> 
      </form>
    </div>

  
  </body>
</html>
