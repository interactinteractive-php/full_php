<div class="col-md-12">
    <div class="tabbable-line">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a aria-expanded="false" href="#setlayouttab_1" class="nav-link active" data-toggle="tab">Жагсаалт</a>
            </li>
            <li class="nav-item">
                <a aria-expanded="false" href="#setlayouttab_2" data-toggle="tab" class="nav-link">Хүснэгт</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="setlayouttab_1">
                <div class="table-scrollable">
                    <table class="table table-hover table-small-header-text" id="layout-cell-<?php echo $this->layoutId."-".$this->metaDataId; ?>">
                        <thead>
                            <tr>
                                <th style="width: 5px">№</th>
                                <th style="width: 150px">Нүд (Багана : Мөр)</th>
                                <th>Үзүүлэлт</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($this->cellDatas) {
                                foreach ($this->cellDatas as $k=>$row) {
                            ?>
                            <tr>
                                <td class="middle"><?php echo ($k+1); ?>.</td>
                                <td class="middle font-weight-bold"><?php echo $row['COL_ID']." : ".$row['ROW_ID']; ?></td>
                                <td class="middle">
                                    <?php
                                    echo Form::select(
                                        array(
                                            'name' => 'contentCellId['.$row['CELL_ID'].'][]',
                                            'id' => 'contentCellId',
                                            'class' => 'form-control select2 form-control-sm', 
                                            'data' => $this->metaDatas,
                                            'op_value' => 'META_DATA_ID', 
                                            'op_text' => 'META_DATA_NAME',  
                                            'style' => 'width: 450px' 
                                        )
                                    );
                                    ?>
                                </td>
                            </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>  
                </div>
            </div>
            <div class="tab-pane" id="setlayouttab_2">
                <?php echo $this->contentLayout; ?>
            </div>
        </div>
    </div>       
</div>

<script type="text/javascript">
$(function(){
    notLayoutCellDuplicate<?php echo $this->layoutId.$this->metaDataId; ?>();
    $("table#layout-cell-<?php echo $this->layoutId."-".$this->metaDataId; ?>").on("change", "select", function(){
        $("table#layout-cell-<?php echo $this->layoutId."-".$this->metaDataId; ?>")
        .find("select")
        .find("option.d-none")
        .removeAttr('class');
        notLayoutCellDuplicate<?php echo $this->layoutId.$this->metaDataId; ?>();
    });
}); 
function notLayoutCellDuplicate<?php echo $this->layoutId.$this->metaDataId; ?>(){
    var _table = $("table#layout-cell-<?php echo $this->layoutId."-".$this->metaDataId; ?>");
    $("tbody tr", _table).each(function(i, v){
        var _select = $(this).find("select");
        var _value = _select.val();
        if (_value !== "") {
            $("tbody tr", _table).each(function(n, w){
                var _select2 = $(this).find("select");
                if (n !== i) {
                    $('option[value=' + _value + ']', _select2).addClass('d-none');
                }
            });
        }
    });
    Core.initSelect2();
}
</script>