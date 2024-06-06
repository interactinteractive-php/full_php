<div data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <form method="post" id="form-dv-dmrecord-map">
        <div class="col-md-12">
            <div class="form-group row">
                <?php 
                echo Form::label(
                    array(
                        'text' => $this->lang->line('dmrmap_refstructure'), 
                        'for' => 'refStructureId', 
                        'class' => 'col-form-label col-md-4 text-right pr0', 
                        'required' => 'required'
                    )
                ); 
                ?>
                <div class="col-md-8">
                    <?php 
                    echo Form::select(
                        array(
                            'id' => 'refStructureId', 
                            'name' => 'refStructureId', 
                            'class' => 'form-control form-control-sm select2',
                            'data' => $this->refStructureList, 
                            'op_value' => 'id', 
                            'op_text' => 'name', 
                            'required' => 'required'
                        )
                    ); 
                    ?> 
                </div>
            </div>
            <div class="form-group row">
                <?php 
                echo Form::label(
                    array(
                        'text' => $this->lang->line('dmrmap_workflow'), 
                        'for' => 'workFlowId', 
                        'class' => 'col-form-label col-md-4 text-right pr0', 
                        'required' => 'required'
                    )
                ); 
                ?>
                <div class="col-md-8">
                    <?php 
                    echo Form::select(
                        array(
                            'id' => 'workFlowId', 
                            'name' => 'workFlowId', 
                            'class' => 'form-control form-control-sm select2',
                            'data' => $this->workFlowList,
                            'op_value' => 'code', 
                            'op_text' => 'name',  
                            'required' => 'required'
                        )
                    ); 
                    ?> 
                </div>
            </div>
            <div class="form-group row">
                <?php 
                echo Form::label(
                    array(
                        'text' => $this->lang->line('dmrmap_record'), 
                        'for' => 'recordId', 
                        'class' => 'col-form-label col-md-4 text-right pr0', 
                        'required' => 'required'
                    )
                ); 
                ?>
                <div class="col-md-8">
                    <?php 
                    echo Form::select(
                        array(
                            'id' => 'recordId', 
                            'name' => 'recordId', 
                            'class' => 'form-control form-control-sm select2',
                            'data' => $this->recordList, 
                            'op_value' => 'code', 
                            'op_text' => 'name', 
                            'required' => 'required'
                        )
                    ); 
                    ?> 
                </div>
            </div>
        </div>    
        <div data-section-path="kpiDmDtl" class="mt15">
        </div>    
        <?php 
        echo Form::hidden(array('name' => 'srcRefStructureId', 'value' => $this->refStrId)); 
        echo Form::hidden(array('name' => 'srcName')); 
        echo Form::hidden(array('name' => 'trgName')); 
        echo Form::hidden(array('name' => 'srcWfmWorkflowId')); 
        echo Form::hidden(array('name' => 'dataViewId'));
        echo Form::hidden(array('name' => 'trgWfmStatusId'));
        ?> 
    </form>
</div>     

<script type="text/javascript">
var bp_window_<?php echo $this->uniqId; ?> = $("div[data-bp-uniq-id='<?php echo $this->uniqId; ?>']");
var srcName = '';
var trgName = '';
            
$(function() {
    
    var $form = $('#form-dv-dmrecord-map');
    
    $form.on('change', '#refStructureId', function() {
        
        var refStructureId = $(this).val();
        var $select2 = $form.find('#workFlowId, #recordId');
        
        if (refStructureId != '') {
            
            $.ajax({
                type: 'post',
                url: 'mddatamodel/getDvRowsByCriteria', 
                data: {
                    dvId: '<?php echo $this->workFlowDvId; ?>', 
                    criteria: [
                        {path: 'srcRefStructureId', operand: '<?php echo $this->refStrId; ?>', operator: '='}, 
                        {path: 'srcWfmStatusId', operand: '<?php echo $this->wfmStatusId; ?>', operator: '='}, 
                        {path: 'trgRefStructureId', operand: refStructureId, operator: '='}
                    ]
                },
                dataType: 'json',
                success: function (data) {
                    
                    $select2.select2('val', '');
                    $select2.find('option:gt(0)').remove();
                    
                    if (data.status == 'success') {
                        
                        var rows = data.rows;
                        
                        if (rows.length) {
                            
                            var $workFlow = $form.find('#workFlowId');
                        
                            $.each(rows, function() {
                                var rw = this;
                                $workFlow.append($("<option />").val(rw.id).text(rw.name).attr('data-rowdata', JSON.stringify(rw)));
                            });
                        }
                    }
                    
                    Core.initSelect2($select2);
                }
            });            
            
        } else {
        
            $select2.select2('val', '');
            $select2.find('option:gt(0)').remove();
            $form.find('input[name="srcWfmWorkflowId"]').val('');
            
            Core.initSelect2($select2);
        }
    });
    
    $form.on('change', '#workFlowId', function() {
    
        var $elem = $(this);
        var workFlowId = $elem.val();
        var $record = $form.find('#recordId');
        
        $record.select2('val', '');
        $record.find('option:gt(0)').remove();
                    
        if (workFlowId != '') { 
            
            var rowData = $elem.find(':selected').data('rowdata');
            var dataViewId = rowData.dataviewid;
            var trgWfmStatusIds = rowData.trgwfmstatusids;
            
            srcName = (rowData.srcname).toLowerCase();
            trgName = (rowData.trgname).toLowerCase();
            
            $form.find('input[name="dataViewId"]').val(dataViewId);
            $form.find('input[name="srcWfmWorkflowId"]').val(rowData.srcwfmworkflowid);
            
            $.ajax({
                type: 'post', 
                url: 'mddatamodel/getDvRowsByCriteria', 
                data: {
                    dvId: dataViewId, 
                    criteria: [
                        {path: 'wfmStatusId', operand: trgWfmStatusIds, operator: 'IN'} 
                    ]
                },
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    
                    if (data.status == 'success') {
                        
                        var rows = data.rows;
                        
                        if (rows.length) {
                            
                            var nameField = data.nameField;
                            
                            $.each(rows, function() {
                                var rw = this;
                                $record.append($("<option />").val(rw.id).text(rw[nameField]).attr('data-rowdata', JSON.stringify(rw)));
                            });
                        }
                    }
                    
                    Core.initSelect2($record);
                    Core.unblockUI();
                }
            });      
            
        } else {
            $form.find('input[name="dataViewId"], input[name="srcWfmWorkflowId"]').val('');
            Core.initSelect2($record);
        }
    });
    
    $form.on('change', '#recordId', function() {
        
        var $elem = $(this);
        var recordId = $elem.val();
        
        $form.find('div[data-section-path="kpiDmDtl"]').empty();
        $form.find('input[name="trgWfmStatusId"], input[name="srcName"], input[name="trgName"]').val('');
        
        if (recordId != '') {
            
            var rowData = $elem.find(':selected').data('rowdata');
            var statusId = rowData.wfmstatusid;
            
            $form.find('input[name="trgWfmStatusId"]').val(statusId);
            
            if (srcName != '') {
                
                var dvRows = getDataViewSelectedRows('<?php echo $this->dvId; ?>');
                var dvFirstRow = dvRows[0];
                
                if (dvFirstRow.hasOwnProperty(srcName)) {
                    
                    var appendName = '';
                    
                    for (var r in dvRows) {
                        appendName += dvRows[r][srcName] + ', ';
                    }
                    
                    $form.find('input[name="srcName"]').val(rtrim(appendName, ', '));
                }
            }
            
            if (rowData.hasOwnProperty(trgName)) {
                $form.find('input[name="trgName"]').val(rowData[trgName]);
            }
            
            $.ajax({
                type: 'post', 
                url: 'mdform/kpiFormByDmRecordMap', 
                data: {
                    uniqId: '<?php echo $this->uniqId; ?>', 
                    srcRefStructureId: '<?php echo $this->refStrId; ?>', 
                    srcWfmStatusId: '<?php echo $this->wfmStatusId; ?>', 
                    trgRefStructureId: $form.find('#refStructureId').val(), 
                    trgWfmStatusId: statusId
                },
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    
                    if (data.status == 'success') {
                        $form.find('div[data-section-path="kpiDmDtl"]').append(data.html).promise().done(function() {
                            $('#dialog-dvrecordmap-set').dialog('option', 'position', {my: 'center', at: 'center', of: window});
                            Core.unblockUI();
                        });
                    } else {
                        Core.unblockUI();
                    }
                }
            });      
        } 
    });
    
});    
</script>