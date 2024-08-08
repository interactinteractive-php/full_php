<div class="xs-form is-bp-open-0 main-action-meta bp-banner-container" id="bp-window-<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>" data-isgroup="0">
    <form id="wsForm" method="post" enctype="multipart/form-data" class="">
        <div class="row">
            <div class="col-md-12 center-sidebar">
                <div class="dv-right-tools-btn" style="position: absolute;right: 7px;top: 0;margin-top:-2px;margin-bottom:-30px;"></div>
                <div class="tabbable-line tabbable-tabdrop bp-tabs">
                    <ul class="nav nav-tabs d-none">
                        <li class="nav-item">
                            <a href="#tab_<?php echo $this->uniqId ?>_<?php echo $this->recordId ?>" class="nav-link  active" data-toggle="tab">Drilldown тохиргоо</a>
                        </li>
                    </ul>
                    <div class="tab-content border-none">
                        <div class="tab-pane active" id="tab_<?php echo $this->uniqId ?>_<?php echo $this->recordId ?>">
                            <div class="row mb10">
                                <div class="col-md-12" data-bp-detail-container="1">
                                    <div class="table-toolbar">
                                        <div class="row">
                                            <div class="col">
                                                <button type="button" class="btn btn-xs green-meadow float-left mr5 bp-add-one-row" onclick="addMainRow_<?php echo $this->uniqId ?>(this, '<?php echo $this->uniqId ?>', '<?php echo $this->recordId ?>');">
                                                    <i class="icon-plus3 font-size-12"></i> Мөр нэмэх </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bp-overflow-xy-auto" style="max-height: 450px; overflow: auto;">
                                        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" data-row-id="<?php echo $this->recordId ?>">
                                            <thead>
                                                <tr>
                                                    <th class="rowNumber" style="width: 30px; ">№</th>
                                                    <th class="text-left" style="width: 250px">Үндсэн жагсаалтын линк баганын параметр</th>
                                                    <th style="width: 250px">Холбогдох метадата</th>
                                                    <th style="width: 100px">Харагдац</th>
                                                    <th style="width: 250px">Нөхцөл</th>
                                                    <th style="width: 100px">Өргөн</th>
                                                    <th style="width: 100px">Өндөр</th>
                                                    <th style="width: 50px"></th>
                                                    <th style="width: 50px"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="tbody mainRow_<?php echo $this->uniqId ?>">
                                                <?php if (issetParamArray($this->option)) {
                                                    $index = 1;
                                                    foreach ($this->option as $key => $row) {
                                                        ?> 
                                                        <tr>
                                                            <td class="text-center middle">
                                                                <span class="orderNumber"><?php echo $index++; ?></span>
                                                            </td>
                                                            <td class="stretchInput text-center ">
                                                                <input type="text" name="linkParam[]" class="form-control form-control-sm stringInit" data-path="linkParam" data-field-name="linkParam" value="<?php echo issetParam($row['linkParam']) ?>" placeholder="Үндсэн жагсаалтын линк баганын параметр">
                                                            </td>
                                                            <td class="stretchInput text-center ">
                                                                <div class="meta-autocomplete-wrap">
                                                                    <div class="input-group double-between-input">
                                                                        <input type="hidden" name="linkIndicatorId" id="linkIndicatorId_valueField" data-path="linkIndicatorId" value="<?php echo issetParam($row['linkIndicatorId']) ?>"  data-row-data="" placeholder="Холбогдох метадата">
                                                                        <input type="text" name="linkIndicatorId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="linkIndicatorId" id="linkIndicatorId_displayField" data-processid="<?php echo $this->uniqId ?>" placeholder="кодоор хайх" data-path="linkIndicatorId_displayField" value="<?php echo issetParam($row['linkIndicatorId_displayField']) ?>">
                                                                        <span class="input-group-btn">
                                                                            <button type="button" class="btn default btn-bordered btn-xs mr-0" onclick="dataViewSelectableGrid('linkIndicatorId', '<?php echo $this->uniqId ?>', '<?php echo $this->lookupId ?>', 'single', 'linkIndicatorId', this);" tabindex="-1">
                                                                                <i class="far fa-search"></i>
                                                                            </button>
                                                                        </span>
                                                                        <span class="input-group-btn flex-col-group-btn">
                                                                            <input type="text" name="linkIndicatorId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="linkIndicatorId" id="linkIndicatorId_nameField" data-processid="<?php echo $this->uniqId ?>" placeholder="нэрээр хайх" data-path="linkIndicatorId_nameField" value="<?php echo issetParam($row['linkIndicatorId_nameField']) ?>">
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="stretchInput text-center ">
                                                                <input type="text" name="showType[]" class="form-control form-control-sm stringInit" data-path="showType" data-field-name="showType" placeholder="Харагдац" value="<?php echo issetParam($row['showType']) ?>">
                                                            </td>
                                                            <td class="stretchInput text-center ">
                                                                <textarea name="criteria[]" class="form-control form-control-sm description_autoInit" data-path="criteria" data-field-name="criteria" style="overflow: hidden; height: 47.6px; min-height: 25px;" placeholder="Нөхцөл"></textarea>
                                                            </td>
                                                            <td class="stretchInput text-center ">
                                                                <input type="text" name="dialogWidth[]" class="form-control form-control-sm stringInit" data-path="dialogWidth" data-field-name="dialogWidth" placeholder="Өргөн" value="<?php echo issetParam($row['dialogWidth']) ?>">
                                                            </td>
                                                            <td class="stretchInput text-center">
                                                                <input type="text" name="dialogHeight[]" class="form-control form-control-sm stringInit" data-path="dialogHeight" data-field-name="dialogHeight" placeholder="Өндөр" value="<?php echo issetParam($row['dialogHeight']) ?>">
                                                            </td>
                                                            <td class="stretchInput text-center ">
                                                                <a href="javascript:;" onclick="paramTreePopup(this, '', 'div#bp-window-<?php echo $this->uniqId ?>:visible');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" title="Дэлгэрэнгүй" data-b-path="META_DM_DRILLDOWN_DTL_STRUCTURE.META_DM_DRILLDOWN_PARAM_STRUCTURE">...</a>
                                                                <div class="param-tree-container-tab param-tree-container hide">
                                                                    <div class="tabbable-line">
                                                                        <ul class="nav nav-tabs d-none">
                                                                            <li class="nav-item" >
                                                                                <a href="#tabstructure_<?php echo $this->uniqId ?>_<?php echo $this->recordId ?>" class="nav-link active" data-toggle="tab">Дамжуулах параметрын тохиргоо</a>
                                                                            </li>
                                                                        </ul>
                                                                        <div class="tab-content border-none">
                                                                            <div class="tab-pane in active" id="tabstructure_<?php echo $this->uniqId ?>_<?php echo $this->recordId ?>">
                                                                                <button type="button" class="btn btn-xs green-meadow ml0 bp-add-one-row bp-subdtl-addrow float-left my-1" onclick="addDtlRow_<?php echo $this->uniqId ?>(this, '<?php echo $this->uniqId ?>', '<?php echo $this->recordId ?>');">
                                                                                    <i class="icon-plus3 font-size-12"></i> Мөр нэмэх </button>
                                                                                <div class="clearfix"></div>
                                                                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-table-subdtl bprocess-theme1" data-row-id="1490688355453297">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="rowNumber" style="width: 30px; ">№</th>
                                                                                            <th data-cell-path="trgParam">Холбогдох метадатаны параметр</th>
                                                                                            <th data-cell-path="srcParam">Үндсэн жагсаалтын параметр</th>
                                                                                            <th style="width:40px"></th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="tbody mainDtlRow_<?php echo $this->uniqId ?>">
                                                                                        <?php if (issetParamArray($row['dtl'])) {
                                                                                            $subIndex = 1;
                                                                                            foreach ($row['dtl'] as $value) {
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td class="text-center middle ">
                                                                                                        <span class="orderNumber"><?php echo $subIndex++; ?></span>
                                                                                                    </td>
                                                                                                    <td class="stretchInput text-center ">
                                                                                                        <input type="text" name="trgParam[][]" class="form-control form-control-sm stringInit dtlRow" data-path="trgParam" data-field-name="trgParam" value="<?php echo issetParam($value['trgParam']) ?>" placeholder="">
                                                                                                    </td>
                                                                                                    <td class="stretchInput text-center ">
                                                                                                        <input type="text" name="srcParam[][]" class="form-control form-control-sm stringInit dtlRow" data-path="srcParam" data-field-name="srcParam" value="<?php echo issetParam($value['srcParam']) ?>" placeholder="">
                                                                                                    </td>
                                                                                                    <td class="text-center stretchInput middle">
                                                                                                        <a href="javascript:;" class="btn red btn-xs bp-remove-row" title="Устгах">
                                                                                                            <i class="fa fa-trash"></i>
                                                                                                        </a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="text-center stretchInput middle">
                                                                <a href="javascript:;" class="btn red btn-xs bp-remove-row" title="Устгах">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                <?php 
                                                    }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-none">
            <div class="mainRow">
                <table>
                    <tbody>
                        <tr>
                            <td class="text-center middle">
                                <span class="orderNumber"></span>
                            </td>
                            <td class="stretchInput text-center ">
                                <input type="text" name="linkParam[]" class="form-control form-control-sm stringInit" data-path="linkParam" data-field-name="linkParam" value="" placeholder="Үндсэн жагсаалтын линк баганын параметр">
                            </td>
                            <td class="stretchInput text-center ">
                                <div class="meta-autocomplete-wrap">
                                    <div class="input-group double-between-input">
                                        <input type="hidden" name="linkIndicatorId" id="linkIndicatorId_valueField" data-path="linkIndicatorId" value="" data-row-data="" placeholder="Холбогдох метадата">
                                        <input type="text" name="linkIndicatorId_displayField" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" data-field-name="linkIndicatorId" id="linkIndicatorId_displayField" data-processid="<?php echo $this->uniqId ?>" placeholder="кодоор хайх" data-path="linkIndicatorId_displayField" >
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default btn-bordered btn-xs mr-0" onclick="dataViewSelectableGrid('linkIndicatorId', '<?php echo $this->uniqId ?>', '<?php echo $this->lookupId ?>', 'single', 'linkIndicatorId', this);" tabindex="-1">
                                                <i class="far fa-search"></i>
                                            </button>
                                        </span>
                                        <span class="input-group-btn flex-col-group-btn">
                                            <input type="text" name="linkIndicatorId_nameField" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" data-field-name="linkIndicatorId" id="linkIndicatorId_nameField" data-processid="<?php echo $this->uniqId ?>" placeholder="нэрээр хайх" data-path="linkIndicatorId_nameField" >
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="stretchInput text-center ">
                                <input type="text" name="showType[]" class="form-control form-control-sm stringInit" data-path="showType" data-field-name="showType" value="" placeholder="Харагдац">
                            </td>
                            <td class="stretchInput text-center ">
                                <textarea name="criteria[]" class="form-control form-control-sm description_autoInit" data-path="criteria" data-field-name="criteria" style="overflow: hidden; height: 47.6px; min-height: 25px;" placeholder="Нөхцөл"></textarea>
                            </td>
                            <td class="stretchInput text-center ">
                                <input type="text" name="dialogWidth[]" class="form-control form-control-sm stringInit" data-path="dialogWidth" data-field-name="dialogWidth" value="" placeholder="Өргөн">
                            </td>
                            <td class="stretchInput text-center">
                                <input type="text" name="dialogHeight[]" class="form-control form-control-sm stringInit" data-path="dialogHeight" data-field-name="dialogHeight" value="" placeholder="Өндөр">
                            </td>
                            <td class="stretchInput text-center ">
                                <a href="javascript:;" onclick="paramTreePopup(this, '', 'div#bp-window-<?php echo $this->uniqId ?>:visible');" class="hide-tbl btn btn-sm purple-plum bp-btn-subdtl" title="Дэлгэрэнгүй" data-b-path="META_DM_DRILLDOWN_DTL_STRUCTURE.META_DM_DRILLDOWN_PARAM_STRUCTURE">...</a>
                                <div class="param-tree-container-tab param-tree-container hide">
                                    <div class="tabbable-line">
                                        <ul class="nav nav-tabs d-none">
                                            <li class="nav-item" >
                                                <a href="#tabstructure_<?php echo $this->uniqId ?>_<?php echo $this->recordId ?>" class="nav-link active" data-toggle="tab">Дамжуулах параметрын тохиргоо</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content border-none">
                                            <div class="tab-pane in active" id="tabstructure_<?php echo $this->uniqId ?>_<?php echo $this->recordId ?>">
                                                <button type="button" class="btn btn-xs green-meadow ml0 bp-add-one-row bp-subdtl-addrow float-left my-1" onclick="addDtlRow_<?php echo $this->uniqId ?>(this, '<?php echo $this->uniqId ?>', '<?php echo $this->recordId ?>');">
                                                    <i class="icon-plus3 font-size-12"></i> Мөр нэмэх </button>
                                                <div class="clearfix"></div>
                                                <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-table-subdtl bprocess-theme1" data-row-id="1490688355453297">
                                                    <thead>
                                                        <tr>
                                                            <th class="rowNumber" style="width: 30px; ">№</th>
                                                            <th data-cell-path="trgParam">Холбогдох метадатаны параметр</th>
                                                            <th data-cell-path="srcParam">Үндсэн жагсаалтын параметр</th>
                                                            <th style="width:40px"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="tbody mainDtlRow_<?php echo $this->uniqId ?>"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center stretchInput middle">
                                <a href="javascript:;" class="btn red btn-xs bp-remove-row" title="Устгах">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mainDtlRow">
                <table>
                    <tbody>
                        <tr>
                            <td class="text-center middle ">
                                <span class="orderNumber"></span>
                            </td>
                            <td class="stretchInput text-center ">
                                <input type="text" name="trgParam[][]" class="form-control form-control-sm stringInit dtlRow" data-path="trgParam" data-field-name="trgParam" value="" placeholder="">
                            </td>
                            <td class="stretchInput text-center ">
                                <input type="text" name="srcParam[][]" class="form-control form-control-sm stringInit dtlRow" data-path="srcParam" data-field-name="srcParam" value="" placeholder="">
                            </td>
                            <td class="text-center stretchInput middle">
                                <a href="javascript:;" class="btn red btn-xs bp-remove-row" title="Устгах">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">

    function addDtlRow_<?php echo $this->uniqId ?> (element, uniqId, recordId) {
        var _parent = $(element).closest('.param-tree-container');
        var _row = $('.mainDtlRow > table > tbody').html();
        _parent.find('.mainDtlRow_<?php echo $this->uniqId ?>').append(_row).promise().done(function () {
            orderNumber<?php echo $this->uniqId ?> (_parent.find('.mainDtlRow_<?php echo $this->uniqId ?> > tr'));
        });
    }

    function addMainRow_<?php echo $this->uniqId ?> (element, uniqId, recordId) {
        var _row = $('.mainRow > table > tbody').html();
        $('.mainRow_<?php echo $this->uniqId ?>').append(_row).promise().done(function () {
            orderNumber<?php echo $this->uniqId ?> ($('.mainRow_<?php echo $this->uniqId ?> > tr'));
        });
    }

    $('body').on('click', '.bp-remove-row', function () {
        var $this = $(this);
        var $dialogName = 'dialog-confirm-bp-detail';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $dialog.empty().append('Та бичлэгийг устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Анхааруулга',
            width: 370,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                    $this.closest('tr').remove();
                    $dialog.dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });

        $dialog.dialog('open');
    });

    function orderNumber<?php echo $this->uniqId ?> (parent) {
        var number = 1;
        $.each(parent, function(i, dtl) {
            $(dtl).find('.orderNumber').html(number + '.');
            number++;
        });
    }
</script>