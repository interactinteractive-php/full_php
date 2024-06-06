<div class="card light bg-blue-hoki d-none" style="margin-bottom: 0">
    <div class="card-body">
        <div class="clearfix w-100">
            <a href="javascript:;" class="float-left thumb avatar border m-r">
                <img src="assets/core/global/img/user.png" class="rounded-circle" id="sidebar-user-logo" onerror="onUserImageError(this);">
            </a>
            <div class="clear">
                <div class="h4 mt5 mb5 text-color-white" style="font-size: 12px !important">
                    <div id="sidebar-user-name" style="margin-bottom: 2px;"></div>
                    <div id="sidebar-user-code" style="margin-bottom: 2px;"></div>
                    <div id="sidebar-user-status" style="margin-bottom: 2px;"></div>
                    <div id="sidebar-user-position" style="margin-bottom: 2px;"></div>
                    <div id="sidebar-user-date" style="margin-bottom: 2px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-default hide">
    <div class="panel-heading bg-blue-hoki">
        <h3 class="panel-title">Албан тушаалын төлөвлөгөө</h3>
    </div>
    <div class="panel-body">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 50%;"><label><i class="icon-plus3 font-size-12"></i> Нэмэх</label></td>
                    <td style="width: 50%;"><label><i class="fa fa-trash-o"></i> Устах</label></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><label><?php echo Form::checkbox(array('name' => 'isPositionWorkingDays', 'id' => 'isPositionWorkingDays', 'class' => 'form-control', 'value' => '0')); ?> А/Т төлөвлөгөө хуулах</label></td>
                    <td style="width: 50%;"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-heading bg-blue-hoki border-radius-0">
        <h3 class="panel-title">Төлөвлөгөө</h3>
    </div>
    <div class="panel-body">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Урт хугацаа</label></td>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Гараа</label></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Устгах</label></td>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Хуулах</label></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Экспорт</label></td>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Түгжих</label></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Тайлах</label></td>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Батлах</label></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><label><i class="fa fa-plug"></i> Цуцлах</label></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="panel-heading bg-blue-hoki border-radius-0">
        <h3 class="panel-title">Архив</h3>
    </div>
    <div class="panel-body">
        <table style="width: 100%;">
            <tbody>
                <tr>
                    <td style="width: 50%;"><label><i class="icon-plus3 font-size-12"></i> Үүсэх</label></td>
                    <td style="width: 50%;"><label><i class="fa fa-eye"></i> Харах</label></td>
                </tr>
                <tr>
                    <td style="width: 50%;"><label><i class="fa fa-refresh"></i> Сэргээх</label></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<h4 class="additional-panel hidden">Нэмэлт мэдээлэл</h4>
<div class="panel panel-default bg-inverse additional-panel" style="margin-bottom: 6px">
    <table class="table sheetTable">
        <tbody>
            <tr class="isVerifyBtn">
                <td style="width: 170px" class="left-padding">
                    <?php echo Form::label(array('text' => 'Ажлын өдрөөр эсэх', 'for' => '', 'class' => 'col-form-label')); ?>
                </td>
                <td>
                    <?php echo Form::checkbox(array('name' => 'isWorkingDays', 'id' => 'isWorkingDays', 'class' => 'form-control', 'value' => '0')); ?>
                </td>
            </tr>
            <tr class="isVerifyBtn">
                <td class="left-padding"><?php echo Form::label(array('text' => 'Устгах ганц/олон', 'for' => 'balanceVerify', 'class' => 'col-form-label')); ?></td>
                <td>
                    <div class="btn-group">
                        <?php echo Form::button(array('class' => 'btn btn-sm red balanceChoseDelete ml0 mr0', 'title' => 'Төлөвлөгөө устгах', 'value' => '<i class="fa fa-trash"></i> ')); ?>
                        <?php // echo Form::button(array('class' => 'btn btn-sm balanceDelete ml0 mr0', 'title' => 'Энэ сарын төлөвлөгөөг бүхэлд нь устгах', 'value' => '<i class="fa fa-trash"></i> ')); ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Export', 'for' => 'balanceVerify')); ?></td>
                <td>
                    <div class="">
                        <?php echo Form::button(array('class' => 'btn btn-sm purple-plum balanceExportExcel ml0 mr0', 'title' => 'Export excel', 'value' => '<i class="fa fa-file-excel-o"></i> ')); ?>
                    </div>
                </td>
            </tr>
            <?php
            if (isset($this->isAdmin) && $this->isAdmin) { ?>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Түгжих/Түгжээ тайлах', 'for' => 'Түгжих')); ?></td>
                <td>
                    <?php echo Form::button(array('class' => 'btn btn-sm blue-madison ml0 mr0 isLock', 'title' => 'Түгжих', 'value' => '<i class="fa fa-lock"></i> / <i class="fa fa-unlock-alt"></i>')); ?>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Урт хугацаагаар төлөвлөх', 'for' => 'Урт хугацаагаар төлөвлөх')); ?></td>
                <td>
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum ml0 mr0', 'title' => 'Урт хугацаагаар төлөвлөх', 'value' => '<i class="fa fa-calendar"></i>', 'onclick' => 'callCustomByMeta(\'' . $this->proc1 . '\', true);')); ?>
                </td>
            </tr>
            
            <?php //if (Config::getFromCache('tmsCustomerCode') !== 'gov') { ?>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Ээлжийн гараа төлөвлөх', 'for' => 'Ээлжийн гараа төлөвлөх')); ?></td>
                <td>
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum ml0 mr0', 'title' => 'Ээлжийн гараа төлөвлөх', 'value' => '<i class="fa fa-calendar"></i>', 'onclick' => 'callCustomByMeta(\'' . $this->proc2 . '\', true);')); ?>
                </td>
            </tr>
            <?php //} ?>
            <tr class="isVerifyBtn hidden">
                <td class="left-padding"><?php echo Form::label(array('text' => 'Шийдвэрлэлтийн төлөв', 'for' => 'balanceVerify')); ?></td>
                <td>
                    <div class="btn-group">
                        <?php echo isset($this->planBtn) && $this->planBtn['item'] ? $this->planBtn['item'] : ''; ?>
                    </div>
                </td>
            </tr>
            <tr class="isVerifyBtn hidden">
                <td style="width: 170px" class="left-padding">
                    <?php echo Form::label(array('text' => 'Албан тушаалын төлөвлөгөө хуулах', 'for' => 'isPositionWorkingDays', 'class' => 'col-form-label')); ?>
                </td>
                <td>
                    <?php echo Form::checkbox(array('name' => 'isPositionWorkingDays', 'id' => 'isPositionWorkingDays', 'class' => 'form-control', 'value' => '0')); ?>
                </td>
            </tr>
            <tr class="hidden">
                <td class="left-padding"><?php echo Form::label(array('text' => 'Төлөвлөгөө нэмэх', 'for' => 'Төлөвлөгөө нэмэх')); ?></td>
                <td>
                    <?php echo Form::button(array('class' => 'btn btn-sm btn-success ml0 mr0', 'title' => 'Түгжих', 'value' => '<i class="fa fa-steam-square"></i> Албан тушаал', 'onclick' => 'callCustomByMeta(1457087017597211, true);')); ?>
                </td>
            </tr>
            <tr class="isVerifyBtn hidden">
                <td class="left-padding"><?php echo Form::label(array('text' => 'Төлөвлөгөө хуулах', 'for' => 'copyPaste', 'class' => 'col-form-label')); ?></td>
                <td>
                    <div class="form-inline">
                        <div class="form-group row fom-row mb0">
                            <?php
                            echo Form::select(
                                    array(
                                        'name' => 'copy_planYear',
                                        'id' => 'copy_planYear',
                                        'class' => 'form-control select2 form-control-sm input-xxlarge',
                                        'data' => Info::getRefYearList(Date::currentDate('Y')),
                                        'op_value' => 'YEAR_CODE',
                                        'op_text' => 'YEAR_NAME',
                                        'value' => Date::currentDate('Y')
                                    )
                            );
                            ?>
                        </div>
                        <div class="form-group row fom-row mb0">
                            <?php
                            echo Form::select(
                                    array(
                                        'name' => 'copy_planMonth',
                                        'id' => 'copy_planMonth',
                                        'class' => 'form-control select2 form-control-sm input-xxlarge',
                                        'data' => Info::getRefMonthList(),
                                        'op_value' => 'MONTH_CODE',
                                        'op_text' => 'MONTH_NAME',
                                        'value' => 11
                                    )
                            );
                            ?>
                        </div>
                        <div class="form-group row fom-row mb0">
                            <div class="btn-group">
                                <?php echo Form::button(array('class' => 'btn btn-xs btn-success balancePaste ml0 mr0', 'title' => 'Хуулах', 'value' => '<i class="fa fa-paste"></i> ')); ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="HOLIDAY_NAME hidden">
                <td class="left-padding"><?php echo Form::label(array('text' => 'Баяр өдөр', 'for' => 'HOLIDAY_NAME')); ?></td>
                <td>
                    <span id="HOLIDAY_NAME"></span>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Архив', 'for' => 'Архив')); ?></td>
                <td>
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum ml0 mr0', 'title' => 'Үүсгэх', 'value' => '<i class="fa fa-archive"></i>', 'onclick' => 'sendArchiv(this, '. $this->uniqId . ');')); ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Архив үзэх', 'for' => 'Архив үзэх')); ?></td>
                <td>
                    <div class="row">
                        <div class="col tnaArchiveList-<?php echo $this->uniqId ?>">
                            <?php
                            echo Form::select(array(
                                'name' => 'archivId',
                                'id' => 'archivId',
                                'class' => 'form-control select2 form-control-sm input-xxlarge',
                                'data' => $this->getArchivList,
                                'op_value' => 'ID',
                                'op_text' => 'VERSION| |-| |DESCRIPTION',
                                'onchange' => 'enableOrDisableBackArchivBtn(this);'
                            ));
                            ?>
                        </div>
                        <div class="w-100"></div>
                        <div class="col mt2">
                            <?php echo Form::button(array('class' => 'btn btn-sm purple-plum ml0 mr0', 'title' => 'Архив харах', 'value' => '<i class="fa fa-eye"></i>', 'onclick' => 'getViewArchiv(this, '. $this->uniqId .');')); ?>
                            <?php echo Form::button(array('class' => 'btn btn-sm purple-plum ml0 mr0 balanceReload disabled archivEnableOrDisableBtn d-none', 'data-uniqId' => $this->uniqId, 'title' => 'Буцах', 'value' => '<i class="fa fa-arrow-left"></i>')); ?>
                            <?php echo Form::button(array('class' => 'btn btn-sm purple-plum ml0 mr0 balanceReload disabled archivEnableOrDisableBtn d-none', 'title' => 'Архив сэргээх', 'value' => '<i class="fa fa-rotate-left"></i>', 'onclick' => 'recoveryArchivDailog(this, '. $this->uniqId .');')); ?>
                        </div>
                    </div>
                </td>
            </tr>
            <?php if ($this->isAdd) { ?>
<!--                <tr>
                    <td class="left-padding"><?php echo Form::label(array('text' => 'Цагийн хуваарь нэмэх', 'for' => '')); ?></td>
                    <td>
                        <div class="form-inline">
                            <button type="button" class="btn btn-xs btn-success ml0 mr0 mb5" title="Цагийн хуваарь нэмэх" onclick="callCustomByMeta('<?php echo $this->tmsMetaDataId ?>', true);"><i class="fa fa-clock-o"></i></button>  
                        </div>
                    </td>
                </tr>-->
            <?php } ?>
<!--            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Цагаа бүртгүүлдэггүй ажилтан', 'for' => '')); ?></td>
                <td>
                    <div class="form-inline">
                        <button type="button" class="btn btn-xs btn-success ml0 mr0 mb5" title="Цагаа бүртгүүлдэггүй ажилтан" onclick="callCustomByMeta('<?php echo $this->tmsMetaDataId ?>', true);"><i class="fa fa-user"></i></button>  
                    </div>
                </td>
            </tr>                -->
        </tbody>
    </table>
</div>
<?php if(isset($this->dataPlan) && $this->dataPlan) { ?>
    <div class="row defaultShowEmployeePlanTime">
        <div class="col-md-12">
        <input type="text" id="tnatimeplan" class="form-control form-control-sm stringInit mb5" placeholder="Хайх" value="">
        </div>
    </div>
    <div class="panel panel-default bg-inverse timePlanScroller defaultShowEmployeePlanTime" style="max-height: 210px;">
        <table class="table sheetTable timePlanList" id="tna_timeplan_list">
            <thead class="hidden">
                <tr>
                    <th>Нэр</th>
                    <th>Цаг</th>
                    <?php if (Config::getFromCache('tmsCustomerCode') == 'gov') { ?>
                    <th style="min-width:40px !important"></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                for($i = 0; $i < count($this->dataPlan); $i++) {
                    $planStartAndEndTime = ($this->dataPlan[$i]['START_TIME']) ? $this->dataPlan[$i]['START_TIME'] . '-' . $this->dataPlan[$i]['END_TIME'] : '';

                    echo '<tr>';
                    echo '<td>';
                        echo '<label>';
                            echo '<input class="ui-selected" name="plan" type="radio" value="'.$this->dataPlan[$i]['PLAN_ID'].'" /> ';
                            echo $this->dataPlan[$i]['NAME'];
                        echo '</label>';
                    echo '</td>';
                    echo '<td>' . $planStartAndEndTime . '</td>';
                    if (Config::getFromCache('tmsCustomerCode') == 'gov') {
                        if ($this->dataPlan[$i]['PLAN_COUNT'] > 0) {
                            echo '<td style="min-width:40px !important"><a href="javascript:;" onclick="dataViewTimePlanEmployeeGrid()">' . $this->dataPlan[$i]['PLAN_COUNT'] . '</a></td>';
                        } else {
                            echo '<td style="min-width:40px !important"></td>';
                        }
                    }
                    echo '</tr>';
                }
                ?>            
            </tbody>
        </table>
    </div>
<?php } ?>

<script type="text/javascript">
    var employeeSetTimePlan = [];
    var archiveList_<?php echo $this->uniqId ?> = <?php echo json_encode($this->getArchivList); ?>;
    $('.isLock').on('click', function () {
        var status = $(tnaTimeEmployeePlanWindowId).find('.statusCancelBtn').attr('data-status-code');
        employeeSetTimePlan = [];
        if (isWorkFlowStatus(status)) {
            var tbl = $("#tnaBalanceGrid").find("table tbody");
            var isDiffUser = false;
            tbl.find('.ui-selected').each(function () {
                var cell = $(this);
                var row = cell.closest('tr');
                employeeSetTimePlan.push({
                    "employeeId": row.find('input[data-name="employeeId"]').val(),
                    "planDate": cell.find('input[data-name="planDate"]').val()
                });
                var lockUserId = cell.find('input[data-name="lockUserId"]').val();
                if (lockUserId.length != 0) {
                    if (lockUserId != 'null') {
                        if (lockUserId != '<?php echo Ue::sessionUserId(); ?>') {
                            isDiffUser = true;
                        }
                    }
                }
            });
            if (isDiffUser || employeeSetTimePlan.length == 0) {
                new PNotify({
                    title: MESSSAGE_WARNING_TITLE,
                    text: 'Өөр хэрэглэгч түгжсэн эсвэл ямар нэг нүд нүд сонгоогүй байна',
                    type: 'warning'
                });
            } else {
                if (userSessionIsFull()) {
                    new PNotify({
                        title: MESSSAGE_WARNING_TITLE,
                        text: MESSSAGE_SESSION_FULL,
                        type: 'warning'
                    });
                } else {
                    callIsLockDialog();
                }

            }
        } else {
            PNotify.removeAll();
            new PNotify({
                title: MESSSAGE_WARNING_TITLE,
                text: MESSSAGE_STATUS_ERROR,
                type: 'warning',
                sticker: false
            });
        }
    });

    // if ( ! $.fn.DataTable.isDataTable( '#tna_timeplan_list' ) ) {
    //     var table = $('#tna_timeplan_list').DataTable({
    //         "paging": false,
    //         "info":     false,
    //         "ordering": false
    //     });
    // }    

    // $('#tnatimeplan').on('keyup', function () {
    //     table.search( this.value ).draw();
    // });    

    $("#tnatimeplan").on("keyup", function() {
        var value = $(this).val().toLowerCase(), $row;
    
        $("#tna_timeplan_list > tbody > tr").each(function(index) {
            $row = $(this);        
            var id = $.trim($row.find("td:first").text().toLowerCase());

            if (id.indexOf(value) != 0) {
                $row.hide();
            } else {
                $row.show();
            }
        });
    });    
    
    $('.timePlanScroller').slimScroll({
        height: 300,
        alwaysVisible: true
    });    

    function callIsLockDialog() {
        var $dialogName = 'dialog-isLock';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdtime/isLockPlan',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 400,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                                if ($("input[name='isLock']:checked", "#" + $dialogName).val() == '1') {
                                    $("#isLockForm").validate({
                                        errorPlacement: function () {}
                                    });
                                    if ($("#isLockForm").valid()) {
                                        $.ajax({
                                            type: 'post',
                                            url: 'mdtime/isLockPlanQuery',
                                            dataType: "json",
                                            data: {"data": JSON.stringify(employeeSetTimePlan), "lockEndDate": $("#lockEndDate", "#" + $dialogName).val(), "isLock": $("input[name='isLock']:checked", "#" + $dialogName).val()},
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    animate: true
                                                });
                                            },
                                            success: function (data) {
                                                new PNotify({
                                                    title: 'Амжилттай',
                                                    text: data.message,
                                                    type: data.status,
                                                    sticker: false
                                                });
                                                addUserSessionCount();
                                                getEmployeePlanList();
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
                                            }
                                        });
                                    }
                                } else {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdtime/isLockPlanQuery',
                                        dataType: "json",
                                        data: {"data": JSON.stringify(employeeSetTimePlan), "lockEndDate": '', "isLock": $("input[name='isLock']:checked", "#" + $dialogName).val()},
                                        beforeSend: function () {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
                                        success: function (data) {
                                            new PNotify({
                                                title: 'Амжилттай',
                                                text: data.message,
                                                type: data.status,
                                                sticker: false
                                            });
                                            addUserSessionCount();
                                            getEmployeePlanList();
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                }
                                $("#" + $dialogName).dialog('close');
                            }},
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
</script>