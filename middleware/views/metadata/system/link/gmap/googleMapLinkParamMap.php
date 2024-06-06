<button type="button" class="btn btn-xs green-meadow mb10" onclick="addRow();"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?></button>
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'googlemaplink-form', 'method' => 'post')); ?>
<input type="hidden" name="metaDataId" value="<?php echo $this->metaDataId; ?>">
<div class="table-bordered">
    <table class="table googleMaplink mb0" style="table-layout: fixed;">
        <thead>
            <tr>
                <th style="width: 30px;">#</th>
                <th style="width: 250px;">DV name</th>
                <th style="width: 100px;" class="text-center"><?php echo $this->lang->line('meta_00197'); ?></th>
                <th style="width: 250px;">Action meta</th>
                <th style="width: 60px;" class="text-center">Dynamic</th>
                <th style="width: 200px;">Service name</th>
                <th style="width: 200px;">Service Url</th>
                <th style="width: 80px;"><?php echo $this->lang->line('meta_00080'); ?></th>
                <th style="width: 50px;">#</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $this->initGoogleMapLink; ?>
        </tbody>
    </table>
</div>

<?php echo Form::close(); ?>
<script type="text/javascript">
    
    
    function addRow() {
        var rowNumber = $('table.googleMaplink tbody tr').length;
        var i = ++rowNumber;
        var html = '<tr>';
        html += '<td>';
        html += '<input type="hidden" name="metaGoogleMapLinkId[]" value="">';
        html += i;
        html += '</td>';
        html += '<td>';
        html += '<div class="input-group">';
        html += '<input type="hidden" name="listMetaDataId[]" value="">';
        html += '<input type="text" class="form-control form-control-sm" name="listMetaDataName[]" style="min-width: 150px;">';
        html += '<span class="input-group-btn"><button type="button" class="btn blue form-control-sm mr0" onclick="commonMetaDataGrid(\'single\', \'metaObject\', \'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>\', \'chooseListMetaData\', this);"><i class="fa fa-search"></i></button></span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="text-center">'
                + '<input type="hidden" value="ff0000" name="displayColor[]">'
                + '<input type="hidden" value="marker" name="iconName[]">'
                + '<span class="markerImg"><?php echo Mdcommon::svgIconByColor("FF0000");?></span>'
                + '<button type="button" class="btn btn-sm blue-hoki ml5 float-right" onclick="chooseIcon(this)">...</button>'
        html += '</td>';
        html += '<td>';
        html += '<div class="input-group">';
        html += '<input type="hidden" name="actionMetaDataId[]" value="">';
        html += '<input type="text" class="form-control form-control-sm" name="actionMetaDataName[]" style="min-width: 150px;">';
        html += '<input type="hidden" name="actionMetaTypeId[]">';
        html += '<span class="input-group-btn">';
        html += '<button type="button" class="btn blue form-control-sm mr0" title="Action meta сонгох" onclick="commonMetaDataGrid(\'single\', \'metaObject\', \'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId . '|' . Mdmetadata::$diagramMetaTypeId . '|' . Mdmetadata::$googleMapMetaTypeId . '|' . Mdmetadata::$businessProcessMetaTypeId . '|' . Mdmetadata::$layoutMetaTypeId; ?>\', \'chooseActionMetaData\', this);"><i class="fa fa-search"></i></button>';
        html += '<button type="button" class="btn purple-plum form-control-sm mr0" onclick="googleMapParam(this)" title="Параметр тохируулах">...</button>';
        html += '</span>';
        html += '</div>';
        html += '</td>';
        html += '<td class="text-center"><input type="hidden" name="isDynamic[]" value="0"><input type="checkbox" onclick="isDymanic(this);" value="0"></td>';
        html += '<td><input type="text" class="form-control form-control-sm" name="serviceUrl[]" value="" style="width: 100%;"></td>';
        html += '<td><input type="text" class="form-control form-control-sm" name="serviceName[]" value=""  style="width: 100%;"></td>';
        
        
        

        html += '<td><input type="text" class="form-control form-control-sm" name="orderNum[]" value="' + i + '" style="width: 50px;"></td>';
        html += '<td><button type="button" class="btn btn-sm red-sunglo" onclick="removeMetaGoogleMapLink(this);"><i class="fa fa-trash"></i></button></td>';
        html += '</tr>';
        $('table.googleMaplink').append(html);
    }
    
    function removeMetaGoogleMapLink(elem) {
        var $dialogName = 'dialog-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var _row = $(elem).closest("tr");
        var metaGoogleMapLinkId = _row.find('input[name="metaGoogleMapLinkId[]"]').val();
        $.ajax({
            type: 'post',
            url: 'mdcommon/deleteConfirm',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().html(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 330,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function () {
                                if (metaGoogleMapLinkId.length > 0) {
                                    $.ajax({
                                        type: 'post',
                                        url: 'mdmeta/deleteGoogleMapLink',
                                        data: {metaGoogleMapLinkId: metaGoogleMapLinkId},
                                        dataType: "json",
                                        beforeSend: function () {
                                            Core.blockUI({
                                                animate: true
                                            });
                                        },
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
                                            Core.unblockUI();
                                        },
                                        error: function () {
                                            alert("Error");
                                        }
                                    });
                                } else {
                                    _row.remove();
                                }
                                $("#" + $dialogName).dialog('close');
                                Core.unblockUI();
                            }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
    
    function chooseListMetaData(chooseType, elem, params, _this) {
        var cell = $(_this).closest('td');
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            cell.find('input[name="listMetaDataId[]"]').val(rows['0']['META_DATA_ID']);
            cell.find('input[name="listMetaDataName[]"]').val(rows['0']['META_DATA_NAME']);
        }
    }

    function chooseActionMetaData(chooseType, elem, params, _this) {
        var cell = $(_this).closest('td');
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            console.log(rows);
            cell.find('input[name="actionMetaTypeId[]"]').val(rows['0']['META_TYPE_ID']);
            cell.find('input[name="actionMetaDataId[]"]').val(rows['0']['META_DATA_ID']);
            cell.find('input[name="actionMetaDataName[]"]').val(rows['0']['META_DATA_NAME']);
        }
    }

    function chooseIcon(elem) {
        var cell = $(elem).closest('td');
        var dialogName = '#chooseGoogleMapIconDialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/chooseGoogleMapIcon',
            data: {displayColor: cell.find('input[name="displayColor[]"]').val(), iconName: cell.find('input[name="iconName[]"]').val()},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
                if (!$().colorpicker) {
                    $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                    });
                } 
            },
            success: function (data) {
                $(dialogName).html(data.Html);
                $(dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: '600',
                    height: 'auto',
                    modal: true,
                    buttons: [
                        {text: data.add_btn, class: 'btn green-meadow btn-sm', click: function () {
                                var displayColor = $('input[name="displayColor"]', dialogName).val();
                                var iconName = $('input[name="iconName"]', dialogName).val();
                                cell.find('img.markerIcon').attr('src', 'mdcommon/svgIconByColor/' + displayColor + '/' + iconName);
                                cell.find('input[name="displayColor[]"]').val(displayColor);
                                cell.find('input[name="iconName[]"]').val(iconName);
                                displayColor = displayColor.substr(1, 6);
                                $.ajax({
                                    type: 'post',
                                    url: 'mdcommon/svgIconJsonByColor/' + displayColor + '/' + iconName,
                                    dataType: 'json',
                                    beforeSend: function () {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function (data) {
                                        Core.blockUI({
                                            animate: true
                                        });
                                        cell.find('span[class="markerImg"]').empty().html(data);
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                                $(dialogName).dialog('close');
                            }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $(dialogName).dialog('close');
                            }}
                    ]
                }).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            $('.colorpicker-default', dialogName).colorpicker({
                format: 'hex'
            });
            Core.initAjax();
            Core.initSlimScroll('.chooseSvgIcon');
            $('.svgIcon', dialogName).each(function(k, row){
                var _this = $(row);
                if (_this.attr('id') == cell.find('input[name="iconName[]"]').val()) {
                    console.log(_this.attr('id'));
                    _this.addClass('active');
                }
            });
    
        });
    }
    
    function googleMapParam(elem) {
        var row = $(elem).closest('tr');
        var listMetaDataId = row.find('input[name="listMetaDataId[]"]').val();
        var actionMetaDataId = row.find('input[name="actionMetaDataId[]"]').val();
        var actionMetaTypeId = row.find('input[name="actionMetaTypeId[]"]').val();
        var googleMapLinkId = row.find('input[name="metaGoogleMapLinkId[]"]').val();
        var metaGoogleMapLinkId = row.find('input[name="metaGoogleMapLinkId[]"]').val();
        
        if (listMetaDataId != '' && actionMetaDataId != '' && metaGoogleMapLinkId != '' && actionMetaTypeId != '') {
            var dialogName = '#googleMapParamDailog';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            $.ajax({
                type: 'post',
                url: 'mdmeta/googleMapParam',
                data: {listMetaDataId: listMetaDataId, actionMetaDataId: actionMetaDataId, googleMapLinkId: googleMapLinkId, actionMetaTypeId: actionMetaTypeId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $(dialogName).html(data.Html);
                    $(dialogName).dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: '1000',
                        height: 'auto',
                        modal: true,
                        buttons: [
                            {text: data.save_btn, class: 'btn green-meadow btn-sm bp-btn-subsave', click: function () {
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/insertGoogleMapParam',
                                    data: $('form[name="googleMapParamForm"]').serialize(),
                                    dataType: "json",
                                    beforeSend: function () {
                                        Core.blockUI({
                                            animate: true
                                        });
                                    },
                                    success: function (data) {
                                        console.log(data);
                                        if (data.status === 'success') {
                                            new PNotify({
                                                title: 'Success',
                                                text: data.message,
                                                type: 'success',
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI();
                                        $(dialogName).dialog('close');
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                            }},
                            {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $(dialogName).dialog('close');
                            }}
                        ]
                    }).dialog('open');
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                $('.colorpicker-default', dialogName).colorpicker({
                    format: 'hex'
                });
                Core.initAjax();
            });
        } else {
            var dialogName = '#googleMapParamAlertDailog';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            $(dialogName).html("Dataview map үүсээгүй эсвэл action meta сонгоогүй байна");
            $(dialogName).dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Сануулга',
                width: '400',
                height: '150',
                modal: true,
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $(dialogName).dialog('close');
                    }}
                ]
            }).dialog('open');
            Core.unblockUI();
        }
    }
    
    function removeGoogleMapParam(elem) {
        var dialogName = '#removeGoogleMapParamConfirmDialgon';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/deleteGoogleMapParamDialog',
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                var row = $(elem).closest('tr');
                var googleMapParamId = row.find('input[name="googleMapParamId[]"]').val();
                var dialogName = '#deletegoogleMapParamDailog';
                if (!$(dialogName).length) {
                    $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
                }
                $(dialogName).empty().html(data.Html);
                $(dialogName).dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $(dialogName).empty().dialog('close');
                    },
                    buttons: [
                        {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function () {
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/deleteGoogleMapParam',
                                data: {googleMapParamId: googleMapParamId},
                                dataType: "json",
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
                                },
                                success: function (data) {
                                    PNotify.removeAll();
                                    if (data.status === 'success') {
                                        new PNotify({
                                            title: 'Success',
                                            text: data.message,
                                            type: 'success',
                                            sticker: false
                                        });
                                    } else {
                                        new PNotify({
                                            title: 'Error',
                                            text: data.message,
                                            type: 'error',
                                            sticker: false
                                        });
                                    }
                                    $(dialogName).dialog('close');
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                }
                            });
                        }},
                        {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                });
                $(dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
    function isDymanic(elem) {
        var _this = $(elem);
        var cell = _this.closest('td', '#googlemaplink-form');
        var isDynamic = cell.find('input[name="isDynamic[]"]', '#googlemaplink-form');
        if (_this.is(':checked')) {
            _this.val(1);
            isDynamic.val(1);
        } else {
            _this.val(0);
            isDynamic.val(0);
        }
    }
</script>