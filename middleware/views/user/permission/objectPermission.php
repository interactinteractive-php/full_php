<table class="table table-advance table-bordered table-striped table-hover" id="mdObj-<?php echo $this->rowObj['OBJECT_ID']; ?>">
    <thead>
        <tr>
            <th>
                <?php 
                echo Html::anchor('javascript:;', '<i class="icon-user"></i>', 
                        array(
                            'class' => 'btn mini green', 
                            'title' => $this->lang->line('udep_user_choose'),
                            'onclick' => 'mdChooseUser(\'multi\', \''.$this->rowObj['OBJECT_ID'].'\', \''.$this->rowObj['OBJECT_ID'].'\', \'objPermissionUser-'.$this->rowObj['OBJECT_ID'].'\', this);'
                        ), 
                    true); 
                ?>
            </th>
            <?php
            if ($this->permission) {
                foreach ($this->permission as $per) {
                    echo '<th class="objPermission center">';
                    echo $per['NAME'];
                    echo Form::hidden(array('value'=>$per['PERMISSION_ID']));
                    echo '</th>';
                }
            }
            ?>
            <th></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<?php echo Form::hidden(array('id'=>'objPermissionUser-'.$this->rowObj['OBJECT_ID'])); ?>
<?php echo Form::hidden(array('name'=>'mdObjectId[]','value'=>$this->rowObj['OBJECT_ID'])); ?>