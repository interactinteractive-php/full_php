<?php
if ($this->packageChildMetas) {
    
    $tabHead = $tabContent = '';
    
    foreach ($this->packageChildMetas as $k => $row) {
        
        $activeClass = ($k == 0) ? ' active' : '';
        
        $tabHead .= '<li class="nav-item">';
            $tabHead .= '<a href="#package-tab-'.$row['META_DATA_ID'].'" class="nav-link '.$activeClass.'" data-toggle="tab" onclick="packageRenderType(\''.$row['META_DATA_ID'].'\', \''.$row['META_TYPE_ID'].'\', this, \''.$this->metaDataId.'\', undefined, undefined, undefined, undefined, \''. issetParam($this->uriParams) .'\');" data-packagecode="'.$this->packageCode.'" data-metadatacode="'.$row['META_DATA_CODE'].'">';
                $tabHead .= $this->lang->line((new Mdobject())->getNameByType($row['META_DATA_ID'], $row['META_TYPE_ID'], $row['META_DATA_NAME']));
                
                if (isset($this->countResult[$row['META_DATA_ID']])) {
                    $tabHead.= '<span class="badge badge-info badge-pill font-size-11 bg-root-color ml-2" style="padding: 3px 6px;">'.$this->countResult[$row['META_DATA_ID']]['cnt'].'</span>';
                }
                
            $tabHead .= '</a>';
        $tabHead .= '</li>';
        
        $tabContent .= '<div class="tab-pane'.$activeClass.'" id="package-tab-'.$row['META_DATA_ID'].'"></div>';
    }
?>
<div class="card-title tabbable-line tab-not-padding-top package-tab row">
    <?php
    if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) {
        
        $inlineStyle = $addonClass = '';
        
        if (!empty($this->row['TAB_BACKGROUND_COLOR'])) {
            
            $inlineStyle = 'background: none !important; background-color: '.$this->row['TAB_BACKGROUND_COLOR'].' !important;';
            
            if ($this->row['IS_IGNORE_MAIN_TITLE'] == '1') {
                $inlineStyle .= 'min-height: 40px;';
                $addonClass = ' no-title-package'; 
            }    
            
        } elseif ($this->row['IS_IGNORE_MAIN_TITLE'] == '1') {
            $addonClass = ' no-title-package'; 
        }
    ?>
        <div class="meta-toolbar col<?php echo $addonClass; ?>" style="<?php echo $inlineStyle; ?>">
            <span class="text-uppercase"><?php echo $this->title; ?></span>
        </div> 
    <?php 
        echo Form::button(
            array(
                'class' => 'btn btn-light btn-sm float-right', 
                'value' => '<i class="icon-sync"></i> '.$this->lang->line('refresh_btn'), 
                'onclick' => 'packageReload(this);'
            ), issetParam($this->row['IS_REFRESH'])
        ); 
    }
    ?>
    <ul class="nav nav-tabs float-left">
        <?php echo $tabHead; ?>
    </ul>
</div>
<div class="card-body card-package-body pt0">
    <div class="tab-content">
        <div class="clearfix w-100"></div>
        <?php echo $tabContent; ?>
    </div>
</div>
<script type="text/javascript">
$(function(){
    $("div#package-meta-<?php echo $this->metaDataId; ?>").find("ul.nav-tabs > li:eq(0) > a").trigger("click");
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