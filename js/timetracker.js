var tt = {
    newEntry : function(user_id,module_name,module_id) {
        _this = this;
    
        $.post('tt_controller.php?action=newTimeEntry', {
            user_id: user_id,
            module_name: module_name,
            module_id: module_id
        },function(data){
            data = $.trim(data);
            if(data.length) {
                // prepend data to entry stack
                $('#tt_table').append(data);
                
                // unbind old onlick
                $('#tt_button').attr('onclick','').unbind('click');
                
                // update button text
                 $('#tt_button').attr('value','Stop');
                
                // change action to stop current entry
                $('#tt_button').click(function () {
                    tt.setTimeEnd(module_name,module_id,user_id);
                });
                
                
            } else {
                alert('There was a problem starting the clock.');
            }
        });
    },
    setTimeEnd : function(module_name,module_id,user_id) {
        _this = this;
        
        $.post('tt_controller.php?action=setTimeEnd', {
            module_name: module_name,
            module_id: module_id
        },function(data){
            data = $.trim(data);
            if(data=='pass') {
                
                // Get updated entries
                $.post('tt_controller.php?action=getUpdatedEntries', {
                    module_name: module_name,
                    module_id: module_id
                },function(data){
                    data = $.trim(data);
                    if (data.length) {
                        $('#tt_table').html(data);
                    }
                });
                
                // update button text
                $('#tt_button').attr('value','Start');
                
                // change button text and action to stop current entry
                $('#tt_button').click(function () {
                    tt.newEntry(user_id,module_name,module_id);
                });
                
                
            } else {
                alert('There was a problem stopping the clock.');
            }
        });
    },
    delTimeEntry : function(tt_id) {
        $.post('tt_controller.php?action=delTimeEntry', { tt_id: tt_id },function(data){
            data = $.trim(data);
            if(data=='pass') {
                $('tr[data-id=' + tt_id + ']').hide('slow');
            }
            else {
                alert('Could not delete entry.');
            }
        });
    }
};