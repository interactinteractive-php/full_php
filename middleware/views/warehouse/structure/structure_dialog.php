<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php 
/*
echo 'LOCATION_ID=' . $this->LOCATION_ID . '<br>';
echo 'ISWAREHOUSE=' . $this->ISWAREHOUSE . '<br>';
echo 'QUERYTYPE=' . $this->QUERYTYPE . '<br>';
echo 'COORDINATE_X=' . $this->COORDINATE_X . '<br>';
echo 'COORDINATE_Y=' . $this->COORDINATE_Y . '<br>';
echo 'OLD_COORDINATE_X=' . $this->OLD_COORDINATE_X . '<br>';
echo 'OLD_COORDINATE_Y=' . $this->OLD_COORDINATE_Y . '<br>';
 * */
 
?>
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'addWH-form', 'method' => 'post')); ?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Агуулах', 'for'=>'WAREHOUSE', 'class'=>'col-form-label col-md-4')); ?>
        <div class="col-md-3">
        <?php echo Form::text(array('value'=>$this->getOneWareHouse['WAREHOUSE_NAME'], 'class' => 'form-control select2 form-control-sm input-xxlarge col-md-3', 'readonly'=>'readonly')); ?>
        <?php
            if($this->ISWAREHOUSE!='0'){
                echo Form::hidden(array('id'=>'WAREHOUSE_ID', 'name'=>'WAREHOUSE_ID', 'value'=>$this->getOneWareHouse['WAREHOUSE_ID']));
            }else{
                echo Form::hidden(array('id'=>'WAREHOUSE_ID', 'name'=>'WAREHOUSE_ID', 'value'=>''));
            }

            echo Form::hidden(array('value'=>$this->OLD_COORDINATE_X, 'id'=>'OLD_COORDINATE_X', 'name'=>'OLD_COORDINATE_X')); 
            echo Form::hidden(array('value'=>$this->OLD_COORDINATE_Y, 'id'=>'OLD_COORDINATE_Y', 'name'=>'OLD_COORDINATE_Y')); 
            echo Form::hidden(array('value'=>$this->LOCATION_ID, 'id'=>'OLD_LOCATION_ID', 'name'=>'OLD_LOCATION_ID')); 
            echo Form::hidden(array('value'=>$this->ISWAREHOUSE, 'id'=>'ISWAREHOUSE', 'name'=>'ISWAREHOUSE')); 
            echo Form::hidden(array('value'=>$this->MARKER_NAME, 'id'=>'MARKER_NAME', 'name'=>'MARKER_NAME')); 
            echo Form::hidden(array('value'=>$this->MARKER_ID, 'id'=>'MARKER_ID', 'name'=>'MARKER_ID')); 

            ?>
        <?php echo Form::hidden(array('id'=>'QUERYTYPE', 'name'=>'QUERYTYPE', 'value'=>$this->QUERYTYPE)); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Зураг дээрх байрлал', 'for'=>'COORDINAT', 'class'=>'col-form-label col-md-4', 'required'=>'required')); ?>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon">X=</span>
                <?php echo Form::text(array('value'=>$this->COORDINATE_X, 'class'=>'col-form-label number-format col-md-10', 'readonly'=>true)); ?>
                <?php echo Form::hidden(array('value'=>$this->COORDINATE_X, 'id'=>'COORDINATE_X', 'name'=>'COORDINATE_X', 'readonly'=>true)); ?>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-addon">Y=</span>
                <?php echo Form::text(array('value'=>$this->COORDINATE_Y, 'class'=>'col-form-label number-format col-md-10', 'readonly'=>true)); ?>
                <?php echo Form::hidden(array('value'=>$this->COORDINATE_Y, 'id'=>'COORDINATE_Y', 'name'=>'COORDINATE_Y')); ?>
            </div>
        </div>
    </div>
    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Байршил', 'for'=>'LOCATION', 'class'=>'col-form-label col-md-4', 'required'=>'required')); ?>
        <div class="col-md-7">
            <?php

            echo Form::select(
                array(
                    'name' => 'LOCATION_ID',
                    'id' => 'DIALOG_LOCATION_ID',
                    'class' => 'form-control form-control-sm input-xxlarge',
                    'data' => $this->getActiveWHLocationList,
                    'op_value' => 'LOCATION_ID',
                    'op_text' => 'LOCATION_CODE|-|LOCATION_NAME',
                    'value' => 'LOCATION_ID',
                    'required' => 'required'
                )
            );
            ?>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>
<script>
//    function format(state) {
//        console.log(state);
//        if (!state.MARKER_IMAGE) return state.DESCRIPTION; // optgroup
//        return "<img class='flag' src='" + URL  + "assets/custom/addon/plugins/marker/hotspotCustom/img/" + state.MARKER_IMAGE + "'/>&nbsp;&nbsp;" + state.DESCRIPTION;
//    }
        
    $(function(){
        $('.number-format').autoNumeric('init', {aPad: false, mDec: 2, vMin: '-99999999'});
        var locationId = <?php if($this->LOCATION_ID!=null) echo $this->LOCATION_ID; else echo 0;?>;
        var selVal = "<option value=''>- Байршил -</option>";
        $("#DIALOG_LOCATION_ID option").each(function() {
            if(locationId==$(this).val()){
                selVal += "<option value='" + $(this).val() + "' selected='selected'>" + $(this).html() + "</option>";
            }else{
                selVal += "<option value='" + $(this).val() + "'>" + $(this).html() + "</option>";
            }

        });
        $('#DIALOG_LOCATION_ID').empty().prop('disabled', false);
        $('#DIALOG_LOCATION_ID').append(selVal);
        $('#DIALOG_LOCATION_ID').select2('val', $("#OLD_LOCATION_ID").val()).trigger('change');
        
    });
</script>