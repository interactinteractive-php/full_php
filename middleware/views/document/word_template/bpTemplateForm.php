<?php
foreach ($this->bpTemplateMapData as $row) { ?>
    <a href="javascript:;" style="color:#fff;" class="btn btn-sm btn-primary renderBpTemplate" data-metadataid="<?php echo $row['META_DATA_ID']; ?>" data-templateCode="<?php echo $row['TEMPLATE_CODE']; ?>"><?php echo $row['TEMPLATE_NAME']; ?></a>
<?php }
?>