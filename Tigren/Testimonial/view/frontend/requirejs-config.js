/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

var config = {
    paths: {
        // Định nghĩa đường dẫn đến module theo chuẩn AMD
        'Tigren_Testimonial': 'Tigren_Testimonial/my-script',  // Tên module là myModule

        // Định nghĩa đường dẫn đến non-AMD library
        'nonAMDLib': 'Tigren_Testimonial/non-amd-library'  // nonAMDLib là tên thư viện
    },
    shim: {
        // Cấu hình cho non-AMD module
        'nonAMDLib': {
            deps: ['jquery'],  // non-AMD library cần jQuery
            exports: 'NonAMDLib'  // Export đối tượng từ thư viện non-AMD
        }
    }
};
