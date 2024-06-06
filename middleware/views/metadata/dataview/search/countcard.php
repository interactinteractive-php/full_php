<?php
if ($this->getIsCountCardData) {
    $count = count($this->getIsCountCardData);
    
?>
<div class="tabbable-line tab-not-padding-top<?php echo ($count == 1 ? ' tabbable-ul-notvisible-forone' : ''); ?>">
    <?php
    $active = 'active';
    $tabeHead = $tabeContent = '';

    foreach ($this->getIsCountCardData as $k => $row) {
        $active = $content = '';
        
        if ($k == 0) { 
            $active = 'active'; 
            $content = (new Mdobject())->renderCountCardByFieldPath($this->metaDataId, $row['FIELD_PATH'], $row['META_TYPE_CODE'], $row['COUNTCARD_THEME'], $row['COUNTCARD_SELECTION'], issetParam($row['JSON_CONFIG']));
        }

        $tabeHead .= '
            <li class="nav-item">
                <a href="#tab_countcard_'.$row['ID'].'" class="nav-link '.$active.'" data-toggle="tab" data-path="'.$row['FIELD_PATH'].'" data-type="'.$row['META_TYPE_CODE'].'" data-theme="'.$row['COUNTCARD_THEME'].'" data-selection="'.$row['COUNTCARD_SELECTION'].'">'.$this->lang->line($row['META_DATA_NAME']).'</a>
            </li>';
        $tabeContent .= '
            <div class="tab-pane '.$active.'" id="tab_countcard_'.$row['ID'].'">
                '.$content.'
            </div>';
    }
    ?>
    <ul class="nav nav-tabs">
        <?php echo $tabeHead; ?>
    </ul>
    <div class="tab-content bg-transparent">
        <?php echo $tabeContent; ?>
    </div>
</div>

<script type="text/javascript">   
$(function(){
    $('a[data-toggle="tab"]', "#object-value-list-<?php echo $this->metaDataId; ?> div.dataview-search-filter").on('shown.bs.tab', function(e){
        var $this = $(e.target);
        var _href = $this.attr("href").split("_");
        var contentId = _href[2];
        if (contentId === 'search') {
            $.ajax({
                type: 'post',
                url: 'mdobject/renderDataViewSearchForm',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                beforeSend: function(){
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (dataHtml) {
                    $('div#tab_countcard_'+contentId, "#object-value-list-<?php echo $this->metaDataId; ?> div.dataview-search-filter").html(dataHtml);
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                    Core.unblockUI();
                }
            });
        } else {
            var _fieldPath = $this.attr('data-path'), fieldType = $this.attr('data-type'), 
                cardTheme = $this.attr('data-theme'), cardSelection = $this.attr('data-selection');
                
            $.ajax({
                type: 'post',
                url: 'mdobject/renderCountCardByPost',
                data: {
                    metaDataId: '<?php echo $this->metaDataId; ?>', 
                    fieldPath: _fieldPath, 
                    fieldType: fieldType, 
                    cardTheme: cardTheme, 
                    cardSelection: cardSelection, 
                    defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", 'div#object-value-list-<?php echo $this->metaDataId; ?>').serialize()
                },
                beforeSend: function(){
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (dataHtml) {
                    var $tabCardArea = $('div#tab_countcard_'+contentId, "#object-value-list-<?php echo $this->metaDataId; ?> div.dataview-search-filter");
                    $tabCardArea.empty().append(dataHtml);
                    $tabCardArea.find('a[data-default-active="true"]').click();
                    
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                    Core.unblockUI();
                }
            });
        }
    });
    
    setTimeout(function(){
        $('#object-value-list-<?php echo $this->metaDataId; ?> div.dataview-search-filter').find('a[data-default-active="true"]').click();
    }, 100);
});    
</script>
<?php
} else {
    echo html_tag("div", array('class' => 'alert alert-info'), 'Is card тохиргоо хийгдээгүй байна.');
}
?>