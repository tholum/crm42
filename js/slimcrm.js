var slimcrm = new Object;
slimcrm.tick = new Object;
slimcrm.tick.settings = new Object;
slimcrm.tick.settings.timeout = 1000;
slimcrm.tick.currently_running = false;

slimcrm.tick.phone = new Object;
slimcrm.tick.phone.call_id = '';
slimcrm.tick.phone.current_call = '';
slimcrm.tick.phone.check_phone = function( phone_number , call_id ){
    if( slimcrm.tick.phone.call_id != call_id ){
        slimcrm.tick.phone.call_id = call_id;
        slimcrm.tick.phone.current_call = phone_number;
        $('.phone_tab').click();
        //alert(phone_number);
    }
}
slimcrm.test_call = function(){
    rand_id = Math.floor(Math.random() * 11 );
    slimcrm.tick.phone.check_phone('6084060226' , rand_id );
}
slimcrm.every_reload = function(){
    $('a[title]:not .qtip').qtip({style: {name: 'cream', tip: true}}).addClass('qtip');
}

slimcrm.tick.proccess = function( object ){
    $(object).each( 
        function(i , val ){ 
            try{
                eval(val.javascript);
            } catch(e){
                slimcrm.last_error = val.javascript + e;
            }
        } 
    );
        
    slimcrm.tick.currently_running = false;
}

slimcrm.tick.run = function(){
    //alert('running');
    if( slimcrm.tick.currently_running == false ){
    slimcrm.tick.currently_running = true;
    slimcrm.tick.last_sent = new Date().getTime(); 
    $.ajax(
        {
            type: "GET", 
            url: 'ajax.php?type=runticks&format=json&user_id=' + page_object.user_id  , 
            dataType: "json" ,
            timeout: slimcrm.tick.settings.timeout - 100,
            success: slimcrm.tick.proccess,
            error: function(){slimcrm.tick.currently_running = false;} 

        });
    }
}

slimcrm.tick.start = function(){
    //alert('starting');
    slimcrm.tick.tick_id = setInterval( 'slimcrm.tick.run()', slimcrm.tick.settings.timeout );
    //alert('ran' + slimcrm.tick.tick_id);
}
slimcrm.apply_template=function(json_data){
    slimcrm.json_data = json_data;
    if( json_data.subject != ''){
        $('.email_subject').val( json_data.subject ); 
    }
    tinyMCE.set_on_all( json_data.body);
}
slimcrm.use_template=function(template_id){
    //http://10.0.11.231/beta/ajax.php?format=json&type=templates&eml_template_id=1
    $.ajax(
        {
            type: "GET", 
            url: 'ajax.php?format=json&type=templates&eml_template_id=' + template_id  , 
            dataType: "json" ,
            success: slimcrm.apply_template

        });
    
}
slimcrm.download_csv = function(page,search_options){
    get_request = '';
    for( op in  search_options){
        get_request = '&search_' + op + '=' + encodeURIComponent( search_options[op] );
        
    }
    document.location.href = 'csv_export.php?page=' + page + get_request 
//    window.location('csv_export.php?page=' + page + get_request ); 
   
}
slimcrm.screen_resize = function() {
    $('.email_display_list , .email_content').css('height' , $(window).height() - ( $('.search_header').height() + $('#main_navigation').height() + 35 ) );
$('.workspace_list').css('height' , $(window).height() - (  + $('#main_navigation').height() + 235 ) );
    $('.auto_resize_width_minus_350').css('width' , $(window).width() - 350 );
}

slimcrm.columnsort = function(event , ui ){
    slimcrm.list = [];
    slimcrm.tmp_x = 1;
    slimcrm.ui = ui;
    slimcrm.id = '#' + ui.item[0].id;
    $( '#' + ui.item[0].id ).parent().children().each(
    function(){
       slimcrm.list[slimcrm.tmp_x]=$(this).attr('id'); 
       slimcrm.tmp_x++;
    });
    page.set_bulk_order_settings(slimcrm.list , {onUpdate: function(response , root ){refresh_search();}});
    slimcrm.sort_ui = ui;
    
}
slimcrm.toggle_archive_button = function(result){
   
    if( result.archive != 1  ){
        $('.archive_button_text').html('un-archive');
    } else {
        $('.archive_button_text').html('archive');
    }
    
}


slimcrm.set_column = function( page , column , setting , value , target ){
    if( target == '' ){
        target = column + "_search_field";
    }
    page.set_column_settings(
 'bucket_search' , 
 page_object.user_id , 
 'due_date' , 
 'name' , 
 $(this).val() , 
 {target: target} , {target: target ,  onUpdate: function( root , response ){refresh_search();}} );
}
slimcrm.enable_logging = false;
slimcrm.current_log = '';
slimcrm.log = function( text ){
 if( slimcrm.enable_logging == true ){
    var currentTime = new Date()
    var hours = currentTime.getHours()
    var minutes = currentTime.getMinutes()
    if (minutes < 10){
    minutes = "0" + minutes
    }
    
     slimcrm.current_log =  slimcrm.current_log + "<br/>" + hours + ":" + minutes + " - " + text;
     $('.logger').html(slimcrm.current_log);
 }
}

slimcrm.confirm = function(settings){
    var rand_id = Math.floor( Math.random() * 11 );
    var div_id = 'confdiag' + rand_id;
    
    $('body').append('<div id=' + div_id + ' ></div>');
    if( typeof(settings.title) != 'undefined' ){
        $('#' + div_id ).attr('title',settings.title);
    }
    if( typeof(settings.question) != 'undefined' ){
        $('#' + div_id ).html(settings.question);
    }
    $( '#' + div_id  ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Yes': function() {
                if($.isFunction(settings.yes)){
                    settings.yes();
                }
                $(this).remove().dialog( 'close' );
            },
            'No': function() {
                if($.isFunction(settings.no)){
                    settings.no();
                }
                $(this).remove();$( this ).dialog( 'close' );
            }
        }
     });
    
}
slimcrm.show_log = function(){
    rand = Math.floor( Math.random() * 11 );
            slimcrm.enable_logging = true;
            $('body').append('<div class="logger" id="logger' + rand + '" title="logger" >&nbsp;</div>');
            $('#logger' + rand ).html( slimcrm.current_log ).dialog(
                {
                    close: function(event , ui){
                        slimcrm.enable_logging = false;
                        $(this).remove('');
                        $(this).dialog('destroy');},
                    minWidth: 400
                }
            );
    
//    $('body').append('<div id="logger" class="logger" style="position: fixed; top: 0px; left: 25%;width: 50%; background: black; color: green;" >' + slimcrm.current_log + '</div>' );
}

slimcrm.setup_tinyMCE = function(){
    $('.emaildashboard_compose').show();
    tinyMCE.init({
                    mode : 'specific_textareas',
                    editor_selector : 'mceeditor_500',
                    theme : 'advanced' ,
                    theme_advanced_buttons1 : 'bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,undo,redo,link,unlink,forecolor,spellchecker',
                    theme_advanced_buttons2 : '',
                    theme_advanced_buttons3 : '',
                    plugins: 'spellchecker',
                    spellchecker_rpc_url: 'tiny_mce/spellcheck/rpc.php',
                    setup: slimcrm.mceSpellCheckRuntimeHandler
                });
    
}

slimcrm.mceSpellCheckRuntimeHandler = function(ed) {
  ed.addCommand('mceSpellCheckRuntime', function() {
    t = ed.plugins.spellchecker;
    if (t.mceSpellCheckRuntimeTimer) {
      window.clearTimeout(t.mceSpellCheckRuntimeTimer);
    }
    t.mceSpellCheckRuntimeTimer = window.setTimeout(function() {
      t._done();
      t._sendRPC('checkWords', [t.selectedLang, t._getWords()], function(r) {
        if (r.length > 0) {
          t.active = 1;
          t._markWords(r);
          ed.nodeChanged();
        }
      });
     }, 3000); //3 seconds
  });
  ed.onKeyUp.add(function(ed, e) {
    ed.execCommand('mceSpellCheckRuntime');
  });
};

slimcrm.set_column = function( page , user_id , column , attribute , value ){
  
    page.set_column_settings( 
        page , 
        user_id , 
        column , attribute ,
        value , 
        { target: 'bucket_search_search_field'}, { target: 'bucket_search_search_field' , onUpdate: function( root , response ){ refresh_search();set_column_sliders(); }} );
}
slimcrm.flash_sidepanel = function(){
    $('.right_tab_right_arrow_active').dblclick();
    setTimeout( function(){ $('.right_tab_right_arrow_active').click(); },150 );
}
slimcrm.delete_flowchart = function( chart_assign_id ){
    global_task.delete_flowchartTask(chart_assign_id , {onUpdate: function(root,response){$('.right_tab_right_arrow_active').click();} })
}

slimcrm.close_case = function(){
    var rand_id = Math.floor( Math.random() * 11 );
        var tmp_id = 'close_case' + rand_id;
        $('body').append('<div id=' + tmp_id + ' >You can not close this case <br/>there are still bucket tasks open</div>');
        $('#' + tmp_id ).attr('title','Close Case');
   $( '#' + tmp_id ).dialog(
    { 
        close: function(event , ui){  
            $(this).remove();$(this).dialog('destroy'); 
        } , 
        resizable: false,
        height:140,
        modal: true,
        buttons: {
            'Ok': function() {
                $(this).remove().dialog( 'close' );
            }
        }
     });
}
slimcrm.flag = new Object;
slimcrm.flag.apply_image_link = function(src_array, dst_object_id){
    var id_link = src_array.join('|');
    $('#' + dst_object_id ).attr('src' , 'image.flag.php?size=16&id=' + id_link);
   // alert(id_link + ":" + dst_object_id);
}
slimcrm.bucket_search_apply = function(){
    for (x in slimcrm.bucket ){
        $('.a_default_search_' + x ).val(slimcrm.bucket[x]);
    }
    if( slimcrm.bucket != 'undefined' && slimcrm.bucket != null ){
        a_default_search = slimcrm.bucket;
    }
    refresh_search();
}

slimcrm.change_subpage_buttons = function( buttons ){
    slimcrm.test_buttons = buttons;
    $('.subpage_buttons').remove();
    for( button in buttons){
        var html = '<div style="display: inline-block;" class="subpage_buttons page_buttons contact_list';
        if( buttons[button].class_append != undefined ){
            html = html + ' ' + buttons[button].class_append;
        }
        html = html + '" ';
        if( buttons[button].onclick != undefined ){
            html = html + ' onclick="' + buttons[button].onclick + '" ';
        }    
        html = html + ' >';
        if( buttons[button].text != undefined ){
            html = html + buttons[button].text;
        }
        html = html + '</div>';
        $('.buttons_menu').append(html);
    }
}
/*
This function translates the event into usable keys
Some browsers handel keys differently, so I figured a level of 
obfiscation was in order :), I will add keys to this as I need them
*/
slimcrm.is_key_pressed = function(event , keymap ){
    var key = false;
    switch( keymap.key ){
        case 'UP_ARROW':
            if( event.charCode == 38 ){key = true;} else {key = false;}
        break;
        case 'DOWN_ARROW':
            if( event.charCode == 40 ){key = true;} else {key = false;}
        break;
        default:
            key = false;
        break;
    }
    if( key == true){ alert( keymap.alt + " " + event.altKey ); }
}
/*
Usage, 
*/
slimcrm.keymap = {
    on_uparrow : { key: 'UP_ARROW' , alt: false, ctrl: false }, 
    on_downarrow : { key: 'DOWN_ARROW' , alt: false, ctrl: false }
}

slimcrm.onkeypress = function( event ){
    //event.preventDefault();
   // alert(event.charCode);
    for (var key in slimcrm.keymap){
        slimcrm.is_key_pressed( event , key );
    }
}

slimcrm.onclick = function( event){
    slimcrm.flags.document_onclick(event);
    slimcrm.popup.document_onclick(event);
}
    slimcrm.muliselect_onclick=function(element,js_object,name,true_name){
        slimcrm.pause_muliselect();
        
        slimcrm.debug_ms_jsn = js_object;
        slimcrm.debug_ms_jso = window[js_object];
        slimcrm.debug_ms_jsobn = name;
        slimcrm.debug_ms_json = window[js_object][name];
        if($(element).ctl_checked()){
            window[js_object][name][$(element).val()] = $(element).val();
            eval(js_object + '_update_object(\'' + name + '\' , window[\''+js_object+'\'][\''+name+'\'] );');
        } else {
            delete window[js_object][name][$(element).val()];
            eval(js_object + '_update_object(\'' + name + '\' , window[\''+js_object+'\'][\''+name+'\'] );');
        }
        if(name != true_name){
           var tempwo = window[js_object][name];
           var num = window[js_object][name].length;
           // .length is not supported in ie < 9 and other older browsers
           if( num == undefined ){
               num = 0;
               for( var key in window[js_object][name] ){
                   num++;
               }
           }
           
           if(num > 0 ){
            eval(js_object + '_update_object(\'' + true_name + '\' , \'Multiple\' );');
            $('.a_default_search_' + true_name ).val('Multiple');
           } else {
               eval(js_object + '_update_object(\'' + true_name + '\' , \'\' );');
            $('.a_default_search_' + true_name ).val(''); 
           }
            window[js_object][name] = tempwo;
        }
    }
    slimcrm.lock_muliselect = false;
    slimcrm.document_onclick = function( e ){
          if ( $( e.target ).closest( '.muliselect' ).length === 0 && $(e.target).hasClass('inmuliselect') == false && slimcrm.lock_muliselect == false ) {
                slimcrm.etarget = e.target;
                slimcrm.e = e;
               // alert($(e.target).hasClass('inmuliselect'));
                $( '.muliselect' ).remove();
            } else {
                slimcrm.pause_muliselect();
            }
    }
    slimcrm.pause_muliselect = function(){
                 clearTimeout(slimcrm.lock_muliselect_timeout);
                slimcrm.lock_muliselect = true;
                slimcrm.lock_muliselect_timeout = setTimeout(function(){slimcrm.lock_muliselect=false;} , 250 );       
    }
    slimcrm.muliselect = function(element,js_object_name,name,object,true_name){
        clearTimeout(slimcrm.lock_muliselect_timeout);
        slimcrm.lock_muliselect = true;
        tmphtml = '<div class="muliselect" style="position: absolute;z-index:100;" >';
        $.each(object , function(index, value){
            if( value.name == ''){
                value.name = "No Bucket Assigned";
            }
            tmphtml += '<div class="inmuliselect" ><label class="inmuliselect" ><input type="checkbox" ';

            js_object = window[js_object_name];
            if(typeof js_object[name] == 'object' ){
                if(js_object[name][value.value] == value.value ){
                    tmphtml += "checked";   
                }
            } else {
                js_object[name] = {};
            }
            tmphtml += ' value="' + value.value + '" class="inmuliselect" onclick="slimcrm.muliselect_onclick( this , \'' + js_object_name + '\' , \'' + name + '\' , \'' + true_name + '\'  );" />' + value.name + '</label></div>';
        });
        tmphtml += "</div>";
        
        $('#dynamic_main').append(tmphtml);
        //alert(html)
        $('.muliselect').css("top" , $(element).offset().top).css("left" , $(element).offset().left);
            
        slimcrm.lock_muliselect_timeout = setTimeout(function(){slimcrm.lock_muliselect=false;} , 250 );
    }
    

slimcrm.calendar = new Object;
slimcrm.templates = {};
slimcrm.user_list = {};
slimcrm.users  = {
    profile_pic: function(user_id){
        if( slimcrm.user_list[user_id] != undefined ){ 
            return slimcrm.user_list[user_id].image;
        } else {
            return 'images/default.jpg';
        }
    },
    display_full_name: function(user_id){
        if( slimcrm.user_list[user_id] != undefined ){ 
            return slimcrm.user_list[user_id].first_name + " " + slimcrm.user_list[user_id].last_name;
        } else {
            return '';
        }
    }

}
slimcrm.contacts_list = {};
slimcrm.contacts = {
    search: function(object){
        string = object.term;
        var options  = _.filter(slimcrm.contacts_list , function(contact){ if( contact.company_name.toLowerCase().indexOf( string.toLowerCase() ) !== -1 && contact.type == 'Company' ){return true;} else { return false;}});
        
        $.each( options , function(index,val){ options[index].label = val.company_name; options[index].value = val.contact_id; })
        //alert( options);
        return options;
    },
    update_item: function( obj){
        slimcrm.contacts_list[obj.contact_id] = {'contact_id': obj.contact_id , 'company': obj.company , 'company_name': obj.company_name , 'first_name': obj.first_name , 'last_name': obj.last_name , 'type': obj.type };
    },
    display_name: function(contact_id){
        if( slimcrm.contacts_list[contact_id] != undefined){
            if( slimcrm.contacts_list[contact_id].type == "Company" ){
                return slimcrm.contacts_list[contact_id].company_name;
            } else {
                return slimcrm.contacts_list[contact_id].first_name + " " + slimcrm.contacts_list[contact_id].last_name;
            }
        }
    }
}

slimcrm.flags = {
    onclick_popup_paused: false,
    onclick_popup_cto: false,
    onclick_popup_pause: function(){
         clearTimeout(slimcrm.flags.onclick_popup_cto);
                slimcrm.flags.onclick_popup_paused = true;
                slimcrm.lock_muliselect_timeout = setTimeout(function(){slimcrm.lock_muliselect=false;} , 250 );   
    },
    document_onclick: function(e){
        if ( $( e.target ).closest( '.flag_edit_popup' ).length === 0 && $(e.target).hasClass('flag_edit_popup') == false && slimcrm.lock_muliselect == false ) {
            $( '.flag_edit_popup' ).remove();
        } else {
            slimcrm.flags.onclick_popup_pause();
        }
    },
    show_flags: function( selector , module_name , module_id ){
        $.getJSON('api.php?action=get_flags&module_name=' + module_name + '&module_id=' + module_id , function(data){
                    $(selector).html( slimcrm.templates.flags_default( data ));
                } );
    },
    edit_flags: function(selector,module_name , module_id ){
         $.getJSON('api.php?action=get_all_flags&module_name=' + module_name + '&module_id=' + module_id , function(data){
             data.module_name = module_name;
             data.module_id = module_id;
             $('#dynamic_main').append(  '<div style="z-index: 999;position:absolute;background: white; border-style: solid; border-width: 1px; border-color: black; border-radius: 5px;padding: 5px;" class="flag_edit_popup flags_edit_' + module_name + '_' + module_id + '">' + slimcrm.templates.flags_edit( data ) + '</div>');
                    $('.flags_edit_' + module_name + '_' + module_id  ).css("top" , $(selector).offset().top - 26 ).css("left" , $(selector).offset().left - $('.flags_edit_' + module_name + '_' + module_id).width()  + 325);
                    slimcrm.flags.onclick_popup_pause();
                } );
    },
    update: function(module_name,module_id){
        slimcrm.flags.show_flags( '.flags_' + module_name.toLowerCase() + '_' + module_id , module_name , module_id);
    }
    
}
    slimcrm.document_onclick = function( e ){
          if ( $( e.target ).closest( '.muliselect' ).length === 0 && $(e.target).hasClass('inmuliselect') == false && slimcrm.lock_muliselect == false ) {
                slimcrm.etarget = e.target;
                slimcrm.e = e;
                $( '.muliselect' ).remove();
            } else {
                slimcrm.pause_muliselect();
            }
    }
slimcrm.popup = {
    paused: false,
    paused_cto: false,
    default_style: {'position': 'absolute','background': 'white','z-index': '100'},
    pause: function(){
        slimcrm.popup.paused = true;
        clearTimeout(slimcrm.popup.paused_cto);
        slimcrm.popup.paused = true;
        slimcrm.popup.paused_cto = setTimeout(function(){slimcrm.popup.paused = false;},250);
    },
    popup_template: _.template('<div class="popup_options" style="<% _(style).each(function(line,key){ %><%= key %>:<%= line %>;<% }) %>" ><%= content %></div>'),
    open_popup: function( content , style_overide ){
        style=this.default_style;
        $.each(style_overide, function(index,value){
            style[index]=style_overide[index];
        });
        slimcrm.popup.pause();
        $('#dynamic_main').append(slimcrm.popup.popup_template({content: content, style: style }));
        slimcrm.popup.pause();
    },
    document_onclick: function(e){
        if($(e.target).parents('.popup_options').size() > 0 || $(e.target).hasClass('popup_options') || slimcrm.popup.paused == true ){
            slimcrm.popup.pause();
        } else {
            $('.popup_options').remove();
        }
    }
}

slimcrm.activity = {
    default_line: _.template('<%= action %>'),
    note_added: _.template('<%= to %>'),
    flowchart_task_submited: _.template('Task Submited'),
    flowchart_task_created: _.template('Task Created'),
    single_line: _.template('<li data-activity_id="<%= line.activity_id %>" ><div class="small_profile" style="display: inline-block;background-image: url(\'<%= slimcrm.users.profile_pic( line.user_id) %>\')" >&nbsp;</div><%= slimcrm.activity.display_line(line) %></li>'),
    display_line: function(data){
        if( slimcrm.activity[data.action] != undefined ){
            return slimcrm.activity[data.action](data);
        } else {
            slimcrm.tmp_data = data;
            return 'Not Found';
            //return slimcrm.activity['default_line'](data);
        }
    },
    update_module: function( module_name , module_id ){
        $.getJSON('api.php?action=get_activity&module_id=' + module_id + '&module_name=' + module_name, function(data){
            $("ul[data-list_type='activity']ul[data-module_name='" + module_name + "']ul[data-module_id='" + module_id + "'] li").attr('data-active','false');
            $.each(data['data'] , function ( index , value ) {
               if($("li[data-activity_id='" + data['data'][index].activity_id+ "']").size() > 0 ){
                   $("li[data-activity_id='" + data['data'][index].activity_id + "']").attr('data-active','true');
                } else {
                    parrent = { line: data['data'][index] };//Keep it in the format everywhere else passes it
                   $("ul[data-list_type='activity']ul[data-module_name='" + module_name + "']ul[data-module_id='" + module_id + "']").prepend(slimcrm.activity.single_line(parrent)); 
                }
            } );
        } );
    }
}

slimcrm.task_types = {
    global_task_dropdown: _.template('<option value="<%= global_task_status_id %>"><%= global_task_status_name %></option>'),
    global_task: _.template('<div class="task_list_order">F</div><%= name %><% if( access ){ %><select onchange="global_task.submit_flowchartTask(this.value,\'<%= chart_assign_id %>\' , \'<%= module %>\' , \'<%= module_id %>\' , \'\' , \'\' , { onUpdate: function(response,root){slimcrm.tasks.update_module(\'<%= module %>\',\'<%= module_id %>\');}} );" ><option>Actions</option><% _(options).each( function(line){ %><option value="<%= line.global_task_status_id %>"><%= line.global_task_status_name %></option><% } ) %></select><% }%><div class="right_active"><%= moment( due_date ).format("MMM Do YYYY") %></div>'),
    global_task_new: _.template('<div class="task_list_order">F</div><input data-onload_done="false" data-input_type="newtask" data-task_type="global_task" data-task_module_id="<%= task_module_id %>" data-task_module_name="<%= task_module_name %>" />'),
    global_task_new_onload: function(){
        $('input[data-input_type="newtask"]input[data-task_type="global_task"]input[data-onload_done="false"]').autocomplete({source: 'fct_lookup.php', select: function( event, ui ){ slimcrm.tasks.submit_task('global_task' , ui.item.global_task_id , $(this).data('task_module_name') , $(this).data('task_module_id') , ui.item ); }}).attr('data-onload_done',"false"); 
    },
}

slimcrm.tasks = {
    submit_task: function( task_type , task_id , module_name , module_id , options){
        switch( task_type){
            case 'global_task':
                global_task.AddFlowChartTask(module_name,module_id, options.tree,task_id , {onUpdate: function(){slimcrm.tasks.update_module(module_name,module_id);}} );
            break;
        }
    },
    single_line: _.template('<li data-task_type="<%= line.task_type %>" data-task_id="<%= line.task_id %>" class="task_<%= line.task_type %>_<%= line.task_id %>" ><%= slimcrm.workspace.display_task_line(line) %></li>'),
    new_task_line: _.template('<li data-task_type="<%= line.task_type %>"><%= slimcrm.tasks.display_new_task_line(line) %></li>'),
    new_task_object: { line: { task_type: 'global_task' }},
    new_task: function( module_name , module_id ){
        var object = slimcrm.tasks.new_task_object;
        object.line.task_module_name = module_name;
        object.line.task_module_id = module_id;
        $("ul[data-list_type='tasks']ul[data-module_name='" + module_name + "']ul[data-module_id='" + module_id + "'] li.list_footer").before(slimcrm.tasks.new_task_line(object));
        switch(object.line.task_type){
            case "global_task":
                slimcrm.task_types.global_task_new_onload();
            break;
        }
    },
    display_new_task_line: function(data){
        switch(data.task_type){
            case 'global_task':
                return slimcrm.task_types.global_task_new(data);
            break;
            default:
                return '';
            break;
        }
    },
    update_module: function( module_name , module_id ){
        $.getJSON('api.php?action=get_tasks&module_name=' + module_name + '&module_id=' + module_id , function(data){
            $("ul[data-list_type='tasks']ul[data-module_name='" + module_name + "']ul[data-module_id='" + module_id + "'] li").attr('data-active','false');
            $.each(data['data'] , function ( index , value ){
                slimcrm.line_errora = data['data'][index];
                if(data['data'][index].task_type != undefined ){
                    if($("li[data-task_type='" + data['data'][index].task_type + "']li[data-task_id='" + data['data'][index].task_id+ "']").size() > 0 ){
                       $("li[data-task_type='" + data['data'][index].task_type + "']li[data-task_id='" + data['data'][index].task_id+ "']").attr('data-active','true');
                    } else {
                        
                        parrent = { line: data['data'][index] };//Keep it in the format everywhere else passes it
                        slimcrm.line=parrent;
                       $("ul[data-list_type='tasks']ul[data-module_name='" + module_name + "']ul[data-module_id='" + module_id + "'] li.list_footer").before(slimcrm.tasks.single_line(parrent)); 
                    }
                } else {
                    slimcrm.line_error = data['data'][index];
                }
            } );
            $('.list_footer').attr('data-active','true');
            $("ul[data-list_type='tasks']ul[data-module_name='" + module_name + "']ul[data-module_id='" + module_id + "'] li[data-active='false']").remove();
        } );
    }
}

slimcrm.follow = {
    follow_click: function(obj){
        slimcrm.follow.follow_module($(obj).data('module_name'),$(obj).data('module_id'));
    },
    unfollow_click: function(obj){
        slimcrm.follow.unfollow_module($(obj).data('module_name'),$(obj).data('module_id'));
    },
    follow_module: function(module_name , module_id ){
        $.getJSON('api.php?action=follow&module_name=' + module_name + '&module_id=' + module_id , function(data){
            $("a[data-module_name='" + module_name + "']a[data-module_id='" + module_id + "']a[data-button_type='follow']").click(function(){slimcrm.follow.unfollow_click(this)}).html('Un-Follow');
        });
    }, 
    unfollow_module: function(module_name,module_id){
        $.getJSON('api.php?action=unfollow&module_name=' + module_name + '&module_id=' + module_id , function(data){
            $("a[data-module_name='" + module_name + "']a[data-module_id='" + module_id + "']a[data-button_type='follow']").click(function(){slimcrm.follow.follow_click(this)}).html('Follow');
        });   
    }
}

slimcrm.workspace = {
    search_get_checked: function(module_name,name){
        if(slimcrm.workspace.search[module_name][name]){
            return ' checked="checked" ';
        } else {
            return '';
        }
    
    },
    case_names: {
        
    },

    search_open_options: function(){
        
        slimcrm.popup.open_popup(
        slimcrm.templates.workspace_settings(
            {'module_name': $('.workspace_list_item.active').data('list_type'),
            'options':slimcrm.workspace.search_options[$('.workspace_list_item.active').data('list_type')] 
        }) ,{ top: ( $('.workspace_left_panel_nav').offset().top - 10 ) + 'px',left: ( $('.workspace_left_panel_nav').offset().left ) + 'px'});
    },
    search_get_selected: function( module_name , name , value ){
        if(slimcrm.workspace.search[module_name][name] == value ){
            return ' selected="selected" ';
        } else {
            return '';
        }
    },
    search_lines: {
        'checkbox': _.template('<%= label %><input type="checkbox" onchange="slimcrm.workspace.search.<%= module_name %>.<%= name %>=$(this).ctl_checked();slimcrm.workspace.update_list();" <%= slimcrm.workspace.search_get_checked( module_name ,name ) %>/>'),
        'dropdown': _.template('<%= label %><select onchange="slimcrm.workspace.search.<%= module_name %>.<%= name %>=$(this).val();slimcrm.workspace.update_list();"><% _(options).each( function(option){ %><option <%= slimcrm.workspace.search_get_selected( module_name , name , option ) %> value="<%= option %>" ><%= option %></option><% }) %></select>')
    },
    search_options: {
        cases: {
            'self': {'name': 'self', 'type': 'checkbox','label':'Only Mine'},
            'status': {'label': 'Status', 'name': 'status', 'type': 'dropdown', 'options': {'ALL':'ALL','Active': 'Active','Completed':'Completed'}}
        },
        global_task: {
            'self': {'name': 'self', 'type': 'checkbox','label':'Only Mine'},
        },
        activity: {}
    },
    search: {
        cases: { 
            'action': 'get_cases',
            'self': true, 
            'status': 'Active'
        },
        global_task: {
            'self': true,
            'action': 'get_tasks'
        },
        activity: {}
        
    },
    get_search_line: function(module_name,data){
        data.module_name = module_name;
        return slimcrm.workspace.search_lines[data.type](data);
    },
    get_url: function(module_name, options){
        var obj = slimcrm.workspace.search[module_name];
        $.each(options,function(key,value){
            obj[key] = options[key];
        });
        obj.search=$('.workspace_search_box').val();
        return $.param(obj)
        
    },
    task_header: _.template('<li  class="lead workspace_<%= task_type %>_<%= flow_chart_id %> workspace_<%= task_type.toLowerCase() %>"><%= name %></li><ul class="items_<%= task_type %>_<%= flow_chart_id %>"></ul>') ,
    company_header: _.template('<li onclick="slimcrm.workspace.open_list_item(\'<%= contact_module_name %>\' , \'<%= contact_module_id %>\' );" class="lead workspace_<%= contact_module_name %>_<%= contact_module_id %> workspace_<%= contact_module_name.toLowerCase() %>"><%= display_name %></li><ul class="items_<%= contact_module_name %>_<%= contact_module_id %>"></ul>') ,
    case_option: _.template('<li data-module_name="cases" data-module_id="<%= case_id %>" data-workspace_template="true" data-replace_with="case_option" onclick="slimcrm.workspace.open_list_item(\'CASES\' , \'<%= case_id %>\' );" class="workspace_cases" ><%= case_id %>:<%= subject %></li>'),
    company_option: _.template('<li onclick="slimcrm.workspace.open_list_item(\'CONTACTS\' , \'<%= contact_id %>\' );" class="workspace_contacts" ><%= slimcrm.contacts.display_name(contact_id) %></li>'),
    workspace_item: _.template('<li ><%= module_name %> () <%= module_id %></li>'),
    
    display_task_line: function(data){
        switch(data.task_type){
            case 'global_task':
                return slimcrm.task_types.global_task(data);
            break;
            default:
                return '';
            break;
        }
    },
    myactivity: function(){
        
    },
    update_case_info: false,
    display_list_item: function( module_name , module_id , data ){
        switch(module_name.toLowerCase()){
            case "contacts":
                if( data.display_name == undefined ){
                    data.display_name = slimcrm.contacts.display_name(module_id);
                }
                data.contact_id = module_id;
                return slimcrm.workspace.company_option( data );
            break;
            case 'cases':
                data = slimcrm.cases.get_case_info(module_id);
                slimcrm.workspace.update_case_info = true;
                return slimcrm.workspace.case_option( data );
            break;
        }
    },
    display_list_global_task: function(data){
     $('.workspace_list').html('');
        $.each(data['data'] , function( key , val ){
            
            if( $('.items_' + val.task_type + "_" + val.flow_chart_id ).size() == 0  ){
                //slimcrm.val = val;
                //alert(val);
                $('.workspace_list').append( slimcrm.workspace.task_header(val) );
            }           
            
             $('.items_' + val.task_type + '_' + val.flow_chart_id ).append(slimcrm.workspace.display_list_item( val.module_name , val.module_id , val));
        } );
        if( slimcrm.workspace.update_case_info == true ){
            slimcrm.workspace.update_case_info = false;
            slimcrm.cases.update_case_info();
        }
    },
    display_list_case: function(data){
        $('.workspace_list').html('');
        $.each(data , function( key , val ){
            if( $('.items_' + val.contact_module_name + '_' + val.contact_module_id ).size() == 0  ){
                $('.workspace_list').append( slimcrm.workspace.company_header(data[key]) );
            }
            if(val.subject == '' ){ val.subject='No Subject';}
             $('.items_' + val.contact_module_name + '_' + val.contact_module_id ).append(slimcrm.workspace.case_option(val));
        } );
    },
    display_list_contacts: function(data){
        $('.workspace_list').html('');
        slimcrm.debug_contact = data;
       $.each(data , function( key , val ){
            $('.workspace_list').append( slimcrm.workspace.company_header( { contact_module_name: 'contacts' , contact_module_id: val.contact_id , display_name: val.company_name } ) );
        } );
    },
    open_list_item: function( module_name , module_id ){
        switch( module_name ){
            case "CASES":
                $('.workspace_right_panel').html(slimcrm.templates.workspace_2column() );
                $.getJSON('api.php?action=case_info&case_id='+ module_id , function(data){
                    slimcrm.testing=data;
                    $('.workspace_tasks').html(slimcrm.templates.workspace_right_panel( data ));
                    $('.workspace_view').html(slimcrm.templates.workspace_view( data ));
                    slimcrm.flags.show_flags('.flags_cases_' + module_id , module_name , module_id );
                } );
            break;
            case "contacts":
            case "CONTACTS":
                $('.workspace_right_panel').html(slimcrm.templates.workspace_2column() );
                $.getJSON('api.php?action=get_contact_info&contact_id='+ module_id , function(data){
                    slimcrm.testing=data;
                    $('.workspace_tasks').html(slimcrm.templates.workspace_contacts_right_panel( data ));
                    $('.workspace_view').html(slimcrm.templates.workspace_contacts_view( data ));
                    slimcrm.flags.show_flags('.flags_cases_' + module_id , module_name , module_id );
                } );
            break;    
            default:
            alert( module_name + ' ' + module_id );
            break;
        }
    },
    update_list: function(){
        slimcrm.workspace.get_list($('.workspace_list_item.active').data('list_type'));
    },
    get_list: function( module_name ){
        switch(module_name){
            case 'cases':
                $('.workspace_right_panel').html(slimcrm.templates.workspace_2column() );
                $.getJSON('api.php?' +  this.get_url( module_name , {}) , this.display_list_case );
            break;
            case 'global_task':
                $('.workspace_right_panel').html(slimcrm.templates.workspace_2column() );
                $.getJSON('api.php?' +  this.get_url( module_name , {}) , this.display_list_global_task );
            break;
            case 'contacts':
                $('.workspace_right_panel').html(slimcrm.templates.workspace_2column() );
                this.display_list_contacts( slimcrm.contacts.search( { term:$('.workspace_search_box').val() } ) );
            break;
            case 'activity':
                $('.workspace_right_panel').html(slimcrm.templates.workspace_1column() );
                $('.workspace_list').html('');
                $.getJSON('api.php?action=get_activity' , function(data){
                    $('.workspace_full').html(slimcrm.templates.activity_view(data));   
                } );
            break;
        }
        if( slimcrm.workspace.update_case_info == true ){
            slimcrm.workspace.update_case_info = false;
            slimcrm.cases.update_case_info();
        }
    }
    
}
slimcrm.debug_val = function(val){
    slimcrm.debug_test = val;
}

slimcrm.case_info = {},
//C70220D6D14
slimcrm.cases ={
    change_data: function( data){
        $('div[data-update_field="true"]div[data-module_name="cases"]div[data-module_id="' + data.data.case_id + '"]').each( 
            function(){
                if( data.data[$(this).data('display_field')] != undefined ){
                    $(this).html(data.data[$(this).data('display_field')]);
                }
            }
        );
        $('li[data-module_name="cases"]li[data-module_id="' + data.data.case_id + '"]li[data-workspace_template="true"]').each(
            function(){
                //li[data-replace_with="case_option"]
                if($(this).data('replace_with') != undefined && typeof slimcrm.workspace[$(this).data('replace_with')] == 'function'){
                    $(this).replaceWith(slimcrm.workspace[$(this).data('replace_with')](data.data) );
                }
            }
        );
        $('a[data-module_name="cases"]a[data-module_id="' + data.data.case_id + '"]a[data-live_template="true"]').each(
                function(){
                    if($(this).data('template') != undefined && typeof slimcrm.templates[$(this).data('template')] == 'function'){
                        $(this).replaceWith(slimcrm.templates[$(this).data('template')](data.data) );
                    }   
                }
            );
    },
    create_case: function(module_name , module_id , options){
        $.getJSON('api.php?' + $.param( $.extend( {'action': 'create_case' , 'module_name': module_name , 'module_id': module_id } , options ) ) , function(data){
            var htmldata = slimcrm.templates.workspace_case_full_line(data.case) ;
            slimcrm.debug_htmldata = htmldata;
            slimcrm.debug_selector='ul[data-list_type="cases"]ul[data-module_id="'+ module_id + '"]ul[data-module_name="'+ module_name.toLowerCase() + '"] li.list_footer';
            $('ul[data-list_type="cases"]ul[data-module_id="'+ module_id + '"]ul[data-module_name="'+ module_name.toLowerCase() + '"] li.list_footer').before( htmldata );
        } );
    },
    refresh: function(){
        $('.right_tab_right_arrow_active').click();    
    },
    update_case_info: function(){
        $.getJSON('api.php?action=get_cases' , function(data){
            $.each(data, function( index , value ){
                if(value.subject == ''){ value.subject = "No Subject";}
                slimcrm.case_info[value.case_id] = value;                
                $('li[data-module_name="cases"]li[data-module_id="' + value.case_id + '"]li[data-replace_with="case_option"]').replaceWith(slimcrm.workspace.case_option(value));
            });    
        } );
    },
    get_case_info: function(case_id){
        if(slimcrm.case_info[case_id] == undefined ){
            data = { 'case_id': case_id , 'subject': 'No Subject'};
        } else {
            data = slimcrm.case_info[case_id];
        }
        return data;
    }
}

slimcrm.util = {
    to_lower: function(string){
        return string.toLowerCase();
    },
    round: function(number, muliplyer){
        /*
            examples
            slimcrm.util.round( 12.89 , 1 ) == 12.9
            slimcrm.util.round( 12.89 , 2 ) == 12.99
            
        */
        return Math.round(number *  Math.pow(10,muliplyer) ) / (1 * Math.pow(10,muliplyer));
    }
}

slimcrm.timetracker = {
    reload_time: function(module_name,module_id){
        $.getJSON('api.php?action=get_timetracker&module_name=' + module_name + '&module_id= ' + module_id , function(data){
            $('div[data-module_name="' + module_name + '"]div[data-module_id="' + module_id + '"]div[data-content_type="timetracker"]').each(function(){
                $(this).replaceWith(slimcrm.templates[$(this).data('template')](data));
            });
        }  )
    },
    start_time: function(module_name,module_id){
        $.getJSON('api.php?action=timetracker_start&module_name=' + module_name + '&module_id=' + module_id , function(){ slimcrm.timetracker.reload_time(module_name,module_id); }  );
    } ,
    end_time: function(module_name,module_id){
        $.getJSON('api.php?action=timetracker_end&module_name=' + module_name + '&module_id=' + module_id ,  function(){ slimcrm.timetracker.reload_time(module_name,module_id); } );
    }
}

slimcrm.clock = {
    tick: false,
    offset: 0, //Number of seconds different between client and server
    get_now_offset: function(){
      if( this.offset < 0 ){
        return moment().subtract('ms', abs(slimcrm.clock.offset));
      }  else {
         return moment().add('ms', abs(slimcrm.clock.offset)); 
      }
    },
    check_offset: function(){
        $.getJSON('api.php?action=current_time', function(data){
            slimcrm.clock.offset = moment().diff( moment(data.fulltime, "YYYY-MM-DD HH:mm:ss")  );
            slimcrm.clock.debug = data;
        });  
    },
    start_tick: function(){
        slimcrm.clock.tick = setInterval( slimcrm.clock.run_tick , 1000 );
        slimcrm.clock.check_offset();
    },
    run_tick: function(){
        $('.clock').each(
            function(){
                $(this).html(moment().add('ms', abs(slimcrm.clock.offset)).format( $(this).data('clock_format') ) );
            }
        );
        //slimcrm.util.round( moment( end_time , "YYYY-MM-DD HH:mm:ss").diff( moment( start_time , "YYYY-MM-DD HH:mm:ss") , 'hours' , true ) , 2 )
        $('.clock_from').each(
            function(){
                $(this).html( slimcrm.util.round( moment().add('ms', abs(slimcrm.clock.offset)).diff( moment( $(this).data('from_now') , $(this).data('from_format') ) , $(this).data('measurement') , true ) , $(this).data('round') ) );  
            }
        );
    }
}
slimcrm.clock.start_tick();
