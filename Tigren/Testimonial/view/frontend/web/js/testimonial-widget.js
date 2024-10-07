/**
 * @author    Tigren Solutions <info@tigren.com>
 * @copyright Copyright (c) 2024 Tigren Solutions <https://www.tigren.com>. All rights reserved.
 * @license   Open Software License ("OSL") v. 3.0
 *
 */

define([
    'jquery',
    'jquery/ui'
], function ($) {
    'use strict';

    $.widget('tigren.testimonialWidget', {
        options: {
            message: 'Hello, World!',
            buttonText: 'Show Testimonial',
            role: 'testimonial-button'
        },

        _create: function () {
            this.element
            .addClass(this.options.role)
            .text(this.options.buttonText)
            .on('click', this._showTestimonial.bind(this));
        },

        _showTestimonial: function () {
            alert(this.options.message);
        },

        changeMessage: function (newMessage) {
            this.options.message = newMessage;
        }
    });

    return $.tigren.testimonialWidget;
});
