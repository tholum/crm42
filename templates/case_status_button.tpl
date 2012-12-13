<a 
<% if( Status == 'Active' ){ %>
class="slim_button_green"
<% } else { %>
class="slim_button_red"
<% } %>
data-type="status"
data-case_id="<%= case_id %>"
data-status="<%= Status %>"
data-module_name="cases"
data-module_id="<%= case_id %>"
data-template="case_status_button"
data-live_template="true"

onclick="if( $(this).data('status') == 'Active' ){ $(this).data('status' ,'Completed'); } else { $(this).data('status' ,'Active'); };$.getJSON('api.php?' + $.param( $.extend( { action: 'set_value' } , $(this).data()  ) ) , slimcrm.cases.change_data  );" ><%= Status %></a>