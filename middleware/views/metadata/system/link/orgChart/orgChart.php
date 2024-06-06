<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">Data view:</td>
                <td>
                    <?php
                    echo Form::select(
                            array(
                                'name' => 'dataViewId',
                                'id' => 'dataViewId',
                                'data' => $this->dataViewList,
                                'op_value' => 'META_DATA_ID',
                                'op_text' => 'META_DATA_NAME',
                                'class' => 'form-control select2',
                                'value' => $this->dataViewId
                            )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {

             
    });
</script>