<?php
$color_hex = $_REQUEST["color"];
$id = $_REQUEST['id'];
if( $id != ''){
    ob_start();
    require_once('app_code/global.config.php');
    require_once('class/config.inc.php');
    require_once('class/class.email_client.php');
    require_once('class/class.flags.php');
    require_once('class/class.GlobalTask.php');
    require_once('class/class.smtp.php');
    require_once('class/class.display.php');
    require_once('class/class.casecreation.php');
    require_once('class/class.dynamicpage.php');
    require_once('class/class.FctSearchScreen.php');
    require_once('class/class.eapi_order.php');
    require_once('class/class.eapi_account.php');
    require_once('class/class.cases.php');
    require_once('class/class.note.php');
    require_once('class/class.knowledgebase.php');
    ob_end_clean();
    $flags = new Flags();
    $id_arr = explode( "|" , $id);
    //$color_hex = '';
    foreach( $id_arr as $fid ){
        if( $fid != ''){
            if( $color_hex != '' ){
                $color_hex .= "|";
            }
            if( strtoupper($fid) != "NULL"){
                $color_hex .= $flags->get_flag_color($fid);
            } else {
                $color_hex .= "aaaaaa";
            }
        }
    }
}
//$color_arr = array_map('hexdec', explode('|', wordwrap(substr($color_hex, 1), 2, '|', 1)));
//echo $color_hex;
function genterate_flag( $hex ){
    $r = hexdec( substr($hex,0 , 2) );
    $g = hexdec( substr($hex,2 , 2) );
    $b = hexdec( substr($hex,4 , 2) );

    // create a true colour, transparent image
     // turn blending OFF and draw a background rectangle in our transparent colour
    $default_size=30;
    $size = $_REQUEST["size"];
    if( $size == ''){
        $size = $default_size;
    }
    $iwidth=$size;
    $iheight=$size;

     $image=imagecreatetruecolor($iwidth,$iheight);
     //
     imagealphablending($image,false);
     $col=imagecolorallocatealpha($image,255,255,255,127);
     $newc=imagecolorallocate($image, $r, $g , $b );
     $black=imagecolorallocatealpha($image, 0, 0, 0 , 50);
     imagefilledrectangle($image,0,0,$iwidth,$iheight,$col);
     //imagefilledrectangle($image,10,10,($iwidth -10) ,($iheight-10),$newc);
     $values = array(
         0 , $size / 10  ,
         0 , $size / 2 ,
         $size / 2 , $size ,
         $size , $size / 2 ,
         $size / 2 , 0 , 
         $size / 10 , 0 
     );
     imagefilledpolygon($image , $values , 6 , $newc );
     //imagefilledellipse($image, $size / 5, $size / 5 , $size / 8 , $size / 8, $black);
     imagefilledellipse($image, $size / 5, $size / 5 , $size / 6 , $size / 6, $col);
     //imagefilledpolygon($image , $values , 4 , $col );
    // imagealphablending($image,true);
     // ^^ Alpha blanding is back on.

     // insert image manipulation stuff in here

     // output the results... 
     imagealphablending($image,true);
    // imagesavealpha($image,true);
     return $image;
 
}
$size = $_REQUEST["size"];

if( $size == '' ){ $size = 30; }
$offset = $size / 2;
if($color_hex==''){
    $color_hex='aaaaaa';
}
$hex= $color_hex;//$_REQUEST["color"];
$color_arr = explode("|", $hex);
$num = count( $color_arr);
if( $num == 1 ){
    $fx = $size; $fy = $size;
} else {
$fx = ($offset * 2 ) + ( $offset * $num ); $fy = $size;
}
$final = imagecreatetruecolor($fx, $fy);
imagealphablending($final,false);
$col=imagecolorallocatealpha($final,255,255,255,127);
imagefilledrectangle($final,0,0,$fx,$fy,$col);
imagealphablending($final,true);
$x = 0;
foreach( $color_arr as $flag_color ){
   // echo $flag_color . " $x $num $fx , $fy";
    $image = genterate_flag($flag_color);
    imagecopy($final, $image, $x * $offset, 0, 0, 0, $size, $size );
    imagealphablending($final,true);
    $x++;
    //$final = $image;
}



 header("Content-Type: image/png;");
    imagealphablending($final,false);
    imagesavealpha($final,true);

imagepng($final);

?>
