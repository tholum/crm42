<ul class="data_list"  data-module_name="<%= followers_module_name %>"  data-module_id="<%= followers_module_id %>" data-list_type="followers" >
    <% _(followers).each(function( line ){ %>
        <%= slimcrm.templates.followers_line(line) %>
    <% }) %>
</ul>