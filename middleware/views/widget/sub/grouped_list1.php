<?php
$checkGroupKey = array();
$render = '';

foreach ($this->widgetData as $row) {
    
    $item = '<div class="dropdown-item">
        <i class="icon-file-text2"></i> '.$row['position2'].'
    </div>';
    
    if (!isset($checkGroupKey[$row['position1']])) {
        
        $checkGroupKey[$row['position1']] = 1;
        
        $render .= '<div class="mb-3">
            <h6 class="font-weight-semibold mt-2">
                <i class="icon-folder6 mr-2"></i> '.$row['position1'].'
            </h6>
            <div class="dropdown-divider mb-2"></div>
            '.$item.'
            <!--'.$row['position1'].'-->
        </div>';
                
    } else {
        
        $render = str_replace('<!--'.$row['position1'].'-->', $item . '<!--'.$row['position1'].'-->', $render);
    }
}

echo $render;
?>