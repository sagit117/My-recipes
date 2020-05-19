<?php	
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  // getUser 				  - получить данные пользователя/или нескольких по полю(ключу)
  // updateUserHash	  - обновить запись хэш в БД
  // generateCode		  - генерация кода
  // updateUser 		  - изменить поле в таблице пользователь по условию
  // getUserWithHash  - получить пользователя по хэш
  // createUser       - создать пользователя

  require 's_connect.php';

  class User {
    public $id = 0;
    public $pass = "";
    public $post = "";
    public $name = "";
    public $surname = "";
    public $patronymic = "";
    public $datereg = "";
    public $rule = "";
    public $last_connect = "";
    public $created_at = "";
    public $updated_at = "";
  }

  function getUser($key, $value) {
    // получить данные пользователя по полю
    global $link;
    $key = mysqli_real_escape_string($link, $key);
    $value = mysqli_real_escape_string($link, $value);
    $result = mysqli_query($link, "SELECT * FROM `user` WHERE `$key`='$value'");
    $arr = array();

    while ($user = mysqli_fetch_assoc($result)) {
      $usr = new User();
      $usr->id = $user['id'];
      $usr->pass = $user['password'];
      $usr->post = $user['post'];
      $usr->name = $user['name'];
      $usr->surname = $user['surname'];
      $usr->patronymic = $user['patronymic'];
      $usr->datereg = $user['datereg'];
      $usr->rule = $user['rule'];
      $usr->last_connect = $user['last_connect'];
      $usr->created_at = $user['created_at'];
      $usr->updated_at = $user['updated_at'];

      array_push($arr, $usr); // собираем всех найденных пользователей в массив
    }
    return $arr;
  }

  function updateUserHash($id_user) {
    // обновить запись хэш в БД
    global $link;
    $id_user = intval($id_user);
    $hash = md5(generateCode(10));
    $agent = mysqli_real_escape_string($link, $_SERVER["HTTP_USER_AGENT"]);

    $result = mysqli_query($link, "SELECT `id` FROM `hash_user` WHERE `id_user`='$id_user' AND `agent`='$agent' LIMIT 1");
    $id = intval(mysqli_fetch_row($result)[0]);
    $now = time();

    if ($id === 0) { // если пользователь не найден
      mysqli_query($link, "INSERT INTO `hash_user` (`id_user`, `agent`, `hash`, `created_at`) 
        VALUES ('$id_user', '$agent', '$hash', '$now')") 
        or die(mysqli_error($link));
    } else {
      mysqli_query($link, "UPDATE `hash_user` SET `hash`='$hash', `updated_at`='$now' WHERE `id`='$id'") 
        or die(mysqli_error($link));
    }

    return [$hash, 'ok'];
  }

  function generateCode($length = 6) { // Генератор кода
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789-_";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
      $code .= $chars[mt_rand(0, $clen)];
      }
    return $code;
  }

  function updateUser($field, $value, $conditionField, $conditionValue) { // изменить поле в таблице пользователь по условию
    global $link;
    $field = mysqli_real_escape_string($link, $field);
    $value = mysqli_real_escape_string($link, $value);
    $conditionField = mysqli_real_escape_string($link, $conditionField);
    $conditionValue = mysqli_real_escape_string($link, $conditionValue);
    $now = time();

    mysqli_query($link, "UPDATE `user` SET `$field`='$value', `updated_at`='$now' WHERE `$conditionField`='$conditionValue'") 
      or die(mysqli_error($link));
    return true;
  }

  function getUserWithHash($hash) { // получить пользователя по хэш
    global $link;
    $hash = mysqli_real_escape_string($link, $hash);
    $agent = mysqli_real_escape_string($link, $_SERVER["HTTP_USER_AGENT"]);

    $result = mysqli_query($link, 
      "SELECT * FROM `user` 
      LEFT JOIN `hash_user` 
      ON user.id=hash_user.id_user 
      WHERE hash_user.agent='$agent' AND hash_user.hash='$hash' ");
    
    $arr = array();

    while ($user = mysqli_fetch_assoc($result)) {
      $usr = new User();
      $usr->id = $user['id_user'];
      $usr->pass = $user['password'];
      $usr->post = $user['post'];
      $usr->name = $user['name'];
      $usr->surname = $user['surname'];
      $usr->patronymic = $user['patronymic'];
      $usr->datereg = $user['datereg'];
      $usr->rule = $user['rule'];
      $usr->last_connect = $user['last_connect'];
      $usr->created_at = $user['created_at'];
      $usr->updated_at = $user['updated_at'];

      array_push($arr, $usr); // собираем всех найденных пользователей в массив
    }

    return $arr;
  }

  function createUser($user, $pass) {
    // создать пользователя
    global $link;
    $user = mysqli_real_escape_string($link, $user);
    $pass = md5(md5($pass));
    $datereg = time();

    mysqli_query($link, "INSERT INTO `user` (`post`, `password`, `datereg`, `created_at`) 
      VALUES ('$user', '$pass', '$datereg', '$datereg')") or die(mysqli_error($link));
    return mysqli_insert_id($link);
  }

?>