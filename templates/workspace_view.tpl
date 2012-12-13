
            <div class="workspace_view_header">
                <select>
                    <option>TODO: Make Actions</option>
                </select>
                <img src="images/close.png" class="close_button" alt="close" />
            </div>
            <div class="view_padding">
                <h2 ><div style="display: inline-block;"><%= case_id %></div>:<div data-update_field="true" data-module_name="cases" data-module_id="<%= case_id %>" data-display_field="subject" style="display:inline-block;"><%= subject %></div></h2>
                 <a class="slim_button" onclick="casecreation.right_bottom_by_case( '<%= case_id %>',
            {
                preloader:'prl',
                onUpdate: function(response,root){ 
                    $('#right_bottom_panel').html(response);
                }
             });" style="margin: 1px;"  >Flyout</a>
             <a 
                style="margin: 1px;" 
                class="slim_button" 
                data-button_type="follow" 
                data-module_name="cases" 
                data-module_id="<%= case_id %>"
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
                <ul class="data_list group">
                <li><label>Subject</label><p><%= slimcrm.templates.case_subject_default({'case_id': case_id , 'subject': subject}) %></p></li>
                <li><label>Customer</label><p><%= slimcrm.templates.contact_name_search({ 'contact_module_id': contact_module_id, 'contact_module_name': contact_module_name, 'module_name': 'cases', 'module_id': case_id }) %></p></li>
                    <li><label>Owner</label><p><%= slimcrm.templates.user_name_search( { 'user_id': Owner, 'module_name': 'cases', 'module_id': case_id } ) %></p></li>
                    <li><label>Origin</label><p><%= CaseOrigin %></p></li>
                    <li><label>Status</label><p><%= slimcrm.templates.case_status_button( { case_id: case_id , Status: Status }) %></p></li>
                    <li><label>Case Type</label><p> TODO: make live</p></li>
                </ul>
                <hr />
                <H3 style="margin-bottom: 15px;">Time Tracker</H3>
                
                <%= slimcrm.templates.timetracker( $.extend( { module_name: 'cases' , module_id: case_id } , time ) ) %>
                
                <ul class="tools group">
                    <li class="calendar">Due Date</li>
                    
                    <li class="attach">Attach a File</li>
                    <li class="flags_cases_<%= case_id %>" onclick="slimcrm.flags.edit_flags('.edit_cases_<%= case_id %>','cases', '<%= case_id %>');">Flags</li>
                </ul>
                <div class="edit_cases_<%= case_id %>"></div>
                <hr />
                <%= slimcrm.templates.followers_list({ 'followers': followers, 'followers_module_name': 'cases' , 'followers_module_id': case_id}) %>
                
                <h3>Activity Feed</h3>
                <ul class="activity_feed" data-module_name="cases"  data-module_id="<%= case_id %>" data-list_type="activity" >
                   <% _(activity).each(function( line ){ %>
                        <li data-activity_id="<%= line.activity_id %>" ><div class="small_profile" style="display: inline-block;background-image: url('<%= slimcrm.users.profile_pic( line.user_id) %>')" >&nbsp;</div><%= slimcrm.activity.display_line(line) %></li>
                   <% }) %>
                    <li><input class="list_footer" onclick="$(this).val('');" onblur="if($(this).val() == '' ){$(this).val('Add Comment..');}" type="text" value="Add Comment..." onkeydown="if(event.which==13 && $(this).val() != '' ){note.add_note( $(this).val() , 'cases' , '<%= case_id %>' , {onUpdate: function(){slimcrm.activity.update_module('cases','<%= case_id %>');}});$(this).val('').blur();}" /></li>
                </ul>
            </div>