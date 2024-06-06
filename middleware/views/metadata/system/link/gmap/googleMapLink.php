<div class="panel panel-default bg-inverse">
    <table class="table sheetTable googleMaplink">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">Params:</td>
                <td>
                    <button type="button" class="btn btn-sm purple-plum mt5 mb5" onclick="configGoogleMapLinkEdit(this);">...</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    function configGoogleMapLinkEdit(elem) {
        $.ajax({
                type: 'post',
                url: 'mdmeta/dialogGoogleMapLink',
                data: {metaGoogleMapLinkId: metaGoogleMapLinkId},
                dataType: "json",
                success: function (data) {
                    if (data.status === 'success') {
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            sticker: false
                        });
                        _row.remove();
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: data.message,
                            type: 'error',
                            sticker: false
                        });
                    }
                },
                error: function () {
                    alert("Error");
                }
            }).done(function(){
                Core.initAjax();
            });
    }
</script>