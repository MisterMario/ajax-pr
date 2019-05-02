<?php

require_once("config.php");
require_once(MODULES_DIR."user.class.php");
require_once(MODULES_DIR."auth.class.php"); // этот класс вроде не нужен
require_once(MODULES_DIR."group.class.php");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["money"])) {
  $answer = array("code" => 2, "message" => "Ошибка! Не передано поле суммы!", "html" => "");

} elseif (strlen($data["money"]) > 14) {
  $answer = array("code" => 2, "message" => "Ошибка! Нельзя ввести сумму больше чем в 14 цифр!", "html" => "");

} elseif (!preg_match("/^[0-9]{1,14}$/", $data["money"])) {
  $answer = array("code" => 2, "message" => "Ошибка! Некорректный баланс!", "html" => "");

//} elseif ((int)$data["money"] + $user->getMoney() > 999999999999999999999999999999999) {
//  $answer = array("code" => 2, "message" => "Ошибка! Баланс не может быть больше 32 разрядов!", "html" => "");

} else {
  session_start();
  $user = Auth::createUser();
  $user->setField("money", (int)$data["money"] + $user->getMoney());
  $answer = array("code" => 1, "message" => "Баланс успешно обновлен!", "html" => "");
}

echo json_encode($answer);

?>
