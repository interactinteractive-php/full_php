<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="col-md-12 xs-form">
    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'tableStructure-form', 'method' => 'post')); ?>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>$this->lang->line('META_00114'), 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-8">
            <p class="form-control-plaintext"><?php echo $this->row['META_DATA_NAME']; ?></p>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Объектын нэр', 'class'=>'col-form-label col-md-3')); ?>
        <div class="col-md-8">
            <p class="form-control-plaintext"><?php echo $this->row['TABLE_NAME']; ?></p>
        </div>
    </div>
    <div class="table-scrollable overflowYauto" style="max-height: 400px;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th style="width: 5px;">№</th>
                    <th>Үзүүлэлтийн нэр</th>
                    <th>Үзүүлэлтийн код</th>
                    <th><?php echo $this->lang->line('META_00145'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($this->metaList) {
                    foreach ($this->metaList as $k=>$row) {
                ?>
                <tr>
                    <td><?php echo $k+1; ?></td>
                    <td><?php echo $row['META_DATA_NAME']; ?></td>
                    <td><?php echo $row['META_DATA_CODE']; ?></td>
                    <td><?php echo $row['META_TYPE_CODE']; ?></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php echo Form::close(); ?>
</div>