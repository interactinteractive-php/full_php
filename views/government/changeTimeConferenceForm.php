<div class="row">
    <div class="col-md-12 addtrows-<?php echo $this->uniqId ?>">
        <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'saveConferenceTime_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data', 'style' => '')); ?>
        <input type="hidden" name="id" value='<?php echo $this->dataRow['id'] ?>' />
        <div class="row">
            <div class="col-md-8">
                <button type="button" data-action-path="CMS_SUBJECT_TIME_DV" class="btn btn-xs green-meadow float-left mr5 bp-add-one-row" onclick="addRow<?php echo $this->uniqId ?>(this);"><i class="icon-plus3 font-size-12"></i></button>
                <div class="clearfix w-100"></div>
            </div>
            <div class="col-md-4 text-right"></div>
        </div>
        <?php if ($this->dataRow['cms_subject_time_dv']) {
            foreach ($this->dataRow['cms_subject_time_dv'] as $key => $row) { ?>
                <div class="col-md-12 timeChange_<?php echo $this->uniqId ?> ">
                    <table class="table table-sm table-no-bordered bp-header-param" style="width:<?php echo $this->width-50 ?>px;">
                        <input type="hidden" name="timeId[]" value='<?php echo $row['id'] ?>' />
                        <input type="hidden" name="rowState[]" data-path="rowState" value='' />
                        <tbody>
                            <tr data-cell-path="startTime">
                                <td class="text-right middle" data-cell-path="startTime" style="width: 40%"> 
                                    <label for="startTime" data-label-path="startTime">Эхлэх цаг:</label>
                                </td>
                                <td class="middle" data-cell-path="startTime" style="width: 60%" colspan="">
                                    <div data-section-path="startTime" class="dateElement">
                                        <input name="startTime[]" class="form-control form-control-sm timeInits" data-path="startTime" data-field-name="startTime" value="<?php echo Date::format('H:i:s', $row['starttime']) ?>"/>
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr data-cell-path="endTime">
                                <td class="text-right middle" data-cell-path="endTime" style="width: 40%">
                                    <label for="endTime" data-label-path="endTime">Дуусах цаг:</label>
                                </td>
                                <td class="middle" data-cell-path="endTime" style="width: 60%" colspan="">
                                    <div data-section-path="endTime" class="dateElement">
                                        <input name="endTime[]" class="form-control form-control-sm timeInits" data-path="endTime" data-field-name="endTime" value="<?php echo Date::format('H:i:s', $row['endtime']) ?>"/>
                                    </div>
                                </td>
                                <td style="width: 66px" class="text-center stretchInput float-left middle">
                                    <button type="button" data-action-path="CMS_SUBJECT_TIME_DV" class="btn btn-xs green-meadow float-left mr5 bp-add-one-row" onclick="addRow<?php echo $this->uniqId ?>(this);"><i class="icon-plus3 font-size-12"></i></button>
                                    <a href="javascript:;" class="btn red btn-xs remove-row-<?php echo $this->uniqId ?> pull-right mr15" title="Устгах"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php }
        } ?>
        <?php echo Form::close(); ?>
    </div>
</div>
<div class="d-none d-none-<?php echo $this->uniqId ?>">
    <div class="col-md-12 timeChange_<?php echo $this->uniqId ?>">
        <table class="table table-sm table-no-bordered bp-header-param" style="width:<?php echo $this->width-50 ?>px;">
            <input type="hidden" name="timeId[]" value='' />
            <input type="hidden" name="rowState[]" data-path="rowState"  value='ADDED' />
            <tbody>
                <tr data-cell-path="startTime">
                    <td class="text-right middle" data-cell-path="startTime" style="width: 40%"> 
                        <label for="startTime" data-label-path="startTime">Эхлэх цаг:</label>
                    </td>
                    <td class="middle" data-cell-path="startTime" style="width: 60%" colspan="">
                        <div data-section-path="startTime" class="dateElement">
                            <input name="startTime[]" class="form-control form-control-sm timeInits" data-path="startTime" data-field-name="startTime" value=""/>
                        </div>
                    </td>
                    <td></td>
                </tr>
                <tr data-cell-path="endTime">
                    <td class="text-right middle" data-cell-path="endTime" style="width: 40%">
                        <label for="endTime" data-label-path="endTime">Дуусах цаг:</label>
                    </td>
                    <td class="middle" data-cell-path="endTime" style="width: 60%" colspan="">
                        <div data-section-path="endTime" class="dateElement">
                            <input name="endTime[]" class="form-control form-control-sm timeInits" data-path="endTime" data-field-name="endTime" value=""/>
                        </div>
                    </td>
                    <td style="width: 66px" class="text-center stretchInput float-left middle">
                        <button type="button" data-action-path="CMS_SUBJECT_TIME_DV" class="btn btn-xs green-meadow float-left mr5 bp-add-one-row" onclick="addRow<?php echo $this->uniqId ?>(this);"><i class="icon-plus3 font-size-12"></i></button>
                        <a href="javascript:;" class="btn red btn-xs remove-row-<?php echo $this->uniqId ?> pull-right mr15" title="Устгах"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<style type="text/css">
    .timeChange_<?php echo $this->uniqId ?> {
        border-bottom: 1px solid #CCC;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }
</style>
<script type="text/javascript">
    $(function () {
        $('#saveConferenceTime_<?php echo $this->uniqId ?>').find('button[data-action-path="CMS_SUBJECT_TIME_DV"]').hide();
        $('#saveConferenceTime_<?php echo $this->uniqId ?>').find('button[data-action-path="CMS_SUBJECT_TIME_DV"]').last().show();
    });
    function addRow<?php echo $this->uniqId ?>(element) {
        var $html = $('.d-none-<?php echo $this->uniqId ?>').html();
        $(element).closest('#saveConferenceTime_<?php echo $this->uniqId ?>').find('button[data-action-path="CMS_SUBJECT_TIME_DV"]').hide();
        $(element).closest('#saveConferenceTime_<?php echo $this->uniqId ?>').append($html).promise().done(function () {
            $(this).find('button[data-action-path="CMS_SUBJECT_TIME_DV"]').hide();
            $(this).find('button[data-action-path="CMS_SUBJECT_TIME_DV"]').last().show();
            Core.initAjax($(this));
        });
    }
    
    $('#saveConferenceTime_<?php echo $this->uniqId ?>').on('click', '.remove-row-<?php echo $this->uniqId ?>', function () {
        $(this).closest('table').find('input[data-path="rowState"]').val('REMOVED');
        $(this).closest('.timeChange_<?php echo $this->uniqId ?>').addClass('d-none');
        
        
        if ($('#saveConferenceTime_<?php echo $this->uniqId ?>').find('.timeChange_<?php echo $this->uniqId ?>:not(".d-none")').length > 0) {
            $('#saveConferenceTime_<?php echo $this->uniqId ?>').find('.timeChange_<?php echo $this->uniqId ?>:not(".d-none")').last().find('button[data-action-path="CMS_SUBJECT_TIME_DV"]').show();
        } else {
            $('button[data-action-path="CMS_SUBJECT_TIME_DV"]').first().show();
        }
    });
</script>