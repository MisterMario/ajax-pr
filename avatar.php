<?php

require_once("config.php");
require_once(MODULES_DIR."user.class.php");
require_once(MODULES_DIR."auth.class.php"); // этот класс вроде не нужен
require_once(MODULES_DIR."group.class.php");

if (!$_FILES["file"]["name"]) {
  $answer = array("code" => 2, "message" => "Ошибка! Файл не загружен!", "html" => "");

} elseif (!is_uploaded_file($_FILES["file"]["tmp_name"])) {
  $answer = array("code" => 2, "message" => "Ошибка доступа к файлу!", "html" => "");

} elseif ($_FILES["file"]["error"] > 0) {
  $answer = array("code" => 2, "message" => "Ошибка при загрузке со стороны сервера!", "html" => "");

} elseif (filesize($_FILES["file"]["tmp_name"]) > MAX_FILE_SIZE) {
  $answer = array("code" => 2, "message" => "Ошибка! Загруженный файл имеет слишком большой размер!", "html" => "");

} elseif (!preg_match("/.{1,}\.png/", $_FILES["file"]["name"])) {
  $answer = array("code" => 2, "message" => "Ошибка! Недопустимое расширение файла!", "html" => "");

} else {
  $img_size = getimagesize($_FILES["file"]["tmp_name"]);
  if ($img_size[0] > MAX_FILE_WIDTH || $img_size[1] > MAX_FILE_HEIGHT) {
    $answer = array("code" => 2, "message" => "Ошибка! Максимальные размеры файла 500x500!", "html" => "");

  } else {
    session_start();
    $user = Auth::createUser();
    $db = DB::getInstance();
    $file_exists = false;

    if ($user && (gettype($user) === "object" && $user->getId())) {

      // Нужно ли переопределять поле def_avatar в базе. По умолчанию значение этого поля - 1.
      // Если у пользователя нет файла аватарки, значит он ее не менял и значение у поля def_avatar == 1.
      if (file_exists(A_DIR.$user->getLogin().".png")) $file_exists = true;
      if (move_uploaded_file($_FILES["file"]["tmp_name"], A_DIR.$user->getLogin().".png")) {

        if (!$file_exists) $user->setField("def_avatar", 0);
        $answer = array("code" => 1, "message" => "Аватар успешно обновлен!", "html" => A_DIR.$user->getAvatarName());

      } else $answer = array("code" => 2, "message" => "Ошибка при перемещении файла!", "html" => "");
    } else $answer = array("code" => 2, "message" => "Ошибка! Пользователь не авторизован", "html" => "");
  }
}

echo json_encode($answer);

?>
