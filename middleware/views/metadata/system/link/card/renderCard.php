<?php
$align = ($this->card['TEXT_ALIGN'] == 'R') ? 'right' : 'left';
$smallIcon = ($this->card['META_ICON_NAME'] != '' && $this->card['META_ICON_NAME'] != null && $this->card['META_ICON_NAME'] != 'null') ? 'assets/core/global/img/metaicon/small/' . $this->card['META_ICON_NAME'] : '';

$cardResult = str_replace('[value]', $this->card['CARD_RESULT'], $this->card['TEXT']);
$explodeData = explode(':', $cardResult);
?>

<div class="col-md-12 pl0 pr0">
    <?php
    if ($this->card['URL']) {
        if (Config::getFromCache('CONFIG_MULTI_TAB')) {
            if (isset($this->card['URL']) && !empty($this->card['URL'])) {
                if (strpos($this->card['URL'], 'clickmenuid') === false) {
                    $metaDataId = str_replace("/", '_', $this->card['URL']);
                    $title = isset($this->card['TEXT_FROM_SERVICE']) ? $this->card['TEXT_FROM_SERVICE'] : (($explodeData[0]) ? $explodeData[0] : $this->card['TEXT']);
                    ?>
                    <a href="javascript:;" onclick="appMultiTab({weburl: '<?php echo $this->card['URL'] ?>', metaDataId: '<?php echo $metaDataId ?>', title: '<?php echo $title ?>', type: 'selfurl'})" class="more card-more" style="background-color: <?php echo $this->card['BGCOLOR'] ?>; text-transform: none;">
                <?php } else {
                    $exploded = explode('=', $this->card['URL']); ?>
                    <a href="javascript:;" onclick="$('a[data-meta-data-id=<?php echo $exploded[1]; ?>]').trigger('click');" class="more card-more" style="background-color: <?php echo $this->card['BGCOLOR'] ?>;text-transform: none;">
                    <?php
                }
            } else {
            ?>
            <a href="javascript:;" class="more card-more" style="background-color: <?php echo $this->card['BGCOLOR'] ?>;text-transform: none;">
                <?php
            }
        } else { ?>
            <a href="<?php echo $this->card['URL'] ?>" class="more card-more" style="background-color: <?php echo $this->card['BGCOLOR'] ?>;text-transform: none;">Цааш нь <i class="m-icon-swapright m-icon-white"></i>
            <?php
        }
    }
    ?>
    <div class="card border-0 px-3 mb-0 <?php //echo $this->card['ADDCLASS'] ?> card_new" data-card-meta-data-id="<?php echo $this->card['META_DATA_ID']; ?>" style="background-color: <?php echo $this->card['BGCOLOR'] ?>">
        <div class="card-body pt-0 d-flex justify-content-between align-items-center">
            <p class="mb-0 small-font text-two-line font-weight-semibold" style="<?php echo $this->card['TEXT_CSS']; ?>"><?php echo $this->lang->line(trim(isset($this->card['TEXT_FROM_SERVICE']) ? $this->card['TEXT_FROM_SERVICE'] : (($explodeData[0]) ? $explodeData[0] : $this->card['TEXT']))); ?></p>
            <h5 class="mb-0"><?php echo isset($this->card['ROW_COUNT']) ? (isset($explodeData[1]) ? $explodeData[1] . (isset($explodeData[2]) ? ': ' . $explodeData[2] : '') : $this->card['ROW_COUNT']) : ''; ?></h5>
        </div>
    </div>
</div>

<script type="text/javascript">
    if (!$("link[href='middleware/assets/css/card/card.css']").length) {
        $("head").prepend('<link rel="stylesheet" type="text/css" href="middleware/assets/css/card/card.css"/>');
    }
</script>