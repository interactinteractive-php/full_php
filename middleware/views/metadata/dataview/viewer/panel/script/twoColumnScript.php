<script type="text/javascript">

var objectdatagrid_<?php echo $this->metaDataId; ?> = $('#objectdatagrid-<?php echo $this->metaDataId; ?>');
var panelDv_<?php echo $this->uniqId; ?> = $('div[data-uniqid="<?php echo $this->uniqId; ?>"]');   
var firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
var secondList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-second-list');
var viewProcess_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-process');
var secondListName_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('[data-secondlist-name="1"]');
var idField_<?php echo $this->uniqId; ?> = '<?php echo $this->idField; ?>';
var nameField_<?php echo $this->uniqId; ?> = '<?php echo $this->nameField; ?>';
var secondFilter_<?php echo $this->uniqId; ?> = '';
var subQueryId_<?php echo $this->uniqId; ?> = null;
var subQueryHeight_<?php echo $this->uniqId; ?> = <?php echo (isset($this->row['subQuery']) && $this->row['subQuery']) ? 20 : 0; ?>;
var dvTwoWindowHeight_<?php echo $this->uniqId; ?> = $(window).height();
var dvTwoFixHeight_<?php echo $this->uniqId; ?> = 0;
var dvTwoFirstListHeight_<?php echo $this->uniqId; ?> = 0;
var groupColumn_<?php echo $this->uniqId; ?>;
var twoListPopupSearch_<?php echo $this->uniqId; ?> = null;
var dvTwoSecondListHdrActions_<?php echo $this->uniqId; ?> = true;
var dvTwoPanelLoad_<?php echo $this->uniqId; ?> = false;
var dvFisrtColFilter_<?php echo $this->uniqId; ?> = '<?php echo issetParam($this->row['IS_FIRST_COL_FILTER']); ?>';
var clickRowId_<?php echo $this->uniqId; ?> = null;
    
var splitobj = Split(["#split-second-sidebar-<?php echo $this->uniqId; ?>","#split-content-<?php echo $this->uniqId; ?>"], {
    elementStyle: function (dimension, size, gutterSize) { 
        $(window).trigger('resize');
        return {'flex-basis': 'calc(' + size + '% + 20px)'};
    },
    gutterStyle: function (dimension, gutterSize) { 
        return {'flex-basis':  gutterSize + 'px'}; 
    },
    sizes: [20,60,20],
    minSize: 50,
    gutterSize: 6,
    cursor: 'col-resize'
});
    
if (typeof isDataViewPanelTwoColumn === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/dataview/panelType/twoColumn.js'); ?>');
}

setTimeout(function() {
    
    dvTwoFixHeight_<?php echo $this->uniqId; ?> = dvTwoWindowHeight_<?php echo $this->uniqId; ?> - secondList_<?php echo $this->uniqId; ?>.offset().top - 40;
    dvTwoFirstListHeight_<?php echo $this->uniqId; ?> = dvTwoFixHeight_<?php echo $this->uniqId; ?> - subQueryHeight_<?php echo $this->uniqId; ?>;
        
    $(function () {
        
        panelDv_<?php echo $this->uniqId; ?>.find('.dv-twocol-first-sidebar').css({'overflow': 'auto', 'height': dvTwoFixHeight_<?php echo $this->uniqId; ?> + 50});
        firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvTwoFirstListHeight_<?php echo $this->uniqId; ?>});
        secondList_<?php echo $this->uniqId; ?>.css({'overflow-x': 'hidden', 'overflow-y': 'auto', 'max-height': dvTwoFixHeight_<?php echo $this->uniqId; ?>});

        panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-listmetadataid]:not(.click-disabled)', function(e) {

            var $this          = $(this);
            var rowId          = $this.data('id');
            var rowData        = $this.data('rowdata');
            var $parent        = $this.parent();
            var isChild        = $parent.hasClass('nav-item-submenu');
            var isSubItem      = $this.hasClass('v2');
            var listMetaDataId = $this.data('listmetadataid');
            var $openMenu      = firstList_<?php echo $this->uniqId; ?>.find('.nav-item-open');
            var openMenuCount  = $openMenu.length;
            
            $this.addClass('click-disabled');
            firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
            $this.addClass('dv-twocol-f-selected');
            
            if (rowData.hasOwnProperty('isgroupcolumn') && rowData.isgroupcolumn == '1' && listMetaDataId) {
                
                $.ajax({
                    type: 'post',
                    url: 'mdobject/dvPanelChildDataList',
                    data: {
                    dvId: '<?php echo $this->metaDataId; ?>', 
                        id: rowId, 
                        listMetaDataId: listMetaDataId, 
                        criteria: $this.data('listmetadatacriteria'), 
                        params: panelDv_<?php echo $this->uniqId; ?>.find('.dv-paneltype-filter-form').serialize(), 
                        isIgnoreSecond: 1, 
                        subQueryId: subQueryId_<?php echo $this->uniqId; ?>, 
                        formFilter: dvFisrtColFilter_<?php echo $this->uniqId; ?>
                    }, 
                    dataType: 'json', 
                    success: function(data) {
                        buildSecondListByCheck('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', listMetaDataId, rowId, $this, false, data.treeData);
                        $this.removeClass('click-disabled');
                    }
                });
                
            } else {
                
                if (!isSubItem && openMenuCount) {
                    $openMenu.not($parent).removeClass('nav-item-open');
                    firstList_<?php echo $this->uniqId; ?>.not($parent).find('.nav-group-sub').hide();
                }

                if (isSubItem) {
                    var $subMenuParent = $this.closest('ul.nav-group-sub');
                    var $openSubMenu = $subMenuParent.find('.nav-item-open');
                    var openSubMenuCount = $openSubMenu.length;

                    if (openSubMenuCount) {
                        $openSubMenu.not($parent).removeClass('nav-item-open');
                        $openSubMenu.not($parent).find('.nav-group-sub').hide();
                    }
                }
                
                if (rowData.hasOwnProperty('firstlistmenuopendvid') && rowData.firstlistmenuopendvid && e.originalEvent) {
                    
                    var listUrl = 'mdobject/dataview/<?php echo $this->metaDataId; ?>?pdfid=' + rowId;
                    var mmid = Core.getURLParameter('mmid');
                    
                    if (mmid) {
                        listUrl += '&mmid=' + mmid;
                    }
                    
                    window.history.pushState('module', 'Veritech ERP', listUrl);
                }

                if (isChild) {

                    if ($parent.hasClass('nav-item-open')) {

                        $parent.removeClass('nav-item-open');
                        $parent.find('.nav-group-sub').hide();

                        buildSecondListByCheck('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', listMetaDataId, rowId, $this, false);
                        
                        $this.removeClass('click-disabled');
                        
                        return;
                    }

                    if ($parent.find('.nav-group-sub').length == 0) {
                        
                        if (rowData.hasOwnProperty('ispaid') && (rowData.ispaid === '0' || !rowData.ispaid) && getConfigValue('usePaymentForm') == '1') {
                            $this.removeClass('click-disabled');
                            if (typeof isProjectAddonScript === 'undefined') {
                                $.getScript(URL_APP + 'projects/assets/custom/js/script.js').done(function() {
                                    $("head").append('<link rel="stylesheet" type="text/css" href="projects/assets/custom/css/style.css"/>');
                                    callPaymentForm($this, rowData, '<?php echo $this->metaDataId; ?>');
                                });
                            } else {
                                callPaymentForm($this, rowData, '<?php echo $this->metaDataId; ?>');
                            }

                            return;
                        }

                        $.ajax({
                            type: 'post',
                            url: 'mdobject/dvPanelChildDataList',
                            data: {
                            dvId: '<?php echo $this->metaDataId; ?>', 
                                id: rowId, 
                                listMetaDataId: listMetaDataId, 
                                criteria: $this.data('listmetadatacriteria'), 
                                params: panelDv_<?php echo $this->uniqId; ?>.find('.dv-paneltype-filter-form').serialize(), 
                                isIgnoreSecond: 1, 
                                subQueryId: subQueryId_<?php echo $this->uniqId; ?>, 
                                formFilter: dvFisrtColFilter_<?php echo $this->uniqId; ?>
                            }, 
                            dataType: 'json', 
                            success: function(data) {

                                var treeData = data.treeData;

                                if (treeData.length) {

                                    var subMenu = '', subMenuClass = '', icon = '', listMetaDataCriteria = '', metaTypeId = '';

                                    for (var key in treeData) {

                                        subMenuClass = '';
                                        icon = '';
                                        metaTypeId = '';
                                        listMetaDataCriteria = '';

                                        if (treeData[key].hasOwnProperty('childrecordcount') 
                                            && treeData[key]['childrecordcount'] 
                                            && (
                                                (treeData[key].hasOwnProperty('isgroupcolumn') && treeData[key]['isgroupcolumn'] != '1') 
                                                || !treeData[key].hasOwnProperty('isgroupcolumn') 
                                                )
                                            ) {
                                            subMenuClass = ' nav-item-submenu';
                                        } 

                                        if (treeData[key].hasOwnProperty('icon') && treeData[key]['icon']) {
                                            icon = '<i class="'+treeData[key]['icon']+' font-weight-bold" style="color: '+treeData[key]['color']+';"></i> ';
                                            subMenuClass += ' with-icon';
                                        }

                                        if (treeData[key].hasOwnProperty('listmetadatacriteria') && treeData[key]['listmetadatacriteria']) {
                                            listMetaDataCriteria = treeData[key]['listmetadatacriteria'];
                                        }
                                        
                                        if (treeData[key].hasOwnProperty('metatypeid') && treeData[key]['metatypeid']) {
                                            metaTypeId = treeData[key]['metatypeid'];
                                        }                                        

                                        subMenu += '<li class="nav-item'+subMenuClass+'"><a href="javascript:void(0);" data-id="' + treeData[key][idField_<?php echo $this->uniqId; ?>] + '" data-listmetadataid="' + treeData[key]['metadataid'] + '" data-listmetadatacriteria="'+listMetaDataCriteria+'" data-metatypeid="'+metaTypeId+'" data-rowdata="'+htmlentities(JSON.stringify(treeData[key]), 'ENT_QUOTES', 'UTF-8')+'" class="nav-link v2" title="' + (typeof treeData[key]['longtextshow'] !== 'undefined' ? treeData[key]['longtextshow'] : '') + '">' + icon + treeData[key][nameField_<?php echo $this->uniqId; ?>];
                                        if (treeData[key].hasOwnProperty('iscountmeta') && treeData[key]['iscountmeta']) {
                                            subMenu += '<span class="badge badge-success ml-auto left-menu-count-meta position-relative mr-3" data-id="' + treeData[key][idField_<?php echo $this->uniqId; ?>] + '" data-listmetadatacriteria="'+listMetaDataCriteria+'" data-counmetadataid="' + treeData[key]['metadataid'] + '"><i class="fa fa-spinner fa-spin mr-0" style="font-size: 8px !important"></i></span>';
                                        }
                                        subMenu += '</a></li>';                                        
                                    }

                                    $parent.append('<ul class="nav nav-group-sub" style="display: block;">'+subMenu+'</ul>');
                                    $parent.addClass('nav-item-open');
                                    getLeftMenuCount(false, $parent.find('ul'));
                                    
                                    if (panelDv_<?php echo $this->uniqId; ?>.find('#two-column-filter-id').length) {
                                        panelDv_<?php echo $this->uniqId; ?>.find('a[data-id="'+panelDv_<?php echo $this->uniqId; ?>.find('#two-column-filter-id').attr('data-root')+'"]').click();
                                    }
                                }

                                buildSecondListByCheck('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', listMetaDataId, rowId, $this, false);
                                
                                $this.removeClass('click-disabled');
                            }
                        });

                    } else {
                        
                        $parent.addClass('nav-item-open');
                        $parent.find('.nav-group-sub').show();
                        
                        $this.removeClass('click-disabled');
                    }

                } else {
                    
                    buildSecondListByCheck('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', listMetaDataId, rowId, $this, false);
                    
                    $this.removeClass('click-disabled');
                }
            }

            if ($('.removeRenderProcessRightBtn').length && $(".removeRenderProcessRightBtn").is(":visible")) {
                $('.removeRenderProcessRightBtn').trigger('click');
            }
        });
        
        firstList_<?php echo $this->uniqId; ?>.find('li.nav-item-menu-click > a[data-listmetadataid]:eq(0)').click();
        
        panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-listmetadataidgroup]', function() {
        
            var $this   = $(this);
            var $parent = $this.parent();
            
            var rowId          = $this.data('id');
            var listMetaDataId = $this.data('listmetadataidgroup');
            
            $parent.find('.active').removeClass('active');
            $this.addClass('active');
            
            groupColumn_<?php echo $this->uniqId; ?> = $this;
            
            buildSecondListByCheck('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', listMetaDataId, rowId, $this, false, undefined, true);
        });

        panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-secondlistaddprocessid]', function() {

            var processId = $(this).data('secondlistaddprocessid');

            if (processId) {

                var fillDataParams = '', rowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').data('rowdata');

                if (typeof rowData !== 'object') {
                    rowData = JSON.parse(rowData);
                }
                
                if (rowData.hasOwnProperty('id')) {
                    fillDataParams = 'templateid='+rowData.id;
                }
                
                if (rowData.hasOwnProperty('secondlistaddprocessopenmode') && rowData.secondlistaddprocessopenmode == 'dialog') {
                    _processPostParam = fillDataParams;
                    callWebServiceByMeta(processId, true, '', false, {callerType:'dv',afterSaveNoAction:true,afterSaveNoActionFnc:"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>, '1')"});
                    return;
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/callMethodByMeta',
                    data: {
                        metaDataId: processId,
                        isDialog: false,
                        isHeaderName: false,
                        isBackBtnIgnore: 1, 
                        callerType: 'dv', 
                        openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>, \'1\')"}', 
                        fillDataParams: fillDataParams 
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').text(plang.get('add_btn'));
                            viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                            
                            var $firstRow = viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .row:eq(0)');

                            if ($firstRow.length) {
                                $firstRow.css({
                                    'overflow': 'auto', 
                                    'max-height': dvTwoFixHeight_<?php echo $this->uniqId; ?> - 5, 
                                    'margin-left': '-15px', 
                                    'margin-right': '-15px'
                                });
                            } else {
                                var $firstTabContent = viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .tabbable-line:eq(0) > .tab-content');
                                if ($firstTabContent.length) {
                                    $firstTabContent.css({
                                        'overflow-x': 'hidden', 
                                        'overflow-y': 'auto', 
                                        'max-height': dvTwoFixHeight_<?php echo $this->uniqId; ?> - 33
                                    });
                                }
                            }
                
                            Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
                            Core.unblockUI();
                        });
                    },
                    error: function() { alert('Error'); Core.unblockUI(); }
                });
            }
        });

        panelDv_<?php echo $this->uniqId; ?>.on('click', '[data-secondprocessid]', function() {
            
            var $this = $(this);
            var processId = $this.data('secondprocessid');
            var metaType = $this.data('secondtypecode');
            var rowData = $this.data('rowdata');
            var isReportTemplate = false;
            
            if (rowData.hasOwnProperty('ispaid') && (rowData.ispaid === '0' || !rowData.ispaid) && getConfigValue('usePaymentForm') == '1') {

                if (typeof isProjectAddonScript === 'undefined') {
                    $.getScript(URL_APP + 'projects/assets/custom/js/script.js').done(function() {
                        $("head").append('<link rel="stylesheet" type="text/css" href="projects/assets/custom/css/style.css"/>');
                        callPaymentForm($this, rowData, '<?php echo $this->metaDataId; ?>');
                    });
                } else {
                    callPaymentForm($this, rowData, '<?php echo $this->metaDataId; ?>');
                }

                return;
            }

            if (rowData.hasOwnProperty('listmetadataid') && rowData.listmetadataid) {
                
                processId = rowData.listmetadataid;
                metaType = 'dataview';
                
            } else {
                
                if ($this.hasAttr('data-countrt') && $this.attr('data-countrt') != '' && $this.attr('data-countrt') != '0') {
                
                    isReportTemplate = true;
                    processId = 1;
                    metaType = 'reportTemplate';

                    var reportTemplates = $this.data('rtmplts');

                    if (reportTemplates) {

                        var isLoopReportTemplate = false;
                        var countCriteriaRt = 0;

                        for (var rt in reportTemplates) {

                            var rtCriteria = reportTemplates[rt]['CRITERIA'];

                            if (rtCriteria) {

                                rtCriteria = rtCriteria.toLowerCase();

                                $.each(rowData, function(index, crow) {
                                    if (rtCriteria.indexOf(index) > -1) {
                                        crow = (crow === null) ? '' : crow.toLowerCase();
                                        var regex = new RegExp('\\b' + index + '\\b', 'g');
                                        rtCriteria = rtCriteria.replace(regex, "'" + crow.toString() + "'");
                                    }
                                });

                                try {
                                    if (eval(rtCriteria)) {
                                        isLoopReportTemplate = true;
                                    }
                                } catch (err) {}

                                countCriteriaRt++;
                            }
                        }

                        if (!isLoopReportTemplate && countCriteriaRt > 0) {

                            isReportTemplate = false;
                            processId = null;
                            metaType = null;
                        }
                    }
                }

                if ($this.hasAttr('data-secondprocessidcount')) {
                    var processCount = Number($this.attr('data-secondprocessidcount'));
                    var processCriterias = $this.attr('data-criterias');

                    if (processCount && processCriterias) {

                        var criterias = $this.attr('data-criterias').split('@@');
                        var isAccessProcess = false;

                        for (var c in criterias) {

                            var criteriaArr = criterias[c].split('$$');
                            var viewProcessId = criteriaArr[0];
                            var viewProcessType = criteriaArr[1];
                            var viewProcessCriteria = criteriaArr[2];

                            if (viewProcessCriteria != '') {

                                viewProcessCriteria = viewProcessCriteria.toLowerCase();

                                $.each(rowData, function(index, crow) {
                                    if (viewProcessCriteria.indexOf(index) > -1) {
                                        crow = (crow === null) ? '' : crow.toLowerCase();
                                        var regex = new RegExp('\\b' + index + '\\b', 'g');
                                        viewProcessCriteria = viewProcessCriteria.replace(regex, "'" + crow.toString() + "'");
                                    }
                                });

                                try {
                                    if (eval(viewProcessCriteria)) {
                                        processId = viewProcessId;
                                        metaType = viewProcessType;
                                        isAccessProcess = true;
                                    }
                                } catch (err) {}

                            } else {
                                processId = viewProcessId;
                                metaType = viewProcessType;
                                isAccessProcess = true;
                            }
                        }

                        if (!isAccessProcess && !isReportTemplate) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Info',
                                text: 'Нөхцөл тохирохгүй байна!',
                                type: 'info',
                                sticker: false
                            });
                            return;
                        }
                    }
                }
            }
            
            var dmMetaDataId = secondList_<?php echo $this->uniqId; ?>.data('listmetadataid');
            var rowId = $this.data('second-id');
            var panelDvFirstId = Core.getURLParameter('pdfid');
            
            secondList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
            $this.addClass('dv-twocol-f-selected');
            
            if (panelDvFirstId) {
                    
                var listUrl = 'mdobject/dataview/<?php echo $this->metaDataId; ?>?pdfid=' + panelDvFirstId;
                var mmid = Core.getURLParameter('mmid');
                
                listUrl += '&pdsid=' + rowId;
                
                if (mmid) {
                    listUrl += '&mmid=' + mmid;
                }

                window.history.pushState('module', 'Veritech ERP', listUrl);
            }

            if (processId) {
                
                var addonProcess = '', isProcessDropDown = false;
                                
                if ($this.hasAttr('data-othercriterias') && $this.attr('data-othercriterias') != '') {

                    var criterias = $this.attr('data-othercriterias').split('@@'), count = 0, dropdownButtons = '', 
                        buttonBarStyle = ($this.hasAttr('data-buttonbarstyle') ? $this.attr('data-buttonbarstyle') : '');

                    for (var c in criterias) {

                        var criteriaArr = criterias[c].split('$$');
                        var viewProcessId = criteriaArr[0];
                        var viewProcessType = criteriaArr[1];
                        var viewProcessCriteria = criteriaArr[2];
                        var thirdProcessId = '';
                        var thirdMetaType = '';

                        if (viewProcessCriteria != '') {

                            viewProcessCriteria = viewProcessCriteria.toLowerCase();

                            $.each(rowData, function(index, crow) {
                                if (viewProcessCriteria.indexOf(index) > -1) {
                                    crow = (crow === null) ? '' : crow;
                                    var regex = new RegExp('\\b' + index + '\\b', 'g');
                                    viewProcessCriteria = viewProcessCriteria.replace(regex, "'" + crow.toString() + "'");
                                }
                            });

                            try {
                                if (eval(viewProcessCriteria)) {
                                    thirdProcessId = viewProcessId;
                                    thirdMetaType = viewProcessType;
                                }
                            } catch (err) {}

                        } else {
                            thirdProcessId = viewProcessId;
                            thirdMetaType = viewProcessType;
                        }

                        if (thirdProcessId != '') {

                            var buttonName = criteriaArr[3];
                            var processName = criteriaArr[4];
                            var iconName = criteriaArr[5];
                            var buttonStyle = criteriaArr[6] ? criteriaArr[6] : 'btn-secondary';
                            var isConfirm = criteriaArr.hasOwnProperty(7) ? criteriaArr[7] : 0;
                            
                            addonProcess += '<button type="button" class="btn btn-sm '+buttonStyle+'" data-thirdprocessid="'+thirdProcessId+'" data-thirdprocesstype="'+thirdMetaType+'" data-isconfirm="'+isConfirm+'" title="'+(processName ? plang.get(processName) : '')+'">'+(iconName ? '<i class="fa '+iconName+'"></i> ' : '')+(buttonName ? plang.get(buttonName) : '')+'</button> ';
                            dropdownButtons += '<a href="javascript:;" data-thirdprocessid="'+thirdProcessId+'" data-thirdprocesstype="'+thirdMetaType+'" data-isconfirm="'+isConfirm+'" title="'+(processName ? plang.get(processName) : '')+'" class="dropdown-item">'+(iconName ? '<i class="fa '+iconName+'"></i> ' : '')+(buttonName ? plang.get(buttonName) : (processName ? plang.get(processName) : ''))+'</a>';
                            
                            count++;
                        }
                    }
                    
                    if (count > 1 && buttonBarStyle == 'dropdown') {
                        
                        isProcessDropDown = true;
                        
                        addonProcess = '<div style="float: left;width: 45px;">';
                            addonProcess += '<div class="btn-group mr10">';
                                addonProcess += '<button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></button>';
                                addonProcess += '<div class="dropdown-menu">';
                                    addonProcess += dropdownButtons;
                                addonProcess += '</div>';
                            addonProcess += '</div>';
                        addonProcess += '</div>';
                    }
                }

                if (metaType == 'process') {
                    
                    var obj = {
                        "this": $this, 
                        "rowData": rowData, 
                        "addonProcess": addonProcess, 
                        "processId": processId, 
                        "dmMetaDataId": dmMetaDataId, 
                        "isProcessDropDown": isProcessDropDown
                    };
                    
                    twoPanelCallProcess_<?php echo $this->uniqId; ?>(obj);

                } else if (metaType == 'workspace') {

                    var rowData = $this.data('rowdata');

                    if (typeof rowData !== 'object') {
                        rowData = JSON.parse(rowData);
                    }

                    $.ajax({
                        type: 'post',
                        url: 'mdworkspace/renderWorkSpace',
                        data: {metaDataId: processId, dmMetaDataId: dmMetaDataId, selectedRow: rowData},
                        dataType: 'json',
                        success: function(data) {
                            
                            if ($("link[href='middleware/assets/theme/" + data.theme + "/css/main.css']").length == 0) {
                                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.css"/>');
                            }

                            if (data.theme == 'theme10') {
                                $.getScript("assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js");
                                $.getScript("assets/custom/addon/plugins/jquery.sparkline.min.js");
                            }

                            viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.html).promise().done(function () {
                                
                                if (rowData.hasOwnProperty('ishideprocess') && rowData.ishideprocess == '1') {
                                    addonProcess = '';
                                }
                                
                                if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                                    panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess);
                                } else {
                                    panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess + $this.find('span:last').text());
                                }
                                
                                viewProcess_<?php echo $this->uniqId; ?>.find('.close-btn').remove();
                                Core.initAjax(viewProcess_<?php echo $this->uniqId; ?>);
                            });
                        }
                    });
                    
                } else if (metaType == 'reportTemplate') {
                    
                    PNotify.removeAll();
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdtemplate/getTemplateByRowData',
                        data: {metaDataId: dmMetaDataId, rowData: rowData},
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            if (data.status == 'success') {
                                
                                var renderHtml = (data.Html).replace('report-preview-container', 'report-preview-container rt-set-autoheight');
                                
                                viewProcess_<?php echo $this->uniqId; ?>.empty().append(renderHtml).promise().done(function () {
                                    
                                    var $viewTitleElem = panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title');
                                    
                                    if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                                        $viewTitleElem.html(addonProcess);
                                    } else {
                                        
                                        if (addonProcess) {
                                            $viewTitleElem.closest('.breadcrumb-line').attr('style', 'height: 70px;');
                                        }

                                        $viewTitleElem.html(addonProcess + '<span class="text-one-line w-100 pull-left mt-1" title="'+ $this.find('span:last').text() +'">' + $this.find('span:last').text() + '</span>');
                                    }
                                    
                                    if (rowData.hasOwnProperty('windowtype') && rowData.windowtype === '2') {
                                        if (viewProcess_<?php echo $this->uniqId; ?>.find('.addonwindowType').length > 0) {
                                            viewProcess_<?php echo $this->uniqId; ?>.find('.addonwindowType').empty();
                                        } else {
                                            viewProcess_<?php echo $this->uniqId; ?>.append('<div class="col-md-12 addonwindowType"></div>');
                                        }
                                    }
                                    
                                    viewProcess_<?php echo $this->uniqId; ?>.find('.report-preview-container').css('height', ($(window).height() - viewProcess_<?php echo $this->uniqId; ?>.offset().top - 90)+'px');
                                    
                                    Core.unblockUI();
                                });
                                
                            } else {
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                                Core.unblockUI();
                            }
                        },
                        error: function() { alert('Error'); Core.unblockUI(); }
                    });

                } else if (metaType == 'bookmark') {
                
                    if (processId == '1575702644228891') {
                    
                        $.ajax({
                            type: 'post',
                            url: 'mdpreview/filePreview',
                            data: {selectedRow: rowData},
                            dataType: 'json',
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(data) {
                                
                                viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.html).promise().done(function () {

                                    if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                                        panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess);
                                    } else {
                                        
                                        if (addonProcess) {
                                            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').closest('.breadcrumb-line').attr('style', 'height: 70px;');
                                        }

                                        panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess + '<span class="text-one-line w-100 pull-left mt-1" title="'+ $this.find('span:last').text() +'">' + $this.find('span:last').text() + '</span>');
                                    }

                                    Core.unblockUI();
                                });
                            },
                            error: function() { alert('Error'); Core.unblockUI(); }
                        });
                        
                    } else if (processId == '1636438397801188') {
                        
                        if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess);
                        } else {
                            addonProcess = '';
                            if (addonProcess) {
                                panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').closest('.breadcrumb-line').attr('style', 'height: 70px;');
                            }

                            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess + '<span class="text-one-line w-100 pull-left mt-1" title="'+ $this.find('span:last').text() +'">' + $this.find('span:last').text() + '</span>');
                        }
                                    
                        viewProcess_<?php echo $this->uniqId; ?>.empty();
                        var $appendElement = viewProcess_<?php echo $this->uniqId; ?>;
                        
                        erdConfigInit($this, processId, processId, {id: rowId}, $appendElement);
                    }
                    
                } else if (metaType == 'dataview') {
                    
                    var postData = {};
                    
                    if (rowData.hasOwnProperty('listmetadatacriteria') && rowData.listmetadatacriteria) {
                        postData = {drillDownDefaultCriteria: rowData.listmetadatacriteria};
                    }
                    
                    $.ajax({
                        type: 'post',
                        dataType: 'json',
                        url: 'mdobject/dataview/' + processId + '/0/json',
                        data: postData, 
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                                
                            viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                                
                                if (rowData.hasOwnProperty('ishideprocess') && rowData.ishideprocess == '1') {
                                    addonProcess = '';
                                }
                                
                                if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                                    panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess);
                                } else {
                                    panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess + $this.find('span:last').text());
                                }
                                
                                viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .col-md-12:eq(0)').remove();
                                
                                Core.unblockUI();
                            });
                        },
                        error: function() { alert('Error'); Core.unblockUI(); }
                    });
                }
            }

            if (rowData.hasOwnProperty('weblink') && rowData.weblink) {
                var urlLower = (rowData.weblink).toLowerCase();
                var addonProcess = '', isProcessDropDown = false;
                                
                if ($this.hasAttr('data-othercriterias') && $this.attr('data-othercriterias') != '') {

                    var criterias = $this.attr('data-othercriterias').split('@@'), count = 0, dropdownButtons = '', 
                        buttonBarStyle = ($this.hasAttr('data-buttonbarstyle') ? $this.attr('data-buttonbarstyle') : '');

                    for (var c in criterias) {

                        var criteriaArr = criterias[c].split('$$');
                        var viewProcessId = criteriaArr[0];
                        var viewProcessType = criteriaArr[1];
                        var viewProcessCriteria = criteriaArr[2];
                        var thirdProcessId = '';
                        var thirdMetaType = '';

                        if (viewProcessCriteria != '') {

                            viewProcessCriteria = viewProcessCriteria.toLowerCase();

                            $.each(rowData, function(index, crow) {
                                if (viewProcessCriteria.indexOf(index) > -1) {
                                    crow = (crow === null) ? '' : crow;
                                    var regex = new RegExp('\\b' + index + '\\b', 'g');
                                    viewProcessCriteria = viewProcessCriteria.replace(regex, "'" + crow.toString() + "'");
                                }
                            });

                            try {
                                if (eval(viewProcessCriteria)) {
                                    thirdProcessId = viewProcessId;
                                    thirdMetaType = viewProcessType;
                                }
                            } catch (err) {}

                        } else {
                            thirdProcessId = viewProcessId;
                            thirdMetaType = viewProcessType;
                        }

                        if (thirdProcessId != '') {

                            var buttonName = criteriaArr[3];
                            var processName = criteriaArr[4];
                            var iconName = criteriaArr[5];
                            var buttonStyle = criteriaArr[6] ? criteriaArr[6] : 'btn-secondary';
                            var isConfirm = criteriaArr.hasOwnProperty(7) ? criteriaArr[7] : 0;
                            
                            addonProcess += '<button type="button" class="btn btn-sm '+buttonStyle+'" data-thirdprocessid="'+thirdProcessId+'" data-thirdprocesstype="'+thirdMetaType+'" data-isconfirm="'+isConfirm+'" title="'+(processName ? plang.get(processName) : '')+'">'+(iconName ? '<i class="fa '+iconName+'"></i> ' : '')+(buttonName ? plang.get(buttonName) : '')+'</button> ';
                            dropdownButtons += '<a href="javascript:;" data-thirdprocessid="'+thirdProcessId+'" data-thirdprocesstype="'+thirdMetaType+'" data-isconfirm="'+isConfirm+'" title="'+(processName ? plang.get(processName) : '')+'" class="dropdown-item">'+(iconName ? '<i class="fa '+iconName+'"></i> ' : '')+(buttonName ? plang.get(buttonName) : (processName ? plang.get(processName) : ''))+'</a>';
                            
                            count++;
                        }
                    }
                    
                    if (count > 1 && buttonBarStyle == 'dropdown') {
                        
                        isProcessDropDown = true;
                        
                        addonProcess = '<div style="float: left;width: 45px;">';
                            addonProcess += '<div class="btn-group mr10">';
                                addonProcess += '<button type="button" class="btn btn-sm btn-light dropdown-toggle" data-toggle="dropdown" aria-expanded="false"></button>';
                                addonProcess += '<div class="dropdown-menu">';
                                    addonProcess += dropdownButtons;
                                addonProcess += '</div>';
                            addonProcess += '</div>';
                        addonProcess += '</div>';
                    }
                }
                
                switch (urlLower) {
                    case 'campreviewwbl':
                        if (typeof isGovAddonScript === 'undefined') {
                            $.getScript(URL_APP + 'assets/custom/gov/script.js').done(function() {
                                view1Exam($this, dmMetaDataId, rowData, viewProcess_<?php echo $this->uniqId; ?>, panelDv_<?php echo $this->uniqId; ?>, dvTwoWindowHeight_<?php echo $this->uniqId; ?>, addonProcess);
                            });
                        } else {
                            view1Exam($this, dmMetaDataId, rowData, viewProcess_<?php echo $this->uniqId; ?>, panelDv_<?php echo $this->uniqId; ?>, dvTwoWindowHeight_<?php echo $this->uniqId; ?>, addonProcess);
                        }
                        break;
                    case 'step2exam':
                        if (typeof isGovAddonScript === 'undefined') {
                            $.getScript(URL_APP + 'assets/custom/gov/script.js').done(function() {
                                step2Exam($this, dmMetaDataId, rowData, viewProcess_<?php echo $this->uniqId; ?>, panelDv_<?php echo $this->uniqId; ?>, dvTwoWindowHeight_<?php echo $this->uniqId; ?>, addonProcess);
                            });
                        } else {
                            step2Exam($this, dmMetaDataId, rowData, viewProcess_<?php echo $this->uniqId; ?>, panelDv_<?php echo $this->uniqId; ?>, dvTwoWindowHeight_<?php echo $this->uniqId; ?>, addonProcess);
                        }
                        break;
                
                    default:
                        break;
                }
            }
            
            if ($('.removeRenderProcessRightBtn').length && $(".removeRenderProcessRightBtn").is(":visible")) {
                $('.removeRenderProcessRightBtn').trigger('click');
            }
        });

        panelDv_<?php echo $this->uniqId; ?>.on('click', 'button[data-deleteactionbtn]', function(e) {
            var $this = $(this);
            var $parent = $this.closest('span');
            var id = $parent.data('second-id');

            if (id) {
                
                var deleteProcessId = secondList_<?php echo $this->uniqId; ?>.data('deleteprocessid');
                
                if ($this.hasAttr('data-processcount')) {
                    var processCount = Number($this.attr('data-processcount'));
                    var criterias = $this.attr('data-criterias').split('@@');
                    
                    if (processCount > 0) {
                        
                        var rowData = $this.closest('[data-rowdata]').data('rowdata');
                        var isAccessProcess = false;
                        var processNoAccessMsg = 'Нөхцөл тохирохгүй байна!';

                        for (var c in criterias) {

                            var criteriaArr = criterias[c].split('$$');
                            var processId = criteriaArr[0];
                            var processCriteria = criteriaArr[1];

                            if (processCriteria != '') {
                                
                                if (processCriteria.indexOf('#') !== -1) {
                                    var processCriteriaArr = processCriteria.split('#');
                                    processCriteria = (processCriteriaArr[0]).toLowerCase();
                                    var noAccessMsg = (processCriteriaArr[1]).trim();
                                    if (noAccessMsg) {
                                        processNoAccessMsg = noAccessMsg;
                                    }
                                } else {
                                    processCriteria = processCriteria.toLowerCase();
                                }

                                $.each(rowData, function(index, crow) {
                                    if (processCriteria.indexOf(index) > -1) {
                                        crow = (crow === null) ? '' : crow;
                                        var regex = new RegExp('\\b' + index + '\\b', 'g');
                                        processCriteria = processCriteria.replace(regex, "'" + crow.toString() + "'");
                                    }
                                });

                                try {
                                    if (eval(processCriteria)) {
                                        deleteProcessId = processId;
                                        isAccessProcess = true;
                                    }
                                } catch (err) {}

                            } else {
                                deleteProcessId = processId;
                                isAccessProcess = true;
                            }
                        }

                        if (!isAccessProcess) {
                            PNotify.removeAll();
                            new PNotify({
                                title: 'Warning',
                                text: processNoAccessMsg,
                                type: 'Warning',
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                            e.preventDefault();
                            e.stopPropagation();
                            return;
                        }
                    } else {
                        var criteriaArr = criterias[0].split('$$');
                        deleteProcessId = criteriaArr[0];
                    }
                }
                
                runIsOneBusinessProcess(secondList_<?php echo $this->uniqId; ?>.data('listmetadataid'), deleteProcessId, true, {id: id}, function() {
                    $this.closest('li').remove();
                });

                e.preventDefault();
                e.stopPropagation();
            }
        });

        panelDv_<?php echo $this->uniqId; ?>.on('click', '.dv-paneltype-filter-btn', function() {
            
            <?php
            if (issetParam($this->row['IS_FIRST_COL_FILTER']) == '1') {
            ?>  
            $.ajax({
                type: 'post',
                url: 'mdobject/panelMainColumn/getResult',
                data: {
                    dvId: '<?php echo $this->metaDataId; ?>', 
                    formFilter: 1, 
                    params: panelDv_<?php echo $this->uniqId; ?>.find('.dv-paneltype-filter-form').serialize()
                },
                success: function(data) {
                    $('#dv-panel-tab1-<?php echo $this->uniqId; ?> .not-datagrid').empty().append(data).promise().done(function(){
                        $('a[href="#dv-panel-tab1-<?php echo $this->uniqId; ?>"]').tab('show');
                        firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
                    });
                }
            });
            <?php
            } else {
            ?>
            var $selectedRow = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
            buildSecondListByCheck('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', $selectedRow.data('listmetadataid'), $selectedRow.data('id'), $selectedRow, true);
            <?php
            }
            ?>
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('click', '.first-sidebar-search', function() {
            
            <?php
            if ($this->isTree) {
            ?>
            $('#dv-filter-withtreeview-<?php echo $this->uniqId; ?>').toggle('slide');
            <?php
            } else {
            ?>
            
            if ($('#first-sidebar-search-box-<?php echo $this->uniqId; ?>').css('display') == 'none') {
                $('#first-sidebar-search-box-<?php echo $this->uniqId; ?>').show();
                $('.ea-first-sidebar-tabs-<?php echo $this->uniqId; ?>').hide();
                $('#first-sidebar-search-box-<?php echo $this->uniqId; ?>').find('input').focus().select();
            } else {
                $('#first-sidebar-search-box-<?php echo $this->uniqId; ?>').hide();
                $('.ea-first-sidebar-tabs-<?php echo $this->uniqId; ?>').show();
            }
            <?php
            }
            ?>
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('keydown', '.first-sidebar-search-input', function(e) {
            
            if (e.which == 13) {
                var $this = $(this), filterVal = $this.val();
                $.ajax({
                    type: 'post',
                    url: 'mdobject/panelMainColumn/getResult',
                    data: {dvId: '<?php echo $this->metaDataId; ?>', criteria: 'filterName='+filterVal, topFilter: 1},
                    success: function(data) {
                        $('#dv-panel-tab1-<?php echo $this->uniqId; ?> .not-datagrid').empty().append(data).promise().done(function() {
                            firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
                        });
                    }
                });
            }
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('click', '[data-thirdprocessid]', function() {
            var $this = $(this);
            var metaType = $this.data('thirdprocesstype');
            var processId = $this.data('thirdprocessid');
            var isConfirm = $this.data('isconfirm');
            var dmMetaDataId = secondList_<?php echo $this->uniqId; ?>.data('listmetadataid');
            var $selectedRow = secondList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
            var rowData = $selectedRow.data('rowdata');
            
            if (metaType == 'delete') {
                
                runIsOneBusinessProcess(dmMetaDataId, processId, true, rowData, function() {
                
                    panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').empty();
                    $selectedRow.closest('li').remove();
                    viewProcess_<?php echo $this->uniqId; ?>.empty();
                });
                
            } else if (isConfirm == '1') { 
                
                runIsOneBusinessProcess(dmMetaDataId, processId, true, rowData, function() {
                    panelDv_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected[data-secondprocessid]').click();
                });
                
            } else if (metaType == 'process' || metaType == 'consolidate') {
                
                var bpData = {
                    metaDataId: processId,
                    dmMetaDataId: dmMetaDataId,
                    isDialog: false,
                    isHeaderName: false,
                    isBackBtnIgnore: 1, 
                    oneSelectedRow: rowData, 
                    callerType: 'dv', 
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelDvRefreshSecondList(<?php echo $this->uniqId; ?>, \'1\')"}'
                };
                
                if (metaType == 'consolidate') {
                    
                    var selectedList = $('div[data-treeid="panelTreeView_<?php echo $this->uniqId; ?>"]').jstree('get_selected', true), 
                        rowDatas = [];
                    
                    for (var i in selectedList) {
                        
                        var $html = $(selectedList[i]['text']);
                        rowDatas.push($html.data('rowdata'));
                    }
                    
                    bpData.isGetConsolidate = true;
                    bpData.oneSelectedRow = rowDatas;
                }
                
                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/callMethodByMeta',
                    data: bpData,
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        
                        var $addonwindowType = viewProcess_<?php echo $this->uniqId; ?>.find('.addonwindowType');
                        
                        if ($addonwindowType.length > 0) {
                            $addonwindowType.empty().append(data.Html).promise().done(function() {
                            
                                viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar:eq(0)').addClass('float-right');
                                viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                                viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .row:eq(0)').css({
                                    'overflow': 'auto', 
                                    'max-height': dvTwoWindowHeight_<?php echo $this->uniqId; ?> - viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar').offset().top - 80, 
                                    'margin-left': '-15px', 
                                    'margin-right': '-15px'
                                });

                                Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
                                
                                var $reportViewer = viewProcess_<?php echo $this->uniqId; ?>.find('.report-preview-container');
                                
                                if ($reportViewer.length) {
                                    $reportViewer.css({'height': '250px', 'min-height': '250px'});
                                }

                                Core.unblockUI();
                            });
                        } else {
                            viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function() {
                            
                                viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar:eq(0)').addClass('float-right');
                                viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                                viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .row:eq(0)').css({
                                    'overflow': 'auto', 
                                    'max-height': dvTwoWindowHeight_<?php echo $this->uniqId; ?> - viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar').offset().top - 80, 
                                    'margin-left': '-15px', 
                                    'margin-right': '-15px'
                                });

                                Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
                                Core.unblockUI();
                            });
                        }
                        
                    },
                    error: function() { alert('Error'); Core.unblockUI(); }
                });
                
            } else if (metaType == 'bookmark') {
                
                if (processId == '1575702644228891') {
                    $.ajax({
                        type: 'post',
                        url: 'mdpreview/filePreview',
                        data: {selectedRow: rowData},
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {

                            viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.html).promise().done(function () {
                                Core.unblockUI();
                            });
                        },
                        error: function() { alert('Error'); Core.unblockUI(); }
                    });
                    
                } else {
                    var dvCode = $selectedRow.attr('data-dvcode'); 
                    window['objectdatagrid_' + dmMetaDataId] = $('#objectdatagrid-'+dmMetaDataId);
                    transferProcessAction('', dmMetaDataId, processId, '200101010000010', 'processCriteria', this, {callerType: dvCode}, undefined, undefined, undefined, undefined, '');
                }
            } 
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-subqueryid]', function() {
            
            var $this = $(this);
            var subQueryId = $this.attr('data-subqueryid');
            var $parent = $this.closest('.btn-group').find('button[data-toggle="dropdown"]');    
            
            $parent.html($this.text());
            
            subQueryId_<?php echo $this->uniqId; ?> = subQueryId;
            
            $.ajax({
                type: 'post',
                url: 'mdobject/panelMainColumn/getResult',
                data: {dvId: '<?php echo $this->metaDataId; ?>', subQueryId: subQueryId},
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {

                    $('#dv-panel-tab1-<?php echo $this->uniqId; ?> .not-datagrid').empty().append(data).promise().done(function () {
                        firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
                        firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvTwoFirstListHeight_<?php echo $this->uniqId; ?>});
                        Core.unblockUI();
                    });
                },
                error: function() { alert('Error'); Core.unblockUI(); }
            });
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('click', '.second-sidebar-search', function() {
            var $this = $(this);
            
            if ($this.hasAttr('data-popup-search') && $this.attr('data-popup-search') == '1') {
                
                var $dialogName = 'dvpanel-popup-form-<?php echo $this->uniqId; ?>';
                var $dialog = $('#' + $dialogName);   
                
                if (!$dialog.length) {
                    
                    var listMetaDataId = secondList_<?php echo $this->uniqId; ?>.attr('data-listmetadataid');
                    var firstRowData = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').data('rowdata');
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdstatement/popupSearch',
                        data: {metaDataId: listMetaDataId},
                        success: function(data) {
                            
                            $('<div id="' + $dialogName + '" class="paneldv-filter-after-close-<?php echo $this->uniqId; ?>"></div>').appendTo('body');
                            var $dialog = $('#' + $dialogName);
                            
                            $dialog.empty().append(data);
                            $dialog.dialog({
                                cache: false,
                                resizable: false,
                                bgiframe: true,
                                autoOpen: false,
                                title: plang.get('filter'),
                                width: 500, 
                                height: 'auto',
                                modal: true,
                                open: function() {
                                    var $disabledElements = $dialog.find('[readonly], [disabled]');
                                    if ($disabledElements.length) {
                                        $disabledElements.removeAttr('readonly disabled');
                                    }
                                    
                                    if (firstRowData.hasOwnProperty('listmetadatacriteria') && firstRowData.listmetadatacriteria) {
                                        var $lookupFields = $dialog.find('input.popupInit, select.select2');
                                        if ($lookupFields.length) {
                                            var listMetaDataCriteria = firstRowData.listmetadatacriteria;
                                            $lookupFields.each(function() {
                                                var $lookupField = $(this);
                                                $lookupField.attr('data-criteria', listMetaDataCriteria);
                                            });
                                        }
                                    }
                                }, 
                                buttons: [
                                    {text: plang.get('do_filter'), class: 'btn green-meadow btn-sm', click: function () {

                                        PNotify.removeAll();

                                        var $validForm = $dialog.find('form');
                                        $validForm.validate({errorPlacement: function () {}});

                                        if ($validForm.valid()) { 

                                            $dialog.dialog('close');
                                            
                                            twoListPopupSearch_<?php echo $this->uniqId; ?> = '&isIgnoreParentNull=1&' + $validForm.serialize();
                                            
                                            panelDrawTree(
                                                '<?php echo $this->uniqId; ?>', 
                                                null, 
                                                secondList_<?php echo $this->uniqId; ?>.attr('data-listmetadataid'), 
                                                secondListName_<?php echo $this->uniqId; ?>.text(), 
                                                secondList_<?php echo $this->uniqId; ?>.attr('data-loadrowid')
                                            );
                                        }
                                    }},
                                    {text: plang.get('clear_btn'), class: 'btn purple-plum bp-btn-saveprint btn-sm', click: function () {
                                    
                                        $dialog.dialog('close');
                                        
                                        $dialog.find('input[type="text"]:not([data-field-name="filterScenarioId"],[data-path="filterScenarioId"]), textarea').val('');
                                        $dialog.find('select.select2').select2('val', '');
                                        
                                        dvTwoSecondListHdrActions_<?php echo $this->uniqId; ?> = false;
                                        
                                        panelDrawTree(
                                            '<?php echo $this->uniqId; ?>', 
                                            null, 
                                            secondList_<?php echo $this->uniqId; ?>.attr('data-listmetadataid'), 
                                            secondListName_<?php echo $this->uniqId; ?>.text(), 
                                            secondList_<?php echo $this->uniqId; ?>.attr('data-loadrowid')
                                        );
                                    }}, 
                                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                                        $dialog.dialog('close');
                                    }}
                                ]
                            });
                            Core.initAjax($dialog);

                            $dialog.dialog('open');
                            dvFilterDateCheckInterval($dialog);
                        }
                    }); 
                    
                } else {
                    $dialog.dialog('open');
                }
                
            } else {
                panelDv_<?php echo $this->uniqId; ?>.find('.second-sidebar-search-box, .second-sidebar-title').toggle();
            }
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('search', '.second-sidebar-search-input', function() {
                
            var $this = $(this), filterVal = trim($this.val());

            if (filterVal != '') {
                secondFilter_<?php echo $this->uniqId; ?> = '&filterVal='+filterVal;
            } else {
                secondFilter_<?php echo $this->uniqId; ?> = '';
            }

            panelDrawTree(
                '<?php echo $this->uniqId; ?>', 
                null, 
                secondList_<?php echo $this->uniqId; ?>.attr('data-listmetadataid'), 
                secondListName_<?php echo $this->uniqId; ?>.text(), 
                secondList_<?php echo $this->uniqId; ?>.attr('data-loadrowid')
            );
        });
        
        panelDv_<?php echo $this->uniqId; ?>.on('click', '.panel-dv-collapse-btn', function() {
            var $this = $(this), $parent = $this.closest('.pf-paneltype-dataview'), 
                $firstCol = $parent.find('.sidebar-main'), 
                $secondCol = $parent.find('.sidebar-secondary'), 
                $gutterCol = $parent.find('.gutter');
            
            if (!$this.hasAttr('data-collapse')) {
                
                $parent.addClass('panel-dv-collapse-parent');
                $firstCol.addClass('d-none');
                $secondCol.addClass('panel-dv-collapse-view');   
                $gutterCol.addClass('d-none');
                $this.find('i').removeClass('fa-arrow-alt-to-left').addClass('fa-arrow-alt-to-right');
                
                $this.attr('data-collapse', '1');
                
            } else {
                
                $parent.removeClass('panel-dv-collapse-parent');
                $firstCol.removeClass('d-none');
                $secondCol.removeClass('panel-dv-collapse-view');  
                $gutterCol.removeClass('d-none');
                $this.find('i').removeClass('fa-arrow-alt-to-right').addClass('fa-arrow-alt-to-left');
                
                $this.removeAttr('data-collapse');
            }
            
            $(window).resize();
        });
        
        <?php
        if (isset($this->dataViewProcessCommand['commandContext'])) {
        ?>
        $.contextMenu({
            selector: 'div[data-uniqid="<?php echo $this->uniqId; ?>"] ul[data-part="dv-twocol-first-list"] a.nav-link',
            events: {
                show: function(opt) {
                    var $this = opt.$trigger;
                    var $parent = $this.closest('ul[data-part="dv-twocol-first-list"]');
                    
                    $parent.find('.paneldv-selected-row').removeClass('paneldv-selected-row');
                    $this.addClass('paneldv-selected-row');
                }
            },
            build: function($trigger, e) {
                
                var rows = $trigger.data('rowdata');
                var contextMenuData = {};
                
                contextMenuData = {
                    <?php 
                    $commandContextArray = Arr::sortBy('ORDER_NUM', $this->dataViewProcessCommand['commandContext'], 'asc');
                    
                    foreach ($commandContextArray as $cm => $row) {
                        
                        $contextMenuIcon = str_replace('fa-', '', $row['ICON_NAME']);
                        
                        if (isset($row['STANDART_ACTION'])) {
                            
                            if ($row['STANDART_ACTION'] == 'criteria') {
                                
                                echo '"' . $cm . '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';
                                        
                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }
                                
                                echo 'callback: function(key, options) {'
                                . 'transferProcessCriteria(\'' . $this->metaDataId . '\', \'' . $row['BATCH_NUMBER'] . '\', \'context\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'});'
                                . '}'
                                . '},';
                                
                            } elseif ($row['STANDART_ACTION'] == 'processCriteria') {
                                
                                echo '"' . $cm . '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';
                                        
                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }
                                
                                echo 'callback: function(key, options) {';
                                
                                if ($row['ADVANCED_CRITERIA'] != '') {
                                    echo '_dvAdvancedCriteria = "'.$row['ADVANCED_CRITERIA'].'";';
                                }
                                
                                echo 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                                
                            } else {
                                
                                echo '"' . $cm. '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';
                                        
                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }
                                
                                echo 'callback: function(key, options) {'
                                . 'transferProcessAction(\'\', \'' . $this->metaDataId . '\', \'' . $row['STANDART_ACTION'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                            }
                            
                        } else {
                            
                            echo '"' . $cm. '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", ';
                                        
                                if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                    echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                                }
                                
                                echo 'callback: function(key, options) {'
                                . 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                        }
                    }
                    ?>
                };
                
                $.each(contextMenuData, function ($indexCn, $contextR) {
                    if (typeof $contextR['_dvSimpleCriteria'] !== 'undefined' && $contextR['_dvSimpleCriteria']) {
                        var evalcriteria = $contextR['_dvSimpleCriteria'].toLowerCase();
                        
                        if (evalcriteria.indexOf('#') > -1) {
                            var criteriaSplit = evalcriteria.split('#');
                            evalcriteria = trim(criteriaSplit[0]);
                        }
                        
                        $.each(rows, function(index, row) {
                            if (evalcriteria.indexOf(index) > -1) {
                                row = (row === null) ? '' : row.toLowerCase();
                                var regex = new RegExp('\\b' + index + '\\b', 'g');
                                evalcriteria = evalcriteria.replace(regex, "'" + row.toString() + "'");
                            }
                        });

                        try {
                            if (!eval(evalcriteria)) {
                                ticket = false;
                                delete contextMenuData[$indexCn];
                            }
                        } catch (err) {
                            console.log(evalcriteria);
                        }
                    }
                });
                
                var options = {
                    callback: function (key, opt) {
                        eval(key);
                    },
                    items: contextMenuData
                };
                
                return options;
            }
        });  
        <?php
        }
        ?>
        
        $.contextMenu({
            selector: 'div[data-uniqid="<?php echo $this->uniqId; ?>"] div[id="dv-twocol-second-list"] span[data-contextmenu]:not([data-contextmenu=""])',
            build: function($trigger, e) {
                
                var rowData = $trigger.data('rowdata');
                var contextMenuProcess = $trigger.attr('data-contextmenu');
                var contextMenuProcessArr = contextMenuProcess.split('@@');
                var contextMenuData = {};
                var dmMetaDataId = secondList_<?php echo $this->uniqId; ?>.data('listmetadataid');
                
                for (var i = 0; i < contextMenuProcessArr.length; i++) { 
                    
                    var rowContextMenu = (contextMenuProcessArr[i]).split('$$');
                    var rowCriteria = (rowContextMenu[2]).trim();
                    var processNoAccessMsg = '';
                    
                    if (rowCriteria) {
                        
                        if (rowCriteria.indexOf('#') !== -1) {
                            
                            var processCriteriaArr = rowCriteria.split('#');
                            var noAccessMsg = (processCriteriaArr[1]).trim();
                            
                            rowCriteria = (processCriteriaArr[0]).toLowerCase();
                            
                            if (noAccessMsg) {
                                processNoAccessMsg = noAccessMsg;
                            }
                            
                        } else {
                            rowCriteria = rowCriteria.toLowerCase();
                        }

                        $.each(rowData, function(index, crow) {
                            if (rowCriteria.indexOf(index) > -1) {
                                crow = (crow === null) ? '' : crow.toLowerCase();
                                var regex = new RegExp('\\b' + index + '\\b', 'g');
                                rowCriteria = rowCriteria.replace(regex, "'" + crow.toString() + "'");
                            }
                        });

                        try {
                            if (!eval(rowCriteria)) {
                                console.log(processNoAccessMsg);
                                continue;
                            }
                        } catch (err) {
                            continue;
                        }
                    }
                    
                    $trigger.attr('data-runprocess-id-' + i, rowContextMenu[0]);
                    $trigger.attr('data-metatype-id-' + i, rowContextMenu[1]);
                    $trigger.attr('data-actiontype-' + i, rowContextMenu[5]);
                    $trigger.attr('data-isconfirm-' + i, rowContextMenu[6]);
                    
                    contextMenuData[i] = {
                        name: plang.get(rowContextMenu[3]),
                        icon: (rowContextMenu[4]).replace('fa-', ''), 
                        callback: function(key, options) {
                            
                            var $rowElem = $(options.$trigger);
                            var $rowParent = $rowElem.closest('li');
                            var $jsTree = $rowParent.closest('.dv-twocol-panel-tree');
                            var rowId = $rowParent.attr('id');

                            $jsTree.find('.jstree-wholerow-clicked, .jstree-clicked').removeClass('jstree-wholerow-clicked jstree-clicked');

                            $rowParent.find('.jstree-wholerow').addClass('jstree-wholerow-clicked');
                            $rowParent.find('.jstree-anchor').addClass('jstree-clicked');

                            $jsTree.jstree(true).deselect_all(true);
                            $jsTree.jstree(true).select_node(rowId);
                            
                            if ($rowElem.hasAttr('data-runprocess-id-' + key)) {
                                
                                var obj = {
                                    "this": $rowElem, 
                                    "rowData": rowData, 
                                    "addonProcess": '', 
                                    "processId": $rowElem.attr('data-runprocess-id-' + key), 
                                    "metaTypeId": $rowElem.attr('data-metatype-id-' + key), 
                                    "actionType": $rowElem.attr('data-actiontype-' + key), 
                                    "isConfirm": $rowElem.attr('data-isconfirm-' + key), 
                                    "dmMetaDataId": dmMetaDataId, 
                                    "isProcessDropDown": 0
                                };

                                twoPanelCallProcess_<?php echo $this->uniqId; ?>(obj);

                                //transferProcessAction('', dmMetaDataId, rowContextMenu[0], rowContextMenu[1], 'processCriteria', options.$trigger, {callerType: ''}, '');
                            }
                        }
                    };
                }
                
                if (Object.keys(contextMenuData).length) {
                
                    var options = {
                        callback: function (key, opt) {
                            eval(key);
                        },
                        items: contextMenuData
                    };

                    return options;
                } else {
                    return false;
                }
            }
        });
        
        if (<?php echo $this->gridOption['FIRSTROWSELECT'] == 'true' ? 1 : 0; ?> == 1 && typeof ignoreFirstRowSelect_<?php echo $this->metaDataId; ?> == 'undefined' && Core.getURLParameter('pdfid') == '') {
            panelDv_<?php echo $this->uniqId; ?>.find('a[data-listmetadataid]:eq(0)').click();
        } else if (typeof ignoreFirstRowSelect_<?php echo $this->metaDataId; ?> != 'undefined' && ignoreFirstRowSelect_<?php echo $this->metaDataId; ?>) {
            panelDv_<?php echo $this->uniqId; ?>.find('a[data-id="'+ignoreFirstRowSelect_<?php echo $this->metaDataId; ?>+'"]:eq(0)').click();
            ignoreFirstRowSelect_<?php echo $this->metaDataId; ?> = null;
        }

    });
    
}, 200);

function twoPanelCallProcess_<?php echo $this->uniqId; ?>(obj) {
    var rowData = obj.rowData, 
        addonProcess = obj.addonProcess, 
        processId = obj.processId, 
        metaTypeId = obj.hasOwnProperty('metaTypeId') ? obj.metaTypeId : '', 
        dmMetaDataId = obj.dmMetaDataId, 
        $this = obj.this;
    
    if (metaTypeId == '200101010000010') {
        
        var dvCode = $this.attr('data-dvcode'); 
        window['objectdatagrid_' + dmMetaDataId] = $('#objectdatagrid-'+dmMetaDataId);
        
        transferProcessAction('', dmMetaDataId, processId, '200101010000010', 'processCriteria', $this, {callerType: dvCode}, undefined, undefined, undefined, undefined, '');
        
        return;
    }
    
    var actionType = obj.hasOwnProperty('actionType') ? obj.actionType : '', 
        isConfirm = obj.hasOwnProperty('isConfirm') ? obj.isConfirm : '';
        
    if (actionType == 'delete') {
        
        var $selectedRow = secondList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
            
        runIsOneBusinessProcess(dmMetaDataId, processId, true, rowData, function() {
                
            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').empty();
            $selectedRow.closest('li').remove();
            viewProcess_<?php echo $this->uniqId; ?>.empty();
        });
        
        return;
        
    } else if (isConfirm == '1' || processId == '1678156885624269') { 
        
        var dvCode = $this.attr('data-dvcode'); 
        window['objectdatagrid_' + dmMetaDataId] = $('#objectdatagrid-'+dmMetaDataId);
        
        transferProcessAction('', dmMetaDataId, processId, metaTypeId, 'processCriteria', $this, {callerType: dvCode}, undefined, undefined, undefined, undefined, '');
        
        return;
    } 
    
    var isWorkflow = false;
    
    if (secondList_<?php echo $this->uniqId; ?>.hasAttr('data-isworkflow') 
        && secondList_<?php echo $this->uniqId; ?>.attr('data-isworkflow') == '1' 
        && rowData.wfmstatusname != '' 
        && rowData.wfmstatusname != null) {
    
        isWorkflow = true;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: {
            metaDataId: obj.processId,
            dmMetaDataId: obj.dmMetaDataId,
            isDialog: false,
            isHeaderName: false,
            isBackBtnIgnore: 1, 
            oneSelectedRow: rowData, 
            callerType: 'dv', 
            openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"panelRowClickDvRefreshSecondList(<?php echo $this->uniqId; ?>, \'1\', true)"}', 
            isIgnoreMainProcess: (isWorkflow ? 1 : 0)
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data){
            viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                
                var $addonLeftElem = panelDv_<?php echo $this->uniqId; ?>.find('[data-addon-left-title="1"]');
                
                if (isWorkflow) {
                    
                    var wfmButton = '<div class="dropdown bp-rowdata-workflow-btn d-inline-block mr-1">'+
                        '<a href="#" data-id="'+rowData.id+'" data-current-status-id="'+rowData.wfmstatusid+'" data-dv-id="'+dmMetaDataId+'" class="badge badge-primary badge-pill dropdown-toggle" data-toggle="dropdown" style="padding-top: 8px;padding-bottom: 8px;padding-left: 12px;font-size: 12px; background-color: '+rowData.wfmstatuscolor+'">'+rowData.wfmstatusname+'</a>'+
                        '<div class="dropdown-menu dropdown-menu-right"></div>'+
                    '</div>';
                    
                    $addonLeftElem.html(wfmButton);
                    
                } else {
                    $addonLeftElem.html('');
                }
                
                if (rowData.hasOwnProperty('ishideprocess') && rowData.ishideprocess == '1') {
                    addonProcess = '';
                }

                if (rowData.hasOwnProperty('ishidetitle') && rowData.ishidetitle == '1') {
                    panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess);
                } else {

                    if (obj.isProcessDropDown) {

                        var rowTitle = $this.find('span:last').text();
                        var processTitle = '<div style="float: left;width: calc(100% - 45px);height: 30px;display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-align: center;-ms-flex-align: center;align-items: center;-webkit-box-pack: center;-ms-flex-pack: center;justify-content: center;" title="'+rowTitle+'"><div style="display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;line-height: 16px;font-size: 11px;">'+rowTitle+'</div></div>';

                        panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess + processTitle);
                    } else {
                        panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').html(addonProcess + $this.find('span:last').text());
                    }
                }

                var $metaToolbar = viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar:eq(0)');
                var $firstRow = viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .row:eq(0)');

                $metaToolbar.addClass('float-right');
                viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();

                if ($firstRow.length) {
                    $firstRow.css({
                        'overflow': 'auto', 
                        'max-height': dvTwoWindowHeight_<?php echo $this->uniqId; ?> - $metaToolbar.offset().top - 80, 
                        'margin-left': '-15px', 
                        'margin-right': '-15px'
                    });
                } else {
                    var $firstTabContent = viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .tabbable-line:eq(0) > .tab-content');
                    if ($firstTabContent.length) {
                        $firstTabContent.css({
                            'overflow-x': 'hidden', 
                            'overflow-y': 'auto', 
                            'max-height': dvTwoWindowHeight_<?php echo $this->uniqId; ?> - $metaToolbar.offset().top - 128
                        });
                        $metaToolbar.css('margin-bottom', '8px');
                    }
                }

                Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
                Core.unblockUI();
            });
        },
        error: function(){ alert('Error'); Core.unblockUI(); }
    });
}

function explorerRefresh_<?php echo $this->metaDataId; ?>(elem, dvSearchParam, uriParams) {
    $.ajax({
        type: 'post',
        url: 'mdobject/panelMainColumn/getResult',
        data: {
            dvId: '<?php echo $this->metaDataId; ?>', 
            subQueryId: subQueryId_<?php echo $this->uniqId; ?>, 
            drillDownDefaultCriteria: '<?php echo $this->drillDownDefaultCriteria; ?>'
        },
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            $('#dv-panel-tab1-<?php echo $this->uniqId; ?> .not-datagrid').empty().append(data).promise().done(function () {
                firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
                firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvTwoFirstListHeight_<?php echo $this->uniqId; ?>});                
                Core.unblockUI();
            });
        },
        error: function() { alert('Error'); Core.unblockUI(); }
    });
}

<?php
if (isset($commandAddMeta)) {
?>
function dvPanelRunMeta_<?php echo $this->uniqId; ?>(elem, processId, metaTypeId) {
    transferProcessAction('', '<?php echo $this->metaDataId; ?>', processId, metaTypeId, 'toolbar', elem, {callerType: '<?php echo $this->metaDataCode; ?>'}, undefined, undefined, undefined, undefined, '');
}
<?php
}
if ($this->isTree) { 
?>
    
function drawTree_<?php echo $this->uniqId; ?>() {

    var dataViewStructureTreeView_<?php echo $this->uniqId; ?> = $('div#dataViewStructureTreeView_<?php echo $this->uniqId; ?>');
    var dataViewId = '<?php echo $this->metaDataId; ?>';
    var metaDataId = panelDv_<?php echo $this->uniqId; ?>.find('#treeCategory').val();
    var $tabContent = panelDv_<?php echo $this->uniqId; ?>.find('.dv-filter-withtreeview .tab-content');
    var $datagrid = $('#dv-panel-tab1-<?php echo $this->uniqId; ?> .not-datagrid');
    
    $tabContent.css('height', dvTwoWindowHeight_<?php echo $this->uniqId; ?> - $datagrid.offset().top - 58);
    
    dataViewStructureTreeView_<?php echo $this->uniqId; ?>.jstree({
        "core": {
            "themes": {
                "responsive": true
            },
            "check_callback": true,
            "data": {
                "url": function (node) {
                    return 'mdobject/getAjaxTree';
                },
                "data": function (node) {
                    return {'parent': node.id, 'dataViewId': dataViewId, 'structureMetaDataId': metaDataId};
                }
            }
        },
        "types": {
            "default": {
                "icon": "icon-folder2 text-orange-300"
            }
        },
        "plugins": ["types", "cookies"]
    }).bind('select_node.jstree', function (e, data) {

        var filterFieldList = JSON.parse('<?php echo json_encode($this->filterFieldList); ?>');
        var filtedField = filterFieldList[metaDataId];
        var criteria = '';
        
        if (data.node.id != 'all') {
            if (data.node.id === 'null') {
                criteria = filtedField + '=isnull';
            } else {
                criteria = filtedField + '=' + data.node.id;
            }
        } 
        
        $('a[href="#dv-panel-tab1-<?php echo $this->uniqId; ?>"]').html(data.node.text);
        $('#dv-filter-withtreeview-<?php echo $this->uniqId; ?>').toggle('slide');
        
        $.ajax({
            type: 'post',
            url: 'mdobject/panelMainColumn/getResult',
            data: {dvId: '<?php echo $this->metaDataId; ?>', criteria: criteria, topFilter: 1},
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                $datagrid.empty().append(data).promise().done(function () {
                    firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
                    Core.unblockUI();
                });
            }
        });
    });
}
drawTree_<?php echo $this->uniqId; ?>();
<?php
}
?>
</script>