import $ from 'jquery';
import 'jquery.inputmask';
import 'inputmask.date.extension';
import 'inputmask.numeric.extension';
/*import App from 'components/_app/app.js';*/
import App from '../_app/app.js';
import Module from 'components/Module/Module.js';

export default class Send_Form extends Module {
    constructor(options) {
        super({
            name: 'Form',
            self: '.js-send-form'
        }, options);
    }

    mount() {
        this.ajax_error = 'При отправке формы произошла неизвестная ошибка. Пожалуйста, повторите попытку позднее.';
        this.$form = this.$root;
        this.$phone_inputs = $(".js-phone", this.$form);
        this.$inputs = $(".js-required", this.$form);
        this.$inputs_numbers = $(".js-required.js-num", this.$form);
        this.$send_btn = $(".js-send-btn", this.$form);
        this.$form_fields_wrapper = $(".js-form-fields-wrapper", this.$form);
        this.$success_block = $(".js-success-block", this.$form);

        //this.$phone_inputs.inputmask("+7 999 9999999", {"placeholder": "+7 xxx xxxxxxx"});
        this.$phone_inputs.inputmask({ "mask":"+7 999 9999999", "placeholder": "xxxxxxxxxx"});
        this.$inputs_numbers.inputmask({ "mask": "9", "repeat": 20, "greedy": false, 'autoUnmask' : true});

        this.form_gen();
        this.$send_btn.on('click', ::this.send_btn_click);
    }

    send_btn_click() {
        this.$form.trigger('submit');
    }

    /* проверка верно ли заполнено передаваемое поле */
    check_required_field (field, _event) {
        //let reg_email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zа-яёії\-0-9]+\.)+[a-zа-яёії]{2,}))$/i,
        let reg_email = /^(([a-zA-Z0-9_\-.]+)@([a-zA-Z0-9\-]+)\.[a-zA-Z0-9\-.]+$)/,
            reg_text = /^[а-яА-ЯёЁa-zA-Z\s-]+$/,
            reg_person = /^[а-яА-ЯёЁa-zA-Z0-9\s-]+$/,
            reg_house = /^[а-яА-ЯёЁa-zA-Z0-9/\s-]+$/,
            reg,
            is_valid,
            wrap = field.closest('.js-form-field'),
            phone,
            num,
            field_value = field.val(),
            err_field = wrap.find('.js-error'),
            res_valid = false;

        if (field.data('type') === 'email') {
            reg = reg_email;
            is_valid = reg.test(field_value);
        } else if (field.data('type') === 'any') {
            is_valid = field_value != '';
        } else if (field.data('type') === 'date') {
            var temp = field_value.replace(/\./g, '');
            is_valid = $.isNumeric(temp);
        } else if (field.data('type') === 'house') {
            reg = reg_house;
            is_valid = reg.test(field_value);
        } else if (field.data('type') === 'person') {
            reg = reg_person;
            is_valid = reg.test(field_value);
        } else if (field.data('type') === 'text') {
            reg = reg_text;
            is_valid = reg.test(field_value);
        } else if (field.data('type') === 'phone') {
            phone = field_value.replace(/\s+/g, '').replace('+', '');
            is_valid = $.isNumeric(phone);
        } else if (field.data('type') === 'num') {
            num = field_value;
            is_valid = $.isNumeric(num);
        } else if (field.data('type') === 'checkbox') {
            is_valid = field.prop('checked');
        }

        if (field.hasClass('js-pass') && field.hasClass('js-required')) {
            let pass_wrap = field.closest('.js-change-pass-form'),
                passes = pass_wrap.find('.js-pass');

            if (passes.eq(0).val() == "") {
                is_valid = false;
                passes.addClass('_empty');
                passes.eq(0).closest('.js-form-field').addClass('_error');
            } else {
                is_valid = true;
                passes.removeClass('_empty');
                passes.closest('.js-form-field').removeClass('_error');

                //if (passes.eq(0).val() == "" || (passes.eq(0).val() != passes.eq(1).val() && passes.eq(1).val() != "")) {
                if (field.attr('name') == "personal_pass_confirm" && passes.eq(0).val() != passes.eq(1).val()) {
                    is_valid = false;
                    passes.addClass('_empty');
                    passes.eq(1).closest('.js-form-field').addClass('_error');
                } else {
                    is_valid = true;
                    passes.removeClass('_empty');
                    passes.eq(1).closest('.js-form-field').removeClass('_error');
                }

                if (passes.eq(1).val() == "" && _event.type != "focusout") {
                    is_valid = true;
                }
            }
            return is_valid;
        }

        if (field_value === '' && field.hasClass('js-required') && field.data('req') === 'no') {
            return true;
        }

        if ((field_value === '' || !is_valid) && field.hasClass('js-required')) {
            wrap.addClass('_error');
            field.addClass('_empty');
        } else {
            res_valid = true;
            wrap.removeClass('_error');
            field.removeClass('_empty');
        }

        if (field_value === "" && _event.type === "keyup") {
            res_valid = true;
            wrap.removeClass('_error');
            field.removeClass('_empty');
        }

        return res_valid;
    }

    form_gen () {
        this.$inputs.on('change focusout keyup', (e) => {
            this.check_required_field($(e.currentTarget), e);

            if (this.$form.data('comp') == true) {
                this.comp_values(this.$form);
            }
        });

        this.$inputs_numbers.on('keydown', (e) => {
            /*if (App.is_touch) {
                if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105 )) {
                    return false;
                }
            } else {*/
                if (e.keyCode == 46 || e.keyCode == 8 || e.keyCode == 9 || e.keyCode == 36 || e.keyCode == 35 || e.keyCode == 39 || e.keyCode == 37 || e.keyCode == 27 || e.keyCode == 13 || (e.keyCode == 65 && e.ctrlKey === true)) {

                } else {
                    if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105 )) {
                        return false;
                    }
                }
            /*}*/
        });

        this.$form.on('submit', (e)=> {
            if (!this.before_send_form()) {
                this.$inputs.each((index, el) => {
                    this.check_required_field($(el), e);
                });
            } else {
                this.send_form();
            }
            e.preventDefault();
        });
    }

    before_send_form() {
        return ((this.$form.find('._error').length == 0) && (this.$form.find('._empty').length == 0));
    }

    comp_values(form) {
        let inputs = form.find('input');
        let _active = false;
        let form_btn = form.find('input[type="submit"]');

        inputs.each((index, el) => {
            if ($(el).val() != $(el).data('default') && $(el).attr('type') != 'submit') {
                _active = true;
                console.log( $(el).attr('name'), '"' + $(el).val() + '"', '---', '"' + $(el).data('default') + '"');
                return false;
            }
        });

        if (_active) {
            form_btn.prop('disabled', false).removeClass('_disabled');
            form.removeClass('_disabled');
        } else {
            form_btn.prop('disabled', true).addClass('_disabled');
            form.addClass('_disabled');
        }
    }

    send_form () {
        let req,
            url = this.$form.attr('action'),
            method = this.$form.attr('method'),
            $form = this.$form;

        if ($form.hasClass('_sending') || $form.hasClass('_disabled')) {
            return false;
        }

        if ($form.data('preloader')) {
            App.html.addClass($form.data('preloader'));
        }

        $form.addClass('_sending _sended');

        // делаем запрос
        req = $.ajax( {
            url: url,
            type: method,
            data: $form.serialize(),
            cache: false,
            dataType: 'json',
            processData: false,
            contentType: false
        });

        // Пришел ответ
        req.done((data, textStatus, jqXHR) => {
            $form.find('.js-ajax-error').html('').hide();

            if (data.success) {
                if ($form.data('opt') == 'refresh') {
                    location.reload();
                }

                if ($form.hasClass('js-personal-data') && $form.find('.js-pass').eq(0) != '' && $form.find('.js-change-pass-link').hasClass('_active')) {
                    $form.find('.js-pass').val('');
                    $form.find('.js-change-pass-link').trigger('click');
                }

                if ($form.hasClass('js-order-pay-form')) {
                    $form.parent().append('<div class="_hidden js-new-ajax-form"></div>');
                    $form.parent().find('.js-new-ajax-form').html(data.html);
                    $form.parent().find('.js-new-ajax-form').find('form').trigger('submit');
                }

                if ($form.hasClass('js-auth-form')) {
                    let $auth_link = $('.js-auth-menu-link'),
                        $personal_link  = $('.js-personal-cabinet-link');

                    $auth_link.hide();
                    $personal_link.show();
                    $('.js-main-menu-item').trigger('click');
                }

                /*if ($form.hasClass('js-reg-form')) {
                    let $auth_link = $('.js-auth-menu-link'),
                        $personal_link  = $('.js-personal-cabinet-link');

                    $auth_link.hide();
                    $personal_link.show();
                    $('.js-main-menu-item').trigger('click');
                }*/

                if ($form.data('success') == "only_show") {
                    this.$success_block.fadeIn();

                    setTimeout(() => {
                        this.$success_block.fadeOut();

                        if ($form.data('save') != "save_values") {
                            this.$inputs.val('').addClass('_empty');
                        }

                        $form.removeClass('_sending');
                    }, 3000);
                } else {
                    if ($form.hasClass('js-reg-form')) {
                        $form.closest('.js-left-main-block').find('.js-form-fields-wrapper').slideUp();
                        $form.closest('.js-left-main-block').find('.js-ajax-error').hide();
                        App.html.addClass('_reload');
                    }

                    if (!$form.hasClass('js-order-pay-form')) {
                        this.$form_fields_wrapper.slideUp();
                        this.$success_block.slideDown();
                    }
                }
            }
            else {
                $form.find('.js-ajax-error').html(data.message).slideDown();

                if ($form.hasClass('js-order-pay-form')) {
                    App.html.removeClass($form.data('preloader'));
                }
            }

            $form.removeClass('_sending');

            if ($form.data('preloader') && !$form.hasClass('js-order-pay-form')) {
                App.html.removeClass($form.data('preloader'));
            }

            /*if ($form.data('success') != "only_show") {
                this.$form.removeClass('_sending');
            }*/

            //that.$submit_btn.removeAttr('disabled');
        });

        // Запрос не удался
        req.fail(function (jqXHR, textStatus, errorThrown) {
            this.$form.find('.js-ajax-error').html(this.ajax_error).slideDown(150);
            this.$form.removeClass('_sending');

            if ($form.data('preloader')) {
                App.html.removeClass($form.data('preloader'));
            }
        });
    }
}
