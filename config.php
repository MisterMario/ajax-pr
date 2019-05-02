<?php

# Данные для работы сайта
define("VIEW_DIR", "view/");
define("JS_DIR", VIEW_DIR."js/");
define("MODULES_DIR", "modules/");
define("A_DIR", "avatars/");
define("MAX_FILE_SIZE", 500 * 1024); // Максимальный размер аватарки в КБ
define("MAX_FILE_WIDTH", 500);
define("MAX_FILE_HEIGHT", 500);
define("CURRENCY", "BYR");

require_once(MODULES_DIR."db.class.php");

# Данные для связи с БД
define("DB_SETTINGS", array(
  "host" => "127.0.0.1",
  "user" => "root",
  "pass" => "",
  "name" => "pr_v1"
));
define("DB_TABLES", array(
  "users" => "users",
  "groups" => "groups"
));
define("DBT_USERS", array(
  "id" => "id",
  "name" => "name",
  "age" => "age",
  "gender" => "sex",
  "email" => "email",
  "money" => "money",
  "group_id" => "group_id",
  "skype" => "skype",
  "vk" => "vk",
  "facebook" => "facebook",
  "login" => "login",
  "pass" => "password",
  "def_avatar" => "default_avatar",
  "tmp" => "tmp"
));
define("DBT_GROUPS", array(
  "id" => "id",
  "name" => "name"
));

?>
