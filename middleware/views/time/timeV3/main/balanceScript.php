<script type="text/javascript">
    
    var currentDate = '<?php echo Date::currentDate("Y-m-d");?>';
    var currentUserId = '<?php echo Ue::sessionUserId(); ?>';
    var golomtView = '<?php echo (isset($this->golomtView) && $this->golomtView) ? '1' : '0' ?>';
    var IS_MM_DEFINE = <?php echo defined('CONFIG_TMS_MM') && CONFIG_TMS_MM ? 'true' : 'false'; ?>;
    var tmsPageSize = <?php echo Config::getFromCache('tmsPageSize') ? Config::getFromCache('tmsPageSize') : '35'; ?>;
    var tmsPageSubGridHeidght = <?php echo Config::getFromCache('tmsPageHeight') ? Config::getFromCache('tmsPageHeight') : '425'; ?>;
    var _employeeCodeBalanceWindow = '<?php echo (isset($this->golomtView) && $this->golomtView) ? 'Домайн' : 'Ажилтны код' ?>';
    var _isAdminApproved = '<?php echo (isset($this->isAdmin) && $this->isAdmin) ? '1' : '0' ?>';
    windowId = "#tnaTimeBalanceWindow<?php echo $this->uniqId ?>";
    
    $(function () {
        $('.mergeCelltnaEmployeeBalance').hide();
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.css"/>');
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-multiselect/css/jquery.multiselect.filter.css"/>');
        $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.js" ).done(function( script, textStatus ) {
            $.getScript("assets/custom/addon/plugins/jquery-multiselect/js/jquery.multiselect.filter.js").done(function( script, textStatus ) {
                /*$('.balanceDepartmentId_<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-balanceDepartmentId_<?php echo $this->uniqId ?>-option-0').parent().parent().remove(); */
                
                $('.causeTypeId_<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-causeTypeId_<?php echo $this->uniqId ?>-option-0').parent().parent().remove(); 
                
                $('.employeeStatus_<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-employeeStatus_<?php echo $this->uniqId ?>-option-0').parent().parent().remove(); 
                
                $('.ui-multiselect-menu').attr('style', 'width: 360px'); 

                $('.groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupId_<?php echo $this->uniqId ?>-option-0').parent().parent().remove();                  
                
                /*var balanceDepartmentVa_<?php echo $this->uniqId ?> = $('.balanceDepartmentId_<?php echo $this->uniqId ?>').val();
                if (balanceDepartmentVa_<?php echo $this->uniqId ?> != '') {
                    $.ajax({
                        type: 'post',
                        url: 'mdtime/getDepartmentGroupList',
                        data: {departmentId: balanceDepartmentVa_<?php echo $this->uniqId ?>},
                        dataType: "json",
                        beforeSend: function() {},
                        success: function(detail) {
                            Core.unblockUI();

                            $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>lanceC').empty();
                            $('.ui-multiselect', '.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').addClass('ui-state-disabled').attr('aria-disabled', 'true').attr('diabled', 'diabled');
                            var ticketGroup = true;

                            var html = '<select name="groupId[]" multiple="multiple" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title="">';
                            if (detail.length > 0) {
                                $.each(detail, function (key, value) {
                                    html += '<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                                });
                                ticketGroup = false;
                            }
                            html += '</select>'; 

                            if (ticketGroup) {
                                html = '<select disabled = "disabled"  name="groupId[]" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title=""></select>';
                            }
                            $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').html(html);
                            $('.groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                            $('#ui-multiselect-groupIdTimeEmployeeBalance-option-0').parent().parent().remove();  

                        },
                        error: function() {
                            Core.unblockUI();
                            new PNotify({
                                title: 'Error',
                                text: 'error',
                                type: 'error',
                                sticker: false
                            });
                        }
                    });
                }*/ 
                
            });
        });
        $('.radio').find('span').addClass('mt0');

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
    var selectedDepartmentId<?php echo $this->uniqId ?> = [],
        selectedDepartmentText<?php echo $this->uniqId ?> = [],
        selectedDepartment<?php echo $this->uniqId ?> = false;

    $.jstree.defaults.search.ajax = true
    $('.list-jtree-<?php echo $this->uniqId ?>').on("changed.jstree", function (e, data) {
        if (data.action === "select_node") {
            if ($.inArray(data.node.id, selectedDepartmentId<?php echo $this->uniqId ?>) < 0) {
                selectedDepartment<?php echo $this->uniqId ?> = true;
                selectedDepartmentId<?php echo $this->uniqId ?>.push(data.node.id);
                selectedDepartmentText<?php echo $this->uniqId ?>.push(data.node.text);
            }
            selectNode(data.node.id);
            $.each(data.node.children_d, function (key, value) {
                selectNode(value);
                if (value != '#') {
                    if ($.inArray(value, selectedDepartmentId<?php echo $this->uniqId ?>) < 0) {
                        selectedDepartmentId<?php echo $this->uniqId ?>.push(value);
                        var getDepName = $('.list-jtree-<?php echo $this->uniqId ?>').jstree("get_node", value);
                        selectedDepartmentText<?php echo $this->uniqId ?>.push(getDepName.text);
                    }
                }
            });
        } else if (data.action === "deselect_node") {
            var _index = selectedDepartmentId<?php echo $this->uniqId ?>.indexOf(data.node.id);
            selectedDepartment<?php echo $this->uniqId ?> = true;
            selectedDepartmentId<?php echo $this->uniqId ?>.splice(_index, 1);
            selectedDepartmentText<?php echo $this->uniqId ?>.splice(_index, 1);
            deSelectNode(data.node.id);
            $.each(data.node.children_d, function (key, value) {
                var indexMid = selectedDepartmentId<?php echo $this->uniqId ?>.indexOf(value);
                if(indexMid >= 0) {
                    selectedDepartmentId<?php echo $this->uniqId ?>.splice(indexMid, 1);
                    selectedDepartmentText<?php echo $this->uniqId ?>.splice(indexMid, 1);
                    deSelectNode(value);
                }
            });
        } else if (data.action === "select_all") {
            selectedDepartment<?php echo $this->uniqId ?> = true;
            $(this).children().children().each(function(){
                selectedDepartmentId<?php echo $this->uniqId ?>.push($(this).attr('id'));
                selectedDepartmentText<?php echo $this->uniqId ?>.push($(this).text());
            });
        }
        if(!selectedDepartment<?php echo $this->uniqId ?>)
            return;
        
        $('.departmentId_<?php echo $this->uniqId ?>').val(selectedDepartmentId<?php echo $this->uniqId ?>);
        $('.departmentIdName_<?php echo $this->uniqId ?>').val(selectedDepartmentText<?php echo $this->uniqId ?>.join('__'));
        $('#selectedDepartmentNamesContainer_<?php echo $this->uniqId ?>').text(selectedDepartmentText<?php echo $this->uniqId ?>.join(', '));
        if(selectedDepartmentText<?php echo $this->uniqId ?>.length > 0)
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
        selectedDepartmentId<?php echo $this->uniqId ?> = [];
        selectedDepartmentText<?php echo $this->uniqId ?> = [];        
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("deselect_all");
    });
    var selectNode = function (id) {
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("select_node", id, true, true);
    };
    var deSelectNode = function (id) {
        $('.list-jtree-<?php echo $this->uniqId ?>').jstree("deselect_node", id, true, true);
    };    
    // End Select2 <-----        
        
        setTimeout(function(){
            var offsetTopExpression = $(".tna-sidebar-container").offset().top - 35;
            <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { ?>
                var dynamicHeightTna = $(window).height() - 350;
            <?php } else { ?>
                var dynamicHeightTna = $(window).height() - 320;
            <?php } ?>

            $(window).scroll(function(){
                if(tnaSidebarWidth > 0) {
                    var scrollPos = offsetTopExpression - $(this).scrollTop();
                    if(scrollPos <= 0) {
                        $(".tna-sidebar-container").addClass("tnasidbar-viewer-class").css({'width':(tnaSidebarWidth + 30)+'px'});
                        $(".tna-sidebar-container").find(".grid-row-content").css({'height':(dynamicHeightTna + 80)+'px'});
                    } else {
                        $(".tna-sidebar-container").find(".grid-row-content").css({'height':(dynamicHeightTna)+'px'});
                        
                        <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { ?>
                            $(".tna-sidebar-container").removeClass("tnasidbar-viewer-class").removeAttr('style').css('margin-top', '75px');
                        <?php } 
                            else { ?>
                                $(".tna-sidebar-container").removeClass("tnasidbar-viewer-class").removeAttr('style').css('margin-top', '9px');
                        <?php } ?>
                    }
                }
            });
        }, 600);
        
    });
    
    $('.balanceDepartmentId_<?php echo $this->uniqId ?>').on('change', function () {
        var thisval = $(this).val();
        $.ajax({
            type: 'post',
            url: 'mdtime/getDepartmentGroupList',
            data: {departmentId: thisval},
            dataType: "json",
            beforeSend: function() {},
            success: function(detail) {
                Core.unblockUI();
                $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').empty();
                $('.ui-multiselect', '.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').addClass('ui-state-disabled').attr('aria-disabled', 'true').attr('diabled', 'diabled');
                var ticketGroup = true;

                var html = '<select name="groupId[]" multiple="multiple" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title="">';
                if (detail.length > 0) {
                    $.each(detail, function (key, value) {
                        html += '<option value="' + value.ID + '">' + value.GROUPNAME + '</option>';
                    });
                    ticketGroup = false;
                }
                html += '</select>'; 

                if (ticketGroup) {
                    html = '<select disabled = "disabled" name="groupId[]" class="form-control input-xs input-xxlarge groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>" data-placeholder="- Сонгох -" tabindex="-1" title=""></select>';
                }

                $('.groupIdTimeEmployeeBalanceC_<?php echo $this->uniqId ?>').html(html);
                $('.groupIdTimeEmployeeBalance_<?php echo $this->uniqId ?>').multiselect({ noneSelectedText: '- Сонгох -', selectedList: 10}).multiselectfilter();
                $('#ui-multiselect-groupIdTimeEmployeeBalance-option-0').parent().parent().remove(); 
            },
            error: function() {
                Core.unblockUI();
                new PNotify({
                    title: 'Error',
                    text: 'error',
                    type: 'error',
                    sticker: false
                });
            }
        })
    });
    
    function onUserImageError(source) {
        source.src = "assets/core/global/img/user.png";
        source.onerror = "";
        return true;
    }
    
    function tnaRenderSidebar(row, index, $uniqId) {
        selectedDataRow = row;
        selectedDataRowIndex = index;
        index = (typeof index === 'undefined') ? '' : index;
        var selectedRowUniqueId = row.TIME_BALANCE_HDR_ID;
        var rightSidebarContent = $('.right-sidebar-content-' + $uniqId);
        
        Core.blockUI({animate: true});
        $.ajax({
            type: 'post',
            url: 'mdtimestable/getBalanceDetailListV3',
            data: {timeBalanceId: row.TIME_BALANCE_HDR_ID, balanceDate: row.BALANCE_DATE, employeeKeyId: row.EMPLOYEE_ID, uniqId: '<?php echo $this->uniqId ?>', depId: row.DEPARTMENT_ID},
            dataType: "json",
            beforeSend: function() {},
            success: function(detail) {
                PNotify.removeAll();
                if (detail.status === 'locked') {                    
                    new PNotify({
                        title: 'Info',
                        text: 'Уучлаарай түгжсэн байна.',
                        type: 'info',
                        sticker: false
                    });
                    return;
                }

                if (detail.status !== 'success') {
                    Core.unblockUI();
                    return;
                }
                
                var isStartTimeEdit = detail.isStartTimeEdit,
                    isEndTimeEdit = detail.isEndTimeEdit;                
                var detail = detail['causeType'];
                selectedDataRowDetail = detail['causeType'];
                
                var EMPLOYEE_NAME = row.LAST_NAME.substring(0, 1) + "." + row.FIRST_NAME;
                var _defferenceTime = row.DEFFERENCE_TIME;
                var _originalDefferenceTime = row.DEFFERENCE_TIME;
                
                var $addDay = (row.ADD_DAY != null) ? row.ADD_DAY : 1;
                var $addDayCheck = (row.IS_ZERO_TIME != '0' && row.IS_ZERO_TIME != null) ? 'checked = "checked"' : '';
                var $addDayHide = (row.IS_ZERO_TIME == '0' || row.IS_ZERO_TIME == null) ? 'display:none;' : 'display:inline-block;';
                
                var sideBarHtml = '<div id="' + selectedRowUniqueId + '" class="selectedRowDetail">';
                    sideBarHtml += '<input name="timeBalanceHdrId['+selectedRowUniqueId+']" data-name="timeBalanceHdrId" type="hidden" value="' + row.TIME_BALANCE_ID + '">';
                    sideBarHtml += '<input name="employeeName['+selectedRowUniqueId+']" data-name="employeeName" type="hidden" value="' + EMPLOYEE_NAME + '">';
                    sideBarHtml += '<input name="timeBalanceId['+selectedRowUniqueId+']" data-name="timeBalanceId" type="hidden" value="' + row.TIME_BALANCE_ID + '">';
                    sideBarHtml += '<input name="employeeId['+selectedRowUniqueId+']" data-name="timeBalanceId" type="hidden" value="' + row.EMPLOYEE_ID + '">';
                    sideBarHtml += '<input name="employeeKeyId['+selectedRowUniqueId+']" data-name="employeeKeyId" type="hidden" value="' + row.EMPLOYEE_KEY_ID + '">';
                    sideBarHtml += '<input name="departmentId['+selectedRowUniqueId+']" data-name="departmentId" type="hidden" value="' + row.DEPARTMENT_ID + '">';
                    sideBarHtml += '<input name="inTime['+selectedRowUniqueId+']" data-name="inTime" type="hidden" value="' + row.IN_TIME + '">';
                    sideBarHtml += '<input name="outTime['+selectedRowUniqueId+']" data-name="outTime" type="hidden" value="' + row.OUT_TIME + '">';
                    sideBarHtml += '<input name="balanceDate['+selectedRowUniqueId+']" data-name="balanceDate" type="hidden" value="' + row.BALANCE_DATE + '">';
                    sideBarHtml += '<input name="clearTime['+selectedRowUniqueId+']" data-name="clearTime" type="hidden" value="' + row.CLEAR_TIME + '">';
                    sideBarHtml += '<input name="unclearTime['+selectedRowUniqueId+']" data-name="unclearTime" type="hidden" value="' + row.UNCLEAR_TIME + '">';
                    sideBarHtml += '<input name="defferenceTime['+selectedRowUniqueId+']" data-name="defferenceTime" type="hidden" value="' + _defferenceTime + '">';
                    sideBarHtml += '<input name="originalDefferenceTime['+selectedRowUniqueId+']" data-name="originalDefferenceTime" type="hidden" value="' + _originalDefferenceTime + '">';
                    sideBarHtml += '<input name="faultType['+selectedRowUniqueId+']" data-name="faultType" type="hidden" value="' + row.FAULT_TYPE + '">';
                    sideBarHtml += '<input name="nightTime['+selectedRowUniqueId+']" data-name="nightTime" type="hidden" value="' + row.NIGHT_TIME + '">';
                    sideBarHtml += '<input name="earlyTime['+selectedRowUniqueId+']" data-name="earlyTime" type="hidden" value="' + row.EARLY_TIME + '">';
                    sideBarHtml += '<input name="lateTime['+selectedRowUniqueId+']" data-name="lateTime" type="hidden" value="' + row.LATE_TIME + '">';
                    sideBarHtml += '<input name="chBalanceDate['+selectedRowUniqueId+']" data-name="chBalanceDate" type="hidden" value="' + row.CH_BALANCE_DATE + '">';
                    sideBarHtml += '<input name="activetrIndex" type="hidden" value="' + index + '">';
                    sideBarHtml += '<input name="timeBalanceHdrId" type="hidden" value="' + row.TIME_BALANCE_ID + '">';

                    sideBarHtml += '<div class="card light bg-blue-hoki">';
                        sideBarHtml += '<div class="card-body">';
                            sideBarHtml += '<div class="clearfix w-100">';
                                sideBarHtml += '<a href="javascript:;" class="float-left thumb avatar border m-r">';
                                    sideBarHtml += '<img src="'+row.PICTURE+'" class="rounded-circle" id="sidebar-user-logo" onerror="onUserImageError(this);" style="width: 58px; height:58px;">';
                                sideBarHtml += '</a>';
                                sideBarHtml += '<div class="clear">';
                                    sideBarHtml += '<div class="h4 mt5 mb5 text-color-white" style="font-size: 12px !important">';
                                        sideBarHtml += '<div id="sidebar-user-name">'+EMPLOYEE_NAME+ ' ('+ row.EMPLOYEE_CODE +')'+'</div>';
                                        sideBarHtml += '<div id="sidebar-user-status">'+row.STATUS_NAME + ' - ' + row.POSITION_NAME+'</div>';
                                        sideBarHtml += '<div id="sidebar-user-type-name">'+(row.TYPE_NAME === null ? '' : row.TYPE_NAME)+'</div>';
                                        var employeeIntime = '';
                                        if (row.STARTTIME != 'null' && row.ENDTIME != null) {
                                            employeeIntime += row.STARTTIME + ' - '+ row.ENDTIME;
                                        }
                                        sideBarHtml += '<div id="sidebar-user-date">'+row.BALANCE_DATE+' '+employeeIntime +'</div>';
                                        
                                    sideBarHtml += '</div>';
                                sideBarHtml += '</div>';
                                sideBarHtml += '<span class="float-right" style="margin-right: -7px;"><button data-time-confirm="" type="button" style="padding:0px 7px 1px 7px; margin-right: 2px;" data-uniqid="'+$uniqId+'" id="" class="btn btn-sm btn-success employeeConfirmBtn float-right statusApproveBtn" data-status-id="1472634629956170" data-status-name="Баталсан" data-status-code="002" title="">Батлах</button></span>';
                            sideBarHtml += '</div>';
                        sideBarHtml += '</div>';
                    sideBarHtml += '</div>';
                    var _timeInit = 'timeInit';
                   
                    <?php if (defined('CONFIG_TOP_MENU') && CONFIG_TOP_MENU) { ?>
                        var dynamicHeightTnaCause = $(window).height() - 285;
                    <?php } else { ?>
                        var dynamicHeightTnaCause = $(window).height() - 245;
                    <?php } ?>        
                        
                    sideBarHtml += '<div class="panel panel-default bg-inverse grid-row-content" style="margin-top: -21px; height: '+dynamicHeightTnaCause+'px; overflow-y: auto; overflow-x: hidden;">';
                        sideBarHtml += '<table class="table sheetTable sheetTableTms">';
                            sideBarHtml += '<tbody>';
                                
                                sideBarHtml += '<tr class="intime">';
                                    sideBarHtml += '<td class="left-padding hide"></td>';
                                    sideBarHtml += '<td class="left-padding hide"></td>';
                                    sideBarHtml += '<td style="color:' + color + ' !important;" class="left-padding">ИРСЭН</td>';
                                    sideBarHtml += '<td style="min-width: 42px !important; width: 42px">';
                                        sideBarHtml += '<input class="intime '+ _timeInit +' " ' + (!isStartTimeEdit ? 'readonly style="background-color: #EBEBE4" ' : '') + 'name="change_intime['+selectedRowUniqueId+']" placeholder="hh:mm" onchange="changeListenerIO(this);" style="width: 40px" type="text" value="'+ row.IN_TIME +'">';
                                        sideBarHtml += '<input name="detect_change_intime['+selectedRowUniqueId+']" placeholder="hh:mm" disabled type="hidden" value="">';
                                    sideBarHtml += '</td>';
                                    sideBarHtml += '<td>'
                                                        //+ '<input type="hidden" name="descriptionIn['+selectedRowUniqueId+']" value=""/>'
                                                        //+ '<button type="button" id="" class="btn btn-sm float-right" title="Тайлбар"><i class="fa fa-comment"></i> </button>'
                                                    + '</td>';
                                sideBarHtml += '</tr>';

                                sideBarHtml += '<tr class="outtime">';
                                    sideBarHtml += '<td class="left-padding hide"></td>';
                                    sideBarHtml += '<td class="left-padding hide"></td>';
                                    sideBarHtml += '<td style="color:' + color + ' !important;" class="left-padding">ГАРСАН</td>';
                                    sideBarHtml += '<td>';
                                        sideBarHtml += '<input class="outtime '+ _timeInit +' " ' + (!isEndTimeEdit ? 'readonly style="background-color: #EBEBE4" ' : '') + 'name="change_outtime['+selectedRowUniqueId+']" placeholder="hh:mm" style="width: 40px" type="text" onchange="changeListenerIO(this);" value="'+ row.OUT_TIME +'">';
                                        sideBarHtml += '<input name="detect_change_outtime['+selectedRowUniqueId+']" placeholder="hh:mm" disabled type="hidden" value="">';                                        
                                    sideBarHtml += '</td>';
                                    sideBarHtml += '<td>'
                                                        + '<div class="checkbox-list" style="height: 31px; float: left; margin:0px !important; ">'
                                                        + '<input type="checkbox" id="isAddonDate" name="isAddonDate" '+ $addDayCheck +' class="isAddonCheckSub" value="1">'
                                                        + '<label class="checkbox-inline addonRequiredLabel mt0" style="'+ $addDayHide +'">'
                                                            +'<input type="text" id="addonDate" name="addonDate" '
                                                                + ' class="longInit" value="'+ $addDay +'" '
                                                                + 'maxlength="1" style="width:25px;">'
                                                        + '</label>'
                                                        + '</div>'
                                                        //+ '<input type="hidden" name="descriptionOut['+selectedRowUniqueId+']" value=""/>'
                                                        //+ '<button type="button" id="" class="btn btn-sm float-right" title="Тайлбар"><i class="fa fa-comment"></i> </button>'
                                                    + '</td>';
                                sideBarHtml += '</tr>';
                                
                                sideBarHtml += '<tr>';
                                sideBarHtml += '<td colspan="5" class="left-padding"><?php echo $this->balanceBtn['item'];?></td>';
                                sideBarHtml += '</tr>';
                                
                                for (var i = 0; i < detail.length; i++) {
                                    var _btn = '<div class="float-right employeeBalanceDescriptionContainer" style="padding-right: 24px">';
                                    var color = "#000";
                                    var disabled = (detail[i].IS_EDIT === '0') ? 'disabled="disabled"' : '';
                                    
                                    if ((detail[i].NAME).toUpperCase() === 'ГАДУУР АЖИЛЛАСАН' ||
                                            (detail[i].NAME).toUpperCase() === 'ТОМИЛОЛТ' ||
                                            (detail[i].NAME).toUpperCase() === 'ЧӨЛӨӨТЭЙ' ||
                                            (detail[i].NAME).toUpperCase() === 'ЭЭЛЖИЙН АМРАЛТ' ||
                                            (detail[i].NAME).toUpperCase() === 'БАЯРААР АЖИЛЛАСАН' ||
                                            (detail[i].NAME).toUpperCase() === 'ТАСАГ ШИЛЖСЭН' ||
                                            (detail[i].NAME).toUpperCase() === 'ИЛҮҮ ЦАГ' ||
                                            (detail[i].NAME).toUpperCase() === 'ХУВИЙН ШАЛТГААН' ||
                                            (detail[i].NAME).toUpperCase() === 'ӨВЧТЭЙ'
                                            ) {
                                        var descriptionBtnClassName = 'btn btn-sm btn-success';
                                        var descriptionBtnClickEvent = 'onclick="clickCallDialogOpen(this)"';
                                        if (detail[i].DESCRIPTION_CAUSE_DTL == 0) {
                                            descriptionBtnClassName = 'btn btn-sm grey-cascade';
                                            descriptionBtnClickEvent = '';
                                        }
                                        //_btn += '<button type="button" class="'+ descriptionBtnClassName +' employeeOutWorkBtn ml0 mr0" '+ descriptionBtnClickEvent + ' title="' + detail[i].NAME + '"><i class="fa fa-list"></i> </button>';
                                    }
                                    /*if ((detail[i].NAME).toUpperCase() === 'ОРЛОН ХАВСАРСАН') {
                                        if (parseFloat(detail[i].COUNTT) > 0) {
                                            _btn += '<button type="button" '+ disabled +' class="btn btn-sm red employeeFillInForBtn ml0 mr0" title="' + detail[i].NAME + '"><i class="fa fa-list"></i> </button>';
                                        }
                                        color = (parseFloat(detail[i].COUNTT) > 0) ? '#F00' : "#000";
                                    }*/
                                    if ((detail[i].NAME).toUpperCase() === 'ОРЛОН ХАВСАРСАН') {
                                        TEMPED_FILLINFORDATA = {
                                            EMPLOYEE_NAME: EMPLOYEE_NAME,
                                            EMPLOYEE_ID: row.EMPLOYEE_ID,
                                            EMPLOYEE_KEY_ID: row.EMPLOYEE_KEY_ID,
                                            BALANCE_DATE: row.BALANCE_DATE,
                                            BALANCE_DTL_NAME: detail[i].NAME,
                                            BALANCE_TYPE_ID: detail[i].CAUSE_TYPE_ID
                                        };
                                    }
                                    
                                    _btn += '<button type="button" '+ disabled +' id=' + i + ' class="btn btn-sm employeeBalanceDescription ml0 mr0" title="Тайлбар"><i class="fa fa-comment"></i> </button>';
                                    _btn += '<input type="hidden" name="description['+selectedRowUniqueId+']['+ detail[i].CAUSE_TYPE_ID +']" data-description="description"  class="causeDescriptionClassName_' + detail[i].CAUSE_TYPE_ID + '" value="' + (detail[i].DESCRIPTION_CAUSE === undefined ? '' : detail[i].DESCRIPTION_CAUSE) + '">';
                                    _btn += '</div>';

                                    var ctypeDisable = '';
                                    //row.WFM_STATUS_CODE === 'confirmedbyceo'
                                    
                                    if((detail[i].CODE === '1014') && row.DEFFERENCE_TIME < 0)
                                        ctypeDisable = ' disabled';

                                    var readOnly = detail[i].CAUSE_TYPE_ID == '17' ? ' readonly="readonly" style="background-color: #EBEBE4"' : '';
                                    sideBarHtml += '<tr class="causeClassName_' + detail[i].CAUSE_TYPE_ID + '">';
                                        sideBarHtml += '<td class="left-padding hide"></td>';
                                        sideBarHtml += '<td class="left-padding hide"></td>';
                                        sideBarHtml += '<td style="color:' + color + ' !important;" class="left-padding">' + detail[i].NAME + '</td>';
                                        sideBarHtml += '<td>';
                                            sideBarHtml += '<input name="cause_type_id['+selectedRowUniqueId+'][]" data-name="cause_type_id" type="hidden" value="' + detail[i].CAUSE_TYPE_ID + '">';
                                            sideBarHtml += '<input name="cause_type_value['+selectedRowUniqueId+']['+ detail[i].CAUSE_TYPE_ID +']" data-name="cause_type_value" type="hidden" value="' + detail[i].V_TIME + '">';
                                            sideBarHtml += '<input '+ disabled +' class="cause_type_value_display '+ _timeInit +' "'+ctypeDisable+' id="cause_type_code_'+detail[i].CODE+'" data-name="cause_type_value_display"'+readOnly+' name="cause_type_value_display['+selectedRowUniqueId+'][]" placeholder="hh:mm" type="text" value="' + minutToTime(detail[i].V_TIME) + '" onchange="setMinut(this);">';
                                        sideBarHtml += '</td>';
                                        sideBarHtml += '<td>' + _btn + '</td>';
                                    sideBarHtml += '</tr>';
                                }
                                
                                sideBarHtml += '<tr class="hidden">';
                                    sideBarHtml += '<td class="left-padding">Экспорт</td>';
                                    sideBarHtml += '<td style="!important; padding-right:2px;"></td>';
                                    sideBarHtml += '<td><button style="border-radius:0 !important;" type="button" class="btn btn-sm employeeBalanceExportListExcel ml0 mr0" title="Экспорт"><i class="fa fa-file-excel-o"></i> </button></td>';
                                sideBarHtml += '</tr>';
                                
                                sideBarHtml += '<tr>';
                                    sideBarHtml += '<td style="color:#FFF !important;" class="left-padding"></td>';
                                    sideBarHtml += '<td style="padding-right:2px;"></td>';
                                    sideBarHtml += '<td>';
                                            sideBarHtml += '<?php echo $this->balanceBtn['item'];?>';
                                    sideBarHtml += '</td>';
                                sideBarHtml += '</tr>';

                                sideBarHtml += '<tr id ="EMPLOYEE_DESCRIPTION_' + row.EMPLOYEE_KEY_ID + '_' + row.CH_BALANCE_DATE + '" class="hidden">';
                                    sideBarHtml += '<td style="background-color:#f36a5a !important; color:#FFF !important;" class="left-padding">CASE Дэлгэрэнгүй</td>';
                                    sideBarHtml += '<td style="background-color:#f36a5a !important; padding-right:2px;"></td>';
                                    sideBarHtml += '<td>';
                                        sideBarHtml += '<button style="border-radius:0 !important; border-left: 3px solid #26a69a;" type="button" class="btn btn-sm red employeeBalanceDetailDescription ml0 mr0" title="Дэлгэрэнгүй"><i class="fa fa-list-alt"></i></button>';
                                    sideBarHtml += '</td>';
                                sideBarHtml += '</tr>';
                            sideBarHtml += '</tbody>';
                        sideBarHtml += '</table>';
                    sideBarHtml += '</div>';
                    sideBarHtml += '<div class="panel panel-default bg-inverse grid-plan-time-more"></div>';
                sideBarHtml += '</div>';
                
                rightSidebarContent.find(".selectedRowDetail").hide();
                rightSidebarContent.html(sideBarHtml);
                
                setTimeout(function(){
                    tnaSidebarWidth = $(".tna-sidebar-container").width();
    
                    $('.isAddonCheckSub').click(function() {
                        var $this = $(this),
                            $addonRequiredLabel = $this.closest('.checkbox-list').find('.addonRequiredLabel');

                        if ($this.closest('span').hasClass('checked')) {
                            $addonRequiredLabel.show();
                            $('#uniform-isAddonDate').addClass('uniform-isAddonDate');

                            $addonRequiredLabel.find('input').val('1');
                        } else {
                            $('#uniform-isAddonDate').removeClass('uniform-isAddonDate');
                            $addonRequiredLabel.hide();
                            $addonRequiredLabel.find('input[type=checkbox]').attr('checked', false);
                            $addonRequiredLabel.find('input[type=checkbox]').closest('span').removeClass('checked');
                        }
                    });
                }, 300);
                
                Core.unblockUI();
            },
            error: function() {
                Core.unblockUI();
                new PNotify({
                    title: 'Error',
                    text: 'error',
                    type: 'error',
                    sticker: false
                });
            }
        }).done(function(){
            Core.unblockUI();
            if($().inputmask){
              $("input.timeInit", rightSidebarContent).inputmask({
                mask: "s:s",
                placeholder: "__:__",
                alias: "datetime",
                hourFormat: "24"
              });
              Core.initDateTimeInput(rightSidebarContent);
              Core.initUniform(rightSidebarContent);
            }
        });
    }
    
    function groupSelectableGrid(metaDataCode, chooseType, elem, rows) {
        var _selectedRowId = '';
        var _selectedRowName = '';
        var _selectedRowCode = '';
        if (rows.length > 0) {
            $.each(rows, function(key, row){
                if (key == 0) {
                    _selectedRowId   = row.id;
                    _selectedRowCode = row.code;
                    _selectedRowName = row.name;
                }
                else {
                    _selectedRowId   = _selectedRowId+','+row.id;
                    _selectedRowCode = _selectedRowCode+','+row.code;
                    _selectedRowName = _selectedRowName+','+row.name;
                }
            });
        }
        $("#groupId", windowId).val(_selectedRowId);
        $("#groupCode_displayField", windowId).val(_selectedRowCode);
        $("#groupName_nameField", windowId).val(_selectedRowName);
    }
    
    $('.balanceClear', windowId).on('click', function () {
        $("#groupId", windowId).val('');
        $("#groupCode_displayField", windowId).val('');
        $("#groupName_nameField", windowId).val('');
    });
        
    $('.grid-expand-cell-tnaEmployeeBalance', windowId).on('click', function() {
        var mergeBtn = $(this);
        var index = 1;
        
        if (mergeBtn.hasClass("active")) {
            mergeBtn.removeClass("active").addClass("init-merge-cell");
            var count = $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('getRows').length;
            
            for ( var i = 0; i < count; i++ ) {
                $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('expandRow', i);
                index++;
            }

        } else {
            mergeBtn.addClass("active").removeClass("init-merge-cell");
            var count = $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('getRows').length;
            
            for (var i=0; i < count; i++) {
                $('#tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('collapseRow', i);
                index++;
            }
        }
    });
    
    $("body").on("click", "#tnaTimeBalanceForm" + " .isLock", function () {
        var rows = $('body #tna-balance-data-grid-<?php echo $this->uniqId ?>').datagrid('getSelections');
        
        if (rows.length == 0) {
            new PNotify({
                title: 'Анхааруулга',
                text: 'Мөр сонгоно уу?',
                type: 'warning'
            });
            return;
        }
        var isDiffUser = false;
        $.each(rows, function(key, row) {
            if (row.LOCK_USER_ID != null) {
                if (row.LOCK_USER_ID != '<?php echo Ue::sessionUserId(); ?>') {
                    isDiffUser = true;
                }
            }
        });
        
        if (isDiffUser) {
            new PNotify({
                title: 'Анхааруулга',
                text: 'Өөр хэрэглэгч түгжсэн эсвэл ямар нэг нүд нүд сонгоогүй байна',
                type: 'warning'
            });
        } else {
            if (userSessionIsFull()) {
                new PNotify({
                    title: 'Анхааруулга',
                    text: 'Өөрчлөлт хийх хязгаар хэтэрсэн байна.',
                    type: 'warning'
                });
            } else {
                callIsLockBalanceDialog(rows);
            }
        }
    });
    
    function getLogTimeAttendanceData_<?php echo $this->uniqId ?>(element, timeBalanceHdrId) {
        var dialogName = '#dialog-timebalanceHdrLogData';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        
        $.ajax({
            type: 'post',
            url: 'mdtime/timebalanceHdrLogData',
            dataType: "json",
            data: {timeBalanceHdrId: timeBalanceHdrId},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                var html = '';
                html = '<table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1">';
                html += '</thead>';
                    html += '<tr>';
                        html += '<td>№</td>';
                        html += '<td>Өөрчлөсөн өдөр</td>';
                        html += '<td>Ирсэн цаг</td>';
                        html += '<td>Явсан цаг</td>';
                        html += '<td>Өөрчлөсөн хэрэглэгч</td>';
                    html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                var _index = 1;
                $.each(data, function(index, row) {
                    html += '<tr>';
                        html += '<td>'+ _index +'</td>';
                        html += '<td>'+ row.CREATED_DATE +'</td>';
                        html += '<td>'+ ((row.IN_TIME) ? row.IN_TIME : '') +'</td>';
                        html += '<td>'+ ((row.OUT_TIME) ? row.OUT_TIME  : '')+'</td>';
                        html += '<td>'+ row.CREATED_USER +'</td>';
                    html += '</tr>';
                    _index++;
                });
                
                html += '</tbody>';
                html += '</table>';
                
                $(dialogName).empty().html(html);
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: '430',
                    height: 'auto',
                    modal: true,
                    close: function () {
                        $(dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).empty().dialog('destroy').remove();
                        }
                    }]
                });
                $(dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                Core.unblockUI();
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: 'error',
                    type: 'error',
                    sticker: false
                });
            }
        }).done(function(){
            Core.initInputType();
        });
    }
</script>