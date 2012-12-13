<div class="flag_edit_container flag_edit_popup" >
<ul class="flag_edit_popup">
 <% _(data).each(function( line ){ %>
           <li class="flag_edit_popup" ><input onclick="if($(this).ctl_checked()){flags.add_flags_by_module( '<%= module_name %>' , '<%= module_id %>' , '<%= line.flag_type_id %>' , { onUpdate: function(response,data){ slimcrm.flags.update('<%= module_name %>','<%= module_id %>');}} ); } else { flags.remove_flags_by_module_id('<%= line.flag_type_id %>','<%= module_id %>' ,'<%= module_name %>' , { onUpdate: function(response,data){ slimcrm.flags.update('<%= module_name %>','<%= module_id %>');}} )}" class="flag_edit_popup"  type="checkbox" <% if(line.selected == true){ %> checked="yes" <% } %> /><div class="default_flag flag_edit_popup" style="background: #<%= line.color %>" title="<%= line.description %>">&nbsp;</div><div class="flags_description"><%= line.description %></div></li>
        <% }) %>
</ul>
</div>