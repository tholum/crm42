<div 
    data-module_name="<%= module_name %>" 
    data-module_id="<%= module_id %>" 
    data-content_type="user" 
    data-template="user_name_search" 
    data-user_id="<%= user_id %>"
    ><a onclick="
    var parent=$(this).parent();
    $(this).replaceWith(slimcrm.templates.user_name_search_ac( $(this).parent().data() ) ); 
    parent.children('.autocomplete').autocomplete(
        { 
            source: 'user_lookup.php' ,
            select: function( event , ui ){
                $(this).parent().data('user_id', ui.item.user_id );
                $(this).parent().attr('data-user_id', ui.item.user_id );
                $.getJSON('api.php?action=set_value&type=user&' + $.param($(this).parent().data()));
                $(this).replaceWith(slimcrm.templates.user_name_search( $(this).parent().data() ) );
            }
        });" ><%= slimcrm.users.display_full_name( user_id ) %></a></div>