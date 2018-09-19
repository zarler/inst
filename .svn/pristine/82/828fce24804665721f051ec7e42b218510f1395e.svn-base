//ready 函数
var readyRE = /complete|loaded|interactive/;
var ready = window.ready = function (callback) {
    if (readyRE.test(document.readyState) && document.body) callback()
    else document.addEventListener('DOMContentLoaded', function () {
        callback()
    }, false)
}
//rem方法
function ready_rem() {
    var view_width = document.getElementsByTagName('html')[0].getBoundingClientRect().width;
    var _html = document.getElementsByTagName('html')[0];
    if (view_width > 750) {
        _html.style.fontSize = 750 / 16 + 'px'
    } else {
        _html.style.fontSize = view_width / 16 + 'px';
    }
}
ready(function () {
    ready_rem();
});