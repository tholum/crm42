<div style="height: 40px;padding-left: 55px;position: absolute;right: 0px;top: 2px;display:inline-block;width: 100%;background: #729BC7">

    <div style="display: inline-block;position: relative;width: 50px;" ></div>
<input 
    onkeyup="contact.GetContact( $(this).val() , '' ,'','','contact',{target: 'search_result', preloader: 'prl'});" 
    style="width: 250px;top: 5px;position: relative;" class="welcome_quicksearch ui-autocomplete-input" id="search" >
<div class="buttons_menu" style="position: relative; left: 50px;display:inline-block; background: #729BC7;font-weight: bold;"  >
    <div 
        style="display: inline-block;"
        class="page_buttons contact_list " 
        onclick="em.create_contact({onUpdate: function(response,root){dynamic_page.phplivex_subpage('contacts' , 'edit' ,{'contact_id': response } ,  { target: 'display_contact_area'  });}})">Create Contact</div>
    </div>
</div>
<div style="padding: 25px;width: 300px;display:inline-block;position: absolute;top:20px;left: 0px;background: #729BC7;z-index: -2;border-bottom-right-radius: 10px;" id="search_result" >
<?php 
$contact = new Company_Global();
echo $contact->GetContact('','','','','contact');
?>
</div>
<div class="auto_resize_width_minus_350" style="display:inline-block;position: absolute;top:40px;left: 350px;background: #729BC7;min-width: 10px;min-height: 10px; width: 1000px;">
    <div style="background: white; border-top-left-radius: 10px;padding: 25px;" id="display_contact_area">&nbsp;</div>
</div>