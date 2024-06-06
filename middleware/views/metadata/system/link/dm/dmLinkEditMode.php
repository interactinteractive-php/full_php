<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr class="datamodel-listname">
                <td class="left-padding" style="width: 170px; height: 32px;">
                    <label for="dmTableName">
                        Table Name
                    </label>
                </td>
                <td colspan="2">
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'dmTableName',
                            'id' => 'dmTableName',
                            'class' => 'form-control textInit', 
                            'value' => $this->gRow['TABLE_NAME']
                        )
                    );
                    ?>
                </td>
            </tr>            
            <tr class="datamodel-tablename">
                <td class="left-padding" style="width: 170px; height: 32px;">
                    <label for="tableName">
                        Object:
                    </label>
                </td>
                <td colspan="2">
                    <div style="float: left; width: 80%;">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'tableName',
                                'id' => 'tableName',
                                'class' => 'form-control', 
                                'style' => 'min-height: 31px; height: 31px; resize:vertical; display: block',
                                'value' => $this->gRow['SELECT_QUERY']
                            )
                        );
                        ?>
                    </div>
                    <div style="float: right; width: 20%; text-align: right">
                        <button type="button" class="btn btn-sm blue mt5 mr0" onclick="dvSqlViewEditor(this);" title="SQL Editor"><i class="fa fa-edit"></i></button>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-tablename">
                <td class="left-padding" style="width: 170px; height: 32px;">
                    <label for="tableName">
                        Create Table:
                    </label>
                </td>
                <td colspan="2">
                    <button type="button" class="btn btn-sm purple-plum mt5 mr0" onclick="createDmTable(this);" title=""><i class="fa fa-table"></i></button>
                </td>
            </tr>
            <tr class="datamodel-tablename">
                <td class="left-padding" style="width: 170px; height: 32px;">
                    <label for="dmScheduleId">
                        Create Schedule:
                    </label>
                </td>
                <td colspan="2">
                    <?php
                        echo Form::multiselect(
                            array(
                                'name' => 'dmScheduleId[]',
                                'id' => 'dmScheduleId',
                                'class' => 'form-control form-control-sm select2',
                                'data' => $this->scheduleList,
                                'multiple'=>'multiple',
                                'op_text' => 'name',
                                'op_value' => 'id',
                                'value' => $this->savedSchedule
                            )
                        );                        
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php echo Form::hidden(array('name' => 'dmLinkId', 'value' => $this->gRow['ID'])); ?>

<script type="text/javascript">
function dvSqlViewEditor(elem) {

    var $dialogName = 'dialog-dvSqlViewEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var $parent = $(elem).closest('td');
                                            
    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $.ajax({
            type: 'post',
            url: 'mdmeta/dmSqlViewEditor',
            data: {
                query: $parent.find('textarea[name*="tableName"]').val(),
                dialogId: $dialogName
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
                if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.css']").length == 0) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');
                }
            },
            success: function(data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    minWidth: 1000,
                    height: 600,
                    modal: false,
                    open: function() {
                        disableScrolling();
                    }, 
                    close: function() {
                        enableScrolling();
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.format_btn, class: 'btn btn-sm purple-plum float-left', click: function() {
                            var $activeTab = $('.dbdriver-tabs [aria-expanded="true"]'), 
                                hrefUrl = $activeTab.attr('href'), sqlQuerySql = '', dbDriverTab = '';
                        
                            if (hrefUrl == '#default-tab') {
                                
                                dvSqlQueryEditor.save();
                                sqlQuerySql = dvSqlQueryEditor.getValue();
                                dbDriverTab = 'default';
                                
                            }
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/sqlFormatting',
                                data: {query: sqlQuerySql},
                                beforeSend: function() {
                                    Core.blockUI({
                                        message: 'Formatting...',
                                        boxed: true
                                    });
                                },
                                success: function(content) {
                                    
                                    if (dbDriverTab == 'default') {
                                        dvSqlQueryEditor.setValue(content);
                                        dvSqlQueryEditor.focus();
                                    }
                                    
                                    Core.unblockUI();
                                }
                            });
                        }}, 
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {

                            dvSqlQueryEditor.save();
                            
                            $parent.find('textarea[name*="tableName"]').val(dvSqlQueryEditor.getValue()).trigger('change');
                            
                            $dialog.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": true,
                    "collapsable": true,
                    "dblclick": "maximize",
                    "minimizeLocation": "left", 
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }, 
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    });
}
function createDmTable(elem) {
    $.ajax({
        type: 'post',
        url: 'mdmeta/createDmTable',
        data: {
            metaDataId: '<?php echo $this->metaDataId; ?>'
        },
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(content) {
            PNotify.removeAll();
            new PNotify({
                title: content.status,
                text: content.msg,
                type: content.status,
                sticker: false
            });            
            Core.unblockUI();
        }
    });    
}
</script>
