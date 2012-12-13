<div 
data-module_name="<%= module_name %>"
data-module_id="<%= module_id %>"
data-content_type="timetracker"
data-template="timetracker"
data-open="<% open %>"
>
<% if( open == false ){ %>
<a class="slim_button" onclick="slimcrm.timetracker.start_time('<%= module_name %>','<%= module_id %>');" >Start</a>
<% } else { %>
<a class="slim_button" onclick="slimcrm.timetracker.end_time('<%= module_name %>','<%= module_id %>');" >End</a>
<% } %>
<ul>
<% _.each(entries , function( line ){ %>
    <%= slimcrm.templates.timetracker_line( line ) %>
<% }) %>
</ul>
</div>