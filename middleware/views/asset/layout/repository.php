<link href="<?php echo autoVersion('middleware/assets/css/intranet/style.css'); ?>" rel="stylesheet" />
<div class="intranet ea-repository-<?php echo $this->uniqId ?>">
    <div class="page-content">
        <?php include_once "leftsidebar.php"; ?>
        
        <div class="content-wrapper">
            <div class="page-header page-header-light bg-white mb-3">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:10px 20px;">
                    <div class="d-flex">
                        <span id="content_title" class="font-weight-bold"><?php echo $this->getIntranetAllContent[0]['description']  ?></span>
                    </div>

                    <div class="header-elements d-none">
                        <form action="#">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="search" class="form-control wmin-250" placeholder="<?php echo Lang::line('EA_LAYOUT_005') ?>">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ea-content content-<?php echo $this->uniqId ?>"></div>
        </div>
        <?php include_once "rightsidebar.php"; ?>
    </div>
</div>

<style type="text/css">
    
    .ea-repository-<?php echo $this->uniqId ?> .media-title { 
        text-align: justify;
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
//            $element.closest('li').find('.nav-link:after').attr('style', "-webkit-transform: rotate(90deg); transform: rotate(90deg);")
        } else {
            var $dataRow = JSON.parse($element.attr('data-row'));
            
            $element.attr('li-status', 'open')
            $.ajax({
                url: 'mdasset/getLayoutSubMenuRender/',
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
//                        $element.closest('li').find('.nav-link:after').attr('style', "-webkit-transform: rotate(270deg); transform: rotate(270deg);")
                        if (typeof $dataRow['isopen'] !== 'undefined' && $dataRow['isopen'] == '0') {
                            renderContent_<?php echo $this->uniqId ?>(id, metadataid, $dataRow['name'], $element.attr('data-row'));
                        }
                    } else {
                        renderContent_<?php echo $this->uniqId ?>(id, metadataid, $dataRow['name'], $element.attr('data-row'));
                    }
                    Core.unblockUI();
                }
            });
        }

    }
    
    function renderContent_<?php echo $this->uniqId ?> (menuId, metadataid, menuName, dataRow) {
        var metaDid = 1564379242959113;
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/dataview/' + metaDid + '/' + 'false'+ '/json' ,
            data: {
                drillDownDefaultCriteria: 'parentid='+menuId, 
            },
            beforeSend: function () {},
            success: function (data) {
                $(".content-<?php echo $this->uniqId ?>").html(data.Html).promise().done(function () {
                    $(".content-<?php echo $this->uniqId ?>").find('.meta-toolbar').remove();
                    $('#content_title').html(menuName);
                });
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($(".content-<?php echo $this->uniqId ?>"));
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
        
        var $dataRow = JSON.parse($(this).attr('data-row'));
        console.log($dataRow);
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
</script>