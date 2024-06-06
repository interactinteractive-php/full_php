<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">Процесс:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="processId" name="processId" type="hidden" value="<?php echo Arr::get($this->donut, 'PROCESS_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->donut, 'PROCESS_META_DATA_CODE'); ?>">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->donut, 'PROCESS_META_DATA_NAME'); ?>">      
                            </span>     
                        </div>
                    </div>
                </td>
            </tr>
            
            <tr>
                <td style="width: 170px" class="left-padding">Хувь:</td>
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'text',
                            'id' => 'text',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['TEXT']) ? $this->donut['TEXT'] : '',
                            'placeholder' => 'Хувь',
                            'maxlength' => 5
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Линк:</td>
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'url',
                            'id' => 'url',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['URL']) ? $this->donut['URL'] : '',
                            'placeholder' => 'Дарахад орох линк'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Тойргийн хэмжээ:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'dimension',
                            'id' => 'dimension',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['DIMENSION']) ? $this->donut['DIMENSION'] : '250',
                            'placeholder' => 'Тоо оруулана уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Фонтын хэмжээ:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'fontsize',
                            'id' => 'fontsize',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['FONTSIZE']) ? $this->donut['FONTSIZE'] : '38',
                            'placeholder' => 'Тоо оруулана уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Зураасын өргөн:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'width',
                            'id' => 'width',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['WIDTH']) ? $this->donut['WIDTH'] : '30',
                            'placeholder' => 'Тоо оруулана уу'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Зураасын өнгө:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'fgcolor',
                            'id' => 'fgcolor',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['FGCOLOR']) ? $this->donut['FGCOLOR'] : '61a9dc'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Суурь өнгө:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'bgcolor',
                            'id' => 'bgcolor',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['BGCOLOR']) ? $this->donut['BGCOLOR'] : 'eee'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">Доторх өнгө:</td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'fill',
                            'id' => 'fill',                            
                            'class' => 'form-control',
                            'value' => isset($this->donut['FILL']) ? $this->donut['FILL'] : 'FFF'
                        )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function() {
    $('#processId').on('change', function(){
        if ($(this).val() !== '') {
            $('#text').attr('readOnly', 'readOnly');
        } else {
            $('#text').removeAttr('readOnly');
        }
    });
});
</script>