function( root , response ){$('.auto_resize_width_minus_350').css('width' , $(window).width() - 350 );slimcrm.change_subpage_buttons( [ {text: 'Back to Contact' , onclick: '<?php 
$person = $this->db->fetch_assoc($this->db->query("SELECT * FROM contacts WHERE contact_id = '" . $vars['contact_id'] . "' "));
            if( $person['type'] == "People"){
                $contact_id = $person['company'];
            } else {
                $contact_id = $contact_id;
            }
echo addslashes($this->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $contact_id )) ); ?>' }  ] );}