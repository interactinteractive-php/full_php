<?php

echo Form::select(
        array(
            'name' => 'SYS_BOOKMARK_TARGET',
            'id' => 'SYS_BOOKMARK_TARGET',
            'class' => 'form-control',
            'data' => (new Mdmetadata())->getAllTargetLink(),
            'op_value' => 'TARGET_ID',
            'op_text' => 'TARGET_NAME',
            'value' => $this->target,
            'required' => 'required',
            'text' => 'notext',
            'onchange' => 'callPage()'
        )
);
echo '<br/>';
echo Form::text(
        array(
            'name' => 'SYS_BOOKMARK_NAME',
            'id' => 'SYS_BOOKMARK_NAME',
            'class' => 'form-control',
            'required' => 'required',
            'value' => $this->bookmarkUrl
        )
);
echo Form::hidden(
        array(
            'name' => 'meta_type_id',
            'id' => 'meta_type_id',
            'value' => $this->metaTypeId
        )
)
?>  

<script type="text/javascript">
function callPage() {
    if ($('#SYS_BOOKMARK_TARGET').val() == '_blank') {
        if ($('#SYS_BOOKMARK_NAME').val() == '') {
            $('#SYS_BOOKMARK_NAME').val('http://');
        } else {
            var currentVal = $('#SYS_BOOKMARK_NAME').val();
            $('#SYS_BOOKMARK_NAME').val('http://' + currentVal);
        }
    } else {
        var value = $('#SYS_BOOKMARK_NAME').val();
        value = value.replace("http://", " ");
        $('#SYS_BOOKMARK_NAME').val(value);
    }
}
</script>
