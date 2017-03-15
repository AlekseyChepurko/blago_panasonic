import $ from 'jquery';
import App from '../../_app/app.js';
import Module from '../../Module/Module.js';
import Basic_Select from '../../Basic_Select/Basic_Select.js';
import Form_Page from '../Form_Page/Form_Page.js';
// import Layout_Info from 'components/Layout_Info/Layout_Info.js';

export default class Main_Page extends Module {
    constructor(options) {
        super({
            name: 'Main_Page',
            self: '.js-main-page'
        }, options);
    }

    mount() {
        this.$content = $('.js-content', this.$root);
        this.$basic_selects = $('.js-basic-select', this.$root);
        this.$linked_btns = $('.js-linked-btn', this.$root);
        this.$insurance_toggle = $('.js-good-ins-toggle', this.$root);
        this.$option_toggle = $('.js-good-opt-toggle', this.$root);
        this.$goods_form = $('.js-goods-form', this.$root);
        this.$opt_inputs = $('.js-opt-input', this.$root);
        this.$amount_inputs = $('.js-basic-select-input', this.$root);
        this.$price_total = $('.js-price-total', this.$root);

        // Создаем селекты
        this.$basic_selects.each((index, el) => {
            new Basic_Select({ root: $(el) });
        });

        // Закрываем селекты по клику за пределами селектов
        // Обработчик в src/components/Basic_Select/Basic_Select.js
        this.$goods_form.click((e) => {
            let _self = $(e.target);

            if (!_self.hasClass('js-basic-select') && !_self.closest('.js-basic-select').length) {
                App.body.trigger('select:close');
            }
        });

        // Показываем нужную вкладку
        this.$content.addClass(`_${this.$linked_btns.filter('._active').attr('data-type')}-form`);

        // Переключаем вкладки страхования (всех / каждого)
        this.$linked_btns.click((e) => {
            let _self = $(e.currentTarget);

            this.$linked_btns.removeClass('_active');
            _self.addClass('_active');
            this.$content.removeClass('_all-form _separate-form');
            this.$content.addClass(`_${_self.attr('data-type')}-form`);
        });

        // Разворачиваем "Выбрать страховку"
        this.$insurance_toggle.click((e) => {
            let _self = $(e.currentTarget);

            _self.closest('.js-good-line-wrap').toggleClass('_open');
        });

        // Считаем цену
        const set_price = (el) => {
            let _lines = el.closest('.js-goods-form').find('.js-good-line-wrap'),
                _price_field = el.closest('.js-goods-form').find('.js-final-price');

            _price_field.html('');

            _lines.each((index, el) => {
                let _el = $(el),
                    _protect = _el.find('.js-protect').filter('._on'),
                    _old_price,
                    _insurance = _el.find('.js-insurance').filter('._on'),
                    _ins_length = _el.find('.js-ins-length'),
                    _line_price = _el.find('.js-good-price');

                // Защита
                if (_protect.length) {
                    _old_price = _price_field.html() !== '' ? _price_field.html().split(' ').join('') * 1 : 0;
                    _price_field.html(App.add_spaces(_old_price + _protect.attr('data-price') * 1));
                    _line_price.val(_protect.attr('data-price') * 1);
                } else {
                    _line_price.val(0);
                }

                // Продление гарантии
                if (_insurance.length) {
                    let _input = _insurance.find('.js-opt-input').filter(':checked'),
                        _new_price = (_insurance.attr('data-price') * 1) * (_input.val() * 1),
                        _amount_select = _insurance.find('.js-basic-select-input'),
                        _amount = _amount_select.length ? _amount_select.val() * 1 : 1,
                        _opt_price = _insurance.find('.js-opt-price');

                    _old_price = _price_field.html() !== '' ? _price_field.html().split(' ').join('') * 1 : 0;
                    _opt_price.html(App.add_spaces(_new_price * _amount));
                    _price_field.html(App.add_spaces(_old_price + (_new_price * _amount)));
                    _ins_length.html(_input.val());

                    _line_price.val(_line_price.val() * 1 + _new_price * _amount);
                }
            });

            if (_price_field.html() === '') {
                _price_field.html('0');
            }

            this.$price_total.val(_price_field.html().split(' ').join(''));
        };

        // Добавляем опцию
        this.$option_toggle.click((e) => {
            let _self = $(e.currentTarget),
                _line = _self.closest('.js-good-line-wrap'),
                _opt = _self.closest('.js-good-opt-b'),
                _input = _opt.find('.js-opt-input-hidden'),
                _is_protect = _opt.hasClass('js-protect');

            _opt.toggleClass('_on');

            if (_opt.hasClass('_on')) {
                _input.val(1);

                if (_is_protect) {
                    _line.addClass('_protect');
                } else {
                    _line.addClass('_ins');
                }
            } else {
                _input.val(0);

                if (_is_protect) {
                    _line.removeClass('_protect');
                } else {
                    _line.removeClass('_ins');
                }

                // Закрываем селект
                // Обработчик в src/components/Basic_Select/Basic_Select.js
                App.body.trigger('select:close');
            }

            set_price(_self);
        });

        // Выбираем кол-во лет страховки
        this.$opt_inputs.change((e) => {
            let _self = $(e.currentTarget);
            set_price(_self);
        });

        // Выбираем кол-во товара
        this.$amount_inputs.change((e) => {
            let _self = $(e.currentTarget);
            set_price(_self);
        });

        // Отправка формы
        this.$goods_form.submit((e) => {
            e.preventDefault();

            let _self = $(e.currentTarget);

            let _ajax_opts = {
                url: _self.attr('action'),
                type: _self.attr('method'),
                data: _self.serialize(),
                dataType: 'json'
            };

            App.ajax(_ajax_opts)
                .then((data) => {
                    // console.log(data);
                    if (data.success) {
                        console.log('-- success');
                        this.$content.html(data.html);
                        App.html.addClass(data.class);
                        new Form_Page();
                        App.html.removeClass('main-page js-main-page');
                    }
                })
                .catch((msg) => {
                    console.log('-- ajax rejected');
                    console.log(msg);
                });

            return false;
        });

    }
}