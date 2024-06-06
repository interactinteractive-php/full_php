<div class="col-md-12">
    <div class="card-body xs-form">
        <form action="javascript:;" class="form-horizontal" id="editPermission-form">
            <?php echo Form::hidden(array('name' => 'permissionId', 'value' => $this->row['PERMISSION_ID'])); ?>    
            <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaData['META_DATA_ID'])); ?>    
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <label class="col-md-4 col-form-label">Мета код:</label>
                            <div class="col-md-8">
                                <input type="text" name="metaDataCode" class="form-control" value="<?php echo $this->metaData['META_DATA_CODE'] ?>" readonly="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <label class="col-md-4 col-form-label">Хэрэглэгч:</label>
                            <div class="col-md-8">
                                    <div class="input-group">
                                        <?php echo Form::hidden(array('name' => 'userId', 'id' => 'userId', 'value'=>$this->row['USER_ID'])); ?>
                                        <span class="input-group-btn">
                                            <?php echo Form::button(array('class' => 'btn blue', 'value' => '<i class="fa fa-search"></i>', 'onclick' => 'selectableUserDataGrid();')); ?>
                                        </span>
                                        <input type="text" name="userName" id="userName" class="form-control" value="<?php echo $this->row['USERNAME'];?>">
                                        <span class="input-group-btn">
                                            <?php echo Form::button(array('class' => 'btn yellow', 'value' => '<i class="fa fa-list"></i>', 'onclick' => 'viewUserBasket();')); ?>
                                        </span>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <label class="col-md-4 col-form-label">Мета нэр:</label>
                            <div class="col-md-8">
                                <input type="text" name="metaDataName" class="form-control" value="<?php echo $this->metaData['META_DATA_NAME'] ?>" readonly="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <label class="col-md-4 col-form-label">Role:</label>
                            <div class="col-md-8">
                                <?php
                                echo Form::multiselect(
                                        array(
                                            'name' => 'roleId[]',
                                            'id' => 'roleId[]',
                                            'class' => 'form-control form-control-sm select2',
                                            'data' => $this->roleData,
                                            'multiple'=>'multiple',
                                            'op_text' => 'ROLE_NAME',
                                            'op_value' => 'ROLE_ID',
                                            'value' => $this->row['ROLE_ID']
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <label class="col-md-4 col-form-label" required="required">Action:</label>
                            <div class="col-md-8">
                                <?php
                                echo Form::multiselect(
                                        array(
                                            'name' => 'actionId[]',
                                            'id' => 'actionId[]',
                                            'class' => 'form-control form-control-sm select2',
                                            'data' => $this->actionData,
                                            'multiple'=>'multiple',
                                            'required'=>'required',
                                            'op_text' => 'ACTION_NAME',
                                            'op_value' => 'ACTION_ID',
                                            'value' => $this->row['ACTION_ID']
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row fom-row">
                            <label class="col-md-4 col-form-label">Групп:</label>
                            <div class="col-md-8">
                                <?php
                                echo Form::multiselect(
                                        array(
                                            'name' => 'groupId[]',
                                            'id' => 'groupId[]',
                                            'class' => 'form-control form-control-sm select2',
                                            'data' => $this->groupData,
                                            'multiple'=>'multiple',
                                            'op_text' => 'GROUP_NAME',
                                            'op_value' => 'GROUP_ID',
                                            'value' => $this->row['GROUP_ID']
                                        )
                                );
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br />
            <div class="tabbable-line">
                <ul class="nav nav-tabs param-criteria-tabs">
                    <li class="nav-item">
                        <a href="#field" class="nav-link active" data-toggle="tab">Field criteria</a>
                    </li>
                    <li class="nav-item">
                        <a href="#record" class="nav-link" data-toggle="tab">Record criteria</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="field">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                    array(
                                        'name' => 'field_criteria',
                                        'id' => 'field_criteria',
                                        'class' => 'form-control',
                                        'spellcheck' => 'false',
                                        'rows' => 4,
                                        'value' => $this->row['FIELD_CRITERIA']
                                    )
                            );
                            ?>
                        </div>
                    </div>  
                </div>
                <div class="tab-pane" id="record">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            echo Form::textArea(
                                    array(
                                        'name' => 'record_criteria',
                                        'id' => 'record_criteria',
                                        'class' => 'form-control',
                                        'spellcheck' => 'false',
                                        'rows' => 4,
                                        'value' => $this->row['RECORD_CRITERIA']
                                    )
                            );
                            ?>
                        </div>
                    </div>  
                </div>
            </div>  

    </div>   
</form>
</div>
</div> 
<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>
<script type="text/javascript">
    var editfieldCriteriaEditor = CodeMirror.fromTextArea(document.getElementById("field_criteria"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    var editrecordCriteriaEditor = CodeMirror.fromTextArea(document.getElementById("record_criteria"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 4,
        theme: "material"
    });
    $(function() {
        $('.param-criteria-tabs a[data-toggle="tab"]').on('shown.bs.tab', function() {
            editfieldCriteriaEditor.refresh();
            editrecordCriteriaEditor.refresh();
        });
    });
    function selectableUserDataGrid(){
         commonSelectableGrid('user', 'multi', 'users', '');
    }
    function selectableCommonDataGrid(metaCode, chooseType, elem, params) {
        if (elem === 'users') {
            var rows = $('#commonSelectableBasketDataGrid').datagrid('getRows');
            var userIds = "";
            var userNames = "";
            jQuery.each(rows, function(i, val) {
                 userIds += val['USER_ID']+',';
                 userNames += val['USERNAME']+',';
            });
            $("input#userId").val(rtrim(userIds, ','));
            $("#userName").val(rtrim(userNames, ','));
        }
    }
</script>    