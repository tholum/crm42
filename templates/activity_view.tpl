<ul class="activity_feed"  data-list_type="my_activity" >
    <% _(data).each(function( line ){ %>
        <li data-activity_id="<%= line.activity_id %>" ><div class="small_profile" style="display: inline-block;background-image: url('<%= slimcrm.users.profile_pic( line.user_id) %>')" >&nbsp;</div><%= slimcrm.activity.display_line(line) %></li>
    <% }) %>
</ul>