
<div class="intranet pfix w-100 intranet-<?php echo $this->uniqId ?>" style="margin-top:0;">
    <div class="card-body p-0 intranet_tab fix">
        <ul class="nav nav-tabs v2 nav-tabs-bottom border-bottom-0 nav-justified mb-0 bg-white">
            <li class="nav-item"><a href="#intranet_tab1" class="nav-link active text-uppercase font-weight-bold font-size-12" data-toggle="tab"><?php echo Lang::line('OLONNIIT_TITLE') ?></a></li>
        </ul>
    </div>
    <div class="page-content">
        <div class="tab-content w-100">
            <div class="tab-pane fade show active" id="intranet_tab1">
                <div class="page-content">
                    <?php echo isset($this->leftsidebar) ? $this->leftsidebar : ''; ?>
                    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md bg-white" style="width:16.875rem;">
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
                            <div class="card">
                                <!-- <div class="input-group p-2">
                                    <input type="text" class="form-control border-radius-0" placeholder="Хайлт..." style="height:30px;">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-light dropdown-toggle border-radius-0" data-toggle="dropdown" style="padding:3px 10px;">Action</button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a href="#" class="dropdown-item">Action</a>
                                            <a href="#" class="dropdown-item">Another action</a>
                                            <a href="#" class="dropdown-item">Something else here</a>
                                            <a href="#" class="dropdown-item">One more line</a>
                                        </div>
                                    </div>
                                </div> -->
                                <div class="card-header bg-white header-elements-inline pt14 pl-2 pr-2">
                                    <span id="category_title" class="text-uppercase font-weight-bold line-height-normal font-size-12">Бүх мэдээ</span>
                                    <div class="header-elements">
                                        <div class="list-icons">
                                            <a href="javascript:;" style="font-family: Roboto Condensed; text-transform: uppercase; color: #ffffff; background: #AAA;padding: 2px 5px;border-radius: 3px;margin-right:0;" class="mr-1">
                                                <i class="icon-search4"></i>
                                            </a>
                                            <a href="javascript:;" data-secondlistaddprocessid="1567154435267" style="font-family: Roboto Condensed; text-transform: uppercase; color: #ffffff; background: #4caf50;padding: 2px 5px;border-radius: 3px;margin-right:0;">
                                                <i class="icon-plus3"></i>
                                            </a>
                                            <!-- <a href="javascript:;" id="rowsDeleteButton" class="bg-danger" onclick="postSelect()" style="font-family: Roboto Condensed;text-transform: uppercase;color: #ffffff; background: #4caf50;padding: 2px 5px;border-radius: 3px;display: none;margin-right:0;">
                                                <i class="icon-trash"></i>
                                            </a> -->
                                        </div>
                                    </div>
                                </div>
                                <form id="all-content-form">
                                    <ul id="all-content" class="media-list media-list-linked height-scroll pb30"></ul>
                                </form>         
                            </div>
                        </div>
                    </div>
                    <div id="main-content" class="content-wrapper w-100">
                        <div class="page-header page-header-light bg-white" style="display:none;">
                            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                                <div class="d-flex">
                                    <div class="table-responsive">
                                        <table class="table text-nowrap">
                                            <tbody>
                                                <tr>
                                                    <td class="pl-0 pr-2 pt6" style="border-right: 1px solid #e0e0e0;">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-2">
                                                                <img src="assets/custom/img/user.png" class="rounded-circle" width="40" height="40">
                                                            </div>
                                                            <div>
                                                                <a href="javascript:void(0);" id="created-user" class="text-default font-weight-bold letter-icon-title">Г.Дашжид</a>
                                                                <div class="desc text-blue">Дадлагажигч</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="pl-2 pr-2" style="border-right: 1px solid #e0e0e0;">
                                                        <div class="desc" id="created-date">Бүртгэсэн: 2019/09/30</div>
                                                        <span class="desc">Даваа гариг 13:01</span>
                                                    </td>
                                                    <td class="pl-2">
                                                        <div class="desc">
                                                            <a href="javascript:void(0);" style="color: inherit;" id="totalviewhref" data-toggle="modal" data-target="#modal_default_show_view">
                                                                <li class="list-inline-item"><i class="icon-eye mr-1"></i>
                                                                    <span id="view-count"></span>
                                                                </li>
                                                            </a>
                                                            <li id="likesection" class="list-inline-item ml-3">
                                                                <a href="javascript:void(0);" style="" id="likebutton" onclick="like(<?php //echo $this->getIntranetAllContent[0]['id'] ?>, 'post', 1)"><i class="icon-thumbs-up2 mr-1"></i></a>
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_post_show_like"><span id="like-count">0 </span></a>
                                                            </li>
                                                            <li id="dislikesection" class="list-inline-item">
                                                                <a href="javascript:void(0);" style="" id="dislikebutton" onclick="like(<?php //echo $this->getIntranetAllContent[0]['id'] ?>, 'post', 2)"><i class="icon-thumbs-down2 mr-1"></i></a>
                                                                <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_post_show_dislike"><span id="dislike-count">0 </span></a>
                                                            </li>
                                                            <li id="commentsection" class="list-inline-item"><i class="icon-bubble mr-1"></i>
                                                                <span id="total-comment">0 </span>
                                                            </li>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="header-elements d-none right-icons">
                                    <a href="javascript:void(0);" id="dtlPostEdit" class="mr-2">
                                        <i class="icon-pencil text-gray"></i>
                                    </a>
                                    <a href="javascript:void(0);" id="dtlPostDelete" class="mr-2">
                                        <i class="icon-trash text-gray"></i>
                                    </a>
                                    <a href="javascript:void(0);" id="printLink" target="_blank" class="mr-2">
                                        <i class="icon-printer4 text-gray"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="page-header page-header-light bg-white">
                            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline" style="padding:8px 20px 9px 20px;">
                                <div class="d-flex">
                                    <span id="content_title" class="text-uppercase font-weight-bold font-size-12"></span>
                                </div>
                                <div class="header-elements d-none">
                                    <a href="mdintranet/printContentNewWindow/123" id="printLink" target="_blank"><button type="button" class="btn btn-success btn-sm mr-2"><i class="icon-printer mr-2"></i>Хэвлэх</button></a>
                                </div>
                            </div>
                        </div> -->
                        <div class="content height-scroll pt-2 pr-3">
                            <h5 id="content_title" class="text-blue"></h5>
                            <div class="card" style="border: 0;box-shadow: none;">
                                <div class="card-body p-0">
                                    <!-- <div class="media flex-column flex-md-row mb-4">
                                        <a href="javascript:void(0);" class="align-self-md-center mr-md-3 mb-2 mb-md-0">
                                            <img src="assets/core/global/img/user.png" class="rounded" width="44" height="44" alt="">
                                        </a>
                                        <div class="media-body">
                                            <h5 id="created-user" class="media-title font-weight-bold"></h5>
                                            <ul class="list-inline list-inline-dotted text-muted mb-0">
                                                <li class="list-inline-item">
                                                    <span id="created-date"></span>
                                                </li>
                                                <a href="javascript:void(0);" style="color: inherit;" id="totalviewhref" data-toggle="modal" data-target="#modal_default_show_view">
                                                    <li class="list-inline-item"><i class="icon-eye mr-1"></i> Үзсэн: 
                                                        <span id="view-count"></span>
                                                    </li>
                                                </a>
                                                <li class="list-inline-item ml-3">
                                                    <a href="javascript:void(0);" style="" id="likebutton" onclick="like(<?php //echo $this->getIntranetAllContent[0]['id'] ?>, 'post', 1)"><i class="icon-thumbs-up2 mr-1"></i></a>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_post_show_like"><span id="like-count">0 </span></a></li>
                                                </a>
                                                <li class="list-inline-item">
                                                    <a href="javascript:void(0);" style="" id="dislikebutton" onclick="like(<?php //echo $this->getIntranetAllContent[0]['id'] ?>, 'post', 2)"><i class="icon-thumbs-down2 mr-1"></i></a>
                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_post_show_dislike"><span id="dislike-count">0 </span></a></li>
                                                <li class="list-inline-item"><i class="icon-bubble mr-1"></i>
                                                    <span id="total-comment">0 </span></li>
                                            </ul>
                                        </div>
                                    </div> -->
                                    <div id="body" class="mb-1"></div>
                                </div>
                            </div>
                            <hr class="intrahr">
                            <!-- photo library -->
                            <div id="photolibrary" style="display:none;">
                                <div id="photolibrarybody" class="row">

                                </div>
                            </div>
                            
                            <!-- poll -->
                            <div id="votingsection" style="display:none;">
                                <div class="tab-content w-100">
                                    <div class="tab-pane fade active show" id="profile">
                                        <div class="card">
                                            <div class="card-header bg-light p-2 header-elements-inline">
                                                <p id="poll_title" class="card-title">Таны ажлын байрны судалгаа</p>
                                                <div class="header-elements">
                                                    <div class="list-icons">
                                                    </div>
                                                </div>
                                            </div>
                                            <form id="main_form">
                                                <div id="questionbody" class="card-body">
                                                </div>
                                                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                                    <div class="ml-auto">
                                                        <button type="button" onclick="savePoll();" class="btn bg-success">Хадгалах</button>
                                                        <!--<button type="button" class="btn btn-link">Хаах</button>-->
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="filelibrary" style="display:none;">
                                <div class="card border-radius-0 border-0">
                                    <div id="filelibrarybody" class="row">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- forum -->
                            <div id="forum" style="display:none;" class="card" style="border: 0;box-shadow: none;">
                                <div class="card-header header-elements-inline">
                                    <h5 class="card-title mb-3 font-weight-bold">Сэтгэгдэл</h5>
                                </div>
                                <div class="card-body pl-0 pr-0 communication-<?php echo $this->uniqId ?>" data-post-id="0">
                                    <div class="mb-3">
                                        <textarea rows="3" cols="3" class="form-control" id="comment_writing" placeholder="Саналаа бичээд ENTER дарна уу..." style="margin-top: 0px; margin-bottom: 0px; height: 76px;" required></textarea>
                                    </div>
                                    <button type="button" id="save_comment" onclick="saveComment(this, 'comment')" class="btn bg-pink mb-3">Санал бичих</button>        
                                    <div id="commentbody-<?php echo $this->uniqId ?>" class="mb-4"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md bg-white" style="width:250px;border-left:1px solid #d0d0d0;border-right:0;">
                        <div class="card-header bg-white header-elements-inline pt14 pl-2 pr-2">
                            <span id="category_title" class="text-uppercase font-weight-bold line-height-normal font-size-12">Сүүлд нэмэгдсэн, өөрчлөгдсөн</span>
                        </div>
                        <div id="rightsidebar" class="p-2">

                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show active" id="intranet_tab2">
                <div class="webmail_iframe"> <!-- WEBMAIL LEFT_SIDEBAR PT-5 -->
                    <?php include_once "intranet_webmail.php"; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="post_id" value="">
<?php echo isset($this->intranetModals) ? $this->intranetModals : ''; ?>

<style type="text/css">
    
    .intranet-<?php echo $this->uniqId ?> .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }
    
    .intranet-<?php echo $this->uniqId ?> .mini-dialog {
        max-width: 300px !important;
        margin: 1.75rem auto !important;
    }
    
    .intranet-<?php echo $this->uniqId ?> .modal .modal-header,
    .intranet-<?php echo $this->uniqId ?> .modal .modal-footer {
        background: none;
    }
    
    .intranet-<?php echo $this->uniqId ?> #intranet-right .fancybox-button, .fancybox-button:link, .fancybox-button:visited {
        background: none;
        width: 10px;
    }
    
    .intranet-<?php echo $this->uniqId ?> .sidebar.v2 .nav-sidebar .nav-item:not(.nav-item-divider) {
        border-bottom: none;
    }
    
</style>



