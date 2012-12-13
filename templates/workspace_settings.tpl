<div class="workspace_search" style="z-index: 100;">
<% _(options).each(function(data){ %>
    <%= slimcrm.workspace.get_search_line( module_name , data)  %>
<% }) %>
</div>