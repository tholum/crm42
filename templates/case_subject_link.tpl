    <a onclick="var par = $(this).parent();$(this).replaceWith(slimcrm.templates.case_subject_input({ case_id: $(this).parent().data('case_id') , subject: $(this).parent().data('subject') } ));par.children().focus();" >
    <% if( subject == '' ){ %>
        No Subject
    <% } else { %>
        <%= subject %>
    <% } %>