<?php
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  require 's_sendmail.php';
  require 'm_user.php';

  class Response {
    public $errorCode = "";
    public $errorText = '';
    public $code = '';
    public $post = '';
  }

  if (isset($_GET['email'])) {
    $res = new Response;
    $res->post = $_GET['email'];
    $res->code = generateCode(6);
    $usr = getUser("post", $res->post);

    if (!filter_var($res->post, FILTER_VALIDATE_EMAIL) or $res->post == '') {
      $res->errorText = "E-mail адрес указан не верно!";
      $res->errorCode = "send_code/email_wrong";
    }
    
    if (count($usr) > 0) {
      $res->errorText = "E-mail существует!";
      $res->errorCode = "send_code/isset_email";
    }

    if ($res->errorCode === "") {
      smtpmail($res->post, $res->post, 'Ваш код для подтверждения регистрации', 
        'Ваш код для подтверждения регистрации: '.$res->code);
    }

    exit(json_encode($res));
  }
?>