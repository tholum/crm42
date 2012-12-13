(function( $ ){
  $.fn.ctl_checked = function() {     
      if($(this).attr('checked') == 'checked'){
          return true;
      } else {
          return false;
      }
  };
})( jQuery );
