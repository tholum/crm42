<?php
$q = $_REQUEST['q'];
$url = "http://search.twitter.com/search.json?lang=en&rpp=500&q=" . urlencode ( $q );
        ob_start();
            $curl = curl_init();
            curl_setopt( $curl , CURLOPT_URL , "$url" );
            curl_setopt( $curl , CURLOPT_HTTPHEADER , array("Accept: application/json") );
            curl_exec( $curl );
        $html = ob_get_contents();
        ob_end_clean();
echo $html;
?>