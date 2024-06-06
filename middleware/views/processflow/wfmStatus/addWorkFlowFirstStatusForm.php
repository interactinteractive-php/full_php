<div class="col-md-12">
    <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'createWfmTransition-from', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
    <table class="table table-sm table-no-bordered" style="table-layout: fixed !important">
        <tbody>
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusName" data-label-path="title" required="required">Ажлын урсгалын нэр:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmStatusName">
                        <input type="text" id="wfmStatusName" name="wfmStatusName" placeholder="Ажлын урсгалын нэр" class="form-control form-control-sm" required="required">
                    </div>
                </td>
            </tr>  
            <tr>
                <td class="text-right middle" style="width: 45%">
                    <label for="wfmStatusId" data-label-path="title" required="required">Эхлэх төлөв:</label>
                </td>
                <td class="middle" style="width: 55%" colspan="">
                    <div data-section-path="wfmStatusId">
                        <?php echo Form::select(array('name' => 'wfmStatusId', 'id' => 'wfmStatusId', 'class' => 'form-control form-control-sm select2me', 'data' => $this->statusList, 'op_value' => 'ID', 'op_text' => 'WFM_STATUS_NAME', 'required' => 'required', 'text' => 'notext')); ?>
                    </div>
                </td>
            </tr>     
            <tr>
                <td class="middle" style="width: 100%" colspan="2">
                    <div data-section-path="bpCriteria">
                        <?php 
                            echo Form::textArea(array('name' => 'bpCriteria', 'id' => 'bpCriteria', 'class' => 'form-control', 'value' => '', 'style'=>'min-height:300px;', 'required' => 'required'));
                        ?>
                    </div>
                </td>
            </tr>         
        </tbody>
    </table>
        
    <?php echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId)); ?>
    <?php echo Form::close(); ?>  
</div>
<script type="text/javascript">
    $(function() {
        Core.initUniform($('#createWfmStatus-from'));
    });
    
    var bpCriteriaEditorParam = CodeMirror.fromTextArea(document.getElementById("bpCriteria"), {
        mode: "javascript",
        styleActiveLine: true,
        lineNumbers: true,
        lineWrapping: true,
        matchBrackets: true,
        autoCloseBrackets: true,
        indentUnit: 1,
        theme: "material"
    });
    
</script>