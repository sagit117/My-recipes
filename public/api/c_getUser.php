<?php
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  require_once 'm_user.php';
  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  class Response {
    public $errorCode = '';
    public $errorText = '';
    public $user = '';
  }

  $id = intval($_POST['id']);
  if ($id > 0) {
    $res = new Response;
    $usr = getUserWithHash($_POST['hash'])[0];

    if ($usr->post === $_POST['login']) {
      if ($usr->id == $id or $usr->rule === "admin") {
        $res->user = getUser('id', $id)[0];
        if ($res->user->id > 0) exit(json_encode($res));
        else {
          $res->errorText = "Запись отсутствует!";
          $res->errorCode = "auth/no_entry";
          exit(json_encode($res));
        }
      } else {
        $res->errorText = "Отказано в доступе!";
        $res->errorCode = "auth/access_denied";
        exit(json_encode($res));
      }
    } else {
      $res->errorText = "Пользователь с хэш не определен!";
      $res->errorCode = "auth/hash_wrong";
      exit(json_encode($res));
    }
  }

?>