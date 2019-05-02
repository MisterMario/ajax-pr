function showMessage(type, text) {
  var message = $('div#message');

  if (type == 1) message.attr('class', 'successful');
  else message.attr('class', 'fail')
  $('div#text').html(text);
  $('div#empty-message').attr('class', 'd-none');
}

function hideMessage() {
  $('div#message').attr('class', 'd-none');
  $('div#empty-message').attr('class', '');
}

/* Показать скрыть правила */
function rules() {
  var rules = $('div#rules');
  if (rules.attr('class').length == 0) rules.attr('class', 'd-none');
  else rules.attr('class', '');
}

/* Показать/скрыть блок пополнения баланса */
function relenishAccount() {
  var relenish = $('div#relenish-account');
  if (relenish.attr('class').length == 0) relenish.attr('class', 'd-none');
  else relenish.attr('class', '');
  $('input#set-money-input').val('0');
}

/* Кнопки пополнения баланса */
function btnPlus() {
  var input = $("input#set-money-input"),
      value = Number(input.val());

  if (!isNaN(value)) {
    value += 100;
    input.val(value);
  } else showMessage(0, "Ошибка! Баланс содержит недопустимые символы!");
}

function btnMinus() {
  var input = $("input#set-money-input"),
      value = Number(input.val());

  if (!isNaN(value)) {
    value -= 100;
    input.val(value > 0 ? value : 0);
  } else showMessage(0, "Ошибка! Баланс содержит недопустимые символы!");
}
