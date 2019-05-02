function ajaxPOST(handler, params, callback) {
  var request = new XMLHttpRequest();

  request.onreadystatechange = function() {
    if (request.readyState == 4 && request.status == 200)
      callback(request.responseText);
  };

  params = JSON.stringify(params);
  request.open("POST", handler, true);
  request.setRequestHeader("Content-Type", "application/json");
  request.send(params);
}

/* Подгружает страницы по запросу.
   add_history определяет нужно ли добавлять действие в историю. */
function getStaticPage(page, add_history) {
  var content = document.querySelector("#content");
  ajaxPOST("page.php", {"mode": page}, function(data) {
    data = JSON.parse(data);
    content.innerHTML = data.html;
    if (add_history) addToHistory(data.uri);
  });
}

/* Отправляет авторизационные данные на сервер */
function goLogin() {
  var params = {
    login: document.querySelector("input[name=login]").value,
    password: document.querySelector("input[name=password]").value,
    remember: document.querySelector("input[name=remember]:checked")? 1 : 0,
  };
  ajaxPOST("login.php", params, function(data) {
    data = JSON.parse(data);
    if (data.html.length != 0){
      document.querySelector("#content").innerHTML = data.html;
      addToHistory(data.uri);
    }
    if (data.code != 0) showMessage(data.code, data.message);
  });
}

function goRegister() {
  var params = {
    name: document.querySelector("input[name=name]").value,
    age: document.querySelector("input[name=age]").value,
    sex: document.querySelector("#sex-list").selectedIndex,
    email: document.querySelector("input[name=email]").value,
    login: document.querySelector("input[name=login]").value,
    password: document.querySelector("input[name=password]").value,
    repassword: document.querySelector("input[name=repassword]").value,
    iagree: document.querySelector("input[name=iagree]:checked")? 1 : 0,
  };
  ajaxPOST("register.php", params, function(data) {
    //document.querySelector("#empty-message").innerHTML = data;
    data = JSON.parse(data);
    if (data.html.length != 0) {
      document.querySelector("#content").innerHTML = data.html;
      addToHistory(data.uri);
    }
    if (data.code != 0) showMessage(data.code, data.message);
  });
}

function goSaveChangesToProfil() {
  var params = {
    name: document.querySelector("input[name=name]").value,
    age: document.querySelector("input[name=age]").value,
    gender: document.querySelector("#gender-list option:checked").value,
    email: document.querySelector("input[name=email]").value,
    group_id: document.querySelector("#group-list option:checked").value,
    login: document.querySelector("input[name=login]").value,
    skype: document.querySelector("input[name=skype]").value,
    vk: document.querySelector("input[name=vk]").value,
    facebook: document.querySelector("input[name=facebook]").value,
    old_password: document.querySelector("input[name=old_password]").value,
    new_password: document.querySelector("input[name=new_password]").value,
    new_repassword: document.querySelector("input[name=new_repassword]").value,
  };
  ajaxPOST("edit_profil.php", params, function(data) {
    data = JSON.parse(data);
    if (data.html.length != 0) {
      document.querySelector("#content").innerHTML = data.html;
      addToHistory(data.uri);
    }
    if (data.code != 0) showMessage(data.code, data.message);
  });
}

function goMoney() {
  var params = {
    money: Number(document.querySelector("#set-money-input").value),
  };
  var b_value = document.querySelector("#balance-value");

  if (!isNaN(params.money)) {
    if (params.money > 0) {
      ajaxPOST("money.php", params, function(data) {
        data = JSON.parse(data);
        if (data.html.length != 0) {
          document.querySelector("#content").innerHTML = data.html;
          addToHistory(data.uri);
        }

        if (data.code != 0) showMessage(data.code, data.message);
        if (data.code == 1) // Обновить баланс в профиле и скрыть блок пополенения
        {
          b_value.innerHTML = Number(b_value.innerHTML) + params.money;
          relenishAccount();
        }
      });

    } else showMessage(0, "Ошибка! Нельзя пополнить баланс суммой меньше 1!");

  } else showMessage(0, "Ошибка! Баланс содержит недопустимые символы!");
}
