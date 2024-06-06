<div class="kpiFormSection col-md-12 mb10 kpi-rendertype-<?php echo $this->renderType; ?>">
    <?php 
    echo $this->templateName; 
    
    if ($this->templateHeight != 'auto') {
        
        $style = 'style="max-height: 450px"';
    
        if (!empty($this->templateWidth) && !empty($this->templateHeight)) {
            $style = 'style="width: ' . $this->templateWidth . '; max-height: ' . $this->templateHeight . '"';
        } elseif (!empty($this->templateWidth)) {
            $style = 'style="width: ' . $this->templateWidth . '; max-height: 450px"';
        } elseif (!empty($this->templateHeight)) {
            $style = 'style="max-height: ' . $this->templateHeight . '"';
        }
        
    } else {
        $style = '';
    }
    ?>
    
    <div id="kpiDmDtl-<?php echo $this->templateId; ?>" data-parent-path="kpiDmDtl" <?php echo $style; ?> class="bp-overflow-xy-auto border-top-1 border-bottom-0 border-left-0 border-right-0 border-gray">
        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 kpi-dtl-table" data-table-path="kpiDmDtl" data-kpi-code="<?php echo $this->templateCode; ?>" data-group-path="<?php echo Mdform::$pathPrefix; ?>">
            <thead>
                <?php echo $this->gridHead; ?>
            </thead>
            <tbody>
                <?php echo $this->gridBody; ?>
            </tbody>
        </table>
        <input type="hidden" name="param[pfKpiTemplateId][]" value="<?php echo $this->templateId; ?>" data-kpiheader-input="kpiTemplateId">
        <input type="hidden" name="param[pfKpiTemplateCode][]" value="<?php echo $this->templateCode; ?>" data-kpiheader-input="kpiTemplateCode">
        <?php echo Mdform::$pfTranslationValueTextarea; ?>
    </div>    
</div> 

<?php echo $this->scripts; ?>