(function( wp, $ ) {// jscs:ignore validateLineBreaks
  var api;
  if ( ! wp || ! wp.customize ) {
    return;
  }

  api = wp.customize;

  api.BonkersRadioImage = api.Control.extend( {
    ready: function() {
      var control = this;
      control.container.on( 'change', '.bonkers-radio-image', function() {
        if ( $( this ).val() ) {
          control.setting( $( this ).val() );
        }
      } );
    }
  } );

  api.BonkersButton = api.Control.extend( {
    ready: function() {
      var control = this;
      control.container.on( 'click', 'a[data-section]', function( evt ) {
        var newSection = $( this ).data( 'section' ),
            oldSection = control.params.section;
        evt.preventDefault();
        if ( undefined !== newSection ) {
          api.BonkersNavigateTo = oldSection;
          api.section( newSection ).focus();
        }
      } );
    }
  } );

  api.NewSidebarSection = api.Widgets.SidebarSection.extend( {

    attachEvents: function() {
      var meta, content, section = this;

      if ( section.container.hasClass( 'cannot-expand' ) ) {
        return;
      }

      // Expand/Collapse accordion sections on click.
      section.container.find( '.accordion-section-title' ).on( 'click keydown', function( event ) {
        if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
          return;
        }
        event.preventDefault(); // Keep this AFTER the key filter above

        if ( section.expanded() ) {
          section.collapse();
        } else {
          section.expand();
        }
      } );

      section.container.find( '.customize-section-back' ).on( 'click keydown', function( event ) {
        if ( api.utils.isKeydownButNotEnterEvent( event ) ) {
          return;
        }
        event.preventDefault(); // Keep this AFTER the key filter above
        if ( section.expanded() ) {
          if ( api.BonkersNavigateTo ) {
            api.section( api.BonkersNavigateTo ).expand();
            api.BonkersNavigateTo = false;
          } else {
            section.collapse();
          }
        } else {
          section.expand();
        }
      } );

      // This is very similar to what is found for api.Panel.attachEvents().
      section.container.find( '.customize-section-title .customize-help-toggle' ).on( 'click', function() {

        meta = section.container.find( '.section-meta' );
        if ( meta.hasClass( 'cannot-expand' ) ) {
          return;
        }
        content = meta.find( '.customize-section-description:first' );
        content.toggleClass( 'open' );
        content.slideToggle();
        content.attr( 'aria-expanded', function( i, attr ) {
          return 'true' === attr ? 'false' : 'true';
        } );
      } );
    }

  } );

  // Extend epsilon button constructor
  $.extend( api.controlConstructor, {
    'bonkers-addons-display-text': api.BonkersButton,
    'bonkers-radio-image': api.BonkersRadioImage
  } );

  $.extend( api.sectionConstructor, {
    sidebar: api.NewSidebarSection
  } );

})( window.wp, jQuery );
