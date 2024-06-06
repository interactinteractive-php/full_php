<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_process'); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId . '|' . Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId" name="processId" type="hidden" value="<?php echo Arr::get($this->card, 'PROCESS_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->card, 'PROCESS_META_DATA_CODE'); ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->card, 'PROCESS_META_DATA_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_text'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'text',
                                'id' => 'text',
                                'class' => 'form-control',
                                'value' => isset($this->card['TEXT']) ? $this->card['TEXT'] : '',
                                'placeholder' => $this->lang->line('metadata_text'),
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_text_css'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::textArea(
                            array(
                                'name' => 'textCss',
                                'id' => 'textCss',
                                'class' => 'form-control',
                                'value' => isset($this->card['TEXT_CSS']) ? $this->card['TEXT_CSS'] : '',
                                'placeholder' => 'Advanced feature: Only CSS syntax...',
                                'style' => 'font-family: monospace;background-color: #000;color: #00ff00',
                                'title' => 'Advanced feature: Only CSS syntax...',
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_text_align'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'textAlign',
                            'id' => 'textAlign',
                            'class' => 'form-control',
                            'value' => isset($this->card['TEXT_ALIGN']) ? $this->card['TEXT_ALIGN'] : 'R',
                            'placeholder' => 'Текст зэрэгцүүлэлт',
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_link'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'url',
                            'id' => 'url',
                            'class' => 'form-control',
                            'value' => isset($this->card['URL']) ? $this->card['URL'] : '',
                            'placeholder' => 'Дарахад орох линк'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_hide_link'); ?>:</td>
                <td colspan="2">
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isShowUrl',
                                'id' => 'isShowUrl',
                                'class' => 'form-control',
                                'value' => '1',
                                'saved_val' => isset($this->card['IS_SHOW_URL']) ? $this->card['IS_SHOW_URL'] : ''
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_width'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'width',
                            'id' => 'width',
                            'class' => 'form-control',
                            'value' => isset($this->card['WIDTH']) ? $this->card['WIDTH'] : '280',
                            'placeholder' => 'Утга оруулна уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_height'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'height',
                            'id' => 'height',
                            'class' => 'form-control',
                            'value' => isset($this->card['HEIGHT']) ? $this->card['HEIGHT'] : '150',
                            'placeholder' => 'Тоо оруулана уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('metadata_base_color'); ?>:</td>
                <td colspan="2">
                    <div class="input-group color colorpicker-default" data-color="<?php echo isset($this->card['BGCOLOR']) ? $this->card['BGCOLOR'] : '#FFFFF'; ?>" data-color-format="rgba">
                        <input type="text" name="bgcolor" id="bgcolor" class="form-control" value="<?php echo isset($this->card['BGCOLOR']) ? $this->card['BGCOLOR'] : ''; ?>">
                        <span class="input-group-btn">
                            <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo isset($this->card['BGCOLOR']) ? $this->card['BGCOLOR'] : '#FFFFF'; ?>;"></i>&nbsp;</button>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('add_class'); ?>:</td>
                <td colspan="2">
                    <input type="text" name="addclass" id="addclass" class="form-control" value="<?php echo isset($this->card['ADDCLASS']) ? $this->card['ADDCLASS'] : ''; ?>">
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Font icon:</td>
                <td>
                    <?php // echo Form::hidden(array('name' => 'fontIcon', 'value' => isset($this->card['FONT_ICON']) ? $this->card['FONT_ICON'] : 'fa-comment')); ?>
                    <button id="card-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="5" data-icon="<?php echo isset($this->card['FONT_ICON']) ? $this->card['FONT_ICON'] : 'fa-comment'; ?>" role="iconpicker"></button>
                </td>
                <td>
                    <?php echo Form::text(array('name' => 'fontIcon', 'value' => isset($this->card['FONT_ICON']) ? $this->card['FONT_ICON'] : 'fa-comment')); ?>
                </td>
            </tr>
            <tr class="dataview">
                <td class="left-padding">Data View:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="dataViewId" name="dataViewId" type="hidden" value="<?php echo Arr::get($this->card, 'DATA_VIEW_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->card, 'DATA_VIEW_CODE'); ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->card, 'DATA_VIEW_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="dataViewColumnName">
                        Column name:
                    </label>
                </td>
                <td colspan="2">
                    <?php
                        echo Form::select(
                            array(
                                'name' => 'dataViewColumnName',
                                'id' => 'dataViewColumnName',
                                'class' => 'form-control select2',
                                'data' => '',
                                'op_value' => 'TABLE_NAME',
                                'op_text' => 'TABLE_NAME',
                                'value' => isset($this->card['COLUMN_NAME']) ? $this->card['COLUMN_NAME'] : ''
                            )
                        );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="aggregateName">
                        Aggregate name:
                    </label>
                </td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                            array(
                                'name' => 'setColumnAggregate',
                                'id' => 'setColumnAggregate',
                                'class' => 'form-control form-control-sm select2',
                                'data' => array(
                                    array(
                                        'code' => 'sum',
                                        'name' => $this->lang->line('META_00031')
                                    ),
                                    array(
                                        'code' => 'avg',
                                        'name' => $this->lang->line('META_00157')
                                    ),
                                    array(
                                        'code' => 'min',
                                        'name' => $this->lang->line('META_00078')
                                    ),
                                    array(
                                        'code' => 'max',
                                        'name' => $this->lang->line('META_00184')
                                    ),
                                    array(
                                        'code' => 'count',
                                        'name' => $this->lang->line('metadata_count')
                                    ),
                                    array(
                                        'code' => 'limit',
                                        'name' => $this->lang->line('metadata_view_last_value')
                                    )
                                ),
                                'op_value' => 'code',
                                'op_text' => 'name',
                                'value' => isset($this->card['AGGREGATE_NAME']) ? $this->card['AGGREGATE_NAME'] : '',
                            )
                    );
                    ?> 
                </td>
            </tr>
            <tr class="chartdataview">
                <td class="left-padding">Chart dataView:</td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="chartDataViewId" name="chartDataViewId" type="hidden" value="<?php echo Arr::get($this->card, 'CHART_DATA_VIEW_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->card, 'CHART_DATA_VIEW_CODE'); ?>">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->card, 'CHART_DATA_VIEW_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>  
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="chartType">
                        Chart type:
                    </label>
                </td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'chartType',
                            'id' => 'chartType',
                            'class' => 'form-control form-control-sm select2',
                            'data' => array(
                                array(
                                    'code' => '1',
                                    'name' => 'bar'
                                ),
                                array(
                                    'code' => '2',
                                    'name' => 'graph'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'text' => 'notext',
                            'value' => isset($this->card['CHART_TYPE']) ? $this->card['CHART_TYPE'] : '',
                        )
                    );
                    ?> 
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(function () {
        $('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
        $('#processId').on('change', function () {
            if ($(this).val() !== '') {
                $('#text').attr('readOnly', 'readOnly');
                //  $('#text').val('Процесс утга');
            } else {
                $('#text').removeAttr('readOnly');
                //  $('#text').val('');
                //  $('#text').removeClass('error');
            }
        });
        $('#width').on('change', function () {
            var thisval = $(this).val();
            var percent = thisval.indexOf("%");
            if (percent != -1) {
                var replace = thisval.replace("%", "");
                if (parseInt(replace) <= 100) {

                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: 'Warning',
                        text: '100% их утга оруулж болохгүй.',
                        type: 'warning',
                        sticker: false
                    });
                    $(this).val('');
                }
            }
        });
        $('#card-iconpicker').on('change', function (e) {
            if (e.icon === 'empty' || e.icon === 'fa-empty') {
                $("input[name='fontIcon']").val("");
            } else {
                $("input[name='fontIcon']").val(e.icon);
            }
        });
        var dataViewId = $('#dataViewId').val();
        if (typeof dataViewId != 'undefined' && dataViewId.length != 0) {
            $.ajax({
                type: 'post',
                url: 'mdobject/generateDataviewFields',
                dataType: "json",
                data: {metaDataId: dataViewId},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '#dataViewColumnName'
                    });
                },
                success: function (data) {
                    var options = $("#dataViewColumnName").empty().append($("<option />").val('').text('<?php echo $this->lang->line('choose') ?>'));
                    $.each(data, function () {
                        options.append($("<option />").val(this.FIELD_PATH).text(this.LABEL_NAME));
                    });
                    $('#dataViewColumnName').select2('val', '<?php echo isset($this->card['COLUMN_NAME']) ? $this->card['COLUMN_NAME'] : ''; ?>');

                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
        }

        $('#dataViewId').on('change', function () {
            $.ajax({
                type: 'post',
                url: 'mdobject/generateDataviewFields',
                dataType: "json",
                data: {metaDataId: $(this).val()},
                beforeSend: function () {
                    Core.blockUI({
                        animate: true,
                        target: '#dataViewColumnName'
                    });
                },
                success: function (response) {
                    var options = $("#dataViewColumnName").empty().append($("<option />").val('').text('<?php echo $this->lang->line('choose') ?>'));
                    $.each(response, function () {
                        options.append($("<option />").val(this.FIELD_PATH).text(this.LABEL_NAME));
                    });
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            });
        });
    });
</script>