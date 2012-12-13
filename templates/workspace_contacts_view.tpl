
            <div class="workspace_view_header">
                <select>
                    <option>TODO: Make Actions</option>
                </select>
                <img src="images/close.png" class="close_button" alt="close" />
            </div>
            <div class="view_padding">
                <h2><%= company_name %></h2>
             <a 
                style="margin: 1px;" 
                class="slim_button" 
                data-button_type="follow" 
                data-module_name="contacts" 
                data-module_id="<%= contact_id %>"
                <% if( following ){ %>
                onclick="slimcrm.follow.unfollow_click(this);"
                <% } else { %>
                onclick="slimcrm.follow.follow_click(this);"
                <% } %>
            ><% if( following ){ %>
                Un-Follow
                <% } else { %>
                Follow
                <% } %></a>
                <ul class="phone_list" data-module_name='contacts' data-module_id='<%= contact_id %>' data-list_type="phone"  >
                    <% _.each( phone , function(data){ %>
                        <%= slimcrm.templates.simple_phone($.extend(data , { 'module_name': 'contacts' , 'module_id': contact_id } ) ) %>
                    <% }) %>
                </ul>
                <ul class="email_list" data-module_name='contacts' data-module_id='<%= contact_id %>' data-list_type="email"  >
                    <% _.each( email , function(data){ %>
                        <%= slimcrm.templates.simple_email($.extend(data , { 'module_name': 'contacts' , 'module_id': contact_id } ) ) %>
                    <% }) %>
                </ul>
                <ul class="address_list" data-module_name='contacts' data-module_id='<%= contact_id %>' data-list_type="address"  >
                    <% _.each( address , function(data){ %>
                        <%= slimcrm.templates.simple_address($.extend(data , { 'module_name': 'contacts' , 'module_id': contact_id } ) ) %>
                    <% }) %>
                </ul>
                <ul class="data_list group">
                    <li><label>Created By</label><p><%= slimcrm.users.display_full_name( user_id ) %></p></li>
                </ul>
                
                <ul class="tools group">
                    <li class="attach">Attach a File</li>
                    <li class="flags_contacts_<%= contact_id %>" onclick="slimcrm.flags.edit_flags('.edit_contacts_<%= contact_id %>','contacts', '<%= contact_id %>');">Flags</li>
                </ul>
                <div class="edit_contacts_<%= contact_id %>"></div>
                <hr />
                <%= slimcrm.templates.followers_list({ 'followers': followers, 'followers_module_name': 'contacts' , 'followers_module_id': contact_id}) %>
                
                <h3>Activity Feed</h3>
                <ul class="activity_feed" data-module_name="contacts"  data-module_id="<%= contact_id %>" data-list_type="activity" >
                   <% _(activity).each(function( line ){ %>
                        <li data-activity_id="<%= line.activity_id %>" ><div class="small_profile" style="display: inline-block;background-image: url('<%= slimcrm.users.profile_pic( line.user_id) %>')" >&nbsp;</div><%= slimcrm.activity.display_line(line) %></li>
                   <% }) %>
                    <li><input class="list_footer" onclick="$(this).val('');" onblur="if($(this).val() == '' ){$(this).val('Add Comment..');}" type="text" value="Add Comment..." onkeydown="if(event.which==13 && $(this).val() != '' ){note.add_note( $(this).val() , 'contacts' , '<%= contact_id %>' , {onUpdate: function(){slimcrm.activity.update_module('contacts','<%= contact_id %>');}});$(this).val('').blur();}" /></li>
                </ul>
            </div>