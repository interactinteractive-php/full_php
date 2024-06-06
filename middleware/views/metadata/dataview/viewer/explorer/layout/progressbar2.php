<div class="work list mt-3">

    <?php

    if ($this->recordList) {
        
        $taskname = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name1']);
        $statusname = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name2']);
        $doneworkpercent = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name3']);
        $unitprice = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name4']);
        // $ischeck = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name4']);
        $percent =  $this->recordList[0]['doneworkpercent']; 
    ?>

    <div class="progress rounded-round">
        <div class="progress-bar bg-success" style="width: <?php echo $percent; ?>%">
            <span><?php echo $percent; ?>%</span>
        </div>
    </div>
    <ul>
    <?php 
    foreach ($this->recordList as $row) {
        ?>
            <li> 
                <div class="squaredTwo">
                    <input type="checkbox" name="item" value="<?php echo $row['checkstatus']; ?>"  <?php echo ($row['checkstatus'] == '1') ? 'checked' : ''; ?> readonly>
                    <label for="squaredTwo"></label>
                </div>
                <span><?php echo $row[$taskname]; ?></span>
                <span style="float:right"><?php echo $row[$unitprice]; ?></span>
            </li>
            <?php
        }
    }
    ?>
    </ul>
</div>

<style>
    .work .progress .progress-bar span{
        font-size: 15px;
    }
    .work .progress{
        height: 20px;
        margin-bottom: 20px;
    }
    .work ul li span{
        margin-left: 40px;
        font-size: 15px;
    }
    .work ul li{
        margin: 10px 0;
    }
    .work ul {
        list-style-type: none;
        padding: 0;
    }

    .squaredTwo div.checker .checked:after {
        content: '';
        width: 13px;
        height: 6px;
        position: absolute;
        top: 5px;
        left: 1px;
        border: 3px solid #fff;
        border-top: none;
        border-right: none;
        background: transparent;
        opacity: 1;
        transform: rotate(-45deg);
    }
    .squaredTwo div.checker span{
        background-image: inherit !important;
    }
    .squaredTwo .checker.checked span{
        background-position: inherit !important;
    }
    .squaredTwo {
        width: 22px;
        height: 22px;
        position: absolute;
        border-radius: 4px;
        background: #4b8df8;
        border: 1px solid #4b8df8;
    }
    .squaredTwo label {
        width: 20px;
        height: 20px;
        cursor: pointer;
        position: absolute;
        left: 1px;
        top: 4px;
    }
    .squaredTwo label:after {
        content: '';
        width: 13px;
        height: 6px;
        position: absolute;
        top: 1px;
        left: 3px;
        border: 3px solid #fff;
        border-top: none;
        border-right: none;
        background: transparent;
        opacity: 0;
        transform: rotate(-45deg);
    }
    .squaredTwo label:hover::after {
        opacity: 0.3;
    }
    .squaredTwo input[type=checkbox] {
        visibility: hidden;
    }
    .squaredTwo input[type=checkbox]:checked + label:after {
        opacity: 1 !important;
    }
</style>