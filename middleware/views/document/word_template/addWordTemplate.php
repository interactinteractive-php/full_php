<div id="window-word-template-<?php echo $this->uniqId ?>">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'appbp-template-form', 'method' => 'post')); ?>
    <div class="col-md-12 xs-form">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => '<strong>НЭР</strong>', 'for' => 'templateName', 'class' => 'col-form-label col-md-3', 'required' => 'required', 'style' => 'margin-top: 13px;')); ?>
            <div class="col-md-6">
                <?php echo Form::text(array('name' => 'templateName', 'id' => 'templateName', 'class' => 'form-control form-control-sm', 'required' => 'required', 'style' => 'color:#000; border:4px #1BBC9B solid; font-weight:bold; font-size: 15px !important; height: 40px;')); ?>
                <?php echo Form::hidden(array('name' => 'folderId', 'id' => 'folderId', 'value' => $this->filterFolderId)); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Код', 'for' => 'templateCode', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-6">
                <?php echo Form::text(array('name' => 'templateCode', 'id' => 'templateCode', 'class' => 'form-control form-control-sm', 'required' => 'required')); ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Үйлчилгээ', 'for' => 'metaDataId_displayField', 'required' => 'required', 'class' => 'col-form-label col-md-3')); ?>
            <div class="col-md-6">
                <div class="meta-autocomplete-wrap" data-section-path="metaDataId">
                    <div class="input-group double-between-input">
                        <input id="metaDataId_valueField" name="serviceId" class="popupInit" data-path="metaDataId" type="hidden">
                        <input autocomplete="off" id="metaDataId_displayField" name="metaDataId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete ui-autocomplete-input" required="required" placeholder="кодоор хайх" data-metadataid="0" data-processid="0" data-lookupid="1490902053189120" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">
                        <span class="input-group-btn">
                            <button type="button" id="searchCalcTypeButton" class="btn default btn-bordered form-control-sm mr0 searchCalcTypeButton" onclick="dataViewCustomSelectableGrid('ntrItemList', 'single', 'chooseNotaryBpMeta', '', this);"><i class="fa fa-search"></i></button>
                        </span>     
                        <span class="input-group-btn">
                            <input id="metaDataId_nameField" name="metaDataId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" required="required" placeholder="нэрээр хайх" data-metadataid="0" data-processid="0" data-lookupid="1490902053189120" data-lookuptypeid="200101010000016" data-field-name="metaDataId" type="text">      
                        </span>     
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'MS Ворд темплейт', 'for' => 'templateWordFile', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-6">
                <?php echo Form::file(array('name' => 'templateWordFile', 'id' => 'templateWordFile', 'class' => 'form-control-sm', 'required' => 'required')); ?>
            </div>
        </div>
    </div>
    <?php 
    echo Form::hidden(array('name' => 'processId', 'value' => $this->processId)); 
    echo Form::close(); 
    ?>
</div>

<script type="text/javascript">
    $(function() {
        setLookupPopupValue($('#window-word-template-<?php echo $this->uniqId ?>').find('[name="serviceId"]'), '<?php echo $this->serviceId; ?>');
    });
    
    function chooseNotaryBpMeta(metaDataCode, chooseType, elem, rows) {
        var row = rows[0];
        var $parent = $(elem).closest('.meta-autocomplete-wrap');
        $parent.find('#metaDataId_valueField').val(row.itemid);
        $parent.find('#metaDataId_displayField').val(row.itemcode);
        $parent.find('#metaDataId_nameField').val(row.itemname);
    }   
</script>