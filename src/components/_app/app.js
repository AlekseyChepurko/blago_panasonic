import $ from 'jquery';
import { Promise } from 'es6-promise-polyfill';

const params = {
    doc: $(document),
    win: $(window),
    body: $('body'),
    html: $('html'),
    is_touch: $('html').hasClass('touch'),
    modules: [],
    is_ie: window.navigator.userAgent.indexOf("MSIE") > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./),
    is_edge: navigator.userAgent.indexOf("Edge") !== -1,
    is_ff: navigator.userAgent.indexOf("Firefox") !== -1,
    is_chr: navigator.userAgent.indexOf("Chrome") !== -1,
    is_op: navigator.userAgent.indexOf("Opera") !== -1,
    is_safari: navigator.userAgent.indexOf("Safari") !== -1,
    is_android: navigator.userAgent.indexOf("Android") !== -1,
    is_ios: navigator.userAgent.match(/iPhone|iPad|iPod/i),
    is_mac: navigator.userAgent.indexOf("Mac") !== -1,
    update_delay: 400,
    allow_paging: true,
    debug: true,
    b_point_mobile: 760,
    b_point_tablet: 1000,
    b_point_desktop: 1440
};

// if (params.is_touch) {
//     params.container = params.scroll_container = $('.js-app-container');
// } else {
    params.container = $(window);
    params.scroll_container = $('html').add($('body'));
// }

const methods = {
    get_width () {
        return this.win.width();
    },
    get_height () {
        return this.win.height();
    },
    get_scroll_x () {
        return window.pageXOffset || document.documentElement.scrollLeft;
    },
    get_scroll_y () {
        return window.pageYOffset || document.documentElement.scrollTop;
    },
    scroll_to (top, left = 0, speed = 300, callback = () => {}) {
        params.scroll_container.animate({ scrollTop: top }, speed, 'swing', callback);
    },
    // Функция разбивает число на разряды и отделяет их пробелом
    add_spaces (num) {
        return num.toString().split(/(?=(?:\d{3})+$)/).join(' ');
    },
    delay(ms) {
        return (result) => new Promise((resolve, reject) => { setTimeout(() => { resolve(result); }, ms); });
    },
    ajax(options) {
        return new Promise((resolve, reject) => { $.ajax(options).done(resolve).fail(reject); });
    }
};

const App = {...params, ...methods};

// if (App.is_mac) {
//     App.html.addClass('mac');
// }

// if (App.is_chr) {
//     App.html.addClass('chrome');
// }

// if (App.is_safari) {
//     App.html.addClass('safari');
// }

if (App.is_ff) {
    App.html.addClass('ff');
}

// if (Object.freeze) {
//     Object.freeze(App);
// }

export default App;