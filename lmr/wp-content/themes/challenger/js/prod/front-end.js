/*global jQuery */
/*jshint browser:true */
/*!
 * FitVids 1.1
 *
 * Copyright 2013, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
 * Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
 * Released under the WTFPL license - http://sam.zoy.org/wtfpl/
 *
 */

;(function( $ ){

    'use strict';

    $.fn.fitVids = function( options ) {
        var settings = {
            customSelector: null,
            ignore: null
        };

        if(!document.getElementById('fit-vids-style')) {
            // appendStyles: https://github.com/toddmotto/fluidvids/blob/master/dist/fluidvids.js
            var head = document.head || document.getElementsByTagName('head')[0];
            var css = '.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}';
            var div = document.createElement("div");
            div.innerHTML = '<p>x</p><style id="fit-vids-style">' + css + '</style>';
            head.appendChild(div.childNodes[1]);
        }

        if ( options ) {
            $.extend( settings, options );
        }

        return this.each(function(){
            var selectors = [
                'iframe[src*="player.vimeo.com"]',
                'iframe[src*="youtube.com"]',
                'iframe[src*="youtube-nocookie.com"]',
                'iframe[src*="kickstarter.com"][src*="video.html"]',
                'object',
                'embed'
            ];

            if (settings.customSelector) {
                selectors.push(settings.customSelector);
            }

            var ignoreList = '.fitvidsignore';

            if(settings.ignore) {
                ignoreList = ignoreList + ', ' + settings.ignore;
            }

            var $allVideos = $(this).find(selectors.join(','));
            $allVideos = $allVideos.not('object object'); // SwfObj conflict patch
            $allVideos = $allVideos.not(ignoreList); // Disable FitVids on this video.

            $allVideos.each(function(){
                var $this = $(this);
                if($this.parents(ignoreList).length > 0) {
                    return; // Disable FitVids on this video.
                }
                if (this.tagName.toLowerCase() === 'embed' && $this.parent('object').length || $this.parent('.fluid-width-video-wrapper').length) { return; }
                if ((!$this.css('height') && !$this.css('width')) && (isNaN($this.attr('height')) || isNaN($this.attr('width'))))
                {
                    $this.attr('height', 9);
                    $this.attr('width', 16);
                }
                var height = ( this.tagName.toLowerCase() === 'object' || ($this.attr('height') && !isNaN(parseInt($this.attr('height'), 10))) ) ? parseInt($this.attr('height'), 10) : $this.height(),
                    width = !isNaN(parseInt($this.attr('width'), 10)) ? parseInt($this.attr('width'), 10) : $this.width(),
                    aspectRatio = height / width;
                if(!$this.attr('id')){
                    var videoID = 'fitvid' + Math.floor(Math.random()*999999);
                    $this.attr('id', videoID);
                }
                $this.wrap('<div class="fluid-width-video-wrapper"></div>').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+'%');
                $this.removeAttr('height').removeAttr('width');
            });
        });
    };
// Works with either jQuery or Zepto
})( window.jQuery || window.Zepto );
jQuery(document).ready(function($){

  //----------------------------------------------------------------------------------
  //	Find elements once to improve performance
  //----------------------------------------------------------------------------------
  const body = $(document.body);
  const siteHeader = $('#site-header');
  const toggleNavigation = $('#toggle-navigation');
  const menuPrimaryContainer = $('#menu-primary-container');
  const menuPrimary = $('#menu-primary');
  const menuPrimaryItems = $('#menu-primary-items').length ? $('#menu-primary-items') : $('.menu-unset > ul');
  const menuLink = $('.menu-item').children('a');
  const parentMenuItems = menuPrimaryContainer.find('.menu-item-has-children, .page_item_has_children');
  var openMenu = false;

  //----------------------------------------------------------------------------------
  //	Functions to call immediately
  //----------------------------------------------------------------------------------

  // Featured Images - "cover" for browsers without object-fit
  objectFitAdjustment();

  // Make sub-menus open to the left if they go off screen on the right
  keepDropdownsVisible();

  // Apply FitVids to all video embeds within posts/pages
  $('.post-content').fitVids({
      customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
  });

  //----------------------------------------------------------------------------------
  //	Event triggers
  //----------------------------------------------------------------------------------

  // Watch for clicks on the mobile menu button
  toggleNavigation.on('click', openPrimaryMenu);

  // Run functions on resizing the window
  $(window).resize(function(){
    keepDropdownsVisible();
    objectFitAdjustment();
    
    // Auto-close the mobile menu if screen resized to desktop design
    if ( window.innerWidth > 799 ) {
      if ( menuPrimaryContainer.hasClass('open') ) {
        toggleNavigation.removeClass('open');
        siteHeader.removeClass('open');
        menuPrimaryContainer.removeClass('open');
        menuPrimaryContainer.css('margin-top', 0);
        menuPrimaryItems.find('li').removeClass('visible');
        body.css('overflow', 'auto');
        $('.overflow-container').css('position', 'static');
      }
    }
  });

  // Jetpack infinite scroll event that reloads posts
  $( document.body ).on( 'post-load', function () {
      objectFitAdjustment();
  } );

  // Add a class to sub-menus on focus so they can stay visible without :hover (for keyboard users)
  menuLink.focus(function(){
    $(this).parents('ul').addClass('focused');
  });
  menuLink.focusout(function(){
    $(this).parents('ul').removeClass('focused');
  });

  // If a tablet is being used, add functionality for handling desktop style submenus
  if (window.innerWidth > 799) {  
    $(window).on('touchstart', tabletSubMenus);
  }

  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  //----------------------------------------------------------------------------------
  //	Functions
  //----------------------------------------------------------------------------------
  //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  
  //----------------------------------------------------------------------------------
  //	Open/close the primary menu when user taps the mobile menu button
  //----------------------------------------------------------------------------------
  function openPrimaryMenu() {

    // Close the mobile menu
    if ( menuPrimaryContainer.hasClass('open') ) {
      $(this).removeClass('open');
      siteHeader.removeClass('open');
      menuPrimaryContainer.removeClass('open');
      menuPrimaryItems.find('li').removeClass('visible');
      body.css('overflow', 'auto');
      $('.overflow-container').css('position', 'static');

      // Change screen reader text
      $(this).children('span').text(objectL10n.openMenu);

      // Change aria text
      $(this).attr('aria-expanded', 'false');

      // Delay the margin-top so that the menu doesn't jump up instantly when closed
      setTimeout( function(){ 
          menuPrimaryContainer.css('margin-top', 0);
      }, 500)
    } else {
      siteHeader.addClass('open');
      menuPrimaryContainer.addClass('open');
      $(this).addClass('open');
      body.css('overflow', 'hidden');
      $('.overflow-container').css('position', 'fixed');

      // Change screen reader text
      $(this).children('span').text(objectL10n.closeMenu);

      // Change aria text
      $(this).attr('aria-expanded', 'true');

      // Move the menu down below the title container
      var marginTop = siteHeader.outerHeight();
      if ( body.hasClass('admin-bar') ) {
          if ( window.innerWidth < 783 ) {
              marginTop += 46;
          } else {
              marginTop += 32;
          }   
      }
      // Move further up to account for pages with the Header Box
      if ( body.hasClass('has-header-box') ) {
          marginTop -= $('#header-box').outerHeight(true);
      }
      menuPrimaryContainer.css('margin-top', marginTop + 'px');

      // Make each list item fade-in one after the next
      var delay = 200/menuPrimaryItems.children().length;;
      var currentDelay = 75
      menuPrimaryItems.find('li').each(function() {
          const li = $(this);
          setTimeout( function(){ 
              li.addClass('visible');
          }, currentDelay)
          currentDelay += delay;
      });
    }
  }

  //----------------------------------------------------------------------------------
  //	Make sub-menus open to the left if they go off screen on the right
  //----------------------------------------------------------------------------------
  function keepDropdownsVisible() {

      if ( window.innerWidth > 799 ) {
          const submenus = menuPrimary.find('.sub-menu');
          submenus.each(function () {
              if ( $(this).offset().left + $(this).width() > window.innerWidth ) {
                  $(this).addClass('flipped');
              } else {
                  $(this).removeClass('flipped');
              }
          });
      }
  }

  //----------------------------------------------------------------------------------
  //	Mimic "object-fit: cover;" in browsers that don't support this property
  //----------------------------------------------------------------------------------
  function objectFitAdjustment() {

    // If the object-fit property is not supported
    if( !('object-fit' in document.body.style) ) {
      // Loop through every Featured Image on the page
      $('.featured-image').each(function () {
        // As long as it's not a natural aspect ratio Featured Image...
        if ( !$(this).hasClass('ratio-natural') ) {
          // Get the image whether inside a link element or div.featured-image
          var image = $(this).children('img').add($(this).children('a').children('img'));
          // Don't process images twice (relevant when using infinite scroll)
          if ( image.hasClass('no-object-fit') ) {
            return;
          }
          // Add class to avoid double processing
          image.addClass('no-object-fit');
          // if the image is not wide enough to fill the space
          if (image.outerWidth() < $(this).outerWidth()) {
              // Crop top/bottom
            image.css({
              'width': '100%',
              'min-width': '100%',
              'max-width': '100%',
              'height': 'auto',
              'min-height': '100%',
              'max-height': 'none'
            });
          }
          // if the image is not tall enough to fill the space
          if (image.outerHeight() < $(this).outerHeight()) {
            // Crop left/right
            image.css({
              'height': '100%',
              'min-height': '100%',
              'max-height': '100%',
              'width': 'auto',
              'min-width': '100%',
              'max-width': 'none'
            });
          }
        }
      });
    }
  }

  //----------------------------------------------------------------------------------
  //	Add event listener for parent menu items to require two taps and allow tapping 
  //  outside of menus to close sub menus
  //----------------------------------------------------------------------------------
  function tabletSubMenus() {
    console.log('cafsdfsdfd');
    $(window).off('touchstart', tabletSubMenus);
    parentMenuItems.on('click', openDropdown);
    $(document).on('touchstart', (function(e) {
      if ( openMenu ) {
        if ($(e.target).parents('.menu-primary').length == 0) {
          parentMenuItems.removeClass('menu-open');
          openMenu = false
        }
      }
    }));
  }
  //----------------------------------------------------------------------------------
  //	Prevent first tap on a parent menu item from following link (for tablets)
  //----------------------------------------------------------------------------------
  function openDropdown(e){
    if (!$(this).hasClass('menu-open')){
      e.preventDefault();
      $(this).addClass('menu-open');
      openMenu = true;
    }
  }
});