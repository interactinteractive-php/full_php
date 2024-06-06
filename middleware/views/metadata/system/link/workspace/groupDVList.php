<?php 
foreach ($this->groupList as $key => $row) {
    echo '<label class="col-md-12"><input type="radio" value="'.$row['META_DATA_ID'].'" name="groupMetaData"> '.$row['META_DATA_NAME'].'</lable>';
}
?>
