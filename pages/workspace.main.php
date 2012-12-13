<div class="workspace_container group">
    <div class="workspace_left_panel group">
        <img class="logo" src="images/logo.png" />
        <input type="text" name="search_box" class="workspace_search_box" onchange="slimcrm.workspace.update_list();" onkeyup="if( $('.workspace_list_item.active').data('keyup') != undefined ){ $(this).change() }" />
        <ul class="workspace_left_panel_nav group">
            <li  onclick="slimcrm.workspace.get_list('cases');slimcrm.screen_resize();$('.workspace_left_panel_nav li').removeClass('active');$(this).addClass('active');"  class="active workspace_list_item" data-list_type="cases">Cases</li>
            <li onclick="slimcrm.workspace.get_list('global_task');slimcrm.screen_resize();$('.workspace_left_panel_nav li').removeClass('active');$(this).addClass('active');"  class="workspace_list_item" data-list_type="global_task" >FCT&apos;s</li>
            <li onclick="slimcrm.workspace.get_list('contacts');slimcrm.screen_resize();$('.workspace_left_panel_nav li').removeClass('active');$(this).addClass('active');"  class="workspace_list_item" data-list_type="contacts" data-keyup="true">Contacts</li>
            <li onclick="slimcrm.workspace.get_list('activity');slimcrm.screen_resize();$('.workspace_left_panel_nav li').removeClass('active');$(this).addClass('active');" class="workspace_list_item" data-list_type="activity">Activity</li>
            <li ><div style="background-image: url('images/settings.png');width:16px; height: 16px; background-size: 16px 16px;margin: 2px;" onclick="slimcrm.workspace.search_open_options();"></div></li>
        </ul>
        <div id='test' ></div>
        <ul class="workspace_list">
        </ul>
    </div>
    <div class="workspace_right_panel group">
        <div class="workspace_tasks">
        </div>

        <div class="workspace_view" ></div>
        
        <div class="workspace_footer">
            <label>Videos:</label>
            <ul class="video_links group">
                <li><a href="">Video Link example</a></li>
                <li><a href="">Video Link example</a></li>
                <li><a href="">Video Link example</a></li>
                <li><a href="">Video Link example</a></li>
                <li><a href="">Video Link example</a></li>
                <li><a href="">Video Link example</a></li>
                <li><a href="">Video Link example</a></li>
            </ul>
        </div>
    </div>
</div>