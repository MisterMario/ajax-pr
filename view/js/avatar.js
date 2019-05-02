function findFile() {
  $('#avatar').click();
}
function loadFile() {
  var file = $('input#avatar').prop('files')[0],
      form_data = new FormData();
  form_data.append('file', file);
  $.ajax({
    url: 'avatar.php',
    type: 'post',
    dataType: 'json', // тип ответа сервера
    contentType: false,
    cache: false, // не кешировать
    processData: false, // не преобразовывать в классическу URL строку
    data: form_data,
    success: function(data) {
      // После имени файла используется случайное значение GET v для того, чтобы браузер обновлял кешированную аватарку
      if (data.code == 1) $('img#img-avatar').attr('src',  data.html + '?v=' + Math.floor(Math.random() * (999 - 100 + 1) + 100));
      if (data.code != 0) showMessage(data.code, data.message);
    },
  });
}
