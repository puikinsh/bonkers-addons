jQuery( function( $ ) {// jscs:ignore validateLineBreaks

  function mediaUpload( buttonClass ) {

    var _customMedia = true,

        _origSendAttachment = wp.media.editor.send.attachment;

    $( 'body' ).on( 'click', buttonClass, function() {

      var buttonID = '#' + $( this ).attr( 'id' );
      var button = $( buttonID );
      var id = button.attr( 'id' ).replace( '_button', '' );

      _customMedia = true;

      wp.media.editor.send.attachment = function( props, attachment ) {

        if ( _customMedia ) {

          $( '.custom_media_id' ).val( attachment.id );

          $( '.custom_media_url' ).val( attachment.url );

          $( '.custom_media_image' ).attr( 'src', attachment.url ).css( 'display', 'block' );

        } else {

          return _origSendAttachment.apply( buttonID, [ props, attachment ] );

        }

      };

      wp.media.editor.open( button );

      return false;

    } );

  }

  mediaUpload( '.custom_media_button.button' );

} );
