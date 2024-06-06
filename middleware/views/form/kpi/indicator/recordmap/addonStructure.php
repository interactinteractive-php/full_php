<div class="table-toolbar mb5">
    <button type="button" class="btn btn-xs green-meadow" onclick="mvAddStructureFormCardView(this);" data-limit="<?php echo $this->additionalInfo['STRUCTURE_LIMIT']; ?>" data-tabname="<?php echo $this->additionalInfo['STRUCTURE_TAB_NAME']; ?>">
        <i class="far fa-plus"></i> <?php echo $this->lang->line('add_btn'); ?>
    </button>
</div>
<div class="mv-addon-structure-render">
    <?php 
    $removeBtn = '<button type="button" class="btn btn-xs red" style="position: absolute;right: 20px;top: -3px;" onclick="mvAddStructureFormRemove(this);"><i class="far fa-trash"></i></button>';
    
    foreach ($this->structureTabContent as $row) {
        $hidden = '<input type="hidden" name="kpiAddonForm['.$row['id'].'_'.$row['uniqId'].']" value="'.$row['recordId'].'">';
        
        echo '<div data-addonform-id="'.$row['id'].'" data-addonform-recordid="'.$row['recordId'].'" data-addonform-uniqid="'.$row['uniqId'].'" style="position: relative">
                '.$removeBtn.'
                <fieldset class="collapsible border-fieldset mt-2 mb-3">
                    <legend>'.$row['name'].'</legend>'.
                    $row['form'].$hidden.
                '</fieldset>
            </div>';
    }
    ?>
</div>