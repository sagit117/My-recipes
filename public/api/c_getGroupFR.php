<?php
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  require_once 'm_user.php';
  require_once 'm_groupFR.php';
  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  class Response {
    public $errorCode = '';
    public $errorText = '';
    public $group = '';
  }

  $login = $_POST['login'];
  $hash = $_POST['hash'];
  $res = new Response;
  $usr = getUserWithHash($_POST['hash'])[0];

  if ($usr->post === $_POST['login']) {

    if (isset($_POST['userID'])) { // userID
      $userID = intval($_POST['userID']);
      if ($usr->id == $userID or $usr->rule === "admin") {
        $res->group = getGroupFR('author_id', $userID);
        exit(json_encode($res));
      } else {
        $res->errorText = "Отказано в доступе!";
        $res->errorCode = "auth/access_denied";
        exit(json_encode($res));
      }
    }

  } else {
    $res->errorText = "Пользователь с хэш не определен!";
    $res->errorCode = "auth/hash_wrong";
    exit(json_encode($res));
  }
?>