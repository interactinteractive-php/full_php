<?php
if ($this->packageChildMetas) {
    
    $content = '<div class="row">';
        
        foreach ($this->packageChildMetas as $k => $row) {
            
            $labelName = $this->lang->line((new Mdobject())->getNameByType($row['META_DATA_ID'], $row['META_TYPE_ID'], $row['META_DATA_NAME']));
            
            if ($k == 0) {
                $columnCount = '12';
            } else {
                $columnCount = '6';
            }
            
            $content .= '<a href="#package-tab-'.$row['META_DATA_ID'].'" data-metadataid="'.$row['META_DATA_ID'].'" data-metatypeid="'.$row['META_TYPE_ID'].'" data-metadatacode="'.$row['META_DATA_CODE'].'" class="hide">'.$labelName.'</a>';
            $content .= '<div class="col-md-'.$columnCount.'">';
                
                if (isset($this->isIgnorePackTitle) && $this->isIgnorePackTitle === '0') {
                    $content .= '<div class="package-tab-name">'.$labelName.'</div>';
                }
                
                $content .= '<div class="package-tab" id="package-tab-'.$row['META_DATA_ID'].'"></div>';
            $content .= '</div>';
        }
        
    $content .= '</div>';
    echo $content;
?>

<script type="text/javascript">
    $(function(){
        $("div#package-meta-<?php echo $this->metaDataId; ?>").find('a[data-metadataid]').each(function(){
            var $this = $(this);        
            var metadataid = $this.attr('data-metadataid');
            var metatypeid = $this.attr('data-metatypeid');
            packageRenderType(metadataid, metatypeid, this);
        });
    });    
</script>

<style type="text/css">
    .web-process .merge-column .merge-column-content .row {
        display: inherit;
    }
</style>
<?php    
}
?>