<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr class="dataview">
              <td style="width: 170px;" class="left-padding"><?php echo $this->lang->line('META_00046'); ?></td>
                <td class="pl10">
                    <span id="dataview-name"><?php echo isset($this->calRow['TARGET_META_DATA_NAME']) ? $this->calRow['TARGET_META_DATA_NAME'] : ''; ?></span>
                </td>
                <td style="width: 15px; text-align: right">
                    <a href="javascript:;" class="btn btn-sm purple-plum mr0" onclick="commonMetaDataGrid('single', 'metaMenu', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId;?>', 'dataviewChoose');">...</a>
                    <a href="javascript:;" class="btn btn-sm default ml0" onclick="removeDataview(this);"><i class="icon-cross2 font-size-12"></i></a>
                    <?php echo Form::hidden(array('name' => 'targetMetaDataId', 'id' => 'targetMetaDataId', 'value' => isset($this->calRow['TARGET_META_DATA_ID']) ? $this->calRow['TARGET_META_DATA_ID'] : '')); ?>
                </td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('MET_330477'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'calendarTitle',
                                'id' => 'calendarTitle',
                                'class' => 'form-control',
                                'value' => isset($this->calRow['TITLE']) ? $this->calRow['TITLE'] : '',
                                'placeholder' => $this->lang->line('metadata_insert_text'),
                                'maxlength' => 255,
                                'required' => true,
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendarwidth'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'calendarWidth',
                                'id' => 'calendarWidth',
                                'class' => 'form-control',
                                'value' => isset($this->calRow['WIDTH']) ? $this->calRow['WIDTH'] : '',
                                'placeholder' => '',
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendarheight'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'calendarHeight',
                                'id' => 'calendarHeight',
                                'class' => 'form-control',
                                'value' => isset($this->calRow['HEIGHT']) ? $this->calRow['HEIGHT'] : '',
                                'placeholder' => '',
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_textfontsize'); ?>:</td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                            array(
                                'name' => 'textFontSize',
                                'id' => 'textFontSize',
                                'class' => 'form-control',
                                'value' => isset($this->calRow['TEXT_FONT_SIZE']) ? $this->calRow['TEXT_FONT_SIZE'] : '13px',
                                'placeholder' => '',
                            )
                    );
                    ?>
                </td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendar_title'); ?>:</td>
                <td colspan="2" id="columnParam"></td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendar_start'); ?>:</td>
                <td colspan="2" id="startDate"></td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendar_end'); ?>:</td>
                <td colspan="2" id="endDate"></td>
            </tr>
             <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendar_color'); ?>:</td>
                <td colspan="2" id="color"></td>
            </tr>
            <tr class="">
                <td class="left-padding">Filter group:</td>
                <td colspan="2" id="filterGroup"></td>
            </tr>
            <tr class="dataview">
              <td style="width: 170px;" class="left-padding"><?php echo $this->lang->line('metadata_calendar_linkmeta'); ?>:</td>
                <td class="pl10">
                    <span id="link-metadata-name"><?php echo isset($this->calRow['LINK_META_DATA_NAME']) ? $this->calRow['LINK_META_DATA_NAME'] : ''; ?></span>
                </td>
                <td style="width: 15px; text-align: right">
                    <a href="javascript:;" class="btn btn-sm purple-plum mr0" onclick="commonMetaDataGrid('single', 'metaMenu', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId;?>', 'linkMetaDataChoose');">...</a>
                    <a href="javascript:;" class="btn btn-sm default ml0" onclick="removeLinkMetaData(this);"><i class="icon-cross2 font-size-12"></i></a>
                    <?php echo Form::hidden(array('name' => 'linkMetaDataId', 'id' => 'linkMetaDataId', 'value' => isset($this->calRow['LINK_META_DATA_ID']) ? $this->calRow['LINK_META_DATA_ID'] : '')); ?>
                </td>
            </tr>
            <tr class="">
                <td class="left-padding"><?php echo $this->lang->line('metadata_calendar_defaultinterval'); ?>:</td>
                  <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'defaultIntervalId',
                            'id' => 'defaultIntervalId',
                            'class' => 'form-control select2',
                            'data' => (new Mdcalendar())->getRefTimeIntervalList(),
                            'op_value' => 'ID',
                            'op_text' => 'NAME',
                            'value' => isset($this->calRow['DEFAULT_INTERVAL_ID']) ? $this->calRow['DEFAULT_INTERVAL_ID'] : '1',
                        )
                    );
                    ?> 
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    getColumnsAjax();
    
    function dataviewChoose() {
        var $commonBasketMetaDataGrid = $('#commonBasketMetaDataGrid'),
                metaBasketNum = $commonBasketMetaDataGrid.datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $commonBasketMetaDataGrid.datagrid('getRows');
            $("#dataview-name").html(rows[0]['META_DATA_NAME'] + ' | ' + rows[0]['META_DATA_CODE']);
            $("#targetMetaDataId").val(rows[0]['META_DATA_ID']);
            getColumnsAjax();
        }
    }
    
    function removeDataview(elem) {
        var _row = $(elem).closest("tr");
        _row.find("input[name='targetMetaDataId']").val("");
        _row.find("span#dataview-name").empty();
        
        setColumnParam();
        setStartDate();
        setEndDate();
        setColor();
        setFilterGroup();
    }
    
    function linkMetaDataChoose() {
        var $commonBasketMetaDataGrid = $('#commonBasketMetaDataGrid'),
                metaBasketNum = $commonBasketMetaDataGrid.datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $commonBasketMetaDataGrid.datagrid('getRows');
            $("#link-metadata-name").html(rows[0]['META_DATA_NAME'] + ' | ' + rows[0]['META_DATA_CODE']);
            $("#linkMetaDataId").val(rows[0]['META_DATA_ID']);
        }
    }

    function removeLinkMetaData(elem) {
        var _row = $(elem).closest("tr");
        _row.find("input[name='linkMetaDataId']").val("");
        _row.find("span#link-metadata-name").empty();
    }
    
    function getColumnsAjax() {
        var targetMetaDataId = $('#targetMetaDataId').val();
        var result = null;
        $.ajax({
            url: "mddashboard/getColumnsAjax",
            data: {dataViewId: targetMetaDataId},
            type: "POST",
            dataType: 'json',
            success: function (response) {                
                setColumnParam(response, '<?php echo isset($this->calRow['COLUMN_PARAM_PATH']) ? $this->calRow['COLUMN_PARAM_PATH'] : 'undefined'; ?>');
                setStartDate(response, '<?php echo isset($this->calRow['START_PARAM_PATH']) ? $this->calRow['START_PARAM_PATH'] : 'undefined'; ?>');
                setEndDate(response, '<?php echo isset($this->calRow['END_PARAM_PATH']) ? $this->calRow['END_PARAM_PATH'] : 'undefined'; ?>');
                setColor(response, '<?php echo isset($this->calRow['COLOR_PARAM_PATH']) ? $this->calRow['COLOR_PARAM_PATH'] : 'undefined'; ?>');
                setFilterGroup(response, '<?php echo isset($this->calRow['FILTER_GROUP_PARAM_PATH']) ? $this->calRow['FILTER_GROUP_PARAM_PATH'] : 'undefined'; ?>');
                Core.initAjax();        
            },
            error: function (jqXHR, exception) {
                Core.unblockUI();
            }
        }).complete(function () {
            Core.unblockUI();
        });

        return result;
    }
    
    function setColumnParam(data, defaultValue) {
        var html = '<select id="columnParamPath" name="columnParamPath" class="form-control select2" data-placeholder="- Сонгох -" title=""><option value="">- Сонгох -</option>';
        html += getColumnData(data, defaultValue);
        html += '</select>'; 
        $('#columnParam').html(html);
    }
    
    function setStartDate(data, defaultValue) {
        var html = '<select id="startDatePath" name="startDatePath" class="form-control select2" data-placeholder="- Сонгох -" title=""><option value="">- Сонгох -</option>';
        html += getColumnData(data, defaultValue);
        html += '</select>'; 
        $('#startDate').html(html);        
    }
    
    function setEndDate(data, defaultValue) {
        var html = '<select id="endDatePath" name="endDatePath" class="form-control select2" data-placeholder="- Сонгох -" title=""><option value="">- Сонгох -</option>';
        html += getColumnData(data, defaultValue);
        html += '</select>'; 
        $('#endDate').html(html);
    }
    
    function setColor(data, defaultValue) {
        var html = '<select id="colorPath" name="colorPath" class="form-control select2" data-placeholder="- Сонгох -" title=""><option value="">- Сонгох -</option>';
        html += getColumnData(data, defaultValue);
        html += '</select>'; 
        $('#color').html(html);
    }
    
    function setFilterGroup(data, defaultValue) {
        var html = '<select id="filterGroupPath" name="filterGroupPath" class="form-control select2" data-placeholder="- Сонгох -" title=""><option value="">- Сонгох -</option>';
        html += getColumnData(data, defaultValue);
        html += '</select>'; 
        $('#filterGroup').html(html);
    }
    
    function getColumnData(data, defaultValue) {
        var html = '';
        if (typeof data !== 'undefined') {
            $.each(data, function (key, value) {
                var isSelected = '';
                if(typeof defaultValue !== 'undefined') {
                    if(value.FIELD_PATH === defaultValue) {
                        isSelected = 'selected="selected"';
                    }
                }
                html += '<option value="' + value.FIELD_PATH + '" ' + isSelected + '>' + value.LABEL_NAME + '</option>';
            });
        }
        
        return html;
    }
</script>