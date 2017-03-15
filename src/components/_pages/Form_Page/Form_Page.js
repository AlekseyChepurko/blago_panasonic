import $ from 'jquery';
import 'jquery.inputmask';
import 'inputmask.date.extension';
import App from '../../_app/app.js';
import Module from '../../Module/Module.js';
import Basic_Select from '../../Basic_Select/Basic_Select.js';
import 'cleave.js/dist/cleave.min';
import '../../../libs/js/dadata.js'

export default class Main_Page extends Module {
    constructor(options) {
        super({
            name: 'Form_Page',
            self: '.js-form-page'
        }, options);
    }

    mount() {
        this.$order_form = $('.js-order-form', this.$root);
        this.$items = $('.js-form-item', this.$root);
        this.$inputs = $('.js-form-input', this.$order_form);
        this.$required = this.$inputs.filter('.js-required');
        this.$basic_selects = $('.js-basic-select', this.$root);

        this.PATTERNS = {
            name: /^[а-яА-ЯёЁa-zA-Z\s-]+$/,
            name_eng: /^[a-zA-Z\s-]+$/,
            email: /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zа-яёії\-0-9]+\.)+[a-zа-яёії]{2,}))$/i,
            url: /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/
        };

        $('.js-suggestions').suggestions({
            serviceUrl: "https://suggestions.dadata.ru/suggestions/api/4_1/rs",
            token: "a211c848c8bc090fc9def42c46f97194e32e44a1",
            type: "ADDRESS",
            count: 10,
            onSelect: function(suggestion) {
                // console.log(suggestion);
            }
        });

        // Создаем селекты
        this.$basic_selects.each((index, el) => {
            new Basic_Select({ root: $(el) });
        });

        // Закрываем селекты по клику за пределами селектов
        // Обработчик в src/components/Basic_Select/Basic_Select.js
        this.$order_form.click((e) => {
            let _self = $(e.target);

            if (!_self.hasClass('js-basic-select') && !_self.closest('.js-basic-select').length) {
                App.body.trigger('select:close');
            }
        });

        $('.js-phone').inputmask({ 'mask': '+7 (999) 999-99-99' });

        // Форматируем ввод Серия паспорта
        if ($('.js-pass-series').length) {
            new Cleave($('.js-pass-series'), {
                numericOnly: true,
                blocks: [4]
            });
        }

        // Форматируем ввод Номер паспорта
        if ($('.js-pass-number').length) {
            new Cleave($('.js-pass-number'), {
                numericOnly: true,
                blocks: [6]
            });
        }

        // Форматируем ввод Код подразделения
        if ($('.js-pass-dep').length) {
            new Cleave($('.js-pass-dep'), {
                numericOnly: true,
                delimiter: '-',
                blocks: [3, 3]
            });
        }

        // Индекс
        if ($('.js-pass-index').length) {
            new Cleave($('.js-pass-index'), {
                numericOnly: true,
                blocks: [6]
            });
        }

        const process_validation = (e) => {
            let _self = $(e.currentTarget),
                _item = _self.closest('.js-form-item'),
                _type = _self.attr('data-type'),
                _valid = false;

            // Телефон
            if (_type === 'phone') {
                _valid = $.isNumeric(_self.val().replace(/\s+/g, '').replace('+', '').replace('(', '').replace(')', '').replace(/-/g, ''));
                // console.log(_self.val().replace(/\s+/g, '').replace('+', '').replace('(', '').replace(')', '').replace(/-/g, ''));
            // Текстовое сообщение
            } else if (_type === 'text') {
                _valid = _self.val().length !== 0;
            // Имя, Почта
            } else if (_type === 'name' || _type === 'email') {
                _valid = this.PATTERNS[_type].test(_self.val());
            }

            if (_valid) {
                _item.removeClass('_error').addClass('_ok');
            } else {
                _item.removeClass('_ok').addClass('_error');
            }
        };

        // Проверяем обязательные поля
        this.$required.on('blur', process_validation);

        // Отправка формы
        this.$order_form.submit(() => {
            if (this.$items.filter('._ok').length === this.$required.length) {
                return true;
            } else {
                if (this.$items.filter('._error').length) {
                    this.$items.filter('._error').eq(0).find('.js-form-input').focus();
                } else {
                    this.$required.closest('.js-form-item').not('._ok');
                    this.$required.closest('.js-form-item').not('._ok').addClass('_error');
                    this.$items.filter('._error').eq(0).find('.js-form-input').focus();
                }
                return false;
            }
        });
    }
}
