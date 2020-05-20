<?php	
  /* author: Aksenov Pavel */
  /* 05.2020 */
  /* sagit117@gmail.com */

  // getGroupFR - получить данные группы по полю

  require 's_connect.php';

  class Group {
    public $id = 0;
    public $parent_id = 0;
    public $name = "";
    public $author_id = 0;
    public $public = 0;
    public $created_at = "";
    public $updated_at = "";
  }

  function getGroupFR($key, $value) { // получить данные группы по полю
    global $link;
    $key = mysqli_real_escape_string($link, $key);
    $value = mysqli_real_escape_string($link, $value);
    $result = mysqli_query($link, "SELECT * FROM `categories_recipes` WHERE `$key`='$value'");
    $arr = array();

    while($group = mysqli_fetch_assoc($result)) {
      $gr = new Group();
      $gr = (array) $gr;

      foreach($group as $key => $value) {
        $gr[$key] = $value;
      }
      array_push($arr, $gr);
    }
    return $arr;
  }


?>