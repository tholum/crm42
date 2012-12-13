<?php
/*****************************************************************************************
This is Coulee Techlink's / Tim ivey Company's yui php functions, A few things to know
Q: What is a "Nested Assoceated array"
A: It is an array of assoceated array, An associated array is an array where the key is the 
indicator of what type of data for example lets say you have 2 users with info like this
firstname	lastname	id
bob			doe		1
bill			brink		2

an associated array of bob's user looks like
$array = array();
$array["firstname"] = "bob";
$array["lastname"] = "doe";
$array["id"] = "1";
OR 
$array = array( "firstname" => "bob" , "lastname" => "doe" , "id" => "1" );

Now a nested assoceated array would be 
$accoc = array();
$accoc[0] = array()
$accoc[0]["firstname"] = "bob";
$accoc[0]["lastname"] = "doe";
$accoc[0]["id"] = "1";
$accoc[1] = array()
$accoc[1]["firstname"] = "bill";
$accoc[1]["lastname"] = "brink";
$accoc[1]["id"] = "2";
OR
$accoc = array( array("firstname" => "bob" , "lastname" => "doe" , "id" => "1" ) , array("firstname" => "bill" , "lastname" => "brink" , "id" => "2 ) );
The reason I went with this format is 
1 it is extreamly easy to create from an sql statment for example
function test(){
	$result = mysql_query("SELECT * FROM TABLE");
	$return = array(); 
	while( $row = mysql_fetch_assoc( $result ) ){ $return[] = $row; }
	return $return;
}
and it is also very easy to minipulate you just have to do
If you have any questions on this feel free to ask me
tholum@couleetechlink.com

*/

class phpyui{
	var $path;
	var $header;
	var $body;
	var $js_inc;
	var $css_inc;
	var $javascript_init; // an array of functions javascript needs to call
	function __construct( $path="http://yui.yahooapis.com/2.8.2r1/build/" ){
            $this->js_inc = array();
            $this->css_inc = array();
            $this->javascript_init = array();
            $this->path = $path;
	}
        /* This function allows you to normalize a string into an xml safe formate, this allows for full html to be returned in xml
         * Usage $xml $phpyui->string_to_xml( "<div>What Ever you 'want' is'good'</div>" );      */
	function string_to_xml( $string ){
            $xml = $string;
            /* I am making sure that all & are not a part of a symbol */
            $xml = str_replace( "&lt;" ,"<" ,  $xml );
            $xml = str_replace( "&gt;" ,">" ,  $xml );
            $xml = str_replace( "&quot;" , '"' ,  $xml );
            $xml = str_replace( "&apos;" ,"'" ,  $xml );
            // Now i replace everything so it is in xml format and can be outputed without errors
            $xml = str_replace( "&" , "&amp;" , $xml );
            $xml = str_replace( "<" , "&lt;" , $xml );
            $xml = str_replace( ">" , "&gt;" , $xml );
            $xml = str_replace( '"' , "&quot;" , $xml );
            $xml = str_replace( "'" , "&apos;" , $xml );
            return $xml;
        }

	function array_to_yuiarray( $array ){
		//{id:"po-0167", date:new Date(1980, 2, 24), quantity:1, amount:4, title:"A Book About Nothing"},
		$return = "";
		foreach( $array as $n => $v ){
			$return .= "{";
			foreach( $v as $nn => $vv ){
				$return .= "$nn:\"$vv\", ";	
			}
			$return = substr_replace($return, "", -2);
			$return .= "},";
		}
		$return = substr_replace($return, "", -1);
		return $return;		
	}
/*****************************************************************************************
This function should only be called in an xml doc, so you should have called both
	header ("content-type: text/xml");
	echo '<?xml version="1.0"?>';
before you call this function
*****************************************************************************************/
	function array_to_yuixml( $array ){
		$return = '<ResultSet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="urn:yahoo:lcl" xsi:schemaLocation="urn:yahoo:lcl http://api.local.yahoo.com/LocalSearchService/V2/LocalSearchResponse.xsd" totalResultsAvailable="501" totalResultsReturned="10" firstResultPosition="1">
		';

		foreach( $array as $n => $v ){
			$return .= "\n\n<Result>\n";
			foreach( $v as $nn => $vv ){
				
				$return .= "\t<$nn>" . strip_tags($vv) . "</$nn>\n";	
			}
			$return .= "</Result>\n";
		}
		    
    	$return .= "</ResultSet>\n";
    	return $return;	
		
	}	
/*****************************************************************************************
$datatype can be xml or array, 
if $datatype is xml then $datasource has to be the url of 
the xml document. 
if $datatype is array, $datasource must be a nested associated array, see the header of this
doc if you need to know what that is
$fields is a nested associeated array with, The assoceated array MUST have key with the field
name, for example 
$fields = array();
$fields[] = array( "key" => "field" , "sortable" =>true ); 

$subscribes optional, it is to do things like on row click, mouseover ext...
$extra is things to overwrite like resultNode or ext...
*****************************************************************************************/	
	function get_body(){
		$return = $this->body;
		$return .= "<script>\n";
		$return .= "\tYAHOO.util.Event.addListener(window, \"load\", yui_init_page  );\n";
		$return .= "</script>\n";
		return $return;
	}
	function add_yui_css( $css ){
		$this->css_inc[ $this->path . $css ] = $this->path . $css;
	}
	function add_yui_script( $script ){
		$this->js_inc[ $this->path . $script ] = $this->path . $script;
	}
	
	function show_panel( $name ){
		return "YAHOO.$name.panel.show();";
	}
	
	function hide_panel( $name ){
		return "YAHOO.$name.panel.hide();";	
	}

	function get_header(){
		$return = "";
		
		foreach( $this->js_inc as $js ){
			$return .= '<script type="text/javascript" src="' . $js . '"></script>' . "\n";
		}
		
		$return .= $this->header;
		foreach( $this->css_inc as $acss ){
			$return .= '<link rel="stylesheet" type="text/css" href="' . $acss . '" />' . "\n";	
		}
		$return .= "<script>\n";
		$return .= "\tfunction yui_init_page(){\n";
		foreach( $this->javascript_init as $script ){
			$return .= "\t\t$script\n";
		}
		$return .= "\t}\n";
		$return .= "</script>\n";
		return $return;
	}
	
	function add_init_script( $script ){
		$this->javascript_init[] = $script;	
	}
	function add_listener( $obj , $action , $var1 = '' , $var2 = '' , $var3 = '' , $var4 = '' , $var5 ='' , $var6='', $var7='' , $var8='' , $var9='', $var10='' ){
		$script = "YAHOO.util.Event.addListener(\"$obj\" , \"$action\"";
		if( $var1 != '' ){
			$script .= ", $var1";	
		}
		if( $var2 != '' ){
			$script .= ", $var2";	
		}
		if( $var3 != '' ){
			$script .= ", $var3";	
		}
		if( $var4 != '' ){
			$script .= ", $var4";	
		}
		if( $var5 != '' ){
			$script .= ", $var5";	
		}
		if( $var6 != '' ){
			$script .= ", $var6";	
		}
		if( $var7 != '' ){
			$script .= ", $var7";	
		}
		if( $var8 != '' ){
			$script .= ", $var8";	
		}
		if( $var9 != '' ){
			$script .= ", $var9";	
		}
		if( $var10 != '' ){
			$script .= ", $var10";	
		}
		
		$script .= ");";
		$this->add_init_script( $script );		
	}
	
	function add_panel( $name , $propertys = array() , $functions = array() ){
		$render = true;
		$this->add_yui_script( "yahoo-dom-event/yahoo-dom-event.js" );
		$this->add_yui_script( "dragdrop/dragdrop-min.js");
		$this->add_yui_script( "container/container-min.js" );
		$this->header .= "<script type=\"text/javascript\" >\n";
		$this->header .= "\tfunction yui_panel_$name(){\n";
		$this->header .= "\t\tYAHOO.namespace(\"$name\");\n";
		$this->header .= "\t\tYAHOO.$name.panel = new YAHOO.widget.Panel( \"$name\" , { ";
		foreach( $propertys as $n => $v ){
			if( $n == "width" ){ $qt = '"'; } else { $qt = ''; }
			$this->header .= "$n:$qt$v$qt, ";	
		}
		$this->header = substr_replace($this->header, "" , -2);
		$this->header .= " } );\n";
		foreach( $functions as $n => $v ){
			if( $n == "render" ){ $render = false; }
			if( $v != NULL && $v != '' ){ $qt = '"'; } else { $qt = '"'; }
			$this->header .= "\t\tYAHOO.$name.$n($qt$v$qt);\n";
		}
		if( $render ){
			$this->header .= "\t\tYAHOO.$name.panel.render();\n";
		}
		$this->header .= "\t}\n";		
		$this->header .= "</script>\n";
		$this->add_init_script( "yui_panel_$name();" );
	}	
	
	function add_ajax_post( $name , $url , $fields = array() , $success = '', $failure = '' , $callbackvar = array() ){
		$this->header .= "<script type=\"text/javascript\" >\n";
		$this->header .= "\tvar yui_ajax_post_success_$name = function(o) {\n\t\t";
		$this->header .= str_replace("\n", "\n\t\t" , $success );
		$this->header .= "\n\t};\n\n";
		
		$this->header .= "\tvar yui_ajax_post_failure_$name = function(o) {\n\t\t";
		$this->header .= str_replace("\n" , "\n\t\t" , $failure );
		$this->header .= "\n\t};\n\n";
		
		$this->header .= "\tvar yui_callback_$name = {\n";
		$this->header .= "\t\tsuccess: yui_ajax_post_success_$name,\n";
		$this->header .= "\t\tfailure: yui_ajax_post_failure_$name,\n";
		$this->header .= "\t\targument: [ ";
		foreach( $callbackvar as $v ){
			$this->header .= "'$v',";
		}		
		$this->header = substr_replace( $this->header , "" , -1 );
		$this->header .= "]\n";
		$this->header .= "\t};\n\n";
		
		$this->header .= "\tfunction yui_ajax_post_call_$name(obj){\n";
		$tmp = false;
		$this->header .= "\t\tvar poststr_$name = ";
		foreach( $fields as $field ){		
			if( $tmp ){ $this->header .= "\t\t\"&"; } else { $this->header .= '"'; }
			$this->header .=  $field["post"] . '="' . "+ encodeURI( document.getElementById(\"" . $field["id"] . "\").value ) +\n";
			$tmp = true;
		}
		$this->header = substr_replace( $this->header , "" , -2 );
		$this->header .= ";\n";
		$this->header .= "\t\tvar request = YAHOO.util.Connect.asyncRequest('POST', \"" . $url . "\", yui_callback_$name,  poststr_$name);\n";
		$this->header .= "\t}\n";
		$this->header .= "</script>\n";

	}
	
	function add_datasource( $name , $datatype="xml" , $datasource="ajax.xml" , $fields = array() , $extra=array() ){
		$this->add_yui_script( "yahoo-dom-event/yahoo-dom-event.js");
		$this->add_yui_script( "datasource/datasource-min.js");
		
		$this->header .= "<script type=\"text/javascript\" >\n";
		switch( $datatype ){
			case "xml":
				$this->add_yui_script("connection/connection-min.js");
				$this->header .= "\tvar yui_DataSource_$name = new YAHOO.util.DataSource(\"$datasource\");\n";
				$this->header .= "\tyui_DataSource_$name.responseType = YAHOO.util.DataSource.TYPE_XML;\n";
				$this->header .= "\tyui_DataSource_$name.responseSchema = {\n";
				if( array_key_exists("resultNode" , $extra)){
					$this->header .= "\t\tresultNode: \"" . $extra["resultNode"] . "\" , \n";
				} else {
					$this->header .= "\t\tresultNode: \"Result\" , \n";					
				}
				$this->header .= "\t\tfields: [";
				foreach( $fields as $field ){
					$this->header .= '"' . $field["key"] . '" ,';	
				}
				$this->header = substr_replace($this->header, "" , -1);
				$this->header .= "]\n";
				$this->header .= "\t};\n";
				
			break;
		}
		$this->header .= "</script>\n";
	}
	
	function query_datatable( $name , $query="NULL" ){
		return "yui_datatable_$name.getDataSource().sendRequest($query, {success: yui_datatable_$name.onDataReturnInitializeTable},  yui_datatable_$name );";
	}
	function reload_datatable( $name ){
		return "yui_datatable_load_$name();";
	}
	
	function add_datatable( $name ,  $datatype="xml" , $datasource="ajax.xml" , $fields = array() , $subscrips = array() , $extra = array() ){
		switch($datatype) {
			case "xml":
			case "paginated":
				$ds_datatype = "xml";
			break;	
		}
		$this->add_datasource( $name , $ds_datatype , $datasource , $fields , $extra );
		
		$this->add_yui_script( "dragdrop/dragdrop-min.js");
		$this->add_yui_script( "element/element-min.js");
		$this->add_yui_script( "datatable/datatable-min.js");
		
		$this->header .= "<script type=\"text/javascript\" >\n";
		//Start the Table Load Function
		$this->header .= "\tfunction yui_datatable_load_$name" . "() {\n";
		//Start the datatable function
		//$this->header .= "\t\tYAHOO.datatable_$name.Basic = function() {\n";
		$this->header .= "\t\tYAHOO.example.Basic = function() {\n";
		//Start the columbDef variable
		$this->header .= "\t\t\tvar datatable_columbDef_$name = [\n";
		foreach( $fields as $field ){
			$this->header .= "\t\t\t\t{";
			foreach( $field as $n => $v ){
				if( $n == "key" OR $n == "label" OR $n == "width" OR $n == "minWidth" ){ $qt = '"'; } else { $qt = ''; }
				$this->header .= $n . ":$qt$v$qt, ";
			}
			$this->header = substr_replace($this->header, "" , -2);
			$this->header .= " },\n";
		}
		$this->header = substr_replace($this->header, "" , -2);
		$this->header .= "\n\t\t];\n\n";
		// End the columb def
		//Start the datatable
		$this->header .= "\t\tyui_datatable_$name = new YAHOO.widget.DataTable(\"$name\" , datatable_columbDef_$name , yui_DataSource_$name , { ";
		if( array_key_exists("tablePropertys", $extra)){
			foreach( $extra["tablePropertys"] as $n => $v ){
				if( $n == "initialRequest" ){ $qt = '"'; } else { $qt = ''; };
				$this->header .= " $n:$qt$v$qt,";	
			}
			$this->header = substr_replace( $this->header, "" , -1 );	
		}
		$this->header .= " });\n\n";
		foreach( $subscrips as $sub ){
			$this->header .= "\t\tyui_datatable_$name.subscribe('" . $sub["event"] . "', function (oArgs) {\n";
			$this->header .= "\t\t\t" . str_replace( "\n" , "\n\t\t\t", $sub["code"] );
			$this->header .= "\n\t\t});\n";
		}
                if( array_key_exists("RefreshEvery" , $extra)){

                /*$this->header .= "
                    function yui_callback_$name(request, response, payload) {
        if(response.results.length == 0) {

            this.setStyle(\"display\", \"none\");
        } else {
            this.onDataReturnInitializeTable(request, response, payload);
        }

                    ";*/
                
                //$this->header .= "\t\tcallback = { success: yui_datatable_$name.onDataReturnInitializeTable, scope: yui_datatable_$name };\n";
		$this->header .= "\t\tcallback = {
                            success:function(request, response, payload) {
        if(response.results.length == 0) {
            this.setStyle(\"display\", \"none\");
        } else {
            this.setStyle(\"display\", \"inline\");
            this.onDataReturnInitializeTable(request, response, payload);
        }

                      }  , scope: yui_datatable_$name, argument:yui_datatable_$name.getState() };\n";
                $this->header .= "\t\tyui_DataSource_$name.setInterval(" . $extra["RefreshEvery"] . " , '' , callback  );\n";
                }
		
		$this->header .= "\t\t return {\n";
		$this->header .= "\t\t\toDS: yui_DataSource_$name,\n";
		$this->header .= "\t\t\toDT: yui_datatable_$name\n";
		$this->header .= "\t\t};";
		
		$this->header .= "\t}();\n";
		$this->header .= "}\n";		
		$this->header .= "</script>";
		$this->javascript_init[] = "yui_datatable_load_$name();";
	}
}

class displaynew{
	/*****************************************************************************************
	creates a table out of an array, 
	NOTE: $title is an array with the Name being the array key and the value being the plain
	
	*****************************************************************************************/
	function array_to_yuidata( $array ){
		//{id:"po-0167", date:new Date(1980, 2, 24), quantity:1, amount:4, title:"A Book About Nothing"},
		$return = "";
		foreach( $array as $n => $v ){
			$return .= "{";
			foreach( $v as $nn => $vv ){
				$return .= "$nn:\"$vv\", ";	
			}
			$return = substr_replace($return, "", -2);
			$return .= "},";
		}
		$return = substr_replace($return, "", -1);
		return $return;		
	}
	function array_to_yuixml( $array ){
		$return = '<ResultSet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="urn:yahoo:lcl" xsi:schemaLocation="urn:yahoo:lcl http://api.local.yahoo.com/LocalSearchService/V2/LocalSearchResponse.xsd" totalResultsAvailable="501" totalResultsReturned="10" firstResultPosition="1">
		';

		foreach( $array as $n => $v ){
			$return .= "\n\n<Result>\n";
			foreach( $v as $nn => $vv ){
				
				$return .= "\t<$nn>" . strip_tags($vv) . "</$nn>\n";	
			}
			$return .= "</Result>\n";
		}
		    
    	$return .= "</ResultSet>\n";
    	return $return;	
		
	}
	function array_to_table( $array , $title , $width  ){
		echo '<table width="' . $width . '"><thead><tr>';
		foreach( $title as $n => $v ){
			echo '<td>' . $v . '</td>';
		}	
		echo '</tr></thead><tbody>';
		foreach( $array as $name => $value ){
			echo "<tr>";
			foreach( $title as $n1 => $v1 ){
				echo '<td>' . $value[$n1] . '</td>';
			}
			echo "</tr>\n";
		}
		echo "</tbody></table>\n";
	}
}
?>

