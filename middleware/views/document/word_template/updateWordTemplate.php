<div id="window-word-template-edit">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-update-template-form', 'method' => 'post')); ?>
    <div class="col-md-12 xs-form">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => '<strong>НЭР</strong>', 'for' => 'templateName', 'class' => 'col-form-label col-md-3', 'style' => 'margin-top: 13px;')); ?>
            <div class="col-md-6">
                <?php echo Form::text(array('name' => 'templateName', 'id' => 'templateName', 'readonly' => 'readonly', 'class' => 'form-control form-control-sm', 'value' => $this->row['TEMPLATE_NAME'], 'style' => 'color:#000; border:4px #1BBC9B solid; font-weight:bold; font-size: 15px !important; height: 40px;')); ?>
            </div>
        </div>        
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Код', 'for' => 'templateCode', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-6">
                <?php echo Form::text(array('name' => 'templateCode', 'id' => 'templateCode', 'readonly' => 'readonly', 'class' => 'form-control form-control-sm', 'value' => $this->row['TEMPLATE_CODE'])); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Word Path', 'for' => 'templateCode', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-6">
                <?php echo Form::text(array('readonly' => 'readonly', 'class' => 'form-control form-control-sm', 'value' => $this->row['PHYSICAL_PATH'])); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'HTML Path', 'for' => 'templateCode', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-6">
                <?php echo Form::text(array('readonly' => 'readonly', 'class' => 'form-control form-control-sm', 'value' => $this->row['HTML_FILE_PATH'])); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Үйлчилгээ', 'for' => 'metaDataId_displayField', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-6">
                <div class="meta-autocomplete-wrap" data-section-path="metaDataId">
                    <div class="input-group double-between-input">
                        <input id="metaDataId_valueField" name="serviceId" class="popupInit" data-path="metaDataId" value="<?php echo $this->row['ITEM_ID']; ?>" type="hidden" value="">
                        <input autocomplete="off" id="metaDataId_displayField" readonly = 'readonly' name="metaDataId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" required="required" placeholder="кодоор хайх" data-metadataid="0" data-processid="0" data-lookupid="1466671920664499" data-lookuptypeid="200101010000016" data-field-name="metaDataId" value="<?php echo $this->row['ITEM_CODE']; ?>" type="text">
                        <span class="input-group-btn">
                            <button type="button" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('ntrItemList', 'single', 'chooseNotaryEditBpMeta', '', this);"><i class="fa fa-search"></i></button>
                        </span>     
                        <span class="input-group-btn">
                            <input id="metaDataId_nameField" name="metaDataId_nameField" readonly = 'readonly' class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" value="<?php echo $this->row['ITEM_NAME']; ?>" required="required" placeholder="нэрээр хайх" data-metadataid="0" data-processid="0" data-lookupid="1466671920664499" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">      
                        </span>     
                    </div>
                </div>
            </div>
        </div>                
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'MS Ворд темплейт', 'for' => 'templateWordFile', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-6">
                <?php echo Form::file(array('name' => 'templateWordFile', 'id' => 'templateWordFile', 'class' => 'form-control-sm')); ?>
            </div>
        </div>
        <input type="hidden" name="templateId" value="<?php echo $this->row['ID']; ?>">
    </div>
    <?php echo Form::close(); ?>
</div>

<script type="text/javascript">
    function chooseNotaryEditBpMeta(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest('.meta-autocomplete-wrap');
        _parent.find('#metaDataId_valueField').val(row.itemid);
        _parent.find('#metaDataId_displayField').val(row.itemcode);
        _parent.find('#metaDataId_nameField').val(row.itemname);
    }   
</script>