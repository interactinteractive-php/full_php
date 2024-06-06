<script type="text/javascript">
    
    $(document).ready(function () {
        
        var $window_<?php echo $this->metaDataId; ?> = $(window).height() - 160;
        var $window1_<?php echo $this->metaDataId; ?> = $(window).height() - 250;
        
        <?php if ($this->useBasket) { ?>
            $('#dialog-dataview-selectable-<?php echo $this->metaDataId; ?>').find('.dynamic-height-<?php echo $this->metaDataId; ?>').attr('style', 'max-height: 550px; overflow: auto; overflow-x: hidden; ')
        <?php } else { ?>
            $('#dialog-dataview-selectable-<?php echo $this->metaDataId; ?>').find('.dynamic-height-<?php echo $this->metaDataId; ?>').attr('style', 'max-height: ' + $window_<?php echo $this->metaDataId; ?> + 'px; overflow: auto; overflow-x: hidden; ')
        <?php } ?>
        
        $('#dialog-dataview-selectable-<?php echo $this->metaDataId; ?>').find('#objectdatagrid-<?php echo $this->metaDataId; ?>').datagrid('resize', {
            height: $window1_<?php echo $this->metaDataId; ?>
        });
        
        $('.ecommerce_timeline').find('.datagrid-body').addClass('card-columns');
        
        <?php if (isset($this->useBasket) && $this->useBasket && isset($this->selectedRowData[0])) { ?>
            _selectedRows_<?php echo $this->metaDataId; ?> = <?php echo json_encode($this->selectedRowData);  ?>;
        <?php } ?>
    });

    $(function () {
        
        <?php if ($this->isUseSidebar === '0') { ?>
            $('.ecommerce_<?php echo $this->metaDataId ?>').find('.sidebar-right-toggle').trigger('click');
        <?php } ?>

        $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').on('click', '.tab-criteria-value', function (e) {
            /*Gantt div visible ued*/
            var $this = $(this);
            $('#objectDataView_<?php echo $this->metaDataId; ?>').find('.list_name').text($this.find('.greenbtntext').text());
            if ($this.hasClass('card'))
                return;

            dv_search_<?php echo $this->metaDataId; ?>.find('input[data-path="' + $this.data('path') + '"]').val($this.data('id'));

            if (!$this.hasAttr('onclick') || ($this.hasAttr('onclick') && $this.attr('onclick').indexOf('dataViewFilterCardFieldPath_') == -1)) {
                dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
            }
        });

        $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').on('click', '.nextTabCriteriaData', function (e) {
            var $this = $(this),
                    indexKey = Number($this.attr('data-step'));

            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('.prevTabCriteriaData').show();

            $this.parent().find('.prevTabCriteriaData').attr('data-step', indexKey);
            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('div[data-index="' + indexKey + '"]').addClass('hidden');
            $this.attr('data-step', (++indexKey));

            if (!$('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('div[data-index="' + (++indexKey) + '"]').length) {
                $this.hide();
            }
        });

        $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').on('click', '.prevTabCriteriaData', function (e) {
            var $this = $(this),
                    indexKey = Number($this.attr('data-step'));

            if (indexKey == 0) {
                $this.hide();
            }
            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('.nextTabCriteriaData').show();

            $this.parent().find('.nextTabCriteriaData').attr('data-step', indexKey);
            $('#tab-lookupdata-<?php echo $this->metaDataId; ?>').find('div[data-index="' + indexKey + '"]').removeClass('hidden');
            $this.attr('data-step', (--indexKey));
        });

        dv_search_<?php echo $this->metaDataId; ?>.find('.bp-icon-selection').on('click', 'li', function (e) {
            if ($('.div-ganttLayout-<?php echo $this->metaDataId; ?>').is(":visible")) {
                dataId = e.currentTarget.getAttribute('data-id');
                gantt.clearAll();

                setTimeout(function () {
                    var dvSearchParam = {
                        metaDataId: '<?php echo $this->metaDataId; ?>',
                        defaultCriteriaData: $("form#default-criteria-form, form.mandatory-criteria-form-<?php echo $this->metaDataId; ?>", "div#object-value-list-<?php echo $this->metaDataId; ?>").serialize(), 
                        workSpaceId: '<?php echo issetParam($this->workSpaceId); ?>', 
                        workSpaceParams: '<?php echo issetParam($this->workSpaceParams); ?>', 
                        uriParams: '<?php echo $this->uriParams; ?>', 
                        drillDownDefaultCriteria: '<?php echo isset($this->drillDownDefaultCriteria) ? $this->drillDownDefaultCriteria : ''; ?>', 
                        treeConfigs: '<?php echo $this->isTreeGridData; ?>', 
                        filterRules: getDataViewFilterRules('<?php echo $this->metaDataId; ?>', false), 
                        ignorePermission: '<?php echo isset($this->ignorePermission) ? $this->ignorePermission : ''; ?>', 
                        subQueryId: $('#subQueryId-<?php echo $this->metaDataId; ?>').val()
                    };

                    gantt.clearAll();
                    gantt.ajax.post({ 
                        url:"Mdwidget/getEvents?metaDataId=<?php echo $this->metaDataId; ?>",
                        method:"POST",
                        data: dvSearchParam
                    }).then(function(response){
                        gantt.parse(response.responseText);
                    });
                }, 1);

            }else{
                var $this = $(this);
                setTimeout(function () {
                    dv_search_<?php echo $this->metaDataId; ?>.find('.dataview-default-filter-btn').click();
                    $('.tab-lookupcriteria-<?php echo $this->metaDataId; ?>').first().click();
                    $('#objectDataView_<?php echo $this->metaDataId; ?>').find('.list_name').text($this.find('p').text());
                }, 10);
            }
        });

        if ($(".sub-dv-list-<?php echo $this->metaDataId; ?>").find("li:eq(1)").length && $(".sub-dv-list-<?php echo $this->metaDataId; ?>").find("li:eq(0)").data('permission') == '1') {
            $(".sub-dv-list-<?php echo $this->metaDataId; ?>").find("li:eq(1)").find("a").click();
        }

        $("#checkAll_<?php echo $this->metaDataId; ?>").click(function () {
            $('#basket_ecommerce_<?php echo $this->metaDataId; ?>').find('input[type="checkbox"]').prop('checked', this.checked).parent().addClass('checked');
            if (!this.checked) {
                $('#basket_ecommerce_<?php echo $this->metaDataId; ?>').find('input[type="checkbox"]').parent().removeClass('checked');
            }
        });
        
        <?php if (($this->metaDataId == '1566556713853' || $this->metaDataId == '1566556713932') && !$this->useBasket) { ?>
                $(document).bind('keydown', 'Alt+z', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-meta-data-id="1566556713853"]:visible').click();
                    e.preventDefault();
                    $(document).off("keydown");
                    return false;
                });        
                $(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+z', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-meta-data-id="1566556713853"]:visible').click();
                    e.preventDefault();
                    $(document.body).off("keydown");
                    return false;
                });        
                $(document).bind('keydown', 'Alt+x', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-meta-data-id="1566556713932"]').click();
                    e.preventDefault();
                    $(document).off("keydown");
                    return false;
                });        
                $(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+x', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-meta-data-id="1566556713932"]').click();
                    e.preventDefault();
                    $(document.body).off("keydown");
                    return false;
                });      
                $(document).bind('keydown', 'Alt+p', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="SCAN_LOCKER"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+p', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="SCAN_LOCKER"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document).bind('keydown', 'Shift+x', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="checkMembershipLockerSaunaWoman_DV"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document.body).on('keydown', 'input, select, textarea, a, button', 'Shift+x', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="checkMembershipLockerSaunaWoman_DV"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document).bind('keydown', 'Alt+o', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="checkMembershipLockerSaunaMan_DV"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+o', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="checkMembershipLockerSaunaMan_DV"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document).bind('keydown', 'Alt+s', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="printIpTerminalSettlement"]').click();
                    e.preventDefault();
                    return false;
                });        
                $(document.body).on('keydown', 'input, select, textarea, a, button', 'Alt+s', function(e){
                    $('#object-value-list-<?php echo $this->metaDataId; ?>').find('a[data-dvbtn-processcode="printIpTerminalSettlement"]').click();
                    e.preventDefault();
                    return false;
                });        
        <?php } ?>

        <?php if (Config::getFromCache('ECOMMERCE_GANTT_CHART') && !$this->useBasket) {  ?> 
            $.ajax({
                type: 'get',
                url: 'Mdwidget/checkGantt?metaDataId=<?php echo $this->metaDataId; ?>', 
                success: function (res) {
                    if(res == 1){ callGanttView_<?php echo $this->metaDataId ?>('<?php echo $this->metaDataId; ?>', this); }
                }
            })
        <?php }?>

        $("#topbuttonInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".dvecommerce .topbutton .btn-lg").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        $("#topbuttonInput2").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".dvecommerce .general-item-list > .item").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });   
        
    });

    function lookupCriteriaTabMoreLink(elem) {
        if ($(elem).hasClass('moreclick')) {
            $(elem).addClass('lessclick').removeClass('moreclick').text('Бүгд').attr('title', 'Дэлгэрэнгүй харах').parent().css('height', '32px');
        } else {
            $(elem).addClass('moreclick').removeClass('lessclick').text('Хураангуй').attr('title', 'Хураангуй харах').parent().css('height', '');
        }
    }
    
    function selectAllBasket_<?php echo $this->metaDataId ?> (elem) {
        if (!$("#basket_ecommerce_<?php echo $this->metaDataId; ?>").length) {
            return;
        }
        
        if ('<?php echo $this->isGridType; ?>' == 'treegrid') {
            var rows = objectdatagrid_1565070690138433.treegrid('getData');
        } else {
            var rows = objectdatagrid_<?php echo $this->metaDataId; ?>.datagrid('getRows');
        }
        
        $.each(rows, function (index, row) {
            var isAdded = false,
                rowId = row.id;

            for (var key in _selectedRows_<?php echo $this->metaDataId; ?>) {
                var basketRow = _selectedRows_<?php echo $this->metaDataId; ?>[key], childId = basketRow.id;

                if (rowId == childId) {
                    isAdded = true;
                    break;
                }
            }

            <?php if ($this->layoutType === 'ecommerce' && isset($this->useBasket) && $this->useBasket) { 
                $typeRow = $this->row['dataViewLayoutTypes']['ecommerce']; ?>
                var chooseTypeDataGrid = '<?php echo $this->chooseTypeBasket; ?>';

                if (!isAdded) {
                    var $index = _selectedRows_<?php echo $this->metaDataId; ?>.length;

                    _selectedRows_<?php echo $this->metaDataId; ?>.push(row); 
                    <?php if (isset($typeRow['fields']['basketname'])) { ?>
                        var basketPhoto = '<span class="tree-icon tree-file "></span>'; 
                        
                        <?php if (issetParam($typeRow['fields']['basketphoto']) !== '') { ?>
                            if (row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>'].indexOf('<img') > -1) {
                                basketPhoto = row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>'];
                            } else {
                                basketPhoto = '<img src="'+row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>']+'" width="25" height="25" class="rounded-circle" alt="" onerror="onUserImgError(this);">'; 
                            }
                        <?php } ?>
                            
                        var $appendBasketHtml = '';
                                    $appendBasketHtml += '<li data-index="'+ $index +'" class="datagrid-row media p-1 border-bottom-1 border-gray"style="height: 43px;">' 
                                        + basketPhoto;
                                        $appendBasketHtml += '<div class="media-body <?php echo issetParam($typeRow['fields']['basketcode']) == '' ? 'one-row' : '' ?>">';
                                            $appendBasketHtml += '<div class="line-height-normal d-flex align-items-center">';
                                                $appendBasketHtml += '<span>' + row['<?php echo Str::lower($typeRow['fields']['basketname']); ?>'] + '</span>';
                                            $appendBasketHtml += '</div>';
                                            $appendBasketHtml += '<?php if (issetParam($typeRow['fields']['basketcode'])) { ?>';
                                                $appendBasketHtml += '<span class="memberposition" style="font-size: 10px;color: #999;text-transform: uppercase;">' + row['<?php echo Str::lower($typeRow['fields']['basketcode']); ?>'] + '</span>';
                                            $appendBasketHtml += '<?php } ?>';
                                        $appendBasketHtml += '</div>';
                                        $appendBasketHtml += '<div class="ml10 mr10 align-self-center">';
                                            $appendBasketHtml += '<a href="javascript:;" class="position-relative" onclick="removeCommerceBasket<?php echo $this->metaDataId; ?>(this)"><i class="fa fa-close basket-choose-icon"></i></a>';
                                        $appendBasketHtml += '</div>';
                                    $appendBasketHtml += '</li>';
                                    
                        $("#basket_ecommerce_<?php echo $this->metaDataId; ?>").append($appendBasketHtml);
                        $('.basket_ecommerce_counter_<?php echo $this->metaDataId; ?>').text('('+_selectedRows_<?php echo $this->metaDataId; ?>.length+')');

                        if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
                            $('#objectdatagrid-<?php echo $this->metaDataId ?>').closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-choose-btn").click();
                        }                                
                    <?php } ?>
                }
            <?php } ?>
        });
    }
    
    function removeAllBasket_<?php echo $this->metaDataId ?> (elem) {
        $("#basket_ecommerce_<?php echo $this->metaDataId; ?>").empty();
        $('.basket_ecommerce_counter_<?php echo $this->metaDataId; ?>').text('(0)');
        _selectedRows_<?php echo $this->metaDataId; ?> = [];
    }
    
    function pushCommerceBasket<?php echo $this->metaDataId; ?>(elem) {

        if (!$("#basket_ecommerce_<?php echo $this->metaDataId; ?>").length) {
            return;
        }

        var row = JSON.parse(decodeURIComponent($(elem).data('row-data')));
        var isAdded = false,
            rowId = row.id;
    
        for (var key in _selectedRows_<?php echo $this->metaDataId; ?>) {
            var basketRow = _selectedRows_<?php echo $this->metaDataId; ?>[key], childId = basketRow.id;

            if (rowId == childId) {
                isAdded = true;
                break;
            }
        }

        <?php if ($this->layoutType === 'ecommerce' && isset($this->useBasket) && $this->useBasket) { 
            $typeRow = $this->row['dataViewLayoutTypes']['ecommerce']; ?>
            var chooseTypeDataGrid = '<?php echo $this->chooseTypeBasket; ?>';

            if (!isAdded) {
                var $index = _selectedRows_<?php echo $this->metaDataId; ?>.length;
                if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
                    _selectedRows_<?php echo $this->metaDataId; ?> = [];
                }
                
                _selectedRows_<?php echo $this->metaDataId; ?>.push(row); 
                
                <?php if (isset($typeRow['fields']['basketname'])) { ?>
                    
                    var $appendHtml = ''+
                        '<li data-index="'+ $index +'" class="datagrid-row media p-1 border-bottom-1 border-gray" style="height: 40px;">'+
                        '    <a href="javascript:;" class="mr-2 position-relative">';
                        <?php if (isset($typeRow['fields']['basketphoto'])) { ?>
                            if (row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>'] && row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>'].indexOf('<img') > -1) {
                                $appendHtml += row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>'];
                            } else {
                                $appendHtml += '<img src="'+row['<?php echo Str::lower($typeRow['fields']['basketphoto']); ?>']+'" width="28" height="28" class="rounded-circle" onerror="onUserImgError(this);">';
                            }
                        <?php } ?>
                        
                        $appendHtml += '</a>'+
                        '    <div class="media-body">'+
                        '        <div class="membername text-blue text-uppercase line-height-normal d-flex align-items-center font-size-11">'+
                        '            <span>'+row['<?php echo Str::lower($typeRow['fields']['basketname']); ?>']+'</span>'+
                        '        </div>';
                
                        <?php if (isset($typeRow['fields']['basketcode'])) { ?>
                        $appendHtml += '<span class="memberposition" style="font-size: 10px;color: #999;text-transform: uppercase;">'+row['<?php echo Str::lower($typeRow['fields']['basketcode']); ?>']+'</span>';
                        <?php } ?>
                            
                        $appendHtml += '</div>'+
                        '    <div class="ml10 mr10 align-self-center">'+
                        '        <a href="javascript:;" class="position-relative" onclick="removeCommerceBasket<?php echo $this->metaDataId; ?>(this)">'+
                        '            <i class="fa fa-close basket-choose-icon"></i>'+
                        '        </a>'+
                        '    </div>'+
                        '</li>'
                
                    $("#basket_ecommerce_<?php echo $this->metaDataId; ?>").append($appendHtml);
                    $('.basket_ecommerce_counter_<?php echo $this->metaDataId; ?>').text('('+_selectedRows_<?php echo $this->metaDataId; ?>.length+')');

                    if (chooseTypeDataGrid == 'single' || chooseTypeDataGrid == 'singlealways') {
                        $('#objectdatagrid-<?php echo $this->metaDataId ?>').closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-choose-btn").click();
                    }                                
                <?php } ?>
            }
        <?php } ?>
    }
        
    function removeCommerceBasket<?php echo $this->metaDataId; ?>(elem) {
        var $this = $(elem), index = $this.closest('li').data('index') - 1, $ulparent = $this.closest('ul');
        $this.closest('li').remove();
        delete _selectedRows_<?php echo $this->metaDataId; ?>[index];

        $('.basket_ecommerce_counter_<?php echo $this->metaDataId; ?>').text('(' + $ulparent.children().length + ')');
        _selectedRows_<?php echo $this->metaDataId; ?> = _selectedRows_<?php echo $this->metaDataId; ?>.filter(function (el) {
            return true;
        });
    }

    function appMultiTabEcommerce<?php echo $this->metaDataId; ?>(elem, metaId, name) {
        if (typeof vr_top_menu !== 'undefined' && vr_top_menu) {

            var $tabMainContainer = $('div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs');

            if ($tabMainContainer.length == 0) {
                $("div.pf-header-main-content").html('<div class="">' +
                        '<div class="card light shadow card-multi-tab">' +
                        '<div class="card-body">' +
                        '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

                $('div.m-tab').html('<div class="card-header header-elements-inline tabbable-line">' +
                        '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                        '</div>');

                $tabMainContainer = $('body').find("div.m-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
            }

        } else {
            var $tabMainContainer = $("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
            if ($tabMainContainer.length == 0) {
                $("div.pf-header-main-content").html('<div class="">' +
                        '<div class="card light shadow card-multi-tab">' +
                        '<div class="card-header header-elements-inline tabbable-line">' +
                        '<ul class="nav nav-tabs card-multi-tab-navtabs"></ul>' +
                        '<div class="header-elements">' +
                        '<div class="list-icons">' +
                        '<a class="list-icons-item" data-action="fullscreen"></a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="card-body">' +
                        '<div class="tab-content card-multi-tab-content"></div></div></div></div>');

                $tabMainContainer = $('body').find("div.card-multi-tab > div.tabbable-line > ul.card-multi-tab-navtabs");
            }
        }

        var param = {},
                _this = $(elem);

        $tabMainContainer.find("a[href='#app_tab_<?php echo $this->metaDataId; ?>']").parent().attr('data-type', 'dataview');
        param['metaDataId'] = metaId;
        param['title'] = name;
        param['type'] = 'dataview';
        appMultiTab(param, _this.find('a'), function (div, param) {
            multiTabCloseConfirm($tabMainContainer.find("a[href='#app_tab_<?php echo $this->metaDataId; ?>']"));
        });
    }

</script>
