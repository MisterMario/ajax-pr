<?php

# Движок спроектировал и разработал MrMario.
# Дизайн выполнен KeelGore. Верстка от MrMario.
# Разработан за 11 дней. Закончен 29 авг 2018. Последние правки внесены 3-4 сен 2018.
# Личный кабиент работает полностью на AJAX. Не нужно ни какой перезагрузки страницы.
# Папка js.old содержит JS-скрипты написанные на чистом JS, без использования jQuery. Можно удалить.

header("Content-Type: text/html; charset: UTF-8");
require_once("config.php");

ob_start();
include MODULES_DIR."content.php";
$info_block = ob_get_clean();

include "view/index.html";

?>
