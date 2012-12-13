<?php
$array=$this->db->fetch_assoc($this->db->query("SELECT * FROM eml_template WHERE eml_template_id = '" . $options['eml_template_id'] . "'"));
        
?>
