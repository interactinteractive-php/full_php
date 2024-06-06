<div class="w-100 bg-white p-3 eedit">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'saveform_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data','action' => 'javascript:void(0)')); ?>
        <input type="hidden" class="" name="name" value="<?php echo $this->data['name']; ?>">
        <input type="hidden" name="mainData" value="<?php echo $this->mainData; ?>">
        <input type="hidden" name="startTime" value="<?php echo $this->data['starttime']; ?>">
        <input type="hidden" name="endTime" value="<?php echo $this->data['endtime']; ?>">

        <div class="top-background v2">
            <div class="row">
                <div class="col-9">
                    <span class="first text-uppercase">Эхэлсэн:</span> <span class="second"><input type="text" class="timeInit" id="startTime" value="<?php echo $this->data['starttime']; ?>"></span>
                    <span class="first text-uppercase">Завсарласан:</span> <span class="second"><input type="text" class="timeInits" name="totalBreakTime" value="<?php echo $this->data['totalbreaktime']; ?>"></span>
                    <span class="first text-uppercase">Үргэлжилсэн:</span> <span class="second"><input type="text" class="timeInits" id="duration" name="duration" value="<?php echo $this->data['duration']; ?>"></span>
                    <span class="first text-uppercase">Дууссан:</span> <span class="second"><input type="text" class="timeInit" id="endTime" onchange="calculateTime();" value="<?php echo $this->data['endtime']; ?>"></span>
                    <span class="first text-uppercase">Ирцийн хувь:</span> <span class="second"><input type="text" class="" name="percent" value="<?php echo $this->data['percentofattendance']; ?>"></span>
                </div>
                <div class="col-3 text-right">
                    <button type="button" class="btn btn-sm btn-circle hide btn-success mr5 bp-btn-saveedit" onclick="runAutoEditBusinessProcess(this, '1559809605021332', '1573441697300548', true);" data-dm-id="1559809605021332"><i class="fa fa-pencil"></i> Хадгалаад засах</button>
                    <button type="button" class="btn btn-sm btn-circle btn-success saveBtn_<?php echo $this->uniqId ?> bp-btn-save " data-status="none" ><i class="icon-checkmark-circle2"></i> Хадгалах</button>
                    <button type="button" class="btn btn-sm btn-circle purple-plum ml5 saveBtn_<?php echo $this->uniqId ?>" data-status="saveprint"><i class="fa fa-print"></i> Хадгалаад хэвлэх</button>
                    <button type="button" class="btn btn-sm btn-circle green ml5 bp-btn-print " data-row="<?php echo htmlentities(json_encode(array('param' => $this->selectedRow)), ENT_QUOTES, 'UTF-8') ?>" id="printReportProcess_<?php echo $this->uniqId ?>" onclick="processPrintPreviewCust_<?php echo $this->uniqId ?>(this, '1570442015252', '21573124899715');"><i class="fa fa-print"></i> Тайлан хэвлэх</button>
                </div>
            </div>
        </div> 
        <div class="row justify-content-center mt-2 mb-2">
            <div style="width:600px;">
                <center><img src="assets/custom/img/mgl-soyombo.png" height="50" class="mb-1"></center>
                <h5 class="text-uppercase text-center mb-0" style="color:#2b3d87;"><?php echo $this->data['namecode']; ?></h5>
            </div>
        </div>
        <div class="mb-4">
            <h5 class="mb-0 ml-1">Хуралдаанд оролцогчид</h5>
            <div class="table-responsive" id="task-list">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>Дугаар</th>
                            <th>ИТХ-Н ТӨЛӨӨЛӨГЧ</th>
                            <th><span class="mr70">Ирсэн</span><span>Завсарлаад ирсэн</span></th>
                            <th><span class="mr13">Чөлөө авч явсан</span><span>Ирсэн</span></th>
                            <th><span class="mr13">Орхин гарсан</span><span>Ирсэн</span></th>
                            <th>Тайлбар</th>
                            <th>Төлөв</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($this->data['cms_meeting_participant_dv']) && $this->data['cms_meeting_participant_dv']) {
                            $num = 0;
                            foreach ($this->data['cms_meeting_participant_dv'] as $key => $participant) {
                                $num++;
                                ?>

                                <tr>
                                    <td class="text-blue pl25"><?php echo $num; ?></td>
                                    <td class="membername">
                                        <input type="hidden" class="" name="employeeKeyId[]"  value="<?php echo $participant['employeekeyid']; ?>">
                                        <input type="hidden" class="" name="id[]"  value="<?php echo $participant['id']; ?>">
                                        <input type="hidden" class="" name="bookId[]"  value="<?php echo $participant['bookid']; ?>">
                                        <input type="hidden" class="" name="participantRoleId[]"  value="<?php echo $participant['participantroleid']; ?>">
                                        <input type="hidden" class="" name="orderNum[]"  value="<?php echo $participant['ordernum']; ?>">
                                        <?php echo $participant['employeename']; ?>  
                                    </td>
                                    <td>
                                        <input type="text" class="timeInit" name="time1[]" value="<?php echo $participant['time1']; ?>">
                                        <input type="text" class="timeInit" name="time9[]" value="<?php echo $participant['time9']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="timeInit" name="time2[]" value="<?php echo $participant['time2']; ?>">
                                        <input type="text" class="timeInit" name="time3[]" value="<?php echo $participant['time3']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="timeInit" name="time4[]" value="<?php echo $participant['time4']; ?>">
                                        <input type="text" class="timeInit" name="time5[]" value="<?php echo $participant['time5']; ?>">
                                    </td>
                                    <td class="desc">
                                        <textarea rows="4" cols="50" name="wfmDescription[]" value="<?php echo $participant['wfmdescription']; ?>"><?php echo $participant['wfmdescription']; ?></textarea>
                                    </td>
                                    <td>
                                        <select class="slct" name="wfmStatusId[]">
                                            <?php
                                            if (isset($this->statusOption) && $this->statusOption) {
                                                foreach ($this->statusOption as $key => $options) {
                                                    ?>
                                                    <option value="<?php echo $options['id'] ?>" <?php if ($participant['wfmstatusid'] == $options['id']) {
                                        echo 'selected';
                                    } ?>><?php echo $options['wfmstatusname'] ?></option>
                                    <?php }
                                } ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <h5 class="mb-0 ml-1">Бусад оролцогчид</h5>
            <div class="table-responsive" id="task-list">
                <table class="table table-striped table-borderless">
                    <thead>
                        <tr>
                            <th>Дугаар</th>
                            <th>ИТХ-Н ТӨЛӨӨЛӨГЧ</th>
                            <th><span class="mr70">Ирсэн</span><span>Завсарлаад ирсэн</span></th>
                            <th><span class="mr13">Чөлөө авч явсан</span><span>Ирсэн</span></th>
                            <th><span class="mr13">Орхин гарсан</span><span>Ирсэн</span></th>
                            <th>Тайлбар</th>
                            <th>Төлөв</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($this->data['cms_other_6_get_list']) && $this->data['cms_other_6_get_list']) {
                        $num = 0;
                        foreach ($this->data['cms_other_6_get_list'] as $key => $other) {
                            $num++;
                            ?>
                                <tr>
                                    <td class="text-blue pl25"><?php echo $num ?></td>
                                    <td class="membername">
                                        <input type="hidden" class="" name="otheremployeeKeyId[]"  value="<?php echo $other['employeekeyid']; ?>">
                                        <input type="hidden" class="" name="otherId[]"  value="<?php echo $other['id']; ?>">
                                        <input type="hidden" class="" name="otherbookId[]"  value="<?php echo $other['bookid']; ?>">
                                        <input type="hidden" class="" name="otherparticipantRoleId[]"  value="<?php echo $other['participantroleid']; ?>">
                                        <input type="hidden" class="" name="otherorderNum[]"  value="<?php echo $other['ordernum']; ?>">
                                        <?php echo $other['employeename']; ?>
                                    </td>
                                    <td>
                                        <input type="text" class="timeInit" name="othertime1[]" value="<?php echo $other['time1']; ?>">
                                        <input type="text" class="timeInit" name="othertime9[]" value="<?php echo $other['time9']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="timeInit" name="othertime2[]" value="<?php echo $other['time2']; ?>">
                                        <input type="text" class="timeInit" name="othertime3[]" value="<?php echo $other['time3']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="timeInit" name="othertime4[]" value="<?php echo $other['time4']; ?>">
                                        <input type="text" class="timeInit" name="othertime5[]" value="<?php echo $other['time5']; ?>">
                                    </td>
                                    <td class="desc">
                                        <textarea rows="4" cols="50" name="otherwfmDescription[]" value="<?php echo $other['wfmdescription']; ?>"><?php echo $other['wfmdescription']; ?></textarea>
                                    </td>
                                    <td>
                                        <select class="slct" name="otherwfmStatusId[]">
                                <?php
                                if (isset($this->statusOption) && $this->statusOption) {
                                    foreach ($this->statusOption as $key => $options) { ?>
                                        <option value="<?php echo $options['id'] ?>" <?php echo ($other['wfmstatusid'] == $options['id']) ? 'selected' : '' ?>><?php echo $options['wfmstatusname'] ?></option>
                                    <?php }
                                } ?>
                                        </select>
                                    </td>
                                </tr>
                        <?php }
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="task-modal-detail-22" class="modal fade task-list-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo $row['taskname']; ?></h5>
                        <button type="button" class="close" data-dismiss="modal"><i class="icon-cross2 font-size-base"></i></button>
                    </div>
                    <div class="modal-body p-0"></div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" data-dismiss="modal">Хаах</button>
                    </div>
                </div>
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>
<script type="text/javascript">

    $(function () {
        Core.initAjax($("#saveform_<?php echo $this->uniqId ?>"));
    });
    
    $('body').on('click', '.saveBtn_<?php echo $this->uniqId ?>', function () {
        var $this = $(this);
        $('#saveform_<?php echo $this->uniqId ?>').ajaxSubmit({
            type: 'post',
            url: 'government/saveAttendanceProcess',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true,
                    message: 'Түр хүлээнэ үү'
                });
            },
            success: function (responseData) {
                PNotify.removeAll();
                new PNotify({
                    title: responseData.status,
                    text: responseData.text,
                    type: responseData.status,
                    sticker: false
                });

                if (responseData.status === 'success') {
                    if (typeof $this.attr('data-status') !== 'undefined' && $this.attr('data-status') === 'saveprint') {
                        $('#printReportProcess_<?php echo $this->uniqId ?>').trigger('click');
                    } else {
                        editGovernmentAttendance(<?php echo json_encode($this->selectedRow) ?>);
                    }
                }
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    });

    function processPrintPreviewCust_<?php echo $this->uniqId ?>(element, methodId, rowId) {
        var $row = JSON.parse($(element).attr('data-row'));
        processPrintPreview(element, methodId, rowId, '', $row);
    }
    
    function print(mainMetaDataId, isDialog, whereFrom, elem, isOneRow) {
        
        if (typeof isOneRow !== 'undefined' && isOneRow) {
            var _datagridRowIndex = $(elem).closest('tr').attr('datagrid-row-index');
            var getRows = getDataViewSelectedRows(mainMetaDataId);
            var rows = [];
            rows[0] = typeof getRows[_datagridRowIndex] === 'undefined' ? getRows[0] : getRows[_datagridRowIndex];
        } else {
            var rows = getDataViewSelectedRows(mainMetaDataId);
        }
        
        if (rows.length === 0) {
            alert(plang.get('msg_pls_list_select'));
            return;
        }
        
        var $dialogName = 'dialog-printSettings';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdtemplate/checkCriteria',
            data: {metaDataId: '1576737704882', dataRow: rows, isProcess: false},
            dataType: "json",
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(response) {
                
                PNotify.removeAll();
                
                if (typeof response.isSettingsDialog !== 'undefined' && response.isSettingsDialog === '1') {
                    var print_options = {
                        numberOfCopies: response.numberOfCopies,
                        isPrintNewPage: response.isPrintNewPage,
                        isSettingsDialog: response.isSettingsDialog,
                        isShowPreview: response.isShowPreview,
                        isPrintPageBottom: response.isPrintPageBottom,
                        isPrintPageRight: response.isPrintPageRight,
                        pageOrientation: response.pageOrientation,
                        isPrintSaveTemplate: response.isPrintSaveTemplate,
                        paperInput: response.paperInput,
                        pageSize: response.pageSize,
                        printType: response.printType,
                        templates: response.templates, 
                        templateIds: response.templateIds 
                    }; 
                    if (response.numberOfCopies != '' && response.numberOfCopies != '0' && response.templates != null) {
                        callTemplate(rows, '1576737704882', print_options);
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Warning',
                            text: 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү',
                            type: 'warning',
                            addclass: pnotifyPosition,
                            sticker: false
                        });
                    }                    
                } else {
                    $dialog.empty().append(response.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: plang.get('MET_99990001'),
                        width: 500, 
                        minWidth: 400,
                        height: "auto",
                        maxHeight: $(window).height() - 25, 
                        modal: false,
                        close: function(){
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {text: plang.get('preview_btn'), class: 'btn btn-sm blue', click: function() {
                                var numberOfCopies = $("#numberOfCopies").val(),
                                    isPrintNewPage = $("#isPrintNewPage").is(':checked') ? '1' : '0',
                                    isSettingsDialog = $("#isSettingsDialog").is(':checked') ? '1' : '0',
                                    isShowPreview = $("#isShowPreview").is(':checked') ? '1' : '0',
                                    isPrintPageBottom = $("#isPrintPageBottom").is(':checked') ? '1' : '0',
                                    isPrintPageRight = $("#isPrintPageRight").is(':checked') ? '1' : '0',
                                    isPrintSaveTemplate = $("#isPrintSaveTemplate").is(':checked') ? '1' : '0',
                                    pageOrientation = $("#pageOrientation").val(),
                                    paperInput = $("#paperInput").val(),
                                    pageSize = $("#pageSize").val(),
                                    templates = $("#printTemplate").val(),
                                    templateIds = $("#rtTemplateIds").val(), 
                                    printType = $("#printType").val();
                                var print_options = {
                                    numberOfCopies: numberOfCopies,
                                    isPrintNewPage: isPrintNewPage,
                                    isSettingsDialog: isSettingsDialog,
                                    isShowPreview: isShowPreview,
                                    isPrintPageBottom: isPrintPageBottom,
                                    isPrintPageRight: isPrintPageRight,
                                    pageOrientation: pageOrientation,
                                    isPrintSaveTemplate: isPrintSaveTemplate,
                                    paperInput: paperInput,
                                    pageSize: pageSize,
                                    printType: printType,
                                    templates: templates, 
                                    templateIds: templateIds 
                                }; 
                                if (numberOfCopies != '' && numberOfCopies != '0' && templateIds) {
                                    if (print_options.templates == '') {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: 'Warning',
                                            text: 'Загвараа сонгоно уу!',
                                            type: 'warning',
                                            sticker: false
                                        });  
                                        return;              
                                    }
                                    $dialog.dialog('close');
                                    callTemplate(rows, '1576737704882', print_options);
                                } else {
                                    PNotify.removeAll();
                                    new PNotify({
                                        title: 'Warning',
                                        text: plang.getDefault('PRINT_0019', 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү'),
                                        type: 'warning',
                                        addclass: pnotifyPosition,
                                        sticker: false
                                    });
                                }
                            }},
                            {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    if ($dialog.find("#rtTemplateIds").val().length === 0) {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Warning',
                            text: 'Загвараа сонгоно уу!',
                            type: 'warning',
                            sticker: false
                        });                      
                        $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:eq(0)').prop('disabled', true);
                    }
                    $dialog.on('change', '#printTemplate', function(){
                        if ($dialog.find("#printTemplate").val().length === 0) {
                            $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:eq(0)').prop('disabled', true);
                        } else {
                            $dialog.closest('.ui-dialog').find('.ui-dialog-buttonpane').find('button:eq(0)').prop('disabled', false);
                        }
                    });
                    $dialog.dialog('open');
                }
                Core.unblockUI();
            }
        }).done(function() {
            Core.initDVAjax($dialog);
        });
    }
    
    function calculateTime() {
        var stime = $("#startTimeDistrict").val();
        var etime = $("#endTime").val();
        
        $("#duration").val(diff(stime, etime) + ':00');
    }
    
    function diff(start, end) {
        start = start.split(":");
        end = end.split(":");
        var startDate = new Date(0, 0, 0, start[0], start[1], 0);
        var endDate = new Date(0, 0, 0, end[0], end[1], 0);
        var diff = endDate.getTime() - startDate.getTime();
        var hours = Math.floor(diff / 1000 / 60 / 60);
        diff -= hours * 1000 * 60 * 60;
        var minutes = Math.floor(diff / 1000 / 60);

        return (hours < 9 ? "0" : "") + hours + ":" + (minutes < 9 ? "0" : "") + minutes;
    }

    
</script>