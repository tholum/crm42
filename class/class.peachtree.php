<?php

class peachtree {
    var $db;
    var $cid;
    var $opp;
    function __construct(){
        
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->cid = 'SLIMCRM_ORDER';
        $this->opp = "";
    }
    
    function write_ref( $t ){
        switch( $t["payment_module"]){
            case "creditcard":
                switch( $t["card_type"]){
                    case "American Express":
                        $dtid = "ap";
                        $ref = "pcc";
                    break;
                    default:
                        $dtid = "c_";
                        $ref = "pcc";
                    break;    
                }
            
            
            break;
            case "check":
                $dtid = "d_";
                $ref = "ck";
            break; 
            case "wiretransfer":
                $dtid = "wr";
                $ref = "wr";
            break;
            
            
        }
        return array("dtid" => $dtid, "ref" => 'R');
    }
    
    
    function array2csv($array , $type="csv"){
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

    function getNewTransactions(){
        $result = $this->db->query(
                "SELECT a.* , b.type card_type FROM `payments` a 
                    LEFT JOIN credit_cards b ON a.payment_module_id = b.ccid AND a.payment_module = 'creditcard' 
                    LEFT JOIN contacts c ON a.payee_module_id = c.contact_id AND a.payee_module_name = 'contacts' 
                    LEFT JOIN erp_contactscreen_custom d ON c.contact_id = d.contact_id 
                    WHERE a.synced = 0 AND refund = 'no' GROUP BY payment_id ");
        $tr = array(); 
        while( $row=$this->db->fetch_assoc($result)){
            
            if( $row["total_tax"] != 0 ){
                $row["tax_code"] = "W";
            } else {
                $row["tax_code"] = "";
            }
            
            $tr[] = $row;
        }
        return $tr;
    }
    function getRefundTransactions(){
        $result = $this->db->query(
                "SELECT a.* , b.type card_type FROM `payments` a 
                    LEFT JOIN credit_cards b ON a.payment_module_id = b.ccid AND a.payment_module = 'creditcard' 
                    LEFT JOIN contacts c ON a.payee_module_id = c.contact_id AND a.payee_module_name = 'contacts' 
                    LEFT JOIN erp_contactscreen_custom d ON c.contact_id = d.contact_id 
                    WHERE refund = 'yes' GROUP BY payment_id");
        $tr = array(); 
        while( $row=$this->db->fetch_assoc($result)){
            
            if( $row["total_tax"] != 0 ){
                $row["tax_code"] = "W";
            } else {
                $row["tax_code"] = "";
            }
            
            $tr[] = $row;
        }
        return $tr;
    }
    
    
    function get_downPaymentArr( $t ){
           $pdate =date( "mdy", strtotime($t["timestamp"]) );
            $dtrf = $this->write_ref( $t );
            $dtid = $dtrf["dtid"];
            $ref = $dtrf["ref"];/*
            switch( $t["card_type"]){
                case "American express":
                    $dtid = "a_";
                    $ref = "pcc";
                break;
                default:
                    $dtid = "c_";
                    $ref = "pcc";
                break;    
            }*/
            $this->db->update('payments', array("synced" => '1', 'synced_timestamp' => date("Y-m-d H:i:s")  ), 'payment_id', $t["payment_id"]);
            $taxtype = '1'; // May be needed to be chaned accourding to tax exempt
            $pa = array();
            $pa["Deposit Ticket ID"] = "$dtid$pdate";
            $pa["Customer ID"] = $this->cid;
            $pa["Reference"] = $this->opp . "$ref $pdate pid " . $t["payment_id"];
            $pa["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
            $pa["Payment Method"] = "Receipt";
            $pa["Cash Account"] = "10300";
            $pa["Cash Amount"] = $t["amount"]; 
            $pa["Total Paid on Invoice(s)"] = "0";
            $pa["Prepayment"] = "TRUE";
            $pa["Number of Distributions"] = "1";
            $pa["Invoice Paid"] = "";
            $pa["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " down";
            $pa["G/L Account"] = "24400";
            $pa["Tax Type"] = "$taxtype";
            $pa["Amount"] = "-" .$t["amount"]; 
            return $pa;
        
        
    }
    

      
    
    function get_applyPaymentArr( $t , $pi ){
           $pdate =date( "mdy", strtotime($pi["timestamp"]) );
                      $pdate2 =date( "mdy", strtotime($t["timestamp"]) );
            $dtid = '';
            $ref = '';
            $dtrf2 = $this->write_ref( $t );
            $dtid2 = $dtrf2["dtid"];
            $ref2 = $dtrf2["ref"];
            $dtrf = $this->write_ref( $pi );
            $dtid = $dtrf["dtid"];
            $ref = $dtrf["ref"];
            /*
            switch( $pi["card_type"]){
                case "American express":
                    $dtid = "a_";
                    $ref = "pcc";
                break;
                default:
                    $dtid = "c_";
                    $ref = "pcc";
                break;    
            }
            switch( $t["card_type"]){
                case "American express":
                    $dtid2 = "a_";
                    $ref2 = "pcc";
                break;
                default:
                    $dtid2 = "c_";
                    $ref2 = "pcc";
                break;    
            }
             * 
             */
            $taxtype = '0'; // May be needed to be chaned accourding to tax exempt
            $this->db->update('payments', array("synced" => '1', 'synced_timestamp' => date("Y-m-d H:i:s")  ), 'payment_id', $t["payment_id"] );
            $pa = array();
            $pa["Deposit Ticket ID"] = "$dtid$pdate";
            $pa["Customer ID"] = $this->cid;
            $pa["Reference"] = $this->opp . "F:$ref:$pdate:" . $pi["payment_id"];
            $pa["Date"] = date( "n/j/y", strtotime($pi["timestamp"]) );
            $pa["Payment Method"] = "Receipt";
            $pa["Cash Account"] = "10300";
            $pa["Cash Amount"] = $pi["amount"]; 
            $pa["Total Paid on Invoice(s)"] = "-" . $pi["amount"];
            $pa["Prepayment"] = "FALSE";
            $pa["Number of Distributions"] = $this->get_dist_num( $t["for_module_name"] , $t["for_module_id"] );
            $pa["Invoice Paid"] = $this->opp . "$ref2 $pdate2 pid " . $t["payment_id"];
            $pa["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final" ;
            $pa["G/L Account"] = "24400";
            $pa["Tax Type"] = "$taxtype";
            $pa["Amount"] = $t["amount"];
            return $pa;
        
        
    }
   
    
      function get_finalPaymentArr( $t ){
           $pdate =date( "mdy", strtotime($t["timestamp"]) );
            $dtid = '';
            $ref = '';
            /*
            switch( $t["card_type"]){
                case "American express":
                    $dtid = "a_";
                    $ref = "pcc";
                break;
                default:
                    $dtid = "c_";
                    $ref = "pcc";
                break;    
            }
             * 
             */
            $dtrf = $this->write_ref( $t );
            $dtid = $dtrf["dtid"];
            $ref = $dtrf["ref"];

                      $cash_ammount =  $t["amount"];
                    $amount = "-" .$t["amount"];  

            $taxtype = '0'; // May be needed to be chaned accourding to tax exempt
            $this->db->update('payments', array("synced" => '1', 'synced_timestamp' => date("Y-m-d H:i:s") ), 'payment_id', $t["payment_id"]);
            $pa = array();
            $pa["Deposit Ticket ID"] = "$dtid$pdate";
            $pa["Customer ID"] = $this->cid;
            $pa["Reference"] = $this->opp . "F:$ref:$pdate:" . $t["payment_id"];//"$ref $pdate";
            $pa["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
            $pa["Payment Method"] = "Receipt";
            $pa["Cash Account"] = "10300";
            $pa["Cash Amount"] = $t["amount"]; 
            $pa["Total Paid on Invoice(s)"] = "-" . $t["amount"];
            $pa["Prepayment"] = "FALSE";
            $pa["Number of Distributions"] = $this->get_dist_num( $t["for_module_name"] , $t["for_module_id"] );
            $pa["Invoice Paid"] = $this->opp . "SLIM" . strtoupper($t["for_module_name"]) . $t["for_module_id"];
            $pa["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final";
            $pa["G/L Account"] = "11000";
            $pa["Tax Type"] = "$taxtype";
            $pa["Amount"] = "-" . $this->get_total($t["for_module_name"], $t["for_module_id"]);
            return $pa;
        
        
    } 
    function check_for_final( $module_id , $module_name ){
        $num = $this->db->num_rows($this->db->query("SELECT * FROM payments WHERE for_module_id = '$module_id' AND for_module_name = '$module_name' AND payment_type = 'final' GROUP BY payment_id"));
            if( $num == 0){
                return false;
            } else {
                return true;
            }
        }
    
      function get_refund_downPaymentArr( $t ){
           $pdate =date( "mdy", strtotime($t["timestamp"]) );
           /*
            $dtid = '';
            $ref = '';
            switch( $t["card_type"]){
                case "American express":
                    $dtid = "a_";
                    $ref = "pcc";
                break;
                default:
                    $dtid = "c_";
                    $ref = "pcc";
                break;    
            }*/
            $dtrf = $this->write_ref( $t );
            $dtid = $dtrf["dtid"];
            $ref = $dtrf["ref"];
            $this->db->update('payments', array("refund" => 'done', 'refund_timestamp' => date("Y-m-d H:i:s")  ), 'payment_id', $t["payment_id"]);
            $taxtype = '0'; // May be needed to be chaned accourding to tax exempt

            $pa = array();
            $pa["Deposit Ticket ID"] = "$dtid$pdate";
            $pa["Customer ID"] = $this->cid;
            $pa["Reference"] = $this->opp . ":REF:$ref:$pdate:" . $t["payment_id"];
            $pa["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
            $pa["Payment Method"] = "Receipt";
            $pa["Cash Account"] = "10300";
            $pa["Cash Amount"] = "-" . $t["amount"]; 
            $pa["Total Paid on Invoice(s)"] = $t["amount"];
            $pa["Prepayment"] = "FALSE";
            $pa["Number of Distributions"] = "1";
            $pa["Invoice Paid"] = $this->opp . "$ref $pdate pid " . $t["payment_id"];
            $pa["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " down";
            $pa["G/L Account"] = "24400";
            $pa["Tax Type"] = "$taxtype";
            $pa["Amount"] = $t["amount"]; 
            //if( $this->check_for_final( $t["for_module_id"] , $t["payment_id"] ) ){
                return $pa;
           // }
        
        
    }
    
    function get_refund_finalPaymentArr( $t ){
           $pdate =date( "mdy", strtotime($t["timestamp"]) );
           /*
            $dtid = '';
            $ref = '';
            switch( $t["card_type"]){
                case "American express":
                    $dtid = "a_";
                    $ref = "pcc";
                break;
                default:
                    $dtid = "c_";
                    $ref = "pcc";
                break;    
            }
            * 
            */
            $dtrf = $this->write_ref( $t );
            $dtid = $dtrf["dtid"];
            $ref = $dtrf["ref"];
                      $cash_ammount =  $t["amount"];
                    $amount = "-" .$t["amount"];  

            $taxtype = '1'; // May be needed to be chaned accourding to tax exempt
            $this->db->update('payments', array("refund" => 'done', 'refund_timestamp' => date("Y-m-d H:i:s") ), 'payment_id', $t["payment_id"]);
            $pa = array();
            $pa["Deposit Ticket ID"] = "$dtid$pdate";
            $pa["Customer ID"] = $this->cid;
            $pa["Reference"] = $this->opp . "REF:$ref:$pdate:" . $t["payment_id"];//"$ref $pdate";
            $pa["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
            $pa["Payment Method"] = "Receipt";
            $pa["Cash Account"] = "10300";
            $pa["Cash Amount"] = "-" . $this->get_total($t["for_module_name"], $t["for_module_id"] , true); 
            $pa["Total Paid on Invoice(s)"] =  $this->get_total($t["for_module_name"], $t["for_module_id"] , true);
            $pa["Prepayment"] = "FALSE";
            $pa["Number of Distributions"] = '1';
            $pa["Invoice Paid"] = $this->opp . "SLIM" . strtoupper($t["for_module_name"]) . $t["for_module_id"];
            $pa["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final";
            $pa["G/L Account"] = "11000";
            $pa["Tax Type"] = "$taxtype";
            $pa["Amount"] =  $this->get_total($t["for_module_name"], $t["for_module_id"] , true); 
            return $pa;
        
        
    } 
    
    function get_SubPayments( $for_module_name , $for_module_id ){
        $sql = "SELECT a.* , b.type card_type FROM `payments` a 
                    LEFT JOIN credit_cards b ON a.payment_module_id = b.ccid AND a.payment_module = 'creditcard' 
                    LEFT JOIN contacts c ON a.payee_module_id = c.contact_id AND a.payee_module_name = 'contacts' 
                    LEFT JOIN erp_contactscreen_custom d ON c.contact_id = d.contact_id 
                    WHERE a.for_module_name = '$for_module_name' AND a.for_module_id = '$for_module_id' AND a.payment_type <> 'final'  AND refund='no' GROUP BY payment_id";
        //echo $sql;
                $result = $this->db->query( $sql           );
        $tr = array(); 
        while( $row=$this->db->fetch_assoc($result)){
            if( count( $this->db->query("SELECT * FROM contacts_address WHERE contact_id = '" . $row["contact_id"] . "' AND state = 'WI'") ) != 0 ){
                $row["tax_code"] = "W";
            } else {
                $row["tax_code"] = "";
            }
            
            $tr[] = $row;
        }
        return $tr;
    }
    
    function get_total( $for_module_name , $for_module_id , $forref = false ){
        if( $forref){
            $sf = $this->db->fetch_assoc($this->db->query("SELECT SUM(amount) total FROM payments WHERE for_module_id = '$for_module_id' AND for_module_name = '$for_module_name' AND ( refund = 'yes' OR refund = 'done' ) "));           
        } else {
            $sf = $this->db->fetch_assoc($this->db->query("SELECT SUM(amount) total FROM payments WHERE for_module_id = '$for_module_id' AND for_module_name = '$for_module_name' AND refund='no'"));
        }
        return $sf["total"];
        }
    
    function get_dist_num( $for_module_name , $for_module_id ){
        $dn = $this->db->fetch_assoc($this->db->query("SELECT count(amount) total FROM payments WHERE for_module_id = '$for_module_id' AND for_module_name = '$for_module_name' AND refund = 'no'"));
        return $dn["total"];
        
    }
    /*
    function get_salesArr( $t ){
        $sj = array();
        $sj["Customer ID"] = $this->cid;
        $sj["Invoice/CM #"] = $this->opp . "SLIM" . strtoupper($t["for_module_name"]) . $t["for_module_id"];
        $sj["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
        $sj["Date Due"] = date( "n/j/y", strtotime( "+1 month" , strtotime($t["timestamp"])) );
        $sj["Discount Amount"] = "0";
        $sj["Discount Date"] = date( "n/j/y", strtotime($t["timestamp"]) );;
        $sj["Displayed Terms"] = "Net 30 Days";
        $sj["Accounts Receivable Account"] = "11000";
        $sj["Accounts Receivable Amount"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        $sj["Number of Distributions"] = "1";
        $sj["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final";
        $sj["G/L Account"] = "40300";
        $sj["Unit Price"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        $sj["Tax Type"] = "1";
        $sj["Amount"] = "-" . $this->get_total($t["for_module_name"], $t["for_module_id"]);

        $sj["Invoice/CM Distribution"] = "1";
        $sj["Apply to Invoice Distribution"] = "0";
                $sj["Credit Memo"] = 'FALSE';
        $sj["U/M No. of Stocking Units"] = "1";
        $sj["Stocking Quantity"] = "1";

        return $sj;

    }*/

    function get_invoice_distribs( $t ){
        $dist = 1;
        if( $t["shipping_amt"] > 0 ){
            $dist++;
        }
        if( $t["total_tax"] > 0 ){
            $dist++;
        }
        return $dist;
    }
    
    function get_salesArr( $t ){
        $sj = array();
        $sj["Customer ID"] = $this->cid;
        $sj["Invoice/CM #"] = $this->opp . "SLIM" . strtoupper($t["for_module_name"]) . $t["for_module_id"];
        $sj["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
        $sj["Date Due"] = date( "n/j/y", strtotime( "+1 month" , strtotime($t["timestamp"])) );
        $sj["Discount Amount"] = "0";
        $sj["Discount Date"] = date( "n/j/y", strtotime($t["timestamp"]) );;
        $sj["Displayed Terms"] = "Net 30 Days";
        $sj["Accounts Receivable Account"] = "11000";
        $sj["Accounts Receivable Amount"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        /*if( $t["total_tax"] == 0){
            $sj["Number of Distributions"] = "1";
        }   else {
            $sj["Number of Distributions"] = "2";
        }*/
        $sj["Number of Distributions"] = $this->get_invoice_distribs($t);
        $sj["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final";
        $sj["G/L Account"] = "40300";
        $sj["Unit Price"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        if( $t["total_tax"] == 0){
            $sj["Tax Type"] = "1";
        }   else {
            $sj["Tax Type"] = "2";
        }
        $sj["Amount"] = "-" . ($this->get_total($t["for_module_name"], $t["for_module_id"]) - $t["total_tax"] - $t["shipping_amt"]);

        $sj["Invoice/CM Distribution"] = "1";
        $sj["Apply to Invoice Distribution"] = "0";
                $sj["Credit Memo"] = 'FALSE';
        $sj["U/M No. of Stocking Units"] = "1";
        $sj["Stocking Quantity"] = "1";

        return $sj;

    }
    
    function get_sales_shipping_Arr( $t ){
        $sj = array();
        $sj["Customer ID"] = $this->cid;
        $sj["Invoice/CM #"] = $this->opp . "SLIM" . strtoupper($t["for_module_name"]) . $t["for_module_id"];
        $sj["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
        $sj["Date Due"] = date( "n/j/y", strtotime( "+1 month" , strtotime($t["timestamp"])) );
        $sj["Discount Amount"] = "0";
        $sj["Discount Date"] = date( "n/j/y", strtotime($t["timestamp"]) );;
        $sj["Displayed Terms"] = "Net 30 Days";
        $sj["Accounts Receivable Account"] = "11000";
        $sj["Accounts Receivable Amount"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        /*if( $t["total_tax"] == 0){
            $sj["Number of Distributions"] = "1";
        }   else {
            $sj["Number of Distributions"] = "2";
        }*/
        $sj["Number of Distributions"] = $this->get_invoice_distribs($t);
        $sj["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final";
        $sj["G/L Account"] = "45500";
        $sj["Unit Price"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        if( $t["total_tax"] == 0){
            $sj["Tax Type"] = "1";
        }   else {
            $sj["Tax Type"] = "2";
        }
        $sj["Amount"] = "-" . $t["shipping_amt"];

        $sj["Invoice/CM Distribution"] = "0";
        $sj["Apply to Invoice Distribution"] = "0";
                $sj["Credit Memo"] = 'FALSE';
        $sj["U/M No. of Stocking Units"] = "1";
        $sj["Stocking Quantity"] = "1";

        return $sj;

    } 
      function get_sales_tax_Arr( $t ){
        $sj = array();
        $sj["Customer ID"] = $this->cid;
        $sj["Invoice/CM #"] = $this->opp . "SLIM" . strtoupper($t["for_module_name"]) . $t["for_module_id"];
        $sj["Date"] = date( "n/j/y", strtotime($t["timestamp"]) );
        $sj["Date Due"] = date( "n/j/y", strtotime( "+1 month" , strtotime($t["timestamp"])) );
        $sj["Discount Amount"] = "0";
        $sj["Discount Date"] = date( "n/j/y", strtotime($t["timestamp"]) );;
        $sj["Displayed Terms"] = "Net 30 Days";
        $sj["Accounts Receivable Account"] = "11000";
        $sj["Accounts Receivable Amount"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        /*if( $t["total_tax"] == 0){
            $sj["Number of Distributions"] = "1";
        }   else {
            $sj["Number of Distributions"] = "2";
        }*/
        $sj["Number of Distributions"] = $this->get_invoice_distribs($t);
        $sj["Description"] = "SLIM: " . $t["for_module_name"] . " " . $t["for_module_id"] . " payment " . $t["payment_id"] . " final";
        $sj["G/L Account"] = "23100";
        $sj["Unit Price"] = $this->get_total($t["for_module_name"], $t["for_module_id"]);
        if( $t["total_tax"] == 0){
            $sj["Tax Type"] = "1";
        }   else {
            $sj["Tax Type"] = "2";
        }
        $sj["Amount"] = "-" . $t["total_tax"];

        $sj["Invoice/CM Distribution"] = "0";
        $sj["Apply to Invoice Distribution"] = "0";
                $sj["Credit Memo"] = 'FALSE';
        $sj["U/M No. of Stocking Units"] = "1";
        $sj["Stocking Quantity"] = "1";

        return $sj;

    }  
    function transArr2SalesArr( $tr , $data ){
        $return = array();
        foreach( $tr as $t ){
            if( $t["payment_type"] == "final" ){
                if( $t["total_tax"] != 0 ){
                    $return[] = $this->get_sales_tax_Arr( $t );
                }
                if( $t["shipping_amt"] != 0 ){
                    $return[] = $this->get_sales_shipping_Arr($t);
                }
                $return[] = $this->get_salesArr( $t );
            }
        }
        return $return;
        
    }
    function transArr2PeachArr( $tr , $data ){
        $return = array();
        foreach( $tr as $t ){
            switch( $t["payment_type"]){
                case "down":
                   if( $data == "prepay"){ 
                        $return[] = $this->get_downPaymentArr($t);
                   }
                break;
                case "final":
                    if( $data == "final" ){
                        $subpayments = $this->get_SubPayments( $t["for_module_name"] , $t["for_module_id"] );
                       // print_r( $subpayments );
                         
                        foreach( $subpayments as $payment ){
                            $ap = $this->get_applyPaymentArr($payment , $t);
                            $return[] = $ap;
                        }
                        $return[] = $this->get_finalPaymentArr( $t );
                    }
                break;    
            }
            
            //$return[] = $pa;
        }
        return $return;
    }
    function transRefundArr2PeachArr( $tr , $data ){
        $return = array();
        foreach( $tr as $t ){
            switch( $t["payment_type"]){
                case "down":
                   if( $data == "prepay"){ 
                        //$return[] = $this->get_downPaymentArr($t);
                       $tmp = $this->get_refund_downPaymentArr($t);
                       if( $tmp != false){
                            $return[] = $tmp;
                       }
                   }
                break;
                case "final":
                    if( $data == "prepay"){
                        $return[] = $this->get_refund_finalPaymentArr($t);
                        //$return[] = $this->get_finalPaymentArr( $t );
                    }
                break;    
            }
            
            //$return[] = $pa;
        }
        return $return;
    }

}

?>
