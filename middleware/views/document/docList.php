<div class="row mb10">
    <div class="col-md-2">
        <?php
        if ($this->isAdd) {
        ?>
        <div class="btn-group">
            <button class="btn green btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?> 
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="javascript:;" onclick="addFolder('<?php echo $this->folderId; ?>');">
                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('metadata_folder_add'); ?>
                    </a>
                    <a href="javascript:;">
                        <i class="icon-plus3 font-size-12"></i> Файл нэмэх
                    </a>
                </li>
            </ul>
        </div>
        <?php
        }
        ?>
    </div>
    <div class="col-md-3 text-center">
        <?php echo $this->lang->line('metadata_view_type'); ?>:
        <div class="btn-group btn-group-solid docSystemView-controller">
            <button class="btn btn-sm default tooltips" type="button" data-value="0" data-placement="top" data-original-title="Box view" data-container="body"><i class="fa fa-th-large"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="1" data-placement="top" data-original-title="List view" data-container="body"><i class="fa fa-reorder"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="2" data-placement="top" data-original-title="Detail view" data-container="body"><i class="fa fa-list"></i></button>
            <button class="btn btn-sm default tooltips" type="button" data-value="3" data-placement="top" data-original-title="Columns view" data-container="body"><i class="fa fa-columns"></i></button>
        </div>
    </div>
    <div class="col-md-6 text-center">
        <?php echo Form::select(array('name' => 'doc_search_type', 'id' => 'doc_search_type', 'data' => (new Mddoc())->searchType(),'onchange'=>'searchDocType(this);', 'class'=>'form-control form-control-sm  display-inline', 'op_value'=>'code', 'op_text'=>'name', 'text'=>'notext')); ?>
        <?php echo Form::text(array('name' => 'doc_search_txt', 'id' => 'doc_search_txt', 'class' => 'form-control form-control-sm d-inline', 'placeholder'=>$this->lang->line('search'), 'onkeydown'=>'if(event.keyCode==13) searchDocType(this);', 'style'=>'width:240px')); ?>
        <?php echo Form::text(array('name' => 'doc_filter_txt', 'id' => 'doc_filter_txt', 'class' => 'form-control form-control-sm  display-inline', 'onkeyup'=>'searchDocFileType(this);', 'placeholder'=>$this->lang->line('metadata_filter'))); ?>
    </div>
    <div class="col-md-1">
        <button class="btn btn-sm default tooltips float-right refreshBtn" type="button" onclick="docRefreshList('<?php echo $this->rowId; ?>', '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');"  data-placement="top" data-original-title="<?php echo $this->lang->line('refresh_btn'); ?>" data-container="body"><i class="fa fa-refresh"></i></button>
    </div>  
</div>

<div class="row mb10">
    <div class="col-md-12">
        <ul class="page-breadcrumb breadcrumb bg-grey mb0">
            <li>
                <i class="fa fa-home"></i>
                <a href="javascript:;" onclick="docListDefault();"><?php echo $this->lang->line('metadata_home'); ?></a> 
                <span class="fa fa-angle-right"></span>
            </li>
            <?php echo Mddoc_Model::getCrumbs($this->rowId, "0", $this->rowId); ?>
        </ul>
    </div>    
</div>    

<div class="row" id="main-doc-wrap">
    <div class="col-md-12">
        <div class="sorter-container list-view0">
            <div class="file-name"><a class="sorter sort-name" href="javascript:;" data-sort="name">Нэр</a></div>
            <div class="file-code"><a class="sorter sort-code" href="javascript:;" data-sort="user">Код</a></div>
            <div class="file-user"><a class="sorter sort-size" href="javascript:;" data-sort="user">Хэрэглэгч</a></div>
            <div class="file-date"><a class="sorter sort-date" href="javascript:;" data-sort="date">Огноо</a></div>
        </div>
        <ul class="grid cs-style-2 list-view0" id="main-doc-container">
            <?php
            if ($this->isBack) {
            ?>
            <li class="back">
                <figure class="back-directory">
                    <a class="folder-link" href="javascript:;" onclick="docHistoryBackList('<?php echo $this->rowId; ?>', '<?php echo $this->rowType; ?>', '<?php echo $this->params; ?>');">
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
                        <div class="box no-effect">
                            <h4><?php echo $this->lang->line('back_btn'); ?></h4>
                        </div>
                    </a>
                </figure>
            </li>
            <?php
            }
            if ($this->folderList) {
                foreach ($this->folderList as $folderRow) {
            ?>
            <li class="dir" id="<?php echo $folderRow['ID']; ?>">	
                <figure class="directory">
                    <a href="javascript:;" onclick="docChildRecordView('<?php echo $folderRow['ID']; ?>', 'folder', '<?php echo $this->params; ?>');" class="folder-link" title="<?php echo $folderRow['NAME']; ?>">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/meta/folder-mini.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $folderRow['NAME']; ?></h4>
                        </div>
                    </a>	
                    <div class="file-code"><?php echo $folderRow['CODE']; ?></div>
                    <div class="file-date"><?php echo Date::format('Y/m/d H:i', $folderRow['CREATED_DATE']); ?></div>
                    <div class="file-user"><?php echo $folderRow['CREATED_PERSON_NAME']; ?></div>
                </figure>
            </li>
            <?php
                }
            }
            if ($this->docList) {
                foreach ($this->docList as $docRow) {
            ?>
            <li class="doc" id="<?php echo $docRow['ID']; ?>" data-folder-id="<?php echo $this->rowId; ?>">	
                <figure class="directory">
                    <a href="javascript:;" class="folder-link" title="<?php echo $docRow['NAME']; ?>">
                        <div class="img-precontainer">
                            <div class="img-container directory"><span></span>
                                <img class="directory-img" src="assets/core/global/img/document/big/<?php echo $docRow['EXTENSION']; ?>.png"/>
                            </div>
                        </div>
                        <div class="img-precontainer-mini directory">
                            <div class="img-container-mini">
                                <span></span>
                                <img class="directory-img" src="assets/core/global/img/document/small/<?php echo $docRow['EXTENSION']; ?>.png"/>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis"><?php echo $docRow['NAME']; ?></h4>
                        </div>
                    </a>	
                    <div class="file-code"><?php echo $docRow['CODE']; ?></div>
                    <div class="file-date"><?php echo Date::format('Y/m/d H:i', $docRow['CREATED_DATE']); ?></div>
                    <div class="file-user"><?php echo $docRow['CREATED_PERSON_NAME']; ?></div>
                </figure>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>    
</div>   
<script type="text/javascript">
var metaIdData = [];
$(function(){
    
    $("#main-doc-wrap").selectable({
        filter: 'li.meta', 
        cancel: "a,.cancel",    
        stop: function() {
            metaIdData = [];
            $(".ui-selected", this).each(function() {
                metaIdData.push({ 
                    metaDataId: $(this).attr("id")
                });
            });
        } 
    });
    
    if ($.cookie) {
        if ($.cookie && ($.cookie('system_doc') === null || $.cookie('system_doc') == '' || $.cookie('system_doc') == undefined)) {
            var viewType = $('#docSystemView').val();
        } else {
            var viewType = $.cookie('system_doc');
            $('#docSystemView').val(viewType);
        }
    } else {
        var viewType = $('#docSystemView').val();
    }
    
    if (viewType !== '') {
        typeof $("#main-doc-wrap ul.grid")[0] != "undefined" && $("#main-doc-wrap ul.grid")[0] && ($("#main-doc-wrap ul.grid")[0].className = $("#main-doc-wrap ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        "undefined" != typeof $("#main-doc-wrap .sorter-container")[0] && $("#main-doc-wrap .sorter-container")[0] && ($("#main-doc-wrap .sorter-container")[0].className = $("#main-doc-wrap .sorter-container")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = viewType;
        $("#main-doc-wrap ul.grid").addClass("list-view" + t);
        $("#main-doc-wrap .sorter-container").addClass("list-view" + t);
        $(".docSystemView-controller button").removeClass("active");
        $(".docSystemView-controller button[data-value=" + t + "]").addClass("active");
        t >= 1 ? fix_colums(0, t) : ($("#main-doc-wrap ul.grid li").css("width", 124), $("#main-doc-wrap ul.grid figure").css("width", 122));
    }
    
    $(".docSystemView-controller button").on("click", function() {
        var e = $(this);
        $(".docSystemView-controller button").removeClass("active");
        e.addClass("active");
        typeof $("#main-doc-wrap ul.grid")[0] != "undefined" && $("#main-doc-wrap ul.grid")[0] && ($("#main-doc-wrap ul.grid")[0].className = $("#main-doc-wrap ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        "undefined" != typeof $("#main-doc-wrap .sorter-container")[0] && $("#main-doc-wrap .sorter-container")[0] && ($("#main-doc-wrap .sorter-container")[0].className = $("#main-doc-wrap .sorter-container")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = e.attr("data-value");
        $("#docSystemView").val(t);
        $("#main-doc-wrap ul.grid").addClass("list-view" + t);
        $("#main-doc-wrap .sorter-container").addClass("list-view" + t);
        if ($.cookie) {
            $.cookie('system_doc', t);
        }
        t >= 1 ? fix_colums(0, t) : ($("#main-doc-wrap ul.grid li").css("width", 124), $("#main-doc-wrap ul.grid figure").css("width", 122));
    });
    
    <?php
    if ($this->isControl) {
    ?>
    $.contextMenu({
        selector: 'ul.grid li.dir',
        callback: function(key, opt) {
            if (key === 'edit') {
                editFormFolder(opt.$trigger.attr("id"), '<?php echo $this->rowId; ?>', this);
            } else if (key === 'delete') {
                deleteFolder(opt.$trigger.attr("id"));
            } 
        },
        items: {
            "edit": {name: "<?php echo $this->lang->line('edit_btn'); ?>", icon: "edit"},
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    $.contextMenu({
        selector: 'ul.grid li.meta:not(.combo, .view, .table, .metamodel, .plan, .process, .dataview, .metagroup, .tablestructure, .back, .content)',
        callback: function(key, opt) {
            if (key === 'edit') {
                editFormMeta(opt.$trigger.attr("id"), opt.$trigger.attr("data-folder-id"), this);
            } else if (key === 'view') {
                viewMetaData(opt.$trigger.attr("id"), opt.$trigger.attr("data-folder-id"));
            } else if (key === 'delete') {
                metaDataDelete(opt.$trigger.attr("id"));
            } 
        },
        items: {
            "view": {name: "Харах", icon: "search"},
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
    
    <?php
    }
    ?>
    $(window).bind('resize', function() {
        doc_fix_colums(0, $("#docSystemView").val());
    });
    $("#doc_search_txt").focus();
});

function doc_fix_colums(e, t) {
    var a = $("#docMainRenderDiv").width() + e - 10;
    if (t > 0) {
        if (1 == t || 2 == t) $("#main-doc-wrap ul.grid li, #main-doc-wrap ul.grid figure").css("width", "100%");
        else {
            var tt = Math.floor(a / 3);
            $("#main-doc-wrap ul.grid li, #main-doc-wrap ul.grid figure").css("width", tt);
        }
    }
}
</script>