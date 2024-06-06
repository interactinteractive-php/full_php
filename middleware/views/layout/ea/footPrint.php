<div class="bg-white">
    <div id="footprint-window-<?php echo $this->uniqId ?>" data-meta-type="process" data-process-id="<?php echo $this->uniqId ?>" data-bp-uniq-id="<?php echo $this->uniqId ?>">
        <?php echo Form::create(array('class' => '', 'id' => 'eaportal-footprint-layout-form-'.$this->uniqId, 'method' => 'post')); ?>
        <div class="row">
            <?php echo $this->searchForm; ?>
            <div class="center-sidebar-<?php echo $this->uniqId ?>">
                <?php 
                if (isset($this->dataRow['data']) && $this->dataRow['data']) {
                    foreach ($this->dataRow['data'] as $gkey => $gRow) { ?>
                        <div class="card">
                            <div class="card-header header-elements-inline" style="background: #e26a6a; padding: 10px; color: #FFF !important;">
                                <h5 class="card-title" style="color: #FFF !important;"><?php echo $gRow['groupName'] ?></h5>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                        <a class="list-icons-item" data-action="reload"></a>
                                        <a class="list-icons-item" data-action="remove"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" style="overflow-x: auto; overflow-y: hidden; padding-bottom: 10px;">
                            <?php if (isset($gRow['children']) && $gRow['children']) { 
                                foreach ($gRow['children'] as $ckey => $cRow) { ?>
                                    <div style="width: 186020px;">
                                        <div class="vlayout-body-<?php echo $this->uniqId; ?>">
                                            <div class="col-md-12 pl0 pr0" style="border-bottom: 2px solid #CCC; padding-bottom: 10px; width: 100%; float:left">
                                                <div class="col-md-12 mt35">
                                                    <span style="font-weight: bold;"><?php echo $cRow['name'] ?></span>
                                                </div>
                                                <?php if (isset($cRow['children']) && $cRow['children']) {  ?>
                                                <div class="col-md-12">
                                                    <div class="threeColModel_header_<?php echo $this->dataViewId ?>">
                                                        <?php foreach ($cRow['children'] as $ckKey => $ckRow) { ?>
                                                            <div class="threeColModel_valueChainObject small bg-primary8" style="" id="">
                                                                <div style="padding-top: 11.5px;" class="a-title"><?php echo $ckKey.'. '.$ckRow['name'] ?></div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php 
                                                $maxLen = sizeOf($cRow['children']);
                                                    for ($i = 0; $i < sizeof($cRow['children']); $i++) { ?>
                                                        <div class="col-md-12">
                                                            <div class="threeColModel_footer_<?php echo $this->dataViewId ?> mb5">
                                                                <?php  for ($j = 0; $j < $maxLen; $j++) { 
                                                                    if (isset($cRow['children'][$j]['children']) && sizeOf($cRow['children'][$j]['children']) > $maxLen) {
                                                                        $maxLen = $cRow['children'][$j]['children'];
                                                                    }

                                                                    if (isset($cRow['children'][$j]['children'][$i]['name']) && $cRow['children'][$j]['children'][$i]['name']) { ?>
                                                                        <div class="threeColModel_footer_body_<?php echo $this->dataViewId ?>">
                                                                            <div class="add"></div>
                                                                            <div class="threeColModel_valueChainObject small bg-primary8" style="" id="">
                                                                                <div style="padding-top: 11.5px;" class="a-title"><?php echo $cRow['children'][$j]['children'][$i]['name']; ?></div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } ?>
                            </div>
                        </div>

                <?php 
                    }
                } ?>

            </div>
        </div>
        <div class="clearfix w-100"></div>
        <?php echo Form::close(); ?>
    </div>
</div>

<script type="text/javascript">
    var footPrintId<?php echo $this->uniqId ?> = '#footprint-window-<?php echo $this->uniqId ?>';
    $(document).ready(function () {
        $('input[name="p3_value[]"]', footPrintId<?php echo $this->uniqId ?>).on('change', function(){
            if ($(this).val() == '1') {
                $('.threeColModel_footer_<?php echo $this->uniqId ?>').show(600);
            } else {
                $('.threeColModel_footer_<?php echo $this->uniqId ?>').hide(600);
            }            
        })
    });
    
    function searchFun<?php echo $this->uniqId ?>() {
        $.ajax({
            type: 'post',
            url: "mdlayout/searchFootPrint",
            dataType: "html",
            data: $('#eaportal-footprint-layout-form-<?php echo $this->uniqId ?>').serialize()+'&uniqId=<?php echo $this->uniqId ?>',
            beforeSend:function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });                
            },
            success: function(data) {
                $('.center-sidebar-<?php echo $this->uniqId ?>').empty().append(data);
                Core.unblockUI();
            }
        });             
    }    
    
    function footPrintCardFilter<?php echo $this->uniqId ?>(id, name, elem, type) {
    
        switch (type) {
          case 'checkbox':
            if ($(elem).is(':checked'))
                $('input[name="'+name+'_value"]', footPrintId<?php echo $this->uniqId ?>).val('1');
            else
                $('input[name="'+name+'_value"]', footPrintId<?php echo $this->uniqId ?>).val('0');
            break; 
          case 'card':
            $('input[name="'+name+'_value"]', footPrintId<?php echo $this->uniqId ?>).val(id);
            break; 
          default: 
            break;
        }                
        
        searchFun<?php echo $this->uniqId ?>();
    }
    
</script>

<style type="text/css">
    .threeColModel_header_<?php echo $this->dataViewId ?>, .threeColModel_footer_<?php echo $this->dataViewId ?> {
        width: 100%;
        float: left;
    }
    .threeColModel_footer_body_<?php echo $this->dataViewId ?> {
        float:left;
        width: 150px;
        min-height: 50px;   
    }
    
    .threeColModel_header_<?php echo $this->dataViewId ?> .threeColModel_valueChainObject {
        float: left;
        margin-right: 10px;
        background-color: #ffcd01 !important;
        width: 115px;
        padding: 5px 20px 5px 5px;
        height: 40px;
        margin-bottom: 5px;
        text-align: center;
        position: relative;
        box-sizing: content-box;
    }
    
    .threeColModel_header_<?php echo $this->dataViewId ?>.box-only .threeColModel_valueChainObject {
        width: 97px;
    }
    
    .threeColModel_header_<?php echo $this->dataViewId ?>:not(.box-only) .threeColModel_valueChainObject {
        background: url(assets/core/global/img/value_chain_arrow_end.png) no-repeat right center;
    }
    
    .threeColModel_header_<?php echo $this->dataViewId ?> .threeColModel_valueChainObject:hover {
        background-color: #ffcd019c !important;
        cursor: pointer;
    }
    
    
    .threeColModel_footer_<?php echo $this->dataViewId ?>.box-only .threeColModel_footer_body_<?php echo $this->dataViewId ?> {
        width: 132px;
    }
    
    .threeColModel_footer_<?php echo $this->dataViewId ?> .threeColModel_valueChainObject {
        position: relative;
        background-color: #feef9c;
        border: 1px solid #ffcd01;
        width: 110px;
        height: 40px;
        max-height: 40px;
        overflow: hidden;
        border: 1px solid #ffcd01;
        padding: 5px;
        float: left;
        text-align: center;
        opacity: 1.0;
        box-sizing: content-box;
        line-height: 1.1em;
        box-sizing: content-box;
    }
    
    .threeColModel_footer_<?php echo $this->dataViewId ?> .threeColModel_valueChainObject:hover {
        background-color: #feef9c9c !important;
        cursor: pointer;
    }
    
    .threeColModel_footer_<?php echo $this->dataViewId ?> .add {
        padding: 5px;   
        position: absolute;
        width: 122px;
        height: 52px;
        max-height: 52px;
        font-size: 36px;
        z-index: 0;
        opacity: 1.0;
        box-sizing: content-box;
    }
    
    .a-title {
        color: #000;
        font-size: 12px;
        white-space: nowrap;
        float: left;
        width: 100%; 
        word-wrap: break-word;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .threeColModel_dynamicKeyContainer {
        float: left;
        margin-right: 5px;
    }    
    .threeColModel_dynamicKeyObject {
        padding: 1px;
        text-align: center;
        border: 1px solid #ccc;
        width: 115px;
        height: 90px;
    }    
    .threeColModel_dynamicKeyTitle {
        float: left;
        padding: 3px;
        margin-right: 5px;
        height: 20px;
        box-sizing: content-box;
    }    
</style>