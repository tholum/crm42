/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * <div id="WidgetHandle<?php echo $widgetCT; ?>" class="Handle"></div>
 <div id="WidgetBody<?php echo $widgetCT; ?>"class="Info">
 */
var widget_textedit_arr = [];
function alert_test(){
    alert("it worked");
}
function widget_textedit( widget_number ){
    onSidebarExe[ "Widget" + widget_number ] = function (){ alert("this is a test"); };
    widgetHeader = document.getElementById("WidgetHandle" + widget_number );
    widgetHeader.innerHTML = "Text Edit";
    widgetBody = document.getElementById("WidgetBody" + widget_number );
    widgetBody.innerHTML = "<textarea id='texteditWidget" + widget_number + "' style='width: 100%; height: 80%;positon: relative; top: -15px;' ></textarea>";
   // widgetBody.style.height = "400px";
/*
    var Dom = YAHOO.util.Dom,
        Event = YAHOO.util.Event;

    var myConfig = {
        height: '100px',
        width: '300px',
        dompath: false,
        focusAtStart: false,
toolbar: {
        
        buttons: [
            { group: 'textstyle', label: 'Font Style',
                buttons: [
                    { type: 'push', label: 'Bold', value: 'bold' },
                    { type: 'push', label: 'Italic', value: 'italic' },
                    { type: 'push', label: 'Underline', value: 'underline' },
                    { type: 'separator' },
                    { type: 'color', label: 'Font Color', value: 'forecolor', disabled: true },
                    { type: 'color', label: 'Background Color', value: 'backcolor', disabled: true }
                ]
            }
        ]
    }
    };

    YAHOO.log('Create the Editor..', 'info', 'example');
    widget_textedit_arr["Widget" + widget_number ] = new YAHOO.widget.SimpleEditor('texteditWidget' + widget_number , myConfig);
    widget_textedit_arr["Widget" + widget_number ].render();
    
    onSidebarExe[ "Widget" + widget_number ] = function ( id ){
             widget_textedit_arr[ id ].render();
            }

*/


}

