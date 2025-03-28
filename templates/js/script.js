// nếu tồn tại #button thì thực hiện
if ($('#button').length) {
    var btn = $('#button');

    $(window).scroll(function() {
        if ($(window).scrollTop() > 300) {
        btn.addClass('show');
        } else {
        btn.removeClass('show');
        }
    });

    btn.on('click', function(e) {
        e.preventDefault();
        $('html, body').animate({
        scrollTop: 0
        }, '300');
    });
};

// Khởi tạo tất cả Swiper dựa vào cấu hình HTML
if ($('.swiper-container').length) {
    $('.swiper-container').each(function() {
        const $this = $(this);
        
        // Đọc cấu hình từ data attributes
        const config = {
            slidesPerView: $this.data('slides-per-view') || 1,
            spaceBetween: $this.data('space-between') || 0,
            loop: $this.data('loop') !== undefined ? $this.data('loop') : false,
            autoplay: $this.data('autoplay') ? {
                delay: parseInt($this.data('autoplay')),
                disableOnInteraction: false
            } : false,
            effect: $this.data('effect') || 'slide',
            speed: $this.data('speed') || 300,
            breakpoints: (() => {
                const breakpointsData = $this.data('breakpoints');
                if (!breakpointsData) return null;
                if (typeof breakpointsData === 'object') return breakpointsData;
                if (typeof breakpointsData === 'string' && breakpointsData !== '') {
                    try {
                        return JSON.parse(breakpointsData);
                    } catch (e) {
                        console.error('Error parsing breakpoints:', e);
                        return null;
                    }
                }
                return null;
            })()
        };
        
        // Thêm pagination nếu cần
        if ($this.find('.swiper-pagination').length) {
            config.pagination = {
                el: $this.find('.swiper-pagination')[0],
                clickable: $this.data('pagination-clickable') !== undefined ? $this.data('pagination-clickable') : true,
                type: $this.data('pagination-type') || 'bullets'
            };
        }
        
        // Thêm navigation nếu cần
        if ($this.find('.swiper-button-next').length && $this.find('.swiper-button-prev').length) {
            config.navigation = {
                nextEl: $this.find('.swiper-button-next')[0],
                prevEl: $this.find('.swiper-button-prev')[0]
            };
        }
        
        // Khởi tạo Swiper
        new Swiper($this[0], config);
    });
}
