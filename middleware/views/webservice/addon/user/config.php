<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal mb0', 'id' => 'bpDetailConfig-form', 'method' => 'post')); ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover mb0">
            <thead>
                <tr>
                    <th style="vertical-align: middle; width: 25px">№</th>
                    <th style="vertical-align: middle">Нэр</th>
                    <th style="width: 90px" class="text-center">
                        <label>
                            <input type="checkbox" class="dv-column-check-all"> 
                            Харах эсэх
                        </label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($this->detailColumns as $param) {
                    
                    $paramRealPath = strtolower($param['PARAM_REAL_PATH']);
                    $checked = '';
                    
                    if (!$this->userConfig) {
                        $checked = ' checked="checked"';
                    } else {
                        if (isset($this->userConfig[$paramRealPath]) && $this->userConfig[$paramRealPath] == '1') {
                            $checked = ' checked="checked"';
                        }
                    }
                ?>
                <tr>
                    <td class="text-center"><?php echo $i; ?></td>
                    <td><?php echo $this->lang->line($param['LABEL_NAME']); ?></td>
                    <td class="text-center">
                        <input type="hidden" name="userConfigHidden[<?php echo $param['PARAM_REAL_PATH']; ?>]" value="1">
                        <input type="checkbox" name="userConfig[<?php echo $param['PARAM_REAL_PATH']; ?>]" value="1"<?php echo $checked; ?>/>
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
<input type="hidden" name="metaDataId" value="<?php echo $this->metaDataId; ?>"/>
<input type="hidden" name="groupPath" value="<?php echo $this->groupPath; ?>"/>
<input type="hidden" name="groupId" value="<?php echo $this->parentId; ?>"/>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function() {
    $(".dv-column-check-all").on("click", function() {
        var $this = $(this);
        var $outputParamTable = $this.closest("table");
        var outputParamCol = $this.closest("tr").children().index($this.closest("th"));
        var outputParamIndex = outputParamCol + 1;
        
        if ($this.is(":checked")) {
            $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").attr("checked", false);
        }
        $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").click();
        $.uniform.update();
    });
});
</script>

