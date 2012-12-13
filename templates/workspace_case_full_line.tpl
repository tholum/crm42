<li 
 onclick="slimcrm.workspace.open_list_item('CASES' , '<%= case_id %>');" 
 data-case_id="<%= case_id %>" 
 data-module_name="<%= module_name %>" 
 class="cases_<%= case_id %> cases_<%= Status.toLowerCase() %>"  >
 <div class="task_list_order"><%= case_id %></div>
<p style="display: inline-block;text-overflow: ellipsis; width: 20em;overflow:hidden;white-space:nowrap;" >
<% if( subject == '' ){ %>
No Subject
<% } else { %>
<%= subject %>
<% } %></p>
<div class="mini_profile" style="display: inline-block;background-image: url('<%= slimcrm.users.profile_pic( Owner )  %>');">&nbsp;</div> 
 </li>