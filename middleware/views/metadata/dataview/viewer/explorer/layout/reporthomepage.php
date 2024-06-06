<?php 
if ($this->recordList) {

$name = issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['name'], 'name');
$metaDataId = issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['metadataid'], 'metadataid');
$metaTypeId = issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['metatypeid'], 'metatypeid');
$groupName = issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['groupname'], 'groupname');
$leftGroupCount = issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['leftgroupcount'], 2);
$firstRow = $this->recordList[0];

if (array_key_exists($groupName, $firstRow)) {
    $dataList = Arr::groupByArray($this->recordList, $groupName);
} else {
    $firstRow[$groupName] = 'Хоосон';
    $dataList = array(
        $firstRow[$groupName] => array(
            'row' => $firstRow, 
            'rows' => $this->recordList
        )
    );
}
?>
<div class="dvexplorer pt-1">
    <div class="row">
        <div class="col-7">
            <div class="row align-items-center mb-4">
                <div class="col-6 d-flex align-items-center justify-content-center">
                    <img src="middleware/views/asset/covid/dvexplorer/cards_drib.jpg" style="width:300px"/>
                </div>
                <div class="col-6">
                    <h1 class="font-size-28 line-height-normal">Find your first reports</h1>
                    <div class="input-group mb-5 search-box">
                        <input type="search" class="form-control keyword-search" placeholder="<?php echo Lang::line('btn_search'); ?>...">
                        <i class="icon-search4 mr-1 search-icon text-root-color"></i>
                    </div>
                </div>
            </div>
            
            <?php
            $n = 1;
            foreach ($dataList as $k => $row) {
                $rows = $row['rows'];
            ?>
            <div class="mt-2">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0 font-weight-bold"><?php echo $row['row'][$groupName]; ?></h5>
                </div>
                <div class="row added_objects">
                    <?php
                    foreach ($rows as $val) {
                        $val['criteria'] = isset($val['criteria']) ? Str::lower($val['criteria']) : '';
                        $title = $this->lang->line($val[$name]);
                    ?>
                    <div class="col-3">
                        <div class="card cursor-pointer" data-metaid="<?php echo issetParam($val[$metaDataId]); ?>" data-typeid="<?php echo issetParam($val[$metaTypeId]); ?>" data-criteria="<?php echo $val['criteria']; ?>">
                            <div class="d-flex align-items-top justify-content-between">
                                <i class="icon-puzzle2 icon-2x text-primary"></i>
                            </div>
                            <div class="mt-2 font-weight-bold text-three-line" style="height: 54px;" title="<?php echo $title; ?>">
                                <?php echo $title; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <?php
                unset($dataList[$k]);
                if ($n == $leftGroupCount) { break; }
                            
                $n++;
            }
            ?>
            
        </div>
        
        <div class="col-5">
            <div class="grouping-wrap">
            <?php
            if ($dataList) {
                foreach ($dataList as $k => $row) {
                    $rows = $row['rows'];
            ?>
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="mb-0 font-weight-bold"><?php echo ($row['row'][$groupName] ? $row['row'][$groupName] : $this->lang->line('othermenu_title')); ?></h5>
                </div>
                <div class="row popular_reports">
                    <div class="col-12">
                        
                        <?php
                        foreach ($rows as $val) {
                            $val['criteria'] = isset($val['criteria']) ? Str::lower($val['criteria']) : '';
                            $title = $this->lang->line($val[$name]);
                        ?>
                        <div class="card cursor-pointer" data-metaid="<?php echo $val[$metaDataId]; ?>" data-typeid="<?php echo $val[$metaTypeId]; ?>" data-criteria="<?php echo $val['criteria']; ?>">
                            <div class="row d-flex align-items-center justify-content-center">
                                <div class="col font-weight-bold text-one-line" title="<?php echo $title; ?>">
                                    <i class="icon-stack-star font-size-26 text-root-color mr-2 top-0"></i>
                                    <?php echo $title; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        
                    </div>
                </div>
            <?php
                }
            }
            ?>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .div-objectdatagrid-<?php echo $this->dataViewId; ?>.bgnone {
        background: 0 !important;
        border: 0 !important;
    }
    .dvexplorer .card {
        padding: 20px 25px;
    }
    .dvexplorer .card a {
        color: #555;
    }
    .dvexplorer .card a:hover,
    .dvexplorer .card a:active,
    .dvexplorer .card a:focus {
        color: #000;
    }
    .dvexplorer .card:hover,
    .dvexplorer .card:active,
    .dvexplorer .card:focus {
        background: #f1f5fd;
        border: 1px solid rgba(0,0,0,0.03);
        box-shadow: 3px 3px 15px 0 rgba(0,0,0,.05);
    }
    .dvexplorer .cs-img {
        max-width: 50px;
    }
    .dvexplorer .cs-icons {
        opacity: 0.3;
    }
    .dvexplorer .cs-icons:hover,
    .dvexplorer .cs-icons:active,
    .dvexplorer .cs-icons:focus {
        opacity: 1;
        color: var(--root-color1);
        cursor: pointer;
    }
    .dvexplorer .popular_reports .card {
        padding: 10px;
        border-left: 0;
        border-right: 0;
        border-top: 1px solid #eee;
        border-bottom: 0;
        border-radius: 0;
        box-shadow: none !important;
        margin-bottom: 0;
    }
    .dvexplorer .input-group .form-control:not(textarea):not([type='file']):not(.form-control-lg):not(.select2-container-multi) {
        height: 36px !important;
        min-height: 36px !important;
        max-width: 350px;
    }
    .dvexplorer .search-box.input-group .form-control {
        border-radius: 100px;
        padding-left: 50px;
        padding-right: 20px;
    }
    .dvexplorer .search-box.input-group .search-icon {
        position: absolute;
        top: 10px;
        left: 20px;
        z-index: 9;
    }
</style>

<script type="text/javascript">
$(function() {
    
    $(windowId_<?php echo $this->dataViewId; ?>).find('.filter-right-btn').removeClass('d-flex').addClass('d-none');
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '[data-metaid]', function(){
        var elem = this, 
            $this = $(elem), 
            metaId = $this.attr('data-metaid'), 
            typeId = $this.attr('data-typeid'), 
            criteria = $this.attr('data-criteria'), 
            criteriaObj = qryStrToObj(criteria), 
            $searchForm = $(windowId_<?php echo $this->dataViewId; ?>).find('form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->dataViewId; ?>');
        
        for (var path in criteriaObj) {
            var $path = $searchForm.find('[data-path]:attrNoCase("data-path","'+path+'")');
            if ($path.length) {
                criteriaObj[path] = $path.val();
            }
        }
        
        if (criteriaObj) {
            criteria = $.param(criteriaObj);
        }
        
        if (typeId == '200101010000016') {
            appMultiTab({metaDataId: metaId+'', title: $this.text().trim(), type: 'dataview', proxyId: '', criteria: criteria}, elem);
        } else if (typeId == '200101010000035') {
            appMultiTab({metaDataId: metaId+'', title: $this.text().trim(), type: 'statement', criteria: criteria}, elem);
        }
    });
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('input', '.keyword-search', function(e){
        
        var code = e.keyCode || e.which;
        if (code == '9') return;
        
        var inputVal = $(this).val().toLowerCase().trim();
        var $groupingWrap = $('#objectdatagrid-<?php echo $this->dataViewId; ?>').find('.grouping-wrap');
        var $searchingWrap = $groupingWrap.next('.searching-wrap');
        
        if (inputVal == '') {
            $searchingWrap.hide();
            $groupingWrap.show();
            return;
        }
        
        var $rows = $('#objectdatagrid-<?php echo $this->dataViewId; ?>').find('[data-metaid]:not(.searching-item)');

        var $filteredRows = $rows.filter(function(){
            var $rowElem = $(this);
            var name = $rowElem.text().trim().toLowerCase();
            return name.indexOf(inputVal) !== -1;
        });
        
        var searchResults = [];
        $groupingWrap.hide();
        
        if ($filteredRows.length) {
            
            $filteredRows.each(function() {
                var $row = $(this);
                searchResults.push('<div class="card cursor-pointer searching-item" data-metaid="'+$row.attr('data-metaid')+'" data-typeid="'+$row.attr('data-typeid')+'" data-criteria="'+$row.attr('data-criteria')+'">');
                    searchResults.push('<div class="row d-flex align-items-center justify-content-center">');
                        searchResults.push('<div class="col font-weight-bold text-one-line" title="'+$row.text().trim()+'">');
                            searchResults.push('<i class="icon-stack-star font-size-26 text-root-color mr-2 top-0"></i>');
                            searchResults.push($row.text().trim());
                        searchResults.push('</div>');
                    searchResults.push('</div>');
                searchResults.push('</div>');
            });
            
        } else {
            searchResults.push('No data!');
        }
        
        if ($searchingWrap.length) {
            $searchingWrap.show();
            $searchingWrap.empty().append('<div class="d-flex align-items-center justify-content-between mb-2">'+
                '<h5 class="mb-0 font-weight-bold">Хайлт</h5>'+
            '</div>'+
            '<div class="row popular_reports">'+
                '<div class="col-12">'+
                    searchResults.join('')+
                '</div>'+
            '</div>');
        } else {
            $groupingWrap.after('<div class="searching-wrap">'+
                '<div class="d-flex align-items-center justify-content-between mb-2">'+
                    '<h5 class="mb-0 font-weight-bold">Хайлт</h5>'+
                '</div>'+
                '<div class="row popular_reports">'+
                    '<div class="col-12">'+
                        searchResults.join('')+
                    '</div>'+
                '</div>'+
            '</div>');
        }
    });
});
</script>
<?php
}
?>