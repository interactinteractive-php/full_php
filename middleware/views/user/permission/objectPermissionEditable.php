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
                    $this->isPermission); 
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
        <?php
        if ($this->userData) {
            foreach ($this->userData as $user) {
        ?>
        <tr>
            <td>
                <?php echo $user['FIRST_NAME']; ?> (Хэрэглэгч) 
                <?php echo Form::hidden(array('name'=>'mdUserId_'.$this->rowObj['OBJECT_ID'].'[]','class'=>'mdUserInput','value'=>$user['USER_ID'])); ?>
            </td>
            <?php
            if ($this->permission) {
                foreach ($this->permission as $per) {
                    $checkBoxAttr = array('name'=>'mdUserPermissionId_'.$this->rowObj['OBJECT_ID'].'_'.$user['USER_ID'].'[]', 'value'=>$per['PERMISSION_ID']);
                    $getChecked = Arr::search($this->recordPermission, "OBJECT_ID = '{$this->rowObj['OBJECT_ID']}' and USER_ID = '{$user['USER_ID']}' and PERMISSION_ID = '{$per['PERMISSION_ID']}'", 1);
                    if ($getChecked) {
                        $checkBoxAttr = array_merge($checkBoxAttr, array('checked' => 'checked'));
                    }
                    echo '<td class="center">';
                    echo Form::checkbox($checkBoxAttr);
                    echo '</td>';
                }
            }
            ?>
            <td class="center">
                <?php 
                echo Html::anchor('javascript:;', '<i class="icon-trash"></i>', 
                        array(
                            'class' => 'btn mini red', 
                            'title' => $this->lang->line('delete_btn'),
                            'onclick' => 'deleteMdUserList(this);'
                        ), 
                    $this->isPermission); 
                ?>
            </td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>
<?php echo Form::hidden(array('id'=>'objPermissionUser-'.$this->rowObj['OBJECT_ID'])); ?>
<?php echo Form::hidden(array('name'=>'mdObjectId[]','value'=>$this->rowObj['OBJECT_ID'])); ?>