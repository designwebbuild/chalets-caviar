( function( $ ) {
    'use strict';

    $( function() {

        var $tooltipsterDragAvailable = $( '.js-es__available-tooltipster--drag' );
        var $tooltipsterRetsAvailable = $( '.js-es__available-tooltipster--rets' );
        var $tooltipsterSearchAvailable = $( '.js-es__available-tooltipster--search' );

        var toolTipsterConfig = {
            contentAsHTML: true,
            theme: 'tooltipster-borderless',
            side: ['right'],
            debug: false,
            // content: Estatik.tr.searchAvailable,
            interactive: true
        };

        var cloneyaConfig = {
            cloneThis: '.es-field',
            cloneButton	: '.clone',
            deleteButton: '.delete'
        };

        $tooltipsterDragAvailable.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.dragndropAvailable );

        $tooltipsterRetsAvailable.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.retsAvailable );

        $tooltipsterSearchAvailable.tooltipster( toolTipsterConfig ).tooltipster( 'content', Estatik.tr.searchAvailable );

        $('.es-clone__wrap').cloneya( cloneyaConfig );

        $( '.js-es-load-options-fields' ).change( function() {
            var type = $( this ).val();

            if ( type ) {
                $.post( ajaxurl, { action: 'es_fbuilder_load_field_options', type: type }, function( response ) {
                    $( '.js-es-fbuilder__field-options' ).html( response );
                    $('.es-clone__wrap').cloneya( cloneyaConfig );

                    $( '.es-switch-input:not(.es-checkbox)' ).esCheckbox({
                        labelTrue: Estatik.tr.yes,
                        labelFalse: Estatik.tr.no
                    });
                } );
            }
        } );
    } );
} )( jQuery );
