<?php
ini_set( "display_errors" , 0 );
error_reporting(0);
require_once 'class/global.config.php';
require_once 'class/database.inc.php';

$db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);

function backup_database(){
    $dump = shell_exec("/usr/bin/mysqldump -u " . DATABASE_USER . " -p" .DATABASE_PASSWORD. " " . DATABASE_NAME  );
    $backup = fopen("/backup/" . DATABASE_NAME . time() . ".sql" , "w");
    fwrite( $backup , $dump );
    fclose($backup);
    
    
}



function create_contact( $user_id , $first_name , $last_name , $title , $company_name , $comments , $type , $company_id='' ){
	global $db;
	$ct = array();
	$ct["user_id"] = $user_id;
	$ct["first_name"] = $first_name;
	$ct["last_name"] = $last_name;
	$ct["title"] = $title;
	$ct["company_name"] = $company_name;
	$ct["comments"] = $comments;
	$ct["type"] = $type;
        $ct["company"] = $company_id;
	$db->insert(TBL_CONTACT, $ct);
	$contact_id = $db->last_insert_id();
	$perm = array();
	$perm["module_id"] = $contact_id;
	$perm["module"] = "TBL_CONTACT";
	$perm["access_to_type"] = "*";
	$perm["access_to"] = "*";
	$perm["access_type"] = "VIEWONLY";
	$db->insert("tbl_element_permission" , $perm );
	$perm["access_to_type"] = "TBL_USERGROUP";
	$perm["access_to"] = "contact_admin";
	$perm["access_type"] = "FULL";
	$perm["display"] = "NONE";
	$db->insert("tbl_element_permission" , $perm );
	return $contact_id;

}

function add_address( $contact_id , $street_address , $city , $state , $zip , $type = "Work" , $country = "United States"  ){
	global $db;
	$addr = array();
	$addr["contact_id"] = $contact_id;
	$addr["street_address"] = $street_address;
	$addr["city"] = $city;
	$addr["state"] = $state;
	$addr["zip"] = $zip;
	$addr["type"] = $type;
	$addr["country"] = $country;
	$db->insert("contacts_address" , $addr );
}

function add_email( $contact_id , $email , $type = "Work" ){
	global $db;
	$eml = array();
	$eml["contact_id"] = $contact_id;
	$eml["email"] = $email;
	$eml["type"] = $type;
	$db->insert("contacts_email" , $eml );
}

function add_im( $contact_id , $im , $type = "Work" ){
	global $db;
	$arr = array();
	$arr["contact_id"] = $contact_id;
	$arr["im"] = $im;
	$arr["type"] = $type;
	$db->insert("contacts_im" , $arr );
}

function add_phone( $contact_id , $phone , $type = "Work" ){
	global $db;
	$arr = array();
	$arr["contact_id"] = $contact_id;
	$arr["number"] = $phone;
	$arr["type"] = $type;
	$db->insert("contacts_phone" , $arr );
}

function add_website( $contact_id , $website , $type = "Work" ){
	global $db;
	$arr = array();
	$arr["contact_id"] = $contact_id;
	$arr["website"] = $website;
	$arr["type"] = $type;
	$db->insert("contacts_website" , $arr );
}

function add_note( $contact_id , $note ){
	global $db;
	$arr = array();
	$arr["user_id"] = "1";
	$arr["description"] = $note;
	$arr["module_name"] = "TBL_CONTACT";
	$arr["module_id"] = $contact_id;
	$db->insert("tbl_note" , $arr );
}
//Convert A Single Line Name to first_name last_name
function NameConvert_SLN( $name ){
	$name_arr = explode( " " , $name );
	$first_name = $name_arr[0];
	$last_name = $name_arr[ count( $name_arr ) - 1 ];
	return array( "first_name" => $first_name , "last_name" => $last_name );

}

function csv_to_assoc( $file ){
	$hd = fopen($file  , "a+" );
	$array = array();
	$row = 0;
	while( ($data = fgetcsv( $hd , 5000 )) !== false ){
		if( $row == 0 ){
			$key = $data;
		} else {
			$tmp = array();
			foreach( $data as $n => $v ){
				if( array_key_exists( $n , $key ) ){
					$tmp[ $key[ $n ] ] = $v;
				}
			}
			$array[$row] = $tmp;
		}
		$row++;
	}
	fclose( $hd );
	return $array;
}


function date_convert( $date , $format ){
	switch( $format ){
		case "n/j/y":
		    $exp_date = explode( "/" , $date );
		    if( count( $exp_date ) == 3 ) {
		  	if( $exp_date[2] < 70 ){
				$year = "20" . $exp_date[2];
	             	} else { 
				$year = "19" . $exp_date[2];
		     	}
			$month = $exp_date[0];
			$day = $exp_date[1];
			return mktime( 10 , 0 , 0 , $month , $day , $year );

		     }

		break;
		case "n/j/Y":
		    $exp_date = explode( "/" , $date );
		    if( count( $exp_date ) == 3 ) {
			$year = $exp_date[2];
			$month = $exp_date[0];
			$day = $exp_date[1];
			//echo "$year $month $day = " . date( "Y-m-d" , mktime( 10 , 0 , 0 , $month , $day , $year ) ) . "\n";
			return mktime( 10 , 0 , 0 , $month , $day , $year );

		     }

		break;
		case "Y-m":
		    $exp_date = explode( "-" , $date );
			if( count ($exp_date) == 2 ){
				//echo "Month: " . $exp_date[1] . "\n" . "Year: " . $exp_date[0];
			   return mktime( 10 , 0 , 0 , $exp_date[1] , 1 , $exp_date[0] );
			}
		break;
		

	}


}
function check_sales_date( $date , $contact_id ){
	global $db;
	$res = $db->query( "SELECT * FROM erp_salesdata_custum WHERE date = '" . date( 'Y-m-d' ,  $date ) . "' AND contact_id = '$contact_id'" );
	if( mysql_num_rows( $res ) == 0 ){
		return true;
	} else {
		return false;
	}
}

function process_csr( $csr_text ){
	switch( $csr_text ){
		case "Kimberly":
		case "Kimbery":
		case "kimberly":
		case "Kmberly":
		case "kimberly":
		case "Kimbrly":
		case "Kimberly & Mark":
		case "Kimberly & Ben":
		case "Kimberly/Ben":
		case "KJK":
			return "3";			
		break;

		case "Mark":
		case "MJK":
		case "MARK":
			return "7";
		break;
		case "Jennifer":
		case "Jennnifer":

			return "6";
		break;


		case "CJ":
		case "Chris Nw":
			return "4";
		break;

		case "Kenny":
			return "2";
		case "Karen":
			return "8";
		break;
		default:
			return '';
		break;
	}
}


function process_account_type( $account_type ){
/*
Vendor
Custom
Wholesale
Distributor
Consumer
*/


	switch( $account_type ){
		case "CON":
		case "Con":
			return "Consumer";
		break;
		case "SUB":
		case "SUBw":
		case "SUBW":
		case "SUBZ":
			return "Custom";
		break;
		case "WHO":
		case "WHO-YA":
		case "zWHO":
			return "Wholesale";
		break;
		case "WHO-DIS":
			return "Distributor";
		break;
		case "ZZZ":
		case '':
			return '';
		break;

	}
}
function text2bool( $text ){
	switch( $text ){
		case "TRUE":
		case "True":
		case "true":
		case "1":
			//echo "$text = true\n";
			return 'true';
		break;
		case "FALSE":
		case "false":
		case "False":
		case "0":
			//echo "$text = false\n";
			return 'false';
		break;
		default:
			//echo "$text = unknown\n";
			return $text;
		break;
	}
}
function process_sales_rep( $repid ){
	switch( $repid ){
		case "DYSTEJ":
			return "DYSTEJ";	
		break;
		case "WIZBEN":
			return "WIZBEN";	
		break;
		case "KONMAR":
			return "KONMAR";
		break;
		case "ASPKAR":
			return "ASPKAR";
		break;
		default:
			return "";
		break;
	}

}


function erp_custom( $contact_id , $contact ){
    global $db;
    $run = false;
    $custom = array();
    if(array_key_exists('custom_csr', $contact )){
        if( $contact['custom_csr'] != ''){
            $run = true;
            $custom['csr'] = $contact['custom_csr'];
        }
        
    }
    
    if(array_key_exists('custom_account_type', $contact )){
        if( $contact['custom_account_type'] != ''){
            $run = true;
            $custom['account_type'] = $contact['custom_account_type'];
        }
        
    }
    
    
    if(array_key_exists('custom_peachtree_id', $contact )){
        if( $contact['custom_peachtree_id'] != ''){
            $run = true;
            $custom['peachtree_account'] = $contact['custom_peachtree_id'];
        }
        
    }
    
    
    if(array_key_exists('custom_sales', $contact )){
        if( $contact['custom_sales'] != ''){
            $run = true;
            $custom['sales'] = $contact['custom_sales'];
        }
        
    }
    if(array_key_exists('custom_account_id', $contact )){
        if( $contact['custom_account_id'] != ''){
            $run = true;
            $custom['account_id'] = $contact['custom_account_id'];
        }
        
    }
    if( $run == true ){
       $custom["contact_id"] = $contact_id; 
       $db->insert("erp_contactscreen_custom" , $custom );
    }
    
}









function process_custom_contact( $array , $contact_id ){
	global $db;
	$ytd= $array["ytd"];
	$ccs = array();
	$ccs["contact_id"] = $contact_id;	
	$ccs["account_type"] = process_account_type( $array["Customer Type"] );
	$ccs["csr"] = process_csr( $array["CSR"] );
	//echo $array["CSR"] . " = " . $ccs["csr"] . "\n";
	$ccs["peachtree_account"] = $array["Customer ID"];
	$ccs["peachtree_ytd"] =  "$ytd";
	//echo "X:" . $array["ytd"]. "\n";
	$ccs["peachtree_inactive"] = text2bool( $array["Inactive"]);
	$ccs["peachtree_prospect"] = text2bool( $array["Prospect"]);
	$ccs["peachtree_standard_terms"] = text2bool( $array["Use Standard Terms"] );
	$ccs["peachtree_cod"] = text2bool( $array["C.O.D. Terms"] );
	$ccs["peachtree_prepaid"] = text2bool( $array["Prepaid Terms"] );
	$ccs["peachtree_due_days"] = $array["Due Days"];
	$ccs["peachtree_credit_limit"] = $array["Credit Limit"];
	$ccs["peachtree_account_created"] = date( "Y-m-d" , date_convert(  $array["Customer Since Date"], "n/j/Y" ) );//[Customer Since Date] => 1/2/2007
	$ccs["sales"] = process_sales_rep( $array["Sales Representative ID"] );
	$db->insert("erp_contactscreen_custom" , $ccs );
}

function process_sales( $array , $contact_id){
	global $db;
	$tmp_arr = array();
	foreach( $array as $n => $v ){
		if( substr_count( $n , "Sales-Period End" ) == 1 ){
			if( date( "Ym" ) > date("Ym" , date_convert( substr( $n , 17 ) , "n/j/y" ) )){
				$tmp_arr[ date( "Y-m" , date_convert( substr( $n , 17 ) , "n/j/y" ) ) ] = $v;
			}
		}
	}
        krsort( $tmp_arr );
	$x = 1;
	$ytd = 0;
	foreach( $tmp_arr as $k => $s ){
		if( $x <= 12 ){
			$ytd = $ytd + $s;
		}
		if( check_sales_date( date_convert( $k , "Y-m" ) , $contact_id ) ){
			$sdc = array();
			$sdc["contact_id"] = $contact_id;
			$sdc["date"] = date( 'Y-m-d' ,  date_convert( $k , "Y-m" ) );
			$sdc["amount"] = $s;
			$db->insert( "erp_salesdata_custum" , $sdc );
		}
		$x++;
	}
	return $ytd;
}

function process_2line_address( $contact_id ,  $line1 , $line2 , $city , $state , $zip , $country ){
	if( $line1 != '' || $line2 != '' ){
		if( $line1 != '' && $line2 != '' ){
			$street_address = "$line1 : $line2";
		} elseif( $line1 != '' ){
			$street_address = $line1;
		} else {
			$street_address = $line2;
		}
		if( $country == '' || $country == "USA" || $country == "usa" ){
			$country = "United States";
		} 

		add_address( $contact_id , $street_address , $city , $state , $zip , "Work" , $country  );
	}
}

function get_tagByName( $name ){
	global $db;
	$result = $db->query("SELECT * FROM `tags_name` WHERE `name` = '$name'");
	if( mysql_num_rows( $result ) == 0 ){
		$db->query("INSERT INTO `tags_name` (`name`) VALUES('$name')");
		return $db->last_insert_id();
	} else {
		$arr = $db->fetch_assoc( $result );
		return $arr["tag_id"];
	}



}


function add_tag( $contact_id ,  $tag_name ){
	global $db;
	$id = get_tagByName($tag_name);
	$t = array();
	$t["tag_id"] = $id;
	$t["module"] = "TBL_CONTACT";
	$t["module_id"] = $contact_id;
	$db->insert("tags" , $t );
}
$pass = $_REQUEST["pass"];
if(array_key_exists('file', $_FILES) AND $pass == "alpine" ){
$array = csv_to_assoc( $_FILES["file"]["tmp_name"] );
//$tmp = array();
//clean_contacts();
$x = 1;
$y = 1;

    

backup_database();
foreach( $array as $contact ){
    
        
	$contact_id = create_contact( "1" , '' , '' , '' , $contact["company_name"] , '' , "Company" );
        erp_custom( $contact_id , $contact );
        $x = 1;
        while( $x < 101 ){
            if(array_key_exists("company_addr" . $x . "_street", $contact)){
                if( $contact["company_addr" . $x . "_street"] != '' ){
                    try{
                        add_address( $contact_id , $contact["company_addr" . $x . "_street"] , $contact["company_addr" . $x . "_city"] , $contact["company_addr" . $x . "_state"] ,$contact["company_addr" . $x . "_zip"] , ucfirst( $contact["company_addr" . $x . "_type"] ) , "United States"  );
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x = 1;
        while( $x < 101 ){
            if(array_key_exists("company_email" . $x , $contact)){
                if($contact["company_email" . $x ] != ''){
                    try{
                        add_email($contact_id, $contact["company_email" . $x], $contact["company_email" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("company_im" . $x , $contact)){
                if( $contact["company_im" . $x] != '' ){
                    try{
                        add_im($contact_id, $contact["company_im" . $x], $contact["company_im" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("company_phone" . $x , $contact)){
                if( $contact["company_phone" . $x] != '' ){
                    try{
                        
                        add_phone($contact_id, preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["company_phone" . $x])), $contact["company_phone" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                } else {
                    echo '$contact["company_phone" '. $x .'] = ' . $contact["company_phone" . $x] . "\n";
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("company_website" . $x , $contact)){
                if( $contact["company_website" . $x] != '' ){
                    try{
                        add_website($contact_id, $contact["company_website" . $x], $contact["company_website" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
       $x=1;
        while( $x < 101 ){
            if(array_key_exists("company_tag" . $x , $contact)){
                if( $contact["company_tag" . $x] != '' ){
                try{
                    
                    add_tag($contact_id, $contact["company_tag" . $x]);
                } catch( Exception $e){
                    echo $e;
                }
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("company_note" . $x , $contact)){
                if( $contact["company_note" . $x] != '' ){
                    try{
                        add_note($contact_id, $contact["company_note" . $x]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }       
        
        $z = 1;
        
        // Start the per subperson while
        while( $z < 101){
            if(array_key_exists('person' . $z . '_fname', $contact) AND array_key_exists('person' . $z . '_lname', $contact)){
                if( $contact['person' . $z . '_fname'] != '' AND $contact['person' . $z . '_lname'] != '' ){
                    $person_id = create_contact( "1" , $contact['person' . $z . '_fname'] , $contact['person' . $z . '_lname'] , $contact['person' . $z . '_title'] , '' , '' , "People" , $contact_id );
/*************************************************************************************************
 * Person Start
 */
                    $x=1;
         echo $person_id . "\n" . "person" . $z . "_addr" . $x . "_street", $contact . "\n";
         while( $x < 101 ){
            if(array_key_exists("person' . $z . '_addr" . $x . "_street", $contact)){
                if( $contact["person' . $z . '_addr" . $x . "_street"] != '' ){
                    try{
                        add_address( $person_id , $contact["person" . $z . "_addr" . $x . "_street"] , $contact["person" . $z . "_addr" . $x . "_city"] , $contact["person" . $z . "_addr" . $x . "_state"] ,$contact["person" . $z . "_addr" . $x . "_zip"] , ucfirst( $contact["person" . $z . "_addr" . $x . "_type"] ) , "United States"  );
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x = 1;
        while( $x < 101 ){
            if(array_key_exists("person" . $z . "_email" . $x , $contact)){
                if($contact["person" . $z . "_email" . $x ] != ''){
                    try{
                        add_email($person_id, $contact["person" . $z . "_email" . $x], $contact["person" . $z . "_email" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("person" . $z . "_im" . $x , $contact)){
                if( $contact["person' . $z . '_im" . $x] != '' ){
                    try{
                        add_im($person_id, $contact["person" . $z . "_im" . $x], $contact["person" . $z . "_im" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("person" . $z . "_phone" . $x , $contact)){
                if( $contact["person" . $z . "_phone" . $x] != '' ){
                    try{
                        add_phone($person_id, preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["person" . $z . "_phone" . $x])), "person" . $z . "_phone" . $x . "_type");
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
        $x=1;
        while( $x < 101 ){
            if(array_key_exists("person" . $z . "_website" . $x , $contact)){
                if( $contact["person" . $z . "_website" . $x] != '' ){
                    try{
                        add_website($person_id, $contact["person" . $z . "_website" . $x], $contact["person" . $z . "_website" . $x . "_type"]);
                    } catch( Exception $e){
                        echo $e;
                    }
                }
           }
           $x++;
        }
       $x=1;
   
                    
                    
                    
                  
                    
                    
                    
 /**************************************************************************************************
  * Person End
  */                   
                 }
             }

            $z++;
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
	//add_tag("Vendors" , $contact_id );
	//process_2line_address( $contact_id ,  $contact["Address-Line One"] , $contact["Address-Line Two"] , $contact["City"] , $contact["State"] , $contact["Zip"] , "USA" );
	
	//$first_name = $name["first_name"];
	//$last_name = $name["last_name"];
	//$tmp[ $contact["CSR"] ] = "tmp";
	//$contact_id = create_contact( "1" , $first_name , $last_name , '' , $contact["Customer Name"] , '' , "Company" );
	//$contact["ytd"] = process_sales( $contact , $contact_id );
	if( $contact["Telephone 1"] != '' ){ add_phone( $contact_id , preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["Telephone 1"])) ); }
	if( $contact["Telephone 2"] != '' ){ add_phone( $contact_id , preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["Telephone 1"])) ); }
	if( $contact["Fax Number"] != '' ){ add_phone( $contact_id , preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["Fax Number"])) , "Fax" ); }
	/*if( $contact["Telephone 2"] != '' ){ add_phone( $contact_id , preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["Telephone 2"] )) ); }
	if( $contact["Fax Number"] != '' ){ add_phone( $contact_id , preg_replace( '#[^0-9]#' , '' , strip_tags( $contact["Fax Number"] )) , "Fax" ); }*/
	if( $contact["Vendor E-mail"] != '' ){ add_email( $contact_id ,$contact["Vendor E-mail"]  ); }
	 add_note( $contact_id , "Vender Since: " . $contact["Vendor Since Date"] . "\n" . $contact["Info"] );

  /*
	if( $contact["Add'l  e-Notify"] != '' ){ add_email( $contact_id ,$contact["Add'l  e-Notify"] , "Other" ); }
	if( $contact["Customer Web Site"] != '' ){ add_website( $contact_id ,$contact["Customer Web Site"]  ); }
	//$tmp[$contact["Sales Representative ID"]] = "tmp";
	*/
//	}
}
} else {
    ?>
<html>
<body>

<form action="" method="post"
enctype="multipart/form-data">
<label for="file">CSV:</label>
<input type="file" name="file" id="file" /><br/>
<label for="pass">Password</label><input type="password" name="pass" id="pass" />
<br />
<input type="submit" name="submit" value="Submit" />
</form>

</body>
</html>


<?
}
?>