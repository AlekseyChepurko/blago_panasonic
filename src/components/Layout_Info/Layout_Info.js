import $ from 'jquery';
import App from 'components/_app/app.js';
import Module from 'components/Module/Module.js';
import * as td from 'throttle-debounce';

export default class Layout_Info extends Module {
    constructor(options) {
        super({
            name: 'Layout_Info',
            set_root: false
        }, options);
    }

    mount() {
        // Обрабатывем ресайз документа
        App.container.resize(td.debounce(200, false, () => {
            this.on_resize();
        }));
    }

    // Отдаем тип лэйаута
    static get_layout () {
        if (!this.instance) {
            this.instance = new Layout_Info();
        }
        this.instance.on_resize();
        return this.instance.layout;
    }

    // Обрабатывем ресайз документа
    on_resize() {
        let _w = window.innerWidth;

        if (_w < App.b_point_mobile) {
            if (this.layout !== '320') {
                this.layout = '320';
                App.body.trigger({
                    type: 'layout:changed',
                    layout: this.layout
                });
            }
        } else if (_w >= App.b_point_mobile && _w < App.b_point_tablet) {
            if (this.layout !== '760') {
                this.layout = '760';
                App.body.trigger('layout:changed');
                App.body.trigger({
                    type: 'layout:changed',
                    layout: this.layout
                });
            }
        } else if (_w >= App.b_point_tablet && _w < App.b_point_desktop) {
            if (this.layout !== '1000') {
                this.layout = '1000';
                App.body.trigger('layout:changed');
                App.body.trigger({
                    type: 'layout:changed',
                    layout: this.layout
                });
            }
        } else if (_w >= App.b_point_desktop) {
            if (this.layout !== '1440') {
                this.layout = '1440';
                App.body.trigger('layout:changed');
                App.body.trigger({
                    type: 'layout:changed',
                    layout: this.layout
                });
            }
        }

        // Определяем мобильное устройство
        // if (_w <= 1280 && App.is_touch) {
        //     App.html.addClass('_mobile-device');
        // } else {
        //     App.html.removeClass('_mobile-device');
        // }
    }
}
