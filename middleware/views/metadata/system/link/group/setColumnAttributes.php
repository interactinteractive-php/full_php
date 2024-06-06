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
            <?php echo Form::label(array('text'=>'Текстийн өргөн', 'for' => 'setTextWeight', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::select(
                    array(
                        'name' => 'setTextWeight', 
                        'class' => 'form-control form-control-sm',
                        'id' => 'setTextWeight', 
                        'data' => array(
                            array(
                                'code' => 'normal', 
                                'name' => 'Normal'
                            ), 
                            array(
                                'code' => 'bold', 
                                'name' => 'Bold'
                            ), 
                            array(
                                'code' => '600', 
                                'name' => '600'
                            ),
                            array(
                                'code' => '700', 
                                'name' => '700'
                            )
                        ), 
                        'op_value' => 'code', 
                        'op_text' => 'name', 
                        'value' => $this->params['textWeight'], 
                        'style' => 'width: 148px;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Текстийн өнгө', 'for' => 'setTextColor', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-4">
                <div class="input-group color colorpicker-default" data-color="<?php echo $this->params['textColor']; ?>" data-color-format="rgba">
                    <input type="text" name="setTextColor" id="setTextColor" class="form-control" value="<?php echo $this->params['textColor']; ?>" style="width: 118px;">
                    <span class="input-group-btn">
                        <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo $this->params['textColor']; ?>;"></i>&nbsp;</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Дэвсгэр өнгө', 'for' => 'setBgColor', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-4">
                <div class="input-group color colorpicker-default" data-color="<?php echo $this->params['bgColor']; ?>" data-color-format="rgba">
                    <input type="text" name="setBgColor" id="setBgColor" class="form-control" value="<?php echo $this->params['bgColor']; ?>" style="width: 118px;">
                    <span class="input-group-btn">
                        <button class="btn default colorpicker-input-addon px-1" type="button"><i style="background-color: <?php echo $this->params['bgColor']; ?>;"></i>&nbsp;</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Толгой зэрэгцүүлэлт', 'for' => 'setHeaderAlign', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::select(
                    array(
                        'name' => 'setHeaderAlign', 
                        'class' => 'form-control form-control-sm',
                        'id' => 'setHeaderAlign', 
                        'data' => array(
                            array(
                                'code' => 'center', 
                                'name' => 'Center'
                            ), 
                            array(
                                'code' => 'left', 
                                'name' => 'Left'
                            ), 
                            array(
                                'code' => 'right', 
                                'name' => 'Right'
                            )
                        ), 
                        'op_value' => 'code', 
                        'op_text' => 'name', 
                        'value' => $this->params['headerAlign'], 
                        'style' => 'width: 148px;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Утгын зэрэгцүүлэлт', 'for' => 'setBodyAlign', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::select(
                    array(
                        'name' => 'setBodyAlign', 
                        'class' => 'form-control form-control-sm',
                        'id' => 'setBodyAlign', 
                        'data' => array(
                            array(
                                'code' => 'center', 
                                'name' => 'Center'
                            ), 
                            array(
                                'code' => 'left', 
                                'name' => 'Left'
                            ), 
                            array(
                                'code' => 'right', 
                                'name' => 'Right'
                            ), 
                            array(
                                'code' => 'justify', 
                                'name' => 'Justify'
                            )
                        ), 
                        'op_value' => 'code', 
                        'op_text' => 'name', 
                        'value' => $this->params['bodyAlign'], 
                        'style' => 'width: 148px;'
                    )
                ); 
                ?>
            </div>
        </div>
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text'=>'Текст том/жижиг', 'for' => 'setTextTransform', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::select(
                    array(
                        'name' => 'setTextTransform',
                        'id' => 'setTextTransform', 
                        'class' => 'form-control form-control-sm',
                        'data' => array(
                            array(
                                'code' => 'uppercase', 
                                'name' => 'Uppercase'
                            ), 
                            array(
                                'code' => 'lowercase', 
                                'name' => 'Lowercase'
                            ), 
                            array(
                                'code' => 'capitalize', 
                                'name' => 'Capitalize'
                            )
                        ), 
                        'op_value' => 'code', 
                        'op_text' => 'name', 
                        'value' => $this->params['textTransform'], 
                        'style' => 'width: 148px;'
                    )
                ); 
                ?> 
            </div>
        </div>
        <div class="form-group row">
            <?php echo Form::label(array('text'=>$this->lang->line('META_00124'), 'for' => 'setColumnAggregate', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-3">
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
            <div class="col-md-5">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'setAggregateAliasPath',
                        'id' => 'setAggregateAliasPath', 
                        'class' => 'form-control form-control-sm',
                        'value' => $this->params['aggregateAliasPath'], 
                        'placeholder' => 'Өөр талбарын нэгтгэл дүнг авах'
                    )
                ); 
                ?> 
            </div>
        </div>
        <div class="form-group row">
            <?php echo Form::label(array('text'=>$this->lang->line('Текстийн хэмжээ'), 'for' => 'setFontSize', 'class'=>'col-form-label col-md-4')); ?>
            <div class="col-md-7">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'setFontSize',
                        'id' => 'setFontSize', 
                        'class' => 'form-control form-control-sm',
                        'value' => $this->params['fontSize'], 
                        'style' => 'width: 148px;'
                    )
                ); 
                ?> 
            </div>
        </div>
    <?php echo Form::close(); ?>
</div>
<script type="text/javascript">
$(function(){
    $('.colorpicker-default').colorpicker({
        format: 'hex'
    });
});    
</script>