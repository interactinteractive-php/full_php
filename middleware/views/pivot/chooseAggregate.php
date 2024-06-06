<?php echo Form::create(array('class' => 'form-horizontal', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row pl20">
        <div class="radio-list">
            <?php
            echo Form::radioMulti(
                array(
                    array(
                        'name' => 'pvFieldAggr', 
                        'value' => 'sum', 
                        'label' => 'Sum'
                    ), 
                    array(
                        'name' => 'pvFieldAggr', 
                        'value' => 'min', 
                        'label' => 'Min'
                    ), 
                    array(
                        'name' => 'pvFieldAggr', 
                        'value' => 'max', 
                        'label' => 'Max'
                    ), 
                    array(
                        'name' => 'pvFieldAggr', 
                        'value' => 'avg', 
                        'label' => 'Average'
                    ), 
                    array(
                        'name' => 'pvFieldAggr', 
                        'value' => 'count', 
                        'label' => 'Count'
                    )
                ), 
                $this->aggrName     
            );
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>
