<form id="multiChangeBalanceForm">
    <div class="panel panel-default bg-inverse grid-row-content">
        <table class="table sheetTable">
            <tbody>
                <tr>
                    <td style="width: 150px;" class="left-padding"><?php echo Form::label(array('text'=>'Ирсэн цаг', 'for'=>'Ирсэн цаг', 'required' => true));?></td>
                    <td style="width: 50px;" colspan="<?php echo Config::getFromCache('CONFIG_TNA_HISHIGARVIN') ? '2' : '' ?>">
                        <input class="inTime timeInit <?php echo (!$this->isStartTimeEdit) ? 'readonly-inTime' : '' ?>" data-name="inTime" name="inTime" placeholder="hh:mm" <?php echo (!$this->isStartTimeEdit) ? 'readonly="readonly"' : '' ?> type="text" value="<?php echo isset($this->params[0]['IN_TIME']) ? $this->params[0]['IN_TIME'] : '' ?>">
                    </td>
                    <td style="width:190px;" class="<?php echo Config::getFromCache('CONFIG_TNA_HISHIGARVIN') ? 'hide' : '' ?>">
                        <button type="button" id="" class="btn btn-sm employeeBalanceDescriptionTime float-right mt5" <?php echo (!$this->isStartTimeEdit) ? 'disabled="disabled"' : '' ?> title="Тайлбар"><i class="fa fa-font"></i> </button>
                        <input type="hidden" name="descriptionIn" value=""/>
                    </td>
                </tr>
                <tr>
                    <td style="width: 150px;"class="left-padding"><?php echo Form::label(array('text'=>'Явсан цаг', 'for'=>'Явсан цаг', 'required' => true));?></td>
                    <td style="width: 50px;" colspan="<?php echo Config::getFromCache('CONFIG_TNA_HISHIGARVIN') ? '2' : '' ?>">
                        <input class="outTime timeInit <?php echo (!$this->isEndTimeEdit) ? 'readonly-inTime' : '' ?>" data-name="outTime" name="outTime" placeholder="hh:mm" <?php echo (!$this->isEndTimeEdit) ? 'readonly="readonly"' : '' ?> type="text" value="<?php echo isset($this->params[0]['OUT_TIME']) ? $this->params[0]['OUT_TIME'] : '' ?>">
                    </td>
                    <td style="width:190px;" class="<?php echo Config::getFromCache('CONFIG_TNA_HISHIGARVIN') ? 'hide' : '' ?>">
                        <div class="checkbox-list" style=" width: 100px !important; height: 31px; float: left; margin:0px !important; ">
                            <?php
                            echo Form::checkbox(
                                array(
                                    'name' => 'isAddonDate',
                                    'id' => 'isAddonDate',
                                    'class' => 'isAddonCheck',
                                    'value' => '1',
                                )
                            );
                            ?>
                            <label class="checkbox-inline addonRequiredLabel mt0" style="display: none;">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'addonDate',
                                        'id' => 'addonDate',
                                        'class' => 'longInit',
                                        'value' => '1',
                                        'width' => '50px;',
                                        'length' => '2'
                                    )
                                );
                                ?>
                            </label>
                        </div>
                        <input type="hidden" name="descriptionOut" value=""/>
                        <button type="button" id="" <?php echo (!$this->isEndTimeEdit) ? 'disabled="disabled"' : '' ?> class="btn btn-sm employeeBalanceDescriptionTime float-right mt5" title="Тайлбар"><i class="fa fa-font"></i> </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <input name="clearTime" data-name="clearTime" type="hidden" value="0">
    <input name="unclearTime" data-name="unclearTime" type="hidden" value="0">
    <input name="defferenceTime" data-name="defferenceTime" type="hidden" value="0">
    <input name="originalDefferenceTime" data-name="originalDefferenceTime" type="hidden" value="0">
    <input name="faultType" data-name="faultType" type="hidden" value="0">
    <input name="nightTime" data-name="nightTime" type="hidden" value="0">
<?php if (defined('TNA_MCAA') && TNA_MCAA === true) { ?> 
    <div class="panel panel-default bg-inverse grid-row-content" style="margin-bottom: 0 !important;">
            <table class="table sheetTable">
                <tbody>
                    <?php 
                        $html = '';
                        $btn = '';
                        foreach ($this->tnaCauseType as $k=>$row) {
                            $color = "#000";
                            $causeTypeName = mb_strtoupper($row['NAME'], 'UTF-8');

                            $btnDesc = '<button type="button" id="" class="btn btn-sm employeeBalanceDescriptionTime" '; 
                                if ($this->isLock)
                                    $btnDesc .= ' disabled="disabled" ';
                            $btnDesc .= ' title="Тайлбар"><i class="fa fa-font"></i> </button>';

                            $html .= '<tr>';
                                $html .= '<td style="width: 150px; color:' . $color . ' !important;" class="left-padding"><label>' . $causeTypeName . '</label></td>';
                                $html .= '<td style="width: 50px;">';
                                    $html .= '<input name="cause_type_id[]" data-name="cause_type_id" type="hidden" value="' . $row['CAUSE_TYPE_ID'] . '">';
                                    $html .= '<input name="cause_type_value[]" data-name="cause_type_value" type="hidden" value="0">';
                                    $html .= '<input class="cause_type_value_display timeInit" data-name="cause_type_value_display" ';
                                        if ($this->isLock)
                                            $html .= ' readonly="readonly" ';
                                        $html .= ' name="cause_type_value_display[]" placeholder="hh:mm" type="text" value="" onchange="setMinut(this); calculateTotal(this);">';
                                $html .= '</td>';
                                $html .= '<td style="width: 190px;"><input type="hidden" name="description[]" value=""><div class="btn-group float-right">' . $btn . $btnDesc . '</div></td>';
                            $html .= '</tr>';
                        }
                        echo $html;
                    ?>
                </tbody>
            </table>
        </div>
<?php } ?>
</form>
<style type="text/css">
    #uniform-isAddonDate > span {
        margin-top: 7px;
    }
    .uniform-isAddonDate > span {
        margin-top: 0px !important;
    }
</style>
<script type="text/javascript">
    $('.isAddonCheck').click(function() {
        var $this = $(this),
            $addonRequiredLabel = $this.closest('.checkbox-list').find('.addonRequiredLabel');
        if(!$this.closest('span').hasClass('checked')) {
            $addonRequiredLabel.show();
            $('#uniform-isAddonDate').addClass('uniform-isAddonDate');
        } else {
            $('#uniform-isAddonDate').removeClass('uniform-isAddonDate');
            $addonRequiredLabel.hide();
            $addonRequiredLabel.find('input[type=checkbox]').attr('checked', false);
            $addonRequiredLabel.find('input[type=checkbox]').closest('span').removeClass('checked');
        }
    });
</script>