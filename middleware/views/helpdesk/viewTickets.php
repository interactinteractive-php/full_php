<div class="row-fluid">
    <div class="span12">
        <?php
        if ($this->tickets) {
            $i = 1;
            foreach ($this->tickets as $row) {
        ?>
        <div class="card box <?php echo (($row->status_id == '1')?'blue':'purple'); ?>">
            <div class="card-header card-header-no-padding header-elements-inline">
                <h4><i class="icon-file"></i> <?php echo $row->status_name; ?></h4>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="fullscreen"></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p>
                    №: <?php echo $i++; ?> &nbsp;&nbsp;&nbsp;
                    Код: <span class="badge"><?php echo $row->code; ?></span> &nbsp;&nbsp;&nbsp;
                    Чухал эсэх: <span class="badge"><?php echo $row->priority_name; ?></span> &nbsp;&nbsp;&nbsp; 
                    Төрөл: <span class="badge"><?php echo $row->type_name; ?></span> &nbsp;&nbsp;&nbsp; 
                    Гүйцэтгэгч: <span class="badge"><?php echo $row->performers; ?></span> 
                </p>
                <p><strong><?php echo $row->title; ?></strong></p>
                <p><?php echo $row->description; ?></p>
            </div>
        </div>
        <?php
           }
        }
        ?>
    </div>
</div>

<?php echo Form::button(array('class'=>'btn black','value'=>$this->lang->line('close_btn'), 'onclick'=>"$('#dialog-ticket').dialog('close');")); ?>