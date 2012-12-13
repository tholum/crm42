function( root , response ){$('.auto_resize_width_minus_350').css('width' , $(window).width() - 350 );<?php 
        if( array_key_exists('contact_id' , $vars ) ) { 
            echo $this->phplivex_subpage_link('contacts','contact_profile','display_contact_area',array('contact_id'=> $vars['contact_id'] ) ); 
            
            }
        if( array_key_exists('create_contact',$vars)){
            if($vars['create_contact'] == true ){
                $tmp = array();
                if(array_key_exists('phone_number', $vars)){
                    $tmp['phone_number'] = $vars['phone_number'];
                }
                
                ?>em.create_contact( { phone_number: '<?php echo $vars['phone_number']; ?>' } ,{onUpdate: function(response,root){dynamic_page.phplivex_subpage('contacts' , 'edit' ,{'contact_id': response } ,  { target: 'display_contact_area'  });}});<?
            }
        }
?>}