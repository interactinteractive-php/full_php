<div class="forum forum-<?php echo $this->uniqId ?>">
    <?php include_once "sidebar.php"; ?>
    <div class="d-flex align-items-start flex-column flex-md-row">
        <div class="w-100 overflow-auto forum-content-<?php echo $this->uniqId ?>">
            <div class="col-md-12 pl-0">
                <?php

                if (issetParamArray($this->pollList['result'])) {
                    $num = 1;
                    ?> 
                    <div class="has-minheight w-100 pull-left">    
                        <div class="row">
                        <?php
                        foreach ($this->pollList['result'] as $key => $list) { ?>
                            <div class="col-3">
                                <div class="product">
                                    <div class="product-body">
                                        <div class="product-cat">
                                            <a href="javascript:void(0);"><?php echo $list['departmentname'] . ' > ' . $list['discussiontypename'] ?></a>
                                        </div>
                                        <h3 class="product-title"><a href="javascript:;" onclick="forumdtl<?php echo $this->uniqId ?> ('<?php echo $list['id']  ?>')" s="government/forumdtl/<?php echo $list['id'] ?>"><?php echo $list['subject'] ?></a></h3>
                                        <div class="ratings-container mt-1">
                                            <div class="ratings">
                                                <div class="ratings-val" style="width: 100%;"></div>
                                            </div>
                                            <span class="ratings-text w-100 pull-left">( <?php echo $list['wfmstatusname'] ?> ) <span class="text-right pull-right"><i class="fa fa-calendar"></i> <?php echo $list['createddate'] ?></span></span>
                                            <span class="ratings-text w-100 pull-left"><?php echo 'Нийтэлсэн : '.$list['createdusername'] ?><span class="text-right pull-right"><i class="fa fa-user-plus"></i> <?php echo issetParamZero($list['followcount']) ?></span></span>
                                            <span class="ratings-text w-100 pull-left"><i class="fa fa-eye"></i> <?php echo issetParamZero($list['viewcount']) ?> <span class="text-right pull-right"><i class="fa fa-comment"></i> <?php echo issetParamZero($list['reviewcount']) ?></span></span>
                                        </div>
                                    </div>
                                    <div class="product-action">
                                        <a href="javascript:;" onclick="forumdtl<?php echo $this->uniqId ?> ('<?php echo $list['id']  ?>')" ss="government/forumdtl/<?php echo $list['id'] ?>" class="btn-product btn-cart"><span>Дэлгэрэнгүй</span></a>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $num++;
                        }

                        $total = issetParamZero($this->pollList['paging']['totalcount']);
                        $pagesize = issetParamZero($this->pollList['paging']['pagesize']);
                        $offset = issetParamZero($this->pollList['paging']['offset']);

                        $pageNum = ceil($total / $pagesize); ?>
                        </div>
                    </div>
                    <div class="w-100 d-flex justify-content-center pt-2 mb-3">
                        <ul class="pagination">
                            <li class="page-item <?php echo ($offset - 1 == 0) ? 'disabled' : '' ?>"><a href="javascript:;" class="page-link"><i class="icon-arrow-left12"></i></a></li>
                            <?php for ($i = 1; $i <= $pageNum; $i++) { ?>
                                <li class="page-item <?php echo ($offset == $i) ? 'active' : '' ?>"><a href="javascript:;" class="page-link"><?php echo $i; ?></a></li>
                            <?php } ?>
                            <li class="page-item <?php echo ($offset + 1 > $pageNum) ? 'disabled' : '' ?>"><a href="javascript:;" class="page-link"><i class="icon-arrow-right13"></i></a></li>
                        </ul>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php echo issetParam($this->dcss); ?>
<script type="text/javascript">
    $(document).ready(function () {
//        searchList();
        $("head").append('<link rel="stylesheet" type="text/css" href="'+ URL_APP +'middleware/assets/css/intranet/style.v'+ getUniqueId(1) +'.css"/>');
        if (800 < $(window).height()) {
            $('.has-minheight').attr('style', 'min-height: ' + ($(window).height() - 470) + 'px;');
        }
        
        if (typeof isGovAddonScript === 'undefined') {
            $.getScript(URL_APP + 'assets/custom/gov/script.js');
        }
    });
    
    function forumdtl<?php echo $this->uniqId ?>(id) {
        appMultiTab({ weburl: 'government/forumdtl/' + id, metaDataId: 'pollforum_', dataViewId: '', title: plang.get('Хэлэлцүүлэг дэлгэрэнгүй'), type: 'selfurl', rowId: id, tabReload: true}, this, function(elem) {});
    }
    
</script>