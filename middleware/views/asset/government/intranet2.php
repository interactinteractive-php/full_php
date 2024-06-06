<div class="intranet">
    <div class="page-content">
    <?php include_once "intranet_leftsidebar.php"; ?>
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
                        <span class="text-uppercase font-weight-bold">Файлын сан</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>

                    <ul class="media-list media-list-linked my-2">
                        <!-- <li class="media text-muted border-0 py-2">Office staff</li> -->
                        <li tr-status="0">
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                                <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">2019 оны тайлан төлөвлөгөө</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                                <div class="ml-1" style="width:25px;">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon trash-btn-hide">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </a>
                        </li>
                        
                        <li tr-status="0">
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                                <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">We are a leading sketch-to-scale™ company that designs and builds</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                                <div class="ml-1" style="width:25px;">
                                    <button type="button" class="btn btn-sm btn-primary btn-icon trash-btn-hide">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </a>
                        </li>
                        <script>
                            $('li').mouseover(function() {
                                if ($(this).attr('tr-status') === '0') {
                                    $(this).addClass("trash-hide-show");
                                    $(this).attr('tr-status', '1');
                                }
                            });
                            $('li').mouseout(function() {
                                if ($(this).attr('tr-status') === '1') {
                                    $(this).removeClass("trash-hide-show");
                                    $(this).attr('tr-status', '0');
                                }
                            });
                        </script>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">The company has also received many accolades from employer of choice to energy conservation.</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">We are a leading sketch-to-scale™ company that designs and builds</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">With more than 200,000 professionals across 30 countries </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">The company has also received many accolades from employer of choice to energy conservation. </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">James Alexander James Alexander James Alexander </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">Named among Fortune’s 2016 World’s Most Admired Companies</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">Flex offers a world of innovation, learning opportunities </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">James Alexander James Alexander James Alexander </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">Named among Fortune’s 2016 World’s Most Admired Companies</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">Flex offers a world of innovation, learning opportunities </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">James Alexander James Alexander James Alexander </div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="media d-flex align-items-center">
                            <div class="duedate d-flex flex-column align-items-center pt4 pb4">
                                    <!-- <span class="year"><i class="icon-calendar mb-1"></i></span> -->
                                    <span class="year">2019</span><span class="year">07-08</span>
                                </div>
                                <div class="media-body">
                                    <div class="media-title font-weight-bold mb-0" style="line-height: normal;font-size: 12px;">Named among Fortune’s 2016 World’s Most Admired Companies</div>
                                    <span class="text-muted font-weight-bold font-size-sm mr-3"><i class="icon-file-text mr-1" style="font-size:13px;top:-1px;"></i> 250 KB</span>
                                    <!-- <span class="text-muted font-weight-bold font-size-sm"><i class="icon-calendar mr-1" style="font-size:13px;top:-1px;"></i> 2019-08-07</span> -->
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <div class="page-header page-header-light bg-white mb-3">
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:10px 20px;">
                    <div class="d-flex">
                        <span class="text-uppercase font-weight-bold">2019 оны тайлан төлөвлөгөө</span>
                    </div>

                    <div class="header-elements d-none">
                        <form action="#">
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="search" class="form-control wmin-250" placeholder="Түлхүүр үгээр хайх...">
                                <div class="form-control-feedback">
                                    <i class="icon-search4 font-size-base text-muted"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="content pr-3">
                <div class="card" style="border: 0;">
                    <div class="media flex-column flex-md-row mb-4">
                        <a href="javascript:void(0);" class="align-self-md-center mr-md-3 mb-2 mb-md-0">
                            <img src="http://demo.interface.club/limitless/demo/bs4/Template/global_assets/images/demo/users/face11.jpg" class="rounded" width="44" height="44" alt="">
                        </a>

                        <div class="media-body">
                            <h5 class="media-title font-weight-bold">Д.Болор-Эрдэнэ</h5>
                            <ul class="list-inline list-inline-dotted text-muted mb-0">
                                <li class="list-inline-item"><i class="icon-download"></i> Татаж авах</li>
                                <li class="list-inline-item"><i class="icon-printer2 mr-1"></i> Хэвлэх </li>
                                <li class="list-inline-item"><i class="icon-eye mr-1"></i> Үзсэн: 250</li>
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-sm-6">
                            <div class="card-img-actions">
                                <a href="http://gstat.mn/newsn/thumbnail/240/images/c/2018/07/228315-17072018-1531800677-1333497996-boroo_uyer1.jpg" data-popup="lightbox">
                                    <img src="http://gstat.mn/newsn/thumbnail/240/images/c/2018/07/228315-17072018-1531800677-1333497996-boroo_uyer1.jpg" class="card-img" width="96" height="160" alt="">
                                    <span class="card-img-actions-overlay card-img">
                                        <i class="icon-plus3 icon-2x"></i>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="card-img-actions">
                                <a href="http://gstat.mn/newsn/thumbnail/240/images/c/2019/08/243349-07082019-1565166876-825195651-1200x800.jpg" data-popup="lightbox">
                                    <img src="http://gstat.mn/newsn/thumbnail/240/images/c/2019/08/243349-07082019-1565166876-825195651-1200x800.jpg" class="card-img" width="96" height="160" alt="">
                                    <span class="card-img-actions-overlay card-img">
                                        <i class="icon-plus3 icon-2x"></i>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="card-img-actions">
                                <a href="http://gstat.mn/newsn/thumbnail/240/images/c/2019/08/243344-07082019-1565161280-1179346149-46(64).jpg" data-popup="lightbox">
                                    <img src="http://gstat.mn/newsn/thumbnail/240/images/c/2019/08/243344-07082019-1565161280-1179346149-46(64).jpg" class="card-img" width="96" height="160" alt="">
                                    <span class="card-img-actions-overlay card-img">
                                        <i class="icon-plus3 icon-2x"></i>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6">
                            <div class="card-img-actions">
                                <a href="http://gstat.mn/newsn/thumbnail/240/images/c/2019/08/243346-07082019-1565161684-991987258-udonis-haslem-iso-road.jpg" data-popup="lightbox">
                                    <img src="http://gstat.mn/newsn/thumbnail/240/images/c/2019/08/243346-07082019-1565161684-991987258-udonis-haslem-iso-road.jpg" class="card-img" width="96" height="160" alt="">
                                    <span class="card-img-actions-overlay card-img">
                                        <i class="icon-plus3 icon-2x"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <img class="mt-3" src="http://gstat.mn/newsn/images/c/2019/08/243352-08082019-1565222565-907580926--28042016-1461831999-907336337-tsahilgaan_utas.jpg" style="height: auto;width: 100%;">
                </div>
            </div>
        </div>
        <?php include_once "intranet_rightsidebar.php"; ?>
    </div>
</div>
<script>
    $(function () {
        $('#tooltip-demo').tooltip()
    })
</script>