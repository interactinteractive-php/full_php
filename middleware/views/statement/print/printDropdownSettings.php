<div class="xs-form row">
    <table class="table table-sm table-no-bordered">
        <tr>
            <td class="text-right middle" style="width: 55%">Шинэ хуудсанд хэвлэх:</td>
            <td class="middle" style="width: 45%">
                <input type="checkbox" class="form-control form-control-sm" name="isPrintNewPage" id="isPrintNewPage" value="1" checked="checked" />
            </td>
        </tr>
        <tr>
            <td class="text-right middle" style="width: 55%">Хэвлэхээс өмнө харах:</td>
            <td class="middle" style="width: 45%">
                <input type="checkbox" class="form-control form-control-sm" name="isShowPreview" id="isShowPreview" value="1" checked="checked" />
            </td>
        </tr>
        <tr>
            <td class="text-right middle" style="width: 55%">Хэвлэх хувь/%/:</td>
            <td class="middle" style="width: 45%">
                <input type="text" class="form-control form-control-sm text-right" required="required" style="width:20%" name="numberOfCopies" id="numberOfCopies" value="1"/>
            </td>
        </tr>
        <tr>
            <td class="middle" colspan="2">
                <span class="col-md-1" style="color:#a6a6a6; font-weight: bold;"> 
                    Тайлан:
                </span>
                <div class="col-md-12">
                    <?php 
                    $tmpdata = isset($this->statementDataList) ? $this->statementDataList : array();
                    echo Form::multiselect(
                        array(
                            'name' => 'printStatement',
                            'id' => 'printStatement',
                            'required' => 'required',
                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                            'data'=> $tmpdata,
                            'op_value' => 'META_DATA_ID',
                            'op_text' => 'META_DATA_NAME'
                        )
                    );
                    ?>
                </div>
            </td>
        </tr>
    </table>
</div>  
<script type="text/javascript">
$(function() {
    if ($("#printTemplate").val() != null) {
        $("#printTemplate").closest('tr').addClass('hide'); 
    } else {
        $("#printTemplate").closest('div').find('ul').find('li:first').find('input').attr('style', 'width:379px;');
    }
        
    $("#isPrintNewPage").click(function() {
        if ($("#isPrintNewPage").is(":checked")) {
            $("#isPrintNewPage").val(1);
        } else {
            $("#isPrintNewPage").val(0);
        }
    });
    $("#isShowPreview").click(function() {
        if ($("#isShowPreview").is(":checked")) {
            $("#isShowPreview").val(1);
        } else {
            $("#isShowPreview").val(0);
        }
    });
});
</script>    