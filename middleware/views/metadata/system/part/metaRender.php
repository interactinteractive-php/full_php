<?php
if ($this->metaList) {
    $meta = new Mdmetadata();
    
    foreach ($this->metaList as $metaRow) {
        $rowMeta = $meta->renderMetaRow($metaRow);
?>
<li class="meta <?php echo $rowMeta['META_TYPE_CODE']; ?> isactive-<?php echo $metaRow['IS_ACTIVE']; ?>" id="<?php echo $rowMeta['META_DATA_ID']; ?>" data-id="<?php echo $rowMeta['META_DATA_ID']; ?>" data-folder-id="<?php echo $this->rowId; ?>">	
    <figure class="directory">
        <input type="checkbox" class="notuniform" value="1">
        <a href="javascript:;" data-href="<?php echo $rowMeta['linkHref']; ?>" target="<?php echo $rowMeta['linkTarget']; ?>" class="folder-link" title="<?php echo $rowMeta['META_DATA_NAME']; ?>" ondblclick="<?php echo $rowMeta['linkOnClick']; ?>">
            <div class="img-precontainer">
                <div class="img-container directory"><span></span>
                    <img class="directory-img" src="<?php echo $rowMeta['BIG_ICON']; ?>"/>
                </div>
            </div>
            <div class="img-precontainer-mini directory">
                <div class="img-container-mini">
                    <span></span>
                    <img class="directory-img" src="<?php echo $rowMeta['SMALL_ICON']; ?>"/>
                </div>
            </div>
            <div class="box">
                <h4 class="ellipsis"><?php echo $rowMeta['META_DATA_NAME']; ?></h4>
            </div>
        </a>	
        <div class="file-code file-code-main"><?php echo $rowMeta['META_DATA_CODE']; ?></div>
        <div class="file-date"><?php echo Date::formatter($rowMeta['CREATED_DATE'], 'Y/m/d H:i'); ?></div>
        <div class="file-user"><?php echo $rowMeta['CREATED_PERSON_NAME']; ?></div>
    </figure>
</li>
<?php
    }
}
?>