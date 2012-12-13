

<?php
require_once("class/class.user.php");
require_once("class/class.contacts.php");
class Asterisk {
    var $db;
    var $asterisk_db;
    var $user;
    var $contacts;
    function  __construct( $user='NONE') {
        if($user == 'NONE'){
            $this->user = new User;
        } else {
            
            $this->user = $user;
        }
        $this->contacts = new Contacts;
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->asterisk_db = new database(ASTERISK_DATABASE_HOST,ASTERISK_DATABASE_PORT,ASTERISK_DATABASE_USER,ASTERISK_DATABASE_PASSWORD,ASTERISK_DATRABASE_NAME);

    }
    //$phone can eather be a single number or a nested array like array( array("number" => "23449009" ) , array( "number" , "232423423" ) )
    function get_calls_rec( $phone , $num_results ){
        $data = new database(ASTERISK_DATABASE_HOST,ASTERISK_DATABASE_PORT,ASTERISK_DATABASE_USER,ASTERISK_DATABASE_PASSWORD,ASTERISK_DATRABASE_NAME);
                        $WHERE = "src LIKE '%$phone' OR dst LIKE '%$phone' AND accountcode = '" . ASTERISK_ACCOUNT_CODE . "'";
			if( is_array($phone) ){
                            $WHERE = ' (';
                            foreach( $phone as $pn ){
                                $WHERE .= "src LIKE '%" . $pn["number"] . "' OR dst LIKE '%" . $pn["number"] . "' OR ";
                            }
                            $WHERE = substr( $WHERE , 0 , -3);
                            if( $WHERE != '' ){ $WHERE .= ") AND "; } else { $WHERE .= " 0 = 1 AND ";}
                            $WHERE .= "accountcode = '" . ASTERISK_ACCOUNT_CODE . "'";
                        }

                        $sql = "select src , dst , channel , dstchannel , calldate  , filename from asterisk.cdr a LEFT JOIN asterisk.monitor b ON a.uniqueid = b.filename WHERE $WHERE ORDER by calldate DESC LIMIT $num_results";
			//echo $sql;

                        $result = $data->query( $sql , __LINE__ , __FILE__  );
			$x = 0;
			while( $row = mysql_fetch_assoc($result) ){
                                if( is_array($phone) ){
                                    $position = "";
				//echo $row["src"] . " = " . $phone . " | " . strpos( $row["src"] , $phone ) . "\n";
                                    foreach( $phone as $pn ){
                                        if( strpos( $row["dst"] , $pn["number"] ) OR $row["dst"] == $pn["number"] ){ $position = "OUT"; }
                                        if( strpos( $row["src"] , $pn["number"] ) OR $row["src"] == $pn["number"] ){ $position = "IN"; }
                                    }
                                if( $position == "IN" ){
					$z = $row["dstchannel"];
					$zx = explode("/" , $z );
					$zxx = explode( "-" , $zx[1] );
					$exten = $zxx[0];
				 }
				if( $position == "OUT" ){
					$exten = $row["src"];
				}

				$return[$x] = array();
				//$return[$x] = $row;
				$return[$x]["direction"] = $position ;
				$return[$x]["phone"] = $pn["number"];
				$return[$x]["exten"] = $exten;
				$return[$x]["datetime"] = $row["calldate"] ;
                                $return[$x]["filename"] = $row["filename"];


				$x++;

                                } else {
				$position = "";
				//echo $row["src"] . " = " . $phone . " | " . strpos( $row["src"] , $phone ) . "\n";
				if( strpos( $row["dst"] , $phone ) OR $row["dst"] == $phone ){ $position = "OUT"; }
				if( strpos( $row["src"] , $phone ) OR $row["src"] == $phone ){ $position = "IN"; }
				if( $position == "IN" ){
					$z = $row["dstchannel"];
					$zx = explode("/" , $z );
					$zxx = explode( "-" , $zx[1] );
					$exten = $zxx[0];
				 }
				if( $position == "OUT" ){
					$exten = $row["src"];
				}

				$return[$x] = array();
				//$return[$x] = $row;
				$return[$x]["direction"] = $position ;
				$return[$x]["phone"] = $phone;
				$return[$x]["exten"] = $exten;
				$return[$x]["datetime"] = $row["calldate"] ;
                                $return[$x]["filename"] = $row["filename"];


				$x++;
                                }
			}

			return $return;

    }
    function display_call_rec( $phone , $num ){
        $arr = $this->get_calls_rec($phone, $num);
        $return = '';
        foreach( $arr as $rec){
            $cinfo = $this->contacts->contact_search($rec["phone"], array("phone"));
            //echo print_r( $cinfo ) . "\n<br/>\n<br/>";
            if(count($cinfo) != 0){
                echo '<a href="contact_profile.php?contact_id=' . $cinfo[0]["contact_id"] . '" >' . $cinfo[0]["name"] . "</a>" . " at " .  $rec["datetime"] . "<br/>";
                if( $rec["filename"] != '') {
                ?> 
<object type="application/x-shockwave-flash" data="player_mp3_maxi.swf" width="200" height="20">

    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=message<?php echo $rec["filename"];?>.mp3" />
</object><br/>

                <?php }
            }

        }

    }
    function asterisk_get_info( $phone , $num_results ){
			$data = new database(ASTERISK_DATABASE_HOST,ASTERISK_DATABASE_PORT,ASTERISK_DATABASE_USER,ASTERISK_DATABASE_PASSWORD,ASTERISK_DATRABASE_NAME);
			$sql = "select src , dst , channel , dstchannel , calldate  from asterisk.cdr WHERE ( src LIKE '%$phone' OR dst LIKE '%$phone' ) AND `accountcode` = '" . ASTERISK_ACCOUNT_CODE . "' ORDER by calldate DESC LIMIT $num_results";
			$result = $data->query( $sql , __LINE__ , __FILE__ );
			$x = 0;
			while( $row = mysql_fetch_assoc($result) ){
				$position = "";
				//echo $row["src"] . " = " . $phone . " | " . strpos( $row["src"] , $phone ) . "\n";
				if( strpos( $row["dst"] , $phone ) OR $row["dst"] == $phone ){ $position = "OUT"; }
				if( strpos( $row["src"] , $phone ) OR $row["src"] == $phone ){ $position = "IN"; }		
				if( $position == "IN" ){ 
					$z = $row["dstchannel"];
					$zx = explode("/" , $z );
					$zxx = explode( "-" , $zx[1] );
					$exten = $zxx[0];
				 }
				if( $position == "OUT" ){
					$exten = $row["src"];
				}
				
				$return[$x] = array();
				//$return[$x] = $row;
				$return[$x]["direction"] = $position ;
				$return[$x]["phone"] = $phone;
				$return[$x]["exten"] = $exten;
				$return[$x]["datetime"] = $row["calldate"] ;
			
				$x++;
			}
			
			return $return;
		}

	 function get_formatted_phone_log($phone , $num_results) {
 		
		$log=$this->asterisk_get_info( $phone , $num_results );?>
		<table  class="call_log">
		<?php foreach($log as $call=>$value){
		$t=explode(' ',$value['datetime']);
		$tt=explode('-',$t[0]);
		$time= mktime(0,0,0,$tt[1],$tt[0],$tt[2]);
		?>
		<tr><td><?php echo date('D',$time);?></td><td><?php echo ' - '; ?></td><td><?php echo $value['datetime'].' '.$value['direction']; ?></td></tr>
		<?php } ?>
		</table>
		<?php	
 
	 }
         function get_web_ext( $user_id ){
             $res = $this->db->query("SELECT module_id FROM " . USER_ASTERISK . " WHERE module = 'WEB_SIP' and user_id = '$user_id'" , __LINE__ , __FILE__ );
             $WHERE = '';
             while($row=$this->db->fetch_assoc($res)){
                 $WHERE .= " id = '" . $row["module_id"] . "' OR ";
             }
             $WHERE = substr($WHERE, 0 , -3);
             $return = array();
             if( $WHERE != ''){
                $res2 = $this->asterisk_db->query("SELECT secret , name , callerid FROM sip WHERE $WHERE" , __LINE__ , __FILE__ );
                while($row2=$this->asterisk_db->fetch_assoc($res2)){
                    $return[] = array( "exten" => substr( $row2["name"] , 3 ) , "callerid" => $row2["callerid"] , "password" => $row2["secret"] , "username" => $row2["name"]);

                }
             }
             return $return;
         }

         function get_extention( $user_id){
             $res = $this->db->query("SELECT module_id FROM " . USER_ASTERISK . " WHERE module = 'SIP' and user_id = '$user_id'" , __LINE__ , __FILE__ );
             $WHERE = '';
             while($row=$this->db->fetch_assoc($res)){
                 $WHERE .= " id = '" . $row["module_id"] . "' OR ";
             }
             $WHERE = substr($WHERE, 0 , -3);
             $return = array();
             if( $WHERE != ''){
                $res2 = $this->asterisk_db->query("SELECT name , callerid FROM sip WHERE $WHERE" , __LINE__ , __FILE__ );
                while($row2=$this->asterisk_db->fetch_assoc($res2)){
                    $return[] = array( "exten" => substr( $row2["name"] , 3 ) , "callerid" => $row2["callerid"] );
                }
             }
             return $return;
         }
         function edit_settings( $user_id){
             $exten = $this->get_extention($user_id);
             if(count( $exten ) != 0 ){
                $cidXP = explode("<" , $exten[0]["callerid"] );
                $cidtext = $cidXP[0];
                $cidnum = str_replace(">", "", $cidXP[1]);

             }

             ?>
            <tr>
                <td colspan="2" class="profile_head">Phone Settings</td>
            </tr>
            <tr>
                <th>Extention:</th><td><?php if(count( $exten ) != 0 ){ echo $exten[0]["exten"]; }?> ( FIXED ) </td>
            </tr>
            <tr>
                <th>CID:</th><td><?php if(count( $exten ) != 0 ){ echo $exten[0]["callerid"]; }?> ( FIXED ) </td>
            </tr>
             <?php

         }
         function display_settings( $user_id ){
             $exten = $this->get_extention($user_id);
             if(count( $exten ) != 0 ){
                $cidXP = explode("<" , $exten[0]["callerid"] );
                $cidtext = $cidXP[0];
                $cidnum = str_replace(">", "", $cidXP[1]);

             }
             ?>
                  <tr>
                      <td colspan="2" class="profile_head">Phone Settings</td>
                  </tr><tr>
                      <th>Extention</th><td> <?php if(count( $exten ) != 0 ){ echo $exten[0]["exten"]; }?></td>
                   </tr><tr>
                      <th>CID</th><td><?php if(count( $exten ) != 0 ){echo $exten[0]["callerid"]; } ?></td>
                   </tr>
             <?
         }
         function get_voicemail_message( $id ){
             $result = $this->asterisk_db->query("SELECT * FROM voicemessages WHERE id = '$id'" , __LINE__ , __FILE__  );
             return $this->asterisk_db->fetch_assoc($result);
         }
         function get_recording( $id ){
             $result = $this->asterisk_db->query("SELECT * FROM monitor WHERE filename = '$id'" , __LINE__ , __FILE__ );
             return $this->asterisk_db->fetch_assoc($result);
         }


         function list_mailbox_voicemail( $mailbox , $context , $dir='%'){
             $result = $this->asterisk_db->query("SELECT id , msgnum , duration , origtime , mailboxuser , mailboxcontext FROM voicemessages WHERE dir LIKE '%$dir' AND mailboxuser LIKE '$mailbox' AND mailboxcontext LIKE '$context'" , __LINE__ , __FILE__ );
             $return = array();
             while($row=$this->asterisk_db->fetch_assoc($result)){
                 $return[] = $row;
             }
             return $return;
         }

         function list_mailbox_voicemail_by_user( $user_id , $qty="ALL" , $start = '0' ,  $ORDERBY="b.origtime" , $AscORDesc="DESC"){
             $res = $this->db->query("SELECT module_id FROM " . USER_ASTERISK . " WHERE module = 'VOICEMAIL' and user_id = '$user_id'" , __LINE__ , __FILE__ );
             $WHERE = "( ";
             $ORDER = "ORDER BY $ORDERBY $AscORDesc";
             $LIMIT = '';
             if( $qty != "ALL"){
                 $LIMIT = "LIMIT $start , $qty";
             }
             if( $qty == "ALL" && $start != '0'){
                 $LIMIT = "OFFSET $start";
             }
             while($row=$this->db->fetch_assoc($res)){
                 $WHERE .= "a.uniqueid = '" . $row["module_id"] . "' OR ";
             }
             $WHERE = substr($WHERE , 0 , -3);
             if( $WHERE == '' ){ $WHERE = " 1 = 0 "; } else
             { $WHERE .= " ) "; }
             $WHERE .= "AND b.mailboxuser <> ''";
             $sql = "SELECT b.id , b.msgnum , b.duration , b.origtime , b.mailboxuser , b.mailboxcontext , b.dir , b.callerid FROM voicemail_users a LEFT JOIN voicemessages b ON a.context = b.mailboxcontext AND a.mailbox = b.mailboxuser WHERE $WHERE $ORDER $LIMIT"; ;
             $res2 = $this->asterisk_db->query($sql , __LINE__ , __FILE__ );
             $return = array();
             while($row=$this->asterisk_db->fetch_assoc($res2)){
                 $return[] = $row;
             }
             return $return;
             
         }

         function get_current_calls( $user_id ){
             $fake_call = false;
             $extens = $this->get_extention( $user_id);
             $WHERE = "( `ext` = 's' OR";
             foreach( $extens as $exten ){
                 $WHERE .= " `ext` = '" . ASTERISK_ACCOUNT_CODE . $exten["exten"] . "' OR";
             }
             $WHERE = substr( $WHERE , 0 , -3 );
             $WHERE .= ") AND `account` = '" . ASTERISK_ACCOUNT_CODE  . "'";
             $res = $this->asterisk_db->query("SELECT cid FROM currentcalls WHERE $WHERE");
             $return = array();

             while($row=$this->asterisk_db->fetch_assoc($res)){
                 $tmp = array();
                 //$tmp["calllog"] = $this->get_calls_rec( $row["cid"] , 3 );
                 $tmp["cid"] = $row["cid"];
                 $user_info = $this->contacts->contact_search($row["cid"], array("phone"));
                 $tmp["user_info"] = $user_info[0];
                 //$tmp["count"] = count($user_info);
                 if( count($user_info) != 0 ){
                    $tmp = array_merge($tmp , $user_info[0]);

                 }

                 $return[] = $tmp;
             }
             if( $fake_call ===true ){
                  $tmp = array();
                 //$tmp["calllog"] = $this->get_calls_rec( $row["cid"] , 3 );
                  
                  $row = array( "cid" => "6084060226");
                 $tmp["cid"] = $row["cid"];
                 $user_info = $this->contacts->contact_search($row["cid"], array("phone"));
                 
                 //$tmp["count"] = count($user_info);
                 if( count($user_info) != 0 ){
                    $tmp = array_merge($tmp , $user_info[0]);

                 }
                 $tmp["user_info"] = $user_info[0];
                 $return[] = $tmp;
             }

             return $return;

         }
         function callerid_search( $cid ){
             $explode1 = explode( "<" , $cid);
             $explode2 = explode( ">" , $explode1[1]);
             $cidnum = $explode2[0];
             $return = array();
             switch (strlen( $cidnum) ){
                 case "12":
                     $cidnum = substr( $cidnum , 1 , 10 );
                     //echo $cidnum;
                 case "10":
                    $tmp = $this->contacts->contact_search($cidnum, array("phone"));
                    if( count($tmp) != 0 ){
                        //print_r($tmp);
                        $return["name"] = $tmp[0]["name"];
                        $return["module_id"] = $tmp[0]["contact_id"];
                        $return["module"] = "contact";

                    } else {
                        $return["name"] = $explode1[0];
                        $return["module"] = "";
                        $return["module_id"] = '';

                    }
                 break;
                 case "3":
                 case "4":
                 case "5":
                    $res1 = $this->asterisk_db->query("SELECT id FROM sip WHERE callerid = '$cid'" , __LINE__ , __FILE__ );
                    if( $this->asterisk_db->num_rows($res1) == 0 ){
                        $return["name"] = $explode1[0];
                        $return["module"] = '';
                        $return["module_id"] = '';

                    } else {
                        $tmp = $this->asterisk_db->fetch_assoc($res1);
                        $res2 = $this->db->query("SELECT a.user_id user_id , b.first_name first_name , b.last_name last_name FROM user_asterisk a LEFT JOIN tbl_user b ON a.user_id = b.user_id WHERE a.module = 'SIP' AND a.module_id = '" . $tmp["id"] . "'" , __LINE__ , __FILE__ );
                        if($this->db->num_rows($res2) == 0 ){
                            $return["name"] = $explode1[0];
                            $return["module"] = '';
                            $return["module_id"] = '';
                        } else {
                            $info = $this->db->fetch_assoc($res2);
                            $return["name"] = $info["first_name"] . " " . $info["last_name"];
                            $return["module"] = "user";
                            $return["module_id"] = $info["user_id"];
                        }
                    }

                 break;
                 default:
                    $return["name"] = $explode1[0];
                    $return["module"] = '';
                    $return["module_id"] = '';
                  break;




             }
             return $return;
         }

         function display_mailbox( $user_id , $qty="ALL" , $start = '0' ,  $ORDERBY="b.origtime" , $AscORDesc="DESC" , $css_class="" ){
             $vms = $this->list_mailbox_voicemail_by_user( $user_id , $qty , $start ,  $ORDERBY , $AscORDesc );
             foreach( $vms as $vm){
                 ?>
                 <li class="<?php echo $css_class; ?>" >
<!--
<embed src="wavplayer.swf?sound=voicemail<?php echo $vm["id"]; ?>.WAV&gui=full"
	   bgcolor="#ffffff"
	   width="20"
	   height="20"
	   name="haxe"
	   quality="high"
	   align="middle"
	   scale="exactfit"
	   allowScriptAccess="always"
	   type="application/x-shockwave-flash"
	   pluginspage="http://www.macromedia.com/go/getflashplayer"
/> -->
            <p><?php 
            $info = $this->callerid_search($vm["callerid"]);
            //echo $info["name"];
            //print_r($vm);
            switch( $info["module"] ){
                case "contact":
                    echo "<a href='contact_profile.php?contact_id=" . $info["module_id"] . "' >" . $info["name"] . "</a> <br/> " . date("m-d-y H:i:s" , $vm["origtime"]);
                    
                break;
                case "user":
                    echo $info["name"] . "<br/> " . date("m-d-y H:i:s" , $vm["origtime"]);
                break;
                default:
                    echo $info["name"] . " <br/> " . date("m-d-y H:i:s" , $vm["origtime"]);
                break;
            }


            ?></p>
            <object type="application/x-shockwave-flash" data="player_mp3_maxi.swf" width="200" height="20">

    <param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="mp3=voicemail<?php echo $vm["id"]; ?>.mp3" />
</object>
          <!-- <embed src="voicemail<?php echo $vm["id"]; ?>.mp3" controller="true" autoplay="false" autostart="false" height="25px" width="200px"/> -->

                 <?php
             }

         }
         function renumber_voicemail( $dir ){
             $res = $this->asterisk_db->query("SELECT * FROM voicemessages WHERE dir = '$dir' ORDER BY id ASC");
             $x=0;
             while($row=$this->asterisk_db->fetch_assoc($res)){
                 $this->asterisk_db->query("UPDATE voicemessages SET msgnum = '$x' WHERE id = '" . $row["id"] . "'" , __LINE__ , __FILE__ );
                 $x++;
             }
         }

         function move_voicemail( $id , $to="Old"){
             $res1 = $this->asterisk_db->query("SELECT dir FROM voicemessages WHERE id = '$id'" , __LINE__ , __FILE__ );
             $res1row = $this->asterisk_db->fetch_assoc($res1);
             $dir_exp = explode( "/" , $res1row["dir"] );
             $original_dir = $dir_exp[ count($dir_exp)-1];
             $x = 0;
             $dir_base = '';
             while( $x < count( $dir_exp) -1 ){
                 $dir_base .= $dir_exp[$x] . "/";
                 $x++;
             }
             //echo $dir_base;
             $this->asterisk_db->query("UPDATE voicemessages SET dir = '$dir_base$to' WHERE id = '$id'" , __LINE__ , __FILE__ );
             $this->renumber_voicemail($dir_base . $to);
             $this->renumber_voicemail($dir_base . $original_dir);
             echo $dir_base . $to;


         }
         function add_voicemail($user_id , $mailbox , $password='rand' , $override = array()){
             $u = $this->user->GetUser($user_id);
             if($password == 'rand'){
                $password = rand(1000 , 9999 );
             }
             $v = array();
             $v["context"] = ASTERISK_MAILBOX_CONTEXT;
             $v["mailbox"] = $mailbox;
             $v["fullname"] = $u["first_name"] . " " . $u["last_name"];
             $v["password"] = $password;
             $v["email"] = $u["email_id"];
             foreach( $override as $name => $value){
                 $v[$name] = $value;
             }
            $this->asterisk_db->insert("voicemail_users", $v , false , '' , '' , 0 );
            return $v;

         }

         function add_phone( $user_id , $exten , $password='rand' , $override = array() ){
                //MUST HAVE ACCOUNT_CODE defined in global.config.php
                $u = $this->user->GetUser($user_id);
                $char = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                if($password == 'rand'){
                    $password = $char[ rand(0, 61)] . $char[ rand(0, 61)] . $char[ rand(0, 61)] . $char[ rand(0, 61)] .$char[ rand(0, 61)] .$char[ rand(0, 61)] .$char[ rand(0, 61)] . $char[ rand(0, 61)];
                }

                $p = array();
                $p["name"] = ASTERISK_ACCOUNT_CODE . $exten;
                $p["host"] = 'dynamic';
                $p["nat"] = "yes";
                $p["type"] = "friend";
                $p["accountcode"] = ASTERISK_ACCOUNT_CODE;
                $p["callerid"] = '"' . $u["first_name"] . " " . $u["last_name"] . '" <' . $exten . '>';
                $p["call-limit"] = "10";
                $p["cancallforward"] = "yes";
                $p["canreinvite"] = "no"; // investigate this latter to see if maybe I would want this enabled
                $p["context"] = ASTERISK_CONTEXT;
                $p["dtmfmode"] = "rfc2833";
                $p["language"] = 'en';
                $p["mailbox"] = $exten . '@' . ASTERISK_MAILBOX_CONTEXT;
                $p["deny"] = "0.0.0.0/0.0.0.0";
                $p["permit"] = "0.0.0.0/0.0.0.0";
                $p["musiconhold"] = 'default';
                $p["qualify"] = "yes";
                $p["secret"] = $password;
                $p["setvar"] = "CONUM=" . ASTERISK_ACCOUNT_CODE;
                $p["disallow"] = "all";
                $p["allow"] = 'h263p,h263,ulaw,alaw,gsm,all';
                $p["username"] = ASTERISK_ACCOUNT_CODE . $exten;
                $p["defaultuser"] = ASTERISK_ACCOUNT_CODE . $exten;
                foreach( $override as $name => $value){
                    $p[$name] = $value;

                }
                //echo $exten . "\n";
                //echo '"' . $u["first_name"] . " " . $u["last_name"] . '" <' . $exten . '>' . "\n";
                //echo $p["callerid"];
                $this->asterisk_db->insert("sip", $p , false , '' , '' , 0 );
                $ua = array();
                $ua["user_id"] = $user_id;
                $ua["module_id"] = $this->asterisk_db->last_insert_id();
                $ua["module"] = "SIP";

                $this->db->insert("user_asterisk", $ua);
                return $v;
         }
	 
}
/*
$array = asterisk_get_info( "6087902229" , 10 );
foreach( $array as $v ){
	foreach( $v as $nn => $vv ){
		echo "$nn = $vv ";
	}	
	echo "\n";
}
*/
?>
