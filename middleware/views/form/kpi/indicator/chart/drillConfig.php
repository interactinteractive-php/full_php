<div class="col-md-12 mt-2">
    <div class="form-group row">
        <?php echo Form::label(array('text'=>Lang::line('Drill_config'), 'class'=>'col-form-label col-md-auto text-right pr-0 pt-0 line-height-normal', 'style' => 'width: 100px')); ?>
        <div class="col">
            <div class="-input-group drillConfig<?php echo $this->uniqId ?>">
                <textarea name="drillConfig" tabindex="" class="d-none form-control form-control-sm expression_editorInit" data-path="drillConfig" data-field-name="drillConfig" data-isclear="0" style="height: 28px; overflow: hidden; resize: none;" draggable="false" rows="1" placeholder="Drill Config"><?php echo issetParam($this->graphJsonConfig['chartConfig']['drillConfig']) ?></textarea>
                <span class="input-group-append">
                    <a href="javascript:;" onclick="drillConfigPopup<?php echo $this->uniqId ?>(this);" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" title="Тохиргоо" data-b-path="">...</a>
                </span>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function drillConfigPopup<?php echo $this->uniqId ?>(element) {
        var $thisElem = $(element),
            _parent = $(element).closest('.drillConfig<?php echo $this->uniqId ?>');
        var selectorOption = _parent.find('[data-path="drillConfig"]');
        $.ajax({
            type: 'post',
            url: 'mdform/drillConfigForm',
            dataType: 'json',
            data: {
                option: selectorOption.val()
            },
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (response) {
                var $dialogName = 'drill-config' + response.uniqId;
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                
                var $dialog = $("#" + $dialogName);
                $dialog.empty().append(response.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: response.Title,
                    width: response.Width,
                    height: response.Height,
                    modal: true,
                    closeOnEscape: isCloseOnEscape, 
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                            var _drillConfig = [];
                            if ($dialog.find('.mainRow_' + response.uniqId + ' > tr').length > 0) {
                                $dialog.find('.mainRow_' + response.uniqId + ' > tr').each(function (_index, _row) {
                                    var $row = $dialog.find('.mainRow_' + response.uniqId + ' > tr:eq('+ _index +') > td');
                                    var _option = {};

                                    $row.each(function (k, r) {
                                        $(r).find('[data-path]:not(.dtlRow)').each(function (_kIndex, _kRow) {
                                            var __row = $(_kRow);
                                            var key = __row.attr('data-path');
                                            if (key) {
                                                var value = __row.val();
                                                var tmp = {};
                                                tmp[key] = value;
                                                _option = {
                                                    ...tmp,
                                                    ..._option
                                                };
                                            }
                                        });

                                        _option['dtl'] = [];
                                        if ($row.find('.mainDtlRow_' + response.uniqId + ' > tr').length > 0) {
                                            $row.find('.mainDtlRow_' + response.uniqId + ' > tr').each(function (_subIndex, _subRow) {
                                                var $subRow = $(_subRow);
                                                var _subOption = {};

                                                $subRow.find('input').each(function (sk, sr) {
                                                    var __subRow = $(sr);
                                                    var key = __subRow.attr('data-path');
                                                    if (key) {
                                                        var value = __subRow.val();
                                                        var tmp = {};
                                                        tmp[key] = value;
                                                        _subOption = {
                                                            ...tmp,
                                                            ..._subOption
                                                        };
                                                    }
                                                });

                                                _option['dtl'].push(_subOption);
                                            });
                                        }
                                    });


                                    _drillConfig.push(_option);
                                });
                                
                            }

                            selectorOption.val(JSON.stringify(_drillConfig));
                            $dialog.dialog('close');
                        }},
                        {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                }).dialogExtend({
                    "closable": true,
                    "maximizable": true,
                    "minimizable": false,
                    "collapsable": false,
                    "dblclick": "maximize",
                    "minimizeLocation": "left",
                    "icons": {
                        "close": "ui-icon-circle-close",
                        "maximize": "ui-icon-extlink",
                        "minimize": "ui-icon-minus",
                        "collapse": "ui-icon-triangle-1-s",
                        "restore": "ui-icon-newwin"
                    }
                });
                
                $dialog.dialog('open');
                Core.initBPAjax($dialog);
                
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        });
        
    }
</script>