<?php

# Скрипт идентифицирует запрошенную страницу и возвращает ее.
# Страница не загржается в буфер, а только импортируется.
# Это сделано для универсальности скрипта.

require_once("config.php");
require_once("user.class.php");
require_once("auth.class.php");
require_once("group.class.php");

if (!isset($user)) {
  session_start();
  $user = Auth::CreateUser(); // Пользователь может быть создан другими скриптами
}

if (!isset($mode)) $mode = substr(strtok($_SERVER["REQUEST_URI"], "?"), 1);

if ($user) {
  switch ($mode) {
    case "profil":
      include VIEW_DIR."profil.html";
      break;
    case "edit_profil":
      include VIEW_DIR."edit_profil.html";
      break;
    case "logout":
      $mode = "login";
      Auth::sessionDestroy($user->getId());
      include VIEW_DIR."login.html";
      break;
    case "login":
    case "register":
      $message = array("fail", "Ошибка! Нельзя перейти на регистрацию/авторизацию!");
    default:
      if (!isset($message) && $mode != "") $message = array("fail", "Ошибка 404!");
      include VIEW_DIR."profil.html";
  }
} else {
  switch ($mode) {
    case "login":
      include VIEW_DIR."login.html";
      break;
    case "register":
      include VIEW_DIR."register.html";
      break;
		case "profil":
		case "edit_profil":
		case "logout":
			$message = array("fail", "Ошибка 403!");
    default:
      if (!isset($message) && $mode != "")$message = array("fail", "Ошибка 404!");
      include VIEW_DIR."login.html";
  }
}

?>
