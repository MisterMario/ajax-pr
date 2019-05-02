function showMessage(type, text) {
  var message = document.querySelector("#message");
  var message_text = document.querySelector("#text");
  var empty_message = document.querySelector("#empty-message");

  if (type == 1) message.className = "successful";
  else message.className = "fail";
  message_text.innerHTML = text;
  empty_message.className = "d-none";
}

function hideMessage() {
  var message = document.querySelector("#message"),
      empty_message = document.querySelector("#empty-message");

  if (message != null && empty_message != null) {
    empty_message.className = "";
    message.className = "d-none";
  }
}

/* Показать скрыть правила */
function rules() {
  var rules = document.querySelector("#rules");
  if (rules.className.length == 0) rules.className = "d-none";
  else rules.className = "";
}

/* Показать/скрыть блок пополнения баланса */
function relenishAccount() {
  var relenish = document.querySelector("#relenish-account");
  if (relenish.className.length == 0) relenish.className = "d-none";
  else relenish.className = "";
}

/* Кнопки пополнения баланса */
function btnPlus() {
  var input = document.querySelector("#set-money-input"),
      value = Number(input.value);
  if (!isNaN(value)) {
    value += 100;
    input.value = value;
  } else showMessage(0, "Ошибка! Баланс содержит недопустимые символы!");
}

function btnMinus() {
  var input = document.querySelector("#set-money-input"),
      value = Number(input.value);
  if (!isNaN(value)) {
    value -= 100;
    input.value = value > 0 ? value : 0;
  } else showMessage(0, "Ошибка! Баланс содержит недопустимые символы!");
}
