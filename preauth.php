<?
//SLIMCRM.COM 1370e109858584edd3616c9575ce9671a4f75e65295b17f74e0548a0de57d7e5
//VOIP.COULEETECHLINK.COM 6303711cc5803d33e606f54c9c67146123c42d48578a120e0dc60fe946b08798
//DEMO.SLIMCRM.COM e8d25975bc6cd2d457738a17374fbde76c310a501a295bd136ccf08e8b02e8f9
$PREAUTH_KEY="1370e109858584edd3616c9575ce9671a4f75e65295b17f74e0548a0de57d7e5";

    /**
    * Globals. Can be stored in external config.inc.php or retreived from a DB.
    */
    //$PREAUTH_KEY="0f6f5bbf7f3ee4e99e2d24a7091e262db37eb9542bc921b2ae4434fcb6338284";
    $WEB_MAIL_PREAUTH_URL="/service/preauth";

    /**
    * User's email address and domain. In this example obtained from a GET query parameter.
    * i.e. preauthExample.php?email=user@domain.com&domain=domain.com
    * You could also parse the email instead of passing domain as a separate parameter
    */

    $user = "tholum";//$_GET["user"];
    $domain= "slimcrm.com"; //$_GET["domain"];

    $email = "$user@$domain";

    if(empty($PREAUTH_KEY)) {
        die("Need preauth key for domain ".$domain);
    }

    /**
    * Create preauth token and preauth URL
    */
    $timestamp=time()*1000;
    $preauthToken=hash_hmac("sha1",$email."|name|0|".$timestamp,$PREAUTH_KEY);
    $preauthURL = $WEB_MAIL_PREAUTH_URL."?account=".$email."&by=name&timestamp=".$timestamp."&expires=0&preauth=".$preauthToken . "&redirectURL=/zimbra/?skin=fields";

    /**
     * Redirect to Zimbra preauth URL
     */
    header("Location: $preauthURL");
    //echo date("Y-m-D");

?>
