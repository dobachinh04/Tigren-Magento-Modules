/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

var config = {
    // waitSeconds: 120,

    deps: [
        'Tigren_Testimonial/js/my-script',
        'Tigren_Testimonial/js/non-amd-library'
    ],
    map: {
        '*': {
            'my-script': 'Tigren_Testimonial/js/my-script',
            'non-amd-library': 'Tigren_Testimonial/js/non-amd-library',
            'testimonial': 'Tigren_Testimonial/js/testimonial',
            'testimonial-widget': 'Tigren_Testimonial/js/testimonial-widget'
        }
    },
    shim: {
        'Tigren_Testimonial/js/legacy': {
            deps: ['jquery'],
            exports: 'legacyFunction'
        }
    },
};

// Debug log để kiểm tra nếu config được chạy
console.log('RequireJS config loaded:', config);

