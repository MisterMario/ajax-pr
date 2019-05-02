<?php

require_once("config.php");
require_once(MODULES_DIR."user.class.php");
require_once(MODULES_DIR."auth.class.php");
require_once(MODULES_DIR."group.class.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["login"]) || !isset($data["password"]) || !isset($data["remember"])) {
  $answer = array("code" => 2, "message" => "Ошибка! Переданы не все поля!", "html" => "");
} else {

  session_start();
  $login = trim($data["login"]);
  $password = $data["password"];
  $remember = $data["remember"] == 1 ? true: false;
  $answer = null; // Хранит ответ сервера клиенту
  $user = Auth::createUser();

  if ($user) {
    $answer = array("code" => 2, "message" => "Ошибка! Вы уже авторизованы!", "html" => "");
  } elseif (empty($login) || empty($password)) {
    $answer = array("code" => 2, "message" => "Ошибка! Не все поля заполнены!", "html" => "");
  } else {

    $db = DB::getInstance();
    $user_id = $db->query("SELECT ".DBT_USERS["id"]." FROM ".DB_TABLES["users"].
                          " WHERE ".DBT_USERS["login"]."='$login' AND ".DBT_USERS["pass"]."='".md5(md5($password))."' LIMIT 1");
    if ($user_id->num_rows != 0) {

      $user_id = $user_id->fetch_assoc();
      $user = new User("id", $user_id[DBT_USERS["id"]]);
      Auth::sessionCreate($user->getData(), $remember);
      ob_start();
      $mode = "profil";
      include MODULES_DIR."content.php";
      $answer = array("code" => 0, "message" => "Успешный вход!", "html" => ob_get_clean(), "uri" => "profil");

    } else $answer = array("code" => 2, "message" => "Ошибка! Неверный логин или пароль!", "html" => "");
  }

}

echo json_encode($answer);

?>
