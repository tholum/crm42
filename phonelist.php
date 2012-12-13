<?php
require_once('class/global.config.php');
require_once('class/database.inc.php');
$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
$result = $db->query("SELECT * FROM currentcalls");
$arr = array();
while( $row=mysql_fetch_array($result)){
    $arr[] = $row;
}
    function array2csv($array , $type="html"){
        $keys = array();
        foreach( $array as $n => $v ){
            foreach( $v as $nn => $vv ){
                $keys[$nn] = $nn;
            }
        }
        
        if( $type == "csv"){
            $begin = "";
            $row_begin = "";
            $between = ",";
            $row_end = "\n";
            $end = "";
        } else {
            $begin = "<table border >";
            $row_begin = "<tr><td>";
            $between = "</td><td>";
            $row_end = "</td></tr>";
            $end = "</table>";           
        }
        
        
        
        $return = "$begin$row_begin" . implode("$between" , $keys) . "$row_end";
        foreach( $array as $n => $v ){
            //$return .= implode("," , $v) . "\n";
                    $return .= "$row_begin" . implode("$between" , $v) . "$row_end";
        }        
        
        return $return . "$end";
        
        
        
        
        
    }
    echo array2csv($arr);
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
