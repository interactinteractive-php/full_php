<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php 
echo Form::create(array(
    'class' => 'form-horizontal', 
    'id' => 'editMetaSystemForm', 
    'method' => 'post', 
    'enctype' => 'multipart/form-data', 
    'autocomplete' => 'off', 
    'data-metadataid' => $this->metaDataId
)); 
?>
<div class="row">
    <div class="col-md-7">
        <div class="form-body">
            <div class="form-group form-group-feedback form-group-feedback-left float-left" style="width: 50%">
                <?php
                echo Form::text(
                    array(
                        'name' => 'metaDataCode', 
                        'id' => 'metaDataCode', 
                        'class' => 'form-control form-control-sm border-0 focus-border-grey',
                        'required' => 'required',
                        'placeholder' => $this->lang->line('META_00023'),
                        'value' => $this->metaRow['META_DATA_CODE']
                    )
                );
                ?>
                <div class="form-control-feedback form-control-feedback-sm">
                    <i class="fa fa-tag"></i>
                </div>
            </div>
            <div class="float-right" style="color: #999">
                <?php echo $this->metaDataId; ?>
            </div>
            <div class="clearfix w-100"></div>
            <div class="form-group row fom-row">
                <div class="col-md-12">
                    <?php
                    if (isset($this->checkMetaData) && $this->isBackBtn == false) {
                        echo Form::textArea(
                            array(
                                'name' => 'metaDataName',
                                'id' => 'metaDataName',
                                'class' => 'form-control input-text-lg border-0',
                                'required' => 'required',
                                'style' => 'height: 65px',
                                'placeholder' => $this->lang->line('META_00114'),
                                'value' => $this->metaRow['META_DATA_NAME']
                            )
                        );
                    } else {
                        echo Form::textArea(
                            array(
                                'name' => 'META_DATA_NAME',
                                'id' => 'META_DATA_NAME',
                                'class' => 'form-control input-text-lg border-0',
                                'required' => 'required',
                                'style' => 'height: 65px',
                                'placeholder' => $this->lang->line('META_00114'),
                                'value' => $this->metaRow['META_DATA_NAME']
                            )
                        );
                    }
                    ?>
                </div>
            </div>

            <div class="tabbable-line" id="editMetaTabDiv">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a aria-expanded="false" href="#editmetatab_4" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00090'); ?></a>
                    </li>
                    <?php
                    if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$proxyMetaTypeId) {
                    ?>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#editmetatab_6" data-toggle="tab" class="nav-link">Proxy map</a>
                    </li>
                    <?php
                    } if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$statementMetaTypeId) {
                    ?>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#editmetatab_7" data-toggle="tab" class="nav-link">Хувилбарын үзүүлэлт</a>
                    </li>
                    <?php
                    } 
                    ?>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#editmetatab_5" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00007'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#editmetatab_2" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00149'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a aria-expanded="false" href="#editmetatab_8" data-toggle="tab" class="nav-link">Bugfix</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="editmetatab_4">
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
                                <?php if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$dmMetaTypeId) { ?>
                                    <div class="grid main-edit-meta-list list-view0 row" id="group-meta-sortable-dm">
                                    </div>
                                    <a class="btn btn-success btn-circle btn-sm" title="" onclick="dmSourceSrcMap()" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i> Нэмэх</a>
                                    <div style="border: 1px solid rgb(221, 221, 221);height: 250px;overflow: auto;" class="hidden mt12" id="group-meta-sortable-dm-table">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <td>Output</td>
                                                    <td>Expression</td>
                                                    <td>Aggregate</td>
                                                    <td>Alias</td>
                                                    <td>Sort Type</td>
                                                    <td>Sort Order</td>
                                                    <td>Grouping</td>
                                                    <td>Criteria</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="group-meta-sortable-dm-table2" class="mt12 hidden">
                                        <a class="btn btn-success btn-circle btn-sm" title="" onclick="dmSourceTargetMap()" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i> Нэмэх</a>
                                        <div style="border: 1px solid rgb(221, 221, 221);height: 250px;overflow: auto;" class="mt5">                                    
                                            <table class="table table-sm table-hover">
                                                <thead>
                                                    <tr>
                                                        <td>Source Group</td>
                                                        <td>Source Path</td>
                                                        <td>Target Group</td>
                                                        <td>Target Path</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>                                    
                                <?php } else { ?>
                                    <ul class="grid main-edit-meta-list list-view0" id="group-meta-sortable">
                                        <?php echo $this->metaDatas; ?>
                                    </ul>                                    
                                <?php } ?>
                            </div>
                        </div>    
                    </div>
                    <?php
                    if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$proxyMetaTypeId) {
                    ?>
                    <div class="tab-pane" id="editmetatab_6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="btn-group">
                                                <?php echo Form::button(array('class'=>'btn btn-xs green-meadow','value'=>'<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'),'onclick'=>'proxyCommonMetaDataGrid();')); ?>
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
                                <ul class="grid main-edit-meta-list list-view0" id="proxy-meta-sortable">
                                    <?php echo $this->proxyChildMetas; ?>
                                </ul>
                            </div>
                        </div>    
                    </div>
                    <?php
                    } if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$statementMetaTypeId) {
                    ?>
                    <div class="tab-pane" id="editmetatab_7">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-toolbar">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="btn-group">
                                                <?php echo Form::button(array('class'=>'btn btn-xs green-meadow','value'=>'<i class="icon-plus3 font-size-12"></i> '.$this->lang->line('META_00103'),'onclick'=>'statementCommonMetaDataGrid();')); ?>
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
                                <ul class="grid main-edit-meta-list list-view0" id="version-meta-sortable">
                                    <?php echo $this->versionChildMetas; ?>
                                </ul>
                            </div>
                        </div>    
                    </div>
                    <?php
                    } 
                    ?>
                    <div class="tab-pane" id="editmetatab_5">
                        <div class="form-group row fom-row">
                            <div class="col-md-12">
                                <?php
                                echo Form::textArea(
                                    array(
                                        'name' => 'DESCRIPTION',
                                        'id' => 'DESCRIPTION',
                                        'class' => 'form-control border-0 pl16 focus-border-grey',
                                        'rows' => 6,
                                        'placeholder' => $this->lang->line('META_00007'),
                                        'value' => $this->metaRow['DESCRIPTION']
                                    )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="editmetatab_2">
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
                    <div class="tab-pane" id="editmetatab_8">
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
                                    <?php echo $this->bugFixes; ?>
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
<div class="row form-actions">
    <div class="col-lg-8 ml-lg-auto">
        <div class="float-right">
        <?php
        if (!isset($this->checkMetaData)) {
            echo Form::button(
                array(
                    'class' => 'btn grey-cascade meta-btn-back',
                    'value' => $this->lang->line('back_btn'),
                    'onclick' => 'backEditFormMeta(this);'
                )
            );
        }

        $submitTypeName = '0';
        if (isset($this->checkMetaData) && isset($this->isBackBtn) && $this->isBackBtn == false) {
            $submitTypeName = '1';
        }

        echo Form::button(
            array(
                'class' => 'btn green-meadow ml10 bp-btn-save',
                'value' => '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'),
                'onclick' => 'updateMetaForm(this, '. $submitTypeName .', '. $this->metaDataId .');'
            )
        );
        ?>
        </div>
    </div>
</div>
<?php
}

echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); 
echo Form::hidden(array('name' => 'isChildMetaManage', 'value' => '0')); 
echo Form::hidden(array('name' => 'isMetaBugFixManage', 'value' => '0')); 

if (isset($this->checkMetaData)) {
    echo Form::hidden(array('name' => 'isActive', 'value' => '1'));
}

echo Form::close(); 
?>   

<script type="text/javascript">   
var tmpDmObject = [], tmpDmIdObject = {}, tmpDmEditIdObject = {};
$(function(){
    
    var $metaDataCode = $('input[name="metaDataCode"]');
    $metaDataCode.inputmask('Regex', {regex: '^[_A-Za-zА-Яа-яӨҮөү0-9-\+\\/\s|@$*]{1,100}$'});   
    
    $.contextMenu({
        selector: 'ul.grid li.meta-by-group',
        callback: function(key, opt) {
            if (key === 'gotoEditMeta') {
                window.open('mdmetadata/gotoEditMeta/' + opt.$trigger.attr('id'), '_blank');
            } else if (key === 'gotoFolder') {
                window.open('mdmetadata/gotoFolder/' + opt.$trigger.attr('id'), '_blank');
            } else if (key === 'delete') {
                $('input[name="isChildMetaManage"]').val('1');
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
                $('input[name="isMetaBugFixManage"]').val('1');
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
            $('input[name="isChildMetaManage"]').val('1');
        }
    });
    
    var $proxyMetaSortable = $('#proxy-meta-sortable');
    $proxyMetaSortable.sortable({
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
    
    var $versionMetaSortable = $('#version-meta-sortable');
    $versionMetaSortable.sortable({
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
    
    if ($.cookie) {
        if ($.cookie('system_single_meta') == '2')
            $groupMetaSortable.sortable("disable");
    }
       
    if ($.cookie) {
        if ($.cookie && ($.cookie('system_single_meta') === null || $.cookie('system_single_meta') == "" || $.cookie('system_single_meta') == undefined)) {
            var viewType = 0;
        } else {
            var viewType = $.cookie('system_single_meta');
        }
    } else {
        var viewType = 0;
    }
    
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
            $versionMetaSortable.sortable("disable");
        } else {
            $groupMetaSortable.sortable("enable");
            $versionMetaSortable.sortable("disable");
        }
    });  

    $('#group-meta-sortable-dm-table').on('click', 'input.dmOutput', function(){
        var $this = $(this);
        if ($this.is(':checked')) {
            $this.parent().find('input[name="dmOutput[]"]').val('1');
        } else {
            $this.parent().find('input[name="dmOutput[]"]').val('');
        }        
    });

    $('#group-meta-sortable-dm-table').on('click', 'input.dmGrouping', function(){
        var $this = $(this);
        if ($this.is(':checked')) {
            $this.parent().find('input[name="dmGrouping[]"]').val('1');
        } else {
            $this.parent().find('input[name="dmGrouping[]"]').val('');
        }
    });

    $('#group-meta-sortable-dm').on('click', 'input[type="checkbox"]', function(){
        var $this = $(this),
            dmPath = $this.closest('ul').find('li:eq(0)').attr('title')+'.'+$this.closest('li').attr('title');

        if ($this.is(':checked')) {
            $('#group-meta-sortable-dm-table').removeClass('hidden').find('tbody').append(
                '<tr data-key="'+dmPath+'" data-grouppath="'+$this.closest('ul').find('li:eq(0)').attr('title')+'">'+
                    '<td class="text-center">'+
                    '<input type="checkbox" class="dmOutput">'+
                    '<input type="hidden" name="dmOutput[]" value="" class="">'+
                    '<input type="hidden" name="dmPath[]" value="'+$this.data('fieldpath')+'">'+
                    '<input type="hidden" name="dmMetaGroup[]" value="'+$this.data('metagroup')+'">'+
                    '</td>'+
                    '<td style="width:350px"><input class="form-control form-control-sm" type="text" name="dmExpression[]" value="'+$this.closest('ul').find('li:eq(0)').attr('title')+'.'+$this.data('columnname')+'"></td>'+
                    '<td><select class="form-control form-control-sm" name="dmAggregate[]">'+
                        '<option></option>'+
                        '<option value="avg">Avg</option>'+
                        '<option value="count">Count</option>'+
                        '<option value="sum">Sum</option>'+
                        '<option value="group">Group</option>'+
                        '<option value="min">Min</option>'+
                        '<option value="max">Max</option>'+
                        '<option value="wm_concat">WM_CONCAT</option>'+
                        '</select></td>'+
                    '<td><input type="text" name="dmAs[]" class="form-control form-control-sm"></td>'+
                    '<td><select class="form-control form-control-sm" name="dmSortType[]">'+
                        '<option></option>'+
                        '<option value="asc">ASC</option>'+
                        '<option value="desc">DESC</option>'+
                        '</select></td>'+
                    '<td><input type="text" name="dmSortOrder[]" class="form-control form-control-sm"></td>'+
                    '<td class="text-center"><input type="checkbox" class="dmGrouping"><input type="hidden" name="dmGrouping[]" value=""></td>'+
                    '<td><a class="btn btn-success btn-circle btn-sm" title="" onclick="dmCriteriaDialog(this, \''+$this.data('metagroup')+'\')" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i></a><a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a></td>'+
                '</tr>'
            );
        } else {
            $('#group-meta-sortable-dm-table').removeClass('hidden').find('tbody').find('tr[data-key="'+dmPath+'"]').remove();
        }

        $('#group-meta-sortable-dm-table2').removeClass('hidden');
    });     

    $('#group-meta-sortable-dm-table2').on('change', 'select[name="dmSourceGroup[]"]', function(){
        var dmMapComboSub = '', dmtVal = $(this).find('option:selected').data('key');
        for (var dmi = 0; dmi < tmpDmObject[dmtVal].length; dmi++) {
            dmMapComboSub += '<option value="'+tmpDmObject[dmtVal][dmi].FIELD_PATH+'">'+tmpDmObject[dmtVal][dmi].LABEL_NAME+'</option>';
        }        
        $(this).closest('tr').find('select[name="dmSourceGroupPath[]"]').append(dmMapComboSub);
    });

    $('#group-meta-sortable-dm-table2').on('change', 'select[name="dmTargetGroup[]"]', function(){
        var dmMapComboSub = '', dmtVal = $(this).find('option:selected').data('key');
        for (var dmi = 0; dmi < tmpDmObject[dmtVal].length; dmi++) {
            dmMapComboSub += '<option value="'+tmpDmObject[dmtVal][dmi].FIELD_PATH+'">'+tmpDmObject[dmtVal][dmi].LABEL_NAME+'</option>';
        }        
        $(this).closest('tr').find('select[name="dmTargetGroupPath[]"]').append(dmMapComboSub);
    });
    
    $('#editMetaSystemForm').on('change', 'input, select, textarea', function(e) {
        var $form = $('#editMetaSystemForm');
        if (!$form.hasAttr('data-changed')) {
            if (e.originalEvent) {
                $form.attr('data-changed', 1);
                metaConfigChangeLog($form, true);
            } else {
                var name = $(this).attr('name');
                if (name == 'metaDataCode' || name == 'tableName') {
                    var $form = $('#editMetaSystemForm');
                    $form.attr('data-changed', 1);
                    metaConfigChangeLog($form, true);
                }
            }
        }
    });
    
    $('#editMetaSystemForm').on('keydown', 'input[type="text"], textarea', function(e) {
        if (e.originalEvent) {
            var $form = $('#editMetaSystemForm');
            if (!$form.hasAttr('data-changed')) {
                $form.attr('data-changed', 1);
                metaConfigChangeLog($form, true);
            }
        }
    });
    
    $('#editMetaSystemForm').on('remove', function() {
        metaConfigChangeLog($('#editMetaSystemForm'), false); 
    });
});   

window.onbeforeunload = function(e) {
    var $form = $('#editMetaSystemForm'); 
    if ($form.hasAttr('data-changed') && $form.attr('data-changed') == '1') {
        metaConfigChangeLog($form, false); 
        return 'Confirm';
    }
};

<?php if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$dmMetaTypeId) { ?>
    var dmDatas = <?php echo json_encode($this->metaDatas); ?>, relationHtml = '';
    if (dmDatas.map.length) {
        for (var dm = 0; dm < dmDatas.map.length; dm++) {
            renderDMmetas(dmDatas.map[dm], dmDatas.dmLink, dmDatas.criteria);
        }
    }

    if (dmDatas.relation.length) {
        for (var dm = 0; dm < dmDatas.relation.length; dm++) {
            var dmMapCombo = '';
            var dmMapComboSub = '', dmMapComboSub2 = '';

            for (key in tmpDmObject) {
                dmMapCombo += '<option data-key="'+key+'" '+(tmpDmIdObject[key] == dmDatas.relation[dm].SRC_META_GROUP_ID ? 'selected' : '')+' value="'+tmpDmIdObject[key]+'">'+key+'</option>';
                if (tmpDmIdObject[key] == dmDatas.relation[dm].SRC_META_GROUP_ID) {
                    for (var dmi = 0; dmi < tmpDmObject[key].length; dmi++) {
                        dmMapComboSub += '<option '+(tmpDmObject[key][dmi].FIELD_PATH == dmDatas.relation[dm].SRC_PARAM_PATH ? 'selected' : '')+' value="'+tmpDmObject[key][dmi].FIELD_PATH+'">'+tmpDmObject[key][dmi].LABEL_NAME+'</option>';
                    }               
                }               
            }
            var dmMapCombo2 = '';
            for (key in tmpDmObject) {
                dmMapCombo2 += '<option data-key="'+key+'" '+(tmpDmIdObject[key] == dmDatas.relation[dm].TRG_META_GROUP_ID ? 'selected' : '')+' value="'+tmpDmIdObject[key]+'">'+key+'</option>';
                if (tmpDmIdObject[key] == dmDatas.relation[dm].TRG_META_GROUP_ID) {
                    for (var dmi = 0; dmi < tmpDmObject[key].length; dmi++) {
                        dmMapComboSub2 += '<option '+(tmpDmObject[key][dmi].FIELD_PATH == dmDatas.relation[dm].TRG_PARAM_PATH ? 'selected' : '')+' value="'+tmpDmObject[key][dmi].FIELD_PATH+'">'+tmpDmObject[key][dmi].LABEL_NAME+'</option>';
                    }               
                }                    
            }     

            relationHtml += '<tr>'+
                '<td style="width:25%"><select class="form-control form-control-sm" name="dmSourceGroup[]"><option></option>'+dmMapCombo+'</select></td>'+
                '<td style="width:25%"><select class="form-control form-control-sm" name="dmSourceGroupPath[]">'+
                    '<option></option>'+dmMapComboSub+
                    '</select></td>'+
                '<td style="width:25%"><select class="form-control form-control-sm" name="dmTargetGroup[]"><option></option>'+dmMapCombo2+'</select></td>'+
                '<td style="width:25%"><select class="form-control form-control-sm" name="dmTargetGroupPath[]">'+
                    '<option></option>'+dmMapComboSub2+
                    '</select></td>'+
                '<td>'+
                    '<a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a>'+
                '</td>'+                    
            '</tr>';     
        }
        $('#group-meta-sortable-dm-table2').removeClass('hidden').find('tbody').append(relationHtml);
    }
<?php } ?>

function dmSourceTargetMap() {
    var dmMapCombo = '';
    for (key in tmpDmObject) {
        dmMapCombo += '<option data-key="'+key+'" value="'+tmpDmIdObject[key]+'">'+key+'</option>';
    }

    $('#group-meta-sortable-dm-table2').removeClass('hidden').find('tbody').append(
        '<tr>'+
            '<td style="width:25%"><select class="form-control form-control-sm" name="dmSourceGroup[]"><option></option>'+dmMapCombo+'</select></td>'+
            '<td style="width:25%"><select class="form-control form-control-sm" name="dmSourceGroupPath[]">'+
                '<option></option>'+
                '</select></td>'+
            '<td style="width:25%"><select class="form-control form-control-sm" name="dmTargetGroup[]"><option></option>'+dmMapCombo+'</select></td>'+
            '<td style="width:25%"><select class="form-control form-control-sm" name="dmTargetGroupPath[]">'+
                '<option></option>'+
                '</select></td>'+
            '<td>'+
                '<a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a>'+
            '</td>'+
        '</tr>'
    );    
}

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
    } else if (metaTypeId === '<?php echo Mdmetadata::$dmMetaTypeId ?>') {
        commonMetaDataGrid('multi', 'metaDM', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>');
        return;        
    }
    commonMetaDataGrid('multi', 'metaGroup', '');
    return;
} 
function proxyCommonMetaDataGrid(){
    commonMetaDataGrid('multi', 'metaProxy', '');
    return;
}
function statementCommonMetaDataGrid(){
    commonMetaDataGrid('multi', 'metaStatement', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$statementMetaTypeId; ?>');
    return;
}
function selectableCommonMetaDataGrid(chooseType, elem, params){
    if (elem === 'metaGroup') {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var message = '', isShowMessage = false;
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                $('ul#group-meta-sortable li').each(function() {
                    if ($(this).find("input[name='childMetaDataId[]']").val() == row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });
                
                if (isAddRow && '<?php echo $this->metaDataId; ?>' == row.META_DATA_ID && row.META_TYPE_ID !== '<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>') {
                    isAddRow = false;
                }
                
                if (isAddRow && (row.META_TYPE_ID === '<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>' || row.META_TYPE_ID === '<?php echo Mdmetadata::$menuMetaTypeId; ?>')) {
                    $.ajax({
                        type: 'post',
                        url: 'mdmeta/findParentMetaIdByMetaId',
                        data: {currentMetaId: '<?php echo $this->metaDataId; ?>', selectedMetaId: row.META_DATA_ID},
                        dataType: "json",
                        async: false,
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            if (data) {
                                message += row.META_DATA_NAME + ', ';
                                isShowMessage = true;
                                isAddRow = false;
                            } 
                            Core.unblockUI();
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
                
                if (isAddRow) {
                
                    var bigIcon = "assets/core/global/img/meta/file.png";
                    var smallIcon = "assets/core/global/img/meta/file-mini.png";
                    var secondOrderInput = '';
                    
                    if (row.META_ICON_NAME != '' && row.META_ICON_NAME != null && row.META_ICON_NAME != "null") {
                        bigIcon = "assets/core/global/img/metaicon/big/"+row.META_ICON_NAME;
                        smallIcon = "assets/core/global/img/metaicon/small/"+row.META_ICON_NAME;
                    } else if (row.META_TYPE_ID === '<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>') {   
                        bigIcon = "assets/core/global/img/meta/rar.png";
                        smallIcon = "assets/core/global/img/meta/rar-mini.png";
                        if (row.GROUP_TYPE === 'dataview') {
                            bigIcon = "assets/core/global/img/meta/dataview.png";
                            smallIcon = "assets/core/global/img/meta/dataview-mini.png";   
                        }                                      
                    }
                    
                    <?php
                    if ($this->metaRow['META_TYPE_ID'] == Mdmetadata::$statementMetaTypeId) {
                    ?>
                    secondOrderInput = '<input type="text" name="mapSecondOrderNum[]" class="only-detail-view-show" title="Бодолтын дараалал">';                        
                    <?php
                    }
                    ?>
                    
                    $('ul#group-meta-sortable').append(
                        '<li class="meta-by-group '+row.META_TYPE_CODE+'-type-code" id="'+row.META_DATA_ID+'">'+	
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
                                        '<h4 class="ellipsis">'+secondOrderInput+row.META_DATA_NAME+'</h4>'+
                                    '</div>'+
                                '</a>'+
                                '<div class="file-date"><span class="d-block">'+row.META_DATA_ID+'</span></div>'+
                                '<div class="file-code"><span class="d-block">'+row.META_DATA_CODE+'</span></div>'+
                                '<div class="file-user">'+row.META_TYPE_CODE+'</div>'+
                                '<input type="hidden" name="childMetaDataId[]" value="'+row.META_DATA_ID+'">'+
                            '</figure>'+
                        '</li>'); 
                    
                    $('input[name="isChildMetaManage"]').val('1');
                }
            }
            
            if (isShowMessage) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: '<strong>(' + message + ')</strong> дараах үзүүлэлтийг сонгох боломжгүй байна.',
                    type: 'warning',
                    sticker: false
                });
            }
        }
    } else if (elem === 'metaProxy') {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var message = '', isShowMessage = false;
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                $('ul#proxy-meta-sortable li').each(function() {
                    if ($(this).find("input[name='proxyChildMetaDataId[]']").val() === row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });
                
                if (isAddRow && '<?php echo $this->metaDataId; ?>' === row.META_DATA_ID) {
                    isAddRow = false;
                }
                
                if (isAddRow && (row.META_TYPE_ID === '<?php echo Mdmetadata::$metaGroupMetaTypeId;?>' || row.META_TYPE_ID === '<?php echo Mdmetadata::$menuMetaTypeId;?>')) {
                    $.ajax({
                        type: 'post',
                        url: 'mdmeta/findParentMetaIdByMetaId',
                        data: {currentMetaId: '<?php echo $this->metaDataId; ?>', selectedMetaId: row.META_DATA_ID},
                        dataType: "json",
                        async: false,
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            if (data) {
                                message += row.META_DATA_NAME + ', ';
                                isShowMessage = true;
                                isAddRow = false;
                            } 
                            Core.unblockUI();
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
                
                if (isAddRow) {
                
                    var bigIcon = "assets/core/global/img/meta/file.png";
                    var smallIcon = "assets/core/global/img/meta/file-mini.png";
                    
                    if (row.META_ICON_NAME != '' && row.META_ICON_NAME != null && row.META_ICON_NAME != "null") {
                        bigIcon = "assets/core/global/img/metaicon/big/"+row.META_ICON_NAME;
                        smallIcon = "assets/core/global/img/metaicon/small/"+row.META_ICON_NAME;
                    } else if(row.META_TYPE_ID === '<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>') {   
                        bigIcon = "assets/core/global/img/meta/rar.png";
                        smallIcon = "assets/core/global/img/meta/rar-mini.png";
                        if (row.GROUP_TYPE === 'dataview') {
                            bigIcon = "assets/core/global/img/meta/dataview.png";
                            smallIcon = "assets/core/global/img/meta/dataview-mini.png";   
                        }                                      
                    }
                    
                    var isDefaultCheckbox = '<label><input type="radio" name="isDefaultMap" value="'+row.META_DATA_ID+'"/> Дефаулт эсэх</label>';
                    
                    $('ul#proxy-meta-sortable').append(
                        '<li class="meta-by-group '+row.META_TYPE_CODE+'-type-code" id="'+row.META_DATA_ID+'">'+	
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
            
            if (isShowMessage) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: '<strong>(' + message + ')</strong> дараах үзүүлэлтийг сонгох боломжгүй байна.',
                    type: 'warning',
                    sticker: false
                });
            }
        }
        
    } else if (elem === 'metaStatement') {
    
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var message = '', isShowMessage = false;
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                $('ul#version-meta-sortable li').each(function() {
                    if ($(this).find("input[name='versionChildMetaDataId[]']").val() === row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });
                
                if (isAddRow && '<?php echo $this->metaDataId; ?>' === row.META_DATA_ID) {
                    isAddRow = false;
                }
                
                if (isAddRow) {
                    $.ajax({
                        type: 'post',
                        url: 'mdmeta/findParentMetaIdByMetaId',
                        data: {currentMetaId: '<?php echo $this->metaDataId; ?>', selectedMetaId: row.META_DATA_ID},
                        dataType: "json",
                        async: false,
                        beforeSend: function() {
                            Core.blockUI({message: 'Loading...', boxed: true});
                        },
                        success: function(data) {
                            if (data) {
                                message += row.META_DATA_NAME + ', ';
                                isShowMessage = true;
                                isAddRow = false;
                            } 
                            Core.unblockUI();
                        },
                        error: function() {
                            alert("Error");
                        }
                    });
                }
                
                if (isAddRow) {
                
                    var bigIcon = "assets/core/global/img/meta/file.png";
                    var smallIcon = "assets/core/global/img/meta/file-mini.png";
                    
                    if (row.META_ICON_NAME != '' && row.META_ICON_NAME != null && row.META_ICON_NAME != "null") {
                        bigIcon = "assets/core/global/img/metaicon/big/"+row.META_ICON_NAME;
                        smallIcon = "assets/core/global/img/metaicon/small/"+row.META_ICON_NAME;
                    } 
                    
                    $('ul#version-meta-sortable').append(
                        '<li class="meta-by-group '+row.META_TYPE_CODE+'-type-code" id="'+row.META_DATA_ID+'">'+	
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
                                '<input type="hidden" name="versionChildMetaDataId[]" value="'+row.META_DATA_ID+'">'+
                            '</figure>'+
                        '</li>');
                
                    Core.initUniform($('ul#version-meta-sortable').find('li.meta-by-group:last'));  
                }
            }
            
            if (isShowMessage) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Анхааруулга',
                    text: '<strong>(' + message + ')</strong> дараах үзүүлэлтийг сонгох боломжгүй байна.',
                    type: 'warning',
                    sticker: false
                });
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
    } else if (elem === 'metaDM') {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var message = '', isShowMessage = false;
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                $('div#group-meta-sortable-dm ul').each(function() {
                    if ($(this).find("input[name='childMetaDataId[]']").val() == row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });
                
                if (isAddRow) {
                    renderDMmetas(row);
                }
            }
        }
    }
}  
function renderDMmetas(row, link, criteria) {
    var groupChildMetas = [];
    $.ajax({
        type: 'post',
        url: 'mdmetadata/getGroupChildDM',
        data: {groupMetaDataId: row.META_DATA_ID},
        dataType: "json",
        async: false,
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            if (data) {
                groupChildMetas = data;
            } 
            Core.unblockUI();
        },
        error: function() { alert("Error"); }
    });

    var groupChildHtml = '<li class="title" title="'+row.META_DATA_CODE+'"><a class="" title="Устгах" onclick="dmGroupRemove(this)" href="javascript:;"><i class="fa fa-trash" style="color:red"></i></a> '+row.META_DATA_CODE+'</li>';
    tmpDmObject[row.META_DATA_CODE] = [];
    tmpDmIdObject[row.META_DATA_CODE] = row.META_DATA_ID;

    var dt = Date.now();
    if (typeof link !== 'undefined') {
        var editDmDataLen = link.length, edm = 0, dmChecked = '', dmEditHtml = '';
        
        for (var ii = 0; ii < groupChildMetas.length; ii++) {
            dmChecked = '';
            edm = 0;
            for (edm; edm < editDmDataLen; edm++) {
                if (row.META_DATA_ID == link[edm].META_GROUP_ID && groupChildMetas[ii].FIELD_PATH == link[edm].SRC_PARAM_PATH) {
                    var dmPath = row.META_DATA_CODE+'.'+groupChildMetas[ii].LABEL_NAME;
                    dmChecked = 'checked';
                    dmEditHtml += '<tr data-key="'+dmPath+'" data-grouppath="'+row.META_DATA_CODE+'">'+
                        '<td class="text-center">'+
                        '<input type="checkbox" class="dmOutput notuniform" '+(link[edm].IS_OUTPUT == '1' ? 'checked' : '')+'>'+
                        '<input type="hidden" name="dmOutput[]" value="'+link[edm].IS_OUTPUT+'" class="">'+
                        '<input type="hidden" name="dmPath[]" value="'+link[edm].SRC_PARAM_PATH+'">'+
                        '<input type="hidden" name="dmMetaGroup[]" value="'+row.META_DATA_ID+'">';
                        if (typeof criteria[link[edm].ID] !== 'undefined') {
                            for (var cr = 0; cr < criteria[link[edm].ID].rows.length; cr++) {
                                dmEditHtml += '<input type="hidden" value="'+criteria[link[edm].ID].rows[cr].CRITERIA+'" name="dmCriteria'+row.META_DATA_ID+link[edm].SRC_PARAM_PATH+'[]" placeholder="" class="dm-criteria-input">';    
                            }
                        }                        
                        dmEditHtml += '</td>'+
                        '<td style="width:350px"><input type="text" class="form-control form-control-sm" name="dmExpression[]" value="'+link[edm].EXPRESSION+'"></td>'+
                        '<td><select class="form-control form-control-sm" name="dmAggregate[]">'+
                            '<option></option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'avg' ? 'selected' : '')+' value="avg">Avg</option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'count' ? 'selected' : '')+' value="count">Count</option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'sum' ? 'selected' : '')+' value="sum">Sum</option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'group' ? 'selected' : '')+' value="group">Group</option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'min' ? 'selected' : '')+' value="min">Min</option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'max' ? 'selected' : '')+' value="max">Max</option>'+
                            '<option '+(link[edm].AGGREGATE_FUNCTION == 'wm_concat' ? 'selected' : '')+' value="wm_concat">WM_CONCAT</option>'+
                            '</select>'+
                        '</td>'+
                        '<td><input type="text" name="dmAs[]" class="form-control form-control-sm" value="'+(link[edm].ALIAS_NAME ? link[edm].ALIAS_NAME : '')+'"></td>'+
                        '<td><select class="form-control form-control-sm" name="dmSortType[]">'+
                            '<option></option>'+
                            '<option '+(link[edm].SORT_TYPE == 'asc' ? 'selected' : '')+' value="asc">ASC</option>'+
                            '<option '+(link[edm].SORT_TYPE == 'desc' ? 'selected' : '')+' value="desc">DESC</option>'+
                            '</select></td>'+
                        '<td><input type="text" name="dmSortOrder[]" value="'+(link[edm].SORT_ORDER ? link[edm].SORT_ORDER : '')+'" class="form-control form-control-sm"></td>'+
                        '<td class="text-center"><input type="checkbox" '+(link[edm].IS_GROUP == '1' ? 'checked' : '')+' class="dmGrouping notuniform"><input type="hidden" name="dmGrouping[]" value="'+link[edm].IS_GROUP+'"></td>'+
                        '<td><a class="btn btn-success btn-circle btn-sm" title="" onclick="dmCriteriaDialog(this, \''+row.META_DATA_ID+'\')" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i></a><a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a></td>'+
                    '</tr>'; 

                }
            }
            tmpDmObject[row.META_DATA_CODE].push(groupChildMetas[ii]);
            groupChildHtml += '<li title="'+groupChildMetas[ii].LABEL_NAME+'"><input type="checkbox" '+dmChecked+' class="notuniform" data-columnname="'+groupChildMetas[ii].COLUMN_NAME+'" data-metagroup="'+row.META_DATA_ID+'" data-fieldpath="'+groupChildMetas[ii].FIELD_PATH+'" id="meta-dm-check'+dt+'-'+ii+'"/><label for="meta-dm-check'+dt+'-'+ii+'">'+groupChildMetas[ii].LABEL_NAME+'</label></li>';
        }

        edm = 0;
        for(edm; edm < editDmDataLen; edm++) {
            if (link[edm].META_GROUP_ID == null && typeof tmpDmEditIdObject[link[edm].ID] === 'undefined') {
                tmpDmEditIdObject[link[edm].ID] = true;
                dmEditHtml += '<tr data-key="" data-grouppath="">'+
                    '<td class="text-center">'+
                    '<input type="checkbox" class="dmOutput notuniform" '+(link[edm].IS_OUTPUT == '1' ? 'checked' : '')+'>'+
                    '<input type="hidden" name="dmOutput[]" value="'+link[edm].IS_OUTPUT+'" class="">'+
                    '<input type="hidden" name="dmPath[]" value="'+link[edm].SRC_PARAM_PATH+'">'+
                    '<input type="hidden" name="dmMetaGroup[]" value="'+row.META_DATA_ID+'">';
                    // if (typeof criteria[link[edm].ID] !== 'undefined') {
                    //     for (var cr = 0; cr < criteria[link[edm].ID].rows.length; cr++) {
                    //         dmEditHtml += '<input type="hidden" value="'+criteria[link[edm].ID].rows[cr].CRITERIA+'" name="dmCriteria[]" placeholder="" class="dm-criteria-input">';    
                    //     }
                    // }                        
                    dmEditHtml += '</td>'+
                    '<td style="width:350px"><input type="text" class="form-control form-control-sm" name="dmExpression[]" value="'+link[edm].EXPRESSION+'"></td>'+
                    '<td><select class="form-control form-control-sm" name="dmAggregate[]">'+
                        '<option></option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'avg' ? 'selected' : '')+' value="avg">Avg</option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'count' ? 'selected' : '')+' value="count">Count</option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'sum' ? 'selected' : '')+' value="sum">Sum</option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'group' ? 'selected' : '')+' value="group">Group</option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'min' ? 'selected' : '')+' value="min">Min</option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'max' ? 'selected' : '')+' value="max">Max</option>'+
                        '<option '+(link[edm].AGGREGATE_FUNCTION == 'wm_concat' ? 'selected' : '')+' value="wm_concat">WM_CONCAT</option>'+
                        '</select>'+
                    '</td>'+
                    '<td><input type="text" name="dmAs[]" class="form-control form-control-sm" value="'+(link[edm].ALIAS_NAME ? link[edm].ALIAS_NAME : '')+'"></td>'+
                    '<td><select class="form-control form-control-sm" name="dmSortType[]">'+
                        '<option></option>'+
                        '<option '+(link[edm].SORT_TYPE == 'asc' ? 'selected' : '')+' value="asc">ASC</option>'+
                        '<option '+(link[edm].SORT_TYPE == 'desc' ? 'selected' : '')+' value="desc">DESC</option>'+
                        '</select></td>'+
                    '<td><input type="text" name="dmSortOrder[]" value="'+(link[edm].SORT_ORDER ? link[edm].SORT_ORDER : '')+'" class="form-control form-control-sm"></td>'+
                    '<td class="text-center"><input type="checkbox" '+(link[edm].IS_GROUP == '1' ? 'checked' : '')+' class="dmGrouping notuniform"><input type="hidden" name="dmGrouping[]" value="'+link[edm].IS_GROUP+'"></td>'+
                    '<td><a class="btn btn-success btn-circle btn-sm" title="" onclick="dmCriteriaDialog(this, \'\')" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i></a><a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a></td>'+
                '</tr>'; 

            }
        }        

        $('#group-meta-sortable-dm-table').removeClass('hidden').find('tbody').append(dmEditHtml);

    } else {
        for(var ii = 0; ii < groupChildMetas.length; ii++) {
            tmpDmObject[row.META_DATA_CODE].push(groupChildMetas[ii]);
            groupChildHtml += '<li title="'+groupChildMetas[ii].LABEL_NAME+'"><input type="checkbox" class="notuniform" data-columnname="'+groupChildMetas[ii].COLUMN_NAME+'" data-metagroup="'+row.META_DATA_ID+'" data-fieldpath="'+groupChildMetas[ii].FIELD_PATH+'" id="meta-dm-check'+dt+'-'+ii+'"/><label for="meta-dm-check'+dt+'-'+ii+'">'+groupChildMetas[ii].LABEL_NAME+'</label></li>';
        }
    }
    
    $('div#group-meta-sortable-dm').append(
        '<ul class="ml30" id="'+row.META_DATA_ID+'"><input type="hidden" name="childMetaDataId[]" value="'+row.META_DATA_ID+'">'+
            groupChildHtml
        +'</ul>'
    ); 
    $('input[name="isChildMetaManage"]').val('1');
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
                $cell.find('input[name="isFolderManage"]').val('1');
            }
        }
    }        
}
function metaTagSelectable(elem) {
    dataViewSelectableGrid('nullmeta', '0', '1502070138160027', 'multi', 'nullmeta', elem, 'chooseMetaTags');
}
function chooseMetaTags(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    var $cell = $(elem).closest('td');
    var $parent = $cell.find('.meta-folder-tags');
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
            $cell.find('input[name="isTagsManage"]').val('1');
        }
    }
}
function dmCriteriaDialog(elem, gid) {    
    var $thistr = $(elem);
    var dmSavedClone = $thistr.closest('tr').find('td:eq(0)').find('input.dm-criteria-input');
    var $dialogname = 'dialog-dm-mart-criteria', dmSavedCriteria = '';

    if (dmSavedClone.length) {
        dmSavedClone.each(function(){
            dmSavedCriteria += '<tr>';
            dmSavedCriteria += '<td><input type="text" class="form-control form-control-sm" name="'+$(this).attr('name')+'" value="'+$(this).val()+'"></td>';
            dmSavedCriteria += '<td><a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a></td>';
            dmSavedCriteria += '</tr>';
        });
    }

    var data = '<div><a class="btn btn-success btn-circle btn-sm" title="" onclick="dmCriteriaAddRow(this, \''+$thistr.closest('tr').data('key')+'\', \''+gid+'\', \''+$thistr.closest('tr').find('input[name="dmPath[]"]').val()+'\')" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i> Нэмэх</a><table class="table table-sm table-hover mt10 mb10 dm-criteria-table">'+
    '<thead>'+
    '<tr><td>Criteria</td></tr>'+
    '</thead><tbody>'+dmSavedCriteria+'</tbody></table>'+
    '</div>';

    if (!$('#'+$dialogname).length) {
        $('<div id="' + $dialogname + '"></div>').appendTo('body');
    }
    var dialogname = $('#'+$dialogname);

    dialogname.empty().html(data);
    dialogname.dialog({
        appendTo: 'body',
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: $thistr.closest('tr').data('key'),
        width: 550,
        height: 'auto',
        "max-height": 450,
        modal: true,
        open: function () {   
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
            $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn blue-hoki btn-sm ml5');
        },
        close: function (elem) {
            dialogname.dialog('close');
        },
        buttons: [
            {text: plang.get('save_btn'), click: function () {
                $thistr.closest('tr').find('td:eq(0)').find('input.dm-criteria-input').remove();
                if (dialogname.find('tbody > tr').length) {
                    var inputHtml = '';
                    dialogname.find('tbody > tr').each(function(){
                        inputHtml += '<input type="hidden" class="dm-criteria-input" name="'+$(this).find('input').attr('name')+'" value="'+$(this).find('input').val()+'">';
                    });
                    $thistr.closest('tr').find('td:eq(0)').append(inputHtml);
                }
                dialogname.dialog('close');
            }}
        ]
    });
    dialogname.dialog('open');    
}
function dmCriteriaAddRow(elem, key, gid, path) {
    var $thistr = $(elem);
    $thistr.parent().find('tbody').append('<tr data-key="'+key+'" class="dmcriteria">'+
            '<td><input type="text" name="dmCriteria'+gid+path+'[]" placeholder="" class="form-control form-control-sm"></td>'+
            '<td><a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a></td>'+
        '</tr>'        
    );
}
function dmCriteriaRemoveRow(elem) {
    $(elem).closest('tr').remove();
}
function dmGroupRemove(elem) {
    var getPath = $(elem).closest('li').attr('title');
    $(elem).closest('ul').remove();

    $('#group-meta-sortable-dm-table').find('tbody > tr').each(function(){
        if($(this).attr('data-grouppath') == getPath) {
            $(this).remove();
        }
    });
}
function dmSourceSrcMap() {
    $('#group-meta-sortable-dm-table').removeClass('hidden').find('tbody').append(
        '<tr data-key="" data-grouppath="">'+
            '<td class="text-center">'+
            '<input type="checkbox" class="dmOutput">'+
            '<input type="hidden" name="dmOutput[]" value="" class="">'+
            '<input type="hidden" name="dmPath[]" value="">'+
            '<input type="hidden" name="dmMetaGroup[]" value="">'+
            '</td>'+
            '<td style="width:350px"><input class="form-control form-control-sm" type="text" name="dmExpression[]" value=""></td>'+
            '<td><select class="form-control form-control-sm" name="dmAggregate[]">'+
                '<option></option>'+
                '<option value="avg">Avg</option>'+
                '<option value="count">Count</option>'+
                '<option value="sum">Sum</option>'+
                '<option value="group">Group</option>'+
                '<option value="min">Min</option>'+
                '<option value="max">Max</option>'+
                '<option value="wm_concat">WM_CONCAT</option>'+
                '</select></td>'+
            '<td><input type="text" name="dmAs[]" class="form-control form-control-sm"></td>'+
            '<td><select class="form-control form-control-sm" name="dmSortType[]">'+
                '<option></option>'+
                '<option value="asc">ASC</option>'+
                '<option value="desc">DESC</option>'+
                '</select></td>'+
            '<td><input type="text" name="dmSortOrder[]" class="form-control form-control-sm"></td>'+
            '<td class="text-center"><input type="checkbox" class="dmGrouping"><input type="hidden" name="dmGrouping[]" value=""></td>'+
            '<td><a class="btn btn-success btn-circle btn-sm" title="" onclick="dmCriteriaDialog(this, \'\')" href="javascript:;"><i class="icon-plus3 font-size-12" style="color:"></i></a><a class="btn btn-danger btn-circle btn-sm" title="" onclick="dmCriteriaRemoveRow(this)" href="javascript:;"><i class="fa fa-trash" style="color:"></i></a></td>'+
        '</tr>'
    );
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

            $('input[name="isMetaBugFixManage"]').val('1');
        }
    }
}
</script>