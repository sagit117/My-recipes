<?php
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST,GET");
	header("Access-Control-Allow-Headers: *");

	require 's_sendmail.php';
  require_once 'm_user.php';

	class Response {
		public $errorCode = '';
		public $errorText = '';
	}

	$res = new Response;

  if (isset($_GET['login'])) {
    // смена пароля по get (запрос через форму забыли пароль)
    $post = $_GET['login'];
    $usr = getUser("post", $post);

    if (intval($usr[0]->id) === 0) {
      $res->errorText = "E-mail не существует!";
      $res->errorCode = 'send_new_pass/email_wrong';
      exit(json_encode($res));
    } else {
      $newPass = generateCode(8);
      $psw = md5(md5($newPass));
      updateUser("password", $psw, "id", $usr[0]->id);
      $send = smtpmail($post, $post, 'Ваш новый пароль', 'Ваш новый пароль для входа: '.$newPass);
      
      if ($send !== true) {
        $res->errorCode = "send_new_pass/no_send_email";
        $res->errorText = array_slice($send, 0);
      } 
    }

    exit(json_encode($res));
  }

  /*if (intval($_POST['id']) > 0) {
    // смена пароля из личного кабинета 
    $user = getUserWithHash($_POST['hash'])[0];
    if ($user->post !== $_POST['login']) {
      $res->errorText = "Хэш не определен!";
      $res->errorCode = "auth/hash_wrong";
      exit(json_encode($res));
    }

    if ($_POST['id'] !== $user->id and $user->rule !== 'admin') {
      $res->errorText = "Доступ запрещен!";
      $res->errorCode = "auth/rule_wrong";
      exit(json_encode($res));
    }

    $id = intval($_POST['id']);
    $oldPass = md5(md5($_POST['oldPass']));
    $newPass = md5(md5($_POST['newPass']));
    $pass = getUser("id", $id)[0]->pass;
    
    if ($oldPass !== $pass) {
      $res->errorCode = "auth/oldpass_wrong";
      $res->errorText = "Старый пароль не совпадает!";
      exit(json_encode($res));
    }

    updateUser("password", $newPass, "id", $id);
    exit(json_encode($res));
  }*/

?>