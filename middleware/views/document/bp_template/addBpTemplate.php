<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-template-form', 'method' => 'post')); ?>
<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#addbptemp_tab_1" class="nav-link active" data-toggle="tab">Үндсэн</a>
        </li>
        <li class="nav-item">
            <a href="#addbptemp_tab_2" data-toggle="tab" class="nav-link">Загвар угсрах</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="addbptemp_tab_1">
            <div class="col-md-12 xs-form">
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Код', 'for' => 'templateCode', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
                    <div class="col-md-8">
                        <?php echo Form::text(array('name' => 'templateCode', 'id' => 'templateCode', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
                    </div>
                </div>
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Нэр', 'for' => 'templateName', 'class' => 'col-form-label col-md-3', 'required'=>'required')); ?>
                    <div class="col-md-8">
                        <?php echo Form::text(array('name' => 'templateName', 'id' => 'templateName', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
                    </div>
                </div>
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Бүлэг', 'for' => 'folderId', 'class' => 'col-form-label col-md-3')); ?>
                    <div class="col-md-8">
                        <?php 
                        echo Form::select(
                            array(
                                'name' => 'folderId', 
                                'class' => 'form-control select2 form-control-sm',
                                'id' => 'folderId', 
                                'data' => $this->folderList,
                                'op_value' => 'FOLDER_ID', 
                                'op_text' => 'FOLDER_CODE| |-| |FOLDER_NAME'
                            )
                        ); 
                        ?>
                    </div>
                </div>
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Идэвхитэй эсэх', 'for' => 'isActive', 'class' => 'col-form-label col-md-3')); ?>
                    <div class="col-md-8">
                        <?php echo Form::checkbox(array('name' => 'isActive', 'id' => 'isActive', 'value'=>'1', 'checked'=>'checked')); ?>
                    </div>
                </div>
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Default эсэх', 'for' => 'isDefault', 'class' => 'col-form-label col-md-3')); ?>
                    <div class="col-md-8">
                        <?php echo Form::checkbox(array('name' => 'isDefault', 'id' => 'isDefault', 'value'=>'1')); ?>
                    </div>
                </div>
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Процесс сонгох', 'for' => 'metaDataId_displayField', 'required'=>'required', 'class' => 'col-form-label col-md-3')); ?>
                    <div class="col-md-8">
                        <div class="meta-autocomplete-wrap" data-section-path="metaDataId">
                            <div class="input-group double-between-input">
                                <input id="metaDataId_valueField" name="processId" class="popupInit" data-path="metaDataId" type="hidden">
                                <input autocomplete="off" id="metaDataId_displayField" name="metaDataId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" required="required" placeholder="кодоор хайх" data-metadataid="0" data-processid="0" data-lookupid="1466671920664499" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">
                                <span class="input-group-btn">
                                    <button type="button" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('processMetaDataLists', 'single', 'chooseBpMeta', '', this);"><i class="fa fa-search"></i></button>
                                </span>     
                                <span class="input-group-btn">
                                    <input id="metaDataId_nameField" name="metaDataId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" required="required" placeholder="нэрээр хайх" data-metadataid="0" data-processid="0" data-lookupid="1466671920664499" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">      
                                </span>     
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row fom-row mt20">
                    <?php echo Form::label(array('text' => 'Нэмэлтүүд', 'class' => 'col-form-label col-md-3')); ?>
                    <div class="col-md-8">
                        <div class="checkbox-list">
                            <label>
                                <input type="checkbox" name="templateWidget[]" value="idcard"> Иргэний цахим үнэмлэх
                            </label>
                            <label>
                                <input type="checkbox" name="templateWidget[]" value="attach"> Хавсралт баримт бичиг
                            </label>
                            <label>
                                <input type="checkbox" name="templateWidget[]" value="realestate"> Үл хөдлөх хөрөнгө
                            </label>
                            <label>
                                <input type="checkbox" name="templateWidget[]" value="billprint"> Талон хэвлэх
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="addbptemp_tab_2">
            <div class="row columns">
                <div class="col-md-9">  
                    <div class="editor">
                        <textarea name="tempEditor" id="tempEditor"></textarea>
                    </div>
                </div>
                <div class="col-md-3">  
                    <div class="report-tags" style="max-height: 550px; overflow: auto">
                        <p class="meta-title">Талбарууд</p>
                        <ul id="metas">
                            
                        </ul>
                        <p class="consts-title">Тогтмолууд</p>
                        <ul id="constants">
                            <?php
                            foreach ($this->sysKeywords as $sysKeyword) {
                                if ($sysKeyword['KEY_TYPE'] == 'sys' || $sysKeyword['KEY_TYPE'] == 'session') {
                            ?>
                            <li class="pl10" title="<?php echo $sysKeyword['META_DATA_NAME']; ?>">#<?php echo $sysKeyword['META_DATA_CODE']; ?>#</li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                        <p class="consts-title">Тохиргооны утгууд</p>
                        <ul id="configvalues">
                            <?php
                            foreach ($this->sysKeywords as $configValueKeyword) {
                                if ($configValueKeyword['KEY_TYPE'] == 'config') {
                            ?>
                            <li class="pl10" title="<?php echo $configValueKeyword['META_DATA_NAME']; ?>">#<?php echo $configValueKeyword['META_DATA_CODE']; ?>#</li>
                            <?php
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window, .moxman-window").length) {
        e.stopImmediatePropagation();
    }
});
                     
$(function(){
    $(".report-tags").on("dblclick", "li", function(){
        tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).clone().find('a').remove().end().text());
    });
    
    $("table.table-params-tags").on("dblclick", "tr", function(){
        tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).clone().find('td:eq(1)').find('a').remove().end().text());
    });
    
    $('html, body').animate({scrollTop: 0}, 0);
    
    $('a[href="#addbptemp_tab_2"]').on('shown.bs.tab', function(e){
        var _form = $(this).closest('form');
        
        if (_form.find('#metaDataId_valueField').val() == '') {
            alert('Процесс сонгоно уу!');
            
        } else {
            $.ajax({
                type: 'post',
                url: 'mddoc/addBpTemplateParamList',
                data: {processMetaDataId: _form.find('#metaDataId_valueField').val()},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $("table.table-params-tags > tbody").empty().html(data);
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
        }
    });
});

function chooseBpMeta(metaDataCode, chooseType, elem, rows) {
    var row = rows[0];
    var $parent = $(elem).closest('.meta-autocomplete-wrap');
    $parent.find('#metaDataId_valueField').val(row.id);
    $parent.find('#metaDataId_displayField').val(row.code);
    $parent.find('#metaDataId_nameField').val(row.name);
}    
</script>