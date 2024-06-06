<?php
$renderAtom = new Mdwidget(); 
    $offset = checkDefaultVal($this->filterPage, '1');
    $uid = getUID(); 
    
    $colorSet = [
        '#f9ce27',
        '#2888f9',
        '#1ce984',
        '#f46464',
        '#f46464',
    ];

if ($offset == '1') { ?>

<section data-sectioncode="3" class="mb-5 col-span-12 cloudcard_<?php echo $uid; ?>">
    <div class="d-flex justify-content-between">
        <div style="font-size:18px;color:#585858;margin-bottom:15px;margin-top:10px" class="font-bold"><?php echo Lang::line(issetParam($this->jsonAttr['title'])) ?></div>          
        <div class="d-none">
            <a class="btn blue rounded-xl" href="javascript:;" style="border-radius: 100px;"><i class="far fa-plus-circle" style="color:"></i> АПП ҮҮСГЭХ</a>        
        </div>        
    </div>
    <div class="w-full h-full false" style="grid-gap:2%">
        <div class="d-flex cloudcard-moresection<?php echo $uid; ?>" style="gap:1.3rem;overflow: hidden; flex-flow: wrap">
            <?php 
        }
            if (issetParamArray($this->datasrc)) { 
                foreach($this->datasrc as $index => $row) {
                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $class = ' random-border-radius'.mt_rand(1,3);
                    if (issetParam($this->listConfig['id']) !== '17110769684419') {
                        if ($index <= 6) { ?>                       
                        <div style="width: 170px">
                            <div class="<?php echo $class ?> p-4 cloudcard-003-card-row cloud-call-indicator" style="padding-top: 80px !important; justify-self: flex-end; display: flex;height: 170px ;width: 170px;gap: 1.1rem; background-color: <?php echo $colorSet[array_rand($colorSet)] ?>; background-image: url('<?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?>'); background-size: cover; background-repeat: no-repeat;" data-rowdata="<?php echo $rowJson ?>">
                                <div class="row" style="overflow: hidden; display: flex">       
                                    <div class="col-md-12 w-100 pull-left">
                                        <span class="text-one-line" style="font-size: 10px" title="<?php echo Number::formatMoney($renderAtom->renderAtom($row, "position4", $this->positionConfig)) ?>" data-tposition="position4" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>" style="font-size: 14px;color:#fff;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position4", $this->positionConfig)) ?></span>
                                    </div>
                                    <div class="col-md-12 w-100 pull-left">
                                        <span class="text-three-line" style="font-size: 16px" title="<?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?>" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;color:#fff;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <?php }
                    } else { ?>
                        <div style="width: 170px">
                            <div class="<?php echo $class ?> p-4 cloudcard-003-card-row cloud-call-indicator text-white" style="padding-top: 80px !important; justify-self: flex-end; display: flex;height: 170px ;width: 170px;gap: 1.1rem; background-color: <?php echo $colorSet[array_rand($colorSet)] ?>; background-image: url('<?php echo $renderAtom->renderAtom($row, "position3", $this->positionConfig) ?>'); background-size: cover; background-repeat: no-repeat;" data-rowdata="<?php echo $rowJson ?>">
                                <div class="row" style="overflow: hidden; display: flex">       
                                    <div class="col-md-12 w-100 pull-left">
                                        <span class="text-one-line" style="font-size: 10px" title="<?php echo Number::formatMoney($renderAtom->renderAtom($row, "position4", $this->positionConfig)) ?>" data-tposition="position4" data-tpath="<?php echo $renderAtom->renderAtomPath("position4", $this->positionConfig); ?>" style="font-size: 14px;color:#fff;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position4", $this->positionConfig)) ?></span>
                                    </div>
                                    <div class="col-md-12 w-100 pull-left">
                                        <span class="text-three-line" style="font-size: 16px" title="<?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?>" data-tposition="position2" data-tpath="<?php echo $renderAtom->renderAtomPath("position2", $this->positionConfig); ?>" style="font-size: 14px;color:#fff;font-weight: 700;"><?php echo Number::formatMoney($renderAtom->renderAtom($row, "position2", $this->positionConfig)) ?></span>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                    <?php }
                }   
            } else {
                if ($offset == '1') { ?>
                    <div class="col-md-12 text-center">
                        <img src="middleware/assets/img/icon/no-data.png" alt="no-data" class="w-auto mx-auto"/>              
                    </div>
                <?php }
            } 
            if ($offset == '1') { ?>
        </div>
        <?php if ($this->datasrc && issetParam($this->listConfig['id']) !== '17110769684419') { ?>
            <div class="mt-2" style="text-align: right;">
                <?php echo '<span style="color:#A0A0A0;font-size: 11px" data-tposition="position4" data-morebtn="'. $offset .'" class="morebtn">'.Lang::line('More_10').'</span>'; ?>
            </div>   
        <?php } ?>
    </div>
</section>
<style type="text/css">
    .cloudcard_<?php echo $uid; ?> {
        .cloudcard-003-card-row:hover {
            background-color:#5BA6FF !important;
            cursor: pointer;
        }
        .cloudcard-003-card-row:hover span, .cloudcard-003-card-row:hover i {
            color:#fff !important;
        }
        .random-border-radius2 {
            border-radius: .75rem .75rem 80px .75rem;
        }    
        .random-border-radius3 {
            border-radius: .75rem .75rem .75rem .75rem;
        }    
        .random-border-radius1 {
            border-radius: .75rem 80px .75rem .75rem;
        }  
    
        .morebtn:hover {
            cursor: pointer;
        }
    }
</style>
<script type="text/javascript">
    var idField_190189444 = 'id';
    $(function() {
        if (typeof isKpiIndicatorScript === 'undefined') {
            $.cachedScript('<?php echo autoVersion('middleware/assets/js/addon/indicator.js'); ?>', {async: false});
        }
    });

    $('body').on('click', '.cloudcard_<?php echo $uid; ?> .cloud-call-indicator', function () {
        var _this = $(this),
            rowJson = JSON.parse(_this.attr('data-rowdata'));
        mvNormalRelationRender(this, '2008', '190189444', {methodIndicatorId: '190189655', structureIndicatorId: '190189444', mode: 'update', rows: [rowJson]});
    });

    $(".cloud-call-process-oldd").click(function(){
        Core.blockUI({
          message: "Loading...",
          boxed: true,
        });        
        var metaDataId = $(this).data("processid");
        if (metaDataId) {
            var getCustomerItems = $.ajax({
                type: "post",
                url: "mdmetadata/getMetaDataDrill/"+metaDataId,
                dataType: "json",
                async: false,
                success: function (data) {
                    Core.unblockUI();
                    return data.result;
                },
            });
            if (getCustomerItems.responseJSON.META_TYPE_CODE == 'BOOKMARK') {
                appMultiTab({weburl: getCustomerItems.responseJSON.BOOKMARK_URL, metaDataId: getCustomerItems.responseJSON.BOOKMARK_URL+'223999663325', title: getCustomerItems.responseJSON.META_DATA_NAME, type: 'selfurl'});
            } else {
                gridDrillDownLink(this, getCustomerItems.responseJSON.META_DATA_CODE, getCustomerItems.responseJSON.META_TYPE_CODE.toLowerCase(), '1', '',  '', '',metaDataId, '', true, true)
            }
        }
    });
    if (1 == 0) {
        $('body').on('click', '.cloudcard_<?php echo $uid; ?> span[data-morebtn]', function() {
            var _this = $(this),
                filterPage = parseInt(_this.attr('data-morebtn')) + 1;
    
            $.ajax({
                type: 'post',
                url: 'mdlayout/layoutBySection', 
                data: {
                    filterPage: filterPage,
                    config: _this.closest('section[data-attr]').attr('data-attr'),
                }, 
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    if (data.Html.length > 1) {
                        _this.closest('.cloudcard_<?php echo $uid; ?>').find('.cloudcard-moresection<?php echo $uid; ?>').append(data.Html).promise().done(function () {
                            _this.attr('data-morebtn', filterPage);
                        });
                    } else {
                        _this.hide();
                        PNotify.removeAll();
                        new PNotify({
                            title: plang.get('Warning'),
                            text: plang.get('record_not_found'),
                            type: 'warning',
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                },
                error: function(jqXHR, exception) {
                    Core.showErrorMessage(jqXHR, exception);
                    Core.unblockUI();
                }
            });
        });
    }

    $('body').on('click', '.cloudcard_<?php echo $uid; ?> span[data-morebtn]', function() {
        var _this = $(this),
            filterPage = parseInt(_this.attr('data-morebtn')) + 1;
        
        appMultiTab({weburl: 'mdlayout/v2/17110769669929', metaDataId: '17110769669929', title: 'Дэлгэрэнгүй', type: 'selfurl'});;
        return false;
        $.ajax({
            type: 'post',
            url: 'mdlayout/layoutBySection', 
            data: {
                filterPage: filterPage,
                config: _this.closest('section[data-attr]').attr('data-attr'),
            }, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                if (data.Html.length > 1) {
                    _this.closest('.cloudcard_<?php echo $uid; ?>').find('.cloudcard-moresection<?php echo $uid; ?>').append(data.Html).promise().done(function () {
                        _this.attr('data-morebtn', filterPage);
                    });
                } else {
                    _this.hide();
                    PNotify.removeAll();
                    new PNotify({
                        title: plang.get('Warning'),
                        text: plang.get('record_not_found'),
                        type: 'warning',
                        sticker: false
                    });
                }
                Core.unblockUI();
            },
            error: function(jqXHR, exception) {
                Core.showErrorMessage(jqXHR, exception);
                Core.unblockUI();
            }
        });
    });

</script>
<?php } ?>