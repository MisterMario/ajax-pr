<?php

class GroupsManager {
  public static function getNameById($id) {
    $db = DB::getInstance();
    $res = $db->query("SELECT ".DBT_GROUPS["name"]." FROM ".DB_TABLES["groups"]." WHERE ".DBT_GROUPS["id"]."='$id' LIMIT 1");

    if (gettype($res) != "boolean" && $res->num_rows != 0) {
      return $res->fetch_assoc()[DBT_GROUPS["name"]];
    }
    return false;
  }
  public static function getIdByName($name) {
    $db = DB::getInstance();
    $res = $db->query("SELECT ".DBT_GROUPS["id"]." FROM ".DB_TABLES["groups"]." WHERE ".DBT_GROUPS["name"]."='$name' LIMIT 1");
  }
  public static function getGroupsList($selectedId) {
    $list = "";
    $db = DB::getInstance();
    $res = $db->query("SELECT * FROM ".DB_TABLES["groups"]);
    if (gettype($res) != "boolean" && $res->num_rows != 0) {
      ob_start();
      while ($line = $res->fetch_assoc()) {
        $group = array("id" => $line[DBT_GROUPS["id"]],
                       "name" => $line[DBT_GROUPS["name"]],
                       "selected" => $selectedId == $line[DBT_GROUPS["id"]] ? true : false);
        include VIEW_DIR."groups_list_item.html";
      }
      $list = ob_get_clean();
    }
    return $list;
  }
}

?>
