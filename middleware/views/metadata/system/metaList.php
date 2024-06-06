<div class="row mb10">
    <div class="col-md-3">
        <?php
        $isAddMeta = Mdmeta::isAddMetaAccess();
        $isImport = Mdmeta::isAccessMetaImport();
        $isImportMetaCopy = Mdmeta::isAccessMetaImportCopy();
        $isSendTo = Mdmeta::isAccessMetaSendTo();
        
        if ($isAddMeta) {
            
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'onclick' => "addFolder('".$this->folderId."');", 
                    'class' => 'btn btn-sm green-meadow mr6'
                ), 
                '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('metadata_folder')
            );
            
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'onclick' => "addMetaBySystem('".$this->folderId."');", 
                    'class' => 'btn btn-sm green-meadow mr6'
                ), 
                '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('metadata'), 
                ($this->folderId)
            );
        }
        
        if ($isImport) {
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'onclick' => 'metaPHPImport();', 
                    'class' => 'btn btn-sm green-meadow'
                ), 
                '<i class="far fa-cloud-download"></i> Мета шинэчлэх'
            );
            
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'onclick' => "metaImportCopy('".$this->folderId."');", 
                    'class' => 'btn btn-sm bg-purple-300 ml6'
                ), 
                '<i class="far fa-clone font-size-12"></i>', 
                $isImportMetaCopy
            );
        }
        ?>
    </div>
    <div class="col-md-2 text-center">
        <?php echo $this->lang->line('metadata_view_type'); ?>:
        <div class="btn-group btn-group-solid metaSystemView-controller">
            <button class="btn btn-sm default tooltips" type="button" data-value="0" data-placement="top" data-original-title="Box view" data-container="body"><i class="fa fa-th-large"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="1" data-placement="top" data-original-title="List view" data-container="body"><i class="fa fa-reorder"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="2" data-placement="top" data-original-title="Detail view" data-container="body"><i class="fa fa-list"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="3" data-placement="top" data-original-title="Columns view" data-container="body"><i class="fa fa-columns"></i></button>
        </div>
    </div>
    <div class="col-md-6 text-center metaTopFilter">
        <?php 
            $meta = new Mdmetadata();
            $getValues = $meta->metaSearchStandartType();            
            echo '<div class="d-inline-block mr15">';
            foreach ($getValues as $key => $val) {
                echo '<div class="form-check form-check-inline mr-2">'.
                        '<label class="form-check-label">'.
                            '<input type="radio"'.($key === 0 ? ' checked' : '').' value="'.$val['code'].'" onchange="searchType(this);" class="form-check-input mt3 mr-1 search_type" name="unstyled-radio-left">'.
                            $val['name'].
                        '</label>'.
                    '</div>';
            }
            echo '</div>'; 
            ?>
            <label class="form-check-label">
                <input type="checkbox" data-off-color="info" onchange="searchType(this);" data-on-text="<?php echo $this->lang->line('META_00192') ?>" data-off-text="<?php echo $this->lang->line('META_00134') ?>" data-size="small" class="meta-search-form-check-input-switch" checked>
                <input type="hidden" id="search_type_condition" value="like">
            </label>
        <?php
            echo Form::text(array('name' => 'search_txt', 'id' => 'search_txt', 'class' => 'form-control form-control-sm d-inline', 'placeholder'=>'Бүгдээс хайх /Ctrl+Shift+X/', 'style'=>'width:200px', 'autocomplete' => 'off'));
            echo Form::text(array('name' => 'filter_txt', 'id' => 'filter_txt', 'class' => 'form-control form-control-sm d-inline', 'onkeyup'=>'searchFileType(this);', 'placeholder'=>'Эндээсээ шүүх','style'=>'width:130px', 'autocomplete' => 'off')); 
        ?>
    </div>
    <div class="col-md-1">
        <button class="btn btn-sm default tooltips float-right refreshBtn" type="button" onclick="refreshList('<?php echo $this->rowId; ?>', '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');" data-placement="top" data-original-title="<?php echo $this->lang->line('refresh_btn'); ?>" data-container="body"><i class="far fa-sync"></i></button>
    </div>  
</div>

<div class="row mb10">
    <div class="col-md-12">
        <div class="metadata-breadcrumb">
            <div class="d-flex">
                <div class="breadcrumb breadcrumb-caret meta-breadcrumb">
                    <a href="javascript:;" onclick="metaDataDefault();" class="breadcrumb-item py-1" tabindex="-1"><i class="icon-home2 mr-2"></i> <?php echo $this->lang->line('metadata_home'); ?></a> 
                    <?php echo Mdmetadata_Model::getCrumbs($this->rowId, '0', $this->rowId); ?>
                </div>
            </div>
        </div>
    </div>    
</div>    

<div class="row" id="main-meta-wrap">
    <div class="col-md-12">
        <div class="sorter-container list-view0">
            <div class="file-name"><a class="sorter sort-name" href="javascript:;" data-sort="name"><?php echo $this->lang->line('META_00125'); ?></a></div>
            <div class="file-code file-code-main"><a class="sorter sort-code" href="javascript:;" data-sort="user"><?php echo $this->lang->line('META_00075'); ?></a></div>
            <div class="file-user"><a class="sorter sort-size" href="javascript:;" data-sort="user">Хэрэглэгч</a></div>
            <div class="file-date"><a class="sorter sort-date" href="javascript:;" data-sort="date"><?php echo $this->lang->line('date'); ?></a></div>
        </div>
        <ul class="grid list-view0 pb-4" id="main-item-container">
            <?php
            if ($this->isBack) {
            ?>
            <li class="back">
                <figure class="back-directory">
                    <a class="folder-link" href="javascript:;" onclick="historyBackList('<?php echo $this->rowId; ?>', '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder_back.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/back-mini.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $this->lang->line('back_btn'); ?></h4>
                        </div>
                    </a>
                </figure>
            </li>
            <?php
            }
            if ($this->folderList) {
                foreach ($this->folderList as $folderRow) {
                    $style = (isset($metaRow['IS_ACTIVE']) &&  $folderRow['IS_ACTIVE'] == '0') ? 'border-top: 2px solid #F00; border-bottom: 2px solid #F00;' : '';
            ?>
            <li class="dir" id="<?php echo $folderRow['FOLDER_ID']; ?>">	
                <figure class="directory">
                    <a href="javascript:;" ondblclick="childRecordView('<?php echo $folderRow['FOLDER_ID']; ?>', 'folder', '<?php echo $this->params; ?>');" class="folder-link" title="<?php echo $folderRow['FOLDER_NAME']; ?>">
                        <div class="img-precontainer" style="<?php echo $style ?>">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory" style="<?php echo $style ?>">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder-mini.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $folderRow['FOLDER_NAME']; ?></h4>
                        </div>
                    </a>	
                    <div class="file-code file-code-main"><?php echo $folderRow['FOLDER_CODE']; ?></div>
                    <div class="file-date"><?php echo Date::formatter($folderRow['CREATED_DATE'], 'Y/m/d H:i'); ?></div>
                    <div class="file-user"><?php echo $folderRow['CREATED_PERSON_NAME']; ?></div>
                </figure>
            </li>
            <?php
                }
            }
            
            echo $this->metaRender;
            ?>
        </ul>
        <div class="load-more text-center" data-last-page="1" style="display: none;">
            <img src="assets/custom/addon/img/loading-spinner-grey.gif"/>
        </div>
    </div>    
</div>   

<div class="row">
    <div class="col-md-12">
        <div class="pl5" style="border-top: 1px solid #ddd">
            <span id="pfm-counts" style="display: none">
                <span id="pfm-folder-count"></span> фолдер 
                <span id="pfm-meta-count"></span> мета 
                <span id="pfm-selection-count" class="pl20"></span> 
            </span>
        </div>
    </div>    
</div>

<script type="text/javascript">
metaIdData = [];
lastIndexCheckedMeta = null;

$(function() {

    $.cachedScript('assets/core/js/plugins/forms/styling/switch.min.js').done(function() {    
        $('.meta-search-form-check-input-switch').bootstrapSwitch({
            onSwitchChange: function(e, state) { 
                if (state) {
                    $('#search_type_condition').val('like');
                } else {
                    $('#search_type_condition').val('equal');
                }
            } 
        });
    });

    var typeValue = $.cookie('meta_search_type'), conditionValue = $.cookie('meta_search_condition'), $mainMetaWrap = $('#main-meta-wrap');

    if (typeValue) {
        $('input.search_type').filter('[value="'+typeValue+'"]').attr('checked', true);
    }
    if (conditionValue) {
        if (conditionValue === 'like') {
            $('.meta-search-form-check-input-switch').prop('checked', true);
            $('#search_type_condition').val('like');
        } else {
            $('.meta-search-form-check-input-switch').prop('checked', false);
            $('#search_type_condition').val('equal');
        }
    }
    
    folderMetaCountHelper($mainMetaWrap);
    
    $('#pfm-counts').show();
    
    $mainMetaWrap.selectable({
        filter: 'li.meta', 
        cancel: 'a,.cancel,.notuniform,input[type="text"]',  
        selecting: function() {
            $(this).find('.ui-selecting input.notuniform').prop('checked', true);
        }, 
        start: function() {
            var $focusedEl = $(document.activeElement);
            
            if ($focusedEl.length && $focusedEl.is('input:text')) {
                $focusedEl.blur();
            } else {
                var $this = $(this).find('.meta-selected');
            
                if ($this.length) {
                    $this.find('input.notuniform').prop('checked', false);
                    $this.removeClass('meta-selected');
                }
            }
        }, 
        stop: function() {
            var $this = $(this);
            $this.find('.ui-selected input.notuniform').prop('checked', true);
            $this.find('.ui-selected:last a').focus();
            metaIdsSelectionHelper();
        }, 
        unselected: function() {
            var $this = $(this);
            var $checkBoxs = $this.find('.ui-selectee:not(.ui-selected) input.notuniform');
            $checkBoxs.prop('checked', false);
            $checkBoxs.closest('li').removeClass('meta-selected');
            metaIdsSelectionHelper();
        }
    });
    
    $('ul.grid > li.meta').draggable({
        cancel: 'input.notuniform',
        revert: 'invalid',
        containment: 'document',
        helper: 'clone',
        cursor: 'move'
    });
    
    $('ul.grid > li.dir').droppable({
        accept: 'ul.grid > li.meta',
        drop: function(event, ui) {
            
            var $this = $(this);
            var $meta = ui.draggable;
            var metaId = $meta.attr('id');
            
            $meta.remove();
            
            setTimeout(function(){
                folderMetaCountHelper();
                metaIdsSelectionHelper();
            }, 10);
            
            $.ajax({
                type: 'post',
                url: 'mdmetadata/changeMetaFolderMap',
                data: {oldFolderId: '<?php echo $this->folderId; ?>', newFolderId: $this.attr('id'), metaId: metaId}
            });
        }
    });
    
    $(window).bind('resize', function() {
        fix_colums(0, $("#metaSystemView").val());
        setMinHeightPfMetaGrid();
    });
    
    if ($.cookie) {
        if ($.cookie('system_meta') === null || $.cookie('system_meta') == '' || $.cookie('system_meta') == undefined) {
            var viewType = $("#metaSystemView").val();
        } else {
            var viewType = $.cookie('system_meta');
            $("#metaSystemView").val(viewType);
        }
    } else {
        var viewType = $("#metaSystemView").val();
    }
    
    if (viewType !== '') {
        typeof $("#main-meta-wrap ul.grid")[0] != "undefined" && $("#main-meta-wrap ul.grid")[0] && ($("#main-meta-wrap ul.grid")[0].className = $("#main-meta-wrap ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        "undefined" != typeof $("#main-meta-wrap .sorter-container")[0] && $("#main-meta-wrap .sorter-container")[0] && ($("#main-meta-wrap .sorter-container")[0].className = $("#main-meta-wrap .sorter-container")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = viewType;
        $("#main-meta-wrap ul.grid").addClass("list-view" + t);
        $("#main-meta-wrap .sorter-container").addClass("list-view" + t);
        $(".metaSystemView-controller button").removeClass("active");
        $(".metaSystemView-controller button[data-value=" + t + "]").addClass("active");
        if (t >= 1) {
            fix_colums(0, t);
        }
    }
    
    $(".metaSystemView-controller button").on("click", function() {
        var e = $(this);
        $(".metaSystemView-controller button").removeClass("active");
        e.addClass("active");
        typeof $("#main-meta-wrap ul.grid")[0] != "undefined" && $("#main-meta-wrap ul.grid")[0] && ($("#main-meta-wrap ul.grid")[0].className = $("#main-meta-wrap ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        "undefined" != typeof $("#main-meta-wrap .sorter-container")[0] && $("#main-meta-wrap .sorter-container")[0] && ($("#main-meta-wrap .sorter-container")[0].className = $("#main-meta-wrap .sorter-container")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = e.attr("data-value");
        $("#metaSystemView").val(t);
        $("#main-meta-wrap ul.grid").addClass("list-view" + t);
        $("#main-meta-wrap .sorter-container").addClass("list-view" + t);
        if ($.cookie) {
            $.cookie('system_meta', t, {expires: 365, path: '/'});
        }
        if (t >= 1) {
            fix_colums(0, t);
        } else {
            $('#main-meta-wrap ul.grid li, #main-meta-wrap ul.grid figure').css('width', '');
        }
    });
    
    setMinHeightPfMetaGrid();
    
    <?php
    if ($this->folderId) {
    ?>
    var $firstItem = $mainMetaWrap.find('ul.grid > li:not(.back):eq(0)');

    if ($firstItem.length) {
        $firstItem.find('a').focus();
        $firstItem.find('input').prop('checked', true);
        $firstItem.addClass('meta-selected');

        metaIdsSelectionHelper();
        
    } else {
        var $firstItem = $mainMetaWrap.find('ul.grid > li.back');
        if ($firstItem.length) {
            $firstItem.find('a').focus();
            $firstItem.addClass('meta-selected');
        }
    }
    <?php
    } else {
    ?>
    $('#search_txt').focus();
    <?php
    }
    ?>
    
    <?php
    if ($this->isControl) {
    ?>
    $.contextMenu('destroy', 'ul.grid');
    $.contextMenu('destroy', 'ul.grid li.dir');
    $.contextMenu('destroy', 'ul.grid li.meta');
    
    $.contextMenu({
        selector: 'ul.grid li.dir',
        callback: function(key, opt) {
            if (key === 'edit') {
                editFormFolder(opt.$trigger.attr("id"), '<?php echo $this->rowId; ?>', this);
            } else if (key === 'folderexport') {
                metaExport(opt.$trigger.attr("id"), 'folder');
            } else if (key === 'delete') {
                deleteFolder(opt.$trigger.attr("id"));
            } else if (key === 'groupcreate') {
                groupCreate(opt.$trigger.attr("id"));
            } else if (key === 'structurecreate') {
                structureCreate(opt.$trigger.attr("id"));
            } else if (key === 'clearcache') {
                folderCacheClear(opt.$trigger.attr("id"));
            }
        },
        items: {
            "edit": {name: "<?php echo $this->lang->line('edit_btn'); ?>", icon: "edit"},
            <?php
            if ($isAddMeta) {
            ?>
            "structurecreate": {name: "<?php echo $this->lang->line('META_00136'); ?>", icon: "database"},
            "groupcreate": {name: "<?php echo $this->lang->line('META_00019'); ?>", icon: "sitemap"}, 
            "delete": {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"}, 
            <?php
            }
            ?>
            "clearcache": {name: "<?php echo $this->lang->line('META_00137'); ?>", icon: "history"}
        }
    });
    
    $.contextMenu({
        selector: 'ul.grid li.meta',
        build: function($trigger) {
            var options = {
                callback: function(key, opt) {
                    if (key === 'edit') {
                        editFormMeta(opt.$trigger.attr('data-id'), opt.$trigger.attr("data-folder-id"), this);
                    } else if (key === 'view') {
                        viewMetaData(opt.$trigger.attr('data-id'), opt.$trigger.attr("data-folder-id"));
                    } else if (key === 'delete') {
                        metaDataDelete(opt.$trigger.attr('data-id'));
                    } else if (key === 'copy') {
                        metaCopy(opt.$trigger.attr('data-id'));
                    } else if (key === 'path') {
                        groupPathView(opt.$trigger.attr('data-id'));
                    } else if (key === 'changefolder') {
                        changeMetaFolder(opt.$trigger.attr('data-id'), metaIdData);
                    } else if (key === 'php_export') {
                        metaPHPExportById(opt.$trigger.attr('data-id'));
                    } else if (key === 'configreplace') {
                        metaConfigReplace(opt.$trigger);
                    } else if (key === 'sendto') {
                        metaSendToById(opt.$trigger.attr('data-id'));
                    } else if (key === 'configbackup') {
                        groupConfigBackup(opt.$trigger.attr('data-id'));
                    } else if (key === 'editdtl') {
                        metaFullOptions(opt.$trigger.attr('data-id'), opt.$trigger.attr('data-folder-id'), this);
                    } else if (key === 'sql') {
                        dataViewSql(opt.$trigger.attr('data-id'));
                    } else if (key === 'internalprocess') {
                        internalProcess(opt.$trigger.attr('data-id'), opt.$trigger.attr("data-folder-id"));
                    } else if (key === 'clearcache') {
                        dvCacheClear(opt.$trigger.attr('data-id'), this);
                    } else if (key === 'sqledit') {
                        dataViewQueryEditor(opt.$trigger.attr('data-id'));
                    } else if (key === 'createtable') {
                        metaTableStructure(opt.$trigger.attr('data-id'));
                    } else if (key === 'refresh') {
                        structureRefresh(opt.$trigger.attr('data-id'));
                    } else if (key === 'processflow') {
                        window.open('mdprocessflow/metaProcessWorkflow/'+opt.$trigger.attr('data-id'), '_blank');
                    } else if (key === 'bp_clearcache') {
                        bpCacheClear(opt.$trigger.attr('data-id'), this);
                    } else if (key === 'fullexp') {
                        bpFullExpressionCP(opt.$trigger.attr('data-id'));
                    } else if (key === 'fullexp_new') {
                        bpFullExpressionCPNew(opt.$trigger.attr('data-id'));
                    } else if (key === 'inputparam') {
                        bpInputParams(opt.$trigger.attr('data-id'));
                    } else if (key === 'taskflow') {
                        window.open('mdprocessflow/metaProcessWorkflow/'+opt.$trigger.attr('data-id'), '_blank');
                    } else if (key === 'copyreplace') {
                        metaCopyReplaceById(opt.$trigger.attr('data-id'), opt.$trigger.attr('data-folder-id'));
                    } else if (key === 'replace') {
                        metaReplaceById(opt.$trigger.attr('data-id'), opt.$trigger.attr('data-folder-id'));
                    } 
                },
                items: {
                    "view": {name: "<?php echo $this->lang->line('META_00111'); ?>", icon: "search"},
                    "edit": {name: plang.get('edit_btn'), icon: "edit"},
                }
            };

            if ($trigger.hasClass('metagroup')) {
                <?php
                if ($isAddMeta) {
                ?>
                options.items.copy = {name: "<?php echo $this->lang->line('META_00059'); ?>", icon: "copy"}; 
                <?php
                }
                ?>
                options.items.configreplace = {name: "Тохиргоог ижилсүүлэх", icon: "exchange"};
                options.items.configbackup = {name: "<?php echo $this->lang->line('META_00088'); ?>", icon: "download"};
                options.items.changefolder = {name: "<?php echo $this->lang->line('META_00089'); ?>", icon: "folder-open"};
                                
            } else if ($trigger.hasClass('dataview')) { 
                
                options.items.editdtl = {name: "<?php echo $this->lang->line('META_00112'); ?>", icon: "wrench"};
                options.items.sql = {name: "Query харах", icon: "search"};
                options.items.sqledit = {name: "Query editor", icon: "database"};
                <?php
                if ($isAddMeta) {
                ?>
                options.items.copy = {name: "<?php echo $this->lang->line('META_00059'); ?>", icon: "copy"};
                options.items.internalprocess = {name: "<?php echo $this->lang->line('META_00169'); ?>", icon: "retweet"};
                <?php
                }
                ?>
                options.items.configreplace = {name: "Тохиргоог ижилсүүлэх", icon: "exchange"};
                options.items.configbackup = {name: "<?php echo $this->lang->line('META_00088'); ?>", icon: "download"};
                options.items.clearcache = {name: "<?php echo $this->lang->line('META_00137'); ?>", icon: "history"};
                options.items.changefolder = {name: "<?php echo $this->lang->line('META_00089'); ?>", icon: "folder-open"};
                
            } else if ($trigger.hasClass('tablestructure')) { 
                
                <?php
                if ($isAddMeta) {
                ?>
                options.items.copy = {name: "<?php echo $this->lang->line('META_00059'); ?>", icon: "copy"}; 
                <?php
                }
                ?>
                options.items.configreplace = {name: "Тохиргоог ижилсүүлэх", icon: "exchange"}; 
                options.items.refresh = {name: "<?php echo $this->lang->line('META_00020'); ?>", icon: "sync"}; 
                options.items.configbackup = {name: "<?php echo $this->lang->line('META_00088'); ?>", icon: "download"}; 
                options.items.clearcache = {name: "<?php echo $this->lang->line('META_00137'); ?>", icon: "history"}; 
                options.items.changefolder = {name: "<?php echo $this->lang->line('META_00089'); ?>", icon: "folder-open"}; 
            
            } else if ($trigger.hasClass('process')) { 
                
                options.items.editdtl = {name: "<?php echo $this->lang->line('META_00112'); ?>", icon: "wrench"}; 
                options.items.inputparam = {name: plang.get('META_00046'), icon: "list"}; 
                options.items.processflow = {name: "<?php echo $this->lang->line('META_00022'); ?>", icon: "cogs"}; 
                <?php
                if ($isAddMeta) {
                ?>
                options.items.copy = {name: "<?php echo $this->lang->line('META_00059'); ?>", icon: "copy"}; 
                <?php
                }
                ?>
                options.items.configreplace = {name: "Тохиргоог ижилсүүлэх", icon: "exchange"}; 
                options.items.configbackup = {name: "<?php echo $this->lang->line('META_00088'); ?>", icon: "download"}; 
                options.items.fullexp = {name: "Full expression", icon: "calculator"}; 
                options.items.fullexp_new = {name: "<?php echo $this->lang->line('META_00138'); ?>", icon: "calculator"}; 
                options.items.bp_clearcache = {name: "<?php echo $this->lang->line('META_00137'); ?>", icon: "history"}; 
                options.items.changefolder = {name: "<?php echo $this->lang->line('META_00089'); ?>", icon: "folder-open"}; 
            
            } else if ($trigger.hasClass('taskflow')) {
                
                options.items.taskflow = {name: "<?php echo $this->lang->line('META_00022'); ?>", icon: "cogs"}; 
                options.items.configreplace = {name: "Тохиргоог ижилсүүлэх", icon: "exchange"}; 
                options.items.bp_clearcache = {name: "<?php echo $this->lang->line('META_00137'); ?>", icon: "history"}; 
                options.items.changefolder = {name: "<?php echo $this->lang->line('META_00089'); ?>", icon: "folder-open"}; 
                
            } else {
                <?php
                if ($isAddMeta) {
                ?>
                options.items.copy = {name: "<?php echo $this->lang->line('META_00059'); ?>", icon: "copy"};
                <?php
                }
                ?>
                options.items.configreplace = {name: "Тохиргоог ижилсүүлэх", icon: "exchange"};
                options.items.changefolder = {name: "<?php echo $this->lang->line('META_00089'); ?>", icon: "folder-open"};
            }
            
            if ($trigger.hasClass('menu_meta') || $trigger.hasClass('dataview') || $trigger.hasClass('process') || $trigger.hasClass('statement') || $trigger.hasClass('report_template')) {
                options.items.copyreplace = {name: 'Copy & Replace', icon: "paste"}; 
                options.items.replace = {name: 'Replace', icon: "exchange-alt"}; 
            }
            
            <?php
            if ($isAddMeta) {
            ?>
            options.items.delete = {name: "<?php echo $this->lang->line('META_00002'); ?>", icon: "trash"}; 
            <?php
            } 
            if ($isSendTo) {
            ?>
            options.items.sendto = {name: 'Send to', icon: "share-square"}; 
            <?php
            }
            ?>
                                
            options.items.php_export = {name: 'Export', icon: 'download'};

            return options;
        }
    });
        
    
    $.contextMenu({
        selector: 'ul.grid',
        callback: function(key, opt) {
            
            var folderId = '<?php echo $this->folderId; ?>';
            
            if (key === 'refresh') {
                refreshList(folderId, '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');
            } else if (key === 'paste') {
                
                if (navigator.clipboard != undefined && navigator.clipboard.readText != undefined) { // Chrome
                    
                    navigator.clipboard.readText().then(function (textFromClipboard) {
                        clipboardMetaPaste(folderId, textFromClipboard);
                    });
                    
                } else if (window.clipboardData) { // Internet Explorer
                    clipboardMetaPaste(folderId, window.clipboardData.getData('Text'));
                } else {
                    alert('Please use chrome browser!');
                }
        
            } else if (key === 'newfolder') {
                addFolder(folderId);
            } else if (key === 'newmeta') {
                addMetaBySystem(folderId);
            } 
        },
        items: {
            'refresh': {name: plang.get('refresh_btn'), icon: 'sync'}, 
            <?php
            if ($isAddMeta) {
            ?>
            'paste': {name: 'Paste', icon: 'paste'}, 
            "sep1": "---------",
            'newfolder': {name: plang.get('metadata_folder_add'), icon: 'plus'}, 
            <?php 
                if ($this->folderId) { 
            ?>
            'newmeta': {name: plang.get('metadata_add'), icon: 'plus'}
            <?php
                }
            }
            ?>
        }
    });
    <?php
    }
    ?>
    
});
</script>