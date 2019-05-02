function ajax(handler, data, callback) {
  $.ajax({
    url: handler,
    type: 'POST',
    data: JSON.stringify(data),
    contentType: 'application/json; charset=utf-8',
    dataType: 'json',
    success: callback,
  });
}

/* Подгружает страницы по запросу.
   add_history определяет нужно ли добавлять действие в историю. */
function getStaticPage(page, add_history) {
  ajax("page.php", {"mode": page}, function(data) {
    $('div#content').html(data.html);
    if (add_history) addToHistory(data.uri);
  });
}

/* Отправляет авторизационные данные на сервер */
function goLogin() {
  var params = {
    login: $('input[name=login]').val(),
    password: $('input[name=password]').val(),
    remember: $('input[name=remember]:checked').val()? 1 : 0,
  };
  ajax("login.php", params, function(data) {
    if (data.html.length != 0){
      $('div#content').html(data.html);
      addToHistory(data.uri);
    }
    if (data.code != 0) showMessage(data.code, data.message);
  });
}

function goRegister() {
  var params = {
    name: $('input[name=name]').val(),
    age: $('input[name=age]').val(),
    sex: $('#sex-list option:selected').index(),
    email: $('input[name=email]').val(),
    login: $('input[name=login]').val(),
    password: $('input[name=password]').val(),
    repassword: $('input[name=repassword]').val(),
    iagree: $('input[name=iagree]:checked').val()? 1 : 0,
  };
  ajax("register.php", params, function(data) {
    if (data.html.length != 0) {
      $('div#content').html(data.html);
      addToHistory(data.uri);
    }
    if (data.code != 0) showMessage(data.code, data.message);
  });
}

function goSaveChangesToProfil() {
  var params = {
    name: $("input[name=name]").val(),
    age: $("input[name=age]").val(),
    gender: $("#gender-list option:checked").val(),
    email: $("input[name=email]").val(),
    group_id: $("#group-list option:checked").val(),
    login: $("input[name=login]").val(),
    skype: $("input[name=skype]").val(),
    vk: $("input[name=vk]").val(),
    facebook: $("input[name=facebook]").val(),
    old_password: $("input[name=old_password]").val(),
    new_password: $("input[name=new_password]").val(),
    new_repassword: $("input[name=new_repassword]").val(),
  };
  ajax("edit_profil.php", params, function(data) {
    if (data.html.length != 0) {
      $("div#content").html(data.html);
      addToHistory(data.uri);
    }
    if (data.code != 0) showMessage(data.code, data.message);
  });
}

function goMoney() {
  var money = $('input#set-money-input').val(),
      span = $('#balance-value');

  if (!money.match(/^[0-9]{1,}$/)) {
    showMessage(0, 'Ошибка! Баланс содержит недопустимые символы!');
  } else if (money.length > 14) {
    showMessage(0, 'Ошибка! Нельзя ввести сумму больше чем в 14 цифр!')
  } else {

    money = Number(money);
    ajax("money.php", { money: money }, function(data) {
      if (data.html.length != 0) {
        $('div#content').html(data.html);
        addToHistory(data.uri);
      }

      if (data.code != 0) showMessage(data.code, data.message);
      if (data.code == 1) // Обновить баланс в профиле и скрыть блок пополенения
      {
        span.html( Number( span.html() ) + money );
        relenishAccount();
      }
    });
  }
}
