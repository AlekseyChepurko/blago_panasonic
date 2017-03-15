import $ from 'jquery';
import App from 'components/_app/app.js';
import Module from 'components/Module/Module.js';

export default class Header extends Module {
    constructor(options) {
        super({
            name: 'Header',
            self: '.js-header'
        }, options);
    }

    mount() {
        // Обрабатывем скролл документа
        // App.container.on('scroll', (e) => {
        //     let _self = $(e.currentTarget),
        //         _scroll = _self.scrollTop();
        //
        //     if (_scroll > 0) {
        //         App.html.addClass('_header-expanded');
        //     } else {
        //         App.html.removeClass('_header-expanded');
        //     }
        // });
    }
}
