( function( $ ) {
    'use strict';

    $( function() {

        $( '.js-es-tabs' ).each( function() {
            var $links_container = $( this ).find( '.js-es-tabs__links' );
            var $content_container = $( this ).find( '.js-es-tabs__content' );

            $links_container.find( 'a' ).on( 'click', function() {
                var $container = $( $( this ).attr( 'href' ) );
                $links_container.find( 'li' ).removeClass( 'active' );
                $( this ).closest( 'li' ).addClass( 'active' );
                $content_container.find( '.js-es-tabs__tab' ).removeClass( 'active' );
                $container.addClass( 'active' );
                $( window ).trigger( 'resize' );
                // window.location.hash = $( this ).attr( 'href' );

                return false;
            } );

            var hash = window.location.hash;

            if ( hash && $content_container.find( hash ).length ) {
                $links_container.find( 'a[href=' + hash + ']' ).trigger( 'click' );
            } else if ( ! $content_container.find( '.js-es-tabs__tab.active' ).length ) {
                $links_container.find( 'li:first-child a' ).trigger( 'click' );
                console.log(1)
            }
        } );
    } );
} )( jQuery );
