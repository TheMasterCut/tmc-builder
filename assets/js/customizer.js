jQuery( document ).ready( function( $ ) {

    //  ----------------------------------------
    //  Enable sorting of widgets
    //  ----------------------------------------

    $( '.tmc-builder-widgets-area' ).sortable( {
        'items' :   '> .widget'
    } );

    //  ----------------------------------------
    //  Nice hover effect
    //  ----------------------------------------

    wp.customize.bind( 'preview-ready', function() {

        $( '.tmc-builder-widgets-area .customize-partial-edit-shortcut' ).hover(
            function() {

                $( this ).closest( '.widget' ).addClass( 'hover' );

            },
            function() {

                $( this ).closest( '.widget' ).removeClass( 'hover' );

            }
        );

    } );

} );