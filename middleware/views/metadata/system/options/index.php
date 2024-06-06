<div class="bp-settings shift-p-ignore">
    <div class="d-flex">
        <div class="sidebar sidebar-light sidebar-main sidebar-secondary sidebar-expand-md dv-twocol-first-sidebar">
            <div class="header-top">
                <div class="card mb-0 border-0 border-radius-0 bg-blue">
                    <div class="card-body d-flex border-radius-0">
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <img src="assets/custom/img/user.png" class="rounded-circle" width="34" height="34">
                            </div>
                            <div class="d-flex flex-column">
                                <a href="javascript:;" class="media-title font-weight-bold mb-0 line-height-normal text-white"><?php echo $this->metaRow['USERNAME']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar-content">
                <div class="card card-sidebar-mobile">
                    <div class="card-body p-0">
                        <div class="tab-content">
                            <div class="tab-pane fade active show overflow-auto" id="bp-settings-tab1">
                                <?php echo $this->leftSideBar; ?>
                            </div>
                            <div class="tab-pane fade" id="bp-settings-tab2">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <!-- CONTENT HERE -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bp-main">
            <form method="post" enctype="multipart/form-data" id="meta-form-v2" data-metadataid="<?php echo $this->metaDataId; ?>">
                <div class="page-header page-header-light col p-0">
                    <div class="page-header-content header-elements-md-inline namecodebox">
                        <div class="col">
                            <div class="form-group d-flex flex-row">
                                <button type="button" class="btn btn-outline bg-primary text-primary-800 btn-icon pt4 pb4 px-2" data-clipboard-target="#metaDataCode" data-clipboard title="<?php echo $this->lang->line('setting_copy_code'); ?>">
                                    <i class="far fa-copy"></i>
                                </button>
                                <?php
                                echo Form::text(
                                    array(
                                        'name'        => 'metaDataCode', 
                                        'id'          => 'metaDataCode', 
                                        'class'       => 'form-control code',
                                        'required'    => 'required',
                                        'placeholder' => $this->lang->line('META_00023'),
                                        'value'       => $this->metaRow['META_DATA_CODE']
                                    )
                                );
                                ?>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group d-flex flex-row">
                                <button type="button" class="btn btn-outline bg-primary text-primary-800 btn-icon pt4 pb4 px-2" data-clipboard-target="#META_DATA_NAME" data-clipboard title="<?php echo $this->lang->line('setting_copy_name'); ?>">
                                    <i class="far fa-copy"></i>
                                </button>
                                <?php
                                echo Form::text(
                                    array(
                                        'name'        => 'META_DATA_NAME', 
                                        'id'          => 'META_DATA_NAME', 
                                        'class'       => 'form-control name',
                                        'required'    => 'required',
                                        'placeholder' => $this->lang->line('META_00114'),
                                        'value'       => $this->metaRow['META_DATA_NAME']
                                    )
                                );
                                echo Form::hidden(array('name' => 'META_TYPE_ID', 'value' => $this->metaRow['META_TYPE_ID']));
                                echo Form::hidden(array('name' => 'IS_ACTIVE', 'value' => $this->metaRow['IS_ACTIVE']));
                                echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId));
                                ?>
                            </div>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline bg-primary text-primary-800 btn-icon pt4 pb4 px-2" data-clipboard-target="#metaV2CopyMetaDataId" data-clipboard title="<?php echo $this->lang->line('setting_copy_id'); ?>">
                                <i class="far fa-copy"></i>
                            </button>
                            <span class="metid mr-1" id="metaV2CopyMetaDataId"><?php echo $this->metaDataId; ?></span>
                        </div>
                        <div class="d-flex justify-content-end">
                            <?php 
                            echo Form::button(
                                array(
                                    'class' => 'btn bg-pink-400 pt4 pb4 px-2 mr-1 sidebar-secondary-toggle', 
                                    'value' => '<i class="fa fa-align-right"></i>', 
                                    'title' => $this->lang->line('setting_collapse'), 
                                    'onclick' => ''
                                )
                            );
                            echo Form::button(
                                array(
                                    'class' => 'btn bg-blue pt4 pb4 px-2 mr-1', 
                                    'value' => '<i class="fa fa-copy"></i>', 
                                    'title' => $this->lang->line('setting_copy'), 
                                    'onclick' => 'metaCopy(\''.$this->metaDataId.'\');'
                                )
                            );
                            
                            if ($this->metaTypeCode == 'process') {
                                
                                echo Form::button(
                                    array(
                                        'class' => 'btn bg-red pt4 pb4 px-2 mr-1', 
                                        'value' => '<i class="fa fa-history"></i>', 
                                        'title' => $this->lang->line('setting_clear_cache'), 
                                        'onclick' => 'bpExpressionCacheClear(\''.$this->metaDataId.'\');'
                                    )
                                );
                                
                            } elseif ($this->metaTypeCode == 'metagroup') {
                                
                                echo Form::button(
                                    array(
                                        'class' => 'btn bg-red pt4 pb4 px-2 mr-1', 
                                        'value' => '<i class="fa fa-history"></i>', 
                                        'title' => $this->lang->line('setting_clear_cache'), 
                                        'onclick' => 'dvCacheClear(\''.$this->metaDataId.'\');'
                                    )
                                );
                            }
                            
                            echo Form::button(
                                array(
                                    'class' => 'btn bg-indigo-400 pt4 pb4 px-2 mr-1', 
                                    'value' => '<i class="icon-download4"></i>', 
                                    'title' => $this->lang->line('setting_download'), 
                                    'onclick' => "metaPHPExportById('".$this->metaDataId."');"
                                )
                            );
                            
                            if ($this->metaTypeCode == 'process') {
                                echo Form::button(
                                    array(
                                        'class' => 'btn bg-success pt4 pb4 shift-s-save', 
                                        'value' => $this->lang->line('setting_run') . '<i class="fa fa-play-circle ml-1"></i>',
                                        'onclick' => "metaV2ProcessPreview('".$this->metaDataId."');", 
                                        'title' => 'Shift+s'
                                    )
                                );
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="bp-main-header pf-metav2-main-title">
                    <span></span>
                </div>
                <div class="bp-content pf-metav2-content overflow-auto">
                    <div data-opt-code="main" class="main">
                        <?php echo $this->defaultView; ?> 
                    </div>    
                </div>
            </form>
            <div class="bp-content pf-metav2-dv overflow-auto" style="display: none"></div>
            <div class="bp-content pf-metav2-compositeprocess overflow-auto" style="display: none"></div>
            
            <div class="pf-metav2-twocol" style="display: none">
                <div class="d-flex flex-row">
                    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md" id="split-second-sidebar">
                        <div class="sidebar-content">
                            <div class="card">
                                <div class="card-header bg-white header-elements-inline d-flex justify-content-between px-2">
                                    <span class="font-weight-bold text-two-line line-height-normal"><?php echo $this->lang->line('setting_business_process'); ?></span>
                                </div>
                                <div class="pf-metav2-twocol-scroll overflow-auto">
                                    <ul class="media-list media-list-linked">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100" id="split-content">
                        <div class="bp-main-header">
                            <span></span>
                        </div>
                        <div class="bp-content pf-metav2-twocol-content overflow-auto"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
var splitobj = Split(["#split-second-sidebar","#split-content"], {
    elementStyle: function (dimension, size, gutterSize) { 
        $(window).trigger('resize');
        return {'flex-basis': 'calc(' + size + '% + 20px)'};
    },
    gutterStyle: function (dimension, gutterSize) { return {'flex-basis':  gutterSize + 'px'} },
    sizes: [20, 78],
    minSize: 50,
    gutterSize: 6,
    cursor: 'col-resize'
});

var metaV2Sidebar = <?php echo json_encode($this->bpRow['sidebar']); ?>;

function metaV2RenderSidebar(depth = 0, parentId = null) {
    
    var html = ''; 
    
    for (var k in metaV2Sidebar) {

        if ((depth == 0 && !metaV2Sidebar[k]['parentid']) || (depth > 0 && metaV2Sidebar[k]['parentid'] == parentId)) {

            var sRow = metaV2Sidebar[k], liClass = '', subHtml = '', iconName = '';
            delete metaV2Sidebar[k];
            
            var subMetaV2Sidebar = metaV2RenderSidebar(depth + 1, sRow['id']);

            if (!sRow['metadataid'] && !sRow['parentid']) {
                liClass = ' nav-item-submenu';
            }
            
            if (subMetaV2Sidebar) {
                subHtml = '<ul class="nav nav-group-sub"'+((depth == 0 && k == 0) ? ' style="display: block"' : '')+'>' + subMetaV2Sidebar + '</ul>';
            } else {
                liClass = '';
            }
            
            if (depth == 0) {
                
                if (!sRow['icon']) {
                    iconName = '<i class="icon-pencil5"></i>';    
                } else {
                    iconName = sRow['icon'];    
                }
                
                if (k == 0) {
                    liClass += ' nav-item-open';
                }
            }

            html += '<li class="nav-item' + liClass + '">'+
                '<a href="javascript:;" class="nav-link"'+
                    ' data-metadataid="' + dvFieldValueShow(sRow['metadataid']) + '"'+ 
                    ' data-metatypeid="' + dvFieldValueShow(sRow['metatypeid']) + '"'+
                    ' data-opt-code="' + dvFieldValueShow(sRow['dataoptcode']) + '"'+
                    ' data-dv-id="' + dvFieldValueShow(sRow['datadvid']) + '"'+
                    ' data-dv-path="' + dvFieldValueShow(sRow['datadvpath']) + '"'+ 
                    '>'+
                    iconName + '<span>' + sRow['name'] + '</span>' +
                '</a>' + subHtml + 
            '</li>';
        }
    }
    
    return html;
}

var metaV2RenderSidebar = metaV2RenderSidebar(0, null);
$('#metav2-sidebar').append(metaV2RenderSidebar);

$(function() {

    App.initNavigations($('#bp-settings-tab1'));
    new ClipboardJS('[data-clipboard]');   

    $('#window-size').change(function() {
        $('.wsize').toggle();
        $('#' + $(this).val()).show();
    });
    
    var metaV2WindowHeight = $(window).height();
    
    $('.bp-content').css('height', metaV2WindowHeight - 171);
    $('#bp-settings-tab1').css('max-height', metaV2WindowHeight - 134);
    $('.pf-metav2-twocol-scroll').css('max-height', metaV2WindowHeight - 171);
    
    $('.pf-bp-navlink').on('click', 'a.nav-link', function() {
        
        var $this = $(this);
        var $content = $('.pf-metav2-content');
        var $dvContent = $('.pf-metav2-dv');
        var $mainTitle = $('.pf-metav2-main-title');
        var $twoCol = $('.pf-metav2-twocol');
        var $compositeProcess = $('.pf-metav2-compositeprocess');
        var dialogHeight = $(window).height();
        var isCorrectItem = false, isTwoColItem = false;
        
        if ($this.hasAttr('data-opt-code') && $this.attr('data-opt-code')) {
            
            var optCode = $this.attr('data-opt-code');
            
            $content.show();
            $mainTitle.show();
            $dvContent.hide();
            $twoCol.hide();
            $compositeProcess.hide();
            
            $content.find('div[data-opt-code]').hide();
            
            if ($content.find('div[data-opt-code="'+optCode+'"]').length == 0) {
                
                if (optCode == 'input') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdmetadata/setParamAttributesEditModeNew',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.Html+'</div>').promise().done(function() {
                                var $panel = $content.find('div[data-opt-code="'+optCode+'"]');
                                $panel.find("div#fz-process-params-option").css({"height": (dialogHeight - 225)+'px'});
                                $panel.find("div.params-addon-config").css({"height": (dialogHeight - 225)+'px'});
                            });
                        }
                    });
                    
                } else if (optCode == 'output') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdmetadata/setOutputParamAttributesEditModeNew',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.Html+'</div>').promise().done(function() {
                                var $panel = $content.find('div[data-opt-code="'+optCode+'"]');
                                $panel.find("div#fz-process-output-params-option").css({"height": (dialogHeight - 225)+'px'});
                                $panel.find("div.params-addon-config").css({"height": (dialogHeight - 225)+'px'});
                            });
                        }
                    });
                    
                } else if (optCode == 'fullexp') {
                
                    $.ajax({
                        type: 'post',
                        url: 'mdmeta/setProcessFullExpressionCriteria',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>', codeFrom: 'allinone'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.Html+'</div>').promise().done(function() {
                                var $panel = $content.find('div[data-opt-code="'+optCode+'"]');
                                $panel.find("div.table-scrollable").css({"height": (dialogHeight - 190) + 'px', "max-height": (dialogHeight - 190) + 'px'});
                                $panel.find(".CodeMirror").css("height", (dialogHeight - 236) + 'px');
                            });
                        }
                    });
                    
                } else if (optCode == 'fullexp-version') {
                
                    $.ajax({
                        type: 'post',
                        url: 'mdmeta/bpFullExpressionList',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.html+'</div>');
                        }
                    });
                    
                } else if (optCode == 'compositeProcess') {
                    
                    $content.hide();
                    $mainTitle.show();
                    $compositeProcess.removeClass('pl25 pr25').empty().show();
            
                    $.getScript(URL_APP + 'assets/custom/addon/plugins/jsplumb/jsplumb.min.js', function() {
                        
                        if ($("link[href='assets/custom/addon/plugins/jsplumb/css/style.v3.css']").length == 0) {
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jsplumb/css/style.v3.css"/>');
                        }
                    
                        $.getScript(URL_APP + 'middleware/assets/js/mdprocessflow.js', function() {
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdprocessflow/metaProcessWorkflow/<?php echo $this->metaDataId; ?>',
                                success: function(dataHtml) {
                                    $compositeProcess.append(dataHtml);
                                }
                            });
                        });
                    });
                    
                } else if (optCode == 'params') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdmetadata/setGroupParamAttributesNew',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.Html+'</div>').promise().done(function() {
                                var $panel = $content.find('div[data-opt-code="'+optCode+'"]');
                                $panel.find('div#fz-process-params-option').css({'height': (dialogHeight - 225)+'px'});
                                $panel.find('div.params-addon-config').css({'height': (dialogHeight - 225)+'px'});
                            });
                        }
                    });
                    
                } else if (optCode == 'dvprocess') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdmetadata/setDataModelProcessEditMode',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.Html+'</div>').promise().done(function() {
                                var $panel = $content.find('div[data-opt-code="'+optCode+'"]');
                                $panel.find('div#fz-group-process-dtl').css({'max-height': dialogHeight - 270});
                            });
                        }
                    });
                    
                } else if (optCode == 'gridoption') {
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdobject/setDataModelGridOption',
                        data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                        dataType: 'json',
                        success: function(data) {
                            $content.append('<div data-opt-code="'+optCode+'">'+data.Html+'</div>').promise().done(function() {
                                var $panel = $content.find('div[data-opt-code="'+optCode+'"]');
                                $panel.find('div#fz-grid-option').css({'height': dialogHeight - 185});
                            });
                        }
                    });
                    
                }
            } 
            
            $content.find('div[data-opt-code="'+optCode+'"]').show();
            
            isCorrectItem = true;
            
        } else if ($this.hasAttr('data-dv-id') && $this.attr('data-dv-id')) {
            
            var dvId = $this.attr('data-dv-id');
            
            $content.hide();
            $twoCol.hide();
            $compositeProcess.hide();
            $mainTitle.show();
            $dvContent.empty().show();
            
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'mdobject/dataview/' + dvId + '/0/json',
                data: {
                    dataGridDefaultHeight: dialogHeight - 246, 
                    drillDownDefaultCriteria: $this.attr('data-dv-path') + '=<?php echo $this->metaDataId; ?>'
                }, 
                success: function(data) {
                    $dvContent.append(data.Html).promise().done(function () {
                        $dvContent.find('> .row > .col-md-12:eq(0)').remove();
                    });
                },
                error: function() { alert("Error"); }
            });
            
            isCorrectItem = true;
            
        } else if ($this.hasAttr('data-metadataid') && $this.attr('data-metadataid')) {
            
            var dvId = $this.attr('data-metadataid');
            
            $content.hide();
            $dvContent.hide();
            $mainTitle.hide();
            $compositeProcess.hide();
            $twoCol.show().attr('data-listmetadataid', dvId);
            
            metaV2RefreshSecondList(<?php echo $this->uniqId; ?>, dvId);
            
            isTwoColItem = true;
        }
        
        if (isCorrectItem) {
            $mainTitle.find('> span').text($this.closest('.nav-item-submenu').find('> .nav-link > span').text() + ' / ' + $this.text());
        }
        
        if (isTwoColItem) {
            $twoCol.find('.card-header:eq(0) > span:eq(0)').text($this.text());
        }
    });
    
    $('.pf-metav2-twocol').on('click', 'button[data-secondlistaddprocessid]', function() {

        var processId = $(this).attr('data-secondlistaddprocessid');

        if (processId) {
            
            var $twoColContent = $('.pf-metav2-twocol-content');
            var $twoCol = $('.pf-metav2-twocol');
            var fillDataParams = 'metadataid=<?php echo $this->metaDataId; ?>';

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: false,
                    isHeaderName: false,
                    isBackBtnIgnore: 1, 
                    callerType: 'dv', 
                    openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"metaV2RefreshSecondList(<?php echo $this->uniqId; ?>)"}', 
                    fillDataParams: fillDataParams 
                },
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function(data) {
                    $twoColContent.empty().append(data.Html).promise().done(function() {
                        $twoCol.find('.bp-main-header > span').text('Нэмэх');
                        $twoColContent.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                        // $twoColContent.find('> .row > .xs-form > form > .row:eq(0)').css({'overflow': 'auto', 'max-height': $(window).height() - 5, 'margin-left': '-20px', 'margin-right': '-20px'});
                        Core.initBPAjax($twoColContent);
                        Core.unblockUI();
                    });
                },
                error: function() { alert('Error'); Core.unblockUI(); }
            });
        }
    });
    
    $('.pf-metav2-twocol').on('click', 'a[data-secondprocessid]', function() {

        var $this = $(this);
        var $parent = $this.closest('ul');
        var $twoCol = $('.pf-metav2-twocol');
        var $twoColContent = $('.pf-metav2-twocol-content');
        var processId = $this.attr('data-secondprocessid');
        var metaType = $this.attr('data-secondtypecode');
        var dmMetaDataId = $twoCol.attr('data-listmetadataid');
        var rowId = $this.attr('data-second-id');
        
        $twoColContent.addClass('pl30 pr30').removeClass('pl10 pr10');
        $parent.find('.dv-twocol-f-selected').removeClass('dv-twocol-f-selected');
        $this.addClass('dv-twocol-f-selected');

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
                        openParams: '{"callerType":"dv","afterSaveNoAction":true,"afterSaveNoActionFnc":"metaV2RefreshSecondList(<?php echo $this->uniqId; ?>)"}'
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        Core.blockUI({message: 'Loading...', boxed: true});
                    },
                    success: function(data) {
                        $twoColContent.empty().append(data.Html).promise().done(function() {
                            $twoCol.find('.bp-main-header > span').text($this.find('.media-title').text());
                            $twoColContent.find('.bp-btn-back, .bpTestCaseSaveButton, #boot-fileinput-error-wrap').remove();
                            // $twoColContent.find('> .row > .xs-form > form > .row:eq(0)').css({
                            //     'overflow': 'auto', 
                            //     'max-height': $(window).height() - $twoColContent.find('.meta-toolbar').offset().top - 80, 
                            //     'margin-left': '-20px', 
                            //     'margin-right': '-20px'
                            // });
                            Core.initBPAjax($twoColContent);
                            Core.unblockUI();
                        });
                    },
                    error: function() { alert('Error'); Core.unblockUI(); }
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
                
            } else if (metaType == 'bookmark') {
                
                if (processId == '1575702644228891') {
                    
                    $twoColContent.removeClass('pl30 pr30').addClass('pl10 pr10');
                    
                    var rowData = $this.data('rowdata');

                    if (!isObject(rowData)) {
                        rowData = JSON.parse(rowData);
                    }
                
                    $.ajax({
                        type: 'post',
                        url: 'mdpreview/filePreview',
                        data: {selectedRow: rowData},
                        dataType: 'json',
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            $twoColContent.empty().append(data.html).promise().done(function() {
                                $twoCol.find('.bp-main-header > span').text($this.find('.media-title').text());
                                $twoColContent.find('#file_viewer_' + data.uniqId).css('height', $(window).height() - 190);
                                Core.unblockUI();
                            });
                        },
                        error: function() { alert('Error'); Core.unblockUI(); }
                    });
                }
            }
        }
    });
    
    $('.pf-metav2-twocol').on('click', 'button[data-deleteactionbtn]', function(e) {
    
        var $this = $(this);
        var $parent = $this.closest('a');
        var id = $parent.data('second-id');

        if (id) {
            
            var $twoCol = $('.pf-metav2-twocol');
            
            runIsOneBusinessProcess($twoCol.data('listmetadataid'), $twoCol.data('deleteprocessid'), true, {id: id}, function() {
                $this.closest('li').remove();
            });

            e.preventDefault();
            e.stopPropagation();
        }
    });
    
    $('.pf-bp-navlink a.nav-link[data-opt-code="main"]').click();
    
    $('#meta-form-v2').on('change', 'input, select, textarea', function(e) {
        var $form = $('#meta-form-v2');
        if (!$form.hasAttr('data-changed')) {
            if (e.originalEvent) {
                $form.attr('data-changed', 1);
                metaConfigChangeLog($form, true);
            } else {
                var name = $(this).attr('name');
                if (name == 'metaDataCode' || name == 'tableName') {
                    $form.attr('data-changed', 1);
                    metaConfigChangeLog($form, true);
                }
            }
        }
    });
    
    $('#meta-form-v2').on('keydown', 'input[type="text"], textarea', function(e) {
        if (e.originalEvent) {
            var $form = $('#meta-form-v2');
            if (!$form.hasAttr('data-changed')) {
                $form.attr('data-changed', 1);
                metaConfigChangeLog($form, true);
            }
        }
    });
    
    $('#meta-form-v2').on('remove', function() {
        metaConfigChangeLog($('#meta-form-v2'), false); 
    });
});

window.onbeforeunload = function(e) {
    var $form = $('#meta-form-v2'); 
    if ($form.hasAttr('data-changed') && $form.attr('data-changed') == '1') {
        metaConfigChangeLog($form, false); 
        return 'Confirm';
    }
};

function metaV2RefreshSecondList(uniqId, dvId) {

    var $twoCol = $('.pf-metav2-twocol');
    var dataViewId = (typeof dvId === 'undefined') ? $twoCol.data('listmetadataid') : dvId;
    
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'mdmetadata/dvDataListWithProcess',
        data: {dvId: dataViewId, criteria: 'metaDataId=<?php echo $this->metaDataId; ?>'}, 
        dataType: 'json', 
        success: function(data) {

            var items = '';

            $twoCol.find('[data-secondlistaddprocessid]').remove();

            if (data.status == 'success') {

                var rows = data.rows, viewProcessId = '', viewMetaType = '', deleteProcessId = '', prevSecondRowId = '';

                if (data.hasOwnProperty('mainProcess') && data.mainProcess) {

                    var mainProcess = data.mainProcess, addProcessId = null;
                        
                    for (var pKey in mainProcess) {

                        if (mainProcess[pKey]['IS_MAIN'] == '1') {

                            if (mainProcess[pKey]['GET_META_DATA_ID'] == null  
                                && mainProcess[pKey]['META_TYPE_CODE'] == 'process' 
                                && mainProcess[pKey]['ACTION_TYPE'] == 'insert') {

                                addProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];

                            } else if (mainProcess[pKey]['GET_META_DATA_ID']  
                                && mainProcess[pKey]['META_TYPE_CODE'] == 'process' 
                                && mainProcess[pKey]['ACTION_TYPE'] == 'delete') {

                                deleteProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];

                            } else if (mainProcess[pKey]['GET_META_DATA_ID']) {

                                viewProcessId = mainProcess[pKey]['PROCESS_META_DATA_ID'];
                                viewMetaType = mainProcess[pKey]['META_TYPE_CODE'];
                            }
                        }
                    }

                    if (addProcessId) {
                        $twoCol.find('.card-header:eq(0)').append('<button type="button" class="btn bg-blue pt2 pb2 px-1 ml-1" data-secondlistaddprocessid="'+addProcessId+'"><i class="icon-plus2"></i></button>');
                    }

                    if (deleteProcessId) {
                        $twoCol.attr('data-deleteprocessid', deleteProcessId);             
                    }
                }

                if (rows.length) {

                    var idField = data.fieldConfig.id, nameField = data.fieldConfig.name, n = 1, removeLiClass = '', removeButton = '', rowStr = '';

                    if (deleteProcessId) {

                        removeButton = '<div class="ml-1" style="width:25px;height:26px;">'+
                            '<button type="button" class="btn btn-sm btn-outline-danger btn-icon trash-btn-hide pt-0 pb-0" data-deleteactionbtn="1">'+
                                '<i class="fa fa-trash"></i>'+
                            '</button>'+
                        '</div>';

                        removeLiClass = 'dv-twocol-remove-li';
                    }

                    for (var k in rows) {

                        rowStr = htmlentities(JSON.stringify(rows[k]), 'ENT_QUOTES', 'UTF-8');

                        items += '<li class="'+removeLiClass+'">'+
                            '<a href="javascript:void(0);" data-second-id="' + rows[k][idField] + '" data-secondprocessid="'+viewProcessId+'" data-secondtypecode="'+viewMetaType+'" class="media d-flex align-items-center justify-content-center" data-rowdata="'+rowStr+'">'+
                                '<span class="mr-2 text-blue">'+n+'.</span>'+
                                '<div class="media-body">'+
                                    '<div class="media-title mb-0">'+rows[k][nameField] + '</div>'+
                                '</div>'+
                                removeButton+ 
                            '</a>'+
                        '</li>';
                        n++;
                    }
                }
            }

            $twoCol.find('.media-list').empty().append(items);
        },
        error: function() {
            alert("Error");
        }
    });
}
function metaV2ProcessPreview(processId) {

    var $metaSystemForm = $('#meta-form-v2');
    $metaSystemForm.validate({ errorPlacement: function() {} });

    if ($metaSystemForm.valid()) {

        if (typeof fullExpressionEditor !== 'undefined') {
            fullExpressionEditor.save();
            fullExpressionOpenEditor.save();
            fullExpressionVarFncEditor.save();
            fullExpressionSaveEditor.save();  
            fullExpressionAfterSaveEditor.save();
        }

        $metaSystemForm.ajaxSubmit({
            type: 'post',
            url: 'mdmetadata/updateMetaSystemModuleForm',
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: plang.get('msg_saving_block'), boxed: true});
            },
            success: function(data) {
                PNotify.removeAll();

                if (data.status == 'success') {
                    
                    var $content = $('.pf-metav2-content');
                    var $dvContent = $('.pf-metav2-dv');
                    var $mainTitle = $('.pf-metav2-main-title');
                    var $twoCol = $('.pf-metav2-twocol');
                    var $compositeProcess = $('.pf-metav2-compositeprocess');

                    $twoCol.hide();
                    $content.hide();
                    $dvContent.hide();
                    $mainTitle.show();
                    $compositeProcess.addClass('pl25 pr25').empty().show();

                    $mainTitle.find('> span').text('<?php echo $this->lang->line('setting_run'); ?>');

                    $.ajax({
                        type: 'post',
                        url: 'mdwebservice/callMethodByMeta',
                        data: {
                            metaDataId: processId,
                            isDialog: false,
                            isSystemMeta: true,
                            runDefaultGet: '0'
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            Core.unblockUI();
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            $compositeProcess.append(data.Html).promise().done(function() {
                                $content.find('div[data-opt-code="input"], div[data-opt-code="output"]').remove();
                                $compositeProcess.find('.bp-btn-back, .card-subject-blue, .text-gray2').remove();
                                Core.initBPAjax($compositeProcess);
                                Core.unblockUI();
                            });
                        },
                        error: function() { alert('Error'); Core.unblockUI(); }
                    });

                    if (typeof isMetaArea == 'undefined') {
                        if (data.folderId == '' || data.folderId == 'null' || data.folderId == null) {
                            metaDataDefault();
                        } else {
                            refreshList(data.folderId, 'folder');
                        }
                    }

                } else {
                    
                    if (data.status === 'locked') {
                        lockedRequestMeta(data);
                    } else {
                        if (typeof data.fieldName !== 'undefined') {
                            $metaSystemForm.find("input[name='" + data.fieldName + "']").addClass('error');
                        }
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });
                    }
                    
                    Core.unblockUI();
                }
            }
        });
    }
}
function chooseMetaParentFolderV2(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        
        var rows = $('#commonBasketFolderGrid').datagrid('getRows'), 
            $cell = $(elem).closest('div'), 
            $parent = $cell.find('.meta-folder-tags');
    
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var isAddRow = true;
            $parent.find('.meta-folder-tag').each(function() {
                if ($(this).find("input[name='folderId[]']").val() === row.FOLDER_ID) {
                    isAddRow = false;
                }
            });
            if (isAddRow) {
                $parent.append('<div class="meta-folder-tag">'+
                    '<input type="hidden" name="folderId[]" value="'+row.FOLDER_ID+'">'+        
                    '<span class="parent-folder-name"><a href="mdmetadata/system#objectType=folder&objectId='+row.FOLDER_ID+'" target="_blank" title="Фолдер руу очих">'+row.FOLDER_NAME+'</a></span>'+
                    '<span class="meta-folder-tag-remove" onclick="removeMetaFolderTag(this);"><i class="fa fa-times"></i></span>'+
                '</div>');
                $cell.find('input[name="isFolderManage"]').val('1');
            }
        }
    }        
}
</script>