<?php
foreach ($this->widgetData as $row) {
?>
<div class="float-left mr15 mt8 mb8" style="width: 280px">
    <div class="card card-body p-3 m-0">
        <div class="media">
            <div class="mr-3 align-self-center">
                <i class="<?php echo checkDefaultVal($row['position3'], 'icon-office'); ?> icon-3x" style="color: <?php echo checkDefaultVal($row['position4'], '#5c6bc0'); ?>"></i>
            </div>
            <div class="media-body text-right">
                <h2 class="font-weight-bold mb-0"><?php echo $row['position1']; ?></h2>
                <span class="text-uppercase font-size-sm text-muted"><?php echo $row['position2']; ?></span>
            </div>
        </div>
    </div>
</div>
<?php
}
?>