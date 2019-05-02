<?php

# Скрипт возвращает страницу по запросу
require_once("config.php");

$json = file_get_contents("php://input");
if (strlen($json) != 0) {
  $json_arr = json_decode($json, true);
  if (count($json_arr) == 1 && isset($json_arr["mode"])) $mode = $json_arr["mode"];
}

ob_start();
include MODULES_DIR."content.php";
$answer = array("code" => 0, "message" => "", "html" => ob_get_clean(), "uri" => $mode);
echo json_encode($answer);

 ?>
