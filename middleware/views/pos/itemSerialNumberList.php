<?php
if (isset($this->keyList)) {
    $rowData = $this->rowData;
?>
<div style="padding: 0 10px 5px 10px;">
    <div class="row">
        <div class="col-md-5">
            Сериал
        </div>
        <div class="col-md-3 text-right">
            <?php echo $this->lang->line('POS_0156'); ?>
        </div>
        <div class="col-md-4">
            <?php echo $this->lang->line('POS_0157'); ?>
        </div>
    </div>
</div>
<?php
    foreach ($this->keyList as $k => $dtl) {

        if ($k == 0) {
            $activeClass = ' pos-item-serial-row-active';
        } else {
            $activeClass = '';
        }
        
        if ($dtl['isexpired'] == 1) {
            $style = ' pos-item-serial-row-expired';
        } else {
            $style = '';
        }

        $rowData['serialnumber'] = $dtl['serialnumber'];
        $rowData['endqty']       = $dtl['endqty'];
        $rowData['itemkeyid']    = $dtl['itemkeyid'];
?>
<a href="javascript:;" class="pos-item-serial-row<?php echo $activeClass.$style; ?>" data-row="<?php echo htmlentities(json_encode($rowData), ENT_QUOTES, 'UTF-8'); ?>" onclick="posFillItemRowBySerialNumber(this);">
    <div class="row">
        <div class="col-md-5 pos-serial-name">
            <?php echo $dtl['serialnumber']; ?>
        </div>
        <div class="col-md-3 text-right">
            <?php echo $dtl['endqty']; ?>
        </div>
        <div class="col-md-4">
            <?php echo $dtl['enddate']; ?>
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