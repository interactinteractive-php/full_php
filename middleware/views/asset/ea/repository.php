<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet" />
<div class="intranet ea-repository-<?php echo $this->uniqId ?>">
    <div class="page-content">
        <?php include_once "leftsidebar.php"; ?>
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md" style="width:18.875rem;background: #f2f2f2;">
            <div class="sidebar-mobile-toggler text-center">
                <a href="javascript:void(0);" class="sidebar-mobile-secondary-toggle">
                    <i class="icon-arrow-left8"></i>
                </a>
                <span class="font-weight-semibold">Secondary sidebar</span>
                <a href="javascript:void(0);" class="sidebar-mobile-expand">
                    <i class="icon-screen-full"></i>
                    <i class="icon-screen-normal"></i>
                </a>
            </div>
            <div class="sidebar-content" style="background: #f2f2f2;">
                <div class="card">
                    <div class="card-header bg-white header-elements-inline">
                        <span id="category_title" class="text-uppercase font-weight-bold line-height-normal"><?php echo Lang::line('EA_004') ?></span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="add-ea-<?php echo $this->uniqId ?>" href="javascript:;" ><i class="icon-plus3 font-size-12"></i></a>
                            </div>
                        </div>
                    </div>

                    <ul id="all-content" class="media-list-<?php echo $this->uniqId ?> media-list media-list-linked my-2">
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="page-header page-header-light bg-white mb-3">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:10px 20px;">
                    <div class="d-flex">
                        <span id="content_title" class="font-weight-bold"><?php echo $this->getIntranetAllContent[0]['description']  ?></span>
                    </div>

                    <div class="header-elements d-none">
                        <form action="#">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="search" class="form-control wmin-250" placeholder="<?php echo Lang::line('EA_005') ?>">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ea-content">
            </div>
        </div>
        <?php include_once "rightsidebar.php"; ?>
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
    
</style>

<script type="text/javascript">
    
    $(function() {
        $('#tooltip-demo').tooltip();
    });
    
    function getSubMenuEa_<?php echo $this->uniqId ?>(element, id, level, metadataid) {
        var $element = $(element);

        $metedataid = '1559891180690';
        
        if ($element.attr('li-status') === 'open') {
            $element.attr('li-status', 'closed');
            $element.closest('li').find('.add-submenu-' + id).attr('style', "display: none;")
        } else {
            var $dataRow = JSON.parse($element.attr('data-row'));
            
            $element.attr('li-status', 'open')
            $.ajax({
                url: 'mdasset/getSubMenuRender/',
                data: {'id' : id, 'subLevel': level, uniqId: '<?php echo $this->uniqId ?>', dataRow: $dataRow,  'metadata': $metedataid },
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
//                        $element.closest('li').find('.nav-link:after').attr('style', "-webkit-transform: rotate(270deg); transform: rotate(270deg);")
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
    
    function renderContent_<?php echo $this->uniqId ?> (menuId, metadataid, menuName, dataRow) {
        
        $('.ea-repository-<?php echo $this->uniqId ?>').find('.add-ea-<?php echo $this->uniqId ?>').attr('data-row', '');
        $('.ea-repository-<?php echo $this->uniqId ?>').find('.filter-btn-<?php echo $this->uniqId ?>').attr('data-row', '');

        $.ajax({
            url: 'mdasset/renderContentEa/',
            data: {
                'menuId' : menuId, 
                'metadataid': metadataid, 
                uniqId: '<?php echo $this->uniqId ?>',
                filterParam: $('#filter-form-<?php echo $this->uniqId ?>').serialize()
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
                $('.ea-repository-<?php echo $this->uniqId ?>').find("#all-content").empty().append(result.Html).promise().done(function () {
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('#category_title').html(menuName);
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.add-ea-<?php echo $this->uniqId ?>').attr('data-row', dataRow);
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.filter-btn-<?php echo $this->uniqId ?>').attr('data-row', dataRow);
                    $('.ea-repository-<?php echo $this->uniqId ?>').find('.ea-content').empty();
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
        var $this = $(this);
        
        if (typeof $this.attr('data-row') === 'undefined') {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: '<?php echo Lang::line('EA_001') ?>-ээс сонгогдсон мөр олдсонгүй',
                type: 'error',
                sticker: false
            });
        }
        
        if ($this.attr('data-row') === '') {
            PNotify.removeAll();
            new PNotify({
                title: 'Error',
                text: '<?php echo Lang::line('EA_001') ?>-ээс сонгогдсон мөр олдсонгүй',
                type: 'error',
                sticker: false
            });
        }
        
        var $dataRow = JSON.parse($this.attr('data-row'));
        
        renderContent_<?php echo $this->uniqId ?>($dataRow.id, $dataRow.metadataid, $dataRow['name'], $this.attr('data-row'));
        
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
            error: function() {
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