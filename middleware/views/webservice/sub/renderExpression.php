<div class="row-fluid">
    <div class="col-md-12">
        <?php echo Form::create(array('id'=>'expression-form','method'=>'post')); ?>
        <?php
        if ($this->paramList) {
        ?>
        <div class="table-scrollable table-scrollable-borderless mt0" style="margin-top: 0 !important">
            <table class="table table-striped table-sm">
                <tbody>
                    <?php
                    $rows = array_chunk($this->paramList, 3);
                    foreach ($rows as $columns) {
                    ?>
                    <tr>
                        <?php
                        foreach ($columns as $param) { 
                        ?>
                        <td class="text-right middle">
                            <?php 
                            $labelAttr = array('text' => $param['META_DATA_NAME'], 'for' => $param['META_DATA_CODE']);
                            if ($param['IS_REQUIRED'] == '1') {
                                $labelAttr = array_merge($labelAttr, array('required' => 'required'));
                            }
                            echo Form::label($labelAttr); 
                            ?>
                        </td>
                        <td class="middle">
                            <?php
                            echo Mdwebservice::renderParamControl($this->expressionId, $param, "param[".$param['META_DATA_CODE']."]");
                            ?>  
                        </td>
                        <?php
                        }
                        ?>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>   
        <?php 
        } 
        ?>
        <?php echo Form::hidden(array('name'=>'expressionId','value'=>$this->expressionId)); ?>
        <div id="response-method"></div>
        <?php echo Form::close(); ?>
    </div>
</div>