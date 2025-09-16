( function( $ ) {

    const panel     = $('html', window.parent.document);
    const body      = $('body');
    const siteTitle = $('#site-title');
    const headerBox = $('#header-box');

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( siteTitle.find('img').length == 0 ) {
                siteTitle.children('a').text( to );
            }
        } );
    } );
    // Tagline
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            const tagline = $('.tagline');
            if( tagline.length == 0 ) {
                $('#title-container').append('<p class="tagline"></p>');
            }
            tagline.text( to );
        } );
    } );
    
    /***** Header *****/

    // Title
    wp.customize( 'header_box_title', function( value ) {
        value.bind( function( to ) {
            const title = headerBox.find('.title');
            title.text( to );
        } );
    } );
    // Button Text
    wp.customize( 'header_box_button_text', function( value ) {
        value.bind( function( to ) {
            const button = headerBox.find('.button');
            button.text( to );
        } );
    } );
    // Button URL
    wp.customize( 'header_box_button_url', function( value ) {
        value.bind( function( to ) {
            const button = headerBox.find('.button');
            button.attr( 'href', to );
        } );
    } );
    // Title Color
    wp.customize( 'header_box_title_color', function( value ) {
        value.bind( function( to ) {
            const title = headerBox.find('.title');
            title.css( 'color', to );
        } );
    } );
    // Site title, tagline, and menu Color
    wp.customize( 'header_box_color', function( value ) {
        value.bind( function( to ) {
            siteTitle.find('a').css( 'color', to );
            $('.tagline').css( 'color', to );
            $('#toggle-navigation').find('g').css( 'fill', to );
            if ( window.innerWidth > 800 ) {
                $('.social-media-icons, #menu-primary').find('a').css( 'color', to );
                $('.social-media-icons').find('a').css( 'border-color', to );
            }
        } );
    } );
    // Button Color
    wp.customize( 'header_box_button_color', function( value ) {
        value.bind( function( to ) {
            const button = headerBox.find('.button');
            button.css( 'color', to );
        } );
    } );
    // Button Background Color
    wp.customize( 'header_box_button_bg_color', function( value ) {
        value.bind( function( to ) {
            const button = headerBox.find('.button');
            button.css( 'background', to );
        } );
    } );
    // Overlay Color
    wp.customize( 'header_box_overlay', function( value ) {
        value.bind( function( to ) {
            const overlay = $('#site-header').find('.overlay');
            overlay.css( 'background', to );
        } );
    } );
    // Overlay Opacity
    wp.customize( 'header_box_overlay_opacity', function( value ) {
        value.bind( function( to ) {
            const overlay = $('#site-header').find('.overlay');
            overlay.css( 'opacity', to );
        } );
    } );

    /***** Featured Image Size *****/

    wp.customize( 'fi_size', function( value ) {
        value.bind( function( to ) {
            $('.featured-image').css( 'padding-bottom', to + '%' );
        } );
    } );

    /***** Blog - read more *****/

    wp.customize( 'read_more_text', function( value ) {
        value.bind( function( to ) {
            $('.more-link').text( to );
        } );
    } );

} )( jQuery );