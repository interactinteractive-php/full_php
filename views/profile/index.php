<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row-fluid">
    <div class="span12">  
        <div class="card box blue">
           <div class="card-title">
              <h4><i class="icon-user"></i> <?php echo $this->lang->line('profile_title'); ?></h4>
              <div class="tools">
                 <a href="javascript:;" class="collapse"></a>
              </div>
           </div>
           <div class="card-body form">
               <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'addCustomer-form', 'method' => 'post')); ?>     
               <div class="row-fluid">
                    <div class="span8">
                        <div class="control-group">
                            <?php echo Form::label(array('text'=>$this->lang->line('lname'),'class'=>'col-form-label')); ?>
                            <div class="controls">
                               <span class="text font-weight-bold"><?php echo $this->row['LAST_NAME']; ?></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo Form::label(array('text'=>$this->lang->line('fname'),'class'=>'col-form-label')); ?>
                            <div class="controls">
                               <span class="text font-weight-bold"><?php echo $this->row['FIRST_NAME']; ?></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo Form::label(array('text'=>$this->lang->line('register_number'),'class'=>'col-form-label')); ?>
                            <div class="controls">
                               <span class="text font-weight-bold"><?php echo $this->row['STATE_REG_NUMBER']; ?></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo Form::label(array('text'=>$this->lang->line('user_name'),'class'=>'col-form-label')); ?>
                            <div class="controls">
                               <span class="text font-weight-bold"><?php echo $this->row['USERNAME']; ?></span>
                            </div>
                        </div>
                    </div>
                </div> 
                <?php echo Form::close(); ?> 
           </div>
        </div>
    </div>
</div>