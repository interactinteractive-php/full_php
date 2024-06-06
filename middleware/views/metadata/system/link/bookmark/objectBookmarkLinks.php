<?php
echo Form::select(
    array(
        'name' => 'SYS_BOOKMARK_TARGET',
        'id' => 'SYS_BOOKMARK_TARGET',
        'class' => 'form-control',
        'data' => (new Mdmetadata())->getAllTargetLink(),
        'op_value' => 'TARGET_ID',
        'op_text' => 'TARGET_NAME',
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
        'placeholder' => 'URL -ын хаягыг оруулна уу..'
    )
);
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