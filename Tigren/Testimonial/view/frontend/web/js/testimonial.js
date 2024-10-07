/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

define(['jquery'], function ($) {
    'use strict';

    return function (config) {
        // Debug log để kiểm tra config
        console.log('Testimonial Config Data: ', config);

        if (!config || typeof config !== 'object') {
            console.error('Invalid config data provided:', config);
            return;
        }

        alert('Config data: ' + JSON.stringify(config));
    };
});
