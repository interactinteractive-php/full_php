<tr class="rt-notseen-option<?php echo $this->rowClass; ?>">
    <td class="text-right middle" style="width: 40%">
        <label for="isShowPreview"><?php echo $this->lang->lineDefault('PRINT_0003', 'Хэвлэхээс өмнө харах'); ?>:</label>
    </td>
    <td class="middle" style="width: 60%">
        <?php 
        echo Form::checkbox(
            array(
                'name' => 'isShowPreview', 
                'id' => 'isShowPreview', 
                'class' => 'form-control', 
                'value' => '1', 
                'saved_val' => (isset($this->userRow['isShowPreview']) ? $this->userRow['isShowPreview'] : '1')
            )
        ); 
        ?>
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?>">
    <td class="text-right middle">
        <label><?php echo $this->lang->lineDefault('PRINT_0005', 'Хуудасны байрлал'); ?>:</label>
    </td>
    <td class="middle">
        <?php
        echo Form::select(
            array(
                'name' => 'pageOrientation',
                'id' => 'pageOrientation',
                'data' => array(
                    array(
                        'id' => 'portrait',
                        'name' => $this->lang->lineDefault('PRINT_0006', 'Босоо')
                    ),
                    array(
                        'id' => 'landscape',
                        'name' => $this->lang->lineDefault('PRINT_0007', 'Хэвтээ')
                    )
                ),
                'op_value' => 'id',
                'op_text' => 'name',
                'value' => (isset($this->userRow['pageOrientation']) ? $this->userRow['pageOrientation'] : 'portrait'), 
                'required' => 'required',
                'class' => 'form-control form-control-sm',
                'style' => 'width:55%', 
                'text' => 'notext'
            )
        );
        ?>   
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?>">
    <td class="text-right middle">
        <label><?php echo $this->lang->lineDefault('PRINT_0009', 'Хуудасны хэмжээ'); ?>:</label>
    </td>
    <td class="middle">
        <?php
        echo Form::select(
            array(
                'name' => 'pageSize',
                'id' => 'pageSize',
                'data' => array(
                    array(
                        'id' => 'a3',
                        'name' => 'A3'
                    ),
                    array(
                        'id' => 'a4',
                        'name' => 'A4'
                    ),
                    array(
                        'id' => 'a5',
                        'name' => 'A5'
                    ),
                ),
                'op_value' => 'id',
                'op_text' => 'name',
                'value' => (isset($this->userRow['pageSize']) ? $this->userRow['pageSize'] : 'a4'), 
                'required' => 'required',
                'class' => 'form-control form-control-sm',
                'style' => 'width:55%', 
                'text' => 'notext'
            )
        );
        ?>   
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?>">
    <td class="text-right middle">
        <label for="isPrintSaveTemplate">Сонгосон темплейт хадгалахгүй:</label>
    </td>
    <td class="middle">
        <?php 
        echo Form::checkbox(
            array(
                'name' => 'isPrintSaveTemplate', 
                'id' => 'isPrintSaveTemplate', 
                'class' => 'form-control', 
                'value' => '1', 
                'saved_val' => (isset($this->userRow['isPrintSaveTemplate']) ? $this->userRow['isPrintSaveTemplate'] : '0')
            )
        ); 
        ?>
    </td>
</tr>        
<?php if ($this->templatesCount > 0) { ?>
    <tr>
        <td class="text-right middle" style="width: 40%">
            <label for="isSettingsDialog"><?php echo $this->lang->lineDefault('PRINT_0018', 'Дахин тохиргоо асуухгүй'); ?>:</label>
        </td>
        <td class="middle" style="width: 60%">
            <?php 
            echo Form::checkbox(
                array(
                    'name' => 'isSettingsDialog', 
                    'id' => 'isSettingsDialog', 
                    'class' => 'form-control', 
                    'value' => '1', 
                    'saved_val' => issetParam($this->userRow['isSettingsDialog']) 
                )
            ); 
            ?>
        </td>
    </tr>
<?php } ?>
<tr>
    <td class="text-right middle">
        <a href="javascript:;" class="rt-more-options-showhide"><i class="icon-arrow-up5"></i> Дэлгэрэнгүй</a>
    </td>
    <td></td>
</tr>    
<tr class="rt-notseen-option<?php echo $this->rowClass; ?> rt-more-options" style="display: none">
    <td class="text-right middle">
        <label for="numberOfCopies"><?php echo $this->lang->lineDefault('PRINT_0004', 'Хэвлэх хувь'); ?>:</label>
    </td>
    <td class="middle">
        <?php 
        echo Form::text(
            array(
                'name' => 'numberOfCopies', 
                'id' => 'numberOfCopies', 
                'class' => 'form-control form-control-sm longInit', 
                'style' => 'width:20%', 
                'required' => 'required', 
                'value' => (isset($this->userRow['numberOfCopies']) ? $this->userRow['numberOfCopies'] : '1')
            )
        ); 
        ?>
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?> rt-more-options" style="display: none">
    <td class="text-right middle">
        <label><?php echo $this->lang->lineDefault('PRINT_0008', 'Цаасны оролт'); ?>:</label>
    </td>
    <td class="middle">
        <?php
        echo Form::select(
            array(
                'name' => 'paperInput',
                'id' => 'paperInput',
                'data' => array(
                    array(
                        'id' => 'portrait',
                        'name' => $this->lang->lineDefault('PRINT_0006', 'Босоо')
                    ),
                    array(
                        'id' => 'landscape',
                        'name' => $this->lang->lineDefault('PRINT_0007', 'Хэвтээ')
                    )
                ),
                'op_value' => 'id',
                'op_text' => 'name',
                'value' => (isset($this->userRow['paperInput']) ? $this->userRow['paperInput'] : 'portrait'), 
                'required' => 'required',
                'class' => 'form-control form-control-sm',
                'style' => 'width: 55%', 
                'text' => 'notext'
            )
        );
        ?>   
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?> rt-more-options" style="display: none">
    <td class="text-right middle">
        <label><?php echo $this->lang->lineDefault('PRINT_0010', 'Хэвлэх сонголт'); ?>:</label>
    </td>
    <td class="middle">
        <?php
        echo Form::select(
            array(
                'name' => 'printType',
                'id' => 'printType',
                'data' => array(
                    array(
                        'id' => '2col',
                        'name' => $this->lang->lineDefault('PRINT_0011', 'Нэг мөрөнд 2-оор /Хэвлэх хувь/')
                    ),
                    array(
                        'id' => '2colrow',
                        'name' => $this->lang->lineDefault('PRINT_00111', 'Нэг мөрөнд 2-оор')
                    ), 
                    array(
                        'id' => '1col',
                        'name' => $this->lang->lineDefault('PRINT_0012', 'Нэг мөрөнд 1-ээр')
                    ), 
                    array(
                        'id' => '0col',
                        'name' => $this->lang->lineDefault('PRINT_0013', 'Нэг мөрөнд')
                    )
                ),
                'op_value' => 'id',
                'op_text' => 'name',
                'value' => (isset($this->userRow['printType']) ? $this->userRow['printType'] : '1col'),
                'required' => 'required',
                'class' => 'form-control form-control-sm',
                'style' => 'width: 55%', 
                'text' => 'notext'
            )
        );
        ?>   
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?> rt-more-options" style="display: none">
    <td class="text-right middle">
        <label for="isPrintNewPage"><?php echo $this->lang->lineDefault('PRINT_0002', 'Шинэ хуудсанд хэвлэх'); ?>:</label>
    </td>
    <td class="middle">
        <?php 
        echo Form::checkbox(
            array(
                'name' => 'isPrintNewPage', 
                'id' => 'isPrintNewPage', 
                'class' => 'form-control', 
                'value' => '1', 
                'saved_val' => (isset($this->userRow['isPrintNewPage']) ? $this->userRow['isPrintNewPage'] : '1')
            )
        ); 
        ?>
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?> rt-more-options" style="display: none">
    <td class="text-right middle">
        <label for="isPrintPageRight"><?php echo $this->lang->lineDefault('PRINT_0014', 'Хуудасны баруун талд'); ?>:</label>
    </td>
    <td class="middle">
        <?php 
        echo Form::checkbox(
            array(
                'name' => 'isPrintPageRight', 
                'id' => 'isPrintPageRight', 
                'class' => 'form-control', 
                'value' => '1', 
                'saved_val' => (isset($this->userRow['isPrintPageRight']) ? $this->userRow['isPrintPageRight'] : '0')
            )
        ); 
        ?>
    </td>
</tr>
<tr class="rt-notseen-option<?php echo $this->rowClass; ?> rt-more-options" style="display: none">
    <td class="text-right middle">
        <label for="isPrintPageBottom"><?php echo $this->lang->lineDefault('PRINT_0015', 'Хуудасны доод талд'); ?>:</label>
    </td>
    <td class="middle">
        <?php 
        echo Form::checkbox(
            array(
                'name' => 'isPrintPageBottom', 
                'id' => 'isPrintPageBottom', 
                'class' => 'form-control', 
                'value' => '1', 
                'saved_val' => (isset($this->userRow['isPrintPageBottom']) ? $this->userRow['isPrintPageBottom'] : '0')
            )
        ); 
        ?>
    </td>
</tr>