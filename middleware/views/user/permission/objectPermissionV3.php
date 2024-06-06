<table class="table table-advance table-bordered table-striped table-hover" id="mdObj-<?php echo $this->rowObj['OBJECT_ID']; ?>">
    <thead>
        <tr>
            <th>
                <?php 
                echo Html::anchor('javascript:;', '&nbsp;<i class="fa fa-user"></i>&nbsp;', 
                        array(
                            'class' => 'btn green btn-xs', 
                            'title' => $this->lang->line('udep_user_choose'),
                            'onclick' => 'mdChooseUserV3(\'multi\', \''.$this->rowObj['OBJECT_ID'].'\', \''.$this->rowObj['OBJECT_ID'].'\', \'objPermissionUser-'.$this->rowObj['OBJECT_ID'].'\', this);'
                        ), 
                    true); 
                ?>
            </th>
            <?php
            if ($this->permission) {
                foreach ($this->permission as $per) {
                    echo '<th class="objPermission text-center">';
                    echo $per['NAME'];
                    echo Form::hidden(array('value'=>$per['PERMISSION_ID']));
                    echo '<input type="checkbox" class="user-perm-check-all">';
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