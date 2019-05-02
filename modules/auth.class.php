<?php

class Auth {

  /* Функция для генерации случайной строки */
  public static function TmpGenerate($tmp_length = 32){
  	$allchars = "abcdefghijklmnopqrstuvwxyz0123456789";
  	$output = "";
    mt_srand( (double) microtime() * 1000000 );
  	for($i = 0; $i < $tmp_length; $i++){
  	   $output .= $allchars{ mt_rand(0, strlen($allchars)-1) };
  	}
  	return $output;
  }
  /* Создает сессию и куки для пользователя */
  public static function sessionCreate($data, $remember = false) {
    if (!isset($_SESSION["user"]) && !isset($_COOKIE["pr-v1-id"])) { // Если сессия и куки не созданы
      $_SESSION["user"] = $data;

      if ($remember) {
        $tmp = self::TmpGenerate();
        $db = DB::getInstance();
        if (!$db->query("UPDATE ".DB_TABLES["users"]." SET ".DBT_USERS["tmp"]."='$tmp' WHERE ".DBT_USERS["id"]."=".$data[DBT_USERS["id"]]))
          return false;
        setcookie("pr-v1-id", $data[DBT_USERS["id"]], time()+3600*24*30, "/");
        setcookie("pr-v1-tmp", $tmp, time()+3600*24*30, "/");
      }

      return true;
    }
    return false;
  }
  /* Уничтожает сессию и куки пользователя */
  public static function sessionDestroy($id) {
    session_unset();
    session_destroy();
    if (isset($_COOKIE["pr-v1-id"]) && isset($_COOKIE["pr-v1-tmp"])) {
      setcookie("pr-v1-id", "", time()-3600, "/");
      setcookie("pr-v1-tmp", "", time()-3600, "/");
      $db = DB::getInstance();
      $db->query("UPDATE ".DB_TABLES["users"]." SET ".DBT_USERS["tmp"]."='' WHERE ".DBT_USERS["id"]."=$id");
    }
  }
  public static function createUser() {

    if (isset($_SESSION["user"])) { // Получение данных о пользователе из сессии
      return new User("data", $_SESSION["user"]);
    } elseif (isset($_COOKIE["pr-v1-id"]) && isset($_COOKIE["pr-v1-tmp"])) { // Получение даннных о пользователе из кук
      $db = DB::getInstance();
      $user_id = $db->query("SELECT ".DBT_USERS["id"].
                                      " FROM ".DB_TABLES["users"].
                                      " WHERE ".DBT_USERS["id"]."=".$_COOKIE["pr-v1-id"].
                                      " AND ".DBT_USERS["tmp"]."='".$_COOKIE["pr-v1-tmp"]."'");
      if (gettype($user_id) != "boolean" && $user_id->num_rows != 0) {
        $user_id = $user_id->fetch_assoc()[DBT_USERS["id"]];
        $user = new User("id", $user_id);
        $_SESSION["user"] = $user->getData();
        return $user;
      } else { // Если информация из кук устарела или ложная
        setcookie("pr-v1-id", "", time()-3600, "/");
        setcookie("pr-v1-tmp", "", time()-3600, "/");
      }
    }
    return false;
  }
}

?>
