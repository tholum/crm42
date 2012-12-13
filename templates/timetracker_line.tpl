<li style="width: 100%;"><%= moment( start_time , "YYYY-MM-DD HH:mm:ss" ).format("YYYY-MM-DD: hh:mm a")  %> to 
<% if( moment( end_time , "YYYY-MM-DD HH:mm:ss" ).format("YYYY-MM-DD") == moment( start_time , "YYYY-MM-DD HH:mm:ss" ).format("YYYY-MM-DD") ){ %>
<%= moment( end_time , "YYYY-MM-DD HH:mm:ss" ).format("hh:mm a")  %>
<% }else if ( end_time == '0000-00-00 00:00:00' ){ %>
Now: <div class="clock" style="display: inline-block;"data-clock_format="hh:mm a"><%= moment().format("hh:mm a") %></div>
<% } else { %>
<%= moment( end_time , "YYYY-MM-DD HH:mm:ss" ).format("YYYY-MM-DD")  %> <%= moment( end_time , "YYYY-MM-DD HH:mm:ss" ).format("hh:mm a")  %>
<% } %>

<% if ( end_time == '0000-00-00 00:00:00' ){ %>
<div class="clock_from" style="display:inline-block;float:right; text-align: right;" data-from_now="<%= start_time %>" data-from_format="YYYY-MM-DD HH:mm:ss" data-measurement="hours" data-round="2" ></div>
<% } else { %>
<div  style="display:inline-block;float:right; text-align: right;" ><%= slimcrm.util.round( moment( end_time , "YYYY-MM-DD HH:mm:ss").diff( moment( start_time , "YYYY-MM-DD HH:mm:ss") , 'hours' , true ) , 2 ) %></div>
<% } %>

</li>