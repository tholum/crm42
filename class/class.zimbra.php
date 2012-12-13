<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author Tim Holum
 */
class zimbra {
    var $token;
    var $_zimbra_preauth;
    function  __construct() {
        $_zimbra_preauth = array(
            "voip.couleetechlink.com" => array(
                "qstring" => "account={account}&by={by}&timestamp={timestamp}&expires={expires}&preauth={preauth}",
                "preauthkey" => "6b7ead4bd425836e8cf0079cd6c1a05acc127acd07c8ee4b61023e19250e929c",
            ),
            "slimcrm.com"=>array(
                "qstring" => "account={account}&by={by}&timestamp={timestamp}&expires={expires}&preauth={preauth}&redirectURL=/zimbra/h/",
                "preauthkey" => "1370e109858584edd3616c9575ce9671a4f75e65295b17f74e0548a0de57d7e5",
            ),
        );
    }


}

?>

