<script type="text/javascript">

    var verifEmployee = '<?php echo $this->sessionVerifEmployee; ?>';
    var currentDate = '<?php echo Date::currentDate("Y-m-d"); ?> 00:00:00';
    var elementPosition = $('.right-sidebar-content').offset();
    var depreciationWindowId = "#depreciation";
    var jstmsCustomerCode = "<?php echo Config::getFromCache('tmsCustomerCode'); ?>";

    var _golomtViewEmployeePlan = <?php echo (defined('CONFIG_TNA_GOLOMT') ? json_encode(CONFIG_TNA_GOLOMT) : 'false'); ?>;
    var tmsDefaultFilter = '<?php echo Config::getFromCache('tmsDefaultFilter') ? Config::getFromCache('tmsDefaultFilter') : ''; ?>';
    var _tempedSelectedPlanRows = [],
        windowId = ".tnaTimeEmployeePlan-<?php echo $this->uniqId ?>";

//    $(window).scroll(function () {
//        if ($(window).scrollTop() > elementPosition.top) {
//            $('.right-sidebar-content').addClass("fixedRightSideBar");
//        } else {
//            $('.right-sidebar-content').removeClass("fixedRightSideBar");
//        }
//    });

    $(window).resize(function () {
        timePlanResizeDtlTable();
    });

    $(function () {
        $('.positionGroup').hide();
        if (tmsDefaultFilter == '1') {
            setTimeout(function(){
                $('.search-tms-plan-btn', ".tnaTimeEmployeePlan-<?php echo $this->uniqId ?>").click();
            }, 200);
        }
        $(".tnaTimeEmployeePlan-<?php echo $this->uniqId ?>").on('change', "#newDepartmentId_valueField", function(){
            $('.search-tms-plan-btn', ".tnaTimeEmployeePlan-<?php echo $this->uniqId ?>").click();
        });
        
        $(".tnaTimeEmployeePlan-<?php echo $this->uniqId ?>").on('select2-opening', 'select[name="planMonth"]', function(e, isTrigger) {
            var $this = $(this), 
                $relateElement = $this.prev('.select2-container:eq(0)');

//            Core.blockUI({
//                target: $relateElement,
//                animate: false,
//                icon2Only: true
//            });

            if (!$this.hasClass("data-combo-set")) {
                $this.addClass("data-combo-set");
                var comboDatas = [];
                var select2 = $this.data('select2');
                var thisValue = $this.val();
                
                $.ajax({
                    type: 'post',
                    async: false,
                    url: 'mdtimestable/getRefMonthList',
                    data: {'selected': thisValue},
                    dataType: 'json',
                    success: function(data) {
                        $this.empty();
                        if (data.length) { 
                            $this.append($('<option />').val('').text(plang.get('choose')));  

                            $.each(data, function(){
                                if (this.MONTH_CODE == thisValue) {
                                    $this.append($("<option />")
                                        .val(this.MONTH_CODE)
                                        .text(this.MONTH_NAME)
                                        .attr({ 'selected': 'selected'}));
                                } else {
                                    $this.append($("<option />")
                                        .val(this.MONTH_CODE)
                                        .text(this.MONTH_NAME));                                    
                                }
                                comboDatas.push({
                                    id: this.MONTH_CODE,
                                    text: this.MONTH_NAME
                                });                     
                            });
                        }
                    },
                    error: function () {
                        alert("Ajax Error!");
                    } 
                }).done(function(){

                    $this.select2({results: comboDatas, closeOnSelect: false});
                    if (typeof isTrigger === 'undefined' && !select2.opened()) {
                        $this.select2('open');
                    }
                });
            }
        });  
        
        $(".tnaTimeEmployeePlan-<?php echo $this->uniqId ?>").on('change', 'select[name="planMonth"]', function(e, isTrigger) {        
            $(this).removeClass("data-combo-set");
        });
        
        $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js").done(function (script, textStatus) {
            $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function (script, textStatus) {
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');
                
                /*$('#departmentId-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-departmentId-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();*/

                $('#groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupIdTimeEmployeePlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();
                
                $('#positionId-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-positionId-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();

                $('#employeeStatusPlan-<?php echo $this->uniqId ?>').multiselect({noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-employeeStatusPlan-<?php echo $this->uniqId ?>-option-0').parent().parent().remove();
                
//                setTimeout(function(){
//                    $('#ui-multiselect-departmentId-<?php echo $this->uniqId ?>-option-1').closest('ul').children().each(function(){
//                        $(this).find("input[type='checkbox']").uniform.remove();
//                    });
//                }, 500);

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

   $(".selectedDepartmentNamesContainerBtn", windowId).toggle(function(){
        $(this).text('Хураах');
        $('.next-generation-input-wrap-1', windowId).animate({"height": 140}, 200, function(){
            $('#selectedDepartmentNamesContainer_<?php echo $this->uniqId ?>').css({"max-height": 120, "overflow": "auto"});
        });        
    },function(){
        $(this).text('Дэлгэрэнгүй харах');
        $('.next-generation-input-wrap-1', windowId).animate({"height": 80}, 200, function(){
            $('#selectedDepartmentNamesContainer_<?php echo $this->uniqId ?>').css({"max-height": 58, "overflow": "hidden"});
        });
    });   

    // Start Select2 ----->
    var singleClick = 0;
    $('.selectedDepartment_<?php echo $this->uniqId ?>').on('click', function () {
        if($('.selectedDepartmentIco_<?php echo $this->uniqId ?>').hasClass('fa-angle-up'))
            return;
        
        if (singleClick == 0) {
            singleClick = 1;
            var _jtreewidth = 550 - 1;
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').width(_jtreewidth);
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').find('.jstree-container-ul').width(_jtreewidth - 12);
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').removeClass('hidden');
            $('.selectedDepartmentIco_<?php echo $this->uniqId ?>').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            singleClick = 0;
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').addClass('hidden');
            $('.selectedDepartmentIco_<?php echo $this->uniqId ?>').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });    
    $(document).keyup(function (e) {
        if (e.which == 27) {
            closeselectedDepartmentJtree();
        }
    });
    $(document).click(function (e) {
        if ($(e.target)[0].className != 'jstree-icon jstree-ocl') {
            if ($(e.target).parents('.departmentlist-jtree-<?php echo $this->uniqId ?>').length === 0) {
                closeselectedDepartmentJtree();
            }
        }
    });
    function closeselectedDepartmentJtree() {
        singleClick = 0;
        $('.departmentlist-jtree-<?php echo $this->uniqId ?>').addClass('hidden');
        $('.selectedDepartmentIco_<?php echo $this->uniqId ?>').removeClass('fa-angle-up').addClass('fa-angle-down');
    }
    $('.selectedDepartmentIco_<?php echo $this->uniqId ?>').on('click', function () {
        if (singleClick == 0) {
            singleClick = 1;
            var _jtreewidth = 550 - 1;
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').width(_jtreewidth);
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').find('.jstree-container-ul').width(_jtreewidth - 12);
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').removeClass('hidden');
            $('.selectedDepartmentIco_<?php echo $this->uniqId ?>').removeClass('fa-angle-down').addClass('fa-angle-up');
        } else {
            singleClick = 0;
            $('.departmentlist-jtree-<?php echo $this->uniqId ?>').addClass('hidden');
            $('.selectedDepartmentIco_<?php echo $this->uniqId ?>').removeClass('fa-angle-up').addClass('fa-angle-down');
        }
    });    
    var selectedDepartmentId = [],
        selectedDepartmentText = [],
        selectedDepartment = false;

    $.jstree.defaults.search.ajax = true
    $('.list-jtree-<?php echo $this->uniqId ?>').on("changed.jstree", function (e, data) {
        if (data.action === "select_node") {
            if ($.inArray(data.node.id, selectedDepartmentId) < 0) {
                selectedDepartment = true;
                selectedDepartmentId.push(data.node.id);
                selectedDepartmentText.push(data.node.text);
            }
            selectNode(data.node.id);
            $.each(data.node.children_d, function (key, value) {
                selectNode(value);
                if (value != '#') {
                    if ($.inArray(value, selectedDepartmentId) < 0) {
                        selectedDepartmentId.push(value);
                        var getDepName = $('.list-jtree-<?php echo $this->uniqId ?>').jstree("get_node", value);
                        selectedDepartmentText.push(getDepName.text);
                    }
                }
            });
        } else if (data.action === "deselect_node") {
            var _index = selectedDepartmentId.indexOf(data.node.id);
            selectedDepartment = true;
            selectedDepartmentId.splice(_index, 1);
            selectedDepartmentText.splice(_index, 1);
            deSelectNode(data.node.id);
            $.each(data.node.children_d, function (key, value) {
                var indexMid = selectedDepartmentId.indexOf(value);
                if(indexMid >= 0) {
                    selectedDepartmentId.splice(indexMid, 1);
                    selectedDepartmentText.splice(indexMid, 1);
                    deSelectNode(value);
                }
            });
        } else if (data.action === "select_all") {
            selectedDepartment = true;
            $(this).children().children().each(function(){
                selectedDepartmentId.push($(this).attr('id'));
                selectedDepartmentText.push($(this).text());
            });
        }
        if(!selectedDepartment)
            return;
        
        $('.departmentId_<?php echo $this->uniqId ?>').val(selectedDepartmentId);
        $('.departmentIdName_<?php echo $this->uniqId ?>').val(selectedDepartmentText.join('__'));
        $('#selectedDepartmentNamesContainer_<?php echo $this->uniqId ?>').text(selectedDepartmentText.join(', '));
        if(selectedDepartmentText.length > 0)
            $('.selectedDepartmentNamesWrap', windowId).removeClass('hidden');
        else
            $('.selectedDepartmentNamesWrap', windowId).addClass('hidden');
    }).jstree({
        'core': {
            expand_selected_onload: false,
            "open_parents": false,
            "load_open": false,
            'data': {
                url: URL_APP + 'mdtimestable/getDeparmentListJtreeData',
                dataType: "json",
                data: function (node) {
                    return {
                        parentId: (node.id === "#" ? '' : node.id),
                        parentNode: 0,
                        depIds: [],
                        pSelected: node.state.selected ? '1' : '0'
                    };
                },
                success: function(data){
                    if(data.length === 1) {
                        $('.departmentId_<?php echo $this->uniqId ?>').val(data[0]['id']);
                        $('.departmentIdName_<?php echo $this->uniqId ?>').val(data[0]['text']);
                        $('#selectedDepartmentNamesContainer_<?php echo $this->uniqId ?>').text(data[0]['text']);
                        $('.selectedDepartmentNamesWrap', windowId).removeClass('hidden');
                    }
                }
            },
            'themes': {
                'responsive': false,
                'stripes': true
            }
        },
        "checkbox": {
            keep_selected_style: false,
            real_checkboxes: true,
            real_checkboxes_names: function (n) {
                var nid = 0;
                $(n).each(function (data) {
                    nid = $(this).attr("nodeid");
                });
                return (["check_" + nid, nid]);
            },
            three_state: false,
            two_state: true,
            whole_node: true
        },
        "search": {
            "case_insensitive": false,
            "show_only_matches": true
        },
        'types': {
            "default": {
                "icon": "fa fa-play-circle text-orange-400"
            },
            "file": {
                "icon": "fa fa-play-circle text-orange-400"
            }
        },
        'unique': {
            'duplicate': function (name, counter) {
                return name + ' ' + counter;
            }
        },
        'plugins': [
            'changed', 'types', 'unique', 'wholerow', 'checkbox', 'search'
        ]
    });
    
    var to = null;
    $(".departmentList_search_<?php echo $this->uniqId ?>").on('keyup', function (event) {
        var _thisVal = $(this).val();
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree('search', _thisVal);
    });
    $('.department-multiselect-all-<?php echo $this->uniqId ?>').on('click', function () {
        if($(this).hasClass('allCheckedData'))
            return;
        $(this).addClass('allCheckedData');
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("select_all");
    });
    $('.department-multiselect-none-<?php echo $this->uniqId ?>').on('click', function () {
        $('.department-multiselect-all-<?php echo $this->uniqId ?>').removeClass('allCheckedData');
        selectedDepartmentId = [];
        selectedDepartmentText = [];        
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("deselect_all");
    });
    var selectNode = function (id) {
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("select_node", id, true, true);
    };
    var deSelectNode = function (id) {
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("deselect_node", id, true, true);
    };    
    // End Select2 <-----          

    });

    $(document).keydown(function (e) {
        if(!$('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').closest('.tab-pane').hasClass('active'))
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
                    PNotify.removeAll();
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
            $('table#assetDtls', $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>')).tableHeadFixer({'head': true, 'foot': true, 'left': 3, 'z-index': 9});
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
    
    $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').on('change', '.pagination-page-list', function () {
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

        if ($('#listfromdv', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').val() == "1") {
            timePlanGotoPage2(pageNumber);
            return;
        }        

        Core.blockUI({
            boxed: true,
            message: 'Уншиж байна...'
        });

        setTimeout(function(){
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
                url: 'mdtimestable/empPlanListMainDataGridV2',
                data: {
                    "params": $("#tnaTimeEmployeePlanForm").serialize(),
                    uniqId: '<?php echo $this->uniqId; ?>',
                    metaDataId: '<?php echo $this->uniqId; ?>',
                    page: pageNumber,
                    rows: $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').find('.pagination-page-list').val(),
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
                                $('table#assetDtls', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 3, 'z-index': 9});
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
        }, 50);
    }
    
    function timePlanGotoPage2(pageNumber) {

        Core.blockUI({
            boxed: true,
            message: 'Уншиж байна...'
        });

        setTimeout(function(){
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
                url: 'mdtimestable/employeePlanListMainDataGridNewV4',
                data: {
                    "params": $("#tnaTimeEmployeePlanForm").serialize(),
                    uniqId: '<?php echo $this->uniqId; ?>',
                    metaDataId: '<?php echo $this->uniqId; ?>',
                    page: pageNumber,
                    reload: true,
                    rows: $('.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').find('.pagination-page-list').val(),
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
                                $('table#assetDtls', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 3, 'z-index': 9});
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
        }, 50);
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
                            $('table#archivAssetDtls', '.tnaTimeEmployeePlan-<?php echo $this->uniqId ?>').tableHeadFixer({'head': true, 'foot': true, 'left': 3, 'z-index': 9});
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
    
    function dataViewTimePlanEmployeeGrid(depId) {
        var defaultCriteriaParams = {};
        defaultCriteriaParams.departmentId = depId;
        var $dialogName = 'dialog-dataview-tms-plan-employee';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdtimestable/getChildDepartmenIds',
            data: {
                "params": $("#tnaTimeEmployeePlanForm").serialize()
            },
            dataType: 'json',
            async: false,
            beforeSend: function () {
            },
            success: function (data) {
                defaultCriteriaParams = data;
            }
        });

        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/144602085348237/0/json',
            data: {
                uriParams: JSON.stringify(defaultCriteriaParams)
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true, 
                    message: 'Loading...'
                });
            },
            success: function (data) {

                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1100,
                    height: $(window).height() - 90,
                    modal: true,
                    position: {my:'top', at:'top+50'},
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        }).done(function () {
            Core.initDVAjax($dialog);
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