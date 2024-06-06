<?php 
echo $this->htmlTemplate;  

$rowsHtml = '';
$top = $left = $width = $height = 0;

if (isset($this->getNDDprintPosition) && is_array($this->getNDDprintPosition)) {

    $defaultTop = $marginTop = $defaultLeft1 = $marginLeft1 = $defaultLeft2 = $marginLeft2 = 0;
    $rowCount = count($this->getNDDprintPosition); 
    
    foreach ($this->getNDDprintPosition as $key => $value) {
        
        if ($key == 0) {
            $top = $value['top'] * 3.7795275590551;
            $left = $value['colOneLeft'] * 3.7795275590551;
            $width = ($this->getNDDprintPreview['col1Width'] + $this->getNDDprintPreview['col2Width']) * 3.7795275590551;
            $height = ($rowCount * $this->getNDDprintPreview['rowHeight']) * 3.7795275590551;
            
            $defaultTop = $value['top'];
            $defaultLeft1 = $value['colOneLeft'];
            $defaultLeft2 = $value['colTwoLeft'];
            $marginTop = 0;
            $marginLeft1 = 0;
            $marginLeft2 = ($value['colTwoLeft'] - $defaultLeft1) * 3.7795275590551;
            
        } else {
            $marginTop = ($value['top'] - $defaultTop) * 3.7795275590551;
            $marginLeft1 = 0;
            $marginLeft2 = ($value['colTwoLeft'] - $defaultLeft1) * 3.7795275590551;
        }
        
        $rowsHtml .= '<div style="top: '.$marginTop.'px; position: absolute;">
                <div class="nddColPre" style="left: '.$marginLeft1.'px">'.$value['col4Data'].'</div><div class="nddColPre" style="left: '.$marginLeft2.'px">'.$value['col5Data'].'</div>
            </div>';
    }  
}
?>
<div id="nddContentsPrintPrev" style="position: absolute; padding: 0; top: <?php echo $top; ?>px; left: <?php echo $left; ?>px; width: <?php echo $width; ?>px; height: <?php echo $height; ?>px;">
    <?php echo $rowsHtml; ?>
</div>