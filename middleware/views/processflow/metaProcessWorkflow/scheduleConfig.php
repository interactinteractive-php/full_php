<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'bp-scheduleconfig-form', 'method' => 'post')); ?>
<?php echo Form::hidden(array('name' => 'mainBpId', 'value' => $this->mainBpId)); ?>
<?php echo Form::hidden(array('name' => 'doProcessId', 'value' => $this->doProcessId)); ?>
<div class="col-md-12">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding" for="isScheduled"><?php echo $this->lang->line('isScheduled') ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                            echo Form::checkbox(
                                    array(
                                        'name' => 'isScheduled',
                                        'id' => 'isScheduled',
                                        'value' => '1',
                                        'saved_val' => $this->row['IS_SCHEDULED']
                                    )
                            );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding" for="scheduleDatePath"><?php echo $this->lang->line('scheduleDatePath') ?>:</td>
                <td colspan="2">
                    <?php
                        echo Form::text(
                                array(
                                    'name' => 'scheduleDatePath',
                                    'id' => 'scheduleDatePath',
                                    'class' => 'form-control textInit', 
                                    'value' => $this->row['SCHEDULED_DATE_PATH']
                                )
                        );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php echo Form::close(); ?>