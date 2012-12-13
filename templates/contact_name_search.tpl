<div 
    data-module_name="<%= module_name %>" 
    data-module_id="<%= module_id %>" 
    data-content_type="contact" 
    data-template="contact_name_search" 
    data-contact_module_name="<%= contact_module_name %>"
    data-contact_module_id="<%= contact_module_id %>"
    ><a onclick="
    var parent=$(this).parent();
    $(this).replaceWith(slimcrm.templates.contact_name_search_ac( $(this).parent().data() ) ); 
    parent.children('.autocomplete').autocomplete(
        { 
            source: 'contact_lookup.php' ,
            select: function( event , ui ){
                $(this).parent().data('contact_module_name', 'CONTACTS');
                $(this).parent().data('contact_module_id', ui.item.contact_id);
                $(this).parent().attr('data-contact_module_name', 'CONTACTS');
                $(this).parent().attr('data-contact_module_id', ui.item.contact_id );
                slimcrm.contacts.update_item( ui.item );
                $.getJSON('api.php?action=set_value&type=contacts&' + $.param($(this).parent().data()));
                $(this).replaceWith(slimcrm.templates.contact_name_search( $(this).parent().data() ) );
            }
        });" ><%= slimcrm.contacts.display_name( contact_module_id ) %></a></div>