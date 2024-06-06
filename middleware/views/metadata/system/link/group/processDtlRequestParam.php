<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<div class="row">
    <div class="col-md-12">
        <?php
        echo Form::create(array('class' => 'form-horizontal', 'id' => 'processDtlCriteria-form', 'method' => 'post'));
        ?>
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#tab_post_param" class="nav-link active" data-toggle="tab">Post Param</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_get_param" data-toggle="tab" class="nav-link">Get Param</a>
                </li>
                <li class="nav-item">
                    <a href="#tab_pass_param" data-toggle="tab" class="nav-link">Other</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_post_param">
                    <?php
                    echo Form::textArea(
                        array(
                            'name' => 'groupProcessDtlPostParam',
                            'id' => 'groupProcessDtlPostParam',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->groupProcessDtlPostParam, 
                            'placeholder' => 'Post Param'
                        )
                    );
                    ?>
                </div>
                <div class="tab-pane" id="tab_get_param">
                    <?php
                    echo Form::textArea(
                        array(
                            'name' => 'groupProcessDtlGetParam',
                            'id' => 'groupProcessDtlGetParam',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->groupProcessDtlGetParam, 
                            'placeholder' => 'Get Param'
                        )
                    );
                    ?>
                </div>
                <div class="tab-pane" id="tab_pass_param">
                    <?php
                    echo '<div class="row mb10"><div class="col-md-4 text-right">Pass path:</div><div class="col-md-4">'.Form::text(
                        array(
                            'name' => 'groupProcessDtlPasswordPath',
                            'id' => 'groupProcessDtlPasswordPath',
                            'class' => 'form-control form-control-sm',
                            'value' => $this->groupProcessDtlPasswordPath
                        )
                    ).'</div></div>';

                    echo '<div class="row mb10"><div class="col-md-4 text-right">Position:</div><div class="col-md-4">'.Form::select(
                        array(
                            'name' => 'groupProcessShowPosition',
                            'id' => 'groupProcessShowPosition',
                            'data' => array(
                                array(
                                    'code' => 'top', 
                                    'name' => 'Top'
                                ),
                                array(
                                    'code' => 'right', 
                                    'name' => 'Right'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control form-control-sm', 
                            'value' => $this->groupProcessShowPosition,
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">IS_BP_OPEN:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlOpenBP',
                            'id' => 'groupProcessDtlOpenBP',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlOpenBP
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">IS_BP_OPEN_DEFAULT:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlOpenBPdefault',
                            'id' => 'groupProcessDtlOpenBPdefault',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlOpenBPdefault
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">Зөвхөн мөр сонгосон үед харуулах:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlIsShowRowSelect',
                            'id' => 'groupProcessDtlIsShowRowSelect',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlIsShowRowSelect
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">Зөвхөн contextmenu дээр харуулах:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlIsContextMenu',
                            'id' => 'groupProcessDtlIsContextMenu',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlIsContextMenu
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">Олноор мөр сонгосон үед процессыг LOOP коммандаар ажиллуулах:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlIsRunLoop',
                            'id' => 'groupProcessDtlIsRunLoop',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlIsRunLoop
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">Render хийсэн процессийн Toolbar дотор ашиглагдах эсэх:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlUseProcessToolbar',
                            'id' => 'groupProcessDtlUseProcessToolbar',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlUseProcessToolbar
                        )
                    ).'</div></div>';
                    
                    echo '<div class="row mb10"><div class="col-md-4 text-right">Render хийсэн процессийн Toolbar ашиглах эсэх:</div><div class="col-md-4">'.Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlProcessToolbar',
                            'id' => 'groupProcessDtlProcessToolbar',
                            'value' => '1',
                            'saved_val' => $this->groupProcessDtlProcessToolbar
                        )
                    ).'</div></div>';
                    ?>
                    <div class="row">
                        <div class="col-md-4 text-right">Icon color:</div>
                        <div class="col-md-4">
                            <div class="input-group color colorpicker-default" data-color="<?php echo $this->groupProcessDtlIconColor; ?>" data-color-format="">
                                <input type="text" name="groupProcessDtlIconColor" id="groupProcessDtlIconColor" class="form-control form-control-sm" value="<?php echo $this->groupProcessDtlIconColor; ?>">
                                <span class="input-group-btn">
                                    <button class="btn default btn-sm colorpicker-input-addon px-1" type="button">
                                        <i style="background-color: <?php echo $this->groupProcessDtlIconColor; ?>;"></i>
                                    </button>
                                </span>
                            </div>                                        
                        </div>                                        
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>
    </div>
</div>    
<script type="text/javascript">
    $(function() {
        $('.colorpicker-default').colorpicker({
            format: 'hex'
        });
    });
</script>