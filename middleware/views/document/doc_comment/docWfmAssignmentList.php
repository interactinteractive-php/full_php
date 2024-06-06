<?php 
if (isset($this->getRow['dm_wfm_assignment_dv']) && $this->getRow['dm_wfm_assignment_dv']) {
    foreach ($this->getRow['dm_wfm_assignment_dv'] as $key => $row) { 
?>
    <li class="media border-bottom-1 border-gray pb-1 mb-1" id="prepDivAudio_<?php echo $row['commentaudio']; ?>">
        <div class="mr-2">
            <img src="assets/custom/img/user.png" class="rounded-circle" width="36" height="36" alt="" data-popup="tooltip">
        </div>
        <div class="media-body">
            <div class="d-flex align-items-center justify-content-between">
                <span class="line-height-normal">
                    <a href="javascript:void(0);">
                        <?php echo $row['username']; ?>
                    </a>
                </span>

                <?php if ($row['commentaudio'] != '0') { ?>
                    <span class="text-center">
                        <a id="prepAtAudio_<?php echo $row['commentaudio']; ?>" onclick="playAudioComment('<?php echo $row['commentaudio']; ?>')">
                            <i class="fa fa-play-circle-o fa-2x" style="color: dodgerblue;"></i>
                        </a>
                    </span>
                <?php } ?>

                <span class="line-height-normal">
                    <a href="javascript:void(0);">
                        <?php echo $row['username2']; ?>
                    </a>
                </span>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <span title="Хүлээн авсан" class="font-size-12 text-muted mt2">
                    <?php if ($key != 0) { ?>
                        <i class="fa fa-check" style="font-size:16px;color:mediumseagreen"></i> <?php echo $row['dat1']; ?>
                    <?php } ?>
                    </span>
                <?php if (!empty($row['username2'])) { ?>
                <span title="Шилжүүлсэн" class="font-size-12 text-muted mt2">
                    <i class="fa fa-arrow-right" style="font-size:16px;color:mediumseagreen"></i> <?php echo $row['userstatusdate']; ?>
                </span>
                <?php } ?>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                <span class="font-size-12 text-muted line-height-normal d-flex flex-column">
                    <span>
                        <a href="javascript:void(0);">
                            <?php echo $row['assignedtablename0']; ?>
                        </a> <?php echo $row['assignedtablename']; ?>
                    </span>
                    <p>
                    <?php echo $row['assignedtablename2']; ?>
                    </p>
                </span>
            </div>
        </div>
    </li>
<?php 
    }
} 
?>