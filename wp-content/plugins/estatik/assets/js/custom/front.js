( function( $ ) {
    'use strict';

    $( function() {
        var $searchWrap = $( '.es-search__wrapper' );
        var $requestForm = $( '.es-request-widget-wrap form' );
        var $responseBlock = $( '.es-response-block' );
        var $select2Tags = $(' .es-select2-tags ');

        if ( $select2Tags.length ) {
            $select2Tags.select2( {
                tags: true
            } );
        }

        $requestForm.on('submit', function() {

            var formData = $( this ).serialize();
            var $form = $( this );

            $.post( Estatik.ajaxurl, formData, function( response ) {
                response = JSON.parse( response );
                $responseBlock.html( response );
                $form.hide();
            } );

            return false;
        });

        $( document ).on( 'click', '.js-es-request-form-show', function() {
            $requestForm.show();

            if (typeof grecaptcha != 'undefined') {
                grecaptcha.reset();
            }

            $responseBlock.html('');
            $requestForm.find( 'input[type=text], input[type=tel], input[type=email], textarea' ).val( '' );
        });

        $searchWrap.find( 'select:not(.es-select2-tags)' ).esDropDown();

        $searchWrap.find('input[type=reset]' ).click( function() {
            var $form = $( this ).closest( 'form' );

            $form.find( '.es-search__field' ).find( 'input, select' )
                .val( null )
                .attr( 'value', '' )
                .attr( 'data-value', '' )
                .trigger( 'change' );

            var $select2Tags = $form.find( '.es-select2-tags' );

            if ( $select2Tags.length ) {
                $select2Tags.select2( 'val', '' );
                $select2Tags.select2( 'data', null );
                $select2Tags.find( 'option' ).removeProp( 'selected' ).removeAttr( 'selected' );
            }

            $form.find( '.js-es-location' )
                .find( 'option:first' )
                .attr( 'selected', 'selected' )
                .closest( '.es-dropdown-wrap' )
                .find( 'li.active' )
                .removeClass( 'active' );

            $form.find( '.js-es-location' )
                .esDropDown( 'rebuild' );
        } );

        // Upload ling on register page.
        $( '.js-trigger-upload' ).click( function() {
            $( $( this ).data('selector') ).trigger( 'click' );

            return false;
        } );

        // Input on register page.
        $( '.js-es-input-image' ).change( function() {
            var el = this;
            var reader = new FileReader();

            reader.onload = function( e ) {
                $( el ).closest( 'div' ).find( '.js-es-image' ).html( "<img src='" + e.target.result + "'>" );
            };

            reader.readAsDataURL( el.files[0] );

            $( '.js-trigger-upload span' ).html( Estatik.tr.replace_photo );
        } );

        $( '.js-autocomplete-wrap input' ).keyup( function() {
            var $el = $( this );
            var action = $( this ).data( 'action' );

            $.post( Estatik.ajaxurl, {
                action: action,
                s: $( this ).val()
            }, function( response ) {
                $el.closest( '.js-autocomplete-wrap' ).find( '.es-autocomplete-result' ).html( response );
            } );
        } );

        $( '.js-autocomplete-wrap' ).on( 'click', 'li', function() {
            var $el = $( this );
            var $parent = $el.closest( '.js-autocomplete-wrap' );
            $parent.find( 'input' ).val( $el.data( 'content' ) );
            $parent.find( '.es-autocomplete-result' ).html('');
        } );

        $( '.es-recaptcha-wrapper .g-recaptcha' ).each( function() {
            if ( ! $(this).closest('.contact-block__send-form-wrapper').length ) {
                var recaptcha = $(this);
                var newScaleFactor = recaptcha.parent().innerWidth() / 304;
                recaptcha.css('transform', 'scale(' + newScaleFactor + ')');
                recaptcha.css('transform-origin', '0 0');
                setTimeout( function() {
                    recaptcha.parent().height(recaptcha[0].getBoundingClientRect().height);
                }, 600 );
            }
        } );

        $( document ).on( 'click', '.js-es-wishlist-button', function() {

            var $link = $( this );
            $link.removeClass( 'error' );
            if ( ! $link.hasClass( 'preload' ) ) {
                var data = $link.data();
                data.action = 'es_wishlist_' + data.method;
                data.nonce = Estatik.settings.wishlist_nonce;
                $link.addClass( 'preload' );
                var $container = $link.closest( '#es-saved-homes' );
                var $item = $link.closest( 'li.properties' );

                $.post( Estatik.ajaxurl, data, function( response ) {

                    response = response || {};

                    if ( response.status === 'success' ) {
                        $( $link ).replaceWith( response.data );

                        if ( $container.length ) {
                            $item.fadeOut( 400, function() {
                                $item.remove();
                            } )
                        }
                    } else {
                        $link.addClass( 'error' );
                        if ( response.message ) {
                            alert( response.message );
                        }
                    }
                }, 'json' ).always( function() {
                    $link.removeClass( 'preload' );
                } ).fail( function() {
                    $link.addClass( 'error' );
                } );
            }

            return false;
        } );

        $( '.js-es-save-search' ).click( function() {
            var $btn = $( this );
            var $form = $btn.closest( 'form' );

            var label = $btn.val();

            $btn.val( Estatik.tr.saving );

            var data = new FormData( $form[0] );
            data.append( 'action', 'es_save_search' );
            data.append( 'nonce', Estatik.settings.save_search_nonce );

            if ( ! $btn.hasClass( 'es-button-green-corner' ) ) {
                $btn.addClass( 'es-button-green-corner' );
            }

            $form.find( '.js-es-search__messages' ).html('');

            $.ajax( {
                url: Estatik.ajaxurl,
                type: 'POST',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function( response ) {
                    response = response || {};

                    if ( response.status === 'success' ) {
                        $btn.val( Estatik.tr.saved );
                    } else if ( response.status === 'error' ) {
                        $btn.val( Estatik.tr.error );
                        $btn.removeClass( 'es-button-green-corner' ).addClass( 'es-button-error' );
                    }

                    if ( response.message ) {
                        $form.find( '.js-es-search__messages' ).html( response.message );
                    }
                }
            } ).always( function() {

            } ).fail( function() {
                $btn.val( Estatik.tr.error );
                $btn.removeClass( 'es-button-green-corner' ).addClass( 'es-button-error' );
            } );
        } );

        $( '.js-es-change-update-method' ).change( function() {

            var $el = $( this );
            var $msg_container = $el.closest( 'form' ).find( '.es-msg-container' );

            $msg_container.html( '' );

            var data = {
                action: 'es_change_update_method',
                nonce: Estatik.settings.save_search_change_method_nonce,
                id: $( this ).data( 'id' ),
                update_method: $( this ).val()
            };

            $.post( Estatik.ajaxurl, data, function( response ) {
                response = response || {};

                if ( response.message ) {

                    if ( response.status === 'success' ) {
                        response.message = '<p class="es-message es-message-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                    } else {
                        response.message = '<p class="es-message es-message-error"><i class="fa fa-times-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                    }
                    $msg_container.html( response.message );
                }
            }, 'json' ).fail( function() {
                alert( Estatik.tr.system_error );
            } );
        } );

        $( '.js-es-login-form' ).click( function() {

            $.get( Estatik.ajaxurl, { action: 'es_login_form' }, function( response ) {

                $.magnificPopup.open( {
                    items: {
                        src: response,
                        type: 'inline'
                    }
                } );

            } ).fail( function() {
                alert( Estatik.tr.system_error );
            } );

            return false;
        } );

        var $profile_wrapper = $( '.es-profile__wrapper--horizontal' );
        var $profile_nav = $( '.es-profile__wrapper--horizontal .es-profile__tabs-wrapper' );

        $( window ).on( 'resize', function() {
            var nav_width = 0;

            $profile_nav.find( 'li' ).each( function() {
                nav_width += $( this ).outerWidth();
            } );

            if ( $profile_nav.find( 'ul' ).hasClass( 'slick-initialized' ) ) {
                $profile_nav.find( 'ul' ).slick( 'unslick' );
            }

            if ( $profile_wrapper.width() < nav_width ) {
                $profile_nav.find( 'ul' ).slick( {
                    variableWidth: true,
                    prevArrow: '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    nextArrow: '<i class="fa fa-angle-right" aria-hidden="true"></i>'
                } );
            }
        } ).trigger( 'resize' );

        $( '.js-switch-block' ).click( function() {
            var $container = $( $( this ).data( 'block' ) );

            if ( $container.hasClass( 'hidden' ) ) {
                $container.removeClass( 'hidden' );
            } else {
                $container.addClass( 'hidden' );
            }

            return false;
        } );

        $( '.js-saved-search-save' ).click( function() {
            var $container = $( this ).closest( '.es-saved-search__item' );
            var $msg_container = $container.find( '.es-msg-container' );

            $.post( Estatik.ajaxurl, $( this ).closest( 'form' ).serialize(), function( response ) {
                response = response || {};

                if ( response.message ) {

                    if ( response.status === 'success' ) {
                        response.message = '<p class="es-message es-message-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                        $container.find( '.js-saved-search-title' ).html( response.title );
                    } else {
                        response.message = '<p class="es-message es-message-error"><i class="fa fa-times-circle-o" aria-hidden="true"></i> ' + response.message +'</p>';
                    }
                    $msg_container.html( response.message );
                }
            }, 'json' ).fail( function() {
                alert( Estatik.tr.system_error );
            } ).always( function() {
                $container.find( '.js-switch-block' ).trigger( 'click' );
            } );

            return false;
        } );
    } );
})( jQuery );
