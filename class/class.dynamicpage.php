<?php
class dynamic_page {
    public $db;
    function __construct() {
         $this->db = new database(DATABASE_HOST,DATABASE_PORT,DATABASE_USER,DATABASE_PASSWORD,DATABASE_NAME);
    }
    public function phplivex_subpage_link($parent,$subpage,$target,$tmpv=array()){
        $options = array();
        foreach($tmpv as $n => $v){
            if( $n != '' ){
                $options[$n] = $v;
            }
        }
        return 'dynamic_page.phplivex_subpage(\'' . $parent . '\' , \'' . $subpage . '\' ,' . str_replace( '"' , "'" , json_encode($options) ). ' ,  { target: \'' .$target . '\' ' . $this->phplivex_subpage_options($parent,$subpage,$tmpv) .  ' });';
    }
    public function phplivex_subpage($parent , $subpage ,  $tmpv=array() ) {
        
        ob_start();
        $vars = array();
        foreach( $tmpv as $n => $v ){
            $vars[$n] = $v;
        }
        if(file_exists("./pages/$parent.$subpage.main.php")){
            include("pages/$parent.$subpage.main.php");
        }
                $html = ob_get_contents();
                ob_end_clean();
                return $html; 
    }
    
    public function phplivex_subpage_options( $parent , $subpage , $tmpv=array() ){
        ob_start();
        $vars = array();
        foreach( $tmpv as $n => $v ){
            $vars[$n] = $v;
        }
        if(file_exists("./pages/$parent.$subpage.onfinish.php")){
            echo ", onFinish: ";
            include("pages/$parent.$subpage.onfinish.php");
            echo "";
        }
        if(file_exists("./pages/$parent.$subpage.onupdate.php")){
            echo ", onUpdate: ";
            include("pages/$parent.$subpage.onupdate.php");
            echo "";
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
    
    public function phplivex_page($name , $tmpv=array() ) {
        
        ob_start();
        $vars = array();
        foreach( $tmpv as $n => $v ){
            $vars[$n] = $v;
        }
        if(file_exists("./pages/$name.main.php")){
            include("pages/$name.main.php");
        }
                $html = ob_get_contents();
                ob_end_clean();
                return $html; 
    }
    public function phplivex_options( $name , $tmpv=array() ){
        ob_start();
        $vars = array();
        foreach( $tmpv as $n => $v ){
            $vars[$n] = $v;
        }
        if(file_exists("./pages/$name.onfinish.php")){
            echo ", onFinish: ";
            include("pages/$name.onfinish.php");
            echo "";
        }
        if(file_exists("./pages/$name.onupdate.php")){
            echo ", onUpdate: ";
            include("pages/$name.onupdate.php");
            echo "";
        }
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
?>
