define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedCustomerGroups = config.selectedCustomerGroups,
            customerGroupMap = $H(selectedCustomerGroups),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        /**
         * Show selected customer groups when edit form in associated customer group grid
         */
        $('rh_customer_groups').value = Object.toJSON(customerGroupMap);

        /**
         * Register Customer Group
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerCustomerGroup(grid, element, checked) {
            if (checked) {
                if (element.positionElement) {
                    element.positionElement.disabled = false;
                    customerGroupMap.set(element.value, element.positionElement.value);
                }
            } else {
                if (element.positionElement) {
                    element.positionElement.disabled = true;
                }
                customerGroupMap.unset(element.value);
            }
            $('rh_customer_groups').value = Object.toJSON(customerGroupMap);
            grid.reloadParams = {
                'selected_customer_groups[]': customerGroupMap.keys()
            };
        }

        /**
         * Click on customer group row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function customerGroupRowClick(grid, event) {
            var trElement = Event.findElement(event, 'tr'),
                isInput = Event.element(event).tagName === 'INPUT',
                checked = false,
                checkbox = null;

            if (trElement) {
                checkbox = Element.getElementsBySelector(trElement, 'input');

                if (checkbox[0]) {
                    checked = isInput ? checkbox[0].checked : !checkbox[0].checked;
                    gridJsObject.setCheckboxChecked(checkbox[0], checked);
                }
            }
        }

        /**
         * Change customer group position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                customerGroupMap.set(element.checkboxElement.value, element.value);
                $('rh_customer_groups').value = Object.toJSON(customerGroupMap);
            }
        }

        /**
         * Initialize customer group row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function customerGroupRowInit(grid, row) {
            var checkbox = $(row).getElementsByClassName('checkbox')[0],
                position = $(row).getElementsByClassName('input-text')[0];

            if (checkbox && position) {
                checkbox.positionElement = position;
                position.checkboxElement = checkbox;
                position.disabled = !checkbox.checked;
                position.tabIndex = tabIndex++;
                Event.observe(position, 'keyup', positionChange);
            }
        }

        gridJsObject.rowClickCallback = customerGroupRowClick;
        gridJsObject.initRowCallback = customerGroupRowInit;
        gridJsObject.checkboxCheckCallback = registerCustomerGroup;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                customerGroupRowInit(gridJsObject, row);
            });
        }
    };
});
