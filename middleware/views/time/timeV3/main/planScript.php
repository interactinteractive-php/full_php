<script type="text/javascript">

    var verifEmployee = '<?php echo $this->sessionVerifEmployee; ?>';
    var currentDate = '<?php echo Date::currentDate("Y-m-d"); ?> 00:00:00';
    var elementPosition = $('.right-sidebar-content').offset();
    var depreciationWindowId = "#depreciation";

    var _golomtViewEmployeePlan = <?php echo (defined('CONFIG_TNA_GOLOMT') ? json_encode(CONFIG_TNA_GOLOMT) : 'false'); ?>;
    var _tempedSelectedPlanRows = [];

    $(window).scroll(function () {
        if ($(window).scrollTop() > elementPosition.top) {
            $('.right-sidebar-content').addClass("fixedRightSideBar");
        } else {
            $('.right-sidebar-content').removeClass("fixedRightSideBar");
        }
    });

    $(window).resize(function () {
        timePlanResizeDtlTable();
    });

    $(function () {
        $('.positionGroup').hide();

        $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js").done(function (script, textStatus) {
            $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function (script, textStatus) {
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');
                $('#departmentId-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-departmentId-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

                $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

                $('#employeeStatusPlan-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-employeeStatusPlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

            });
        });


        Core.initNumberInput();
        if (parseInt(verifEmployee) == 1) {
            $('.isVerif').removeClass('hidden');
            $('.isVerifyBtn').addClass('hidden');
        } else {
            $('.isVerif').addClass('hidden');
            $('.isVerifyBtn').removeClass('hidden');
        }

        $('.additional-panel').addClass('hidden');
        ///renderSidebar(tnaTimeEmployeePlanWindowId, "");
        $(".cancelTimeBalance").on("click", function () {
            var dialogName = '#cancelDialog';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            $(dialogName).html('Та итгэлтэй байна уу').dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулга',
                width: 'auto',
                height: 'auto',
                modal: true,
                buttons: [
                    {text: '<?php echo $this->lang->line('yes_btn'); ?>', class: 'btn blue btn-sm', click: function () {
                            $(dialogName).dialog('close');
                            window.location = URL_APP + 'mdtime/timeBalance';
                        }},
                    {text: '<?php echo $this->lang->line('no_btn'); ?>', class: 'btn grey-cascade btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                ]
            }).dialog('open');
        });

        var departmentId = $('#departmentId-<?php echo $this->uniqId ?>').val();

        if (departmentId != '') {
            $.ajax({
                type: 'post',
                url: 'mdtime/getDepartmentGroupList',
                data: {departmentId: departmentId},
                dataType: "json",
                beforeSend: function () {},
                success: function (detail) {
                    Core.unblockUI();

                    $('.groupIdTimeEmployeePlanC').empty();

                    var ticketDepGroup = true;

                    var html = '<select id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" multiple="multiple" class="form-control input-xs input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
                    if (detail.length > 0) {
                        $.each(detail, function (key, value) {
                            html += '<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                        });
                        ticketDepGroup = false;
                    }
                    html += '</select>';

                    if (ticketDepGroup) {
                        html = '<select disabled = "disabled" id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" class="form-control input-xs input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option></select>';
                    }

                    $('.groupIdTimeEmployeePlanC').html(html);
                    $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                    $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();
                },
                error: function () {
                    Core.unblockUI();
                    new PNotify({
                        title: 'Error',
                        text: 'error',
                        type: 'error',
                        sticker: false
                    });
                }
            });
        }

    });

    $('#departmentId-<?php echo $this->uniqId ?>').on('change', function () {
        var thisval = $(this).val();
        $.ajax({
            type: 'post',
            url: 'mdtime/getDepartmentGroupList',
            data: {departmentId: thisval},
            dataType: "json",
            beforeSend: function () {},
            success: function (detail) {
                Core.unblockUI();
                $('.groupIdTimeEmployeePlanC').empty();

                var ticketDepGroup = true;
                var html = '<select id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" class="form-control input-xs input-xxlarge" multiple="multiple" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option>';
                if (detail.length > 0) {
                    $.each(detail, function (key, value) {
                        html += '<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                    });
                    ticketDepGroup = false;
                }
                html += '</select>';

                if (ticketDepGroup) {
                    html = '<select disabled = "disabled" id="groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>" name="groupId[]" class="form-control input-xs input-xxlarge" data-placeholder="- Сонгох -" tabindex="-1" title=""><option value="">- Сонгох -</option></select>';
                }

                $('.groupIdTimeEmployeePlanC').html(html);

                $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();
            },
            error: function () {
                Core.unblockUI();
                new PNotify({
                    title: 'Error',
                    text: 'error',
                    type: 'error',
                    sticker: false
                });
            }
        });
    });

    $(document).on('keydown', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>', function (e) {
        console.log($(this).closest('.tab-pane'));
        if(!$(this).closest('.tab-pane').hasClass('active'))
            return;
    
        if (e.keyCode == 65 && e.ctrlKey) {
            $("#tnaBalanceGrid table tbody").find('td.tbl-cell').addClass('ui-selected');
        }
        if (e.keyCode == 90 && e.ctrlKey) {
            $("#tnaBalanceGrid table tbody").find('.ui-selected').removeClass('ui-selected');
        }
        if (e.keyCode == 67 && e.ctrlKey) { // ctrl + c
            _tempedSelectedPlanRows = [];
            var tbl = $('#tnaBalanceGrid', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').find('table tbody');
            tbl.find('.ui-selected').each(function () {
                var _this = $(this);
                _tempedSelectedPlanRows.push({
                    index: _this.index(),
                    planId: _this.find('input[data-name="planId"]').val(),
                    planDate: _this.find('input[data-name="planDate"]').val(),
                    day: _this.find('input[data-name="day"]').val(),
                    planTime: _this.find('input[data-name="planTime"]').val(),
                });
            });            

        }
        if (e.keyCode == 86 && e.ctrlKey) { // ctrl + v
            var pasteSaveParams = [];
            var tbl = $('#tnaBalanceGrid', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').find('table tbody');
            var _pasteRowLength = tbl.find('.ui-selected').length;
            var _copyRowLength = _tempedSelectedPlanRows.length;
            var index = 0;

            if (_pasteRowLength <= _copyRowLength) {
                tbl.find('.ui-selected').each(function (key, row) {
                    var _this = $(this);
                    var _planRows = _tempedSelectedPlanRows.length;
                    
                    pasteSaveParams.push({
                        date: _this.find('input[data-name="planDate"]').val(),
                        wfmStatusId: _this.find('input[data-name="wfmStatusId"]').val(),
                        wfmStatusCode: _this.find('input[data-name="wfmStatusCode"]').val(),
                        tnaEmployeeTimePlanId: _this.find('input[data-name="tnaEmployeeTimePlanId"]').val(),
                        id: _this.closest('tr').find('input[data-name="employeeId"]').val(),
                        employeeKeyId: _this.closest('tr').find('input[data-name="employeeKeyId"]').val(),
                        employeeId: _this.closest('tr').find('input[data-name="employeeId"]').val(),
                        planId: _tempedSelectedPlanRows[index]['planId'],
                        isLock: _this.closest('tr').find('input[data-name="isLock"]').val(),
                        lockEndDate: _this.closest('tr').find('input[data-name="lockEndDate"]').val(),
                        lockUserId: _this.closest('tr').find('input[data-name="lockUserId"]').val(),
                    });

                    if ((_planRows - 1) == index) {
                        index = -1
                    }
                    index++;
                });
            } else {
                tbl.find('.ui-selected').each(function (key, row) {
                    var _this = $(this);
                    var _planRows = _tempedSelectedPlanRows.length;

                    pasteSaveParams.push({
                        date: _this.find('input[data-name="planDate"]').val(),
                        wfmStatusId: _this.find('input[data-name="wfmStatusId"]').val(),
                        wfmStatusCode: _this.find('input[data-name="wfmStatusCode"]').val(),
                        tnaEmployeeTimePlanId: _this.find('input[data-name="tnaEmployeeTimePlanId"]').val(),
                        id: _this.closest('tr').find('input[data-name="employeeId"]').val(),
                        employeeKeyId: _this.closest('tr').find('input[data-name="employeeKeyId"]').val(),
                        employeeId: _this.closest('tr').find('input[data-name="employeeId"]').val(),
                        planId: _tempedSelectedPlanRows[index]['planId'],
                        isLock: _this.closest('tr').find('input[data-name="isLock"]').val(),
                        lockEndDate: _this.closest('tr').find('input[data-name="lockEndDate"]').val(),
                        lockUserId: _this.closest('tr').find('input[data-name="lockUserId"]').val(),
                    });

                    if ((_planRows - 1) == index) {
                        index = -1
                    }
                    index++;
                });
            }

            var postParams = {"data": pasteSaveParams};
            $.ajax({
                type: 'post',
                url: 'mdtimestable/saveEmployeePlanPasteV2',
                data: postParams,
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        message: "Түр хүлээнэ үү!!!",
                        boxed: true
                    });
                },
                success: function (data) {
                    Core.unblockUI();
                    PNotify.removeAll();
                    
                    if(!data.message.length) {
                        return;
                    }
                    
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    if (data.status === 'success') {
                        getEmployeePlanList();
                    }
                },
                error: function () {
                    $.unblockUI();
                }
            });
        }
    });

    function onUserImageError(source) {
        source.src = "assets/core/global/img/user.png";
        source.onerror = "";
        return true;
    }

    function checkFullTime(elem) {
        var _this = $(elem);
        var _realFullTime = parseInt(_this.val());

        var row = _this.closest('tr');
        var _planTime = row.find('input[data-name="planTime"]');
        var _tempFullTime = 0;
        for (var i = 0; i < _planTime.length; i++) {
            var _time = $(_planTime[i]).val();
            if (_time.length > 0) {
                _tempFullTime = _tempFullTime + parseInt(_time);
            }
        }
        if (parseInt(_realFullTime) < parseInt(_tempFullTime)) {
            _this.addClass('error');
            PNotify.removeAll();
            new PNotify({
                title: 'Тайлбар',
                text: 'Хүн цаг хэтэрсэн байна',
                type: 'warning',
                sticker: false
            });
        } else {
            _this.removeClass('error');
        }
    }

    function groupSelectableGrid(metaDataCode, chooseType, elem, rows) {
        var _selectedRowId = '';
        var _selectedRowName = '';
        var _selectedRowCode = '';
        if (rows.length > 0) {
            $.each(rows, function (key, row) {
                if (key == 0) {
                    _selectedRowId = row.id;
                    _selectedRowCode = row.code;
                    _selectedRowName = row.name;
                } else {
                    _selectedRowId = _selectedRowId + ',' + row.id;
                    _selectedRowCode = _selectedRowCode + ',' + row.code;
                    _selectedRowName = _selectedRowName + ',' + row.name;
                }
            });
        }

        $("#groupId", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(_selectedRowId);
        $("#groupCode_displayField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(_selectedRowCode);
        $("#groupName_nameField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(_selectedRowName);
    }

    $('.timePlanClear', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', function () {
        $("#groupId", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val('');
        $("#groupCode_displayField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val('');
        $("#groupName_nameField", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val('');
    });

    function timePlanResizeDtlTable() {
        var freezeParent = $('#fz-parent', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>'));

        if (freezeParent.length) {
            var dynamicHeight = $(window).height() - freezeParent.offset().top - 65;
            freezeParent.css('height', dynamicHeight);
            $('table#assetDtls', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')).tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9});
            $('#fz-parent', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')).trigger('scroll');
        }
    }

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-prev:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());

        timePlanGotoPage(currentPageNumber - 1);
    });

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-last-prev:not(.pf-custom-pager-disabled)', function () {
        if (typeof $(this).attr('data-type') !== 'undefined') {
            timePlanArchivGotoPage(1);
        } else {
            timePlanGotoPage(1);
        }
    });

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        if (typeof $(this).attr('data-type') !== 'undefined') {
            timePlanArchivGotoPage(currentPageNumber + 1);
        } else {
            timePlanGotoPage(currentPageNumber + 1);
        }
        
    });

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('change', '.pagination-page-list', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        
        if (typeof $(this).attr('data-type') !== 'undefined') {
            timePlanArchivGotoPage(currentPageNumber);
        } else {
            timePlanGotoPage(currentPageNumber);
        }
        
    });

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-last-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var totalPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());

        if (typeof $(this).attr('data-type') !== 'undefined') {
            timePlanArchivGotoPage(totalPageNumber);
        } else {
            timePlanGotoPage(totalPageNumber);
        }
    });

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('click', '.pf-custom-pager-refresh:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());

        if (typeof $(this).attr('data-type') !== 'undefined') {
            timePlanArchivGotoPage(currentPageNumber);
        } else {
            timePlanGotoPage(currentPageNumber);
        }
    });

    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('keydown', '#assetDtls > thead > tr > th > input[data-fieldname]', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);

        if (code === 13) {
            if (typeof $(this).attr('data-type') !== 'undefined') {
                timePlanArchivGotoPage(1);
            } else {
                timePlanGotoPage(1);
            }
        }
    });
    
    function timePlanGotoPage(pageNumber) {

        Core.blockUI({
            boxed: true,
            message: 'Уншиж байна...'
        });

        var filterRules = '';
        $('#tnatimePlanPage', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(pageNumber)
        $('#assetDtls > thead > tr > th > input[data-fieldname]', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').each(function () {
            var _this = $(this);
            var _value = _this.val();

            if (_value != '') {
                var fieldName = _this.attr('data-fieldname');
                var condition = _this.attr('data-condition');

                filterRules += '{"field":"' + fieldName + '","op":"' + condition + '","value":"' + _value + '"},';
            }
        });

        if (filterRules) {
            filterRules = rtrim(filterRules, ',');
            filterRules = '[' + filterRules + ']';
        }

        $.ajax({
            type: 'POST',
            url: 'mdtime/empPlanListMainDataGridV2',
            data: {
                "params": $("#tnaTimeEmployeePlanForm").serialize(),
                uniqId: '<?php echo $this->uniqId; ?>',
                metaDataId: '<?php echo $this->uniqId; ?>',
                page: pageNumber,
                rows: 50,
                filterRules: filterRules,
                srch_yearCode: $('#srch_yearCode').val(),
                srch_monthCode: $('#srch_monthCode').val(),
            },
            dataType: 'json',
            beforeSend: function () {},
            success: function (data) {
                if (data.hasOwnProperty('status') && data.status == 'success') {
                    $("table#assetDtls > tbody", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').empty();
                    var depreciationContent = $('table#assetDtls > tbody', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')[0];
                    depreciationContent.innerHTML = data.Html;
                    $('table#assetDtls > tbody', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').promise().done(function () {

                        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
                        var totalRowNumber = data.total;
                        var pageNumbers = Math.ceil(totalRowNumber / 50) || 1;
                        var currentPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());

                        pagerElement.find('.pf-custom-pager-total > span').text(totalRowNumber);
                        pagerElement.find('input[data-gotopage]').val(pageNumber);
                        pagerElement.find('span[data-pagenumber]').text(pageNumbers);

                        if (currentPageNumber == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else {
                            if (pageNumber == currentPageNumber) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            } else if (pageNumber == 1 && pageNumbers == 1) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            } else if (pageNumber == 1) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev').addClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            } else {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            }
                        }

                        if ($().tableHeadFixer) {
                            $('table#assetDtls', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9});
                            $('#fz-parent', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').trigger('scroll');
                        }
                        var tbl = $("#tnaBalanceGrid").find("table");
                        var tblHeader = tbl.find('tr.tablesorter-headerRow th');
                        var tblFilterHeader = tbl.find('tr.tablesorter-ignoreRow td');
                        var _tblColSpan = tbl.find('tbody.tablesorter-no-sort tr').find('td.departmentTitle');
                        var $bcolspan = _tblColSpan.attr('colspan');
                        if (isWorkingDays) {
                            $('#isWorkingDays').attr('checked', 'checked');
                            tbl.find('td.weekday').removeClass('tbl-cell').hide();
                            tbl.find('th.weekday').removeClass('tbl-cell').hide();
                            var $colspan = 1;
                            for (var i = 0; i <= tblHeader.length; i++) {
                                if ($(tblHeader[i]).attr('data-isworking') == '7' || $(tblHeader[i]).attr('data-isworking') == '6') {
                                    $(tblFilterHeader[i]).hide();
                                    $colspan++;
                                }
                            }
                            _tblColSpan.attr('colspan', parseInt($bcolspan) - parseInt($colspan));
                        }

                        Core.unblockUI();
                    });
                } else if (data.hasOwnProperty('status') && data.status == 'error') {

                    new PNotify({
                        title: 'Error',
                        text: 'Өгөгдөл олдсонгүй',
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                }


            }
        });
    }
    
    function timePlanArchivGotoPage(pageNumber) {

        Core.blockUI({
            boxed: true,
            message: 'Уншиж байна...'
        });

        var filterRules = '';
        $('#tnatimePlanPage', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val(pageNumber)
        $('#assetDtls > thead > tr > th > input[data-fieldname]', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').each(function () {
            var _this = $(this);
            var _value = _this.val();

            if (_value != '') {
                var fieldName = _this.attr('data-fieldname');
                var condition = _this.attr('data-condition');

                filterRules += '{"field":"' + fieldName + '","op":"' + condition + '","value":"' + _value + '"},';
            }
        });

        if (filterRules) {
            filterRules = rtrim(filterRules, ',');
            filterRules = '[' + filterRules + ']';
        }

        $.ajax({
            type: 'POST',
            url: 'mdtime/archivEmpPlanListMainDataGridV2',
            data: {
                "params": $("#tnaTimeEmployeePlanForm").serialize(),
                uniqId: '<?php echo $this->uniqId; ?>',
                metaDataId: '<?php echo $this->uniqId; ?>',
                page: pageNumber,
                rows: 50,
                filterRules: filterRules,
                srch_yearCode: $('#srch_yearCode').val(),
                srch_monthCode: $('#srch_monthCode').val(),
                archiveLogId: $('#archiveLogId').val(),
            },
            dataType: 'json',
            beforeSend: function () {},
            success: function (data) {
                if (data.hasOwnProperty('status') && data.status == 'success') {
                    $("table#archivAssetDtls > tbody", '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').empty();
                    var depreciationContent = $('table#archivAssetDtls > tbody', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')[0];
                    depreciationContent.innerHTML = data.Html;
                    $('table#archivAssetDtls > tbody', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').promise().done(function () {

                        var pagerElement = $('.pf-custom-pager-tool', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>');
                        var totalRowNumber = data.total;
                        var pageNumbers = Math.ceil(totalRowNumber / 50) || 1;
                        var currentPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());

                        pagerElement.find('.pf-custom-pager-total > span').text(totalRowNumber);
                        pagerElement.find('input[data-gotopage]').val(pageNumber);
                        pagerElement.find('span[data-pagenumber]').text(pageNumbers);

                        if (currentPageNumber == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else {
                            if (pageNumber == currentPageNumber) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            } else if (pageNumber == 1 && pageNumbers == 1) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            } else if (pageNumber == 1) {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev').addClass('pf-custom-pager-disabled');
                                pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            } else {
                                pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            }
                        }

                        if ($().tableHeadFixer) {
                            $('table#archivAssetDtls', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 4, 'z-index': 9});
                            $('#fz-parent', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').trigger('scroll');
                        }
                        var tbl = $("#tnaBalanceGrid").find("table");
                        var tblHeader = tbl.find('tr.tablesorter-headerRow th');
                        var tblFilterHeader = tbl.find('tr.tablesorter-ignoreRow td');
                        var _tblColSpan = tbl.find('tbody.tablesorter-no-sort tr').find('td.departmentTitle');
                        var $bcolspan = _tblColSpan.attr('colspan');
                        if (isWorkingDays) {
                            $('#isWorkingDays').attr('checked', 'checked');
                            tbl.find('td.weekday').removeClass('tbl-cell').hide();
                            tbl.find('th.weekday').removeClass('tbl-cell').hide();
                            var $colspan = 1;
                            for (var i = 0; i <= tblHeader.length; i++) {
                                if ($(tblHeader[i]).attr('data-isworking') == '7' || $(tblHeader[i]).attr('data-isworking') == '6') {
                                    $(tblFilterHeader[i]).hide();
                                    $colspan++;
                                }
                            }
                            _tblColSpan.attr('colspan', parseInt($bcolspan) - parseInt($colspan));
                        }

                        Core.unblockUI();
                    });
                } else if (data.hasOwnProperty('status') && data.status == 'error') {

                    new PNotify({
                        title: 'Error',
                        text: 'Өгөгдөл олдсонгүй',
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                }
            },
            error: function (data) {
                Core.unblockUI();
            }
        });
    }
    
</script>
<style type='text/css'>
    .ui-multiselect-checkboxes label input {
        top: 3px; 
        margin-right: 5px;
    }
    .ui-state-active, .ui-state-focus, .ui-state-hover, .ui-widget-content .ui-state-active, .ui-widget-content .ui-state-focus, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-active, .ui-widget-header .ui-state-focus, .ui-widget-header .ui-state-hover {
        font-weight: 600;
        color: #FFFFFF;
    }
    .ui-multiselect-menu label {
        margin: 0;
    }
    .ui-multiselect-checkboxes label {
        padding-top:1px;
        padding-bottom:5px;
    }
    body {
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -o-user-select: none;
        user-select: none;
    }
</style>