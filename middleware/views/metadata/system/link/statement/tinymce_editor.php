<div class="row columns">
    <div class="col-md-9">  
        <div class="editor">
            <textarea name="tempEditor" id="tempEditor">
                <div class="tinymce-page tinymce-page-<?php echo $this->pageSize; ?>-<?php echo $this->pageOrientation; ?>">
                    <div class="tinymce-page-margin" style="padding-top: <?php echo $this->pageMarginTop; ?>px;padding-left: <?php echo $this->pageMarginLeft; ?>px;padding-right: <?php echo $this->pageMarginRight; ?>px;padding-bottom: <?php echo $this->pageMarginBottom; ?>px;">
                        <div class="tinymce-page-border" style="min-height: <?php echo $this->pageInnerHeight; ?>px">
                            <?php echo $this->htmlContent; ?>
                        </div>    
                    </div>    
                </div>     
            </textarea>
        </div>
    </div>
    <div class="col-md-3">  
        <div class="report-tags" style="max-height: 450px; overflow: auto">
            <?php
            if ($this->metaList) {
            ?>
                <p class="meta-title">Баганууд</p>
                <ul id="metas">
                    <?php
                    $filterArray = array();
                    foreach ($this->metaList as $value) {
                        if ($value['IS_SELECT'] == 1) {
                    ?>
                    <li title="<?php echo $this->lang->line($value['LABEL_NAME']); ?>" class="pl10">#<?php echo strtolower($value['FIELD_PATH']); ?>#</li> 
                    <?php
                        }
                        if ($value['IS_CRITERIA'] == 1) {
                            array_push($filterArray, $value);
                        }
                    }
                    ?>
                </ul>
                <?php
                if (!empty($filterArray)) {
                ?>
                <p class="filter-title"><?php echo $this->lang->line('META_00193'); ?></p>
                <ul id="filters">
                    <?php
                    foreach ($filterArray as $filterValue) {
                    ?>
                        <li title="<?php echo $this->lang->line($filterValue['LABEL_NAME']); ?>" class="pl10">#<?php echo strtolower($filterValue['FIELD_PATH']); ?>#</li> 
                    <?php
                    }
                    ?>
                </ul>
            <?php
                }
            }
            ?>
            <p class="consts-title">Тогтмолууд</p>
            <ul id="constants">
                <?php
                foreach ($this->sysKeywords as $sysKeyword) {
                    if ($sysKeyword['KEY_TYPE'] == 'sys' || $sysKeyword['KEY_TYPE'] == 'session') {
                ?>
                <li class="pl10" title="<?php echo $sysKeyword['META_DATA_NAME']; ?>">#<?php echo $sysKeyword['META_DATA_CODE']; ?>#</li>
                <?php
                    }
                }
                ?>
            </ul>
            <p class="consts-title">Тохиргооны утгууд</p>
            <ul id="configvalues">
                <?php
                foreach ($this->sysKeywords as $configValueKeyword) {
                    if ($configValueKeyword['KEY_TYPE'] == 'config') {
                ?>
                <li class="pl10" title="<?php echo $configValueKeyword['META_DATA_NAME']; ?>">#<?php echo $configValueKeyword['META_DATA_CODE']; ?>#</li>
                <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $(".report-tags").on("dblclick", "li", function(){
        tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
    });
    
    $('html, body').animate({scrollTop: 0}, 0);   
});
</script>