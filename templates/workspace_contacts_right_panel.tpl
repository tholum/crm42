<div id="workspace_tasks_header"><%= company_name %><img class="fav_star" src="images/fav_off.png" /></div>
<div id="task_list_header"><div id="tlh_left"></div><p style="margin-left: 30px">Tasks</p><div id="tlh_right"></div></div>
<div id="task_list_container">
    <ul data-module_name="contacts"  data-module_id="<%= contact_id %>" data-list_type="tasks"  id="task_list" class="task_list_contacts_<%= contact_id %>">
        <% _(tasks).each(function( line ){ %>
            <li data-task_type="<%= line.task_type %>" data-task_id="<%= line.task_id %>" class="task_<%= line.task_type %>_<%= line.task_id %>" ><%= slimcrm.workspace.display_task_line(line) %></li>
        <% }) %>
            <li class="active_task group list_footer"><div class="left_active"><a class="slim_button" onclick="slimcrm.tasks.new_task('contacts','<%= contact_id %>');">New Task</a></div><div class="right_active">Right content</div></li> 
        </ul> 
 </div>
 
<div id="task_list_header"><div id="tlh_left"></div><p style="margin-left: 30px">Cases</p><div id="tlh_right"></div></div>
<div id="task_list_container">
    <ul data-module_name="contacts"  data-module_id="<%= contact_id %>" data-list_type="cases"  id="task_list" class="task_list_contacts_<%= contact_id %>">
        <% _(cases).each(function( line ){ %>
            <li onclick="slimcrm.workspace.open_list_item('CASES' , '<%= line.case_id %>');" data-case_id="<%= line.case_id %>" data-module_name="<%= line.module_name %>" class="cases_<%= line.case_id %> cases_<%= line.Status.toLowerCase() %>"  ><%= slimcrm.templates.workspace_case_line( line ) %></li>
        <% }) %>
            <li class="active_case group list_footer"><div class="left_active">
            <a 
            class="slim_button" 
            onclick="slimcrm.cases.create_case('CONTACTS' , '<%= contact_id %>' , { contact_module_name: 'CONTACTS' , contact_module_id: '<%= contact_id %>' } );">New Case</a><a class="slim_button" onclick="$('.cases_completed').fadeIn();">Show All</a></div><div class="right_active">Right content</div></li> 
        </ul> 
 </div> 
 