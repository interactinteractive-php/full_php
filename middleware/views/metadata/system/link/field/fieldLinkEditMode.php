<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="dataType">
                        <?php echo $this->lang->line('metadata_data_type'); ?>
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'dataType',
                            'id' => 'dataType',
                            'data' => (new Mdmetadata())->getMetaFieldDataType(),
                            'op_value' => 'DATA_TYPE_CODE',
                            'op_text' => 'DATA_TYPE_NAME',
                            'class' => 'form-control select2', 
                            'value' => $this->flRow['DATA_TYPE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isShow">
                        <?php echo $this->lang->line('META_00003'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isShow',
                                'id' => 'isShow',
                                'value' => '1', 
                                'saved_val' => $this->flRow['IS_SHOW']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isRequired">
                        <?php echo $this->lang->line('META_00121'); ?>
                    </label>
                </td>
                <td>
                    <div class="checkbox-list">
                        <?php
                        echo Form::checkbox(
                            array(
                                'name' => 'isRequired',
                                'id' => 'isRequired',
                                'value' => '1', 
                                'saved_val' => $this->flRow['IS_REQUIRED']
                            )
                        );
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="minValue">
                        <?php echo $this->lang->line('META_00101'); ?>
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'minValue',
                            'id' => 'minValue',
                            'class' => 'form-control', 
                            'value' => $this->flRow['MIN_VALUE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="maxValue">
                        <?php echo $this->lang->line('META_00182'); ?>
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'maxValue',
                            'id' => 'maxValue',
                            'class' => 'form-control', 
                            'value' => $this->flRow['MAX_VALUE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="defaultValue">
                        <?php echo $this->lang->line('META_00005'); ?>
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'defaultValue',
                            'id' => 'defaultValue',
                            'class' => 'form-control', 
                            'value' => $this->flRow['DEFAULT_VALUE']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="fieldFileExtension"> 
                        Файлын өргөтгөл: 
                        <i class="fa fa-question-circle tooltips" data-original-title="Жишээ нь: jpg, png, gif"></i>
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'fieldFileExtension',
                            'id' => 'fieldFileExtension',
                            'class' => 'form-control', 
                            'value' => $this->flRow['FILE_EXTENSION']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="patternId">
                        <?php echo $this->lang->line('META_00183'); ?>
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'patternId',
                            'id' => 'patternId',
                            'class' => 'form-control select2', 
                            'data' => (new Mdmetadata())->getMetaFieldPattern(),
                            'op_value' => 'PATTERN_ID', 
                            'op_text' => 'PATTERN_NAME', 
                            'value' => $this->flRow['PATTERN_ID']
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><label for="lookupType">Lookup <?php echo $this->lang->line('META_00145'); ?></label></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'lookupType',
                            'id' => 'lookupType',
                            'class' => 'form-control select2', 
                            'data' => array(
                                0 => array(
                                    'id' => 'combo',
                                    'name' => 'Combo'
                                ),
                                1 => array(
                                    'id' => 'popup',
                                    'name' => 'Popup'
                                )
                            ),
                            'op_value' => 'id', 
                            'op_text' => 'name', 
                            'value' => $this->flRow['LOOKUP_TYPE'] 
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><label for="chooseType"><?php echo $this->lang->line('metadata_choose_type'); ?></label></td>
                <td><?php echo $this->chooseTypeCombo; ?></td>
            </tr>
            <tr>
                <td class="left-padding"><label for="lookupMetaDataId">Lookup <?php echo $this->lang->line('META_00133'); ?></label></td>
                <td><?php echo $this->lookupMetaDataCombo; ?></td>
            </tr>
            <tr>
                <td class="left-padding"><label for="displayField">Display field:</label></td>
                <td><?php echo $this->displayFieldCombo; ?></td>
            </tr>
            <tr>
                <td class="left-padding"><label for="valueField">Value field:</label></td>
                <td><?php echo $this->valueFieldCombo; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function(){
    $("select#lookupType").on("change", function(){
        var lookupType = $(this).val();
        if (lookupType !== "") {
            $("select#lookupMetaDataId, select#chooseType, select#displayField, select#valueField").removeAttr("disabled");
        } else {
            $("select#lookupMetaDataId, select#chooseType, select#displayField, select#valueField").attr("disabled", "disabled");
        }
    });
    $("select#lookupMetaDataId").on("change", function(){
        var lookupMetaDataId = $(this).val();
        if (lookupMetaDataId !== "") {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/lookupFieldName',
                data: {lookupMetaDataId: lookupMetaDataId},
                dataType: "json",
                success: function(data){
                    $("select#displayField option:gt(0)").remove();
                    $("select#valueField option:gt(0)").remove();
                    $.each(data.fields, function(){
                        $("select#displayField").append($("<option />").val(this.FIELD_NAME).text(this.FIELD_NAME));
                        $("select#valueField").append($("<option />").val(this.FIELD_NAME).text(this.FIELD_NAME));
                    }); 
                    Core.initSelect2();
                    $("select#displayField, select#valueField").trigger("change");
                },
                error: function(){alert("Error");}
            });
        } else {
            $("select#displayField option:gt(0)").remove();
            $("select#valueField option:gt(0)").remove();
            $("select#displayField, select#valueField").trigger("change");
        }
    });
});    
</script>