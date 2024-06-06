<?php
if ($this->processIconList) {
?>
<div class="row">
    <?php
        $path = 'photo';
        foreach ($this->processIconList as $icon) {
            
            $bannerPath = 'assets/custom/addon/img/process_content/'. $icon['CONTENT_TYPE'] .'/' . $icon['CONTENT_DATA'];
            $bannerPath = (strpos($icon['CONTENT_DATA'], UPLOADPATH) !== false) ? $icon['CONTENT_DATA'] : $bannerPath;
    ?>
    <div class="col-sm-4 col-md-2 bannerItem">
        <a 
            href="javascript:;"
            class="thumbnail view-icon" 
            onclick="processIconSelect('<?php echo $icon['CONTENT_ID']; ?>', '<?php echo $icon['CONTENT_TYPE']; ?>', '<?php echo $icon['CONTENT_DATA']; ?>', '<?php echo $icon['CONTENT_NAME']; ?>', '<?php echo $bannerPath ?>');" 
            data-id="<?php echo $icon['CONTENT_ID']; ?>"
            data-type="<?php echo $icon['CONTENT_TYPE']; ?>"
            data-name="<?php echo $icon['CONTENT_DATA']; ?>"
            data-filepath="<?php echo $bannerPath; ?>"
            style="height: 65px; width: 65px; overflow: hidden;">
            <img src="<?php echo $bannerPath; ?>" style="height: 65px; width: 65px; display: block;">
        </a>
    </div>
    <?php
        }
    ?>
</div>
<?php
} else {
    echo "No icon!";
}
?>