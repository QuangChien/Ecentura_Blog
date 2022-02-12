/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/form/element/ui-select'
], function (Select) {
    'use strict';

    return Select.extend({

        setParsed: function (data) {
            var option = this.parseData(data);

            if (data.error) {
                return this;
            }

            this.options([]);
            this.setOption(option);
            this.set('newOption', option);
        },

        parseData: function (data) {
                    return {
                        'is_active': data.model['status'],
                        level: data.model['level'],
                        value: data.model['category_id'],
                        label: data.model['name'],
                        parent: data.model['parent_id']
                    };
                }
    });
});
