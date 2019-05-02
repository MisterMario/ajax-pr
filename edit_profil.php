<?php

require_once("config.php");
require_once(MODULES_DIR."user.class.php");
require_once(MODULES_DIR."auth.class.php"); // этот класс вроде не нужен
require_once(MODULES_DIR."group.class.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["name"]) || !isset($data["age"]) || !isset($data["gender"]) || !isset($data["email"])
    || !isset($data["group_id"]) || !isset($data["login"]) || !isset($data["skype"]) || !isset($data["vk"])
    || !isset($data["facebook"]) || !isset($data["old_password"]) || !isset($data["new_password"]) || !isset($data["new_repassword"])) {
  $answer = array("code" => 2, "message" => "Ошибка! Переданы не все поля!", "html" => "");

} else {

  $name = trim($data["name"]);
  $age = trim($data["age"]);
  $gender = $data["gender"];
  $email = trim($data["email"]);
  $group_id = $data["group_id"];
  $login = trim($data["login"]);
  $skype = trim($data["skype"]);
  $vk = trim($data["vk"]);
  $facebook = trim($data["facebook"]);
  $old_password = $data["old_password"];
  $password = $data["new_password"];
  $repassword = $data["new_repassword"];

  if (empty($name) || (empty($age) && $age !== "0") || empty($email) || empty($login)) {
    $answer = array("code" => 2, "message" => "Ошибка! Не заполнены все обязательные поля!", "html" => "");

  } elseif (strlen($name) < 3) {
    $answer = array("code" => 2, "message" => "Ошибка! Имя не может быть меньше 3-х символов!", "html" => "");

  } elseif (!preg_match("/^[а-яА-Яa-zA-Z]{1,}\s[а-яА-Яa-zA-Z]{1,}$/u", $name)) {
    $answer = array("code" => 2, "message" => "Ошибка! Некорректное ФИ!", "html" => "");

  } elseif (!preg_match("/^[1-9]{0,1}[0-9]$/", $age) || $age == "0") {
    $answer = array("code" => 2, "message" => "Ошибка! Неккоректный возраст!", "html" => "");

  } elseif ($gender != "0" && $gender != "1") {
    $answer = array("code" => 2, "message" => "Ошибка! Некорректный пол!", "html" => "");

  } elseif (!preg_match("/^[a-zA-Z]{1,}[a-zA-Z0-9_]{0,}@(mail|gmail|)\.(ru|by|com)$/", $email)) {
    $answer = array("code" => 2, "message" => "Ошибка! Некорректный e-mail!", "html" => "");

  } elseif (strlen($login) < 3) {
    $answer = array("code" => 2, "message" => "Ошибка! Длина логина не может быть меньше 3-х символов!", "html" => "");

  } elseif (!preg_match("/^[a-zA-Z]{1}[a-zA-Z0-9_]{2,}$/", $login)) {
    $answer = array("code" => 2, "message" => "Ошибка! Некооректный логин!", "html" => "");

  } elseif (!empty($skype) && !preg_match("/^[a-zA-Z0-9\.]{3,}$/", $skype)) {
    $answer = array("code" => 2, "message" => "Ошибка! Неккоректное значение Skype!", "html" => "");

  } elseif (!empty($vk) && !preg_match("/^vk\.com\/(id[0-9]{1,})||([a-z0-9_.]{1,})$/", $vk)) {
    $answer = array("code" => 2, "message" => "Ошибка! Некорректное значение VK!", "html" => "");

  } elseif (!empty($facebbok) && !preg_match("/^facebook\.com\/id=[0-9]{1,}$/", $facebook)) {
    $answer = array("code" => 2, "message" => "Ошибка! Некооректное значение Facebook!", "html" => "");

  } elseif (!empty($password) && strlen($password) < 4) {
    $answer = array("code" => 2, "message" => "Ошибка! Длина пароля не может быть меньше 4-х символов!", "html" => "");

  } elseif (!empty($password) && $login == $password) {
    $answer = array("code" => 2, "message" => "Ошибка! Пароль не должен совпадать с логином!", "html" => "");

  } elseif (!empty($password) && $password != $repassword) {
    $answer = array("code" => 2, "message" => "Ошибка! Пароли не одинаковы!", "html" => "");

  } elseif (!empty($password) && $old_password == $password) {
    $answer = array("code" => 2, "message" => "Ошибка! Старый и новый пароли совпадают!", "html" => "");

  } else {
    session_start();
    $user = Auth::createUser();
    $db = DB::getInstance();
    $allIsWall = true;

    if ($user->getLogin() != $login) {
      $res = $db->query("SELECT ".DBT_USERS["id"]." FROM ".DB_TABLES["users"]." WHERE ".DBT_USERS["login"]."='$login' LIMIT 1");
      if (gettype($res) == "boolean" || $res->num_rows != 0) $allIsWall = false;
      //var_dump($res);
    }

    if ($allIsWall) {

      $num_affected_rows = 1;

      if (!empty($old_password)) {
        $db->query("UPDATE ".DB_TABLES["users"]." SET ".DBT_USERS["pass"]."='".md5(md5($password))."'".
                    " WHERE ".DBT_USERS["id"]."='".$user->getId()."' AND ".DBT_USERS["pass"]."='".md5(md5($old_password))."'");
        $num_affected_rows = $db->affected_rows; // Получить количество строк затронутых последним запросом
      }

      if ($num_affected_rows != 0) {
        $user->setField("name", $name);
        $user->setField("age", $age);
        $user->setField("gender", $gender);
        $user->setField("email", $email);
        $user->setField("group_id", $group_id);
        $user->setField("login", $login);
        $user->setField("skype", $skype);
        $user->setField("vk", $vk);
        $user->setField("facebook", $facebook);

        ob_start();
        $mode = "profil"; include MODULES_DIR."content.php";
        $answer = array("code" => 1, "message" => "Данные о профиле успешно обновлены!", "html" => ob_get_clean(), "uri" => "profil");
      } else $answer = array("code" => 2, "message" => "Ошибка! Введен неверный старый пароль!", "html" => "");

    } else $answer = array("code" => 2, "message" => "Ошибка! Пользователь с таким логином уже зарегистрирован!", "html" => "");
  }
}

echo json_encode($answer);

?>
