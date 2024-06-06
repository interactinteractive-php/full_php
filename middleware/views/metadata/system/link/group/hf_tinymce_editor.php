<div class="row columns">
    <div class="col-md-9">  
        <div class="editor">
            <strong>Header</strong>
            <textarea id="tempHeader" class="tempEditor"><?php echo $this->dvExportHeader; ?></textarea>
            <br />
            <strong>Footer</strong>
            <textarea id="tempFooter" class="tempEditor"><?php echo $this->dvExportFooter; ?></textarea>
        </div>
    </div>
    <div class="col-md-3">  
        <div class="report-tags" style="max-height: 450px; overflow: auto">
            <?php
            if (!empty($this->metaList)) {
            ?>
                <p class="filter-title"><?php echo $this->lang->line('META_00193'); ?></p>
                <ul id="filters">
                    <?php
                    foreach ($this->metaList as $value) {
                        if ($value['IS_CRITERIA'] == 1) {
                    ?>
                        <li title="<?php echo $this->lang->line($value['LABEL_NAME']); ?>" class="pl10">#<?php echo strtolower($value['FIELD_PATH']); ?>#</li> 
                    <?php
                        }
                    }
                    ?>
                </ul>
            <?php
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
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window, .moxman-window").length) {
        e.stopImmediatePropagation();
    }
});
                  
$(function() {
    
    $(".report-tags").on("dblclick", "li", function(){
        tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
    });
    
    $('html, body').animate({scrollTop: 0}, 0);
});
</script>