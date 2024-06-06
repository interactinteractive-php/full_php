<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'columnAttributes-form', 'method' => 'post')); ?>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Param name', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-8">
                <p class="form-control-plaintext font-weight-bold"><?php echo $this->params['paramName']." /".$this->params['paramPath']."/"; ?></p>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00048'), 'for' => 'setColumnWidth', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php echo Form::text(array('name' => 'setColumnWidth', 'value'=>$this->params['columnWidth'], 'id' => 'setColumnWidth', 'class'=>'form-control form-control-sm')); ?>
                <p class="form-text">Жишээ нь: 150px</p>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00124'), 'for' => 'setColumnAggregate', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::select(
                    array(
                        'name' => 'setColumnAggregate',
                        'id' => 'setColumnAggregate', 
                        'class' => 'form-control form-control-sm',
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
                            )
                        ), 
                        'op_value' => 'code', 
                        'op_text' => 'name', 
                        'value' => $this->params['columnAggregate'], 
                        'style' => 'width: 148px;'
                    )
                ); 
                ?> 
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>