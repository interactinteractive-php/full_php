<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class'=>'form-horizontal', 'id'=>'addMetaSystemForm', 'method'=>'post', 'enctype'=>'multipart/form-data', 'autocomplete' => 'off')); ?>
<div class="row">
    <div class="col-md-7">
        <div class="form-body">
            <div class="form-group form-group-feedback form-group-feedback-left" style="width: 50%">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'metaDataCode', 
                        'id' => 'metaDataCode', 
                        'class' => 'form-control form-control-sm border-0 focus-border-grey', 
                        'required' => 'required', 
                        'tabindex' => '1', 
                        'placeholder' => $this->lang->line('META_00023'), 
                        'value' => $this->metaCode
                    )
                ); 
                ?>
                <div class="form-control-feedback form-control-feedback-sm">
                    <i class="fa fa-tag"></i>
                </div>
            </div>
            <div class="form-group row fom-row">
                <div class="col-md-12">
                    <?php 
                    echo Form::textArea(
                        array(
                            'name' => 'metaDataName', 
                            'id' => 'metaDataName', 
                            'class' => 'form-control input-text-lg border-0', 
                            'required' => 'required', 
                            'style' => 'height: 65px', 
                            'tabindex' => '2', 
                            'placeholder' => $this->lang->line('META_00114'), 
                            'value' => $this->metaName
                        )
                    ); 
                    ?>
                </div>
            </div>
            <div class="tabbable-line" id="add-meta-tabs">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a aria-expanded="false" href="#metatab_4" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00090'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#metatab_5" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00007'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#metatab_2" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00149'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#metatab_6" data-toggle="tab" class="nav-link">Bugfix</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="metatab_4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="btn-group">
                                                <?php echo Form::button(array('class'=>'btn btn-xs green-meadow','value'=>'<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'),'onclick'=>'groupCommonMetaDataGrid();')); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <?php echo $this->lang->line('metadata_view_type'); ?>:
                                            <div class="btn-group btn-group-solid singleMetaSystemView-controller">
                                                <button class="btn btn-sm default tooltips" type="button" data-value="0" data-placement="top" data-original-title="Box view" data-container="body"><i class="fa fa-th-large"></i></button>
                                                <button class="btn btn-sm default tooltips" type="button" data-value="2" data-placement="top" data-original-title="Detail view" data-container="body"><i class="fa fa-list"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="main-single-meta-wrap">
                            <div class="col-md-12">
                                <div class="sorter-container list-view0">
                                    <div class="file-name"><a class="sorter sort-name" href="javascript:;" data-sort="name"><?php echo $this->lang->line('META_00125'); ?></a></div>
                                    <div class="file-code"><a class="sorter sort-code" href="javascript:;" data-sort="user"><?php echo $this->lang->line('META_00075'); ?></a></div>
                                    <div class="file-user"><a class="sorter sort-size" href="javascript:;" data-sort="user"><?php echo $this->lang->line('META_00145'); ?></a></div>
                                    <div class="file-date"><a class="sorter sort-date" href="javascript:;" data-sort="date">ID</a></div>
                                </div>   
                                <ul class="grid cs-style-2 list-view0" id="group-meta-sortable">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="metatab_5">
                        <div class="form-group row fom-row">
                            <div class="col-md-12">
                                <?php 
                                echo Form::textArea(
                                    array(
                                        'name' => 'description', 
                                        'id' => 'description', 
                                        'class' => 'form-control border-0 pl16 focus-border-grey', 
                                        'rows' => 6, 
                                        'placeholder' => $this->lang->line('META_00007')
                                    )
                                ); 
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="metatab_2">
                        <table class="table table-hover table-light meta_files">
                            <tbody>
                                <tr>
                                    <td style="width: 210px"><input type="file" name="meta_file[]" class="col-md-12" onchange="hasFileExtension(this);"></td>
                                    <td style="width: 550px"><input type="text" name="meta_file_name[]" class="form-control col-md-12" placeholder="<?php echo $this->lang->line('META_00007'); ?>"/></td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-xs btn-success addMetaFile">
                                            <i class="icon-plus3 font-size-12"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="metatab_6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="btn-group">
                                                <?php 
                                                echo Form::button(array(
                                                    'class' => 'btn btn-xs green-meadow',
                                                    'value' => '<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'),
                                                    'onclick' => "dataViewSelectableGrid('nullmeta', '0', '16476553350499', 'multi', 'nullmeta', this, 'metaBugFixFillList');"
                                                )); 
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            <?php echo $this->lang->line('metadata_view_type'); ?>:
                                            <div class="btn-group btn-group-solid singleMetaSystemView-controller">
                                                <button class="btn btn-sm default tooltips" type="button" data-value="0" data-placement="top" data-original-title="Box view" data-container="body"><i class="fa fa-th-large"></i></button>
                                                <button class="btn btn-sm default tooltips" type="button" data-value="2" data-placement="top" data-original-title="Detail view" data-container="body"><i class="fa fa-list"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="main-single-meta-wrap">
                            <div class="col-md-12">
                                <div class="sorter-container list-view0">
                                    <div class="file-name"><a class="sorter sort-name" href="javascript:;" data-sort="name"><?php echo $this->lang->line('META_00125'); ?></a></div>
                                    <div class="file-user"><a class="sorter sort-user" href="javascript:;" data-sort="user">ID</a></div>
                                    <div class="file-date"><a class="sorter sort-date" href="javascript:;" data-sort="date">Date</a></div>
                                </div>                                
                                <ul class="grid main-edit-meta-list list-view0" id="meta-bugfix-sortable">
                                </ul>                                    
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <?php echo $this->sidebar; ?>
    </div> 
</div>
<?php
if ($this->isDialog == false) {
?>
<div class="row form-actions mt20">
    <div class="col-lg-8 ml-lg-auto">
        <?php 
        echo Form::button(
            array(
                'class' => 'btn grey-cascade meta-btn-back mr5', 
                'value' => $this->lang->line('back_btn'), 
                'onclick' => 'backFormMeta();'
            )
        ); 
        echo Form::button(
            array(
                'class' => 'btn green-meadow bp-btn-save', 
                'value' => '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'), 
                'onclick' => 'createMetaForm(this);'
            )
        ); 
        ?>
    </div>
</div>
<?php 
}
echo Form::close(); 
?>  

<script type="text/javascript">
$(function(){
    
    var $metaDataCode = $('input[name="metaDataCode"]');
    
    $metaDataCode.inputmask('Regex', {regex: '^[_A-Za-zА-Яа-яӨҮөү0-9-\+\\/\s|@$*]{1,100}$'});   
    
    setTimeout(function () {
        $metaDataCode.focus();
    }, 10);
    
    $.contextMenu({
        selector: 'ul.grid li.meta-by-group',
        callback: function(key, opt) {
            if (key === 'gotoEditMeta') {
                window.open('mdmetadata/gotoEditMeta/' + opt.$trigger.attr('id'), '_blank');
            } else if (key === 'gotoFolder') {
                window.open('mdmetadata/gotoFolder/' + opt.$trigger.attr('id'), '_blank');
            } else if (key === 'delete') {
                opt.$trigger.remove();
            }
        },
        items: {
            "gotoEditMeta": {name: plang.get('edit_btn'), icon: 'edit'}, 
            "gotoFolder": {name: 'Фолдер руу очих', icon: 'folder'}, 
            "delete": {name: plang.get('delete_btn'), icon: 'trash'}
        }
    }); 
    
    $.contextMenu({
        selector: 'ul.grid li.meta-by-bugfix',
        callback: function(key, opt) {
            if (key === 'delete') {
                opt.$trigger.remove();
            }
        },
        items: {
            "delete": {name: plang.get('delete_btn'), icon: 'trash'}
        }
    }); 
    
    var $groupMetaSortable = $('#group-meta-sortable');
    $groupMetaSortable.sortable({
        revert: true, 
        placeholder: 'meta-sortable-placeholder',
        start: function(event, ui){
            ui.placeholder.html(ui.item.html());
            ui.item.toggleClass("meta-sortable-highlight");
        },
        stop: function (event, ui){
            ui.item.toggleClass("meta-sortable-highlight");
        }
    });
    
    var viewType = 0;
    
    if (viewType !== '') {
        typeof $("#main-single-meta-wrap ul.grid")[0] != "undefined" && $("#main-single-meta-wrap ul.grid")[0] && ($("#main-single-meta-wrap ul.grid")[0].className = $("#main-single-meta-wrap ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        "undefined" != typeof $("#main-single-meta-wrap .sorter-container")[0] && $("#main-single-meta-wrap .sorter-container")[0] && ($("#main-single-meta-wrap .sorter-container")[0].className = $("#main-single-meta-wrap .sorter-container")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = viewType;
        $("#main-single-meta-wrap ul.grid").addClass("list-view" + t);
        $("#main-single-meta-wrap .sorter-container").addClass("list-view" + t);
        $(".singleMetaSystemView-controller button").removeClass("active");
        $(".singleMetaSystemView-controller button[data-value=" + t + "]").addClass("active");
        t >= 1 ? fix_colums_attach(0, t) : ($("#main-single-meta-wrap ul.grid li").css("width", 124), $("#main-single-meta-wrap ul.grid figure").css("width", 122));
    }
    
    $(".singleMetaSystemView-controller button").on("click", function() {
        var e = $(this), $parent = e.closest('.tab-pane');
        $parent.find(".singleMetaSystemView-controller button").removeClass("active");
        e.addClass("active");
        typeof $("#main-single-meta-wrap ul.grid")[0] != "undefined" && $("#main-single-meta-wrap ul.grid")[0] && ($("#main-single-meta-wrap ul.grid")[0].className = $("#main-single-meta-wrap ul.grid")[0].className.replace(/\blist-view.*?\b/g, ""));
        "undefined" != typeof $("#main-single-meta-wrap .sorter-container")[0] && $("#main-single-meta-wrap .sorter-container")[0] && ($("#main-single-meta-wrap .sorter-container")[0].className = $("#main-single-meta-wrap .sorter-container")[0].className.replace(/\blist-view.*?\b/g, ""));
        var t = e.attr("data-value");
        
        $parent.find("#main-single-meta-wrap ul.grid").removeClass('list-view0 list-view2').addClass("list-view" + t);
        $parent.find("#main-single-meta-wrap .sorter-container").removeClass('list-view0 list-view2').addClass("list-view" + t);
        
        if ($.cookie) {
            $.cookie('system_single_meta', t);
        }
        t >= 1 ? fix_colums_attach(0, t) : ($parent.find("#main-single-meta-wrap ul.grid li").css("width", 124), $parent.find("#main-single-meta-wrap ul.grid figure").css("width", 122));
        
        if (t === '2') {
            $groupMetaSortable.sortable("disable");
        } else {
            $groupMetaSortable.sortable("enable");
        }
    });  
}); 

function fix_colums_attach(e, t) {
    var a = $("#main-single-meta-wrap").width() + e - 10;
    if (t > 0) {
        if (1 == t || 2 == t) $("#main-single-meta-wrap ul.grid li, #main-single-meta-wrap ul.grid figure").css("width", "100%");
        else {
            var tt = Math.floor(a / 3);
            $("#main-single-meta-wrap ul.grid li, #main-single-meta-wrap ul.grid figure").css("width", tt);
        }
    }
}
function groupCommonMetaDataGrid(){
    var metaTypeId = $('#SYS_META_TYPE_ID').val();
    if (metaTypeId === '200101010000016' && $("#objectTableLinks").find("select#groupType").val() == 'tablestructure') {
        commonMetaDataGrid('multi', 'metaGroup', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$fieldMetaTypeId; ?>');
        return;
    }
    commonMetaDataGrid('multi', 'metaGroup', '');
    return;
}    
function proxyCommonMetaDataGrid(){
    commonMetaDataGrid('multi', 'metaProxy', '');
    return;
}   
function selectableCommonMetaDataGrid(chooseType, elem, params){
    if (elem === 'metaGroup') {
        
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                $('ul#group-meta-sortable li').each(function() {
                    if ($(this).find("input[name='childMetaDataId[]']").val() === row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });
                
                if (isAddRow) {
                    
                    var bigIcon = "assets/core/global/img/meta/file.png";
                    var smallIcon = "assets/core/global/img/meta/file-mini.png";
                    
                    if (row.META_ICON_NAME != '' && row.META_ICON_NAME != null && row.META_ICON_NAME != 'null') {
                        bigIcon = "assets/core/global/img/metaicon/big/"+row.META_ICON_NAME;
                        smallIcon = "assets/core/global/img/metaicon/small/"+row.META_ICON_NAME;
                    } else if (row.META_TYPE_ID == '<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>') {
                        bigIcon = "assets/core/global/img/meta/rar.png";
                        smallIcon = "assets/core/global/img/meta/rar-mini.png";
                        if (row.GROUP_TYPE === 'dataview') {
                            bigIcon = "assets/core/global/img/meta/dataview.png";
                            smallIcon = "assets/core/global/img/meta/dataview-mini.png";   
                        }                                                    
                    }
                    
                    $('ul#group-meta-sortable').append(
                        '<li class="meta-by-group" id="'+row.META_DATA_ID+'">'+	
                            '<figure class="directory">'+
                                '<a href="javascript:;" class="folder-link" title="'+row.META_DATA_NAME+'">'+
                                    '<div class="img-precontainer">'+
                                        '<div class="img-container directory"><span></span>'+
                                            '<img class="directory-img" src="'+bigIcon+'"/>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="img-precontainer-mini directory">'+
                                        '<div class="img-container-mini"><span></span>'+
                                            '<img class="directory-img" src="'+smallIcon+'"/>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="box">'+
                                        '<h4 class="ellipsis">'+row.META_DATA_NAME+'</h4>'+
                                    '</div>'+
                                '</a>'+
                                '<div class="file-date"><span class="d-block">'+row.META_DATA_ID+'</span></div>'+
                                '<div class="file-code"><span class="d-block">'+row.META_DATA_CODE+'</span></div>'+
                                '<div class="file-user">'+row.META_TYPE_CODE+'</div>'+
                                '<input type="hidden" name="childMetaDataId[]" value="'+row.META_DATA_ID+'">'+
                            '</figure>'+
                        '</li>');
                }
            }
        }
        
    } else if (elem == 'metaProxy') {
        
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                $('ul#proxy-meta-sortable li').each(function() {
                    if ($(this).find("input[name='proxyChildMetaDataId[]']").val() === row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });
                
                if (isAddRow) {
                    
                    var bigIcon = "assets/core/global/img/meta/file.png";
                    var smallIcon = "assets/core/global/img/meta/file-mini.png";
                    
                    if (row.META_ICON_NAME != '' && row.META_ICON_NAME != null && row.META_ICON_NAME != 'null') {
                        bigIcon = "assets/core/global/img/metaicon/big/"+row.META_ICON_NAME;
                        smallIcon = "assets/core/global/img/metaicon/small/"+row.META_ICON_NAME;
                    } else if (row.META_TYPE_ID == '<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>') {
                        bigIcon = "assets/core/global/img/meta/rar.png";
                        smallIcon = "assets/core/global/img/meta/rar-mini.png";
                        if (row.GROUP_TYPE === 'dataview') {
                            bigIcon = "assets/core/global/img/meta/dataview.png";
                            smallIcon = "assets/core/global/img/meta/dataview-mini.png";   
                        }                                                    
                    }
                    

                    var isDefaultCheckbox = '<label><input type="radio" name="isDefaultMap" value="'+row.META_DATA_ID+'"/> Дефаулт эсэх</label>';
                    
                    $('ul#proxy-meta-sortable').append(
                        '<li class="meta-by-group" id="'+row.META_DATA_ID+'">'+	
                            '<figure class="directory">'+isDefaultCheckbox+
                                '<a href="javascript:;" class="folder-link" title="'+row.META_DATA_NAME+'">'+
                                    '<div class="img-precontainer">'+
                                        '<div class="img-container directory"><span></span>'+
                                            '<img class="directory-img" src="'+bigIcon+'"/>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="img-precontainer-mini directory">'+
                                        '<div class="img-container-mini"><span></span>'+
                                            '<img class="directory-img" src="'+smallIcon+'"/>'+
                                        '</div>'+
                                    '</div>'+
                                    '<div class="box">'+
                                        '<h4 class="ellipsis">'+row.META_DATA_NAME+'</h4>'+
                                    '</div>'+
                                '</a>'+
                                '<div class="file-date"><span class="d-block">'+row.META_DATA_ID+'</span></div>'+
                                '<div class="file-code"><span class="d-block">'+row.META_DATA_CODE+'</span></div>'+
                                '<div class="file-user">'+row.META_TYPE_CODE+'</div>'+
                                '<input type="hidden" name="proxyChildMetaDataId[]" value="'+row.META_DATA_ID+'">'+
                            '</figure>'+
                        '</li>');
                    Core.initUniform($('ul#proxy-meta-sortable').find('li.meta-by-group:last'));
                }
            }
        }
        
    } else if (elem === 'metaMenu') {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                $('div#objectTableLinks').find("input[name='menuActionMetaDataId']").val(row.META_DATA_ID);
                $('div#objectTableLinks').find("span#menu-action-meta-name").text(row.META_DATA_NAME).attr("title", row.META_DATA_NAME);
            }
        }
    } 
}  
function chooseMetaParentFolder(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        var rows = $('#commonBasketFolderGrid').datagrid('getRows');
        var $cell = $(elem).closest('td');
        var $parent = $cell.find('.meta-folder-tags');
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
            }
        }
    }        
}
function metaTagSelectable(elem) {
    dataViewSelectableGrid('nullmeta', '0', '1502070138160027', 'multi', 'nullmeta', elem, 'chooseMetaTags');
}
function chooseMetaTags(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    var $parent = $(elem).closest('td').find('.meta-folder-tags');
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var isAddRow = true;
        $parent.find('.meta-folder-tag').each(function() {
            if ($(this).find("input[name='tagId[]']").val() === row.id) {
                isAddRow = false;
            }
        });
        if (isAddRow) {
            $parent.append('<div class="meta-folder-tag">'+
                '<input type="hidden" name="tagId[]" value="'+row.id+'">'+        
                '<span class="parent-folder-name">'+row.name+'</span>'+
                '<span class="meta-folder-tag-remove" onclick="removeMetaFolderTag(this);"><i class="fa fa-times"></i></span>'+
            '</div>');
        }
    }
}
function metaBugFixFillList(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    for (var i in rows) {
                
        var row = rows[i];
        var isAddRow = true;

        $('ul#meta-bugfix-sortable li').each(function() {
            if ($(this).find("input[name='childMetaBugFixId[]']").val() == row.id) {
                isAddRow = false;
            }
        });

        if (isAddRow) {

            var bigIcon = "assets/core/global/img/meta/file.png";
            var smallIcon = "assets/core/global/img/meta/file-mini.png";

            $('ul#meta-bugfix-sortable').append(
                '<li class="meta-by-bugfix -type-code" id="'+row.id+'">'+	
                    '<figure class="directory">'+
                        '<a href="javascript:;" class="folder-link" title="'+row.description+'">'+
                            '<div class="img-precontainer">'+
                                '<div class="img-container directory"><span></span>'+
                                    '<img class="directory-img" src="'+bigIcon+'"/>'+
                                '</div>'+
                            '</div>'+
                            '<div class="img-precontainer-mini directory">'+
                                '<div class="img-container-mini"><span></span>'+
                                    '<img class="directory-img" src="'+smallIcon+'"/>'+
                                '</div>'+
                            '</div>'+
                            '<div class="box">'+
                                '<h4 class="ellipsis">'+row.description+'</h4>'+
                            '</div>'+
                        '</a>'+
                        '<div class="file-user"><span class="d-block">'+row.id+'</span></div>'+
                        '<div class="file-date"><span class="d-block">'+row.createddate+'</span></div>'+
                        '<input type="hidden" name="childMetaBugFixId[]" value="'+row.id+'">'+
                    '</figure>'+
                '</li>'); 
        }
    }
}
</script>