
<div class="task_list_order"><%= case_id %></div>
<p style="display: inline-block;text-overflow: ellipsis; width: 20em;overflow:hidden;white-space:nowrap;" >
<% if( subject == '' ){ %>
No Subject
<% } else { %>
<%= subject %>
<% } %></p>
<div class="mini_profile" style="display: inline-block;background-image: url('<%= slimcrm.users.profile_pic( Owner )  %>');">&nbsp;</div> 