import $ from 'jquery';
import App from 'components/_app/app.js';
import Module from 'components/Module/Module.js';
import Swiper from 'libs/js/swiper.min.js';
import Cookies from 'js-cookie';

export default class Slider_Basic extends Module {
    constructor(options) {
        super({
            name: 'Slider_Basic',
            self: '.js-basic-slider-cont',
            wrap_cl: 'js-basic-slider-wrap',
            slide_cl: 'js-basic-slider-item',
            paging: '.js-basic-slider-paging',
            paging_clickable: true,
            autoplay: false,
            interaction: false,
            speed: 500,
            loop: false,
            slides: 1,
            wrapper_size: true,
            round_lengths: true,
            long_ration: 0.5,
            finger: false,
            hide_slides: false
        }, options);
    }

    mount() {
        this.$root.on('mouseenter', ::this.toggle_autoplay);
        this.$root.on('mouseleave', ::this.toggle_autoplay);
        this.$slides = $('.js-basic-slider-item', this.$root);

        // обновляем слайдер при смене ориентации
        App.body.on('orientation:change', () => {
            setTimeout(() => {
                this.slider_obj.update(true);
            }, 100);
        });

        // Запускаем autoplay после прелоадера (на Главной)
        App.body.on('preloader:done',  () => {
            if (this.slider_obj) {
                this.slider_obj.startAutoplay();
            }
        });

        // Если стоит повтор слайдов и слайд всего один, убираем повтор
        let loop = this.options.loop;
        if (this.options.loop && this.$slides.length <= 1) {
            loop = false;
        }

        // Если слайд всего один, убираем пагинациюи следование за курсором / пальцем
        let paging = this.options.paging;
        let finger = this.options.finger;
        if (this.$slides.length <= 1) {
            paging = null;
            finger = false;
        }

        // Инициализируем слайдер
        this.slider_obj = new Swiper(this.options.self, {
            autoplay: this.options.autoplay,
            autoplayDisableOnInteraction: this.options.interaction,
            // loop: this.options.loop,
            loop: loop,
            direction: 'horizontal',
            speed: this.options.speed,
            // autoHeight: true,
            // setWrapperSize: true,
            setWrapperSize: this.options.wrapper_size,
            roundLengths: this.options.round_lengths,
            slidesPerView: this.options.slides,
            watchSlidesVisibility: false,
            preloadImages: false,
            lazyLoading: false,
            lazyLoadingInPrevNext: false,
            lazyLoadingOnTransitionStart: false,
            // pagination: this.options.paging,
            pagination: paging,
            paginationClickable: this.options.paging_clickable,
            wrapperClass: this.options.wrap_cl,
            slideClass: this.options.slide_cl,
            slideVisibleClass: '_visible',
            resistance: '100%',
            longSwipesRatio: this.options.long_ration,
            prevButton: this.options.prev,
            nextButton: this.options.next,
            // updateOnImagesReady: true,
            simulateTouch: true,
            runCallbacksOnInit: false,
            followFinger: finger,
            onInit: ::this.on_init,
            onTransitionStart: ::this.on_trans_start,
            onTransitionEnd: ::this.on_trans_end
        });
    }

    on_init(swiper) {
        if (this.options.hide_slides) {
            this.$slides = $('.js-basic-slider-item', this.$root);
            this.$slides.not(this.$slides.eq(swiper.activeIndex)).addClass('_invisible');
        }
        // Если Главная страница и первый раз показывается прелоадер, останавливаем autopplay
        if (App.html.hasClass('js-main-page') && Cookies.get('preloader') === undefined) {
            swiper.stopAutoplay();
        }
        swiper.slides.removeClass('_hidden-3');
    }

    on_trans_start(swiper) {
        if (this.options.hide_slides) {
            this.$slides.removeClass('_invisible');
        }
    }

    on_trans_end(swiper) {
        if (this.options.hide_slides) {
            this.$slides.not(this.$slides.eq(swiper.activeIndex)).addClass('_invisible');
        }
    }

    toggle_autoplay(e) {
        if (e.type === 'mouseenter') {
            this.slider_obj.stopAutoplay();
        } else if (e.type === 'mouseleave') {
            this.slider_obj.startAutoplay();
        }
    }
}
