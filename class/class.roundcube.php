<?php

/**
 * Use a PHP script to perform a login to the Roundcube mail system.
 *
 * REQUIREMENTS
 *   - A Roundcube installation (tested with 0.2-beta, 0.3.x, 0.4-beta)
 * 
 *   - Set the "check_ip"/"ip_check" in the config/main.inc.php file to FALSE
 *     Why? The server will perform the login, not the client (= two different IP addresses)
 *
 * INSTALLATION
 *   - Install RC on your server so that it can be accessed via the browser,
 *     e.g. at www.example.com/roundcube/
 *
 *   - Download this script and remove all spaces and new lines
 *     before "<?php" and after "?>"
 *
 *   - Include the class in your very own script and use it.
 *
 * USAGE
 *   The class provides four public methods:
 *
 *   - login($username, $password)
 *         Perform a login to the Roundcube mail system.
 *      
 *         Note: If the client is already logged in, the script will re-login the user (logout/login).
 *               To prevent this behaviour, use the isLoggedIn()-function.
 *     
 *         Returns: TRUE if the login suceeds, FALSE if the user/pass-combination is wrong
 *         Throws:  May throw a RoundcubeLoginException if Roundcube sends an unexpected answer
 *                  (that might happen if a new Roundcube version behaves different).
 *             
 *   - isLoggedIn()
 *         Checks whether the client/browser is logged in and has a valid Roundcube session.
 *    
 *         Returns: TRUE if the user is logged in, FALSE otherwise.
 *         Throws:  May also throw a RoundcubeLoginException (see above).
 *
 *   - logout()
 *         Performs a logout on the current Roundcube session.
 *
 *         Returns: TRUE if the logout was a success, FALSE otherwise.
 *         Throws:  May also throw a RoundcubeLoginException (see above).
 *
 *   - redirect()
 *         Simply redirects to Roundcube.
 * 
 * SAMPLE CODE
 *   <?php
 *
 *       include "RoundcubeLogin.class.php";    
 *   
 *       // Create login object and enable debugging
 *       $rcl = new RoundcubeLogin("/roundcube/", true);
 *   
 *       try {
 *           // If we are already logged in, simply redirect
 *           if ($rcl->isLoggedIn())
 *               $rcl->redirect();
 *   
 *           // If not, try to login and simply redirect on success
 *           $rcl->login("your-email-address", "plain-text-password");
 *   
 *           if ($rcl->isLoggedIn())
 *               $rcl->redirect();
 *   
 *           // If the login fails, display an error message
 *           die("ERROR: Login failed due to a wrong user/pass combination.");
 *       }
 *       catch (RoundcubeLoginException $ex) {
 *           echo "ERROR: Technical problem, ".$ex->getMessage();
 *           $rcl->dumpDebugStack(); exit;
 *       }
 *   
 *   ?>  
 *
 * TROUBLESHOOTING
 *   - Make sure to remove all spaces before "<?php" and after "?>"
 *   - Enable the debug mode (set the second constructor parameter to TRUE)
 *   - Ask me if you have any problems :-)
 *
 * AUTHOR/LICENSE/VERSION
 *   - Written by Philipp Heckel; Find a corresponding blog-post at 
 *     http://blog.philippheckel.com/2008/05/16/roundcube-login-via-php-script/
 *
 *   - Updated Nov/08, tested with Ubuntu/Firefox 3
 *     No license. Feel free to use it :-)
 *
 *   - The script works with Roundcube 0.2, 0.3 and 0.4-beta (as of May 2010)
 *
 */      
class zimbra {
	var $db;
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}
	function zimbra_auth( $user_id , $app=""){
                //calendar

                $result = $this->db->query("SELECT email_username user_name  FROM " . TBL_USER . " WHERE user_id = '$user_id'");
                $return = array();
                $user = mysql_result( $result , 0 , "user_name" );
                $email = "$user@" . ZIMBRA_EMAIL;
                $timestamp=time()*1000;
                $preauthToken=hash_hmac("sha1",$email."|name|0|".$timestamp,ZIMBRA_PREAUTH_KEY);
                $app_url = '';
                if( $app != ''){ $app_url = "|app=$app"; }
                $ru = explode("/" , $_SERVER["REQUEST_URI"]);
                $x = 1;
                $DIR = '';
                while( $x < count($ru) ){
                    $DIR .= $ru[$x-1] . "/";
                    $x++;
                }
                $http_s = "";
                switch($_SERVER["SERVER_PORT"]){
                    case "80":
                    default:
                        $http_s = "http://";
                    break;
                    case "443":
                        $http_s = "https://";
                    break;

                }

                $Decode= $http_s . $_SERVER["HTTP_HOST"] . $DIR . "decode.php?URL=/zimbra/?skin=fields$app_url";
                $preauthURL = ZIMBRA_PREAUTH_URL . "?account=".$email."&by=name&timestamp=".$timestamp."&expires=0&preauth=".$preauthToken . "&redirectURL=$Decode";
                
               return $preauthURL;

	}

	function xml2assoc($xml, $name)
  {
   // print "<ul>";

    $tree = null;
    print("I'm inside " . $name . "<br>");
   
    while($xml->read())
    {
        if($xml->nodeType == XMLReader::END_ELEMENT)
        {
            print "</ul>";
            return $tree;
        }
       
        else if($xml->nodeType == XMLReader::ELEMENT)
        {
            $node = array();
           
            print("Adding " . $xml->name ."<br>");
            $node['tag'] = $xml->name;

            if($xml->hasAttributes)
            {
                $attributes = array();
                while($xml->moveToNextAttribute())
                {
                    print("Adding attr " . $xml->name ." = " . $xml->value . "<br>");
                    $attributes[$xml->name] = $xml->value;
                }
                $node['attr'] = $attributes;
            }
           
            if(!$xml->isEmptyElement)
            {
                $childs = xml2assoc($xml, $node['tag']);
                $node['childs'] = $childs;
            }
           
            print($node['tag'] . " added <br>");
            $tree[] = $node;
        }
       
        else if($xml->nodeType == XMLReader::TEXT)
        {
            $node = array();
            $node['text'] = $xml->value;
            $tree[] = $node;
            print "text added = " . $node['text'] . "<br>";
        }
    }
   
    print "returning " . count($tree) . " childs<br>";
    print "</ul>";
   
    return $tree;
}
        function zimbra_rss_feed( $user_id , $feed ,  $app=""){
                //calendar

                $result = $this->db->query("SELECT user_name  FROM " . TBL_USER . " WHERE user_id = '$user_id'");
                $return = array();
                $user = mysql_result( $result , 0 , "user_name" );
                $email = "$user@" . ZIMBRA_EMAIL;
                $timestamp=time()*1000;
                $preauthToken=hash_hmac("sha1",$email."|name|0|".$timestamp,ZIMBRA_PREAUTH_KEY);
                $app_url = '';
                if( $app != ''){ $app_url = "|app=$app"; }
                $ru = explode("/" , $_SERVER["REQUEST_URI"]);
                $x = 1;
                $DIR = '';
                while( $x < count($ru) ){
                    $DIR .= $ru[$x-1] . "/";
                    $x++;
                }
                $http_s = "";
                switch($_SERVER["SERVER_PORT"]){
                    case "80":
                    default:
                        $http_s = "http://";
                    break;
                    case "443":
                        $http_s = "https://";
                    break;

                }

                $Decode= $http_s . $_SERVER["HTTP_HOST"] . $DIR . "decode.php?URL=/zimbra/?skin=fields$app_url";
                $preauthURL = ZIMBRA_PREAUTH_URL . "?account=".$email."&by=name&timestamp=".$timestamp."&expires=0&preauth=".$preauthToken . "&redirectURL=/home/$email/$feed";

               return $preauthURL;

	}

}

class email_functions {
	var $db;
	function __construct(){
		$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
	}

	
		public function getEmailAuth( $user_id ){
		$result = $this->db->query("SELECT email_username , email_password_enc FROM " . TBL_USER . " WHERE user_id = '$user_id'");
		$return = array();
		$return["user"] = mysql_result( $result , 0 , "email_username" );
		$return["pass"] = $this->decryptData( mysql_result( $result , 0 , "email_password_enc" ));
		return $return;
	}
	
	function encryptData($value){
		$text = $value;
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, WEBMAIL_ENCKEY , $text, MCRYPT_MODE_ECB, $iv);
	   return $crypttext;
	}

	function decryptData($value ){
	   $crypttext = $value;
	   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, WEBMAIL_ENCKEY, $crypttext, MCRYPT_MODE_ECB, $iv);
	   return trim($decrypttext);
	}
	
   	function settings($index,$user_id){
		echo "<th>Email Settings</th>";
		switch($index){
			case 'local' :
						$emailauth = $this->getEmailAuth( $user_id );
						?>
						    <tr>
							    <th>Email Username:</th>
								<td><input type="text" name="email_username" id="email_username" value="<? echo $emailauth["user"] ?>"  /></td>
							</tr>
						    <tr>
							    <th>Email Password</th>
								<td><input type="password" name="email_password_enc" id="email_password_enc" value="***NOPASSSET***"  /></td>
							</tr>
						<?php
					
						break;
			case 'server' :
						extract($_POST);
						//$this->google_apps_id = $google_apps_id;
						//$this->google_apps_password = $google_apps_password;
						$email_username = $_POST["email_username"];
						$email_password_enc = $_POST["email_password_enc"];
						$update_sql_array = array();
						$update_sql_array["email_username"] = $email_username;
						if( $email_password_enc != "***NOPASSSET***" ){
							$update_sql_array["email_password_enc"] = $this->encryptData( $email_password_enc );
						}
						
						$this->db->update(TBL_USER,$update_sql_array,'user_id',$user_id);
						//exit();
						

		}
	}
   



}
 
 
 
class RoundcubeLogin {
    /**
     * Relative path to the Roundcube base directory on the server. 
     * 
     * Can be set via the first argument in the constructor.
     * If the URL is www.example.com/roundcube/, set it to "/roundcube/".
     *
     * @var string
     */
    private $rcPath;    
    
    /**
     * Roundcube session ID
     *
     * RC sends its session ID in the answer. If the first attempt doesn't
     * work, the login-function retries it with the session ID. This does
     * work most of the times.
     *
     * @var string
     */
    private $rcSessionID;    
    
    /**
     * Save the current status of the Roundcube session.
     * 0 = unkown, 1 = logged in, -1 = not logged in.
     *
     * @var int
     */
    private $rcLoginStatus;
    
    /**
     * Debugging can be enabled by setting the second argument
     * in the constructor to TRUE.
     *
     * @var bool
     */
    private $debugEnabled;
    
    /**
     * Keep debug messages on a stack. To dump it, call
     * the dumpDebugStack()-function.
     *
     * @var array
     */
    private $debugStack;    
    private $db;
    /**
     * Create a new RoundcubeLogin class.
     *
     * @param string Relative webserver path to the RC installation, e.g. /roundcube/
     * @param bool Enable debugging, - shows the full POST and the response
     */
    public function __construct($webmailPath, $enableDebug = false) {
        $this->debugStack = array();
        $this->debugEnabled = $enableDebug;
        $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
        $this->rcPath = $webmailPath;        
        $this->rcSessionID = false;
        $this->rcLoginStatus = 0;        
    }
    
	
	function encryptData($value){
    $text = $value;
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, WEBMAIL_ENCKEY , $text, MCRYPT_MODE_ECB, $iv);
   return $crypttext;
}

	function decryptData($value ){
   $crypttext = $value;
   $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
   $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
   $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, WEBMAIL_ENCKEY, $crypttext, MCRYPT_MODE_ECB, $iv);
   return trim($decrypttext);
} 
	
	
	
	public function getEmailAuth( $user_id ){
		$result = $this->db->query("SELECT email_username , email_password_enc FROM " . TBL_USER . " WHERE user_id = '$user_id'");
		$return = array();
		$return["user"] = mysql_result( $result , 0 , "email_username" );
		$return["pass"] = $this->decryptData( mysql_result( $result , 0 , "email_password_enc" ));
		return $return;
	}
	
    /**
     * Login to Roundcube using the IMAP username/password
     *
     * Note: If the function detects that we're already logged in,
     *       it performs a re-login, i.e. a logout/login-combination to ensure
     *       that the specified user is logged in.
     *
     *       If you don't want this, use the isLoggedIn()-function and redirect
     *       the RC without calling login().
     *
     * @param string IMAP username
     * @param string IMAP password (plain text)
     * @return boolean Returns TRUE if the login was successful, FALSE otherwise
     * @throws RoundcubeLoginException
     */
    public function login($username,$password) {                
        $this->updateLoginStatus();

        // If already logged in, perform a re-login (logout first)
        if ($this->isLoggedIn())
            $this->logout();

        // Try login
        $data = "_action=login&_user=".urlencode($username)."&_pass=".urlencode($password);
        $response = $this->sendRequest($this->rcPath, $data);        

        //  Login successful! A redirection to ./?_task=... is a success!                        
        if (preg_match('/^Location\:.+_task=/mi',$response)) {
            $this->addDebug("LOGIN SUCCESSFUL","RC sent a redirection to ./?_task=..., that means we did it!");
            $this->rcLoginStatus = 1;            
        }

        // Login failure detected! If the login failed, RC sends the cookie "sessauth=-del-"
        else if (preg_match('/^Set-Cookie:.+sessauth=-del-;/mi',$response)) {
            //header($line);

            $this->addDebug("LOGIN FAILED","RC sent 'sessauth=-del-'; User/Pass combination wrong.");
            $this->rcLoginStatus = -1;
        }

        // Unkown, neither failure nor success.
        // This maybe the case if no session ID was sent
        else {
            $this->addDebug("LOGIN STATUS UNKNOWN","Neither failure nor success. This maybe the case if no session ID was sent");
            throw new RoundcubeLoginException("Unable to determine login-status due to technical problems.");
        }

        return $this->isLoggedIn();        
    }
    
    /**
     * Returns whether there is an active Roundcube session.
     *
     * @return bool Return TRUE if a user is logged in, FALSE otherwise
     * @throws RoundcubeLoginException
     */
    public function isLoggedIn() {
        $this->updateLoginStatus();
        
        if (!$this->rcLoginStatus)
            throw new RoundcubeLoginException("Unable to determine login-status due to technical problems.");

        return ($this->rcLoginStatus > 0) ? true : false;
    }
        
    /**
     * Logout from Roundcube
     * @return bool Returns TRUE if the login was successful, FALSE otherwise
     */
    public function logout() {
        $data = "_action=logout&_task=logout";
        $this->sendRequest($this->rcPath, $data);    
        
        return !$this->isLoggedIn();
    }
    
    /**
     * Simply redirect to the Roundcube application.
     */
    public function redirect() {
        header("Location: {$this->rcPath}");
        exit;
    }
    
    /**
     * Gets the current login status and the session cookie.
     *
     * It updates the private variables rcSessionID and rcLoginStatus by
     * sending a request to the main page and parsing the result for the login form.
     */
    private function updateLoginStatus($forceUpdate = false) {
        if ($this->rcSessionID && $this->rcLoginStatus && !$forceUpdate)
            return;
    
        // Get current session ID cookie
        if ($_COOKIE['roundcube_sessid'])
            $this->rcSessionID = $_COOKIE['roundcube_sessid'];
    
        // Send request and maybe receive new session ID
        $response = $this->sendRequest($this->rcPath);
        
        // Login form available?
        if (preg_match('/<input.+name="_pass"/mi',$response)) {
            $this->addDebug("NOT LOGGED IN", "Detected that we're NOT logged in.");            
            $this->rcLoginStatus = -1;            
        }
        
        else if (preg_match('/<div.+id="message"/mi',$response)) {
            $this->addDebug("LOGGED IN", "Detected that we're logged in.");            
            $this->rcLoginStatus = 1;    
        }
        
        else {
            $this->addDebug("UNKNOWN LOGIN STATE", "Unable to determine the login status. Did you change the RC version?");            
           // throw new RoundcubeLoginException("Unable to determine the login status. Unable to continue due to technical problems.");
        }            
        
        // If no session ID is available now, throw an exception
        if (!$this->rcSessionID) {
            $this->addDebug("NO SESSION ID", "No session ID received. RC version changed?");            
            throw new RoundcubeLoginException("No session ID received. Unable to continue due to technical problems.");
        }        
    }
        
    /**
     * Send a POST/GET request to the Roundcube login-script
     * to simulate the login.
     *
     * If the second parameter $postData is set, the function will
     * use the POST method, otherwise a GET will be sent.
     *
     * Ensures that all cookies are sent and parses all response headers
     * for a new Roundcube session ID. If a new SID is found, rcSessionId is set.
     *
     * @param string Optional POST data in urlencoded form (param1=value1&...)
     * @return string Returns the complete request response with all headers.
     */
    private function sendRequest($path, $postData = false) {
        $method = (!$postData) ? "GET" : "POST";
        $port = ($_SERVER['HTTPS']) ? 443 : 80;
        $host = ($port == 443) ? "ssl://localhost" : "localhost";
        
        
        // Load cookies and save them in a key/value array    
        $cookies = array();

        foreach ($_COOKIE as $name=>$value)
            $cookies[] = "$name=$value";
            
        // Add roundcube session ID if available
        if (!$_COOKIE['roundcube_sessid'] && $this->rcSessionID)
            $cookies[] = "roundcube_sessid={$this->rcSessionID}";
        
        $cookies = ($cookies) ? "Cookie: ".join("; ",$cookies)."\r\n" : "";
        
        
        // Create POST request with the given data
        if ($method == "POST") {
            $request = 
                  "POST ".$path." HTTP/1.1\r\n"
                . "Host: ".$_SERVER['HTTP_HOST']."\r\n"
                . "Content-Type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: ". strlen($postData) ."\r\n"
                . $cookies
                . "Connection: close\r\n\r\n"
            
                . $postData;
        }
        
        // Make GET
        else {    
            $request = 
                  "GET ".$path." HTTP/1.1\r\n"
                . "Host: ".$_SERVER['HTTP_HOST']."\r\n"
                . $cookies
                . "Connection: close\r\n\r\n";
        }
        
        // Send request    
        $fp = fsockopen($host, $port);

        // Request
        $this->addDebug("REQUEST", $request);
        fputs($fp, $request);
        
        
        // Read response and set received cookies    
        $response = "";
        
        while (!feof($fp)) {
            $line = fgets($fp, 4096);            

            // Not found
            if (preg_match('/^HTTP\/1\.\d\s+404\s+/',$line))
                throw new RoundcubeLoginException("No Roundcube installation found at '$path'");

            // Got session ID!
            if (preg_match('/^Set-Cookie:.+roundcube_sessid=([^;]+);/i',$line,$match)) {
                header($line);
            
                $this->addDebug("GOT SESSION ID", "New session ID: '$match[1]'.");
                $this->rcSessionID = $match[1];                
            }                    

            $response .= $line;
        }
            
        fclose($fp);    
        
        $this->addDebug("RESPONSE", $response);                    
        return $response;        
    }
    
    /**
     * Print a debug message if debugging is enabled.
     *
     * @param string Short action message
     * @param string Output data
     */
    private function addDebug($action, $data) {
        if (!$this->debugEnabled)
            return false;
        
        $this->debugStack[] = sprintf(
            "<b>%s:</b><br /><pre>%s</pre>",
            $action, htmlspecialchars($data) 
        );
    }
    
    /**
     * Dump the debug stack
     */
    public function dumpDebugStack() {
        print "<p>".join("\n", $this->debugStack)."</p>";
    }    
}

/**
 * This Roundcube login exception will be thrown if the two 
 * login attempts fail.
 */
//class RoundcubeLoginException extends Exception { }

?>

