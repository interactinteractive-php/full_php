
<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">Param map:</td>
                <td>
                    <button type="button" class="btn btn-sm purple-plum mt5 mb5" onclick="callGoogleMapLinkParam(this);">...</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">

    function callGoogleMapLinkParam() {
        var dialogName = '#googleMapLinkParamMapDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/initGoogleMapLink',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            dataType: "json",
            success: function (data) {
                $(dialogName).html(data.Html);
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: '1300',
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/insertGoogleMapLink',
                                dataType: "json",
                                data: $("#googlemaplink-form", dialogName).serialize(),
                                success: function (data) {
                                    PNotify.removeAll();
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                        $(dialogName).dialog('close');
                                    } else {
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                    }
                                    Core.unblockUI();
                                }
                            });
                            $(dialogName).dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                }).dialog('open');
            },
            error: function () {
                alert("Error");
            }
        });
    }

</script>