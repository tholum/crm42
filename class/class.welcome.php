<?php
class welcome {
    var $db;
   function __construct(){
	$this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
   }    
    function get_news(){
        $news = $this->db->fetch_assoc($this->db->query("SELECT a.* , b.first_name , b.last_name FROM daily_news a LEFT JOIN tbl_user b ON a.user_id = b.user_id  ORDER BY a.created DESC LIMIT 1"));
        return $news;
    }
    function edit_news($html){
        $update = array();
        $update['user_id'] = $_SESSION['user_id'];
        $update['news'] = base64_encode($html);
        $this->db->update('daily_news', $update, 'news_id', '1');
    }
}
?>
