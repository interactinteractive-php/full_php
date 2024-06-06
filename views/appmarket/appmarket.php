<div class="col-md-12">
    <div class="card light shadow card-multi-tab">
        <div class="card-header header-elements-inline tabbable-line">
            <ul class="nav nav-tabs card-multi-tab-navtabs">
                <li data-type="layout">
                    <a href="#appmarket_tab" class="nav-link active" data-title="App market" data-toggle="tab"><i class="fa fa-caret-right"></i> App market<span><i class="fa fa-times-circle"></i></span></a>
                </li>
            </ul>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content card-multi-tab-content">
                <div class="tab-pane active" id="appmarket_tab">
                    <div class="layout-fullscreen-btn">
                        <!--<button type="button" class="btn btn-sm btn-icon mr-1" title="Fullscreen" onclick="layoutFullScreen(this);">
                            <i class="fa fa-expand"></i>
                        </button>-->
                        <button type="button" class="btn btn-sm btn-icon layout-manual-refresh-btn mr-1 d-none" title="Refresh" style="height: 22px;width: 22px;padding: 0;top: 28px;right: -27px;">
                            <i class="fa fa-refresh"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-icon mr-1 layout-print-btn d-none" title="<?php echo $this->lang->line('print_btn'); ?>" style="height: 22px;width: 22px;padding: 0;">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>

                    <div class="appmarket-wrapper">
                        <div class="appmarket d-flex">

                            <?php include_once 'leftMenu.php'; ?>

                            <div class="appmarket-body">
                                <div class="appmarket-single-page">
                                </div>
                                <div class="appmarket-home-page">
                                    <div>
                                        <div id="carousel-example-caption" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner">
                                                <div class=" active"  style="max-height:380px;">
                                                    <img class="d-block w-100 h-auto" src="https://dev.veritech.mn/storage/uploads/process/market slider img.png" alt="First slide">
                                                    <div class="carousel-caption d-none d-md-block w-50 ml-0 mr-auto" style="text-align: left;left: 80px;">
                                                        <h1 style="font-size:45px">Veritech<b class="font-bold fw-700 fs-18 ml-1">Mobile ERP</b></h1>
                                                        <p style="font-size: 16px;" class="mb-4">Энэ эрин үед бизнес цэцэглэн хөгжихийн тулд технологийн түнш байх ёстой.
                                                            Veritech ERP -ийн хувьд бидний цорын ганц эрхэм зорилго бол таны бизнесийг саадгүй дэмжих бүтээгдэхүүнээр хангах явдал юм.</p>
                                                        <a href="#" class="btn bg-white br-18 bold" style="border-radius: 50px;font-weight: bold;"><span class="p-1">Дэлгэрэнгүй</span></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="content-wrapper">
                                            <div class="content  justify-content-center align-items-center">
                                                <div class="p-3">
                                                    <ul class="nav pb-0" style="width:fit-content">
                                                        <li class="nav-item">
                                                            <a class="nav-link title-color fs-16 active" id="link-menu-active1" data-toggle="tab" href="#menu-active1" aria-current="page" aria-controls="menu-active1" role="tab" aria-selected="true">Ашиглаж байгаа</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link title-color fs-16" id="link-menu-active2"  data-toggle="tab" href="#menu-active2" aria-current="page" aria-controls="menu-active2" role="tab" aria-selected="false">Санал болгох</a>
                                                        </li>
                                                    </ul>
                                                    <div class="tab-content pt-1">
                                                        <div class="tab-pane active" id="menu-active1" aria-labelledby="link-menu-active1" role="tabpanel">
                                                            <div class="row appmarket-card">
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane" id="menu-active2" aria-labelledby="link-menu-active2" role="tabpanel">
                                                            <div class="pt-2 pb-0 pl-1">
                                                                <h4>Онцлох</h4>
                                                            </div>
                                                            <div>
                                                                <div class="appmarket-slick-carousel appmarket-slick-carousel4">
                                                                </div>
                                                            </div>

                                                            <div class="pt-3 pb-0 pl-1">
                                                                <h4>Шинээр нэмэгдсэн</h4>
                                                            </div>
                                                            <div>
                                                                <!--<div class="overflow-hidden relative  box  rounded-xl slickwidget py-2">-->
                                                                <div class="appmarket-slick-carousel appmarket-slick-carousel5">
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем2</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем3</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем4</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем5</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем6</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем7</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем8</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем 50</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="">
                                                                            <div class="card card-style">
                                                                                <div class="card-body p-3">
                                                                                    <div class="d-flex justify-content-between">
                                                                                        <img src="https://barilga.interactive.mn/storage/uploads/process/202307/file_1689578343353623_161674000590411.png" class="module-img mr-1 img-fluid rounded-circle my-auto" alt="img" onerror="onPortalDefaultSvg(this)">
                                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
                                                                                            <g clip-path="url(#clip0_2643_506)">
                                                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M11.9246 22.3739C11.925 22.3742 11.9253 22.3743 12.4421 21.375C12.959 22.3743 12.9593 22.3742 12.9597 22.3739C12.6355 22.5416 12.2488 22.5416 11.9246 22.3739ZM11.9246 22.3739L12.4421 21.375L12.9597 22.3739L12.9631 22.3721L12.9712 22.3679L12.9989 22.3533C13.0224 22.3409 13.056 22.323 13.099 22.2998C13.1849 22.2534 13.3083 22.1856 13.4639 22.0976C13.775 21.9213 14.2156 21.663 14.7425 21.3299C15.794 20.6651 17.2013 19.695 18.6142 18.4772C21.3752 16.0968 24.4421 12.5255 24.4421 8.25C24.4421 4.25368 21.3134 1.5 18.0671 1.5C15.746 1.5 13.7128 2.70226 12.4421 4.52993C11.1715 2.70226 9.13818 1.5 6.81714 1.5C3.57082 1.5 0.442139 4.25368 0.442139 8.25C0.442139 12.5255 3.50902 16.0968 6.27009 18.4772C7.68289 19.695 9.09021 20.6651 10.1418 21.3299C10.6687 21.663 11.1092 21.9213 11.4203 22.0976C11.5759 22.1856 11.6994 22.2534 11.7853 22.2998C11.8282 22.323 11.8618 22.3409 11.8854 22.3533L11.9131 22.3679L11.9212 22.3721L11.9246 22.3739Z" fill="#E1E1E1"/>
                                                                                            </g>
                                                                                            <defs>
                                                                                            <clipPath id="clip0_2643_506">
                                                                                            <rect width="24" height="24" fill="white" transform="translate(0.442139)"/>
                                                                                            </clipPath>
                                                                                            </defs>
                                                                                        </svg>
                                                                                    </div>
                                                                                    <p class="card-text h5 pt-3 font-bold">Онлайн пос систем 50we</p>
                                                                                    <p class="py-3">Гэрээгээ зөв удирдвал хуулийн өмнө бардам...</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <!--</div>-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>                    

<link href="<?php echo autoVersion('assets/custom/css/appmarket.css'); ?>" rel="stylesheet"/>
<style>
    .bg-image {
        background-position: center; /* Center the image */
        background-repeat: no-repeat; /* Do not repeat the image */
        background-size: cover;
    }    

    .section-title {
        color: #585858;
        font-family: Roboto;
        font-size: 24px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
    }
    
    .appmarket-slick-carousel .slick-dots {
        position: absolute;
        display: block;
        width: 100%;
        padding: 0;
        margin: 0;
        margin-top: 0px;
        list-style: none;
        list-style-type: none;
        text-align: center;
        bottom: -20px;
    }    
    .appmarket-slick-carousel .slick-dots {
        margin: auto;
        display: flex;
        grid-gap: 5px;
        text-align: center;
        justify-content: center;
    }
    .appmarket-slick-carousel .slick-slide {
        border-radius: 10px;
        height:265px; 
        width:224px;        
        margin-right: 17px;
    }
    .appmarket-slick-carousel .slick-track {
    }

</style>

<script type="text/javascript">
    var standartColors = <?php echo json_encode($this->standartColors) ?>;
    var selectedModuleId  = '<?php echo $this->moduleId ?>';
</script>
<script src="<?php echo autoVersion('assets/custom/pages/scripts/appmarket.js'); ?>"></script>




    