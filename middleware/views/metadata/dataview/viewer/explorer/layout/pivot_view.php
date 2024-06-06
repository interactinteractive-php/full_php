<?php
if ($this->recordList) {
    $this->color = Str::lower($this->color);
    
    if (isset($this->columnData)) {
        $columns = Arr::groupByArray($this->columnData, $this->columnName);
    } else {
        $columns = Arr::groupByArray($this->pureRecordList, $this->columnName);
    }
    
    $pageLimit = ceil($this->totalCount / (int) $this->dataGridOptionData['PAGESIZE']);
?>
<div class="pf-custom-pager pf-custom-pager-<?php echo $this->dataViewId; ?> border-0">
    <div class="pivot-view-<?php echo $this->dataViewId; ?>" style="overflow: auto; height: 400px">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>№</th>
                    <?php
                    if (!$this->isAllShowField) {
                    ?>
                    <th><?php echo Lang::line($this->header[$this->groupName]['row']['LABEL_NAME']); ?></th>
                    <?php
                    } else {
                        
                        foreach ($this->header as $header) {
                            
                            $headerRow = $header['row'];
                            
                            if ($headerRow['LABEL_NAME'] == '' || $headerRow['FIELD_PATH'] == $this->name1 || $headerRow['FIELD_PATH'] == $this->columnName || ($this->color && $headerRow['FIELD_PATH'] == $this->color)) {
                                continue;
                            }
                            
                            $width = $headerRow['COLUMN_WIDTH'];
                            
                            if ($width) {
                                $width = 'width: '.$width.'; min-width: '.$width;
                            }
                    ?>
                        <th style="<?php echo $width; ?>"><?php echo Lang::line($headerRow['LABEL_NAME']); ?></th>
                    <?php
                        }
                    }
                    
                    foreach ($columns as $colRow) {
                    ?>
                        <th><?php echo $colRow['row'][$this->columnName] ?></th>
                    <?php 
                    } 
                    ?>
                </tr> 
            </thead>   
            <tbody>   
            <?php    
            $n = 1;
            
            if (!$this->isAllShowField) {
                
                foreach ($this->recordList as $recordRow) {
                    $groupName = $recordRow['row'][$this->groupName];
                    $rows      = $recordRow['rows'];
                    $rowData   = htmlentities(json_encode($recordRow['row'], JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
            ?>
                <tr data-rowdata="<?php echo $rowData; ?>">
                    <td style="background-color:#fff;text-align: center"><?php echo $n; ?></td>
                    <td style="background-color:#fff"><?php echo $groupName; ?></td>
                    <?php
                    foreach ($columns as $colRow) {
                        echo '<td style="background-color:#fff">';
                            foreach ($rows as $rs) {
                                if ($rs[$this->columnName] == $colRow['row'][$this->columnName]) {
                                    $rowJson = htmlentities(json_encode($rs), ENT_QUOTES, 'UTF-8');
                                    echo '<div data-row-data="'.$rowJson.'" style="background-color: '.issetParam($rs[$this->color]).';padding: 3px;">' . $rs[$this->name1] . '</div>';
                                }
                            }
                        echo '</td>';
                    } 
                    ?>
                </tr>
            <?php 
                    $n ++;
                } 
                
            } else {
                
                foreach ($this->recordList as $record) {
                    $recordRow = $record['row'];
                    $rows      = $record['rows'];
                    $rowData   = htmlentities(json_encode($recordRow, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
            ?>
                <tr data-rowdata="<?php echo $rowData; ?>">
                    <td style="background-color:#fff;text-align: center"><?php echo $n; ?></td>
                    <?php
                    $pivotColDataType = '';
                    
                    foreach ($this->header as $header) {
                        
                        $headerRow = $header['row'];
                        
                        if ($headerRow['FIELD_PATH'] == $this->name1) {
                            $pivotColDataType = $headerRow['META_TYPE_CODE'];
                        }
                        
                        if ($headerRow['LABEL_NAME'] == '' || $headerRow['FIELD_PATH'] == $this->name1 || $headerRow['FIELD_PATH'] == $this->columnName || ($this->color && $headerRow['FIELD_PATH'] == $this->color)) {
                            continue;
                        }
                        
                        $dataType = $headerRow['META_TYPE_CODE'];
                        $style = '';
                        
                        if ($dataType == 'bigdecimal') {
                            $val = Number::fractionRange($recordRow[$headerRow['FIELD_PATH']], 2);
                            $style = 'text-align: right;';
                        } else {
                            $val = $recordRow[$headerRow['FIELD_PATH']];
                        }
                    ?>
                    <td style="background-color:#fff;<?php echo $style; ?>"><?php echo $val; ?></td>
                    <?php
                    }
                    
                    foreach ($columns as $colRow) {
                        echo '<td style="background-color:#fff">';
                            foreach ($rows as $rs) {
                                if ($rs[$this->columnName] == $colRow['row'][$this->columnName]) {
                                    
                                    $rowJson = htmlentities(json_encode($rs), ENT_QUOTES, 'UTF-8');
                                    $rVal = $rs[$this->name1];
                                    $cellStyle = '';
                                    
                                    if ($pivotColDataType == 'bigdecimal') {
                                        $rVal = Number::fractionRange($rVal, 2);
                                        $cellStyle = 'text-align: right;';
                                    } 
                                    
                                    echo '<div data-row-data="'.$rowJson.'" style="background-color: '.issetParam($rs[$this->color]).';padding: 3px;'.$cellStyle.'">' . $rVal . '</div>';
                                    break;
                                }
                            }
                        echo '</td>';
                    } 
                    ?>
                </tr>
            <?php
                    $n ++;
                }
            }
            ?>
            </tbody>
        </table>     
    </div>
    <div class="pf-custom-pager-tool" style="border: 1px #ddd solid">
        <div class="pf-custom-pager-buttons">
            <select name="" class="pagination-page-list" style="height:24px; float:left;color:#444">
                <?php
                    $pageList = str_replace(array('[', ']'), array('', ''), $this->dataGridOptionData['PAGELIST']);
                    $pageList = explode(',', $pageList);
                    foreach ($pageList as $row) {
                        $selected = '';
                        if ($this->dataGridOptionData['PAGESIZE'] == $row) {
                            $selected = ' selected';
                        }
                        echo '<option'.$selected.' value="'.$row.'">'.$row.'</option>';
                    }
                ?>
            </select>
            <div class="pf-custom-pager-separator"></div>
            <a href="javascript:;" class="pf-custom-pager-last-prev pf-custom-pager-disabled">
                <span></span>
            </a>
            <a href="javascript:;" class="pf-custom-pager-prev pf-custom-pager-disabled">
                <span></span>
            </a>
            <div class="pf-custom-pager-separator"></div>
            <div class="pf-custom-pager-page-info">Хуудас <span><input type="text" size="2" value="1" data-gotopage="1" class="integerInit"></span> of <span data-pagenumber="<?php echo $pageLimit ?>"><?php echo $pageLimit ?></span></div>	
            <div class="pf-custom-pager-separator"></div>
            <a href="javascript:;" class="pf-custom-pager-next">
                <span></span>
            </a>
            <a href="javascript:;" class="pf-custom-pager-last-next">
                <span></span>
            </a>
            <!--<div class="pf-custom-pager-separator"></div>
            <a href="javascript:;" class="pf-custom-pager-refresh">
                <span></span>
            </a>-->
        </div>
        <div class="pf-custom-pager-total">Нийт <span><?php echo $this->totalCount; ?></span> байна.</div>
    </div>   
</div>   
<?php 
} 
?>

<style type="text/css">
.pivot-view-<?php echo $this->dataViewId ?> thead th {
    background: #EBEBEB;
    font-weight: bold!important;
    text-align: center;
}
.pivot-view-<?php echo $this->dataViewId ?> {
    background: #fff;
}
.pivot-view-<?php echo $this->dataViewId ?> tr.paneldv-selected-row > td {
    background-color: #CCE6FF !important;
}
</style>

<script type="text/javascript">
$(function() {
    $('.pivot-view-<?php echo $this->dataViewId ?>').css('width', ($('#objectDataView_<?php echo $this->dataViewId ?>').width()));

    $(window).resize(function() {
        $('.pivot-view-<?php echo $this->dataViewId ?>').css('width', ($('#objectDataView_<?php echo $this->dataViewId ?>').width()));
    });
    
    var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->dataViewId; ?>.offset().top - 80;
    $('div.pivot-view-<?php echo $this->dataViewId; ?>').css({'height': dynamicHeight});
    
    pivotDataViewInitFreeze_<?php echo $this->dataViewId; ?>();
    
    setTimeout(function(){
        if ($("#objectDataView_<?php echo $this->dataViewId; ?>").find('.is-open-bp-default-<?php echo $this->dataViewId; ?>').length) {
            var $bpOpenSelector = $("#objectDataView_<?php echo $this->dataViewId; ?>").find('.is-open-bp-default-<?php echo $this->dataViewId; ?>');

            if ($bpOpenSelector.length > 1) {
                var rowBpDefault = getDataViewSelectedRowsByElement(objectdatagrid_<?php echo $this->dataViewId; ?>);

                if (rowBpDefault.length) {
                    $("#objectDataView_<?php echo $this->dataViewId; ?>").find('.is-open-bp-default-<?php echo $this->dataViewId; ?>[data-actiontype="update"]:first').trigger('click');
                } else {
                    $("#objectDataView_<?php echo $this->dataViewId; ?>").find('.is-open-bp-default-<?php echo $this->dataViewId; ?>[data-actiontype="insert"]:first').trigger('click');
                }

            } else {
                $bpOpenSelector.trigger('click');
            }
        } 
    }, 1000);    
    
    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('click', '.pf-custom-pager-prev:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(currentPageNumber - 1);
    });

    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('click', '.pf-custom-pager-last-prev:not(.pf-custom-pager-disabled)', function () {
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(1);
    });

    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('click', '.pf-custom-pager-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());        
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(currentPageNumber + 1);
        
    });

    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('click', '.pf-custom-pager-last-next:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
        var totalPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(totalPageNumber);
    });

    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('click', '.pf-custom-pager-refresh:not(.pf-custom-pager-disabled)', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(currentPageNumber);
    });    
    
    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('change', '.pagination-page-list', function () {
        var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
        var currentPageNumber = Number(pagerElement.find('input[data-gotopage]').val());
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(currentPageNumber);
        
    });    
    
    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('change', 'input[data-gotopage]', function () {
        var currentPageNumber = Number($(this).val());
        pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(currentPageNumber);
    }); 
    
    $('.pf-custom-pager-<?php echo $this->dataViewId; ?>').on('click', 'table > tbody > tr', function () {
        var $this = $(this), $tbody = $this.closest('tbody');
        $tbody.find('tr.paneldv-selected-row').removeClass('paneldv-selected-row');
        $this.addClass('paneldv-selected-row');
    }); 
    
    var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
    var pageNumbers = pagerElement.find('span[data-pagenumber]').text();
    if (pageNumbers == 1) {
        pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');    
    }
});

function pivotDataViewInitFreeze_<?php echo $this->dataViewId; ?>() {
    $('table', 'div.pivot-view-<?php echo $this->dataViewId; ?>').tableHeadFixer({'head': true, 'left': 2, 'z-index': 9}); 
}

function pivotDataViewGotoPage<?php echo $this->dataViewId; ?>(pageNumber) {

    Core.blockUI({boxed: true, message: 'Loading...'});

    var filterRules = '';
//    $('#tnatimePlanPage', '.pf-custom-pager-<?php echo $this->dataViewId; ?>').val(pageNumber)
//    $('table > thead > tr > th > input[data-fieldname]', '.pf-custom-pager-<?php echo $this->dataViewId; ?>').each(function () {
//        var _this = $(this);
//        var _value = _this.val();
//
//        if (_value != '') {
//            var fieldName = _this.attr('data-fieldname');
//            var condition = _this.attr('data-condition');
//
//            filterRules += '{"field":"' + fieldName + '","op":"' + condition + '","value":"' + _value + '"},';
//        }
//    });
//
//    if (filterRules) {
//        filterRules = rtrim(filterRules, ',');
//        filterRules = '[' + filterRules + ']';
//    }

    $.ajax({
        type: 'POST',
        url: 'mdobject/goToPagePivotView',
        data: {
            columnName: '<?php echo $this->columnName ?>',
            color: '<?php echo $this->color; ?>',
            dataViewId: '<?php echo $this->dataViewId; ?>',
            refStructureId: '<?php echo $this->refStructureId; ?>',
            layoutTheme: '<?php echo $this->layoutTheme; ?>',
            name1: '<?php echo $this->name1; ?>',
            defaultCriteriaData: '<?php echo $this->defaultCriteriaData; ?>',
            drillDownDefaultCriteria: '<?php echo $this->drillDownDefaultCriteria; ?>',
            groupName: '<?php echo $this->groupName; ?>',
            page: pageNumber,
            rows: $('.pagination-page-list > option:selected', '.pf-custom-pager-<?php echo $this->dataViewId; ?>').val(),
            filterRules: filterRules
        },
        dataType: 'json',
        beforeSend: function () {},
        success: function (data) {
            if (data.hasOwnProperty('status') && data.status == 'success') {
                $("table", '.pf-custom-pager-<?php echo $this->dataViewId; ?>').empty();
                var depreciationContent = $('table', '.pf-custom-pager-<?php echo $this->dataViewId; ?>')[0];
                depreciationContent.innerHTML = data.Html;
                $('table', '.pf-custom-pager-<?php echo $this->dataViewId; ?>').promise().done(function () {

                    var pagerElement = $('.pf-custom-pager-tool', '.pf-custom-pager-<?php echo $this->dataViewId; ?>');
                    var totalRowNumber = data.total;
                    var pageNum = Number($('.pagination-page-list > option:selected', '.pf-custom-pager-<?php echo $this->dataViewId; ?>').val());
                    var pageNumbers = Math.ceil(totalRowNumber / pageNum) || 1;
                    var currentPageNumber = Number(pagerElement.find('span[data-pagenumber]').text());

                    pagerElement.find('.pf-custom-pager-total > span').text(totalRowNumber);
                    pagerElement.find('input[data-gotopage]').val(pageNumber);
                    pagerElement.find('span[data-pagenumber]').text(pageNumbers);

                    if (currentPageNumber == 1) {
                        pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                        pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                    } else {
                        if (pageNumber == currentPageNumber) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                        } else if (pageNumber == 1 && pageNumbers == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else if (pageNumber == 1) {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev').addClass('pf-custom-pager-disabled');
                            pagerElement.find('.pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        } else {
                            pagerElement.find('.pf-custom-pager-prev, .pf-custom-pager-last-prev, .pf-custom-pager-next, .pf-custom-pager-last-next, .pf-custom-pager-refresh').removeClass('pf-custom-pager-disabled');
                        }
                    }
                    
                    pivotDataViewInitFreeze_<?php echo $this->dataViewId; ?>();
                    Core.unblockUI();
                });
            }
        }
    });
}
</script>
