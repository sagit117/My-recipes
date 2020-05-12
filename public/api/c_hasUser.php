<?php
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  require_once 'm_user.php';
  
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST,GET");
  header("Access-Control-Allow-Headers: *");

  if (isset($_GET['post'])) {

    if (count(getUser('post', $_GET['post'])) > 0) exit("1");
    else exit("0");
    
  }

  ?>