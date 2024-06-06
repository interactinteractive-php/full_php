<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <form id="wfmuser-metadata-search-form" method="post">
                <?php echo $this->searchForm; ?> 
            </form>
        </div>
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-metadata-tab-order" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00062'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#common-metadata-tab-basket" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> ( <span id="commonMetaSelectedCount" class="dv-basket-count">0</span> )</a>
                </li>
            </ul>
            <div class="tab-content pb0 jeasyuiTheme3">
                <div class="tab-pane active in wfmuserMetaDataGrid" id="common-metadata-tab-order">
                    <table id="wfmuserMetaDataGrid" style="width: 1070px; height: 380px"></table>
                </div>
                <div class="tab-pane in" id="common-metadata-tab-basket">
                    <table id="wfmUserBasketMetaDataGrid"></table>
                </div>
            </div>
        </div>    
    </div>
</div>

<style type="text/css">
#common-metadata-folder-view {
    overflow: auto;
    height: 380px !important;
}   
#wfmuser-metadata-search-form .form-group {
    margin-bottom: 5px !important;
}
#wfmuser-metadata-search-form label {
    font-size: 12px !important;
}
#wfmuser-metadata-search-form .form-actions {
    margin-top: 20px !important;
}
#wfmuser-metadata-search-form .form-body {
    overflow: auto;
    max-height: 300px !important;
}
#common-metadata-tab-order *::-moz-selection, #common-metadata-tab-basket *::-moz-selection { background:transparent; }
#common-metadata-tab-order *::selection, #common-metadata-tab-basket *::selection { background:transparent; }
</style>

<script type="text/javascript">
$(function(){
    
    // $("#wfmuser-metadata-search-form").on("change", 'select', function(e){
    // //     if (e.which === 13) {
    // //     }
    //     if ($(this).attr('name') === 'param[wfmRuleId]') {
    //         wfmUserMetaDataGridSearch($(this));
    //     }
    // });
    
    $('#wfmuserMetaDataGrid').datagrid({
        url: 'mdmetadata/commonSelectableDataGrid',
        <?php echo $this->searchParams; ?>
        rownumbers: true,
        singleSelect: <?php echo $this->singleSelect; ?>,
        ctrlSelect: true,
        pagination: true,
        pageSize: 30,
        fitColumn: true,
        nowrap: false,
        remoteFilter: true,
        filterDelay: 10000000000, 
        /*frozenColumns:[[
            {field:'ck', checkbox:true}, 
            {field:'picture',title:'Зураг',sortable:true,fixed: true,width: '45px',halign: 'left',align: 'center',formatter: gridFileField},
            {field:'username',title:"<?php echo $this->lang->line('wf_user_name') ?>",sortable:true,width:185},
        ]],
        columns:[[
            {field:'userfullname',title:"<?php echo $this->lang->line('Хэрэглэгчийн овог, нэр') ?>",sortable:true,width:215},
            {field:'departmentcode',title:"<?php echo $this->lang->line('MET_331194') ?>",sortable:true,width:105},
            {field:'departmentname',title:"<?php echo $this->lang->line('MET_331194') ?>",sortable:true,width:105},
            {field:'positionname',title:"<?php echo $this->lang->line('Албан тушаал') ?>",sortable:true,width:124}, 
            {field:'createddate',title:"<?php echo $this->lang->line('opportunity_basket_created_date'); ?>",sortable:true,width:110,align:'center',formatter: function(v, r, i) {return dateFormatter('Y-m-d', v);}},
            {field:'createdusername',title:"<?php echo $this->lang->line('segment-created-customer'); ?>",sortable:true,width:180}
        ]],*/
        frozenColumns:<?php echo ((isset($this->dataGridHeader['freeze'])) ? str_replace(array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},"), '', $this->dataGridHeader['freeze']) : ''); ?>,        
        columns:<?php echo ((isset($this->dataGridHeader['header'])) ? str_replace(array("{field:'action', rowspan:2, title:'', sortable:false, width:40, align:'center'},", "{field:'action',  title:'', sortable:false, width:40, align:'center'},"), '', $this->dataGridHeader['header']) : ''); ?>,
        onDblClickRow:function(index, row){
            dblClickWfmUserMetaDataGrid(index, row);
        },
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.contextMenu({
                selector: "#common-metadata-tab-order .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, #common-metadata-tab-order .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'basket') {
                        basketWfmUserMetaDataGrid();
                    }
                },
                items: {
                    "basket": {name: "<?php echo $this->lang->line('META_00042'); ?>", icon: "plus-circle"}
                }
            });
        },
        onLoadSuccess:function(){
            showGridMessage($(this));
            setTimeout(function(){
                if ($(".multiple_filter_values", "#wfmuser-metadata-search-form").length) {
                    $(".multiple_filter_values", "#wfmuser-metadata-search-form").each(function(){
                        $('.wfmuserMetaDataGrid .datagrid-htable .datagrid-filter-c').find('input[name="'+$(this).attr('data-field')+'"]').css('width', $('.wfmuserMetaDataGrid .datagrid-htable .datagrid-filter-c').find('input[name="'+$(this).attr('data-field')+'"]').outerWidth() - 15 + 'px');
                    })
                }                
            }, 0);            
        }
    });
    $('#wfmuserMetaDataGrid').datagrid('enableFilter');
    $('#wfmUserBasketMetaDataGrid').datagrid({
        url:'',
        rownumbers:true,
        singleSelect:true,
        pagination:false,
        remoteSort:false,
        width:1070,
        height:380,
        fitColumn:true,
        showFooter:false,
        autoRowHeight: true,
        /*frozenColumns:[[
            {field:'action', title:'', sortable:false, width:40, align:'center'},
            {field:'username',title:"<?php echo $this->lang->line('wf_user_name') ?>",sortable:true,width:185}
        ]],
        columns:[[
            {field:'userfullname',title:"<?php echo $this->lang->line('Хэрэглэгчийн овог, нэр') ?>",sortable:true,width:215},
            {field:'departmentcode',title:"<?php echo $this->lang->line('MET_331194') ?>",sortable:true,width:105},
            {field:'departmentname',title:"<?php echo $this->lang->line('MET_331194') ?>",sortable:true,width:105},
            {field:'positionname',title:"<?php echo $this->lang->line('Албан тушаал') ?>",sortable:true,width:124}, 
            {field:'createddate',title:"<?php echo $this->lang->line('opportunity_basket_created_date'); ?>",sortable:true,width:110,align:'center',formatter: function(v, r, i) {return dateFormatter('Y-m-d', v);}},
            {field:'createdusername',title:"<?php echo $this->lang->line('segment-created-customer'); ?>",sortable:true,width:180}
        ]]*/
        frozenColumns:<?php echo isset($this->dataGridHeader['freeze']) ? $this->dataGridHeader['freeze'] : ''; ?>, 
        columns:<?php echo isset($this->dataGridHeader['header']) ? $this->dataGridHeader['header'] : ''; ?>
    });
    $('#wfmUserBasketMetaDataGrid').datagrid('loadData', []);
    $("a[href=#common-metadata-tab-basket]").on("shown.bs.tab", function(){      
        $('#wfmUserBasketMetaDataGrid').datagrid('resize');
        $('#wfmUserBasketMetaDataGrid').datagrid('fixRowHeight');
    });

    var timerFilterHover;
    $(document.body).on('mouseenter', '.wfmuserMetaDataGrid .datagrid-htable .datagrid-filter-c', function() {    
        var $this = $(this);
        timerFilterHover = setTimeout(function() {
            if (!$this.find('.dataview-multivalue-filter').length) {
                $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() - 15 + 'px');
                $this.append('<a href="javascript:;" title="Олон утгаар хайх" class="dataview-multivalue-filter"><i class="icon-filter4"></i></a>');
            }
        }, 100);
    });

    $(document.body).on('mouseleave', '.wfmuserMetaDataGrid .datagrid-htable .datagrid-filter-c', function(e) {
        var $this = $(this);
        if (timerFilterHover) {
            clearTimeout(timerFilterHover);
            if ($this.find('.dataview-multivalue-filter').length && !$this.hasClass('dataview-multivalue-filter-sticky')) {
                $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() + 15 + 'px');
                $this.find('.dataview-multivalue-filter').remove();
            }
        }
    });         
    
    $('.wfmuserMetaDataGrid').on('click', '.datagrid-htable .datagrid-filter-c a.dataview-multivalue-filter', function(e){
        var $target = $(this).closest('.datagrid-filter-c');
        dvMultiValueFilter_wfm($target);
    });    
});

function dvMultiValueFilter_wfm(elem) {
        var $fieldName = elem.closest('td').attr('field'), $this = elem;
        var dialogname = $('#dialog-multiple-filter_wfm' + '_' + $fieldName);
        var $dialogname = 'dialog-multiple-filter_wfm' + '_' + $fieldName;
        var data = '';
        data = '<div class="col-md-12">'+
            '<input type="text" name="dvMultipleFilterString" class="form-control form-control-sm" placeholder="Хайх">'+
        '</div><div class="clearfix"></div>'+
        '<div class="salary-aggregate-function-datas mt15 col-md-12"></div>';
        
        Core.blockUI({
            boxed: true
        });
        var $dataGrid = $('#wfmuserMetaDataGrid'), 
            $op = $dataGrid.datagrid('options');

        if (!dialogname.length) {
            $('<div id="' + $dialogname + '"></div>').appendTo('body');
        }
        dialogname = $('#dialog-multiple-filter_wfm' + '_' + $fieldName);
        var filterBtns = [
            {text: plang.get('search_btn'), click: function () {
                var multiInputVal = '';
                dialogname.find('table > tbody').find('input[type="checkbox"]').each(function(){
                    var _thisV = $(this), filterVal2 = {};
                    if(_thisV.is(':checked')) {
                        multiInputVal += '<input type="hidden" name="param['+$fieldName+'][]" value="'+_thisV.val()+'"/>';
                    }
                });                        
                if (!$('#multiple_filter_values_wfm' + '_' + $fieldName).length && multiInputVal) {
                    $('<div class="multiple_filter_values" data-field="' + $fieldName + '" data-dialog-id="dialog-multiple-filter_wfm' + '_' + $fieldName + '" id="multiple_filter_values_wfm' + '_' + $fieldName + '"></div>').appendTo($("#wfmuser-metadata-search-form"));
                }
                if (multiInputVal) {

                    if (!$this.find('.dataview-multivalue-filter').length) {
                        $this.addClass('dataview-multivalue-filter-sticky');
                        $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() - 15 + 'px');
                        $this.append('<a href="javascript:;" title="Олон утгаар хайх" class="dataview-multivalue-filter"><i class="icon-filter3"></i></a>');
                    }                            

                    $('#multiple_filter_values_wfm' + '_' + $fieldName).empty().append(multiInputVal);

                    var dvMultiFilterCriteria = '';
                    if ($("#wfmuser-metadata-search-form").length) {
                        dvMultiFilterCriteria = $("#wfmuser-metadata-search-form").find('input,select').serialize();
                    }
                    wfmUserMetaDataGridSearch(dvMultiFilterCriteria);        
                    dialogname.dialog('close');
                } else {
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').trigger('click');
                }                
            }},
            {text: plang.get('clear_btn'), click: function () {
                if ($this.find('.dataview-multivalue-filter').length) {
                    $this.removeClass('dataview-multivalue-filter-sticky');
                    $this.find('input.datagrid-filter').css('width', $this.find('input.datagrid-filter').outerWidth() + 15 + 'px');
                    $this.find('.dataview-multivalue-filter').remove();
                }                        
                if ($('#multiple_filter_values_wfm' + '_' + $fieldName).length) {
                    $('#multiple_filter_values_wfm' + '_' + $fieldName).remove();

                    var dvMultiFilterCriteria = '';
                    if ($("#wfmuser-metadata-search-form").length) {
                        dvMultiFilterCriteria = $("#wfmuser-metadata-search-form").find('input, select').serialize();
                    }   
                    wfmUserMetaDataGridSearch(dvMultiFilterCriteria);            
                }
                dialogname.dialog('close');
                dialogname.empty().dialog('destroy').remove();
            }},                    
            {text: plang.get('close_btn'), click: function () {
                dialogname.dialog('close');
            }}
        ];

        if (dialogname.children().length > 0) {
            dialogname.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Олон утгаар хайх',
                width: 380,
                height: 'auto',
                modal: true,
                open: function () {              
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm green-meadow mr0');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm');
                },
                close: function () {
                    dialogname.dialog('close');
                },
                buttons: filterBtns
            });
            setTimeout(function() {Core.unblockUI()});
            dialogname.dialog('open');
            dialogname.on('keyup', 'input[name="dvMultipleFilterString"]', function(){
                var $self = $(this);
                $self.closest('.ui-dialog-content').find('table > tbody > tr').each(function(){
                    if ($self.val() == '') {
                        $(this).show();
                    } else {
                        if ($(this).find('label').text().toLowerCase().search($self.val().toLowerCase()) === -1) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    }
                });
            });            
        } else {

            var defaultCriteriaData = 'param[wfmRuleId]='+$('#wfmuser-metadata-search-form').find('select[name="param[wfmRuleId]"]').val();            
            var dvSearchParam = {
                metaDataId: '1487153693627',
                defaultCriteriaData: defaultCriteriaData, 
                workSpaceId: '=', 
                workSpaceParams: '', 
                uriParams: '', 
                treeConfigs: '', 
                filterRules: '', 
                ignorePermission: '', 
                subQueryId: '', 
                ignoreFirstLoad: false,
                filterColumn: $fieldName
            };                  
            dialogname.empty().html(data);         
            $.ajax({
                type: 'post',
                url: 'mdobject/dataViewDataGrid',
                data: dvSearchParam,
                async: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (resp) {
                    if(resp.status === 'success') {
                        var filterHtml = '<div style="border-bottom: 1px solid #bababa;"><input type="checkbox" value="" name="dv_multifilter_select_all" id="dv_multifilter_select_all"/> <label for="dv_multifilter_select_all">' + plang.get('select_all') + '</label></div>';
                        filterHtml += '<div style="overflow-y: auto; max-height: 280px;">';
                        filterHtml += '<table class="table table-sm table-bordered table-hover bprocess-table-dtl mb0"><tbody>';
                        $.each(resp.rows[$fieldName], function(k, v){
                            if (v == null || v == '') {
                                filterHtml += '<tr><td><input type="checkbox" value="@empty@" id="filter_"/> <label for="filter_">Хоосон мөр</label></td></tr>';
                            } else {
                                filterHtml += '<tr><td><input type="checkbox" value="' + v + '" id="filter_' + v + '"/> <label for="filter_' + v + '">' + v + '</label></td></tr>';
                            }
                        });
                        filterHtml += '</tbody></table></div>';
                        $('.salary-aggregate-function-datas', '#'+$dialogname).empty().append(filterHtml);
                        //dialogname.find('input[type="checkbox"]').uniform();
                        
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: 'Анхааруулга',
                            text: resp.message,
                            type: 'warning',
                            sticker: false
                        });           
                    }
                    setTimeout(function() {Core.unblockUI()});
                }
            });        
            
            dialogname.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Олон утгаар хайх',
                width: 380,
                height: 'auto',
                modal: true,
                open: function () {
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').addClass("btn-group float-right");
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(0)').addClass('btn btn-sm green-meadow mr0');
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(1)').addClass('btn blue-hoki btn-sm ml5');                                                                                      
                    $('div[aria-describedby=' + $dialogname + '] .ui-dialog-buttonset').find('button:eq(2)').addClass('btn blue-hoki btn-sm');                
                },
                close: function () {
                    dialogname.dialog('close');
                },
                buttons: filterBtns
            });
            dialogname.dialog('open');
            dialogname.on('keyup', 'input[name="dvMultipleFilterString"]', function(){
                var $self = $(this);
                $self.closest('.ui-dialog-content').find('table > tbody > tr').each(function(k, v){
                    if (k === 0) return;

                    if ($self.val() == '') {
                        $(this).show();
                    } else {
                        if ($(this).find('label').text().toLowerCase().search($self.val().toLowerCase()) === -1) {
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    }
                });
            });
            dialogname.on('click', '#dv_multifilter_select_all', function(){
                var $self = $(this);
                $self.closest('.ui-dialog-content').find('table > tbody > tr').each(function(){
                    if($(this).is(':visible')) {
                        if ($self.is(':checked')) {
                            $(this).find('input[type="checkbox"]').prop('checked', true);
                        } else {
                            $(this).find('input[type="checkbox"]').prop('checked', false);
                        }
                    }
                });
            });
        }
    }    

function wfmUserMetaDataGridSearch(elem){
    $('#wfmuserMetaDataGrid').datagrid('load', {
        metaDataId: '1487153693627',
        defaultCriteriaData: elem
    });
}
function commonMetaDataGridReset(){
    $("#wfmuser-metadata-search-form").find("input[type=text], select").val("");
    $("#wfmuser-metadata-search-form").find("select.select2").select2("val", "");
    $('#wfmuserMetaDataGrid').datagrid('load',{});
}
    
function commonMetaDataFolderFilter(folderId){
    $('a[href="#common-metadata-tab-order"]').tab('show');
    $("#wfmuser-metadata-search-form").find("input[type=text], select").val("");
    $("#wfmuser-metadata-search-form").find("select.select2").select2("val", "");
    $('#wfmuserMetaDataGrid').datagrid('load', {
        folderId: folderId,
        <?php echo $this->defaultCriteria; ?>
    });
    $("#wfmuser-metadata-search-form #folderId").val(folderId);
}
function basketWfmUserMetaDataGrid(){
    var rows = $('#wfmuserMetaDataGrid').datagrid('getSelections');
    <?php
    if ($this->chooseType == 'single') {
        echo 'if (rows.length > 0) {';
        echo "$('#wfmUserBasketMetaDataGrid').datagrid('loadData', []);";
        echo '}';
    }
    ?>
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var isAddRow = true;
        var subrows = $('#wfmUserBasketMetaDataGrid').datagrid('getRows');
        for (var j = 0; j < subrows.length; j++) {
            var subrow = subrows[j];
            if (subrow.id === row.id) {
                isAddRow = false;
            }
        }
        if (isAddRow) {
            $('#wfmUserBasketMetaDataGrid').datagrid('appendRow', {
                picture: row.picture,
                username: row.username,
                userfullname: row.userfullname,
                departmentcode: row.departmentcode,
                departmentname: row.departmentname,
                positionname: row.positionname,
                META_ICON_NAME: row.META_ICON_NAME,
                createddate: row.createddate,
                createdusername: row.createdusername,
                id: row.id,
                departmentid: row.departmentid,
                weight: row.weight,
                wfmruleid: row.wfmruleid,                
                action: '<a href="javascript:;" onclick="deleteWfmUserMetaDataBasket(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
            });
        }
    }
    $("body").find("#commonMetaSelectedCount").text($('#wfmUserBasketMetaDataGrid').datagrid('getData').total);
}
function dblClickWfmUserMetaDataGrid(index, row){``
    <?php
    if ($this->chooseType == 'single') {
        echo "$('#wfmUserBasketMetaDataGrid').datagrid('loadData', []);";
    }
    ?>
    var isAddRow = true;
    var rows = $('#wfmUserBasketMetaDataGrid').datagrid('getRows');
    for (var j = 0; j < rows.length; j++) {
        var subrow = rows[j];
        if (subrow.id === row.id) {
            isAddRow = false;
        }
    }
    if (isAddRow) {
        
        $('#commonMetaSelectedCount').pulsate({
            oclor: '#F3565D', 
            reach: 9,
            speed: 500,
            glow: false, 
            repeat: 1
        }); 
            
        $('#wfmUserBasketMetaDataGrid').datagrid('appendRow', {
            picture: row.picture,
            username: row.username,
            userfullname: row.userfullname,
            departmentcode: row.departmentcode,
            departmentname: row.departmentname,
            positionname: row.positionname,
            META_ICON_NAME: row.META_ICON_NAME,
            createddate: row.createddate,
            createdusername: row.createdusername,
            id: row.id,
            departmentid: row.departmentid,
            weight: row.weight,
            wfmruleid: row.wfmruleid,
            action: '<a href="javascript:;" onclick="deleteWfmUserMetaDataBasket(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
        });
    }
    $("body").find("#commonMetaSelectedCount").text($('#wfmUserBasketMetaDataGrid').datagrid('getData').total);
    
    <?php
    if ($this->chooseType == 'single') { ?>
        if($('#dialog-commonmetadata').length)
            $('#dialog-commonmetadata').closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-common-choose-btn").click();
    <?php
    }
    ?>        
}
function deleteWfmUserMetaDataBasket(target){
    $('#wfmUserBasketMetaDataGrid').datagrid('deleteRow', getRowIndex(target));
    $("body").find("#commonMetaSelectedCount").text($('#wfmUserBasketMetaDataGrid').datagrid('getData').total);
}
</script>