//var chat_module_name = "DEMO";
//var chat_module_id = "1";
var chat = new Object;
var chat_roster_div = "chat_roster";
var chat_window_width = 200;
var roster = {};
var chat_windows = 0;
var chat_windows_array = [];
var chat_tick_time = 2500; //Schedule how many miliseconds it refreshes the chat
var chat_roster_tick_time = 15000;
var chat_sessions = [];
var chat_tmp_last_session;
var chat_tmp_global_session_id;
var docArray;
var web_session_id = '';
    var chat_module_name;
    var chat_module_id;
    var chat_display_name; 

chat.alert_status = "off";
chat.alert_sessions = [];
chat.tick_time = chat_tick_time;
chat.display_roster = "yes";
chat.web_display_name = "Chat With Us";
chat.web_session_id = '';
chat.web_chat_button = function(){
    if( chat.web_session_id == '' ){
        chat.start_group_chat('1');
    } else {
        $('#' + chat.web_session_id + "\\:CHATWINDOW" ).show();
        $("#" + chat.web_session_id + "\\:CHATBODY").show();
        $("#" + chat.web_session_id + "\\:CHATINPUT").show();
    }
}

chat.set_web_session_id = function( xml ){
    chat.web_session_id = $(xml).find("session_id:first").text();
}

chat.start_group_chat = function( group_id ){
    var tmp_append = '';
    if( chat.web_display_name != ''  ){
        tmp_append = tmp_append + "&chat_name=" + encodeURI(chat.web_display_name );
    }
    $.ajax({type: "GET", url: 'chat.xml.php?function=group_chat&module_id=' + chat_module_id + '&module_name=' + chat_module_name + '&group_id=' + group_id + tmp_append, dataType: "xml" , success: chat.set_web_session_id });    
}

chat.web_page_start = function(){
    chat_module_name = chat.module_name ;
    chat_module_id = chat.module_id;
    chat_display_name = chat.display_name;
    chat.display_roster = "no";
    chat.chat_tick_interval = setInterval ( 'chat_tick()', chat_tick_time);
    chat.chat_status_interval = setInterval ( 'chat.update_status()' , 2000 );
    
}

chat.update_status = function(){
    $.ajax({type: "GET", url: 'chat.xml.php?function=update_status&format=xml&module_id=' + chat_module_id + '&module_name=' + chat_module_name  , dataType: "xml" });
}

chat.alert_chat_window = function( session_id ){
        switch ( chat.alert_sessions[session_id].alert_status ){
        case "off":
        default:
            chat.alert_sessions[session_id].alert_status = "on"
            chat.alert_sessions[session_id].original_background = "#333333";//$("#" + session_id + '\\:CHATHEADER').css("background");
            chat.alert_sessions[session_id].which = 0;
            chat.alert_sessions[session_id].alert_timer = window.setInterval(function() {
                //ff6600
                switch( chat.alert_sessions[session_id].which ){
                    case 0:
                        $("#" + session_id + '\\:CHATHEADER').css("background" , "#ff6600");
                        chat.alert_sessions[session_id].which = 1;
                    break;
                    case 1:
                        $("#" + session_id + '\\:CHATHEADER').css("background" , chat.alert_sessions[session_id].original_background);
                        chat.alert_sessions[session_id].which = 0;
                    break;
                }
                
            }, 1000 );
            document.getElementById(  session_id + ":CHATINPUT").onfocus = function(){
                    $("#" + session_id + '\\:CHATHEADER').css("background" , chat.alert_sessions[session_id].original_background);
                    clearInterval( chat.alert_sessions[session_id].alert_timer );
                    chat.alert_sessions[session_id].alert_status = "off"
                };
        break; 
        case "on":
            tmp_nothing = '';
        break;
    }
    
}

chat.alert = function(){
    switch ( chat.alert_status ){
        case "off":
            chat.alert_status = "on"
            chat.original_title = document.title.toString();
            chat.alert_timer = window.setInterval(function() {
                document.title = document.title == chat.original_title ? "New Message" : chat.original_title;
            }, 1000 );
            window.onfocus=function(){
                document.title = chat.original_title
                clearInterval( chat.alert_timer );
                chat.alert_status="off";
            }
        break; 
            
        
    }

}


function chat_min_window( session_id ){
    $("#" + session_id + "\\:CHATBODY").toggle();
    $("#" + session_id + "\\:CHATINPUT").toggle();
    if( $("#" + session_id + "\\:CHATINPUT").css("display") == "none"){
        $("#" + session_id + "\\:MINMAX").css("background" , "url('images/arrow_up20x.png')");
    } else {
        $("#" + session_id + "\\:MINMAX").css("background" , "url('images/arrow_down20x.png')");
    }
}

function chat_close_window( session_id ){
    var tmp_right;
    var new_right;
    $("#" + session_id + "\\:CHATWINDOW").toggle();
    var right = parseInt($("#" + session_id + "\\:CHATWINDOW").css("right"), 10);
    $("#" + session_id + "\\:CHATWINDOW").css("right", "0px");
    chat_windows--;
    for( x in chat_windows_array ){
        tmp_right = parseInt($("#" + x + "\\:CHATWINDOW").css("right"), 10);
        if( tmp_right > right ){
            new_right = tmp_right - chat_window_width;
            $("#" + x + "\\:CHATWINDOW").css("right", new_right + "px" );
        } 
    }    
    $.ajax({type: "GET", url: 'chat.xml.php?function=close_window&session_id=' + session_id + '&module_id=' + chat_module_id + '&module_name=' + chat_module_name  , dataType: "xml" , success: chat_checkSessions});
}

function chat_window_template( session_id , display_name ){
    var ChatHtml = '<div id="' + session_id + ':CHATHEADER" style="height: 25px;position: relative;width: ' +chat_window_width + 'px;text-align: center;color: white;background: #333333" onclick="chat_min_window(\'' + session_id + '\');" >' + display_name +  '<div onclick="chat_close_window(\'' + session_id + '\');" id="' + session_id + ':CLOSE" style="position: absolute; right: 7px;top: 2px;background: url(\'images/redx20.png\');width: 20px; height:20px;cursor:pointer;" />&nbsp;</div></div>';
    ChatHtml = ChatHtml + '<div id="' + session_id + ':CHATBODY" style="position:relative; height: 200px; background: #dddddd; border: 1px; solid #333333;overflow: auto; width: 200px;" ></div>';
    ChatHtml = ChatHtml + '<input type="text" onkeypress="chat_monitor_input( event , this.id )" id="' + session_id + ':CHATINPUT" style="width: ' + chat_window_width + 'px; height: 30px;position:relative;" />';
    return ChatHtml;
}
function chat_display_window( session_id , display_name ){
   
    if ( $("#" + session_id + "\\:CHATWINDOW").length != 0 ){ 
        //If window has alread been open, Check to see if it is hidden or not
        if( $("#" + session_id + "\\:CHATWINDOW").css("display") == "none"){
            //If window is hidden, Open it and possition it correctly
            var tmpWidth = chat_window_width * chat_windows;
            tmpWidth = tmpWidth + chat_window_width;
            $("#" + session_id + "\\:CHATWINDOW").css("width" , chat_window_width + "px");
            $("#" + session_id + "\\:CHATWINDOW").css("right" , tmpWidth + "px"  );
            $("#" + session_id + "\\:CHATWINDOW").toggle();
            chat_windows++;
            $("#" + session_id + "\\:CHATINPUT").focus();
        } else {
            //If window is not hidden Focus on it
            $("#" + session_id + "\\:CHATINPUT").focus();
        }

    } else {
        var ChatHtml = chat_window_template( session_id , display_name );
        chat.alert_sessions[session_id] = new Object;
        var tmpWidth = chat_window_width * chat_windows;
        tmpWidth = tmpWidth + chat_window_width;
        if( chat.display_roster == "no" ){ tmpWidth = tmpWidth - 200; }
        var CW = dom_createChild_Document(session_id + ":CHATWINDOW","div");
        CW.style.width = chat_window_width +"px";
        CW.style.bottom = "0px";
        CW.style.right = tmpWidth + "px";
        CW.style.maxHeight = "400px";
        CW.style.background = "#aaaaaa";
        CW.style.position = "fixed";
        CW.innerHTML = ChatHtml;
        chat_windows++;
        chat_windows_array[session_id] = CW;
        $("#" + session_id + "\\:CHATINPUT").focus();
    }
}

function chat_runMessages( xmld ){
    $(xmld)[0].nodeName;
    //aaa_tmp = $(xml).length;
    //alert( $(xml).children() );
    $(xmld).each(
        function(){
            if( $("#CHAT\\:MESSAGE\\:" + $(this).find("chat_message_id").text() ).length == 0 ){
                
                

                var sessID = $(this).find("session_id").text();
               // $("#log").html( "X"+ sessID + "X" );
                $("#" + sessID + "\\:CHATBODY" ).append("<div id='CHAT\:MESSAGE\:" + $(this).find("chat_message_id").text() + "' >" +   $(this).find("display_name").text() + ":&nbsp;" +  $(this).find("message").text() + " </div>");
                if( $("#" + sessID + "\\:CHATWINDOW").css("display") == "none" ){
                    chat_windows++;
                    var tmpWidth = chat_window_width * chat_windows;
                    if( chat.display_roster == "no" ){ tmpWidth = tmpWidth - 200; }
                    $("#" + sessID + "\\:CHATWINDOW").css("right" , tmpWidth + "px;");
                    
                    $("#" + sessID + "\\:CHATWINDOW").toggle();
                }
                $("#" + sessID + "\\:CHATWINDOW").animate({scrollTop: $("#" + sessID + "\\:CHATWINDOW").attr("scrollHeight")}, 3000);
                
                
            }
        }
    );
}

function chat_checkSessions(xml){
    $(xml).find("Result").each(
        function(){
            //alert($(this).find("session_id:first").text());
            //alert($(this).find("status").text() );
            if( $("#" + $(this).find("session_id:first").text() + "\\:CHATWINDOW" ).length == 0 && (  $(this).find("status").text() == "active" || $(this).find("status").text() == "pending" ||  $(this).find("status").text() == "hidden" )){
                chat_display_window( $(this).find("session_id:first").text() , $(this).find("name:first").text() );
                chat.web_session_id = $(this).find("session_id:first").text();
                if($(this).find("status:first").text() == 'hidden' ){
                    chat_close_window( $(this).find("session_id:first").text() );
                }                
            } else {
                if( $("#" + $(this).find("session_id:first").text() + "\\:CHATWINDOW" ).length != 0 && (  $(this).find("status").text() == "inactive" )){
                    $("#" + $(this).find("session_id:first").text() + "\\:CHATWINDOW" ).remove();
                }
            }
            //Opens Each Messages

                $(this).find("messages").each(
                    function(){
                        $(this).find("item").each(
                            function (){
                            if( $("#CHAT\\:MESSAGE\\:" + $(this).find("chat_message_id").text() ).length == 0 ){
                                   // if( $(this).find("module_id") != chat_module_id ||$(this).find("module_name") != chat_module_name  ){
                                    //    if( $(document).is(":focus") ){
                                     //       alert("Yes");
                                     //       var tmp_null = '';
                                      //  } else {
                                      //      chat.alert();
                                      //  }
                                    //}

                                    var sessID = $(this).find("session_id:first").text();
                                    //if( $("#" + sessID + "\\:CHATINPUT").is(":focus") ){
                                    //    var tmp_null = '';
                                    //} else {
                                    //    chat.alert_chat_window( sessID );
                                    //}
                                    if("#" + sessID  )
                                    $("#log").html( "X"+ sessID + "X<br>" + $(this).find("message").text() + "X</br>" + $("#" + sessID + "\\:CHATBODY" ).text() );
                                    if( $(this).find("message").text() == '' ){
                                        var tmp_display = "display: none;"
                                    } else {
                                        var tmp_display = '';
                                    }
                                
                                    $("#" + sessID + "\\:CHATBODY" ).append("<div id='CHAT\:MESSAGE\:" + $(this).find("chat_message_id").text() + "' class='chat_messages' style='" + tmp_display +  "' ><span class='chat_message_name' >" +   $(this).find("display_name").text() + ":</span><span class='chat_message_text'>&nbsp;" +  $(this).find("message").text() + "</span> </div>");

                                        if( $("#" + sessID + "\\:CHATWINDOW").css("display") == "none" ){
                                            chat_windows++;
                                            var tmpWidth = chat_window_width * chat_windows;
                                            if( chat.display_roster == "no" ){ tmpWidth = tmpWidth - 200; }
                                            //alert( tmpWidth );
                                            $("#" + sessID + "\\:CHATWINDOW").css("right" , tmpWidth + "px");

                                            $("#" + sessID + "\\:CHATWINDOW").toggle();
                                        }
                                }
                            }
                        );
                    }
                );
            
            //Closes Each Messages
            
        }
    );
}

function chat_sessions_tick(){
    $.ajax({type: "GET", url: 'chat.xml.php?function=list_sessions_full&format=xml&module_id=' + chat_module_id + '&module_name=' + chat_module_name  , dataType: "xml" , success: chat_checkSessions});
}


function chat_tick(){
   // for( x in chat_windows_array ){
   //     $.ajax({ type: "GET", url: 'chat.xml.php?function=list_messages&session_id=' + x , dataType: "xml" , success: chat_runMessages });
   // }
    chat_sessions_tick();
}
    


function chat_post_byId( id ){
    var message= encodeURI( document.getElementById(id).value );
    var session_id = id.split(":")[0];
    $.ajax({type: "GET", url: 'chat.xml.php?function=send_message&module_id=' + chat_module_id +'&module_name=' + chat_module_name +'&session_id=' + session_id + '&message=' + message , dataType: "xml"});
    //xml_get_doc( 'chat.xml.php?function=send_message&module_id=' + chat_module_id +'&module_name=' + chat_module_name +'&session_id=' + session_id + '&message=' + message );
    document.getElementById(id).value = '';
    chat_tick();
    
}

chat.list_messages = function(xml){
    if( $(xml).find("Result").length != chat.chat_message_count ){
        $("#searchresult").prepend( "<div class='searchresult_chat' >" + $(xml).find("message:last").text() + "</div>" );
        clearInterval( chat.search_tick_id );
    } 
}

chat.search_tick = function(){
    $.ajax({type: "GET", url: 'chat.xml.php?function=list_messages&format=xml&session_id=' +  chat.web_session_id  , dataType: "xml" , success: chat.list_messages});
}
chat.schedule_alert = function(){ setTimeout("chat.alert();" , 3000 );}


function chat_runSearch(xml){
    chat.web_session_id = $(xml).find("session_id:first").text();
    chat.search_tick_id = setInterval ( 'chat.search_tick()', chat.tick_time);
    chat.chat_message_count = $(xml).find("messages:first").text();
}

function chat_monitor_input_search( e , id ){
        var keynum;
        var message = encodeURI( document.getElementById(id).value );
        //alert( message + "a" );
    if(window.event) // IE
    {
        keynum = e.keyCode
    }
    else if(e.which) // Netscape/Firefox/Opera
    {
        keynum = e.which
    }
    if(keynum == 13 ){
        $.ajax({type: "GET",
            url: 'chat.xml.php?function=web_search&module_id=' + chat_module_id +'&module_name=' + chat_module_name +'&session_id=' + web_session_id + '&display_name='+ chat_display_name + '&message=' + message ,
            dataType: "xml" ,
            success: chat_runSearch
        });
        document.getElementById(id).value = '';
    
        
    }
}

function chat_monitor_input( e , id ){
    var keynum;
    if(window.event) // IE
    {
        keynum = e.keyCode
    }
    else if(e.which) // Netscape/Firefox/Opera
    {
        keynum = e.which
    }
    if(keynum == 13 ){chat_post_byId( id );}
    
}
function chat_open_session_xml( xml , node_name){
    
    $(xml).find("Result").each(
        function(){
            var session_id = $(this).find("session_id").text() ; 
            var display_name = roster[node_name]["display_name"];
            chat_display_window( session_id , display_name );
//FILLME
        }
    )
        
}


function chat_open_window( node_name ){
     module_id = node_name.split(":")[1];
     module_name =  node_name.split(":")[0];
     //window.open('chat.xml.php?function=open_session&to_module_id=' + chat_module_id +'&to_module_name=' + chat_module_name + '&from_module_id=' + module_id + '&from_module_name=' + module_name);
    $.ajax({ 
        type: "GET", 
        url: 'chat.xml.php?function=open_session&from_module_id=' + chat_module_id +'&from_module_name=' + chat_module_name + '&to_module_id=' + module_id + '&to_module_name=' + module_name , 
        dataType: "xml", 
        async: false,
        success:    function(xml){ 
            chat_open_session_xml ( xml , node_name);
        }
    });
}


function schedule_chat_tick(){
    chat.chat_tick_interval = setInterval ( 'chat_tick()', chat_tick_time);
    chat.chat_update_roster_interval = setInterval ( 'chat_update_roster()' , chat_roster_tick_time );
}

function chat_list_messages( session_id ){
    var xmlDoc = xml_get_doc( 'chat.xml.php?function=list_messages&session_id=' + session_id );
    return xml2array( xmlDoc );
}
function chat_status_icon(status){
    if( status == "online"){
        return "images/online15.png";
    } else {
        return "images/offline15.png";
    }
}

function chat_process_roster(xml){
                
            $(xml).find("Result").each(
                function(){
                    var main_div = "#" + $(this).find("module_name").text() + "\\:" + $(this).find("module_id").text();
                    var status_div = "#" + $(this).find("module_name").text() + "\\:" + $(this).find("module_id").text()   + "\\:STATUS";
                    var status_icon = chat_status_icon( $(this).find("status").text());
                    if( $(main_div).length == 0 ){
                        
                        $("#chat_roster").append("<div id='" + $(this).find("module_name").text() + "\:" + $(this).find("module_id").text() + "' class='chat_user'><div id='" + $(this).find("module_name").text() + "\:" + $(this).find("module_id").text()   + "\:STATUS' style='width: 15px; height:15px;float:left;background-size: 100%;position:relative;top:2px;'>&nbsp;</div>" + $(this).find("display_name").text()) + "</div>";
                        $(status_div).css("background-image" , 'url("' + status_icon + '")'  );
                        $(main_div ).addClass("chat_user");
                         $(main_div ).css("height" , "20px");
                         $(main_div).css("overflow" , "hidden");
                         
                         //alert( $(status_div).css("background-image") + status_icon +"<br>\n" + status_div );
                    } else {
                        $(status_div).css("background-image" , 'url("' + status_icon + '")'  );
                    }
                    if( $(this).find("status").text() == "online"){
                        $(main_div).insertAfter( '#chat_roster_top' );
                    }
                    document.getElementById($(this).find("module_name").text() + ":" + $(this).find("module_id").text() ).onclick = function(){chat_open_window( this.id  );};
                    //$(main_div).click( function(){chat_open_window( this.id  );});
                    roster[$(this).find("module_name").text() + ":" + $(this).find("module_id").text() ] =  {"display_name" : $(this).find("display_name").text()};
                }
            ); 
}
function chat_start(){
    $('head').append('<link rel="stylesheet" type="text/css" href="css/chat.css" />');
    $('body').append('\
        <div id="chat_master" style="position: fixed; bottom: 0px; right: 0px; width:200px;z-index: 200; ">\n\
            <div id="chat_header" style="width: 200px; height: 20px; background: url(\'images/bl2Lbl20px.jpg\');text-align: center;color: white;border-top-right-radius: 10px;-moz-border-radius-topright: 10px;-webkit-border-top-right-radius: 10px;border-top-left-radius: 10px;-moz-border-radius-topleft: 10px;-webkit-border-top-left-radius: 10px;" onclick="$(\'#chat_roster\').toggle();">Chat</div> \n\
               <div id="chat_roster" style="background: url(\'images/transparent_90.png\');display:none;" >\n\
                <div id="chat_roster_top" style="width: 200px; height: 1px;" class="chat_user"></div>               \n\
                </div>\n\
            </div>\n\
        </div>');
    chat_update_roster()
    schedule_chat_tick()
    $('#chat_roster').sortable();
    chat.document_title = document.title.innerHTML;
}
function chat_update_roster(){
    $.ajax({ 
        type: "GET", 
        url: 'chat.xml.php?function=list_roster&module_id=' + chat_module_id +'&module_name=' + chat_module_name , 
        dataType: "xml", 
        async: true,
        success:  chat_process_roster
    });
}
