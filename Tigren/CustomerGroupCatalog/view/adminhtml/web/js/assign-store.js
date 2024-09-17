define([
    'mage/adminhtml/grid'
], function () {
    'use strict';

    return function (config) {
        var selectedStores = config.selectedStores,
            storeMap = $H(selectedStores),
            gridJsObject = window[config.gridJsObjectName],
            tabIndex = 1000;

        /**
         * Show selected stores when edit form in associated store grid
         */
        $('rh_stores').value = Object.toJSON(storeMap);

        /**
         * Register Store
         *
         * @param {Object} grid
         * @param {Object} element
         * @param {Boolean} checked
         */
        function registerStore(grid, element, checked) {
            if (checked) {
                if (element.positionElement) {
                    element.positionElement.disabled = false;
                    storeMap.set(element.value, element.positionElement.value);
                }
            } else {
                if (element.positionElement) {
                    element.positionElement.disabled = true;
                }
                storeMap.unset(element.value);
            }
            $('rh_stores').value = Object.toJSON(storeMap);
            grid.reloadParams = {
                'selected_stores[]': storeMap.keys()
            };
        }

        /**
         * Click on store row
         *
         * @param {Object} grid
         * @param {String} event
         */
        function storeRowClick(grid, event) {
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
         * Change store position
         *
         * @param {String} event
         */
        function positionChange(event) {
            var element = Event.element(event);

            if (element && element.checkboxElement && element.checkboxElement.checked) {
                storeMap.set(element.checkboxElement.value, element.value);
                $('rh_stores').value = Object.toJSON(storeMap);
            }
        }

        /**
         * Initialize store row
         *
         * @param {Object} grid
         * @param {String} row
         */
        function storeRowInit(grid, row) {
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

        gridJsObject.rowClickCallback = storeRowClick;
        gridJsObject.initRowCallback = storeRowInit;
        gridJsObject.checkboxCheckCallback = registerStore;

        if (gridJsObject.rows) {
            gridJsObject.rows.each(function (row) {
                storeRowInit(gridJsObject, row);
            });
        }
    };
});
