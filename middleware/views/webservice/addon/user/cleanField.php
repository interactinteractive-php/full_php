<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal mb0', 'id' => 'bpCleanFieldConfig-form', 'method' => 'post')); ?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-sm table-bordered table-hover mb0" id="bp-clean-field">
            <thead>
                <tr>
                    <th style="vertical-align: middle; width: 25px">№</th>
                    <th style="vertical-align: middle">Нэр</th>
                    <th style="width: 40px" class="text-center">
                        <label>
                            <input type="checkbox" class="cf-column-check-all"> 
                        </label>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($this->paths as $param) {
                    
                    $paramRealPath = $param['PARAM_PATH'];
                    $checked = '';
                    $saved = '1';
                    
                    if (isset($this->userConfig[$paramRealPath])) {
                        $checked = ' checked="checked"';
                        $saved = '0';
                    }
                ?>
                <tr>
                    <td class="text-center"><?php echo $i; ?></td>
                    <td><?php echo $this->lang->line($param['LABEL_NAME']); ?></td>
                    <td class="text-center">
                        <input type="hidden" name="userConfigHidden[<?php echo $paramRealPath; ?>]" value="<?php echo $saved; ?>">
                        <input type="checkbox" value="1"<?php echo $checked; ?>/>
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
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function() {
    $('.cf-column-check-all').on('click', function() {
        var $this = $(this);
        var $outputParamTable = $this.closest('table');
        var outputParamCol = $this.closest('tr').children().index($this.closest('th'));
        var outputParamIndex = outputParamCol + 1;
        
        if ($this.is(':checked')) {
            $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").attr('checked', false);
        }
        $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").click();
        $.uniform.update();
    });
    $('#bp-clean-field > tbody').on('click', 'input[type=checkbox]', function() {
        var $this = $(this);
        
        if ($this.is(':checked')) {
            $this.closest('td').find('input[type=hidden]').val('0');
        } else {
            $this.closest('td').find('input[type=hidden]').val('1');
        }
    });
});
</script>

