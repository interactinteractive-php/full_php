<div class="explorer7_<?php echo $this->dataViewId ?>">
    <?php $color = '#0ca766'; ?>
    <ul>
        <li class="dv-explorer-row ">	
            <a target="_blank" class="wfm-status-done d-flex align-items-center" href="<?php echo ((isset($recordRow['metadataid']) && isset($recordRow['wfmstatusid'])) ? "mdobject/dataview/". $recordRow['metadataid'] ."&dv[wfmstatusid][]=". $recordRow['wfmstatusid'] ."" : 'javascript:;'); ?>">
                <?php echo $this->row['LIST_NAME']; ?> 
            </a>
        </li>
        <?php

        if ($this->recordList) {

            if (isset($this->recordList['status'])) {
                echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: '.$this->recordList['message']); exit();
            }

            $firstRow = $this->recordList[0];
            (String) $name1 = $name2 = $name3 = $name4 = $name5 = $photoField = $rowcolor = ' echo "";';
            (String) $click = ' echo "href=\"javascript:;\"";';

            if (isset($firstRow[$this->name1])) {
                $name1 = 'echo $recordRow[$this->name1];';
                $click = 'echo \'href="mdobject/dataview/\'.$recordRow[\'metadataid\'].\'"\';';
            }

            if (isset($firstRow[$this->name2])) {
                $name2 = 'echo \' ( \'.$recordRow[$this->name2].\' )\';';
            }

            if (isset($firstRow[$this->name3])) {
                $name3 = 'echo \'\'.$recordRow[$this->name3].\' )\';';
            }
            
            if (isset($firstRow['rowcolor'])) {
                $rowcolor = 'echo " style=\"background-color: ".$recordRow[\'rowcolor\']."\"";';
            }
            
            foreach ($this->recordList as $recordRow) {
                $color = (isset($recordRow['color']) && $recordRow['color']) ? $recordRow['color'] : '#0ca766';
                $event = "mdobject/dataview/". $recordRow['metadataid'];
                $event = '';
                if (isset($this->name4) && $this->name4 && $this->name5 && isset($recordRow[$this->name5])) {
                    $event .= "". $this->name5 ."=". $recordRow[$this->name4] . "&" ;
                }
                
                if (isset($this->name6) && $this->name6 && $this->name7 && isset($recordRow[$this->name6])) {
                    $event .= "". $this->name7 ."=". $recordRow[$this->name6];
                }
                
                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8'); ?>
                <li class="dv-explorer-row selected-row " data-row-data="<?php echo $rowJson ?>">	
                    <a target="_blank" data-row-data="<?php echo $rowJson ?>" data-row="<?php echo $rowJson ?>" class="wfm-status-done selected-row-link d-flex align-items-center" onclick="gridDrillDownLink(this, 'HRM_EMPLOYEE_KEY_DV_GB', 'metagroup', '1', '', '<?php echo $recordRow['metadataid'] ?>', 'code', '<?php echo $recordRow['metadataid'] ?>', '<?php echo $event; ?>', true, true)" href="javascript:;">
                        <?php eval($name1) .  eval($name2); ?> 
                        <div><?php eval($name3); ?> </div>
                    </a>
                </li>
            <?php
            }
        }
        ?>
    </ul>   
</div>

<style type="text/css">
   
    .layout-theme22 .list-icons-item:after {
        content:none !important;
    }

    .explorer7_<?php echo $this->dataViewId ?> {
        text-align: left;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul {
        list-style: none;
        display: inline-table;
        margin-bottom: 0;
        padding: 0;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li {
        display: inline;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a {
        display: block;
        float: left;
        height: 60px;
        background-color: #eaedf4;
        text-align: center;
        padding: 10px 45px;
        position: relative;
        margin: 0 5px 0 0; 
        font-size: 14px;
        text-decoration: none;
        color: #515f77;
        
        font-weight: 600;
        border-top: 1px <?php echo $color ?> solid;
        border-bottom: 1px <?php echo $color ?> solid;
        border-right: 8px <?php echo $color ?> solid;
        margin-left: -6px;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a:after {
        content: "";  
        border-top: 28px solid transparent;
        border-bottom: 28px solid transparent;
        border-left: 28px solid #eaedf4;
        position: absolute; 
        right: -26px; 
        top: 0;
        z-index: 1;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a:before {
        content: "";  
        border-top: 28px solid transparent;
        border-bottom: 28px solid transparent;
        border-left: 28px solid #fff;
        position: absolute; 
        left: 0; 
        top: 0;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li:first-child a {
        text-transform: uppercase;
        padding: 0 0 0 10px;
        word-wrap: break-word;
        width: 160px;
        color: #FFF !important;
        background-color: <?php echo $color ?> !important;
        border-left: 1px solid <?php echo $color ?>;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li:first-child a:after {
        background-color: <?php echo $color ?> !important;
        z-index: -1;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li:first-child a:before {
        display: none; 
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li:last-child a {
        padding-right: 28px;
        text-transform: uppercase;
        border-right: 3px <?php echo $color ?> solid;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li:last-child a:after {
        display: none; 
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a.wfm-status-done:hover {
        background-color: <?php echo $color ?>;
        color: #FFF;
        border-top: 1px <?php echo $color ?> solid;
        border-bottom: 1px <?php echo $color ?> solid;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a.wfm-status-done:hover:after {
        border-left-color: <?php echo $color ?>;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a.wfm-status-done {
        color: #000;
        background-color: #FFF; //<?php echo $color ?>;
        border-top: 3px <?php echo $color ?> solid;
        border-bottom: 3px <?php echo $color ?> solid;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a.wfm-status-done:after {
        border-left: 28px solid #FFF;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a.wfm-status-current {
        color: #fff;
        background-color: #0070d2;
        border-top: 1px #0070d2 solid;
        border-bottom: 1px #0070d2 solid;
    }
    .explorer7_<?php echo $this->dataViewId ?> ul li a.wfm-status-current:after {
        border-left: 28px solid #0070d2;
    }
    .body-top-menu-style .explorer7_<?php echo $this->dataViewId ?> ul li a:before {
        border-left: 28px solid <?php echo $color ?>;
    }
    
</style>