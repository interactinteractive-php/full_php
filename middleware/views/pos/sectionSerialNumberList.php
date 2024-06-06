<?php
if (isset($this->keyList)) {
    $rowData = $this->rowData;
?>
<div style="padding: 0 10px 5px 10px;">
    <div class="row">
        <div class="col-md-3 text-left">
            Код
        </div>
        <div class="col-md-6 text-left">
            Нэр
        </div>
        <div class="col-md-3 text-right">
            Худалдах үнэ
        </div>
    </div>
</div>
<?php
    foreach ($this->keyList as $k => $dtl) {
        
        $itemData = $rowData;
        $activeClass = ($k == 0 ? ' pos-item-serial-row-active' : '');

        $itemData['sectionid'] = $dtl['sectionid'];
        
        if (isset($dtl['saleprice']) && $dtl['saleprice']) {
            
            $itemData['saleprice'] = $dtl['saleprice'];
            $itemData['vatprice'] = issetParam($dtl['vatprice']);
            $itemData['vattax'] = issetParam($dtl['vattax']);
        }
?>
<a href="javascript:;" class="pos-item-serial-row<?php echo $activeClass; ?>" data-row="<?php echo htmlentities(json_encode($itemData), ENT_QUOTES, 'UTF-8'); ?>" onclick="posFillItemRowBySerialNumber(this);">
    <div class="row">
        <div class="col-md-3 text-left pos-serial-name">
            <?php echo $dtl['code']; ?>
        </div>
        <div class="col-md-6 text-left">
            <?php echo $dtl['name']; ?>
        </div>
        <div class="col-md-3 text-right">
            <?php echo Number::amount(issetParam($itemData['saleprice'])); ?>
        </div>
    </div>
</a>
<?php
    }
}
?>

<style type="text/css">
a.pos-item-serial-row {
    display: block;
    padding: 5px 10px;
    margin: 0;
    border-top: 1px #ddd solid;
    outline: none;
    color: #222;
}
a.pos-item-serial-row-expired {
    background-color: #f99191;
}
a.pos-item-serial-row:last-child {
    border-bottom: 1px #ddd solid;
}
a.pos-item-serial-row:hover {
    background-color: #8ccef7;
}
a.pos-item-serial-row.pos-item-serial-row-active {
    background-color: #8ccef7;
}
.pos-serial-name {
    font-weight: 700;
}
</style>