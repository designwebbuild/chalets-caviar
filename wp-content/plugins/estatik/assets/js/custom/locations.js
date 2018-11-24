(function($) {
    'use strict';

    var priority = Estatik.widgets.search.initPriority;

    /**
     * Append select field options.
     *
     * @param items
     * @param $el
     */
    function appendOptions(items, $el) {
        items = JSON.parse(items);

        if (items) {
            var label = $el.find('option[value=0]').html();
            label = label ? label : Estatik.tr.select_location;
            $el.html('<option value="">' + label + '</option>');
            for (var i in items) {
                if ( ! items[i].long_name ) continue;
                $el.append('<option value="' + items[i].id + '">' + items[i].long_name + '</option>');
            }
            $el.removeProp('disabled');
        }
    }

    /**
     * Load location items.
     *
     * @param object
     * @param $el
     */
    function loadItems(object, $el) {
        object.action = 'es_get_location_items';
        $.post(Estatik.ajaxurl, object, function(response) {
            appendOptions(response, $el);
            var val = $el.data('value');
            if (val) {
                $el.val(val);
                $el.trigger('change');
            }
            $el.esDropDown('rebuild');
        });
    }

    /**
     * Initialize base field location.
     *
     * @param priority
     */
    function initBaseLocation(priority) {
        if (priority) {
            for (var i in priority) {
                var $initField = $('[data-type=' + i + ']');
                if ($initField.length) {
                    loadItems({type: i, status: 'initialize'}, $initField);
                    break;
                }
            }
        }
    }

    $(function() {
        initBaseLocation(priority);

        $(document).on('change', '.js-es-location', function() {
            var $el = $(this);
            var type = $el.data('type');
            var val = $el.val();

            if (priority[type]) {

                for (var i in priority[type]) {
                    if ( typeof priority[type][i] == 'string' ) {
                        var $depEl = $('[data-type=' + priority[type][i] + ']');
                        if ($depEl) {
                            loadItems({type: priority[type][i], status: 'dependency', val: val}, $depEl);
                        }
                    }
                }
            }
        });
    });
})(jQuery);
