<?php

class User {
  private $id;
  private $name;
  private $age;
  private $gender;
  private $email;
  private $money;
  private $group_id;
  private $group_name;
  private $def_avatar;
  private $skype;
  private $vk;
  private $facebook;
  private $login;

  public function __construct($method, $input) {

    if ($method == "id") { // Создание пользователя по ID
      $db = DB::getInstance();
      $res = $db->query("SELECT * FROM ".DB_TABLES["users"]." WHERE ".DBT_USERS["id"]."=$input LIMIT 1");

      if ($res) {

        $res              = $res->fetch_assoc();
        $this->id         = $res[DBT_USERS["id"]];
        $this->name       = $res[DBT_USERS["name"]];
        $this->age        = $res[DBT_USERS["age"]];
        $this->gender     = $res[DBT_USERS["gender"]];
        $this->email      = $res[DBT_USERS["email"]];
        $this->money      = $res[DBT_USERS["money"]];
        $this->group_id   = $res[DBT_USERS["group_id"]];
        $this->changeGroupName();
        $this->skype      = $res[DBT_USERS["skype"]];
        $this->vk         = $res[DBT_USERS["vk"]];
        $this->facebook   = $res[DBT_USERS["facebook"]];
        $this->login      = $res[DBT_USERS["login"]];
        $this->def_avatar = $res[DBT_USERS["def_avatar"]];
      } else $this->id    = false;

    } elseif ($method == "data" && $input) { // Создание пользователя из массива данных о нем

      $this->id         = $input[DBT_USERS["id"]];
      $this->name       = $input[DBT_USERS["name"]];
      $this->age        = $input[DBT_USERS["age"]];
      $this->gender     = $input[DBT_USERS["gender"]];
      $this->email      = $input[DBT_USERS["email"]];
      $this->money      = $input[DBT_USERS["money"]];
      $this->group_id   = $input[DBT_USERS["group_id"]];
      $this->group_name = isset($input["group_name"])? $input["group_name"] : GroupsManager::getNameById($this->group_id);
      $this->skype      = $input[DBT_USERS["skype"]];
      $this->vk         = $input[DBT_USERS["vk"]];
      $this->facebook   = $input[DBT_USERS["facebook"]];
      $this->login      = $input[DBT_USERS["login"]];
      $this->def_avatar = $input[DBT_USERS["def_avatar"]];

    } else $this->id = false;
  }

  /* Getters */
  public function getId() {
    return $this->id;
  }
  public function getName() {
    return $this->name;
  }
  public function getAge() {
    return $this->age;
  }
  public function getGenderId() {
    return $this->gender;
  }
  public function getGender() {
    return $this->gender == 0 ? "мужчина" : "женщина";
  }
  public function getEmail() {
    return $this->email;
  }
  public function getMoney() {
    return $this->money;
  }
  public function getGroupId() {
    return $this->group_id;
  }
  public function getGroupName() {
    return $this->group_name;
  }
  public function getSkype($notNull = false) { // Флаг $notNull нужен для красивого вывода поля в HTML
    return ($notNull && $this->skype == null)? "не указан" : $this->skype;
  }
  public function getVK($notNull = false) {
    return ($notNull && $this->vk == null)? "не указан" : $this->vk;
  }
  public function getFacebook($notNull = false) {
    return ($notNull && $this->facebook == null)? "не указан" : $this->facebook;
  }
  public function getLogin() {
    return $this->login;
  }
  public function getAvatarName() {
    return $this->def_avatar == 1 ? "default-avatar.png" : $this->getLogin().".png";
  }
  /* Возвращает все данные о пользователе в виде ассоциативного массива */
  public function getData() {
    return array(
      DBT_USERS["id"] => $this->id,
      DBT_USERS["name"] => $this->name,
      DBT_USERS["age"] => $this->age,
      DBT_USERS["gender"] => $this->gender,
      DBT_USERS["email"] => $this->email,
      DBT_USERS["money"] => $this->money,
      DBT_USERS["group_id"] => $this->group_id,
      "group_name" => $this->group_name, // В БД этого поля нет. Добавлено, чтобы сократить количество запросов в БД.
      DBT_USERS["skype"] => $this->skype,
      DBT_USERS["vk"] => $this->vk,
      DBT_USERS["facebook"] => $this->facebook,
      DBT_USERS["login"] => $this->login,
      DBT_USERS["def_avatar"] => $this->def_avatar,
    );
  }
  /* Setters */
  private function changeGroupName() {
    $this->group_name = GroupsManager::getNameById($this->group_id);
  }
  public function setField($field, $value) {
    if (array_key_exists($field, DBT_USERS)) {

      if ($field != DBT_USERS["pass"] && $field != DBT_USERS["tmp"]) {

        $this->$field = $value; // Переопределения значения поля
        $_SESSION["user"][DBT_USERS[$field]] = $value; // Переопределение значения в сессии
        if ($field == DBT_USERS["group_id"]) {
          $this->changeGroupName();
          $_SESSION["user"]["group_name"] = $this->group_name;
        }
      }
      $db = DB::getInstance();
      return $db->query("UPDATE ".DB_TABLES["users"]." SET ".DBT_USERS[$field]."='$value' WHERE ".DBT_USERS["id"]."='$this->id'");
    }
    return false;
  }
}
?>
