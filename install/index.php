<?php 
if(!file_exists('../class/global.config.php')){
?><html>
    <head>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js" ></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" ></script>
        <link rel="stylesheet" type="text/css" href="../css/workspace.css">
        <script>
            function check_db(){
                var data = { action: 'check_db' };
                $('.database').each( function(){
                    data[$(this).attr('id')] = $(this).val();
                });
                $.getJSON('ajax.php?' + $.param(data), function(data){
                    if( data.status == 'success'){
                        $('.check_db').html('DB OK').removeClass('slim_button').addClass('slim_button_green');
                        $('.status').html('Database Is Correct');
                    } else {
                        $('.check_db').html('DB ERR Check Again').removeClass('slim_button').addClass('slim_button_red');
                        $('.status').html(data.error);
                    }
                }); 
            }
            var install_button = '<a class="slim_button install" onclick="install()" >install</a>';
            var loading = '<div class="loading" >Installing</div>';
            function install(){
                var data = { action: 'install' };
                $('input').each( function(){
                    data[$(this).attr('id')] = $(this).val();
                });
                $('.install').replaceWith(loading);
                $.getJSON('ajax.php?' + $.param(data) , function(data){
                    if( data.status == 'success'){
                        window.location = '../';
                    } else {
                        $('.loading').replaceWith(install_button);
                        $('.install').html('Error').removeClass('slim_button').addClass('slim_button_red');
                    }
                } ); 
            }
        </script>
        <style>
            input {
                width: 100%;
            }
            a {
                width: 80%;
                text-align: center;
            }
            
        </style>
    </head>
    <body style="background: #3d94f6">
        <div style="position: absolute;width: 50%;left: 0px;top: 0px;height: 50%;" >
        <div style="position: absolute;width: 300px;right: -150px;height: 300px; bottom: -150px;background:white;border-radius: 15px;  box-shadow: 0 0 10px 0 #000 inset; border: 5px;padding: 10px;" >       
            <table style="width: 100%;">
            <tr><td colspan="2" class="status" ></td></tr>
            <tr><th colspan="2">Database</th></tr>
            <tr><td style="text-align: left;" >Host Name</td><td style="text-align: right;"><input class="database" id="database_hostname" value="localhost" /></td></tr>
            <tr><td style="text-align: left;" >Port</td><td style="text-align: right;" ><input class="database" id="database_port" value="3306" /></td></tr>
            <tr><td style="text-align: left;" >Database Name</td><td style="text-align: right;" ><input class="database" id="database_name" /></td></tr>
            <tr><td style="text-align: left;" >User</td><td style="text-align: right;" ><input class="database" id="database_user" value="root" /></td></tr>
            <tr><td style="text-align: left;" >Password</td><td style="text-align: right;" ><input type="password" class="database" id="database_password" /></td></tr>
            <tr><td colspan="2" style="text-align: center;" ><a class="slim_button check_db" onclick="check_db()" >Check DB</a></td>
            <tr><th colspan="2">Admin Info</th></tr>
            <tr><td style="text-align: left;" >Admin Password</td><td style="text-align: right;" ><input type="password" id="admin_password" /></td></tr>
            <tr><td colspan="2" style="text-align: center;" ><a class="slim_button install" onclick="install()" >install</a></td>
        </table>
        </div> </div>
    </body>
    
</html> 
<?php } else { 
    header("location: ../");
    
    }?>