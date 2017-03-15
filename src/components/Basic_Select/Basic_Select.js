import $ from 'jquery';
import App from '../_app/app.js';
import Module from '../Module/Module.js';
import baron from 'baron';

export default class Basic_Select extends Module {
    constructor(options) {
        super({
            name: 'Basic_Select',
            set_root: false
        }, options);
    }

    mount() {
        this.$root = this.options.root;
        this.$selected = $('.js-basic-select-selected', this.$root);
        this.$options = $('.js-basic-select-opt', this.$root);
        this.$input = $('.js-basic-select-input', this.$root);

        // Создаем скроллер
        this.opts_scroller_obj = baron({
            $: $,
            root: this.$root,
            scroller: '.js-select-scroller',
            track: '.js-select-scroller-bar-wrap',
            bar: '.js-select-scroller-bar',
            barOnCls: 'baron',
            cssGuru: true,
            // resizeDebounce: .2
        }).autoUpdate();

        // Закрываем селект
        App.body.on('select:close', (e) => {
            if (e.el !== this.$selected) {
                this.$root.removeClass('_open');
            }
        });

        // Открываем селект
        this.$selected.click(() => {
            App.body.trigger({
                type: 'select:close',
                el: this.$selected
            });
            this.$root.toggleClass('_open');
        });

        // Клики по опциям
        this.$options.click((e) => {
            let _self = $(e.currentTarget);

            this.$selected.html(_self.html());
            this.$input.val(_self.attr('data-val'));
            this.$input.trigger('change');
            this.$root.removeClass('_open');
            this.$options.removeClass('_selected');
            _self.addClass('_selected');
        });

        // Выставляем начальное значение
        this.$options.filter('._selected').click();
        this.$root.removeClass('_open');
    }
}
