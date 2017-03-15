import './main.styl';

// import 'babel-es6-polyfill/polyfill';
import $ from 'jquery';
import App from './components/_app/app.js';
import Module from './components/Module/Module.js';
import * as modules from './components';
import * as td from 'throttle-debounce';

const init_modules = () => {
    const modules_names = [
        'Main_Page',
        'Form_Page'
    ];

    // Проставляем классы для тега html
    $('[data-html-class]').each((index, el) => {
        App.html.addClass($(el).attr('data-html-class'));
    });

    $.each(modules_names, (index, module_name) => {
        if (!(module_name in modules)) { return; }

        App.modules.push(new modules[module_name]());
    });
};

App.doc.ready(init_modules);

// App.win.load(() => {
//     App.body.trigger('app:loaded');
// });

// Обрабатывем ресайз документа
// App.container.resize(td.debounce(200, false, () => {
//     App.container.trigger('scroll');
// }));
//
// App.container.trigger('resize');