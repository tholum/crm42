(function( $ ){

  $.fn.ctlsidetabs = function( options ) {
      var settings = $.extend( {
       'module_name'         : 'none',
       'module_id'         : 'none',
      'background'         : 'green',
      'background'         : 'green',
      'spacing': '53' ,
      'tab_class': 'right_tab',
      'active_tab_class': 'right_tab_active',
      'tabs': {t1 : '602' },
      'tabclick': 'alert("#id#");'
    }, options);
    var x = 0;
    for(tab in settings.tabs ){
        tmp_div = Math.floor(Math.random()*100000000000000);
        if( tab == 'active'){
            tab_class = settings.active_tab_class;
        } else {
            tab_class = settings.tab_class;
        }
        
        $(this).append('<div onclick="' + options.tabclick.replace('#id#' , options.tabs[tab]) + '" onDblclick="$(this).parent().children(\'.inner_body\').toggle();$(this).parent().children(\'.inner_body_sizer\').toggle();" id="tab_rand_id' + tmp_div + '" class="' + tab_class + '" style="position: absolute; top: ' + ( x * settings.spacing ) + 'px; left: -20px;" onclick="alert(\'' + settings.tabs[tab] + '\');"  >&nbsp;</div><br/>');
        x = x + 1;
        //$('#tab_rand_id' + tmp_div ).dblclick();
    }
    $(this).append('<div class="inner_body" style="width:200px;height: 350px; background: yellow;position: absolute;top: 0px;left: 0px;" >&nbsp;</div>');
    $(this).append('<div class="inner_body_sizer" style="width:200px;height: 1px; background: green; position: relative;top: 0px;left: 0px;z-index:-10;" >&nbsp;</div>');
    $(this).css('background' , settings.background );
    return this;
  };
})( jQuery );