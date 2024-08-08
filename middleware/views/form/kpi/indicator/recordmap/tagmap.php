<div class="pf-mv-tagmap" id="pf-mv-tagmap-<?php echo $this->uniqId; ?>">
    <div class="pf-mv-tagmap-title">Tag</div>
    <button type="button" class="btn btn-outline bg-grey-300 border-grey-300 text-grey-300 btn-icon rounded-round mr-1">
        <i class="far fa-plus font-size-16"></i>
    </button>
    
    <?php
    $delete_btn = $this->lang->line('delete_btn');
    foreach ($this->savedTagMapRows as $tagMapRow) {
    ?>
    <span class="badge badge-primary badge-pill bg-success-400 font-size-14 pl-2 pr-2 mr-1" data-tagid="<?php echo $tagMapRow['TAG_ID']; ?>" style="background-color:<?php echo $tagMapRow['VIEW_COLOR']; ?>">
        <?php echo $tagMapRow['NAME']; ?>
        <input type="hidden" name="pfMvTagMap[tagId][]" value="<?php echo $tagMapRow['TAG_ID']; ?>">
        <input type="hidden" name="pfMvTagMap[rowState][]" value="saved">
        <input type="hidden" name="pfMvTagMap[id][]" value="<?php echo $tagMapRow['ID']; ?>">
        <a href="javascript:;" class="btn red btn-xs bp-remove-row" title="<?php echo $delete_btn; ?>"><i class="icon-cross3"></i></a>
    </span>
    <?php
    }
    ?>
</div>

<style type="text/css">
.pf-mv-tagmap {
    padding-left: 35px;
    padding-right: 35px;
}    
.pf-mv-tagmap .pf-mv-tagmap-title {
    font-size: 14px;
    font-weight: normal;
    border-bottom: 1px #eee solid;
    padding: 5px 0;
    margin-bottom: 10px;
}
.pf-mv-tagmap button.btn {
    width: 28px;
    height: 28px;
    margin-top: -4px;
    padding: 5px;
}
.pf-mv-tagmap .badge {
    position: relative;
}
.pf-mv-tagmap .badge .bp-remove-row {
    display: none;
    position: absolute;
    top: -8px;
    right: -8px;
    height: 20px;
    width: 20px;
    border-radius: 100px;
    padding: 0;
}
.pf-mv-tagmap .badge:hover .bp-remove-row {
    display: inline-block;
    z-index: 99;
}
</style>

<script type="text/javascript">
$(function(){
    $('#pf-mv-tagmap-<?php echo $this->uniqId; ?>').on('click', 'button.btn', function() {
        chooseKpiIndicatorRowsFromBasket(this, '17211930402843', 'multi', 'mvRowsTagMapFill');
    });
    $('#pf-mv-tagmap-<?php echo $this->uniqId; ?>').on('click', 'a.bp-remove-row', function() {
        var $this = $(this), $parent = $this.closest('.badge'), $rowState = $parent.find('input[name="pfMvTagMap[rowState][]"]');
        if ($rowState.val() == 'added') {
            $parent.remove();
        } else {
            $parent.addClass('d-none');
            $rowState.val('removed');
        }
    });
});
function mvRowsTagMapFill(elem, indicatorId, rows, idField, codeField, nameField, chooseType) {
    var $this = $(elem), $parent = $this.closest('.pf-mv-tagmap'), $tagMap = $parent.find('[data-tagid]'), tagMapLength = $tagMap.length;
    var delete_btn = plang.get('delete_btn');
    
    for (var r in rows) {
        var isAdd = true;
        var tagId = rows[r]['ID'];
    
        if (tagMapLength) {
            var l = 0;
            for (l; l < tagMapLength; l++) { 
                var $thisTag = $($tagMap[l]);
                if ($thisTag.attr('data-tagid') == tagId) {
                    isAdd = false;
                }
            }
        }

        if (isAdd) {
            var addTag = [];
            
            addTag.push('<span class="badge badge-primary badge-pill bg-success-400 font-size-14 pl-2 pr-2 mr-1" data-tagid="'+tagId+'" style="background-color:'+rows[r]['VIEW_COLOR']+'">');
                addTag.push(rows[r]['NAME']);
                addTag.push('<input type="hidden" name="pfMvTagMap[tagId][]" value="'+tagId+'">');
                addTag.push('<input type="hidden" name="pfMvTagMap[rowState][]" value="added">');
                addTag.push('<input type="hidden" name="pfMvTagMap[id][]">');
                addTag.push('<a href="javascript:;" class="btn red btn-xs bp-remove-row" title="'+delete_btn+'"><i class="icon-cross3"></i></a>');
            addTag.push('</span>');
            
            $parent.append(addTag.join(''));
        }
    }
}
</script>    