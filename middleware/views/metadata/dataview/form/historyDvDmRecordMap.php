<?php
if ($this->historyList['status'] == 'success') {
    $list = $this->historyList['list'];
?>
<table class="table table-hover mt0">
    <thead>                                                                                                                                                                    <thead>
        <tr>
            <th class="font-weight-bold text-uppercase">
                <?php echo $this->lang->line('dmrmap_window_name'); ?>  
            </th>
            <th class="font-weight-bold text-uppercase">
                <?php echo $this->lang->line('dmrmap_window_wfmstatusname'); ?>  
            </th>
            <th class="font-weight-bold text-uppercase">
                <?php echo $this->lang->line('dmrmap_window_createdusername'); ?>    
            </th>
            <th class="font-weight-bold text-uppercase">
                <?php echo $this->lang->line('dmrmap_window_createddate'); ?>      
            </th>
        </tr>
    </thead>                                                   
    <tbody>
    <?php
    if ($list) {
        foreach ($list as $row) {
    ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['wfmstatusname']; ?></td>
            <td><?php echo $row['createdusername']; ?></td>
            <td><?php echo $row['createddate']; ?></td>
        </tr>
    <?php
            if (isset($row['next'])) {
                $next = $row['next'];
            }
        }
    }
    ?>
    </tbody>
</table>
<?php
    if (isset($next)) {
?>
<div class="uppercase font-weight-bold">
    <table class="table mb0">
        <thead>
            <tr>
                <th><label class="font-weight-bold"><?php echo Lang::line('wfm_next_assign_user') ?></label></th>
            </tr>    
        </thead>
        <tbody>
            <?php foreach ($next as $nkey => $wfmLogNext) { ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="mr-2">
                                <img class="rounded-circle" src="<?php echo URL.$wfmLogNext['picture'] ?>" onerror="onUserLogoError(this);" width="40" height="40" title="<?php echo $wfmLogNext['username']; ?> <?php echo (isset($wfmLogNext['departmentname']) ? '('.$wfmLogNext['departmentname'].')' : '') ?>"/>
                            </div> 
                            <div class="line-height-normal">
                                <div>
                                    <label class="text-default font-weight-bold"><?php echo $wfmLogNext['username']; ?></label>
                                </div>
                                <?php if (isset($wfmLogNext['aliasusername'])) { ?>
                                    <div>
                                        <label class="text-default font-weight-bold">
                                            <?php echo '('.$wfmLogNext['aliasusername'].')'; ?>
                                        </label>
                                    </div>
                                <?php } ?>
                                <span title="<?php echo issetParam($wfmLogNext['departmentname']); ?>">
                                    <label class="text-muted font-size-10"><?php echo isset($wfmLogNext['departmentname']) ? ($wfmLogNext['departmentname']) ? $wfmLogNext['departmentname'] .', ' : '-' : '-'; ?>
                                    </label>
                                </span>
                                <span title="<?php echo issetParam($wfmLogNext['positionname']); ?>">
                                    <label class="text-muted font-size-10"><?php echo isset($wfmLogNext['positionname']) ? ($wfmLogNext['positionname']) ? $wfmLogNext['positionname'] : '-' : '-'; ?>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
    }
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), $this->historyList['message'], true);
}
?>
