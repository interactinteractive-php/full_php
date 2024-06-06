<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet" />
<div class="intranet ea-repository-<?php echo $this->uniqId ?>">
    <div class="page-content">
        <?php include_once "leftsidebar.php"; ?>
        <div class="content-wrapper">
            <div class="page-header page-header-light bg-white mb-3">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:10px 20px;">
                    <div class="d-flex">
                        <span id="content_title" class="font-weight-bold"><?php echo isset($this->getIntranetAllContent[0]['description']) ? $this->getIntranetAllContent[0]['description'] : Config::getFromCache('TITLE'); ?></span>
                    </div>

                    <div class="header-elements d-none" style='display: none !important;'>
                        <form action="#">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="search" class="form-control wmin-250" placeholder="<?php echo Lang::line('EA_CATEGORY_002') ?>">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ea-content"></div>
        </div>
    </div>
</div>

<style type="text/css">
    .media-list-<?php echo $this->uniqId ?> li:hover  a::after {
        display: block;
        clear: both;
        content: "\f014";
        position: relative;
        bottom: 25px;
        left: 15px;
        font: normal normal normal 14px/1 "Font Awesome 5 Pro";
        color: #F00;
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .media-title { 
        /*text-align: justify;*/
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .meta-toolbar { 
        background: none !important;
    }
    
    .ea-repository-<?php echo $this->uniqId ?> div.checker span, .ea-repository-<?php echo $this->uniqId ?>  div.radio span {
        display: inline-block;
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .btn-sm {
        padding: 6px 8px;
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .form-control-sm {
        height: 37px;
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .xs-form input.form-control {
        height: 37px;
    }
    .ea-repository-<?php echo $this->uniqId ?> .xs-form .generalledger-header-content .input-group-btn > .btn, 
    .ea-repository-<?php echo $this->uniqId ?> .xs-form .bp-header-param .input-group-btn > .btn {
        padding: 6px 11px !important
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .bp-btn-quickmenu {
        display: none !important;
    }
    
    .ea-repository-<?php echo $this->uniqId ?> .nav-item-submenu>.nav-link:after { 
        /*content: '' !important;*/
    }
</style>

<script type="text/javascript">
    
    $(function() {
        $('#tooltip-demo').tooltip();
    });
    
    function getSubMenuEa_<?php echo $this->uniqId ?>(element, id, level, metadataid) {
        var $element = $(element);
        
        if ($element.attr('li-status') === 'open') {
            $element.attr('li-status', 'closed');
            $element.closest('li').find('.add-submenu-' + id).attr('style', "display: none;")
        } else {
            var $dataRow = JSON.parse($element.attr('data-row'));
            
            $element.attr('li-status', 'open')
            $.ajax({
                url: 'mdasset/getSubMenuRender/',
                data: {'id' : id, 'subLevel': level, uniqId: '<?php echo $this->uniqId ?>', dataRow: $dataRow},
                type: 'post',
                dataType: 'JSON',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Уншиж байна түр хүлээнэ үү...',
                        boxed: true
                    });
                },
                success: function(result) {
                    if (typeof result.menu !== 'undefined' && result.menu == '1') {
                        $('.ea-repository-<?php echo $this->uniqId ?>').find(".add-submenu-" + id).empty().append(result.Html).attr('style', "display: block;");
                        if (typeof $dataRow['isopen'] !== 'undefined' && $dataRow['isopen'] == '0') {
                            renderContent_<?php echo $this->uniqId ?>(id, metadataid, $dataRow['name'], $element.attr('data-row'));
                        }
                    } else {
                        renderContent_<?php echo $this->uniqId ?>(id, metadataid, $dataRow['name'], $element.attr('data-row'));
                        $('.ea-repository-<?php echo $this->uniqId ?>').find('.ea-content').empty();
                    }
                    Core.unblockUI();
                }
            });
        }

    }
    
    <?php if (isset($this->selectedId) && $this->selectedId) { ?>
        $('.ea-repository-<?php echo $this->uniqId ?>').find('#menu<?php echo $this->selectedId; ?>').click();
    <?php } ?>
    
    function renderContent_<?php echo $this->uniqId ?> (menuId, metadataid, menuName, dataRow) {
        $.ajax({
            url: 'mdlayout/renderContentForm/',
            data: {
                'menuId' : menuId, 
                'metadataid': metadataid, 
                uniqId: '<?php echo $this->uniqId ?>',
                legendData: <?php echo json_encode($this->legendData); ?>,
                headerLegendData: <?php echo json_encode($this->getHeaderLegendData); ?>
            },
            type: 'post',
            dataType: 'JSON',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Уншиж байна түр хүлээнэ үү...',
                    boxed: true
                });
            },
            success: function(result) {
                $('.ea-repository-<?php echo $this->uniqId ?>').find(".ea-content").empty().append(result.Html).promise().done(function () {
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('#content_title').html(menuName);
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.add-ea-<?php echo $this->uniqId ?>').attr('data-row', dataRow);
                });
                Core.unblockUI();
            }
        });
    }
    
    function getEaContentRender_<?php echo $this->uniqId ?>(elem, name) {
        var $elementDataRow = $(elem).attr('data-row');
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: 1560236126310,
                dmMetaDataId: 1565060041305,
                isDialog: false,
                isHeaderName: false,
                workSpaceId: 1560141748213,
                isBackBtnIgnore: 1,
                workSpaceParams: $elementDataRow,
                workSpaceParamsType: 'array',
                openParams: {}
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function(data){
                $('.ea-repository-<?php echo $this->uniqId ?>').find('.ea-content').empty().html(data.Html).promise().done(function () {
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.bp-btn-back').addClass('hidden');
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('#content_title').html(name);
                    $(elem).closest('li').addClass('active');
                });
            },
            error: function(){
                alert('Error');
            }
        }).done(function(){
            Core.initBPAjax($('.ea-repository-<?php echo $this->uniqId ?>').find('.ea-content'));
            Core.unblockUI();
        });

    }
    
    $('body').on('click', '.filter-btn-<?php echo $this->uniqId ?>', function() {
    
        var $filterData = $('#filter-form-<?php echo $this->uniqId ?>').serialize();
        console.log($filterData);
        
    });
    
    $('body').on('click', '.add-ea-<?php echo $this->uniqId ?>', function () {
        var $this = $(this);
        var $dataRow = JSON.parse($this.attr('data-row'));
        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: 1559891580909,
                dmMetaDataId: 1565060041305,
                isDialog: false,
                isHeaderName: false,
                workSpaceId: 1560141748213,
                isBackBtnIgnore: 1,
                workSpaceParams: 'workSpaceParam%5Btemplateid%5D=' + $dataRow['id'],
                openParams: {}
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
            },
            success: function(data){
                $('.ea-repository-<?php echo $this->uniqId ?>').find('.ea-content').empty().html(data.Html).promise().done(function () {
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.bp-btn-back').addClass('hidden');
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('#content_title').html(name);
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('input[name="param[templateId]"]').val($dataRow['id']);
                    
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.bpMainSaveButton').attr('callback-datarow', $this.attr('data-row'));
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.bpMainSaveButton').attr('callback-fnc', 'renderContent_<?php echo $this->uniqId ?>');
                    
//                    renderContent_<?php echo $this->uniqId ?> ($dataRow.id, $dataRow.metadataid, $dataRow.name, $dataRow);
                });
            },
            error: function(){
                alert('Error');
            }
        }).done(function(){
            Core.initBPAjax($('.ea-repository-<?php echo $this->uniqId ?>').find('.ea-content'));
            Core.unblockUI();
        });
    });
    
    /*
    $('body').on('click', '.media-list-<?php echo $this->uniqId ?> li > a::after', function () {
        alert('echo 1');
    });*/
</script>