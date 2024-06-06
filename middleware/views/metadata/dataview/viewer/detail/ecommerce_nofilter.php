<div class="page-content ecommerce_<?php echo $this->metaDataId ?> <?php echo 'dvecommerce'; ?> <?php echo isset($this->appendClass) ? $this->appendClass : '' ?>" id="objectDataView_<?php echo $this->metaDataId; ?>" style="background: #FFF !important; margin: 0; margin-right: 15px; padding-left: 5px; padding-right: 5px; width: 99%">
    <div class="w-100">
        <div class="div-objectdatagrid-<?php echo $this->metaDataId; ?> md-listcomment jeasyuiThemeecommerceView ecommerce_timeline_zasag <?php echo $this->dataGridOptionData['VIEWTHEME'] . ' ' . $this->layoutTheme; ?> web-dataview">
            <table id="objectdatagrid-<?php echo $this->metaDataId; ?>"></table>
        </div>
        <div class="div-dataGridLayout-<?php echo $this->metaDataId; ?>"></div>
        <div class="<?php echo $this->dataViewClass; ?>"><div id="md-map-canvas-<?php echo $this->metaDataId; ?>" style="display: none;"></div></div>            
    </div>
</div>
<div id="objectDashboardView_<?php echo $this->metaDataId; ?>"></div>
<div id="objectReportTemplateView_<?php echo $this->metaDataId; ?>"></div>

<?php
echo Form::hidden(array('id' => 'cardViewerFieldPath'));
echo Form::hidden(array('id' => 'cardViewerValue'));
echo Form::hidden(array('id' => 'treeFolderValue'));
echo Form::hidden(array('id' => 'currentSelectedRowIndex'));
echo Form::hidden(array('id' => 'refStructureId', 'value' => $this->refStructureId));
?>
<div class="right-sidebar" data-status="closed">
    <div class="stoggler sidebar-right hide">
        <span class="fa fa-chevron-right hide">&nbsp;</span> 
        <span class="fa fa-chevron-left">&nbsp;</span>
    </div>
    <div class="right-sidebar-content"></div>
</div>

<script type="text/javascript">
    no_resizable_<?php echo $this->metaDataId; ?> = true;
</script>
<?php echo $this->mainDvScripts; ?>
<script type="text/javascript">
    
    $(document).ready(function () {
        $('.ecommerce_timeline').find('.datagrid-body').addClass('card-columns');
        $('.ecommerce_<?php echo $this->metaDataId ?>').find('.pagination-info').hide();
    });

    $(function () {
        $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').on('click', '.tab-criteria-value', function (e) {
            var $this = $(this);

            $('#objectDataView_<?php echo $this->metaDataId; ?>').find('.list_name').text($this.find('.greenbtntext').text());
            if ($this.hasClass('card'))
                return;

            dv_search_<?php echo $this->metaDataId; ?>.find('input[data-path="' + $this.data('path') + '"]').val($this.data('id'));
            dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
        });

        $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').on('click', '.nextTabCriteriaData', function (e) {
            var $this = $(this),
                    indexKey = Number($this.attr('data-step'));

            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('.prevTabCriteriaData').show();

            $this.parent().find('.prevTabCriteriaData').attr('data-step', indexKey);
            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('div[data-index="' + indexKey + '"]').addClass('hidden');
            $this.attr('data-step', (++indexKey));

            if (!$('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('div[data-index="' + (++indexKey) + '"]').length) {
                $this.hide();
            }
        });

        $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').on('click', '.prevTabCriteriaData', function (e) {
            var $this = $(this),
                    indexKey = Number($this.attr('data-step'));

            if (indexKey == 0) {
                $this.hide();
            }
            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('.nextTabCriteriaData').show();

            $this.parent().find('.nextTabCriteriaData').attr('data-step', indexKey);
            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('div[data-index="' + indexKey + '"]').removeClass('hidden');
            $this.attr('data-step', (--indexKey));
        });

        dv_search_<?php echo $this->metaDataId; ?>.find('.bp-icon-selection').on('click', 'li', function (e) {
            var $this = $(this);
            setTimeout(function () {
                dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').first().click();
                $('#objectDataView_<?php echo $this->metaDataId; ?>').find('.list_name').text($this.find('p').text());
            }, 1);
        });

        if ($(".sub-dv-list-<?php echo $this->metaDataId; ?>").find("li:eq(1)").length && $(".sub-dv-list-<?php echo $this->metaDataId; ?>").find("li:eq(0)").data('permission') == '1') {
            $(".sub-dv-list-<?php echo $this->metaDataId; ?>").find("li:eq(1)").find("a").click();
        }

        $("#checkAll_<?php echo $this->metaDataId; ?>").click(function () {
            $('#basket_ecommerce_<?php echo $this->metaDataId; ?>').find('input[type="checkbox"]').prop('checked', this.checked).parent().addClass('checked');
            if (!this.checked) {
                $('#basket_ecommerce_<?php echo $this->metaDataId; ?>').find('input[type="checkbox"]').parent().removeClass('checked');
            }
        });
    });

    function lookupCriteriaTabMoreLink(elem) {
        if ($(elem).hasClass('moreclick')) {
            $(elem).addClass('lessclick').removeClass('moreclick').text('Бүгд').attr('title', 'Дэлгэрэнгүй харах').parent().css('height', '32px');
        } else {
            $(elem).addClass('moreclick').removeClass('lessclick').text('Хураангуй').attr('title', 'Хураангуй харах').parent().css('height', '');
        }
    }

    function dvecommerceAdvancedCriteria<?php echo $this->metaDataId; ?>(elem) {
        var $thisP = $('#dvecommerce-advanced-criteria-<?php echo $this->metaDataId; ?>'),
                $dialogname = 'dialog-dvecommerce-criteria-<?php echo $this->metaDataId; ?>';

        if (!$('#' + $dialogname).length) {
            $('<div id="' + $dialogname + '"></div>').appendTo($thisP.parent());
            var dialogname = $('#' + $dialogname),
                    criteriaCopy = $thisP.html();

            $thisP.empty();
            dialogname.empty().append(criteriaCopy);
            dialogname.dialog({
                appendTo: $thisP.parent(),
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Advanced criteria',
                height: 'auto',
                width: '650',
                modal: true,
                open: function () {
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-title').css("text-align", "left");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm blue mr0');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm ml5');
                },
                close: function (elem) {
                    dialogname.dialog('close');
                },
                buttons: [
                    {text: plang.get('do_filter'), click: function () {
                            dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                            dialogname.dialog('close');
                        }},
                    {text: plang.get('clear_btn'), click: function () {
                            dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-reset-btn').click();
                        }},
                    {text: plang.get('close_btn'), click: function () {
                            dialogname.dialog('close');
                        }}
                ]
            }).dialogExtend({
                'closable': true,
                'maximizable': false,
                'minimizable': false,
                'collapsable': false,
                'dblclick': 'maximize',
                'minimizeLocation': 'left',
                'icons': {
                    'close': 'ui-icon-circle-close',
                    'maximize': 'ui-icon-extlink',
                    'minimize': 'ui-icon-minus',
                    'collapse': 'ui-icon-triangle-1-s',
                    'restore': 'ui-icon-newwin'
                }
            });
            dialogname.dialog('open');
            //dialogname.dialogExtend("maximize");
        } else {
            var dialogname = $('#' + $dialogname);
            dialogname.dialog('open');
            //dialogname.dialogExtend("maximize");
        }
    }

    function pushCommerceBasket<?php echo $this->metaDataId; ?>(elem) {
        if (!$("#basket_ecommerce_<?php echo $this->metaDataId; ?>").length) {
            return;
        }

        var row = JSON.parse(decodeURIComponent($(elem).data('row-data')));
        var isAdded = false,
            rowId = row.id;

        for (var key in _selectedRows_<?php echo $this->metaDataId; ?>) {
            var basketRow = _selectedRows_<?php echo $this->metaDataId; ?>[key], childId = basketRow.id;

            if (rowId == childId) {
                isAdded = true;
                break;
            }
        }

    <?php $typeRow = $this->row['dataViewLayoutTypes']['ecommerce_nofilter']; ?>
        var basketPhoto = '<?php echo issetParam($typeRow['fields']['basketphoto']); ?>'.toLowerCase();

        if (!isAdded) {
            $(elem).closest('.card-wrapper-eui').find('input[type="checkbox"]').prop('checked', true);
            _selectedRows_<?php echo $this->metaDataId; ?>.push(row);
            basketPhoto = basketPhoto != '' ? '<img src="' + row.basketPhoto + '" onerror="onUserImgError(this);">' : '';

    <?php if (isset($typeRow['fields']['basketcode']) && isset($typeRow['fields']['basketname'])) { ?>
            Core.initUniform($("#basket_ecommerce_<?php echo $this->metaDataId; ?>"));
        <?php } ?>
        }}
        
    function removeCommerceBasket<?php echo $this->metaDataId; ?>(elem) {
        var $this = $(elem), index = $this.closest('li').data('index') - 1, $ulparent = $this.closest('ul');
        $this.closest('li').remove();
        delete _selectedRows_<?php echo $this->metaDataId; ?>[index];

        $('.basket_ecommerce_counter_<?php echo $this->metaDataId; ?>').text('(' + $ulparent.children().length + ')');
        _selectedRows_<?php echo $this->metaDataId; ?> = _selectedRows_<?php echo $this->metaDataId; ?>.filter(function (el) {
            return true;
        });
    }

    function appMultiTabEcommerce<?php echo $this->metaDataId; ?>(elem, metaId, name) {
        if (typeof vr_top_menu !== 'undefined' && vr_top_menu) {

            var $tabMainContainer = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');

            if ($tabMainContainer.length == 0) {
                $("div.pf-header-main-content").html('<div class="">' +
                        '<div class="card light shadow card-multi-tab">' +
                        '<div class="card-body">' +
                        '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

                $('div.m-tab').html('<div class="card-header header-elements-inline tabbable-line">' +
                        '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                        '</div>');

                $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
            }

        } else {
            var $tabMainContainer = $("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
            if ($tabMainContainer.length == 0) {
                $("div.pf-header-main-content").html('<div class="">' +
                        '<div class="card light shadow card-multi-tab">' +
                        '<div class="card-header header-elements-inline tabbable-line">' +
                        '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                        '<div class="header-elements">' +
                        '<div class="list-icons">' +
                        '<a class="list-icons-item" data-action="fullscreen"></a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

                $tabMainContainer = $('body').find("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
            }
        }

        var param = {},
            _this = $(elem);

        $tabMainContainer.find("a[href='#app_tab_<?php echo $this->metaDataId; ?>']").parent().attr('data-type', 'dataview');
        param['metaDataId'] = metaId;
        param['title'] = name;
        param['type'] = 'dataview';
        appMultiTab(param, _this.find('a'), function (div, param) {
            multiTabCloseConfirm($tabMainContainer.find("a[href='#app_tab_<?php echo $this->metaDataId; ?>']"));
        });
    }

    $(function () {
        
        $.contextMenu({
            selector: "div#object-value-list-<?php echo $this->metaDataId; ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#object-value-list-<?php echo $this->metaDataId; ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
            build: function($trigger, e) {
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
                                . 'icon: "' . $contextMenuIcon . '", '
                                . 'callback: function(key, options) {'
                                . 'transferProcessCriteria(\'' . $this->metaDataId . '\', \'' . $row['BATCH_NUMBER'] . '\', \'context\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'});'
                                . '}'
                                . '},';
                            } elseif ($row['STANDART_ACTION'] == 'processCriteria') {
                                echo '"' . $cm . '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", '
                                . 'callback: function(key, options) {';
                                
                                if ($row['ADVANCED_CRITERIA'] != '') {
                                    echo '_dvAdvancedCriteria = "'.$row['ADVANCED_CRITERIA'].'";';
                                }
                                
                                echo 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                            } else {
                                echo '"' . $cm. '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", '
                                . 'callback: function(key, options) {'
                                . 'transferProcessAction(\'\', \'' . $this->metaDataId . '\', \'' . $row['STANDART_ACTION'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                            }
                        } else {
                            echo '"' . $cm. '": {'
                                . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                                . 'icon: "' . $contextMenuIcon . '", '
                                . 'callback: function(key, options) {'
                                . 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->metaDataId . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                                . '}'
                                . '},';
                        }
                    }
                    if ($this->isPrint) { 
                        echo '"9999999": {'
                                . 'name: "' . $this->lang->line('printTemplate') . '", '
                                . 'icon: "print", '
                                . 'callback: function(key, options) {'
                                . 'dataViewPrintPreview_'.$this->metaDataId.'(\''.$this->metaDataId.'\', true, \'toolbar\', this);'
                                . '}'
                                . '},';
                    }
                    ?>
                };

                if (typeof wfmActions !== 'undefined') {
                    var $wfmLi = $('.workflow-dropdown-<?php echo $this->metaDataId ?>').find('li'), $thisli;

                    $wfmLi.each(function() {
                        $thisli = $(this);

                        contextMenuData[$thisli.find('a').attr('onclick')] = {
                            name: $thisli.find('a').text(),
                            icon: 'icon-shuffle'
                        };
                    });
                };
                
                var options =  {
                    callback: function (key, opt) {
                        eval(key);
                        //transferProcessAction('', '<?php echo $this->metaDataId; ?>', key, '<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>', 'grid', opt.$trigger, {callerType: '<?php echo $this->metaDataCode ?>'}, '');
                    },
                    items: contextMenuData
                };
                
                return options;
            }
        });
    });

</script>
<style type="text/css">
    .ecommerce_<?php echo $this->metaDataId ?> .pagination-info {
        display: none !important;
    }
    .content {
        background: #ebebeb;
    }
    .select2-results {
        padding: 0;
    }
    .select2-results .select2-result-label {
        padding: 10px 5px 10px;
    }
    .dvecommerce .dvecommercetitle a {
        padding: 0;
        margin-bottom: 15px;
    }
    .select2-container-multi .select2-choices .select2-search-choice {
        background-color: #455a64;
        color: #fff;
        margin-right: .125rem !important;
        margin-top: .125rem !important;
        padding: .4125rem 1.375rem .4125rem .875rem !important;
        white-space: normal;
        word-break: break-all;
        border-radius: .1875rem;
        transition: color ease-in-out .15s,background-color ease-in-out .15s;
        border: 0;
    }
    .chat-container, .chat-container-parent {
        display: table;
        padding: 8px 10px 0px 10px;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        background-color: rgb(170, 103, 8);
        color: #fff;
        margin-left: 45px;
        margin-bottom: 2px;
    }
    .chat-container-self, .chat-container-parent-self {
        display: table;
        padding: 8px 10px 0px 10px;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        background-color: #E2E2E2;
        margin-bottom: 2px;
    }
    .chat-container-parent {
        position: relative;
        border-top-left-radius: 0;
        -webkit-border-top-left-radius: 0;
        -moz-border-top-left-radius: 0;
    }
    .chat-container-parent-self {
        position: relative;
        border-bottom-right-radius: 0;
        -webkit-border-bottom-right-radius: 0;
        -moz-border-bottom-right-radius: 0;
    }
    .chat-container-parent:after {
        content: '';
        display: block;
        position: absolute;
        right: 100%;
        top: 10px;
        margin-top: -10px;
        width: 0;
        height: 0;
        border-top: 0px solid transparent;
        border-right: 4px solid rgb(170, 103, 8);
        border-bottom: 6px solid transparent;
        border-left: 6px solid transparent;
    }
    .chat-container-parent-self:after {
        content: '';
        display: block;
        position: absolute;
        left: 100%;
        bottom: 0;
        margin-top: -10px;
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-left: 4px solid #E2E2E2;
        border-bottom: 0px solid transparent;
        border-right: 6px solid transparent;
    }
    .chat-container-child {
        margin-top: 1px;
    }
    .chat-user-img {
        width: 40px;
    }
    .chat-user-img.chat-user-date {
        margin-top: 0;
    }
    .chat-created-date {
        margin-bottom: 2px;
        margin-top: 2px;
        color: #9E9E9E;
        font-size: 10px;
    }
    .thumbnail_custom {
        height: 32px;
        width: 32px;
    }
    
    .ecommerce_<?php echo $this->metaDataId ?> .datagrid-btable { 
        border: none;
    }
    
    .ecommerce_<?php echo $this->metaDataId ?> .fancybox-button img {
        height: 50px !important;
        width: 50px !important;
    }
    
    .panel-body-scroll {
        overflow-y: auto;    
        overflow-x: hidden;
        visibility: hidden;
        margin-right: -15px;
        padding-right: 15px;
    }
    #chatMessages {
        padding-bottom: 0px !important;
        background-color: #fff;
    }
    #chat-body-container {
        visibility: visible;
    }
    .panel-body-scroll:hover {
        visibility: visible;
    }
    .user-active-img {
        height: 6px;
        width: 6px;
        position: absolute;
        margin-top: 16px;
        margin-left: -10px;
        background: #fff url(../images/user_active_img.png) no-repeat;
    }
    .required {
        color: #a94442;
    }
    .custom-modal-header {
        padding: 10px;
        border-bottom: 1px solid #e5e5e5;
        height: 42px;
    }
    .custom-modal-body {
        position: relative;
        padding: 10px;    
    }
    .custom-modal-footer {
        padding: 6px 10px 10px 10px;
        text-align: right;
        border-top: 1px solid #e5e5e5;
        height: 42px;
    }
    .custom-modal-header .modal-title {
        font-size: 15px;
        font-weight: bold;
    }
    .modal-350 {
        width: 350px;
    }
    label {
        font-weight: normal !important;
    }
    .cropImgWrap {
        background: transparent;
        overflow: hidden;
        width: 468px;
        height: 320px;
        margin-left: -27px;
    }
    img-crop {
        margin-top: 6px;
    }
    .media {
        margin-top: 0;
    }
    .media-heading {
        margin-bottom: 0;
    }
    .badge-custom-background {
        background-color: #a0a0a0 !important;
    }
    .userActive {
        border-left: 2px solid #337AB7;
        font-weight: bold;
        font-family: "Roboto", "Helvetica Neue",Helvetica,Arial,sans-serif;
        background-color: #F5F5F5;
    }
    .user-profile-link {
        position: absolute;
        right: 0;
        padding-right: 48px;
        margin-top: 11px;    
        width: 160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;        
        text-align: right;
    }
    .user-signout {
        position: absolute;
        right: 0;
        margin-right: 17px;
        margin-top: 11px; 
    }
    .loader-typing:before,
    .loader-typing:after,
    .loader-typing {
        border-radius: 50%;
        width: 2.6em;
        height: 2.6em;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        -webkit-animation: load7 1.6s infinite ease-in-out;
        animation: load7 1.6s infinite ease-in-out;
    }
    .loader-typing {
        color: #009900;
        font-size: 2.4px;
        margin: 0px 12px 12px 10px;
        position: relative;
        text-indent: -9999em;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }
    .loader-typing:before {
        left: -3.5em;
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }
    .loader-typing:after {
        left: 3.5em;
    }
    .loader-typing:before,
    .loader-typing:after {
        content: '';
        position: absolute;
        top: 0;
    }
    @-webkit-keyframes load7 {
        0%,
        80%,
        100% {
            box-shadow: 0 2.5em 0 -1.3em;
        }
        40% {
            box-shadow: 0 2.5em 0 0;
        }
    }
    @keyframes load7 {
        0%,
        80%,
        100% {
            box-shadow: 0 2.5em 0 -1.3em;
        }
        40% {
            box-shadow: 0 2.5em 0 0;
        }
    }
    label.custom-file-input input[type="file"] {
        position: fixed;
        top: -1000px;
    }
    label.custom-file-input span {
        opacity: 0.6;
        font-size: 15px;
        color: black;
    }
    label.custom-file-input span:hover {
        display: inline-block;
        opacity: 1;
    }
    .user-list-right-section {
        overflow-y: auto;
        overflow-x: hidden;
    }
    .emoji-wysiwyg-editor {
        height: 38px !important;
        padding-left: 11px !important;
        padding-top: 9px !important;
        font-size: 15px !important;
        overflow: hidden !important;
    }
    .emoji-picker-icon {
        position: inherit !important;
        opacity: 0.6 !important;
        font-size: 17px !important;
        cursor: default !important;
    }
    .emoji-picker-icon:hover {
        opacity: 1 !important;
    }
    .main-wrap .panel-title .chat-header-title {
        font-size: 14px !important;
    }
    .main-wrap .panel {
        margin-bottom: 0px !important;
    }
    .tooltip.customTooltipClass .tooltip-inner {
        font-size: 11px;
    }

    .dvecommerce-body .ui-dialog .ui-widget-header {
        height: 40px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-title {
        line-height: 24px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane button {
        padding: 5px 20px;
        text-transform: uppercase;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane {
        margin-top: 0;
        background: #DDD;
        border: 0;
        padding: 5px 10px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-content {
        padding: 10px 15px 0;
    }
</style>