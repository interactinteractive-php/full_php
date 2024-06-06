<div class="col-md-12">
    <div class="card light shadow bordered bg-white mb0">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title">
                <i class="fa fa-refresh"></i>
                <span class="caption-subject font-weight-bold uppercase card-subject-blue"><?php echo $this->title; ?></span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            <div class="tabbable-line">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#bugfix_tab_1" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('list_title'); ?></a>
                    </li>
                    <li class="nav-item">
                        <a href="#bugfix_tab_2" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> (<span id="bugfix_basket_count">0</span>)</a>
                    </li>
                    <li class="nav-item">
                        <a href="#bugfix_tab_3" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00205'); ?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="bugfix_tab_1">
                        <div class="row">
                            <div class="col-md-3">
                                <form id="bugfixDatagrid-form" method="post">
                                    <div class="form-body row">
                                        <div class="col-md-12">
                                            <label>Тайлбар:</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <select name="criteriaCondition[description]" class="form-control form-control-sm right-radius-zero float-right">
                                                <option value="like">Төстэй</option>
                                                <option value="=">Тэнцүү</option>
                                            </select>
                                        </div>
                                        <div class="col-md-10 pl-0">
                                            <input type="text" name="param[description]" class="form-control form-control-sm" placeholder="Тайлбар">
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label>Хэрэглэгч:</label>
                                        </div>
                                        <div class="col-md-2 pr-0">
                                            <select name="criteriaCondition[createdusername]" class="form-control form-control-sm right-radius-zero float-right">
                                                <option value="like">Төстэй</option>
                                                <option value="=">Тэнцүү</option>
                                            </select>
                                        </div>
                                        <div class="col-md-10 pl-0">
                                            <input type="text" name="param[createdusername]" class="form-control form-control-sm" placeholder="Хэрэглэгч">
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <label>Огноо:</label>
                                        </div>
                                        <div class="col-md-12 date-float-left">
                                            <div class="dateElement input-group">
                                                <input type="text" name="param[createddate][]" class="form-control form-control-sm dateInit" placeholder="Эхлэх огноо" value="<?php echo Date::weekdayAfter('Y-m-d', Date::currentDate('Y-m-d'), '-1 month'); ?>">
                                                <span class="input-group-btn">
                                                    <button onclick="return false;" class="btn" tabindex="-1"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                            <div class="dateElement input-group">
                                                <input type="text" name="param[createddate][]" class="form-control form-control-sm dateInit" placeholder="Дуусах огноо" value="<?php echo Date::weekdayAfter('Y-m-d', Date::currentDate('Y-m-d'), '+1 day'); ?>">
                                                <span class="input-group-btn">
                                                    <button onclick="return false;" class="btn" tabindex="-1"><i class="fa fa-calendar"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-actions mt20">
                                        <button type="button" class="btn blue btn-sm" onclick="bugfixDatagridSearch();"><i class="fa fa-search"></i> Хайх</button> 
                                        <button type="button" class="btn grey-cascade btn-sm ml5" onclick="bugfixDatagridReset();">Цэвэрлэх</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-9 jeasyuiTheme3 pl0">
                                <table id="bugfix-datagrid"></table>
                            </div>
                        </div>    
                    </div>
                    <div class="tab-pane" id="bugfix_tab_2">
                        
                        <div class="row">
                            <div class="col-md-2 pr0">
                                <button type="button" class="btn blue btn-sm mb10" onclick="startBugFixing();">
                                    <i class="fa fa-play-circle"></i> Эхлүүлэх
                                </button>
                            </div>
                            <div class="col-md-7 pl0 pr0">
                                <div class="progress mt2" style="background-color: #ddd;">
                                    <div class="progress-bar bg-success bf-process-bar font-size-14" style="width: 0%">0%</div>
                                </div>
                            </div>
                            <div class="col-md-3 text-right">
                                <button type="button" class="btn green-meadow btn-sm mb10" onclick="metaPHPImport();">
                                    <i class="fa fa-upload"></i> Файл оруулах
                                </button>
                                <button type="button" class="btn purple-plum btn-sm mb10" onclick="downloadBugFixing();">
                                    <i class="fa fa-download"></i> Файл татах
                                </button>
                            </div>
                        </div>
                        
                        <table class="table table-sm table-hover" id="bugfix-basket">
                            <thead>
                                <tr>
                                    <th style="width: 20px" class="text-center">#</th>
                                    <th style="width: 20px"><input type="checkbox" class="notuniform bugfix-all-check"></th>
                                    <th>Тайлбар</th>
                                    <th>Огноо</th>
                                    <th>Хэрэглэгч</th>
                                    <th>Төлөв</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="bugfix_tab_3">
                        <div class="bf-update-logs" contenteditable="true"></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>    

<div class="clearfix w-100"></div>

<style type="text/css">
.bf-update-logs {
    background-color: #eeeeee;
    border: 1px solid #d0d0d0;
    font-family: monospace, monospace;
    font-size: 1em;
    padding: 6px 12px;
    line-height: 1.42857143;
    border-radius: 4px;
    overflow: auto;
}
.jeasyuiTheme3 *::-moz-selection { background:transparent; }
.jeasyuiTheme3 *::selection { background:transparent; }
</style>

<script type="text/javascript">
var $bugfixDatagrid = $('#bugfix-datagrid'), dbUpdatePercent = 0, dbUpdateCompleted = false;
var bfLogElementHeight = ($(window).height() - $bugfixDatagrid.offset().top - 30);
var progressPercent = 1;
$('.bf-update-logs').css({'height': bfLogElementHeight + 'px', 'maxHeight': bfLogElementHeight + 'px'});

$(function () {       
    
    $bugfixDatagrid.attr('height', ($(window).height() - $bugfixDatagrid.offset().top - 30) + 'px');
    
    $bugfixDatagrid.datagrid({
        view: horizonscrollview, 
        url: 'mdupgrade/bugfixDatagrid',
        queryParams: {
            defaultCriteriaData: $('form#bugfixDatagrid-form').serialize()
        }, 
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true, 
        checkOnSelect: true, 
        selectOnCheck: true, 
        pagination: true,
        pageSize: 30,
        multiSort: false,
        remoteSort: true,
        remoteFilter: true,
        filterDelay: 10000000000,
        resizeHandle: 'right',
        fitColumns: false,
        autoRowHeight: true,
        striped: false,
        frozenColumns: [[
            {field: 'ck', checkbox: true}
        ]],
        columns: [[
            {field: 'description', title: 'Тайлбар', halign: 'center', sortable: true, width: 600},
            {field: 'createddate', title: 'Огноо', halign: 'center', sortable: true, width: 122},
            {field: 'createdusername', title: 'Хэрэглэгч', sortable: true, halign: 'center', width: 100}, 
            {field: 'isrequired', title: 'Заавал эсэх', sortable: true, align: 'center', width: 70}, 
            {field: 'fixed', title: 'Ажилласан эсэх', sortable: true, align: 'center', width: 90, formatter: function(val,row){
                if (val == '1') {
                    return 'Тийм';
                } else {
                    return 'Үгүй';
                }
            }}
        ]],
        onCheckAll: function(){
            $.uniform.update();
        },
        onUncheckAll: function(){
            $.uniform.update();
        },
        onClickRow: function(){
            $.uniform.update();
        }, 
        onRowContextMenu: function (e, index) {
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.uniform.update();
            $.contextMenu({
                selector: '.datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function (key, opt) {
                    if (key === 'basket') {
                        basketBugfixDatagrid();
                    }
                },
                items: {
                    "basket": {name: 'Сагсанд нэмэх', icon: 'plus-circle'}
                }
            });
        },
        rowStyler: function(index, row){
            if (row.fixed === '1') {
                return 'background-color: #1ab386;';
            }
        }, 
        onDblClickRow:function(index, row) {
            dblClickBugFixDataGrid(row);
        }, 
        onLoadSuccess: function(data){
            
            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            
            showGridMessage($bugfixDatagrid);
            
            $bugfixDatagrid.datagrid('resize');   
        }
    });
    $bugfixDatagrid.datagrid('enableFilter', [
        {
            field: 'isrequired',
            type: 'label'
        },
        {
            field: 'createduserid',
            type: 'label'
        }
    ]);
    $(window).bind('resize', function () {
        $bugfixDatagrid.datagrid('resize');
    });

    $('#bugfixDatagrid-form').on('keydown', 'input', function (e) {
        if (e.which === 13) {
            bugfixDatagridSearch();
        }
    });
    
    $('.bugfix-all-check').on('click', function() {
        var $this = $(this);
        var $outputParamTable = $this.closest('table');
        var outputParamCol = $this.closest('tr').children().index($(this).closest('th'));
        var outputParamIndex = outputParamCol + 1;
        $outputParamTable.find("td:nth-child(" + outputParamIndex + ") input:checkbox").attr('checked', $this.is(':checked'));
    });
});

function bugfixDatagridSearch() {
    $bugfixDatagrid.datagrid('load', {
        defaultCriteriaData: $('form#bugfixDatagrid-form').serialize()
    });
}
function bugfixDatagridReset() {
    $('#bugfixDatagrid-form').find('input[type=text]').val('');
}
function basketBugfixDatagrid() {
    var $bugFixRows = $bugfixDatagrid.datagrid('getSelections');
    var $bugfixBasket = $('#bugfix-basket > tbody');
    var $basketData = [];
    var $rowElement = '';
    
    for (var i = 0; i < $bugFixRows.length; i++) {
        var bugfixRow = $bugFixRows[i];
        var isAddRow = true;

        if ($bugfixBasket.find('tr[data-bugfix-id="'+bugfixRow.id+'"]').length) {
            isAddRow = false;
        }

        if (isAddRow) {
            
            $rowElement = '<tr data-bugfix-id="'+bugfixRow.id+'">'+
                '<td class="text-center">1</td>'+
                '<td><input type="checkbox" class="notuniform" checked="checked"></td>'+
                '<td>'+bugfixRow.description+'</td>'+ 
                '<td>'+bugfixRow.createddate+'</td>'+ 
                '<td>'+bugfixRow.createdusername+'</td>'+ 
                '<td data-status="true"><span class="badge label-sm badge-success">New</span></td>'+
                '<td><a href="javascript:;" class="btn btn-xs red" title="Устгах" onclick="removeBugfixDatagrid(this);"><i class="fa fa-trash"></i></a></td>'+ 
            '</tr>';
    
            $basketData.push($rowElement);
        }
    }
    
    if ($basketData.length) {
        $bugfixBasket.append($basketData.join(''));
        bugfixBasketNumbering($bugfixBasket);
    }
}

function dblClickBugFixDataGrid(bugfixRow) {
    var $bugfixBasket = $('#bugfix-basket > tbody');
    var $rowElement = '';
    var isAddRow = true;

    if ($bugfixBasket.find('tr[data-bugfix-id="'+bugfixRow.id+'"]').length) {
        isAddRow = false;
    }

    if (isAddRow) {

        $rowElement = '<tr data-bugfix-id="'+bugfixRow.id+'">'+
            '<td class="text-center">1</td>'+
            '<td><input type="checkbox" class="notuniform" checked="checked"></td>'+
            '<td>'+bugfixRow.description+'</td>'+ 
            '<td>'+bugfixRow.createddate+'</td>'+ 
            '<td>'+bugfixRow.createdusername+'</td>'+ 
            '<td data-status="true"><span class="badge label-sm badge-success">New</span></td>'+
            '<td><a href="javascript:;" class="btn btn-xs red" title="Устгах" onclick="removeBugfixDatagrid(this);"><i class="fa fa-trash"></i></a></td>'+ 
        '</tr>';
        
        $bugfixBasket.append($rowElement);
        bugfixBasketNumbering($bugfixBasket);
    }
}

function bugfixBasketNumbering($bugfixBasket) {
    var $rowNumEl = $bugfixBasket.find('> tr');
    var rowNumLen = $rowNumEl.length, ni = 0;

    for (ni; ni < rowNumLen; ni++) { 
        $($rowNumEl[ni]).find('td:first').text(ni + 1);
    }
    
    $('#bugfix_basket_count').text(ni);
}
function removeBugfixDatagrid(elem) {
    var $parentRow = $(elem).closest('tr');
    var $bugfixBasket = $parentRow.closest('tbody');
    
    $parentRow.remove();
    
    if ($bugfixBasket.find('tr').length === 0) {
        $('.bf-process-bar').css({width: '0%'}).text('0%');
        $('.bf-update-logs').empty();
    } else {
        bugfixBasketNumbering($bugfixBasket);
    }
}

function startBugFixing() {
    
    var $checkedBugfix = $('#bugfix-basket > tbody input[type="checkbox"]:checked').closest('tr');
    var $bugfixCount   = $checkedBugfix.length, ni = 0;
    var $logs          = $('.bf-update-logs');
    
    if ($bugfixCount > 0) {
        
        Core.blockUI({
            message: 'Updating...', 
            boxed: true
        });

        $('.bf-process-bar').css({width: '0%'}).text('0%');
        $logs.empty();

        setTimeout(function() {

            for (ni; ni < $bugfixCount; ni++) { 
                updatingBugFixing($($checkedBugfix[ni]).attr('data-bugfix-id'), ni, $bugfixCount); 
            }
            
            if ($logs.text()) {
                PNotify.removeAll(); 
                new PNotify({
                    title: 'Info',
                    text: 'Лог үүссэн байна. Та Лог таб руу орж үзнэ үү.',
                    type: 'info',
                    sticker: false, 
                    addclass: pnotifyPosition,
                    delay: 1000000000
                });
            }

        }, 1000);    
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Info',
            text: 'No bugfix!',
            type: 'info',
            sticker: false
        });
    }
}

function updatingBugFixing(bugFixId, ni, bugfixCount) {
    $.ajax({
        type: 'post',
        url: 'mdupgrade/updatingBugFixing', 
        data: {bugFixId: bugFixId, n: ni, bugfixCount: bugfixCount},    
        dataType: 'json',
        async: false, 
        success: function(data) {
            
            if (data.status == 'success') {
                
                dbUpdatePercent++;
                var percentComplete = (dbUpdatePercent / bugfixCount) * 100;

                $('.bf-process-bar').css({
                    width: percentComplete + '%'
                }).text(percentComplete.toPrecision(3) + '%');

                if (dbUpdatePercent == bugfixCount) {
                    
                    dbUpdateCompleted = true;    
                    dbUpdatePercent = 0;
                    
                    Core.unblockUI();
                }
            }
            
            if (data.hasOwnProperty('logs') && (data.logs !== '' || data.logs !== null)) {
                $('.bf-update-logs').append(data.logs);
            }
            
        }
    });
}
function downloadBugFixing() {
    
    var $checkedBugfix = $('#bugfix-basket > tbody input[type="checkbox"]:checked').closest('tr');
    var $bugfixCount   = $checkedBugfix.length, ni = 0;
    
    if ($bugfixCount > 0) {
        
        Core.blockUI({
            message: 'Downloading...', 
            boxed: true
        });

        $('.bf-process-bar').css({width: '0%'}).text('0%');
        $('.bf-update-logs').empty();
        
        progressPercent = 1;
        var varDownloadBugFixTimer = setInterval(downloadBugFixTimer, 500);

        setTimeout(function() {
            
            var $bugfixIds = '';
            
            for (ni; ni < $bugfixCount; ni++) { 
                $bugfixIds += $($checkedBugfix[ni]).attr('data-bugfix-id') + ',';
            }
            
            $.fileDownload(URL_APP + 'mdupgrade/downloadBugFixing', {
                httpMethod: 'POST',
                data: {bugfixIds: $bugfixIds},
                successCallback: function (url) {
                    clearInterval(varDownloadBugFixTimer);
                    $('.bf-process-bar').css({width: '100%'}).text('100%');
                    Core.unblockUI();
                },
                prepareCallback: function (url) {},
                failCallback: function (responseHtml, url) {
                    clearInterval(varDownloadBugFixTimer);
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Error',
                        text: response,
                        type: 'error',
                        sticker: false
                    });
                    Core.unblockUI();
                }
            });  

        }, 1000);    
        
    } else {
        
        PNotify.removeAll();
        new PNotify({
            title: 'Info',
            text: 'No bugfix!',
            type: 'info',
            sticker: false
        });
    }   
}
function downloadBugFixTimer() {
    $('.bf-process-bar').css({width: progressPercent + '%'}).text(progressPercent + '%');
    progressPercent++;
}
</script>