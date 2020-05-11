<?php   
  /* author: Aksenov Pavel */
  /* 04.2020 */
  /* sagit117@gmail.com */

  $config['smtp_username'] = 'admin@axel-dev.ru.com';  //Смените на адрес своего почтового ящика.
  $config['smtp_port'] = '465'; // Порт работы.
  $config['smtp_host'] =  'ssl://mail.hosting.reg.ru';  //сервер для отправки почты
  $config['smtp_password'] = 'wymmag-pEckyr-7ravqa';  //Измените пароль
  $config['smtp_debug'] = true;  //Если Вы хотите видеть сообщения ошибок, укажите true вместо false
  $config['smtp_charset'] = 'utf-8';	//кодировка сообщений. (windows-1251 или utf-8, итд)
  $config['smtp_from'] = 'www.axel-dev.ru.com'; //Ваше имя - или имя Вашего сайта. Будет показывать при прочтении в поле "От кого"
  
  $error = array();

  function smtpmail($to='', $mail_to, $subject, $message, $headers='') {
    global $config;
    global $error;

    $SEND =	"Date: ".date("D, d M Y H:m:s") . " UT\r\n"; //i
    $SEND .= 'Subject: =?'.$config['smtp_charset'].'?B?'.base64_encode($subject)."=?=\r\n";
    if ($headers) $SEND .= $headers."\r\n\r\n";
    else {
      $SEND .= "Reply-To: ".$config['smtp_username']."\r\n";
      $SEND .= "To: \"=?".$config['smtp_charset']."?B?".base64_encode($to)."=?=\" <$mail_to>\r\n";
      $SEND .= "MIME-Version: 1.0\r\n";
      $SEND .= "Content-Type: text/html; charset=\"".$config['smtp_charset']."\"\r\n";
      $SEND .= "Content-Transfer-Encoding: 8bit\r\n";
      $SEND .= "From: \"=?".$config['smtp_charset']."?B?".base64_encode($config['smtp_from'])."=?=\" <".$config['smtp_username'].">\r\n";
      $SEND .= "X-Priority: 3\r\n\r\n";
    }
    $SEND .=  $message."\r\n";
    if( !$socket = fsockopen($config['smtp_host'], $config['smtp_port'], $errno, $errstr, 30) ) {
      if ($config['smtp_debug']) array_push($error, $errno."<br>".$errstr);
      return $error;
     }
 
    if (!server_parse($socket, "220", __LINE__)) return false;
 
    fputs($socket, "HELO " . $config['smtp_host'] . "\r\n");
    if (!server_parse($socket, "250", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Не могу отправить HELO!');
      fclose($socket);
      return $error;
    }
    fputs($socket, "AUTH LOGIN\r\n");
    if (!server_parse($socket, "334", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Не могу найти ответ на запрос авторизации.');
      fclose($socket);
      return $error;
    }
    fputs($socket, base64_encode($config['smtp_username']) . "\r\n");
    if (!server_parse($socket, "334", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Логин авторизации не был принят сервером!');
      fclose($socket);
      return $error;
    }
    fputs($socket, base64_encode($config['smtp_password']) . "\r\n");
    if (!server_parse($socket, "235", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Пароль не был принят сервером как верный! Ошибка авторизации!');
      fclose($socket);
      return $error;
    }
    fputs($socket, "MAIL FROM: <".$config['smtp_username'].">\r\n");
    if (!server_parse($socket, "250", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Не могу отправить комманду MAIL FROM: ');
      fclose($socket);
      return $error;
    }
    fputs($socket, "RCPT TO: <" . $mail_to . ">\r\n");
 
    if (!server_parse($socket, "250", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Не могу отправить комманду RCPT TO: ');
      fclose($socket);
      return $error;
    }
    fputs($socket, "DATA\r\n");
 
    if (!server_parse($socket, "354", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Не могу отправить комманду DATA');
      fclose($socket);
      return $error;
    }
    fputs($socket, $SEND."\r\n.\r\n");
 
    if (!server_parse($socket, "250", __LINE__)) {
      if ($config['smtp_debug']) array_push($error, 'Не смог отправить тело письма. Письмо не было отправленно!');
      fclose($socket);
      return $error;
    }
    fputs($socket, "QUIT\r\n");
    fclose($socket);
    return TRUE;
  }
 
  function server_parse($socket, $response, $line = __LINE__) {
    global $config;
    global $error;

    while (@substr($server_response, 3, 1) != ' ') {
      if (!($server_response = fgets($socket, 256))) {
        if ($config['smtp_debug']) array_push($error, "Проблемы с отправкой почты! $response $line ");
         return false;
       }
    }
    if (!(substr($server_response, 0, 3) == $response)) {
      if ($config['smtp_debug']) array_push($error, "Проблемы с отправкой почты! $response $line ");
      return false;
    }
    return true;
  }   
?>