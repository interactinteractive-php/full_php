<script type="text/javascript">

var objectdatagrid_<?php echo $this->metaDataId; ?> = $('#objectdatagrid-<?php echo $this->metaDataId; ?>');
var panelDv_<?php echo $this->uniqId; ?> = $('div[data-uniqid="<?php echo $this->uniqId; ?>"]');   
var firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
var viewProcess_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-process');
var idField_<?php echo $this->uniqId; ?> = '<?php echo $this->idField; ?>';
var nameField_<?php echo $this->uniqId; ?> = '<?php echo $this->nameField; ?>';
var subQueryId_<?php echo $this->uniqId; ?> = null;
var subQueryHeight_<?php echo $this->uniqId; ?> = <?php echo (isset($this->row['subQuery']) && $this->row['subQuery']) ? 50 : 0; ?>;
var dvOneFixHeight_<?php echo $this->uniqId; ?> = 0;
var dvOneFirstListHeight_<?php echo $this->uniqId; ?> = 0;
var dvOneFixProcess_<?php echo $this->uniqId ?> = <?php echo json_encode(issetParamArray($this->dataViewProcessCommand['commandContext'])); ?>;
var idValue_<?php echo $this->uniqId; ?> = '';

if (typeof isDataViewPanelOneColumn === 'undefined') {
    $.cachedScript('<?php echo autoVersion('middleware/assets/js/dataview/panelType/oneColumn.js'); ?>');
}

setTimeout(function() {
    
    dvOneFixHeight_<?php echo $this->uniqId; ?> = $(window).height() - firstList_<?php echo $this->uniqId; ?>.offset().top - 40;
    dvOneFirstListHeight_<?php echo $this->uniqId; ?> = dvOneFixHeight_<?php echo $this->uniqId; ?> - subQueryHeight_<?php echo $this->uniqId; ?>;
    
$(function () {
    var dh = 50;
    
    if (panelDv_<?php echo $this->uniqId; ?>.find('.menucolumn').length) {
        dh = 120;
    }
    
    <?php
    if (Input::numeric('isIgnoreSetHeight')) {
    ?>
    panelDv_<?php echo $this->uniqId; ?>.find('.dv-onecol-first-sidebar').css({'overflow': 'auto'});
    firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto'});
    <?php
    } else {
    ?>
    panelDv_<?php echo $this->uniqId; ?>.find('.dv-onecol-first-sidebar').css({'overflow': 'auto', 'height': dvOneFixHeight_<?php echo $this->uniqId; ?> + dh});
    firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvOneFirstListHeight_<?php echo $this->uniqId; ?>});
    <?php
    }
    ?>
    
    panelDv_<?php echo $this->uniqId; ?>.find('a[data-listmetadataid]:not(.click-disabled)').off('click');
    panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-listmetadataid]:not(.click-disabled)', function() {
        
        var $this         = $(this);
        var rowId         = $this.data('id');
        var $parent       = $this.parent();
        var isChild       = $parent.hasClass('nav-item-submenu');
        var isSubItem     = $this.hasClass('v2');
        var $openMenu     = firstList_<?php echo $this->uniqId; ?>.find('.nav-item-open');
        var openMenuCount = $openMenu.length;
        var rowData       = $this.data('rowdata');
        var thisText      = $this.text();
        
        // $this.addClass('click-disabled');
        
        if (typeof rowData !== 'object') { rowData = JSON.parse(rowData); }
        
        firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
        $this.addClass('dv-twocol-f-selected');
        
        if (rowData.hasOwnProperty('criteriaaddtitle') && rowData.criteriaaddtitle == '1') {
            var $dates = panelDv_<?php echo $this->uniqId; ?>.find('.dv-paneltype-filter-form .dateInit');
            if ($dates.length == 2) {
                thisText += ' ' + $dates.eq(0).val() + ' / ' + $dates.eq(1).val();
            }
        }
        
        if (panelDv_<?php echo $this->uniqId; ?>.find('.menu-description').length) {
            panelDv_<?php echo $this->uniqId; ?>.find('.menu-description').text(rowData.description);
        }
        
        panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').text(thisText);
        
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

        if (isChild) {
            
            if ($parent.hasClass('nav-item-open')) {
                
                $parent.removeClass('nav-item-open');
                $parent.find('> .nav-group-sub:eq(0)').hide();
                
                $this.removeClass('click-disabled');
                
                return;
            }

            if ($parent.find('.nav-group-sub').length == 0) {
                
                $.ajax({
                    type: 'post',
                    url: 'mdobject/dvPanelChildDataList',
                    data: {
                        dvId: '<?php echo $this->metaDataId; ?>', 
                        id: rowId, 
                        subQueryId: subQueryId_<?php echo $this->uniqId; ?>
                    }, 
                    dataType: 'json', 
                    success: function(data) {

                        var treeData = data.treeData;

                        if (treeData.length) {

                            var subMenu = '', subMenuClass = '', icon = '', listMetaDataCriteria = '', metaTypeId = '';

                            for (var key in treeData) {

                                subMenuClass = '';
                                icon = '';
                                listMetaDataCriteria = '';
                                metaTypeId = '';

                                if (treeData[key].hasOwnProperty('childrecordcount') && treeData[key]['childrecordcount']) {
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

                                subMenu += '<li class="nav-item'+subMenuClass+'"><a href="javascript:void(0);" data-id="' + treeData[key][idField_<?php echo $this->uniqId; ?>] + '" data-listmetadataid="' + treeData[key]['metadataid'] + '" data-listmetadatacriteria="'+listMetaDataCriteria+'" data-metatypeid="'+metaTypeId+'" data-rowdata="'+htmlentities(JSON.stringify(treeData[key]), 'ENT_QUOTES', 'UTF-8')+'" class="nav-link v2">' + icon + treeData[key][nameField_<?php echo $this->uniqId; ?>];
                                if (treeData[key].hasOwnProperty('iscountmeta') && treeData[key]['iscountmeta']) {
                                    subMenu += '<span class="badge badge-success ml-auto left-menu-count-meta position-relative mr-3" data-id="' + treeData[key][idField_<?php echo $this->uniqId; ?>] + '" data-listmetadatacriteria="'+listMetaDataCriteria+'" data-counmetadataid="' + treeData[key]['metadataid'] + '"><i class="fa fa-spinner fa-spin mr-0" style="font-size: 8px !important"></i></span>';
                                }
                                subMenu += '</a></li>';
                            }

                            $parent.append('<ul class="nav nav-group-sub" style="display: block;">'+subMenu+'</ul>');
                            $parent.addClass('nav-item-open');
                            getLeftMenuCount(false, $parent.find('ul'));
                        }
                        
                        $this.removeClass('click-disabled');
                    }
                });

            } else {

                $parent.addClass('nav-item-open');
                $parent.find('.nav-group-sub:eq(0)').show();
                
                $this.removeClass('click-disabled');
            }   
        }
        
        buildOneColSecondPart('<?php echo $this->uniqId; ?>', '<?php echo $this->metaDataId; ?>', $this);
    });

    panelDv_<?php echo $this->uniqId; ?>.on('click', 'a[data-secondprocessid]', function() {

        var $this = $(this);
        var processId = $this.data('secondprocessid');
        var metaType = $this.data('secondtypecode');
        var dmMetaDataId = secondList_<?php echo $this->uniqId; ?>.data('listmetadataid');
        var rowId = $this.data('second-id');

        if (processId) {

            if (metaType == 'process') {

                $.ajax({
                    type: 'post',
                    url: 'mdwebservice/callMethodByMeta',
                    data: {
                        metaDataId: processId,
                        dmMetaDataId: dmMetaDataId,
                        isDialog: false,
                        isHeaderName: false,
                        isBackBtnIgnore: 1, 
                        oneSelectedRow: {id: rowId}, 
                        callerType: 'dv', 
                        openParams: '{"callerType":"dv","afterSaveNoAction":true}'
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...', 
                            boxed: true
                        });
                    },
                    success: function(data){
                        viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function () {
                            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').text($this.find('.media-title').text());
                            viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton').remove();
                            Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
                            Core.unblockUI();
                        });
                    },
                    error: function(){
                        alert('Error');
                    }
                });

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
                        $("link[href='middleware/assets/theme/" + data.theme + "/css/main.css']").remove();
                        $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.css"/>');

                        if (data.theme == 'theme10') {
                            $.getScript("assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js");
                            $.getScript("assets/custom/addon/plugins/jquery.sparkline.min.js");
                        }

                        viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.html).promise().done(function () {
                            panelDv_<?php echo $this->uniqId; ?>.find('#dv-twocol-view-title').text($this.find('.media-title').text());
                            viewProcess_<?php echo $this->uniqId; ?>.find('.close-btn').remove();
                            Core.initAjax(viewProcess_<?php echo $this->uniqId; ?>);
                        });
                    }
                });
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
                    firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvOneFirstListHeight_<?php echo $this->uniqId; ?>});
                    Core.unblockUI();
                });
            },
            error: function() { alert('Error'); Core.unblockUI(); }
        });
    });
    
    panelDv_<?php echo $this->uniqId; ?>.on('click', '[data-thirdprocessid]', function() {
        var $this = $(this);
        var metaType = $this.data('thirdprocesstype');
        var processId = $this.data('thirdprocessid');
        var isConfirm = $this.data('isconfirm');
        var dmMetaDataId = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected').data('listmetadataid');
        var $selectedRow = firstList_<?php echo $this->uniqId; ?>.find('.dv-twocol-f-selected');
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

        } else if (metaType == 'process' || metaType == '200101010000011') {

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    dmMetaDataId: dmMetaDataId,
                    isDialog: false,
                    isHeaderName: false,
                    isBackBtnIgnore: 1, 
                    oneSelectedRow: rowData, 
                    callerType: 'dv', 
                    openParams: '{"callerType":"dv","afterSaveNoAction":true}'
                },
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    if (viewProcess_<?php echo $this->uniqId; ?>.find('.addonwindowType').length > 0) {
                        
                        viewProcess_<?php echo $this->uniqId; ?>.find('.addonwindowType').empty().append(data.Html).promise().done(function() {

                            viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar:eq(0)').addClass('float-right');
                            viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                            viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .row:eq(0)').css({
                                'overflow': 'auto', 
                                'max-height': $(window).height() - viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar').offset().top - 80, 
                                'margin-left': '-20px', 
                                'margin-right': '-20px'
                            });

                            Core.initBPAjax(viewProcess_<?php echo $this->uniqId; ?>);
                            Core.unblockUI();
                        });
                        
                    } else {
                        
                        viewProcess_<?php echo $this->uniqId; ?>.empty().append(data.Html).promise().done(function() {

                            viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar:eq(0)').addClass('float-right');
                            viewProcess_<?php echo $this->uniqId; ?>.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                            viewProcess_<?php echo $this->uniqId; ?>.find('> .row > .xs-form > form > .row:eq(0)').css({
                                'overflow': 'auto', 
                                'max-height': $(window).height() - viewProcess_<?php echo $this->uniqId; ?>.find('.meta-toolbar').offset().top - 80, 
                                'margin-left': '-20px', 
                                'margin-right': '-20px'
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
            }
        } 
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
    
    panelDv_<?php echo $this->uniqId; ?>.on('click', '.dv-paneltype-filter-btn', function() {
            
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
    });
    
    panelDv_<?php echo $this->uniqId; ?>.on('keydown', '.dv-paneltype-filter-form input[type="text"]', function(e) {
        if (e.which == 13) {
            panelDv_<?php echo $this->uniqId; ?>.find('.dv-paneltype-filter-btn').trigger('click');
            e.preventDefault();
            e.stopPropagation();
            return;
        }
    });
    
    <?php
    if (isset($this->dataViewProcessCommand['commandContext']) && $this->dataViewProcessCommand['commandContext']) {
    ?>
    $.contextMenu({
        selector: 'div[data-uniqid="<?php echo $this->uniqId; ?>"] ul[data-part="dv-twocol-first-list"] a.nav-link',
        events: {
            show: function(opt) {
                var $this = opt.$trigger;
                var $parent = $this.closest('ul[data-part="dv-twocol-first-list"]');

                $parent.find('.paneldv-selected-row').removeClass('paneldv-selected-row');
                $this.addClass('paneldv-selected-row');
                idValue_<?php echo $this->uniqId; ?> = $this.attr('data-id');
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

            var options =  {
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
    
    <?php 
    if ($this->gridOption['FIRSTROWSELECT'] == 'true') {
    ?>
    panelDv_<?php echo $this->uniqId; ?>.find('a[data-listmetadataid]:eq(0)').click(); 
    <?php
    }
    ?>
                
});
}, 200);

function explorerRefresh_<?php echo $this->metaDataId; ?>(elem, dvSearchParam, uriParams) {
    $.ajax({
        type: 'post',
        url: 'mdobject/panelMainColumn/getResult',
        data: {
            dvId: '<?php echo $this->metaDataId; ?>', 
            subQueryId: subQueryId_<?php echo $this->uniqId; ?>, 
            drillDownDefaultCriteria: '<?php echo Input::post('drillDownDefaultCriteria'); ?>'
        },
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {

            $('#dv-panel-tab1-<?php echo $this->uniqId; ?> .not-datagrid').empty().append(data).promise().done(function () {
                firstList_<?php echo $this->uniqId; ?> = panelDv_<?php echo $this->uniqId; ?>.find('ul[data-part="dv-twocol-first-list"]');
                firstList_<?php echo $this->uniqId; ?>.css({'display': 'block', 'overflow': 'auto', 'max-height': dvOneFirstListHeight_<?php echo $this->uniqId; ?>});
                if (idValue_<?php echo $this->uniqId; ?>) {
                    firstList_<?php echo $this->uniqId; ?>.find('a[data-id="'+idValue_<?php echo $this->uniqId; ?>+'"]').trigger('click');
                }
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
?>
</script>