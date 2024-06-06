<div class="m-4" style="overflow-x: auto;">

    <?php

    if ($this->recordList) {
        
        $taskname = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name1']);
        $title = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name2']);
        $description = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name3']);
        $icon = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name4']);
        $color = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['color']);
        $totalCount = count($this->recordList) - 1;
        
        echo '<div class="d-flex mt-4 pb-4 progressstep-list">';
        foreach ($this->recordList as $key => $row) {
            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
        ?>
            <div class="d-flex flex-column progressstep-row" data-row-data="<?php echo $rowJson; ?>" style="align-items: center;width:<?php echo isset($row['width']) ? $row['width'] : 250 ?>px">
                <div class="d-flex" style="align-items: center;">
                    <div style="height: 3px;background: <?php echo $key ? ($row[$color] ? $row[$color] : '#b1b1b1') : 'transparent' ?>;width: 84px"></div>
                    <div class="d-flex __pstep-item" style="border-color: <?php echo $row[$color] ? $row[$color] : '#b1b1b1' ?>">
                        <i class="<?php echo $row[$icon] ?>" style="font-size: 16px;color: <?php echo $row[$color] ? $row[$color] : '#b1b1b1' ?>"></i>
                    </div>
                    <div style="height: 3px;background: <?php echo $totalCount > $key ? ($row[$color] ? $row[$color] : '#b1b1b1') : 'transparent' ?>;width: 84px"></div>
                </div>    
                <div class="mt-2 font-weight-bold"><?php echo $row[$title] ?></div>
                <div class="mt-1 __text4ellipsis"><?php echo $row[$description] ?></div>
            </div>    
        <?php
        }
        echo '</div>';
    }
    ?>
</div>

<style>
    .explorer-table>.explorer-table-row>.explorer-table-cell, .explorer-table>.explorer-table-row, .explorer-table {
        display: block;
    }
    .__pstep-item {
        width: 32px; 
        height: 32px; 
        background: white; border-radius: 50px; border: 3px solid greenyellow; 
        align-content: center; align-items: center; justify-content: center        
    }
    .__text4ellipsis {
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;        
    }
    .progressstep-row {
        cursor: pointer;
    }
</style>

<script type="text/javascript">
    <?php
    if ($this->dataGridOptionData['DRILLDBLCLICKROW'] == 'true' && $this->dataGridOptionData['DRILL_CLICK_FNC']) {
    ?>
        $(document.body).on("click", ".progressstep-row", function () {
            var elem=this;
            var _this=$(elem);
            var _parent=_this.closest('.progressstep-list');
            _parent.find('.selected-row').removeClass('selected-row');
            _this.addClass('selected-row');            
            <?php echo $this->dataGridOptionData['DRILL_CLICK_FNC']; ?>
        });        
    <?php
    } ?>    
</script>       