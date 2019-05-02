/* Чтобы корневая страница попадала в историю. */
window.addEventListener("load", function() {
  var state, info_block;

  if (history.state === null) {
    info_block = $('div#info-block')[0];
    state = { page: (info_block !== null)? info_block.className : "login"};
    history.replaceState(state, "", state.page);
  }
});

/* Обновляет контент страницы в соотвествии с итосторией, при использовании back() и next(). */
window.addEventListener('popstate', function(e) {
  if (e.state !== null)
    getStaticPage(e.state.page);
});

function addToHistory(uri) {
  var state = { page: uri };
  history.pushState(state, "", state.page);
}
