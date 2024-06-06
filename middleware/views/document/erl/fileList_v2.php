<?php
if ($this->fileList) {    
    echo html_entity_decode($this->fileList, ENT_QUOTES, 'UTF-8');
}
?>