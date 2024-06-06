<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class'=>'form-horizontal form-middle mb0', 'id'=>'advancedConfig-form-'.$this->metaDataId, 'method'=>'post')); ?>
<input type="hidden" name="metaDataId" value="<?php echo $this->metaDataId; ?>"/>
<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1 mb10" id="config-list-data-<?php echo $this->metaDataId ?>">
            <thead id="header1">
                <tr>
                    <th class="rowNumber" style="width: 32px">№</th>
                    <th><?php echo $this->lang->line('META_00125'); ?></th>
                    <th class="text-center">
                        <label>
                            <input type="checkbox" class="dv-column-check-all"> 
                            Харах эсэх
                        </label>
                    </th>
                    <th class="text-center">Freeze эсэх</th>
                    <th class="text-center">Шүүлт эсэх</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($this->dataViewColumnDataFields as $param) {
                ?>
                <tr id="config-<?php echo $param['ID']; ?>" style="display: table-row; cursor: move;">
                    <td class="ordernumber-<?php echo $param['ID']; ?> dragHandle"><?php echo $i; ?></td>
                    <td class="left header-name-dbl-click">
                        <span><?php echo ($param['SIDEBAR_NAME'] != '' ? $this->lang->line($param['SIDEBAR_NAME']).' / ' : '').$this->lang->line($param['LABEL_NAME']); ?></span> 
                        <input type="text" name="headerName[]" class="form-control form-control-sm display-none" value="<?php echo $param['HEADER_NAME']; ?>"/>
                    </td>
                    <td class="text-center">
                        <input type="hidden" name="CONFIG_ORDER[]" id="order-<?php echo $param['ID']; ?>" value="<?php echo $i; ?>"/>
                        <input type="hidden" name="GROUP_CONFIG_ID[]" value="<?php echo $param['ID']; ?>"/>
                        <input type="hidden" name="FIELD_PATH[]" value="<?php echo $param['FIELD_PATH']; ?>"/>
                        <input type="hidden" name="IS_SHOW[]" id="show-<?php echo $param['ID']; ?>" value="<?php echo (!$this->isUserConfig) ? '1' : $param['IS_SHOW']; ?>"/>
                        <input type="hidden" name="IS_FREEZE[]" id="freeze-<?php echo $param['ID']; ?>" class="isFreeze-<?php echo $this->metaDataId ?>" value="<?php echo $param['IS_FREEZE']; ?>"/>
                        <input type="hidden" name="IS_CRITERIA[]" id="criteria-<?php echo $param['ID']; ?>" class="isCriteria-<?php echo $this->metaDataId ?>" value="<?php echo $param['IS_CRITERIA']; ?>"/>
                        <input type="checkbox" <?php echo (!$this->isUserConfig) ? 'checked' : (($param['IS_SHOW'] == '1') ? 'checked' : '') ?> onclick="isShow_<?php echo $this->metaDataId ?>(this)" id="<?php echo $param['ID']; ?>" />
                    </td>
                    <td class="text-center">
                        <input type="checkbox" <?php echo ($param['IS_FREEZE'] == '1') ? 'checked' : '' ?> onclick="isFreeze_<?php echo $this->metaDataId ?>(this)" class="checkFreeze-<?php echo $this->metaDataId ?>" id="<?php echo $param['ID']; ?>" />
                    </td>
                    <td class="text-center">
                        <input type="checkbox" <?php echo ($param['IS_CRITERIA'] == '1') ? 'checked' : '' ?> onclick="isCriteria<?php echo $this->metaDataId ?>(this)" class="checkCriteria-<?php echo $this->metaDataId ?>" id="<?php echo $param['ID']; ?>" />
                    </td>
                </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>    
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function() {
    $("#config-list-data-<?php echo $this->metaDataId ?> tbody tr").hover(function() { 
        $(this.cells[0]).addClass('showDragHandle');
    }, function() {
        $(this.cells[0]).removeClass('showDragHandle');
    }); 
    
    $("#config-list-data-<?php echo $this->metaDataId ?> tbody").tableDnD({
        onDragClass: "rowHighlight", 
        dragHandle: ".dragHandle", 
        onDrop: function(table, row) {
            var orders = $.tableDnD.serialize();
            var order = orders.split('[]=config-');
            var number = 1;

            $.each(order, function(i, dtl) {
                if (dtl.length != 0) {
                    
                    var num = dtl.split('&');

                    $('.ordernumber-'+num[0]).html(number);
                    $('#order-'+num[0]).val(number);

                    number++;
                }
            });
        }
    });
    
    $('.header-name-dbl-click').on('dblclick', function(){
        var _this = $(this);
        if (_this.find('input').hasClass('display-none')) {
            _this.find('span').hide();
            _this.find('input').removeClass('display-none').val(_this.find('span').text());
        }
    });
    
    $(".dv-column-check-all").on("click", function() {
        var $this = $(this);
        var $outputParamTable = $this.closest("table");
        var outputParamCol = $this.closest("tr").children().index($this.closest("th"));
        var outputParamIndex = outputParamCol + 1;
        
        if ($this.is(":checked")) {
            $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").prop("checked", false);
        }
        $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").click();
        $.uniform.update();
    });
});
  
function isShow_<?php echo $this->metaDataId ?>(element) {
    var ischecked = element.checked
    var thisid = $(element).attr('id');
    $('#show-'+thisid).val('0');

    if (ischecked) {
        $('#show-'+thisid).val('1');
    }
}
function isFreeze_<?php echo $this->metaDataId ?>(element) {
    var ischecked = element.checked
    var thisid = $(element).attr('id');
    $('#freeze-'+thisid).val('0');
    $('.checkFreeze-<?php echo $this->metaDataId ?>').attr('checked', false);

    $('.checkFreeze-<?php echo $this->metaDataId ?>').parent().removeClass('checked');
    $('.isFreeze-<?php echo $this->metaDataId ?>').val(0);

    if (ischecked) {
        $('#freeze-'+thisid).val('1');
        $(element).attr('checked', 'checked');
        $(element).parent().addClass('checked');
    }
}
function isCriteria<?php echo $this->metaDataId ?>(element) {
    var ischecked = element.checked
    var thisid = $(element).attr('id');
    $('#criteria-'+thisid).val('0');

    if (ischecked) {
        $('#criteria-'+thisid).val('1');
    }
}
</script>

