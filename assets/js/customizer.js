wp.customize.controlConstructor[ 'bonkers-radio-image' ] = wp.customize.Control.extend( {
  ready: function() {
    var control = this;
    var values = [];
    control.container.on( 'change', '.bonkers-radio-image', function() {
      if ( jQuery(this).val() ) { control.setting( jQuery(this).val() ); }
    } );
  }
} );