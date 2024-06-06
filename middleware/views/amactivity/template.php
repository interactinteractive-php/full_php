<div class="col-md-12" id="amactivityTemplateMaindWindow">
        <div class="card-body xs-form">
            <div class="row">
                <div class="col-md-12">
                    <form id="activityInfoForm" class="form-horizontal" method="post">
                        <div class="form-body">
                            <div class="row ">
                                <div class="col-md-12">
                                    <fieldset class="collapsible">
                                        <legend style="font-size: 14px; ">Ерөнхий мэдээлэл</legend>
                                            <div class="form-group row fom-row">
                                                <div class="col-md-4">
                                                    <?php echo Form::label(array( 'text' => 'Төлөвлөлтийн загвар', 'for' => 'activityKeyId', 'class' => 'customLabel col-form-label float-right', 'required' => 'required')); ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="meta-autocomplete-wrap" data-section-path="activityKeyId">
                                                        <div class="input-group double-between-input">
                                                            <input type="hidden" id="activityKeyId_valueField" required="required" class="popupInit" name="activityKeyId" value="<?php echo $this->activityKeyId; ?>">
                                                            <input type="text" id="activityKeyId_displayField" required="required" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete"  title="" value="<?php echo $this->getRowActivityKey['ACTIVITY_KEY_CODE']; ?>" placeholder="<?php echo $this->lang->line('code_search'); ?>" data-activityKeyId="0" data-processid="0" data-lookupid="" data-lookuptypeid="">
                                                            <span class="input-group-btn">
                                                                <button type="button" id="departmentSearchButton" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('AM_ACTIVITY_KEY_TEMPLATE_LIST', 'single', 'activitySelectabledGridTemplate', '', this);">
                                                                    <i class="fa fa-search"></i>
                                                                </button>
                                                            </span>
                                                            <span class="input-group-btn">
                                                                <input type="text" id="activityKeyId_nameField" required="required" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" value="<?php echo $this->getRowActivityKey['DESCRIPTION']; ?>" title="" placeholder="<?php echo $this->lang->line('name_search'); ?>" data-activityKeyId="0" data-processid="0" data-lookupid="" data-lookuptypeid="">
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="templateNextButton">
                                                    <input type='hidden' id='maxDimension' value='<?php echo $this->getRowActivityKey['MAX_DIMENION']; ?>'>
                                                    <input type='hidden' id='minDimension' value='<?php echo $this->getRowActivityKey['MIN_DIMENION']; ?>'>
                                                </div>
                                            </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row mt10">
                        <div class="col-md-12">
                            <div class="btn-group btn-group-devided">
                                <div class="btn-group mr5">
                                    <button class="btn btn-success btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown" onclick="amactivityObj.loadButtonsTemplate();" aria-expanded="false"><i class="icon-plus3 font-size-12"></i> Нэмэх</button>
                                </div>       
                                <?php
                                echo Html::anchor(
                                    'javascript:;', '<i class="fa fa-trash disabled"></i> Устгах', array(
                                    'class' => 'btn btn-danger btn-circle btn-sm',
                                    'title' => '',
                                    'onclick' => 'amactivityObj.deleteActivity(\'template\');'
                                ));
                                echo Html::anchor(
                                    'javascript:;', '<i class="fa fa-trash disabled"></i> Темплейт устгах', array(
                                    'class' => 'btn btn-danger btn-circle btn-sm',
                                    'title' => '',
                                    'onclick' => 'amactivityObj.deleteActivity(\'template-delete\');'
                                ));
                                echo Html::anchor(
                                    'javascript:;', '<i class="fa fa-trash disabled"></i> Данс тохируулах устгах', array(
                                    'class' => 'btn btn-danger btn-circle btn-sm',
                                    'title' => '',
                                    'onclick' => 'amactivityObj.deleteActivity(\'template-delete3\');'
                                ));
                                echo Html::anchor(
                                    'javascript:;', '<i class="fa fa-save"></i> Хадгалах', array(
                                    'class' => 'btn blue btn-circle btn-sm',
                                    'title' => '',
                                    'onclick' => 'amactivityObj.saveTemplate();'
                                ));     
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt10"></div>
                        <div class="col-md-12">
                            <div class="activity-expression-viewer" style="background-color: #FFDEA5; padding: 6px 10px;">
                                <i class="fa fa-calculator"></i> <span></span>
                            </div>
                        </div>                        
                        <div class="col-md-12 jeasyuiTheme3" id="dataGridDiv">
                            <table class="no-border mt0" id="objectdatagrid_<?php echo $this->activityKeyId ?>" style="width: 100%; "></table>
                        </div> 
                    </div>
                    <div class="form-actions mt15 form-actions-btn">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <span id="fieldSpan" class="float-left" style="font-weight: bold !important;"></span><span id="fieldExpressionSpan" class="float-left"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>        
        </div>
</div>
<style>
    .activity-expression-viewer-class {
        z-index: 100;
        position: fixed;
        top: 65px;        
        -webkit-box-shadow: 0 3px 9px -4px black;
        -moz-box-shadow: 0 3px 9px -4px black;
        box-shadow: 0 3px 9px -4px black;
    }
    .customLabel {
        color: #444;
        
        font-size: 12px !important;
        font-weight: 400;
    }    
    .tooltip-inner {
        max-width: 550px;
    }    
    .datagrid-htable td span {
        white-space: normal !important;
    }    
</style>
<script type="text/javascript">        
    var activityKeyId = '<?php echo $this->activityKeyId; ?>', amactivityObj;
    
    $.getScript('middleware/assets/js/amactivity_oop.js', function() {
        amactivityObj = new Amactivity(activityKeyId);
        amactivityObj.initEventListenerTemplate();

        if(activityKeyId !== '0000009'){
            amactivityObj.loadDataGridTemplate();        
        }
    });
    
    function selectedActivityFunction(metaDataCode, chooseType, elem, rows) {
        amactivityObj.insertActivityRow(metaDataCode, rows, elem);
    };    
    
    function selectedActivityTemplateFunction(metaDataCode, chooseType, elem, rows) {
        amactivityObj.insertActivityRowTemplate(metaDataCode, rows, elem);
    };    
    
    function activitySelectabledGridTemplate(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        amactivityObj.activityKeyId = row.id;
        amactivityObj.loadDataGridTemplate();
        amactivityObj.loadDimensionTemplate(row);
    };    
    
    function activitySelectabledGridTemplate2(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        amactivityObj.loadDimensionTemplate2(row);
    };    

    function activitySelectabledGridTemplate4(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        amactivityObj.accountConfigTemplate(row);
    };    
    
    function dataGridFormatterGeneral(value, row, index){
        if(typeof value === 'undefined' || value === null)
            return '';
        return pureNumberFormat(value);
    };
    
    function dataGridTextFormatterGeneral(value, row, index){
        if(typeof value === 'undefined' || value === null)
            return '';
        return '<span title="' + value + '" class="">' + value + '</span>';
    };    
    
    function dataGridFormatterDescription(value, row, index){
        if(typeof value === 'undefined' || value === null)
            return '';        
        var templateId = '';
        if (typeof row.templateid !== 'undefined') {
            templateId = row.templateid;
        }
        return '<div class="input-group"><a href="javascript:;" onclick="dataViewCustomSelectableGrid(\'AM_ACTIVITY_KEY_TEMPLATE_LIST\', \'single\', \'activitySelectabledGridTemplate2\', \'\', this);"><input type="hidden" name="activityKeyId" value="' + templateId + '"><span title="' + value + '" class="">' + replaceAll(value, "#", "&nbsp;&nbsp;&nbsp;&nbsp") + '</span></a></div>';
    };    
    
    function dataGridFormatterExpenseAccount(value, row, index){
        if(typeof value === 'undefined' || value === null)
            value = "";
        
        return '<div class=" quick-item" style="width: 102%">'
                    + '<input type="text" name="expenseAccountQuickCode" class="expenseAccountQuickCode expenseAccountQuickCode_'+ row.id +' form-control accountCodeMask form-control-sm" value="' + value + '" style="padding-left:4px; border: none;" placeholder="Кодоор хайх" autocomplete="off">'
                + '</div>';
    };    
    
    function dataGridFormatterRevenueAccount(value, row, index){
        if(typeof value === 'undefined' || value === null)
            value = "";
        
        return '<div class=" quick-item" style="width: 102%">'
                    + '<input type="text" name="revenueAccountQuickCode" class="revenueAccountQuickCode revenueAccountQuickCode_'+ row.id +' form-control accountCodeMask form-control-sm" value="' + value + '" style="padding-left:4px; border: none;" placeholder="Кодоор хайх" autocomplete="off">'
                + '</div>';
    };    
    
    function dataGridFormatterReceivableAccount(value, row, index){
        if(typeof value === 'undefined' || value === null)
            value = "";
        
        return '<div class=" quick-item" style="width: 102%">'
                    + '<input type="text" name="receivableAccountQuickCode" class="receivableAccountQuickCode receivableAccountQuickCode_'+ row.id +' form-control accountCodeMask form-control-sm" value="' + value + '" style="padding-left:4px; border: none;" placeholder="Кодоор хайх" autocomplete="off">'
                + '</div>';
    };    
    
    function dataGridFormatterFact(value, row, index){
//        if(typeof value === 'undefined' || value === null)
//            value = "";
        
        return '<div onclick="amactivityObj.viewerExpressionTemplate(this);"><a class="btn btn-warning btn-sm" title="Томъёо оруулах" onclick="amactivityObj.insertFormExpressionTemplate(this);" href="javascript:;" style="width: 20px; border-radius: 0px; padding-left: 3px; padding-top: 0px; padding-bottom: 0px;"><i class="fa fa-calculator"></i></a></div>';
    };    
    
    function replaceAll(str, find, replace) {
        var i = str.indexOf(find);
        if (i > -1) {
            str = str.replace(find, replace); 
            i = i + replace.length;
            var st2 = str.substring(i);
            if(st2.indexOf(find) > -1){
                str = str.substring(0 , i) + replaceAll(st2, find, replace);
            }       
        }
        return str;
    }        
</script>
