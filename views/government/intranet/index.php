<div class="intranet pfix w-100 intranet-<?php echo $this->uniqId ?>" style="margin-top:0; <?php echo (isset($this->ajax) && $this->ajax) ? 'margin-left: 0;' : '' ?>">
    <div class="card-body p-0 intranet_tab fix">
        <ul class="nav nav-tabs v2 nav-tabs-bottom border-bottom-0 nav-justified mb-0 bg-white">
            <li class="nav-item d-flex align-items-center justify-content-between">
                <a href="javascript:;" class="nav-link text-uppercase font-weight-bold font-size-12 intranet_tab1 pt-1" data-toggle="tab" style="width: 80%; text-align: left;">
                    <span><?php echo Lang::line('OLONNIIT_TITLE') ?></span>
                </a>
                <?php if (issetParam($this->showSearchBtn)) { ?>
                    <a href="javascript:;" class="group-search d-none p-0" title="Хайх" data-status="0">
                        <button type="button" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2 text-white" style="background: none !important;">
                            <svg height="23pt" viewBox="1 1 511.999 511.999" width="23pt" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="search_ico<?php echo $this->uniqId ?>" gradientUnits="userSpaceOnUse" x1="255.9998561073" x2="255.9998561073" y1="0" y2="511.9997122146"><stop offset="0" stop-color="#2af598"/><stop offset="1" stop-color="#009efd"/></linearGradient><path d="m302.058594 0c-115.761719 0-209.941406 94.179688-209.941406 209.941406 0 50.695313 18.0625 97.253906 48.089843 133.574219l-140.207031 140.207031 28.277344 28.277344 140.207031-140.210938c36.320313 30.027344 82.878906 48.09375 133.574219 48.09375 115.761718 0 209.941406-94.179687 209.941406-209.941406 0-115.761718-94.179688-209.941406-209.941406-209.941406zm0 379.894531c-93.710938 0-169.953125-76.242187-169.953125-169.953125 0-93.710937 76.242187-169.953125 169.953125-169.953125 93.710937 0 169.953125 76.242188 169.953125 169.953125 0 93.710938-76.242188 169.953125-169.953125 169.953125zm19.992187-169.953125c0 11.042969-8.949219 19.996094-19.992187 19.996094-11.042969 0-19.996094-8.953125-19.996094-19.996094 0-11.042968 8.953125-19.996094 19.996094-19.996094 11.042968 0 19.992187 8.953126 19.992187 19.996094zm-79.976562 0c0 11.042969-8.953125 19.996094-19.996094 19.996094s-19.992187-8.953125-19.992187-19.996094c0-11.042968 8.949218-19.996094 19.992187-19.996094s19.996094 8.953126 19.996094 19.996094zm159.957031 0c0 11.042969-8.953125 19.996094-19.996094 19.996094-11.042968 0-19.996094-8.953125-19.996094-19.996094 0-11.042968 8.953126-19.996094 19.996094-19.996094 11.042969 0 19.996094 8.953126 19.996094 19.996094zm0 0" fill="url(#search_ico<?php echo $this->uniqId ?>)"/></svg>
                        </button>
                    </a>
                <?php } ?>
                <a href="javascript:;" class="addfolter p-0" onclick="addFolder<?php echo $this->uniqId ?>()" data-typeid="null" title="Хавтас үүсгэх">
                    <button type="button" class="btn btn-light bg-primary border-0 p-1 pl-2 pr-2" style="background: none !important">
                        <svg height="23pt" viewBox="0 -17 512 512" width="23pt" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="folder_ico<?php echo $this->uniqId ?>" gradientUnits="userSpaceOnUse" x1="256" x2="256" y1="-.0006" y2="477.0366"><stop offset="0" stop-color="#2af598"/><stop offset="1" stop-color="#009efd"/></linearGradient><path d="m494.425781 70.609375c-11.332031-11.328125-26.394531-17.570313-42.414062-17.570313-.003907 0-.007813 0-.011719 0l-193.703125-.003906c-5.4375.054688-10.40625-2.59375-13.328125-6.972656l-30.710938-46.0625h-154.257812c-33.085938 0-60 26.914062-60 60v322.074219c0 33.082031 26.914062 60 60 60h204v-40h-204c-11.027344 0-20-8.972657-20-20v-216.03125c0-5.34375 2.082031-10.363281 5.859375-14.144531 3.777344-3.773438 8.796875-5.855469 14.144531-5.855469h.003906l153.515626-.007813 31.285156-45.519531.15625-.230469c2.84375-4.269531 8.207031-7.25 13.035156-7.25v-.003906c.066406 0 .136719.003906.207031.003906h.136719l193.660156.003906c5.339844 0 10.359375 2.078126 14.136719 5.855469 3.777344 3.777344 5.859375 8.800781 5.859375 14.140625v200h40v-200c0-16.027344-6.242188-31.09375-17.574219-42.425781zm-282.65625 7.355469-19.292969 28.070312-132.464843.007813c-.003907 0-.007813 0-.011719 0-6.921875 0-13.660156 1.167969-20 3.40625v-49.449219c0-11.027344 8.972656-20 20-20h132.851562l18.835938 28.253906c1.148438 1.722656 2.398438 3.355469 3.714844 4.921875-1.285156 1.527344-2.511719 3.117188-3.632813 4.789063zm216.230469 275.070312h84v40h-84v84h-40v-84h-84v-40h84v-84h40zm0 0" fill="url(#folder_ico<?php echo $this->uniqId ?>)"/></svg>
                    </button>
                </a>
            </li>
        </ul>
    </div>
    <div class="page-content">
        <div class="tab-content w-100">
            <div class="tab-pane fade show active" id="intranet_tab1">
                <div class="page-content">
                    <div class="sidebar v2 sidebar-light sidebar-main sidebar-expand-md">
                        <div class="sidebar-mobile-toggler text-center">
                            <a href="javascript:void(0);" class="sidebar-mobile-main-toggle">
                                <i class="icon-arrow-left8"></i>
                            </a>
                            Navigation
                            <a href="javascript:void(0);" class="sidebar-mobile-expand">
                                <i class="icon-screen-full"></i>
                                <i class="icon-screen-normal"></i>
                            </a>
                        </div>
                        <div class="sidebar-content">
                            <div class="card card-sidebar-mobile">
                                <div class="card-body p-0">
                                    <div class="tab-content pt50">
                                        <div class="height-scroll tab-pane fade show active menu_<?php echo $this->uniqId ?>" id="bottom-justified-divided-tab1">
                                            <div class="side">
                                                <ul class="nav nav-sidebar leftsidebar-<?php echo $this->uniqId ?>" data-nav-type="accordion" data-part="dv-twocol-first-list">
                                                    <?php echo isset($this->menu) ? $this->menu : ''; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="search_menu_<?php echo $this->uniqId ?>" style="display: none;">
                                            <div class="form-group form-group-feedback form-group-feedback-left">
                                                
                                            </div>
                                            <!--<input type="text" name="searchp" class="custom-input-search form-control" data-path="search_value" placeholder="Search typing..." />-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md bg-white siderbarsecond<?php echo $this->uniqId ?>" id="split-second-sidebar-<?php echo $this->uniqId; ?>" style=" width:16.875rem;">
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
                        <div class="sidebar-content">
                            <div class="card cardlist-<?php echo $this->uniqId ?>">
                                <div class="card-header bg-white header-elements-inline pt14 pl-2 pr-2">
                                    <span id="category_title" class="line-height-normal text-one-line" style="width: 80%;">Бүх мэдээ</span>
                                    <div class="second-sidebar-search-box d-none" style="width: 80%;">
                                        <input id="search" name="search" type="" class="form-control" placeholder="Хайх..." style="width: 90%;;">
                                    </div>
                                    <a href="javascript:;" class="mail-search" title="Хайх">
                                        <button type="button" class="btn btn-light bg-gray border-0 p-1 pl-2 pr-2 text-white" style="background: none !important;">
                                            <svg height="23pt" viewBox="1 1 511.999 511.999" width="23pt" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="search_1_ico<?php echo $this->uniqId ?>" gradientUnits="userSpaceOnUse" x1="255.9998561073" x2="255.9998561073" y1="0" y2="511.9997122146"><stop offset="0" stop-color="#2af598"/><stop offset="1" stop-color="#009efd"/></linearGradient><path d="m302.058594 0c-115.761719 0-209.941406 94.179688-209.941406 209.941406 0 50.695313 18.0625 97.253906 48.089843 133.574219l-140.207031 140.207031 28.277344 28.277344 140.207031-140.210938c36.320313 30.027344 82.878906 48.09375 133.574219 48.09375 115.761718 0 209.941406-94.179687 209.941406-209.941406 0-115.761718-94.179688-209.941406-209.941406-209.941406zm0 379.894531c-93.710938 0-169.953125-76.242187-169.953125-169.953125 0-93.710937 76.242187-169.953125 169.953125-169.953125 93.710937 0 169.953125 76.242188 169.953125 169.953125 0 93.710938-76.242188 169.953125-169.953125 169.953125zm19.992187-169.953125c0 11.042969-8.949219 19.996094-19.992187 19.996094-11.042969 0-19.996094-8.953125-19.996094-19.996094 0-11.042968 8.953125-19.996094 19.996094-19.996094 11.042968 0 19.992187 8.953126 19.992187 19.996094zm-79.976562 0c0 11.042969-8.953125 19.996094-19.996094 19.996094s-19.992187-8.953125-19.992187-19.996094c0-11.042968 8.949218-19.996094 19.992187-19.996094s19.996094 8.953126 19.996094 19.996094zm159.957031 0c0 11.042969-8.953125 19.996094-19.996094 19.996094-11.042968 0-19.996094-8.953125-19.996094-19.996094 0-11.042968 8.953126-19.996094 19.996094-19.996094 11.042969 0 19.996094 8.953126 19.996094 19.996094zm0 0" fill="url(#search_1_ico<?php echo $this->uniqId ?>)"/></svg>
                                        </button>
                                    </a>
                                    <a href="javascript:;" data-secondlistaddprocessid="1567154435267" class="" title="Нэмэх">
                                        <button type="button" class="btn btn-light bg-primary border-0 p-1 pl-2 pr-2" style="background: none !important;">
                                            <svg height="23pt" viewBox="0 0 512 512" width="23pt" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><linearGradient id="add_1_ico<?php echo $this->uniqId ?>" gradientUnits="userSpaceOnUse" x1="256" x2="256" y1="0" y2="512"><stop offset="0" stop-color="#2af598"/><stop offset="1" stop-color="#009efd"/></linearGradient><path d="m437.019531 74.980469c-48.351562-48.351563-112.640625-74.980469-181.019531-74.980469s-132.667969 26.628906-181.019531 74.980469c-48.351563 48.351562-74.980469 112.640625-74.980469 181.019531s26.628906 132.667969 74.980469 181.019531c48.351562 48.351563 112.640625 74.980469 181.019531 74.980469s132.667969-26.628906 181.019531-74.980469c48.351563-48.351562 74.980469-112.640625 74.980469-181.019531s-26.628906-132.667969-74.980469-181.019531zm-181.019531 397.019531c-119.101562 0-216-96.898438-216-216s96.898438-216 216-216 216 96.898438 216 216-96.898438 216-216 216zm20-236.019531h90v40h-90v90h-40v-90h-90v-40h90v-90h40zm0 0" fill="url(#add_1_ico<?php echo $this->uniqId ?>)"/></svg>
                                        </button>
                                    </a>
                                </div>
                                <div class="w-100 filter-date d-none">
                                    <div data-section-path="filterStartDate" class="dateElement input-group w-50 pull-left">
                                        <input type="text" value="<?php echo Ue::sessionFiscalPeriodStartDate() ?>" data-path="filterStartDate" name="param[filterStartDate]" data-field-name="filterStartDate" class="form-control form-control-sm dateInit fin-fiscalperiod-startdate" />
                                        <span class="input-group-btn"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                    </div>
                                    <div data-section-path="filterEndDate" class="dateElement input-group w-50 pull-left">
                                        <input type="text" value="<?php echo Ue::sessionFiscalPeriodEndDate() ?>" data-path="filterEndDate" name="param[filterEndDate]" data-field-name="filterEndDate"  class="form-control form-control-sm dateInit fin-fiscalperiod-enddate"/>
                                        <span class="input-group-btn"><button tabindex="-1" onclick="return false;" class="btn"><i class="fa fa-calendar"></i></button></span>
                                    </div>
                                </div>
                                <form id1="default-criteria-form" id="all-content-form" class="form-horizontal xs-form" method="post">
                                    <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                                        <li class="nav-item">
                                            <a href="#bottom-justified-tab1<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab">Бүгд</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#bottom-justified-tab2<?php echo $this->uniqId ?>" class="nav-link" data-toggle="tab">Уншаагүй</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="bottom-justified-tab1<?php echo $this->uniqId ?>">		
                                            <ul id="all-content" class="media-list media-list-linked pb30 all-content-<?php echo $this->uniqId ?>"></ul>				
                                        </div>
                                        <div class="tab-pane fade" id="bottom-justified-tab2<?php echo $this->uniqId ?>">
                                            <ul id="all-unread-content" class="media-list media-list-linked pb30 all-content-<?php echo $this->uniqId ?> unread"></ul>	
                                        </div>
                                    </div>
                                </form>         
                            </div>
                        </div>
                    </div>
                    <div id="main-content" class="content-wrapper pt-0 w-100 main-content-<?php echo $this->uniqId ?>">
                    </div>
                    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md bg-white rightsidebar_<?php echo $this->uniqId ?> right-last-sidebar d-none" style="width:250px;border-left:1px solid #d0d0d0;border-right:0;">
                        <div class="card-header bg-white header-elements-inline pt14 pl-2 pr-2">
                            <span id="category_title" class="line-height-normal text-one-line">Сүүлд нэмэгдсэн, өөрчлөгдсөн</span>
                        </div>
                        <div  class="p-2 rightsidebarContent_<?php echo $this->uniqId ?>">
                            <div id="rightsidebar">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="post_id" value="">
<?php echo isset($this->intranetModals) ? $this->intranetModals : ''; ?>
<?php echo isset($this->defaultJs) ? $this->defaultJs : ''; ?>
<?php echo isset($this->defaultCss) ? $this->defaultCss : ''; ?>

<?php if (defined('USE_SOCKET') && USE_SOCKET) { ?>

<script src="<?php echo autoVersion('chat/client/chat-1.15.0.js'); ?>" type="text/javascript"></script>
<script type="text/javascript">
    var intranetPost_<?php echo $this->uniqId ?> = $('.intranet-<?php echo $this->uniqId ?>').mgVideoChat({
        wsURL: wsUrl + '?room=10&userid=<?php echo Ue::sessionUserId(); ?>&name=<?php echo htmlentities(Ue::getSessionPersonName(), ENT_QUOTES, "UTF-8"); ?>'
    });
</script>

<?php } ?>
