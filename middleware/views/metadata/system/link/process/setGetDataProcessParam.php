<div class="row-fluid" id="window-dataModelProcess">
    <div class="table-scrollable">
        <table class="table table-hover table-small-header-text" id="getdata-process-dtl">
            <thead>
                <tr>
                    <th class="text-center">Param code</th>
                    <th class="text-center" style="width: 40%;">Default value</th>
                    <th style="width: 50px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($this->paramDtl) {
                    foreach ($this->paramDtl as $row) {
                ?>
                <tr>
                    <td class="text-center middle">
                        <?php 
                        echo Form::select(
                            array_merge($this->paramComboArr, 
                                array(
                                    'value' => $row['PARAM_CODE']
                                )
                            )
                        ); 
                        ?>
                    </td>
                    <td class="text-center middle">
                        <?php echo Form::text(array('name' => 'getDataProcessDefaultValue[]', 'value' => $row['DEFAULT_VALUE'], 'class' => 'form-control form-control-sm')); ?>
                    </td>
                    <td class="text-center middle">
                        <a href="javascript:;" class="btn red btn-xs" onclick="removeGetDataParamDtl(this);"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <a href="javascript:;" class="btn green btn-xs" onclick="addGetDataParamDtl(this);">
                            <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                        </a>
                    </td>
                </tr>
            </tfoot>
        </table>  
    </div> 
    </div>
</div>
<input type="hidden" name="saveGetDataProcessParam" value="1">

<script type="text/javascript">
function removeGetDataParamDtl(elem) {
    var $parentRow = $(elem).closest("tr");
    $parentRow.remove();
}
function addGetDataParamDtl(elem) {
    $("table#getdata-process-dtl tbody").append('<tr>' +
        '<td><?php echo Form::select($this->paramComboArr); ?></td>' +
        '<td class="text-center middle">' +
        '<input type="text" name="getDataProcessDefaultValue[]" class="form-control form-control-sm">' +
        '</td>' +
        '<td class="text-center middle">' +
        '<a href="javascript:;" class="btn red btn-xs" onclick="removeGetDataParamDtl(this);"><i class="fa fa-trash"></i></a>' +
        '</td>' +
    '</tr>');
    Core.initSelect2($("table#getdata-process-dtl > tbody > tr:last"));
}
</script>