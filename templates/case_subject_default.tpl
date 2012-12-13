<div 
    data-case_id="<%= case_id %>" 
    data-content_type="case_subject" 
    data-template="case_subject_default"
    data-subject="<%= subject %>"
    data-type="subject"
    data-module_name="cases"
    data-module_id="<%= case_id %>"
    >
<%= slimcrm.templates.case_subject_link( { subject: subject, case_id: case_id } ) %>
    </a>
    </div>