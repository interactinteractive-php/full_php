<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-template-form', 'method' => 'post')); ?>
    <div class="col-md-12 xs-form">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Процесс сонгох', 'for' => 'metaDataId_displayField', 'required'=>'required', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-8">
                <div class="meta-autocomplete-wrap" data-section-path="metaDataId">
                    <div class="input-group double-between-input">
                        <input id="metaDataId_valueField" name="processId" value="<?php echo $this->row['META_DATA_ID']; ?>" class="popupInit" data-path="metaDataId" type="hidden">
                        <input autocomplete="off" id="metaDataId_displayField" value="<?php echo $this->row['META_DATA_CODE']; ?>" name="metaDataId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" required="required" placeholder="кодоор хайх" data-metadataid="0" data-processid="0" data-lookupid="1466671920664499" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">
                        <span class="input-group-btn">
                            <button type="button" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('processMetaDataLists', 'single', 'chooseNotaryBpMeta', '', this);"><i class="fa fa-search"></i></button>
                        </span>     
                        <span class="input-group-btn">
                            <input id="metaDataId_nameField" name="metaDataId_nameField" value="<?php echo $this->row['META_DATA_NAME']; ?>" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" required="required" placeholder="нэрээр хайх" data-metadataid="0" data-processid="0" data-lookupid="1466671920664499" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">
                        </span>     
                    </div>
                </div>
                <input type="hidden" name="templateId" value="<?php echo $this->temlapteId; ?>">
            </div>
        </div>
    </div>
<?php echo Form::close(); ?>

<script type="text/javascript">
    function chooseNotaryBpMeta(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var _parent = $(elem).closest('.meta-autocomplete-wrap');
        _parent.find('#metaDataId_valueField').val(row.id);
        _parent.find('#metaDataId_displayField').val(row.code);
        _parent.find('#metaDataId_nameField').val(row.name);
    }
</script>