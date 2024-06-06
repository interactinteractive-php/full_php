<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'excelTempImport-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="col-md-12 xs-form">
    
    <?php
    if (isset($this->paramList) && $this->paramList) {
        
        foreach ($this->paramList as $param) {
            
            if ($param['IS_SHOW'] == '1') { 
                
                $labelAttr = array('text' => $this->lang->line($param['META_DATA_NAME']), 'for' => 'param['.$param['PARAM_REAL_PATH'].']', 'class' => 'col-form-label col-md-3 line-height-normal pt3');
                
                if ($param['IS_REQUIRED'] == '1') {
                    $labelAttr['required'] = 'required';
                }
                
                $label = Form::label($labelAttr);
                $control = Mdwebservice::renderParamControl($this->additionalParametersProcessId, $param, 'param['.$param['PARAM_REAL_PATH'].']', $param['PARAM_REAL_PATH'], null);
                
                echo html_tag('div', array('class' => 'form-group row mb-2'), 
                    $label . html_tag('div', array('class' => 'col-md-9'), $control)
                );
            }
        }
    }
    ?>
    
    <div class="form-group row">
        <?php echo Form::label(array('text' => Lang::lineDefault('translation_2565849952233445', 'Загвар'), 'for' => 'templateId', 'class' => 'col-form-label col-md-3 line-height-normal pt3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php 
            echo Form::select(
                array(
                    'name' => 'templateId', 
                    'id' => 'excelTemplateId', 
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->templateList, 
                    'required' => 'required', 
                    'op_value' => 'ID', 
                    'op_text' => 'CODE| |-| |NAME', 
                    'value' => $this->templateId 
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row mt15 excel-temp-download" style="display: none">
        <?php echo Form::label(array('text' => Lang::lineDefault('translation_25658499522334453433565665', 'Эксель загвар'), 'class' => 'col-form-label col-md-3 line-height-normal pt3')); ?>
        <div class="col-md-9 mt2">
            <a href="javascript:;" id="excel-temp-download-link"><i class="fa fa-download"></i> <?php echo Lang::lineDefault('download_btn', 'Татах'); ?></a>
        </div>
    </div>
    <div class="form-group row mt15">
        <?php echo Form::label(array('text' => Lang::lineDefault('translation_256584995223344534', 'Эксель файл сонгох'), 'for' => 'excelFile', 'class' => 'col-form-label col-md-3 line-height-normal pt3', 'required' => 'required')); ?>
        <div class="col-md-9">
            <?php echo Form::file(array('name' => 'excelFile', 'id' => 'excelFile', 'class'=>'form-control form-control-sm fileInit', 'required' => 'required', 'data-valid-extension' => 'xls, xlsx, csv', 'accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel')); ?>
        </div>
    </div>
    <div class="form-group row mt15">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <label for="isReturnSuccessRows">
                <?php echo Form::checkbox(array('name' => 'isReturnSuccessRows', 'id' => 'isReturnSuccessRows', 'class'=>'form-control form-control-sm', 'value' => '1', 'saved_val' => $this->isReturnSuccessRows)); ?> <?php echo Lang::lineDefault('translation_2565849952', 'Амжилттай импорт хийгдсэн мөрийг буцаах'); ?>
            </label>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <label for="isSaveWhenAllRowSuccessful">
                <?php echo Form::checkbox(array('name' => 'isSaveWhenAllRowSuccessful', 'id' => 'isSaveWhenAllRowSuccessful', 'class'=>'form-control form-control-sm', 'value' => '1', 'saved_val' => $this->isSaveWhenAllRowSuccessful)); ?> <?php echo Lang::lineDefault('translation_2565849952569', 'Бүх мөрийг амжилттай үед импортлох'); ?>
            </label>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
excelTemplateDownloadLinkVisible();

$(function(){
    $('#excelTemplateId').on('change', function(){
        excelTemplateDownloadLinkVisible();
    });
    $('a#excel-temp-download-link').on('click', function(){ 
        var excelTemplateId = $('#excelTemplateId').val();
        
        $.fileDownload(URL_APP + 'mddatamodel/base64Download', {
            httpMethod: 'post',
            data: {
                vId: excelTemplateId, 
                vTable: 'imp_excel_template'
            }
        }).done(function() {
            Core.unblockUI();
        }).fail(function(response){
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: response,
                type: 'error',
                sticker: false
            });
            Core.unblockUI();
        });
    });
});    
function excelTemplateDownloadLinkVisible() {
    var excelTemplateId = $('#excelTemplateId').val();
        
    if (excelTemplateId != '') {
        $('.excel-temp-download').show();
    } else {
        $('.excel-temp-download').hide();
    }
}
</script>