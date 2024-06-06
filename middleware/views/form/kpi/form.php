<div class="kpiFormSection col-md-12 mb10">
    <?php echo $this->templateName; ?>
    
    <div id="kpiDmDtl-<?php echo $this->templateId; ?>" data-parent-path="kpiDmDtl">
        <table class="table bprocess-table-dtl bprocess-theme1 pf-kpi-table" data-table-path="kpiDmDtl" data-kpi-code="<?php echo $this->templateCode; ?>">
            <tbody>
                <?php echo $this->formBody; ?>
            </tbody>
        </table>
        <input type="hidden" name="param[pfKpiTemplateId][]" value="<?php echo $this->templateId; ?>" data-kpiheader-input="kpiTemplateId">
        <input type="hidden" name="param[pfKpiTemplateCode][]" value="<?php echo $this->templateCode; ?>" data-kpiheader-input="kpiTemplateCode">
        <?php echo Mdform::$pfTranslationValueTextarea; ?>
    </div> 
</div>

<?php echo $this->scripts; ?>