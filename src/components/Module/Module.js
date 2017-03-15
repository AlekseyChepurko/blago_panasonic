import $ from 'jquery';
import App from '../_app/app.js';

export default class Module {
    constructor(opt1, opt2) {
        this.options = {
            name: 'Module',
            set_root: true,
            mount: true
        };
        // this.options = $.extend(this.options, opt1, opt2);
        this.options = {...this.options, ...opt1, ...opt2};
        if (this.options.set_root) {
            this.$root = $(this.options.self);
            this.options.mount = this.$root.length > 0;
        }

        if (this.options.mount) {
            if (App.debug) {
                console.log(this.options.name);
            }
            this.mount();
        }
    }

    mount() {}
}