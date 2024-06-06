<?php
$row = $this->row;

if ($row['groupKeyLookupMeta'] != '' && $row['isShowMultipleKeyMap'] != '0' && $row['isShowAdd'] === '1') {
?>
<div class="bp-detail-row-add bp-add-one-row">
    <?php
    echo Form::button(
        array(
            'data-action-path' => $row['code'], 
            'class' => 'btn bg-grey-300 btn-icon rounded-round',
            'style' => 'width: 40px; height: 40px; background-color: #e0e0e0', 
            'value' => '<i class="icon-plus3 font-size-18"></i>', 
            'title' => $this->lang->line('add_btn'), 
            'onclick' => 'bpAddMainMultiRow_' . $this->methodId . '(this, \'' . $this->methodId . '\', \'' . $row['groupKeyLookupMeta'] . '\', \'\', \'' . $row['paramPath'] . '\', \'autocomplete\');'
        )
    );
    ?>
</div>
<?php
}
?>

<style type="text/css">
    .bpdtl-widget-detail_circle_photo .tbody {
        display: inline;
    }
    .bpdtl-widget-detail_circle_photo .bp-detail-row {
        display: inline-block;
        position: relative;
        padding: 0;
        margin-bottom: 5px;
    }
    .bpdtl-widget-detail_circle_photo .bp-detail-row-add {
        display: inline;
    }
    .bpdtl-widget-detail_circle_photo .bp-detail-row .media {
        margin-top: 0;
    }
    .bpdtl-widget-detail_circle_photo .bp-detail-row .media .rounded-circle {
        width: 42px;
        height: 42px;
    }
    .bpdtl-widget-detail_circle_photo .bp-detail-row .bp-remove-row {
        display: none;
        position: absolute;
        top: -12px;
        right: -2px;
        height: 20px;
        width: 20px;
        border-radius: 100px;
        padding: 0;
    }
    .bpdtl-widget-detail_circle_photo .bp-detail-row:hover .bp-remove-row {
        display: inline-block;
    }
</style>