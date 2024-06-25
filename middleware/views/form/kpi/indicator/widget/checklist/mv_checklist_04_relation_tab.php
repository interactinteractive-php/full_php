<?php            
//$relationTabList = Arr::groupByArrayByNullKey($this->relationList, 'TAB_NAME'); 
$relationTabList = $this->relationList; 
?>

<div class="bp-tabs tabbable-line mv-main-tabs mv-checklist4-tab w-100">
    <ul class="nav nav-tabs" style="padding-bottom: 0px !important;">
        <?php
        $tabId = 1;
        foreach ($relationTabList as $groupName => $groupRow) {
            echo '<li class="nav-item">';
                echo '<a style="padding-top: 3px;" href="#maintabcustom4_'.$this->uniqId.'_'.$tabId.'" class="nav-link '.($tabId == -1 ? 'active' : '').' mv-checklist-tab-link" data-toggle="tab" aria-expanded="false">';
                    echo $groupRow['NAME'] ? $groupRow['NAME'] : $groupRow['META_DATA_NAME'];
                echo '</a>';
            echo '</li>';
            $tabId ++;
        }                    
        ?>
    </ul>
    <div class="tab-content" style="padding-top: 0px;padding-bottom: 0px;"> 
        <?php
        $tabId = 1;
        foreach ($relationTabList as $tabName => $tabRow) {       
        ?>                    
            <div class="tab-pane <?php echo ($tabId == -1 ? 'active' : ''); ?>" data-id="<?php echo $tabRow['ID'] ? $tabRow['ID'] : $tabRow['META_DATA_ID'] ?>" data-mapid="<?php echo $tabRow['MAP_ID'] ?>" data-type-id="<?php echo $tabRow['KPI_TYPE_ID'] ? $tabRow['KPI_TYPE_ID'] : $tabRow['META_TYPE_ID']; ?>" id="maintabcustom4_<?php echo $this->uniqId; ?>_<?php echo $tabId; ?>" style="padding-bottom: 0 !important;padding-top: 6px !important;padding-right: 0 !important;">
            </div>
        <?php 
            $tabId ++;
        }                    
        ?>                    
    </div>
</div>  