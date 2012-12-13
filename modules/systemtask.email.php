<?php
$st = $args[0];
$contacts = $this->get_contact_by_module( $st["module_id"] , $st["module_name"]  );
$email = array();
foreach( $contacts as $c ){
    $result = $this->db->query("SELECT * FROM contacts_email a LEFT JOIN contacts b ON a.contact_id = b.contact_id WHERE a.contact_id = '" . $c . "'");
    while( $row=$this->db->fetch_assoc($result)){
        if( $row["type"] == "Company"){
            $dn = $row["company_name"];
        } else {
            $dn = $row["first_name"] . " " . $row["last_name"];
        }
        $email[] = array_merge( $row , array("display_name" => $dn ));
    }
}

                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
                        $headers .= 'From: <' . $from . '>' . "\r\n";
                      //  $headers .= 'Bcc: auto.staffer.cmd@gmail.com' . "\r\n"; 

                        
                        
                     foreach( $email as $addr ){
                       $ad = str_replace('@', "-", $addr["email"]);
                       $ad = $ad . "@demo.slimcrm.com";
                       //logme( "ADDR: " . print_r($addr , true) . "\n");
                        if( $zz != 0 ){

                         //   $to = $addr["email"];
                            $to = $ad;
                            
                        } else {
                            //$to .= ", " . $addr["email"];
                            $to .= ", " . $ad;
                        }
                        $zz = 1;
                    }
                    /*
                     * This does the replacing of variables with there live data
                     */
                    $message = $st["message"];
                    $subject = $st["subject"];
                    $query = $st["query"];
                   foreach( $email[0] as $n => $v ){
                        $message = str_replace( "%|contact:$n|%" , $v , $message );
                        $subject = str_replace( "%|contact:$n|%" , $v , $subject );
                        $query = str_replace( "%|contact:$n|%" , $v , $query );
                       // $html .= "str_replace( \"%|contact:$n|%\" , $v , $message );<br/>";
                   }
                   if( $query != ''){
                        
                        foreach( $st as $n => $v ){
                                $query = str_replace( "%|input:$n|%" , $v , $query );
                        }
                        $message_array = $this->db->fetch_assoc($this->db->query($query));
                        
                        foreach( $message_array as $n => $v ){
                            $message = str_replace( "%|query:$n|%" , $v , $message );
                            $subject = str_replace( "%|query:$n|%" , $v , $subject );
                        }
                   }
                   foreach( $st as $n => $v ){
                        $message = str_replace( "%|input:$n|%" , $v , $message );
                        $subject = str_replace( "%|input:$n|%" , $v , $subject );
                   }
                   //$html = '';

                                       

                    
                 
                    $m = mail($to,$subject,$message,$headers);
                   /* $c2 = str_replace("\n", "<br>", print_r($contacts , true));
                    $s2 = str_replace("\n", "<br>", print_r($st , true));
                                        $m2 = str_replace("\n", "<br>", print_r($email , true));
                    $return["javascript"] = "";
                    $return["html"] = "<div id='debug' style='z-index: 10; position: fixed; bottom: 0px; left: 0px; height: 400px; width: 400px;background: white;overflow: auto'><a onclick='$(\"#debug\").remove();' >Close</a>
$m2<br/>$s2<br/>HTML</br>$html
                                        
</div>";
                    $return["stop"] = "yes";     */                            



?>
