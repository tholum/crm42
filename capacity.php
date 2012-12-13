<?php
//ini_set("display_errors" , 1 );
require_once('app_code/config.inc.php');
require_once('class/class.CapacityCalc.php');
require_once('class/class.Capacity.php');
$calc = new CapacityCalc;
echo date("H:i:s") . " ". microtime() . "<br>";
echo "For order id = 181 and FCT id = 12  => ".$calc->calculate_capacity( "order", "1", "1" ) . " minutes <br>"; 
//echo "For order id = 158 and FCT id = 1  => ".$calc->calculate_capacity( "order", "158", "1" ) . " minutes <br>"; 
//echo "For order id = 91 and FCT id = 1  => ".$calc->calculate_capacity( "order", "91", "1" ) . " minutes <br>"; 
//echo "For work order id = 27 and FCT id = 23  => ".$calc->calculate_capacity( "work order", "27", "23" ) . " minutes <br>"; 
echo date("H:i:s") . " ". microtime() . "<br>";
/*
$capacity = new Capacity();
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

$tasks = $capacity->get_tasks();
echo "<table border >";
foreach( $tasks as $t){
    //print_r( $t );
    $order_info=$capacity->get_order_info($t["order_id"]);
    echo "<tr><td>" . $t["flow_chart_id"] . "</td><td>". $capacity->get_total_predicted_path_days($t["flow_chart_id"]) . "</td><td>" . $order_info["ship_date"] . "</td><td>" . $capacity->getWorkingDays( date("Y-m-d"),$order_info["ship_date"] , array() ) . "</td><td>" . $t["order_id"] . "</td><td>" . $capacity->get_prorate_percent($t['order_id'], $t["flow_chart_id"]) . "%</td></tr>";
    //echo "<br>";
}
echo "</table>";

    $arr = $capacity->get_prorated_duedates();
echo array2csv( $arr );

    print_r( $capacity->group_by_department_then_date());
    $capacity->set_capacitys();
echo "<br/>". date("H:i:s") . " ". microtime() . "<br>";*/
?>