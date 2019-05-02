<?php

require_once("config.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["name"]) || !isset($data["age"]) || !isset($data["sex"]) || !isset($data["email"])
  || !isset($data["login"]) || !isset($data["password"]) || !isset($data["repassword"])) {
  $answer = array("code" => 2, "message" => "Ошибка! Переданы не все поля!", "html" => "");
} else {

  $name = trim($data["name"]);
  $age = trim($data["age"]);
  $gender = $data["sex"];
  $email = trim($data["email"]);
  $login = trim($data["login"]);
  $password = $data["password"];
  $repassword = $data["repassword"];
  $iagree = $data["iagree"] == 1 ? true : false;

  if (empty($name) || (empty($age) && $age !== "0") || empty($email) || empty($login) || empty($password) || empty($repassword)) {
    $answer = array("code" => 2, "message" => "Ошибка! Не все поля заполнены!", "html" => "");

  } elseif (!$iagree) {
    $answer= array("code" => 2, "message" => "Вы не подтвердили согласие с правилами проекта!", "html" => "");

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

  } elseif (!empty($password) && strlen($password) < 4) {
    $answer = array("code" => 2, "message" => "Ошибка! Длина пароля не может быть меньше 4-х символов!", "html" => "");

  } elseif (!empty($password) && $login == $password) {
    $answer = array("code" => 2, "message" => "Ошибка! Пароль не должен совпадать с логином!", "html" => "");

  } elseif (!empty($password) && $password != $repassword) {
    $answer = array("code" => 2, "message" => "Ошибка! Пароли не одинаковы!", "html" => "");

  } elseif ($password != $repassword) {
    $answer = array("code" => 2, "message" => "Ошибка! Пароли не одинаковы!", "html" => "");

  } else {
    $db = DB::getInstance();
    $res = $db->query("SELECT ".DBT_USERS["id"]." FROM ".DB_TABLES["users"]." WHERE ".DBT_USERS["login"]."='$login' LIMIT 1");

    if (gettype($res) != "boolean" && $res->num_rows == 0) {

      $res = $db->query("INSERT INTO ".DB_TABLES["users"]."(".
                        DBT_USERS["name"].", ".
                        DBT_USERS["age"].", ".
                        DBT_USERS["gender"].", ".
                        DBT_USERS["email"].", ".
                        DBT_USERS["login"].", ".
                        DBT_USERS["pass"].
                        ") VALUES ('$name', '$age', '$gender', '$email', '$login', '".md5(md5($password))."')");
      if ($res) {
        ob_start();
        $mode = "login";
        include MODULES_DIR."content.php";
        $answer = array("code" => 1, "message" => "Вы успешно зарегистрированы!", "html" => ob_get_clean(), "uri" => "login");
      } else $answer = array("code" => 2, "message" => "Ошибка! Не удалось добавить пользователя!", "html" => "");

    } else $answer = array("code" => 2, "message" => "Ошибка! Пользователь с таким логином уже зарегистрирован!", "html" => "");
  }

}

echo json_encode($answer);

?>
