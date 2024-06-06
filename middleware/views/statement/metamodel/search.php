<div class="col-md-12 xs-form" id="model-statement-search-<?php echo $this->metaDataId; ?>">
    <fieldset class="collapsible mt10 mb10">
        <legend>Шүүлт</legend>
        <form class="form-horizontal p-2" method="post" id="metamodel-search-form">
            <div class="row">    
                <?php
                if ($this->metaModelSearchData) {
                    foreach ($this->metaModelSearchData as $param) {
                ?>
                <div class="col-md-4">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text'=>$this->lang->line($param['META_DATA_NAME']),'for'=>'param['.$param['META_DATA_CODE'].']','class'=>'col-form-label col-md-4')); ?>
                        <div class="col-md-8">
                            <?php 
                            echo Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $this->metaDataId, $param['META_DATA_CODE'], false); 
                            ?>
                        </div>
                    </div>    
                </div>    
                <?php
                    }
                }
                ?>
                <div class="clearfix w-100"></div>
            </div>
            <div class="row">
                <div class="col-md-12 text-right">
                    <?php 
                    echo Form::button(
                        array(
                            'class' => 'btn btn-sm btn-circle blue-madison metamodel-statement-filter-btn', 
                            'value' => '<i class="fa fa-search"></i> Шүүх'
                        )
                    ); 
                    ?>
                    <?php 
                    echo Form::button(
                        array(
                            'class' => 'btn btn-sm btn-circle default metamodel-statement-filter-reset-btn', 
                            'value' => 'Цэвэрлэх'
                        )
                    ); 
                    ?>
                </div>    
            </div>  
            <?php echo Form::hidden(array('name' => 'modelId', 'value' => $this->modelId)); ?>
            <?php echo Form::hidden(array('name' => 'statementId', 'value' => $this->metaDataId)); ?>
        </form>        
    </fieldset>
</div>
<div class="clearfix w-100"></div>

<script type="text/javascript">
$(function(){

    $("button.metamodel-statement-filter-btn", "#model-statement-search-<?php echo $this->metaDataId; ?>").on("click", function() {
        var _this = $(this);
        $.ajax({
            type: 'post',
            url: 'mdstatement/renderMetaModelByFilter',
            data: _this.closest("form#metamodel-search-form").serialize(), 
            dataType: "json", 
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                PNotify.removeAll();
                
                if (data.status == 'success') {
                    var statementWindow_<?php echo $this->metaDataId; ?> = 'div#statement-area-<?php echo $this->metaDataId; ?>';
                    $("div.report-preview-print", statementWindow_<?php echo $this->metaDataId; ?>).empty().append(data.htmlData);
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });
    $("button.metamodel-statement-filter-reset-btn", "#model-statement-search-<?php echo $this->metaDataId; ?>").on("click", function() {
        var _this = $(this);
        var _thisForm = _this.closest("form#metamodel-search-form");

        _thisForm.find("input[type=text], input[type=hidden], select").not("input[name='modelId'], input[name='statementId']").val("");
        _thisForm.find("select.select2").select2("val", "");
    });
});
</script>