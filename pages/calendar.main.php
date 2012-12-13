<?php
require_once('class/class.local_calendar.php');
$calendar = new calendar();
$default_calendar = $calendar->get_default_calendar();
?>
<script>
function run_on_start(){
    $('#mycal').fullCalendar({
        events: 'calendar.json.php?calendar=<?php echo $default_calendar; ?>&source=local',
        eventClick: function(calEvent, jsEvent, view) {
            //alert(calEvent);
            slimcrm.calEvent = calEvent;
            slimcrm.jsEvent = jsEvent;
            slimcrm.view = view;
            calendar.display_event( calEvent.id , {target: 'event_details',onUpdate: function(response,root){ $('.datetimepicker').datetimepicker({timeFormat: 'hh:mm:ss',dateFormat: 'yy-mm-dd'}); }});
            if(calEvent.url){
                
               // alert(calEvent.url);
                return false;
            }
        },
        dayClick: function(date, allDay, jsEvent, view) {
            //alert(allDay + view.name);
            if (allDay) {
                if( view.name == 'month'){
                    $('#mycal').fullCalendar('gotoDate',date.getFullYear() ,date.getMonth() ,date.getDate() );$('#mycal').fullCalendar('changeView','agendaWeek');
                } else {
                    //alert('yes');
                   calendar.create_event( { all_day: true , start: date.valueOf() }, { onUpdate: function(response,root){calendar.display_event( response , {target: 'event_details',onUpdate: function(response,root){ $('.datetimepicker').datetimepicker({timeFormat: 'hh:mm:ss',dateFormat: 'yy-mm-dd'});$('#mycal').fullCalendar( 'refetchEvents' ); }}); }});
                }
            }else{
                calendar.create_event( { all_day: false , start: date.valueOf() }, { onUpdate: function(response,root){calendar.display_event( response , {target: 'event_details',onUpdate: function(response,root){ $('.datetimepicker').datetimepicker({timeFormat: 'hh:mm:ss',dateFormat: 'yy-mm-dd'});$('#mycal').fullCalendar( 'refetchEvents' ); }}); }});
                
            }
        },
        
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        theme: true,
        editable: true,
        eventResize: function( event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view ){
            slimcrm.event_drop = event;
            var options = new Object;
            if(event.allDay == false ){
                options.all_day = '0';
            } else {
                options.all_day = '1';
            }
            if(event.start){
                options.start = event.start.valueOf();
            }
            if(event.end){
                options.end = event.end.valueOf();
            }
            slimcrm.options = options;
            calendar.edit_calendar(event.id , options, {});
            calendar.display_event( event.id , {target: 'event_details',onUpdate: function(response,root){ $('.datetimepicker').datetimepicker({timeFormat: 'hh:mm:ss',dateFormat: 'yy-mm-dd'}); }});
        },
        eventDrop: function(event, delta) {
            
            slimcrm.event_drop = event;
            slimcrm.event_delta = delta
            var options = new Object;
            if(event.allDay == false ){
                options.all_day = '0';
            } else {
                options.all_day = '1';
            }
            if(event.start){
                options.start = event.start.valueOf();
            }
            if(event.end){
                options.end = event.end.valueOf();
            }
            slimcrm.options = options;
            calendar.edit_calendar(event.id , options, {});
            calendar.display_event( event.id , {target: 'event_details',onUpdate: function(response,root){ $('.datetimepicker').datetimepicker({timeFormat: 'hh:mm:ss',dateFormat: 'yy-mm-dd'}); }});

        }
			
    });
}
</script>

<div style="height: 40px;padding-left: 55px;position: absolute;right: 0px;top: 2px;display:inline-block;width: 100%;background: #729BC7">

    <div style="display: inline-block;position: relative;width: 50px;" ></div>
<div class="buttons_menu" style="position: relative; left: 300px;display:inline-block; background: #729BC7;font-weight: bold;"  >
    <div 
        style="display: inline-block;"
        class="page_buttons contact_list " 
        onclick="calendar.create_event( { onUpdate: function(response,root){calendar.display_event( response , {target: 'event_details',onUpdate: function(response,root){ $('.datetimepicker').datetimepicker({timeFormat: 'hh:mm:ss',dateFormat: 'yy-mm-dd'});$('#mycal').fullCalendar( 'refetchEvents' ); }}); }});">Create Event</div>
    </div>
</div>
<div style="padding: 25px;width: 300px;display:inline-block;position: absolute;top:20px;left: 0px;background: #729BC7;border-bottom-right-radius: 10px;" id="event_details" >

</div>
<div class="auto_resize_width_minus_350" style="display:inline-block;position: absolute;top:40px;left: 350px;background: #729BC7;min-width: 10px;min-height: 10px; width: 1000px;">
    <div style="background: white; border-top-left-radius: 10px;padding: 25px;" id="display_contact_area"><div id="mycal" class="mycal" style="width: 60%;position: relative;top: 20px;" ></div></div>
</div>

