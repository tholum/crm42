<div id="workspace_tasks_header" style="display: none;" ><div style="display: inline-block;"><%= case_id %></div>:<div data-update_field="true"  data-module_name="cases" data-module_id="<%= case_id %>" data-display_field="subject" style="display:inline-block;"><%= subject %></div><img class="fav_star" src="images/fav_off.png" /></div>
<div id="task_list_header"><div id="tlh_left"></div><div id="tlh_right"></div></div>
<div id="task_list_container">
    <ul data-module_name="cases"  data-module_id="<%= case_id %>" data-list_type="tasks"  id="task_list" class="task_list_cases_<%= case_id %>">
        <% _(tasks).each(function( line ){ %>
            <li data-task_type="<%= line.task_type %>" data-task_id="<%= line.task_id %>" class="task_<%= line.task_type %>_<%= line.task_id %>" ><%= slimcrm.workspace.display_task_line(line) %></li>
        <% }) %>
            <li class="active_task group list_footer"><div class="left_active"><a class="slim_button" onclick="slimcrm.tasks.new_task('cases','<%= case_id %>');">New Task</a></div><div class="right_active">Right content</div></li> 
        </ul> 
 </div>