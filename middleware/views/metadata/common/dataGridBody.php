<?php
if ($this->dataGridBodyData) {
    foreach ($this->dataGridBodyData as $row) {
        echo $row['FIELD_NAME'] . ": row." . $row['FIELD_NAME'] . ",";
    }
}    
