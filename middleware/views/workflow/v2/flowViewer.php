<div class="row">
    <form id="changeWfmStatusFlowForm_<?php echo $this->metaDataId ?>" class="form-horizontal" method="post">    
    <div class="col-md-12">
        <div class="xs-form">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4 hidden">
                        <div class="table-scrollable table-scrollable-borderless bp-header-param">
                            <table class="table table-sm table-no-bordered bp-header-param">
                                <tbody>
                                    <tr data-cell-path="flowUserName">
                                        <td class="text-right middle" style="width: 40%">
                                            <label for="param[flowUserName]" data-label-path="flowUserName">Нэр:</label>
                                        </td>
                                        <td class="middle" style="width: 55%" colspan="">
                                            <span class="bold flowUserName"></span>
                                        </td>
                                    </tr>
                                    <tr data-cell-path="flowWfmStatus">
                                        <td class="text-right middle" style="width: 40%">
                                            <label for="param[flowWfmStatus]" data-label-path="flowWfmStatus">Төлөв:</label>
                                        </td>
                                        <td class="middle" style="width: 55%" colspan="">
                                            <span class="form-control-plaintext bold flowWfmStatus">
                                                <span class="badge"></span>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr data-cell-path="flowDate">
                                        <td class="text-right middle" style="width: 40%">
                                            <label for="param[flowDate]" data-label-path="flowDate">Огноо:</label>
                                        </td>
                                        <td class="middle" style="width: 55%" colspan="">
                                            <span class="bold flowDate"></span>
                                        </td>
                                    </tr>
                                    <tr data-cell-path="flowIsSign">
                                        <td class="text-right middle" style="width: 40%">
                                            <label for="param[flowIsSign]" data-label-path="flowIsSign">Гарын үсэгтэй эсэх:</label>
                                        </td>
                                        <td class="middle" style="width: 55%" colspan="">
                                            <span class="flowIsSign"></span>
                                        </td>
                                    </tr>
                                    <tr data-cell-path="changeWfmStatus">
                                        <td class="text-right middle" style="width: 40%">
                                            <label for="param[changeWfmStatus]" data-label-path="changeWfmStatus">Workflow:</label>
                                        </td>
                                        <td class="middle" style="width: 55%" colspan="">
                                            <span class="changeWfmStatus"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>                      
                        </div>     
                    </div>     
                    <div class="col-md-12">
                        <a href="javascript:;" class="btn btn-sm btn-circle green-meadow float-left addBtn_<?php echo $this->uniqId ?>" onclick="dataViewCustomSelectableGrid('sysUmUserList', 'multi', 'chooseWfmStatusAssigmentUserFlow', '', this);">
                            <i class="icon-plus3 font-size-12"></i> Нэмэх
                        </a>
                        <span class="badge float-right text bold" style="color:#578ebe;  font-weight: 700; text-transform: uppercase; margin-top:0; /*background-color: <?php echo $this->newWfmStatusColor; ?>*/">
                            <div class="btn-group">
                                <input type="hidden" id="wfmstatusid_<?php echo $this->uniqId ?>" value="0" />
                                <button class="btn blue-madison btn-circle btn-xs dropdown-toggle wfmnamechangebtn_<?php echo $this->uniqId ?>" type="button" data-toggle="dropdown"><?php echo $this->newWfmStatusName; ?></button>
                                <?php if ($this->wfmStatusList) { ?>
                                    <ul class="dropdown-menu float-right" style="max-height: 200px; overflow: auto;" role="menu">
                                        <?php
                                        foreach ($this->wfmStatusList as $row) { ?>
                                        <li style="background: <?php echo $row['WFM_STATUS_COLOR'] ?>;">
                                            <a href="javascript:;" onclick="changeWfmstatusid_<?php echo $this->uniqId ?>(this)" data-status-color="<?php echo $row['WFM_STATUS_COLOR'] ?>" data-status-id="<?php echo $row['WFM_STATUS_ID'] ?>" data-status-name="<?php echo $row['WFM_STATUS_NAME'] ?>"><?php echo $row['WFM_STATUS_NAME'] ?></a>
                                        </li>
                                        <?php 
                                        } 
                                        ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table wfm-header-table-<?php echo $this->uniqId ?>" id="wfm-status-assigment-tbl-flow">
                                        <thead>
                                            <tr>
                                                <!--<th style="width: 5px;">№</th>-->
                                                <th style="width: 320px;">Овог Нэр</th>
                                                <th style="width: 120px;">Шийдвэрлэх огноо</th>
                                                <th style="width: 200px;">Төлөв (Тэмдэглэл)</th>
                                                <th style="width: 120px;">Шийдвэрлэсэн огноо</th>
                                                <th style="width: 30px;" title="Гарын үсэгтэй эсэх"><i class="fa fa-check-circle"></i></th>
                                                <!--<th style="width: 15px;"></th>-->
                                                <th style="width: 190px;">Хэн шилжүүлсэн</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($this->wfmStatusAssignment) {
                                                $i = 1;

                                                foreach ($this->wfmStatusAssignment as $wfmStatus) {

                                                    $wfmStatusColor = $wfmStatus['WFM_STATUS_COLOR'];
                                                    $wfmStatusName = $wfmStatus['WFM_STATUS_NAME'];

                                                    if ($wfmStatusName == '') {
                                                        $wfmStatusColor = $wfmStatus['WFM_STATUS_COLOR_S'];
                                                        $wfmStatusName = $wfmStatus['WFM_STATUS_NAME_S'];
                                                    }
                                                    ?>
                                                    <tr class="<?php echo ($wfmStatus['USER_ID'] == Ue::sessionUserKeyId() ? 'enableWfmStatus' : ''); ?>">
                                                        <!--<td class="middle"><?php echo $i++; ?>.</td>-->
                                                        <td class="middle">
                                                            <div class="col-md-3 pl0 pr0">
                                                                <img src="<?php echo $wfmStatus['PICTURE']; ?>" onerror="onUserImgError(this);" height="53"/>
                                                            </div> 
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo $wfmStatus['EMPLOYEE_NAME']; ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo isset($wfmStatus['DEPARTMENT_NAME']) ? 'Алба хэлтэс: '.$wfmStatus['DEPARTMENT_NAME'] : 'Алба хэлтэсгүй'; ?>"><?php echo isset($wfmStatus['DEPARTMENT_NAME']) ? $wfmStatus['DEPARTMENT_NAME'] : 'Алба хэлтэсгүй'; ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo isset($wfmStatus['POSITION_NAME']) ? 'Албан тушаал: '.$wfmStatus['POSITION_NAME'] : 'Албан тушаалгүй'; ?>"><?php echo isset($wfmStatus['POSITION_NAME']) ? $wfmStatus['POSITION_NAME'] : 'Албан тушаалгүй'; ?></div></div>
                                                        </td>
                                                        <td class="middle text-center">
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo $wfmStatus['DUE_DATE'] ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" style="color: #578ec0;"><?php echo $wfmStatus['DUE_DAY'] ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo $wfmStatus['DUE_TIME'] ?></div></div>
                                                        </td>
                                                        <td class="middle">
                                                            <div class="col-md-9 pl0 pr0">
                                                                <span class="badge label-sm word-wrap-overflow" style="background-color: <?php echo '' ?>; padding: 4px;"><?php echo '' ?></span>
                                                            <div class="col-md-9 pl0 pr0" title="<?php echo 'Тайлбар: ...'; ?>"><div class="word-wrap-overflow"><?php echo '' ?></div></div>
                                                        </td>
                                                        <td class="middle text-center">
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo '' ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" style="color: #578ec0;"><?php echo '' ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo '' ?></div></div>
                                                        </td>
                                                        <td class="text-center middle"><?php echo ($wfmStatus['IS_NEED_SIGN'] == '1' ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-circle"></i>'); ?></td>
                                                        <td class="hide"><?php echo ($wfmStatus['USER_ID'] == Ue::sessionUserKeyId() ? (new Mdworkflow())->getWorkflowNextStatus($this->metaDataId, $this->dataRow, $this->refStructureId) : ''); ?></td>
                                                        <td>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo $wfmStatus['ASSIGN_EMPLOYEE_NAME'] ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo 'Алба хэлтэс: '.$wfmStatus['ASSIGN_DEPARTMENT_NAME'] ?>"><?php echo $wfmStatus['ASSIGN_DEPARTMENT_NAME'] ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo 'Албан тушаал: '.$wfmStatus['ASSIGN_POSITION_NAME'] ?>"><?php echo $wfmStatus['ASSIGN_POSITION_NAME'] ?></div></div>
                                                            <div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo 'Шилжүүлсэн огноо: '.$wfmStatus['ASSIGNED_DATE'] ?>"><?php echo $wfmStatus['ASSIGNED_DATE'] ?></div></div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>

<script type="text/javascript">
    $(function() {
        var trSelector = $('#wfm-status-assigment-tbl-flow tbody tr.enableWfmStatus');
        $('.flowUserPhoto').html(trSelector.find("td:eq(1)").find('span:first').html());
        $('.flowUserPhoto').find('img').attr({height: 60, width: 60});
        $('.flowUserName').text(trSelector.find("td:eq(1)").find('span:last').text());
        $('.flowWfmStatus').find('span').text(trSelector.find("td:eq(2)").find('span').text()).attr('style', trSelector.find("td:eq(2)").find('span').attr('style'));
        $('.flowWfmStatus').find('span').addClass('label-sm');
        $('.flowDate').text(trSelector.find("td:eq(3)").text());
        $('.flowIsSign').html(trSelector.find("td:eq(4)").html());
        $('.changeWfmStatus').html(trSelector.find("td:eq(5)").html());
    });
    
    function chooseWfmStatusAssigmentUserFlow(metaDataCode, chooseType, elem, rows) {
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var isAddRow = true;
            $('#wfm-status-assigment-tbl-flow > tbody').find("tr").each(function(){
                if ($(this).find("input.assigmentUserId").val() === row.id) {
                    isAddRow = false;
                }
            });
            
            if (isAddRow) {
                var _positionName = (row.positionname) ? row.positionname : '';
                var _statusName = (row.statusname) ? row.statusname : '';
                var _departmentName = (row.departmentname) ? row.departmentname : '';
                var _currentTime = '<?php echo Date::currentDate('H:i:s'); ?>';
                var _currentDate = '<?php echo Date::currentDate('Y/m/d'); ?>';
                
                var appendRow = '<tr class="addRow">'
                                    /* + '<td class="middle">1.</td>' */
                                    + '<td class="middle">'
                                        + '<div class="col-md-3 pl0 pr0">'
                                            + '<img src="'+ row.picture +'" onerror="onUserImgError(this);" height="53">'
                                        + '</div> '
                                        + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.employeename +'</div></div>'
                                        + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Алба хэлтэс: '+ _departmentName +'">'+ _departmentName +'</div></div>'
                                        + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Албан тушаал: '+ _positionName +'">'+ _positionName +'</div></div>'
                                        + '<input type="hidden" name="assigmentUserId[]" class="assigmentUserId" value="'+row.id+'">'
                                    + '</td>'
                                    + '<td class="middle">'
                                        + '<input type="text" name="dueDate[]" class="form-control datetimeInit form-control-sm addRow" style="border-radius: 0px !important;">' 
                                    + '</td>'
                                    + '<td class="middle">'
                                        + '<div class="datetimeElement input-group" style="width:110px;">' 
                                        + '</div>'
                                    + '</td>'
                                    + '<td class="middle text-center">'
                                        
                                    + '</td>'
                                    + '<td class="text-center middle"><input type="checkbox" name="isNeedSign['+row.id+']" class="form-control form-control-sm" value="1"></td>'
                                    + '<td class="hide"></td>'
                                    + '<td class="hide">'
                                        + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow"><?php echo isset($this->userData['EMPLOYEE_NAME']) ? $this->userData['EMPLOYEE_NAME'] : '' ?></div></div>'
                                        + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo isset($this->userData['POSITION_NAME']) ? $this->userData['POSITION_NAME'] : '' ?>"><?php echo isset($this->userData['POSITION_NAME']) ? $this->userData['POSITION_NAME'] : '' ?></div></div>'
                                        + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="<?php echo isset($this->userData['STATUS_NAME']) ? $this->userData['STATUS_NAME'] : '' ?>"><?php echo isset($this->userData['STATUS_NAME']) ? $this->userData['STATUS_NAME'] : '' ?></div></div>'
                                    + '</td>'
                                    + '<td class="middle">'
                                        + '<a class="btn btn-danger btn-circle btn-sm" title="Устгах" onclick="deleteAssigmentUserIdFlow<?php echo $this->uniqId; ?>(this)" href="javascript:;"><i class="fa fa-trash"></i> Устгах</a>'
                                    + '</td>'
                            
                                + '</tr>';
                $('#wfm-status-assigment-tbl-flow > tbody').append(appendRow);
            }
        }
        /*var el = $("#wfm-status-assigment-tbl-flow > tbody > tr");
        var len = el.length, i = 0;
        for (i; i < len; i++) { 
            $(el[i]).find("td:first").text((i + 1) + '.');
        } */
        Core.initUniform($('#wfm-status-assigment-tbl-flow > tbody'));
        Core.initDateTimeInput($('#wfm-status-assigment-tbl-flow > tbody'));
    }   
    
    function deleteAssigmentUserIdFlow(elem) {
        $(elem).closest('tr').remove();
        var el = $("#wfm-status-assigment-tbl > tbody > tr");
        var len = el.length, i = 0;
        for (i; i < len; i++) { 
            $(el[i]).find("td:first").text((i + 1) + '.');
        }
    }
    
    function deleteAssigmentUserIdFlow<?php echo $this->uniqId; ?>(elem) {
        $(elem).closest('tr').remove();
    }
    
    function changeWfmstatusid_<?php echo $this->uniqId ?>(element) {
        $('.addBtn_<?php echo $this->uniqId ?>').attr('disabled', 'disabled');
        
        var _wfmstatusname_<?php echo $this->uniqId ?> = $(element).attr('data-status-name');
        var _wfmstatusId_<?php echo $this->uniqId ?> = $(element).attr('data-status-id');
        var _wfmstatuscolor_<?php echo $this->uniqId ?> = $(element).attr('data-status-color');
        
        $('.wfmnamechangebtn_<?php echo $this->uniqId ?>').html(_wfmstatusname_<?php echo $this->uniqId ?>);
        $('#wfmstatusid_<?php echo $this->uniqId ?>').val(_wfmstatusId_<?php echo $this->uniqId ?>);
        $.ajax({
            type: 'post',
            url: 'mdworkflow/renderflowViewer',
            data: {
                refStructureId: '<?php echo $this->refStructureId ?>', 
                dataViewId: '<?php echo $this->metaDataId ?>',
                rowId: '<?php echo $this->rowId ?>',
                wfmStatusId: _wfmstatusId_<?php echo $this->uniqId ?>,
                wfmStatusName: _wfmstatusname_<?php echo $this->uniqId ?>,
                wfmstatuscolor: _wfmstatuscolor_<?php echo $this->uniqId ?>, 
            }, 
            dataType: 'json',
            beforeSend: function(){
                Core.blockUI({
                    animate: true,
                    target: '.wfm-header-table-<?php echo $this->uniqId ?>',
                });
            }, 
            success: function(data) {
                
                if ('<?php echo $this->newWfmStatusId ?>' == _wfmstatusId_<?php echo $this->uniqId ?>) {
                    $('.addBtn_<?php echo $this->uniqId ?>').removeAttr('disabled');
                }
                Core.unblockUI('.wfm-header-table-<?php echo $this->uniqId ?>');
                var _html<?php echo $this->uniqId  ?> = '';
                if (typeof data.result != undefined && data.result.length > 0) {
                    $.each(data.result, function(index, row) {
                        var _htmlappend<?php echo $this->uniqId  ?> = (row.IS_NEED_SIGN == '1') ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-circle"></i>';
                        _html<?php echo $this->uniqId  ?> += '<tr class="">'
                                                                /* + '<td class="middle">1.</td>' */
                                                                + '<td class="middle">'
                                                                    + '<div class="col-md-3 pl0 pr0">'
                                                                        + '<img src="'+ row.EMPLOYEE_PICTURE +'" onerror="onUserImgError(this);" height="53">'
                                                                    + '</div> '
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.EMPLOYEE_NAME +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Алба хэлтэс: '+ row.DEPARTMENT_NAME +'">'+ row.DEPARTMENT_NAME +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Албан тушаал: '+ row.POSITION_NAME +'">'+ row.POSITION_NAME +'</div></div>'
                                                                + '</td>'
                                                                + '<td class="middle text-center">'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.DUE_DATE +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" style="color: #578ec0;">'+ row.DUE_DAY +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.DUE_TIME +'</div></div>'
                                                                + '</td>'
                                                                + '<td class="middle">'
                                                                    + '<div class="col-md-9 pl0 pr0">'
                                                                        + '<span class="badge label-sm word-wrap-overflow" style="background-color: '+ row.WFM_STATUS_COLOR +'; padding: 4px;">'+ row.WFM_STATUS_NAME +'</span>'
                                                                    + '<div class="col-md-9 pl0 pr0" title="Тайлбар: '+ row.WFM_DESCRIPTION +'"><div class="word-wrap-overflow">'+ row.WFM_DESCRIPTION +'</div></div>'
                                                                + '</td>'
                                                                + '<td class="middle text-center">'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.CASSIGNED_DATE +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" style="color: #578ec0;">'+ row.CASSIGNED_DAY +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.CASSIGNED_TIME +'</div></div>'
                                                                + '</td>'
                                                                + '<td class="text-center middle">'+ _htmlappend<?php echo $this->uniqId  ?>  +'</td>'
                                                                + '<td>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow">'+ row.SIGNED_EMPLOYEE_NAME +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Алба хэлтэс: '+ row.SIGNED_DEPARTMENT_NAME +'">'+ row.SIGNED_DEPARTMENT_NAME +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Албан тушаал: '+ row.SIGNED_POSITION_NAME +'">'+ row.SIGNED_POSITION_NAME +'</div></div>'
                                                                    + '<div class="col-md-9 pl0 pr0"><div class="word-wrap-overflow" title="Шилжүүлсэн огноо: '+ row.ASSIGNED_DATE +'">'+ row.ASSIGNED_DATE +'</div></div>'
                                                                + '</td>'
                                                            + '</tr>';
                    });
                    
                } else {
                    _html<?php echo $this->uniqId  ?> = '<tr>'
                                                        + '<td colspan="6" class="middle text-center">Тохирох үр дүн олдсонгүй</td>'
                                                    + '</tr>';
                }
                $('.wfm-header-table-<?php echo $this->uniqId ?> > tbody').html(_html<?php echo $this->uniqId  ?>);
                
            }
        });
        
    }
</script>