<div class="col-md-12 xs-form">
    <table class="table table-hover" style="border-bottom: 1px solid #ddd;">
        <tbody>
            <?php
            if (!empty($this->array)) {
                foreach ($this->array as $row) {
            ?>
            <tr>
                <td class="middle"><?php echo $row['message']; ?></td>
                <td class="middle text-right">
                    <?php
                    echo Form::button(
                        array(
                            'type' => 'button', 
                            'class' => 'btn btn-primary', 
                            'value' => '<i class="fa fa-arrow-circle-right"></i>', 
                            'onclick' => 'bpChooseMetaCaller(this, {srcMetaCode: \''.$this->methodCode.'\', metaDataId: \''.$row['metaDataId'].'\', typeCode: \''.$row['metaTypeCode'].'\', metaDataName: \''.$row['metaDataName'].'\', isDefaultGet: \''.$row['defaultGet'].'\'}, \''.$row['mappingParams'].'\');'
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