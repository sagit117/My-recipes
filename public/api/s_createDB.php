<?php
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  require 's_connect.php'; // Подключение к БД
    
  // table user
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `user` ("
    . "`id`             INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
    . "`post`           VARCHAR(64) NOT NULL,"
    . "`password`       VARCHAR(32),"
    . "`name`           VARCHAR(32),"
    . "`surname`        VARCHAR(32),"
    . "`patronymic`     VARCHAR(32),"
    . "`datereg`        VARCHAR(32),"
    . "`rule`           VARCHAR(32),"
    . "`last_connect`   VARCHAR(32),"
    . "`created_at`     VARCHAR(32),"
    . "`updated_at`     VARCHAR(32))") or die('Ошибка запроса `user`! '.mysqli_error($link));

  // table user_hash
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `hash_user` ("
    . "`id`         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
    . "`id_user`    INT(11) NOT NULL,"
    . "`agent`      TEXT,"
    . "`hash`       VARCHAR(32),"
    . "`created_at` VARCHAR(32),"
    . "`updated_at` VARCHAR(32))") or die('Ошибка запроса `user_hash`! '.mysqli_error($link));

  /*//  categories_recipes
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `categories_recipes` ("
            . "`id`         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "`parent_id`  INT(11) NOT NULL,"
            . "`name`       TEXT,"
            . "`author_id`  INT(11))") or die('Ошибка запроса `categories_recipes`! '.mysqli_error($link));

  //  foto_recipes
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `foto_recipes` ("
            . "`id`         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "`parent_id`  INT(11) NOT NULL,"
            . "`name`       TEXT,"
            . "`author_id`  INT(11),"
            . "`diet`       INT(1))") or die('Ошибка запроса `foto_recipes`! '.mysqli_error($link));

  //  img_foto_recipes
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `img_foto_recipes` ("
            . "`id`         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "`parent_id`  INT(11) NOT NULL,"
            . "`path_img`   TEXT,"
            . "`author_id`  INT(11))") or die('Ошибка запроса `img_foto_recipes`! '.mysqli_error($link));

  // favorite_foto_recipes
  mysqli_query($link, "CREATE TABLE IF NOT EXISTS `favorite_foto_recipes` ("
            . "`id`         INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,"
            . "`id_recipe`  INT(11) NOT NULL,"
            . "`id_user`    INT(11) NOT NULL)") or die('Ошибка запроса `favorite_foto_recipes`! '.mysqli_error($link));
            */
?>