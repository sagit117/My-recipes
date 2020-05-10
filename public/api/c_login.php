<?php
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  require_once 'm_user.php';
  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  class Response {
    public $errorCode = '';
    public $errorText = '';
    public $user = '';
    public $hash = '';
  }

  $res = new Response;
    
  if (isset($_POST['login'])) {
    // логин по post
    $pass = md5(md5($_POST['pass']));
    $user = getUser("post", $_POST['login']); // возвращает массив пользователей
    
    if (count($user) > 0) {
      if ($user[0]->pass === $pass) {
        $hash = updateUserHash($user[0]->id);
        if (($hash)[1] === 'ok') {
          $res->hash = $hash[0];
        } else {
          $res->errorCode = 'auth/hash_wrong';
          $res->errorText = 'Не удалось создать хеш пользователя!';
          exit(json_encode($res));
        }

        $res->user = $user[0];
        updateUser('last_connect', time(), 'id', $user[0]->id);
        
        exit(json_encode($res));
      } else {
        $res->errorCode = 'auth/pass_wrong';
        $res->errorText = 'Логин или пароль не совпадают!';
        exit(json_encode($res));
      }
    } else {
      $res->errorCode = 'auth/pass_wrong';
      $res->errorText = 'Логин или пароль не совпадают!';
      exit(json_encode($res));
    }
  }

  ?>