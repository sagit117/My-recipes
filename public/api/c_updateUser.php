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
    public $user = "";
  }

  $id = intval($_POST['id']);
  if ($id > 0) {
    $res = new Response;
    $usr = getUserWithHash($_POST['hash'])[0];

    if ($usr->post === $_POST['login']) {
      if ($usr->id == $id or $usr->rule === "admin") {
        if (isset($_POST['name'])) {
          if (!updateUser("name", $_POST['name'], "id", $id)) {
            $res->errorText .= "Не удалось сохранить имя пользователя! ";
            $res->errorCode .= "update_user/save_name_error ";
          }
        }
        if (isset($_POST['surname'])) {
          if (!updateUser("surname", $_POST['surname'], "id", $id)) {
            $res->errorText .= "Не удалось сохранить фамилию пользователя! ";
            $res->errorCode .= "update_user/save_surname_error ";
          }
        }
        if (isset($_POST['patronymic'])) {
          if (!updateUser("patronymic", $_POST['patronymic'], "id", $id)) {
            $res->errorText .= "Не удалось сохранить отчество пользователя! ";
            $res->errorCode .= "update_user/save_patronymic_error ";
          }
        }
        $res->user = getUser('id', $id)[0];
        exit(json_encode($res));
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