<?php 
    /*$json = $this->eapi_account->get_account( $module_id );
    $ob = json_decode($json);
    $email = $ob->Email;

     * 
     */
    $json = $this->eapi_account->search_account( $module_id );
     $ob = json_decode($json);
     foreach($ob->SearchResult as $result ){
         if( $result->Id == $module_id ){
            $email = $result->Email; 
         }
     }
?>