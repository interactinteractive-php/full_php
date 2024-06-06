<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'copyMetaData-form', 'method' => 'post')); ?>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00023'), 'for' => 'metaDataCode', 'class'=>'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-5">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'metaDataCode', 
                        'name' => 'metaDataCode', 
                        'value' => $this->row['META_DATA_CODE'], 
                        'class' => 'form-control', 
                        'required' => 'required'
                    )
                ); 
                ?>
                <p class="form-text">Та үзүүлэлтийн кодыг өөрчлөнө үү.</p>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00114'), 'for' => 'metaDataName', 'class'=>'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-9">
                <?php 
                echo Form::text(
                    array(
                        'id' => 'metaDataName', 
                        'name' => 'metaDataName', 
                        'value' => $this->row['META_DATA_NAME'], 
                        'class' => 'form-control',
                        'required' => 'required'
                    )
                ); 
                ?>
                <p class="form-text">Та үзүүлэлтийн нэрийг өөрчлөнө үү.</p>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00024'), 'class'=>'col-form-label col-md-3', 'required' => 'required')); ?>
            <div class="col-md-9">
                <div class="meta-autocomplete-wrap">
                    <div class="input-group double-between-input">
                        <?php echo Form::hidden(array('name' => 'folderId', 'value' => Arr::get($this->folderRow, 'FOLDER_ID'))); ?>
                        <span class="input-group-btn">
                            <input id="_displayField" value="<?php echo Arr::get($this->folderRow, 'FOLDER_CODE'); ?>" class="form-control form-control-sm md-folder-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                        </span>   
                        <span class="input-group-btn" style="max-width: 40px">
                            <?php echo Form::button(array('class' => 'btn purple-plum', 'value' => '<i class="far fa-search"></i>', 'onclick' => 'commonFolderDataGrid(\'single\', \'\', \'chooseMetaFolderByCopy\', this);')); ?>
                        </span>
                        <span class="input-group-btn flex-col-group-btn">
                            <input id="_nameField" value="<?php echo Arr::get($this->folderRow, 'FOLDER_NAME'); ?>" class="form-control form-control-sm md-folder-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                        </span>     
                    </div>
                </div>  
            </div>
        </div>
    <?php 
    echo Form::hidden(array('name' => 'metaDataId', 'value'=>$this->row['META_DATA_ID'])); 
    echo Form::close(); 
    ?>
</div>

<script type="text/javascript">
    function chooseMetaFolderByCopy(chooseType, elem, params) {
        var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
        if (folderBasketNum > 0) {
            var rows = $('#commonBasketFolderGrid').datagrid('getRows');
            var row = rows[0];

            // $("input#folderName", "#copyMetaData-form").val(row.FOLDER_NAME);
            $("input[name='folderId']", "#copyMetaData-form").val(row.FOLDER_ID);
            $("input#_nameField", "#copyMetaData-form").val(row.FOLDER_NAME);
            $("input#_displayField", "#copyMetaData-form").val(row.FOLDER_CODE);
        }        
    } 
</script>