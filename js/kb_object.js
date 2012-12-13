kb_object = new Object();
kb_object.open_popup = function( knowledgebase_id , title ){
    knowledgebase.display_knowledgebase( knowledgebase_id , 
    { 
        onUpdate: function( result , root ){
            rand = Math.floor( Math.random() * 11 );
            $('body').append('<div id="kb_display' + rand + '" title="' + title +'" >&nbsp;</div>');
            $('#kb_display' + rand ).html( result ).dialog(
                {
                    close: function(event , ui){ $(this).remove('');$(this).dialog('destroy'); },
                    minWidth: 600
                }
            );
            
        } 
    }
);
}