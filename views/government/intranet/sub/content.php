<script>
    var dataCSS<?php echo $this->uniqId ?> = ' @page { }';
    </script>
<?php if(isset($this->data) && $this->data) { ?>
<div class="page-header page-header-light bg-white intranet_content_<?php echo $this->uniqId ?>" style="height: 50px; ">
    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline dynamic-width-breadcrumb">
        <div class="d-flex">
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <tbody>
                        <tr>
                            <td class="pl-0 pr-2 py-1" style="border-right: 1px solid #e0e0e0;">
                                <div class="data-tooltip">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <img src="assets/custom/img/ico/user.png" data-user-dd="<?php echo $this->data['userid'] ?>" class="rounded-circle" width="40" height="40">
                                        </div>
                                        <div class="dynamic-width-int">
                                            <a href="javascript:void(0);" id="created-user" class="text-default font-weight-bold letter-icon-title"><?php echo isset($this->data['name']) ? $this->data['name'] : '' ?></a>
                                            <div id="positionname" class="desc text-blue"><?php echo $this->data['positionname'] ?></div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </td>
                            <td class="pl-2 pr-2" style="border-right: 1px solid #e0e0e0;">
                                <div class="desc" id="created-date"><?php echo isset($this->data['createddate']) ? $this->data['createddate'] : '' ?></div>
                                <span class="desc"><?php echo isset($this->data['posttime']) ? $this->data['posttime'] : '' ?></span>
                            </td>
                            <td class="pl-2">
                                <div class="desc">
                                    <a href="javascript:void(0);" style="color: inherit;" id="totalviewhref" onclick="viewPost<?php echo $this->uniqId ?>('<?php echo $this->data['id'] ?>');" sdata-target="#modal_default_show_view">
                                        <li class="list-inline-item"><i class="icon-eye mr-1"></i> 
                                        <span id="view-count"><?php echo isset($this->data['seenpercent']) ? $this->data['seenpercent'] : '' ?>%</span>
                                        </li>
                                    </a>
                                    <?php if ($this->data['islike']) { ?>
                                        <li id="likesection" class="list-inline-item ml-3">
                                            <a href="javascript:void(0);" style="" id="likebutton" onclick="like_<?php echo $this->uniqId ?>(<?php echo $this->data['id'] ?>, 'post', 1)"><i class="icon-thumbs-up2 mr-1"></i></a>
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_post_show_like"><span id="like-count"><?php echo isset($this->data['likecount']) ? $this->data['likecount'] : '0' ?></span></a>
                                        </li>

                                        <li id="dislikesection" class="list-inline-item">
                                            <a href="javascript:void(0);" style="" id="dislikebutton" onclick="like_<?php echo $this->uniqId ?>(<?php echo $this->data['id'] ?>, 'post', 2)"><i class="icon-thumbs-down2 mr-1"></i></a>
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_post_show_dislike"><span id="dislike-count"><?php echo isset($this->data['dislikecount']) ? $this->data['dislikecount'] : '0' ?></span></a>
                                        </li>
                                    <?php } ?>
                                        
                                    <?php if ($this->data['iscomment']) { ?>
                                        <li id="commentsection" class="list-inline-item"><i class="icon-bubble mr-1"></i>
                                            <span id="total-comment"><?php echo isset($this->data['commentcount']) ? $this->data['commentcount'] : '0' ?></span>
                                        </li>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="header-elements d-none right-icons">
            <!--<a href="javascript:void(0);" class="mr-3">
                <i class="icon-reply text-gray"></i>
            </a>-->
            <?php if ($this->selectedRow['userid'] == Ue::sessionUserId() || (Ue::sessionUserId() === '2' || Ue::sessionUserId() === '1')) { ?>
                <div class="btn-group mr-1">
                    <button type="button" class="btn btn-sm btn-light bg-white" data-toggle="dropdown" aria-expanded="false">
                        <i class="icon-menu"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                        <?php if (isset($this->data['scl_posts_question_list'][0]) && $this->data['scl_posts_question_list'][0]) { ?>
                            <a href="government/exportExcel/<?php echo $this->data['id'] ?>" target="_blank" class="dropdown-item " data-id="<?php echo $this->id ?>">
                                <i class="fa fa-file-excel-o"></i> Excel
                            </a>
                        <?php } ?>
                        <?php if ($this->isEdit) { ?>
                            <a href="javascript:void(0);" id="dtlPostEdit" class="dropdown-item dtlPostEdit_<?php echo $this->uniqId ?>" data-id="<?php echo $this->id ?>">
                                <i class="fa fa-pencil"></i> Засах
                            </a>
                        <?php } ?>
                        <a href="javascript:void(0);" id="dtlPostExcel" class="dropdown-item dtlPostDelete_<?php echo $this->uniqId ?>" data-id="<?php echo $this->id ?>">
                            <i class="fa fa-trash"></i> Устгах
                        </a>
                        <!-- <a href="<?php echo 'government/printContentNewWindow/' . $this->data['id'] ?>" id="printLink" target="_blank" class="dropdown-item">
                            <i class="fa fa-print"></i> Хэвлэх
                        </a> -->
                    </div>
                </div>
                <!-- <a href="javascript:void(0);" id="dtlPostEdit" class="mr-1 dtlPostEdit_<?php echo $this->uniqId ?>" data-id="<?php echo $this->id ?>">
                    <button type="button" class="btn btn-sm btn-light bg-white">
                        <i class="fa fa-pencil"></i>
                    </button>
                </a>
                <a href="javascript:void(0);" id="dtlPostDelete" class="mr-1 dtlPostDelete_<?php echo $this->uniqId ?>" data-id="<?php echo $this->id ?>">
                    <button type="button" class="btn btn-sm btn-light bg-white">
                        <i class="fa fa-trash"></i>
                    </button>
                </a> -->
            <?php } ?>
            <a href="javascript:void(0);" class="mr-1 sidebar-left-content">
                <button type="button" class="btn btn-sm btn-light bg-white">
                    <i class="icon-indent-decrease"></i>
                </button>
            </a>
            <a href="<?php echo 'government/printContentNewWindow/' . $this->data['id'] ?>" id="printLink" target="_blank" class="mr-1">
                <button type="button" class="btn btn-sm btn-light bg-white">
                    <i class="fa fa-print"></i>
                </button>
            </a>

            <!-- <div class="btn-group mr-1">
                <button type="button" class="btn btn-sm btn-light bg-white" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-print"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
                    <a href="<?php echo 'government/printContentNewWindow/' . $this->data['id'] ?>" id="printLink" target="_blank" class="dropdown-item">
                        <i class="fa fa-print"></i> Хэвлэх /Хүснэгт/
                    </a>
                    <a href="javascript:;" id="printContent" class="dropdown-item printContent_<?php echo $this->uniqId ?>" data-id="<?php echo $this->id ?>">
                        <i class="fa fa-print"></i> Хэвлэх
                    </a>
                </div>
            </div> -->
            <a href="javascript:void(0);" class="sidebar-right-content d-none">
                <button type="button" class="btn btn-sm btn-light bg-white">
                    <i class="icon-indent-increase"></i>
                </button>
            </a>
            <script>
                $("a.sidebar-left-content").click(function() {
                    $("body").toggleClass("sidebar-xs");
                });
                $("a.sidebar-right-content").click(function() {
                    $(".right-last-sidebar").toggleClass("d-none");
                });
            </script>
        </div>
    </div>
</div>
<div class="breadcrumb p-1 pl-2">
    <a href="javascript:;" class="breadcrumb-item"><?php echo $this->data['typename'] ?></a>
    <span class="breadcrumb-item active"><?php echo $this->data['categoryname'] ?></span>
</div>
<div class="content height-scroll pt-2 pl-2 pr-3">
    
    <h5 id="content_title" class="text-blue <?php echo $this->data['typeid'] != '3' ? '' : '' ?>"><?php echo isset($this->data['description']) ? $this->data['description'] : '' ?></h5>
    <div class="card <?php echo $this->data['typeid'] != '3' ? '' : 'd-none' ?>" style="border: 0;box-shadow: none;">
        <div class="card-body p-0">
            <div id="body" class="mb-1">
                <?php echo $this->data['longdescr'] ?>
            </div>
        </div>
    </div>
    <hr class="intrahr <?php echo $this->data['typeid'] != '3' ? '' : 'd-none' ?>">
    <div class="row filelibrarybody">
        <div class="col-11">
            <div id="attach_file_section" class="row">
                <?php  if ($this->data['typeid'] === '5') { ?>
                    <div class="col-md-12">
                    <ul class="list-view-photo list-view-photo<?php echo $this->uniqId ?>">
                <?php 
                }
                if (isset($this->data['fileattach_multifile'])) {
                    foreach ($this->data['fileattach_multifile'] as $content) { 
                        $fileview = '';
                        switch (issetParam($content['fileextension'])) {
                            case 'png':
                            case 'gif':
                            case 'jpeg':
                            case 'pjpeg':
                            case 'jpg':
                            case 'x-png':
                            case 'bmp':
                                $icon = "icon-file-picture text-danger-400";
                                $fileview = '<img src="'. $content['physicalpath'] .'" class="w-100">';
                                break;
                            case 'zip':
                            case 'rar':
                                $icon = "icon-file-zip text-danger-400";
                                break;
                            case 'pdf':
                                $icon = "icon-file-pdf text-danger-400";
                                $fileview = '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.$content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>';
                                break;
                            case 'mp3':
                                $icon = "icon-file-music text-danger-400";
                                break;
                            case 'mp4':
                                $fileview = '<div class="w-100 embed-responsive embed-responsive-16by9">
                                                <video controls="true" class="embed-responsive-item">
                                                  <source src="'. $content['physicalpath'] .'" type="video/mp4" />
                                                </video>
                                            </div>'; //<iframe src='?autostart=0&autoplay=0&cc_load_policy=1' width='100%' height='500px' frameborder='1'></iframe>";
                                $icon = "icon-file-video text-danger-400";
                                break;
                            case 'doc':
                            case 'docx':
                                $icon = "icon-file-word text-blue-400";
                                $fileview = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL_OFFICE . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                break;
                            case 'ppt':
                                $icon = "icon-file-presentation text-danger-400";
                                $fileview = '<iframe id="file_viewer_'. $content['id'] .'" src="'. CONFIG_FILE_VIEWER_ADDRESS .'documentviewer.aspx?showRb=0&url='. URL_OFFICE . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                            case 'pptx':
                                $icon = "icon-file-presentation text-danger-400";
                                $fileview = '<iframe id="file_viewer_'. $content['id'] .'" src="'. CONFIG_FILE_VIEWER_ADDRESS .'documentviewer.aspx?showRb=0&url='. URL_OFFICE . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                                break;
                            case 'xls':
                            case 'xlsx':
                                $icon = "icon-file-excel text-green-400";
                                $fileview = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL_OFFICE . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                break;
                            default:
                                $icon = "icon-file-empty text-danger-400";
                                break;
                        }
                        
                        if ($this->data['typeid'] === '5') {
                            $bigIcon = "assets/core/global/img/meta/photo.png";
                            $smallIcon = "assets/core/global/img/meta/photo-mini.png";
                            if (file_exists($content['physicalpath'])) {
                                $bigIcon = $content['physicalpath'];
                                $smallIcon = $content['physicalpath'];
                            }
                            ?>
                        
                            <li class="shadow <?php echo $content['id'] ?>">
                                <a href="<?php echo $bigIcon; ?>" class="fancybox-button main" data-fancybox="images" data-rel="fancybox-button" title="<?php echo $content['filename']; ?>">
                                    <img src="<?php echo $smallIcon; ?>"/>
                                </a>
                                <div class="title-photo">
                                    <?php echo $content['filename']; ?>
                                </div>
                            </li>
                        
                        <?php } else { ?>
                            <div class="col-lg-6">
                                <div class="card card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="<?php echo $icon ?> icon-2x mr-2"></i>
                                        <div class="d-flex flex-column w-100">
                                            <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" ><?php echo $content['filename'] ?></a>
                                            <span class="text-muted font-size-sm mr-3 line-height-normal w-100" style="text-transform: uppercase;">
                                                <a href="government/downloadFileUser?file=<?php echo $content['physicalpath'] ?>&fileName=<?php echo $content['filename'] ?>&contentId=<?php echo $content['id'] ?>&type=2" target="_blank" class="w-50 pull-left">Татах</a>
                                                <a href="javascript:;" onclick="fileview_<?php echo  $this->uniqId ?>(this, '<?php echo $content['id']; ?>')" class="w-50 pull-right">Харах</a>
                                            </span>
                                            <span class="text-muted font-size-sm mr-3 line-height-normal w-100" style="text-transform: uppercase;">
                                                <a href="javascript:;" class="w-50 pull-left text-muted" onclick="viewFilecontent<?php echo $this->uniqId ?>('<?php echo $content['id']; ?>', 'download')" title="Харах">Татсан хүний тоо: <?php echo issetParam($content['downloadcount']); ?> <i class="fa fa-eye text-blue mr-15"></i></a>
                                                <a href="javascript:;" class="w-50 pull-right text-muted" onclick="viewFilecontent<?php echo $this->uniqId ?>('<?php echo $content['id']; ?>', 'see')" title="Харах">Үзсэн хүний тоо: <?php echo issetParam($content['viewcount']); ?> <i class="fa fa-eye text-blue mr-15"></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="<?php echo ($content['fileextension'] != 'mp411') ? 'd-none' : '' ?> fileviewcontent_<?php echo $content['id']; ?>" file-ext="<?php echo $content['fileextension']; ?>">
                                <h5 class="modal-title"><?php echo $content['filename'] ?></h5>
                                <hr>
                                <?php if ($content['fileextension'] == 'pdf') { ?>
                                    <div id="file_viewer_<?php echo $content['id']; ?>"></div>
                                    <style type="text/css">
                                        #file_viewer_<?php echo $content['id']; ?> .pdfobject { border: 1px solid #666; }
                                    </style>

                                    <script type="text/javascript">
                                        if (PDFObject.supportsPDFs) {
                                            var options = {
                                                width:"100%",
                                                height:"100rem",
                                                pdfOpenParams: { 
                                                    navpanes:1,
                                                    statusbar:0,
                                                    toolbar:1,
                                                    view:"FitH",
                                                    pagemode:"bookmarks",
                                                    page:1
                                                }
                                            };
                                            PDFObject.embed("<?php echo URL . $content['physicalpath']; ?>", "#file_viewer_<?php echo $content['id']; ?>", options);
                                        } else {
                                           alert("PDF are not supported by this browser");
                                        }
                                    </script>
                                <?php } else {
                                    echo $fileview;
                                } ?>
                            </div>
                        <?php } ?>
                    <?php }
                } 
                
                if ($this->data['typeid'] === '5') { ?>
                </ul>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        Core.initFancybox($('.list-view-photo<?php echo $this->uniqId ?>'));
                    });
                </script>
                <?php } ?>
            </div>
        </div>
        <div class="col-1 text-right">
            <?php 
            $dataFilesArr = isset($this->data['fileattach_multifile']) ? $this->data['fileattach_multifile'] : array();
            $dataFiles = htmlentities(json_encode($dataFilesArr), ENT_QUOTES, 'UTF-8') ?>
            <a href="javascript:;" data-popup="tooltip" data-placement="top" title="<?php echo Lang::line('download_btn') ?>" class="downloadFiles_<?php echo $this->uniqId; echo (isset($this->data['fileattach_multifile']) && $this->data['fileattach_multifile']) ? ' d-flex float-right' : ' d-none' ?> " data-files="<?php echo $dataFiles ?>">
                <button type="button" class="btn btn-sm btn-light bg-white">
                    <i class="icon-file-download2"></i>
                </button>
            </a>
        </div>
        
        <div class="fileviewer w-100" style="padding: 10px;">

        </div>
    </div>
    <!-- photo library -->
    <div id="photolibrary" style="display:none;">
        <div id="photolibrarybody" class="row">

        </div>
    </div>

    <!-- poll -->
    <div id="votingsection" class="<?php echo $this->data['typeid'] == '3' ? '' : 'd-none' ?>">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="nav-item"><a href="#poll_form_<?php echo $this->uniqId ?>" class="nav-link p-2 pl-4 pr-4 active show" data-toggle="tab">Санал асуулга өгөх</a></li>
            <?php if (issetParam($this->data['enddate']) <= $this->currentDate || $this->selectedRow['userid'] === Ue::sessionUserId()) { ?>
            <li class="nav-item"><a href="#poll_result_<?php echo $this->uniqId ?>" class="nav-link p-2 pl-4 pr-4" data-toggle="tab">Үр дүн</a></li>
            <?php } 
            // if ($this->selectedRow['userid'] === Ue::sessionUserId()) {
                 ?>
            <li class="nav-item"><a href="#poll_notvote_<?php echo $this->uniqId ?>" class="nav-link p-2 pl-4 pr-4" data-toggle="tab">Санал өгөөгүй хэрэглэгчид</a></li>
            <?php 
            // }
            ?>
        </ul>
        <div class="d-flex justify-content-between">
            <div>
                <h2 id="content_title" class="<?php echo $this->data['typeid'] != '3' ? '' : '' ?>">
                    <?php echo isset($this->data['description']) ? $this->data['description'] : '' ?>
                </h2>
            </div>
            <div class="d-flex flex-row poll-info mb10" style="width: 390px">
                <?php if (isset($this->data['scl_posts_result_list'][0]) && $this->data['scl_posts_result_list'][0]) {
                $postResultData = $this->data['scl_posts_result_list'][0]; ?>
                    <ul class="nav nav-link p-0">
                        <li class="nav-item p-0">
                            <h2 class="mr-3 m-0">
                                <span class="title_span">Нийт:</span>
                                <span class="body_span"><?php echo isset($postResultData['votedcount']) ? $postResultData['votedcount'] : '0' ?> саналтай</span>
                            </h2>
                        </li>
                        <li class="nav-item p-0">
                            <h2 class="mr-3 m-0">
                                <span class="title_span">Дуусах хугацаа:</span> 
                                <span class="body_span"><?php echo isset($postResultData['enddate']) ? $postResultData['enddate'] : '' ?></span>
                            </h2>
                        </li>
                        <li class="nav-item p-0">
                            <h2 class="mr-3 m-0">
                                <span class="title_span">Үлдсэн хоног:</span>
                                <span class="body_span"><?php echo isset($postResultData['leftdays']) ? $postResultData['leftdays'] : '' ?></span>
                            </h2>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade active show" id="poll_form_<?php echo $this->uniqId ?>">
                <?php if (!(issetParam($this->data['startdate']) < $this->currentDate)) { ?>
                    <div class="alert alert-warning alert-styled-left" style="background-color: #ffc10726; border-color: #FFEB3B;">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        Санал асуулга <strong><?php echo issetParam($this->data['startdate']) ?></strong> эхлэнэ.
                    </div>
                <?php } elseif (issetParam($this->data['enddate']) < $this->currentDate) { ?> 
                    <div class="alert alert-warning alert-styled-left" style="background-color: #ffc10726; border-color: #FFEB3B;">
                        <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                        Санал асуулга <strong><?php echo issetParam($this->data['enddate']) ?></strong> дууссан байна.
                    </div>
                    <?php
                }
                else { 
                    if (isset($this->data['scl_posts_my_result_list'][0]) && $this->data['scl_posts_my_result_list'][0]) {
                    $postResultData = Arr::groupByArrayOnlyRows($this->data['scl_posts_my_result_list'], 'answerid'); ?>
                    <div id="questionbody_<?php echo $this->uniqId ?>">
                        <?php if (isset($this->data['scl_posts_question_list'])) {
                            foreach ($this->data['scl_posts_question_list'] as $key => $question) {
                                $answertypeid = $question['answertypeid'] == '1' ? 'radio' : 'checkbox';
                                ?>
                                <div class="questiondv questiondv_<?php echo $this->uniqId ?>" data-limitcount="<?php echo isset($question['limitcount']) ? $question['limitcount'] : '100' ?>">
                                    <div class="question-index-<?php echo $this->uniqId ?>">
                                        <div class="d-flex flex-row align-items-center justify-content-between" style="border-bottom: 1px solid #dddddd !important">
                                            <div class="w-100 mb-2">
                                                <?php echo Form::label(array('text' => $question['questiontxt'], 'class' => 'col-form-label text-left w-100 border-gray font-size-16 text-blue')); ?>
                                            </div>
                                        </div>
                                        <div class="main-selector">
                                            <?php if (isset($question['scl_posts_answer_list']) && $question['scl_posts_answer_list']) {
                                                foreach ($question['scl_posts_answer_list'] as $key1 => $answer) { ?>
                                                    <div class="checkboxes checkbox-option">
                                                        <div class="form-group pt-0 mb-0 data-selectoption">
                                                            <div class="form-check">
                                                                <label class="form-check-label d-flex justify-content-between align-items-center">
                                                                    <?php if ($answer['isother'] == '1' && isset($answer['answerdescription']) && $answer['answerdescription']) { ?>
                                                                        <label for="answerData_<?php echo $key . '_' . $key1 ?>" class="text-left w-100 input-text-style2 ml-1" style="padding:10px"><?php echo $answer['answerdescription'] ?></label>
                                                                    <?php } else { ?> 
                                                                        <input type="<?php echo $answertypeid ?>" class="form-check-input-styled" <?php echo isset($postResultData[$answer['id']]) ?  'checked' : '' ?> disabled="disabled" >
                                                                        <label for="answerData_<?php echo $key . '_' . $key1 ?>" class="text-left w-100 input-text-style2 ml-1" style="padding:10px"><?php echo $answer['answertxt'] ?></label>
                                                                    <?php } ?>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php 
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                            }
                        } ?>
                    </div>
                    <?php } else { 
                        $isFinished = (isset($this->data['isfinished']) && $this->data['isfinished'] == '1') ? true : false;
                        $isDisabled = ($isFinished) ? 'disabled="disabled"' : '';
                        ?>
                    <div class="poll-save">
                        <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'savePollForm_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                            <?php 
                            includeLib('Compress/Compression');
                            $decompressContent = Compression::encode_string_array($this->data);
                            echo Form::hidden(array('name' => 'postid', 'value' => $this->id));
                            echo Form::hidden(array('name' => 'pollData', 'value' => $decompressContent));
                            ?>
                            <div id="questionbody_<?php echo $this->uniqId ?>">
                                <?php if (isset($this->data['scl_posts_question_list'])) {
                                    foreach ($this->data['scl_posts_question_list'] as $key => $question) {
                                        $answertypeid = $question['answertypeid'] == '1' ? 'radio' : 'checkbox';
                                        ?>
                                        <div class="questiondv questiondv_<?php echo $this->uniqId ?>" data-limitcount="<?php echo isset($question['limitcount']) ? $question['limitcount'] : '100' ?>">
                                            <div class="question-index-<?php echo $this->uniqId ?>">
                                                <div class="d-flex flex-row align-items-center justify-content-between" style="border-bottom: 1px solid #dddddd !important">
                                                    <div class="w-100 mb-2">
                                                        <input type="hidden" name="question[<?php echo $key ?>]" value="<?php echo $question['id'] ?>" />
                                                        <?php 
                                                        $reqiured = '';
                                                        if ($question['isrequired']) {
                                                            $reqiured = 'data-req="question_req_'. $key .'"';
                                                            echo Form::hidden(array('name' => 'question_req_' . $key, 'id' => 'question_req_' . $key, 'value' => '', 'required' => 'required'));
                                                            echo Form::label(array('text' => $question['questiontxt'], 'class' => 'col-form-label text-left  w-100 border-gray font-size-16 text-blue', 'required' => 'required', 'no_colon' => ''));
                                                        } else {
                                                            $reqiured = "";
                                                            echo Form::label(array('text' => $question['questiontxt'], 'class' => 'col-form-label text-left  w-100 border-gray font-size-16 text-blue', 'no_colon' => ''));
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="" style="width: 150px; color: #00bcd4; font-weight: bold; text-align: right; text-transform: uppercase;">
                                                        <?php echo (isset($question['limitcount']) && $question['limitcount'] != '0' && $question['limitcount'] != '100') ? Lang::line('limit_count') . ': ' . $question['limitcount'] : '' ?>
                                                    </div>
                                                </div>
                                                <div class="main-selector">
                                                    <?php if (isset($question['scl_posts_answer_list']) && $question['scl_posts_answer_list']) {
                                                        foreach ($question['scl_posts_answer_list'] as $key1 => $answer) {
                                                            $name = ($question['answertypeid'] == '1' || $question['answertypeid'] == '3') ? "answerData[". $key ."]" : "answerData[". $key ."][" . $key1 . "]";
                                                            $descName = ($question['answertypeid'] == '1' || $question['answertypeid'] == '3') ? "answerDescription[". $key ."]" : "answerDescription[". $key ."][" . $key1 . "]";
                                                            ?>
                                                            <div class="checkboxes checkbox-option">
                                                                <div class="form-group pt-0 mb-0 data-selectoption">
                                                                    <div class="form-check">
                                                                        <label class="form-check-label d-flex justify-content-between align-items-center">
                                                                            <div class="" <?php echo $question['answertypeid'] !== '3' ? '' : 'style="display: none"'; ?>>
                                                                                <input type="<?php echo $answertypeid ?>" <?php echo $question['answertypeid'] !== '3' ? '' : 'checked'; ?> <?php echo $reqiured; echo $isDisabled ?> class="form-check-input-styled" data-ans-id="<?php echo $answer['id'] ?>" id="answerData_<?php echo $key . '_' . $key1 ?>" name="<?php echo $name ?>" value="<?php echo $answer['id'] ?>" onchange="changeAnswer<?php echo $this->uniqId ?>(this)" data-fouc data-isother="<?php echo isset($answer['isother']) ? $answer['isother'] : '0' ?>">
                                                                            </div>
                                                                            <input type="hidden" name="answer[<?php echo $key ?>][<?php echo $key1 ?>]" value="<?php echo $answer['id'] ?>"  data-isother="<?php echo isset($answer['isother']) ? $answer['isother'] : '0' ?>"/>
                                                                            <label for="answerData_<?php echo $key . '_' . $key1 ?>" class="text-left w-100 input-text-style2 ml-1 <?php echo $answer['isother'] === '1' ? 'd-flex' : '' ?>" style="padding:10px; <?php echo $answer['isother'] === '1' ? 'height: auto;' : '' ?>">
                                                                                <span class="pull-left"  <?php echo $question['answertypeid'] !== '3' ? '' : 'style="display: none"'; ?>><?php echo $answer['answertxt'] ?></span>
                                                                                <?php  if (isset($answer['isother']) && $answer['isother'] == '1') { ?>
                                                                                <textarea name="<?php echo $descName ?>" class="form-control pull-left custom-descr-ss <?php echo $question['answertypeid'] == '3' ? 'w-100' : '' ?>" <?php echo $reqiured; echo $isDisabled ?>  style="<?php echo $question['answertypeid'] == '3' ? '' : 'display: none'; ?>" placeholder="<?php echo Lang::line('other_description') ?>" value=""  data-other-path="<?php echo $answer['id'] ?>" onchange="changeAnswer<?php echo $this->uniqId ?>(this)"></textarea>
                                                                                <?php } ?>
                                                                            </label>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php }
                                                    } ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                    }
                                } ?>
                            </div>
                        <?php echo Form::close(); ?>
                        <?php if ($isFinished) {} else { ?>
                            <div class="border-top-1 border-gray">
                                <div class="w-100 dv-process-buttons pull-right pt-2 pb-2">
                                    <button type="button" class="pull-right btn btn-success btn-circle send-btn-1572807892327314" onclick="savePoll_<?php echo $this->uniqId ?>()">Хадгалах</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="tab-pane fade" id="poll_result_<?php echo $this->uniqId ?>">
                <?php if (isset($this->data['scl_posts_result_list'][0]) && $this->data['scl_posts_result_list'][0]) {
                    $postResultData = $this->data['scl_posts_result_list'][0]; ?>
                    <!-- <div class="alert alert-info alert-styled-left alert-dismissible mt-3">
                        <span class="font-weight-semibold">Та энэ санал асуулгыг өмнө нь өгсөн байна. Доорх үр дүнтэй танилцана уу</span>
                    </div> -->
                    <div id="fill_<?php echo $this->uniqId ?>" class="fill_<?php echo $this->uniqId ?>">
                        <?php if ($postResultData['scl_result_question_list']) {
                            $qnumber = 1;
                            foreach ($postResultData['scl_result_question_list'] as $sk => $row) { ?>
                                <div class="poll_result mb-3">
                                    <h6 class="font-size-16 text-blue"><?php //echo $qnumber .'. '.$row['questiontxt'] ?><?php echo $row['questiontxt'] ?> <span style="color: #000; float: right;">Нийт санал: <?php echo isset($row['votedcount']) ? $row['votedcount'] : '0' ?>/<?php echo isset($row['totalcount']) ? $row['totalcount'] : '0' ?></span></h6>
                                    <?php if (isset($row['scl_post_result_answer_list'])) {
                                        foreach ($row['scl_post_result_answer_list'] as $sak => $ans) { ?>
                                            <div class="poll-box mt20">
                                                <div class="<?php echo (issetParam($ans['isother']) == '1') ? '' : 'd-flex'; ?> align-items-center justify-content-center">
                                                    <?php if (issetParam($ans['isother']) == '1') { 
                                                        $drowJson = Str::nlTobr(htmlentities(json_encode($ans['scl_result_user_list']), ENT_QUOTES, 'UTF-8')); ?> 
                                                        <ul id="viewslist" class="w-100 media-list">
                                                            <?php
                                                            $cc = 1;
                                                            if (issetParamArray($ans['scl_result_user_list'])) {
                                                                foreach ($ans['scl_result_user_list'] as $key => $ssrow) {
                                                                    if ($key < 10) { ?>
                                                                        <div class="d-flex align-items-center p-2 w-100">
                                                                            <table class="table ">
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td class="pl-0 pr-2 py-1" style="width: 50px;">
                                                                                            <div class="d-flex align-items-center">
                                                                                            <div class="mr-2">
                                                                                                <img src="assets/custom/img/ico/user.png" data-comment-dd="1569229434702" class="rounded-circle" width="40" height="40" onerror="onNCUserImgError(this);" data-hasqtip="20" aria-describedby="qtip-20">
                                                                                            </div>
                                                                                        </div>
                                                                                        </td>
                                                                                        <td class="pr-2">
                                                                                            <div class="media-chat-item line-height-normal" data-comment="Байхгүй"><?php echo $ssrow['answerdescription'] ?></div>
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <!-- <li class="media">
                                                                            <div class="media-body p-1">
                                                                                <span href="javascript:void(0);" class="d-block media-title font-weight-semibold pull-left text-left" style="width: 100px !important; /* 30% !important */"><?php echo ($cc++).'.. ********' /* . $ssrow['personname']  */?></span>
                                                                                <span class="d-block text-muted font-size-sm text-right pull-left" style="width: 70% !important; text-align: justify !important;"><?php echo $ssrow['answerdescription'] ?></span>
                                                                            </div>
                                                                        </li> -->
                                                                    <?php 
                                                                    }
                                                                }
                                                            }
                                                            ?> 
                                                        </ul>
                                                        <hr>
                                                        <h5 class="mb-0 text-right font-size-12 text-uppercase w-100">
                                                            <?php if (sizeOf(issetParamArray($ans['scl_result_user_list'])) > 9) { ?>
                                                                <a href="javascript:;" data-rowdata="<?php echo $drowJson; ?>"  onclick="modalShowOther<?php echo $this->uniqId ?>(this)"><?php echo Lang::line('more') ?></a>
                                                            <?php } ?>
                                                        </h5>
                                                        <?php ?>
                                                    <?php } else { ?> 
                                                        <div class="" style="width:500px;">
                                                            <h5 class="mb-0 text-right font-size-12 text-uppercase"><?php echo $ans['answertxt'] ?></h5>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="progress" style="height: 22px;">
                                                                <div class="progress-bar" style="width: <?php echo isset($ans['answerpercent']) ? $ans['answerpercent'] : '0' ?>%"  >
                                                                    <span class="sr-only"><?php echo isset($ans['answerpercent']) ? $ans['answerpercent'] : '0' ?>% Complete</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="" style="width:500px;">
                                                            <h5 class="mb-0 font-size-12"><?php echo isset($ans['answerpercent']) ? $ans['answerpercent'] : '0' ?>%</h5>
                                                        </div>
                                                        <script>
                                                            if (typeof  dataCSS<?php echo $this->uniqId ?>  !== 'undefined') {
                                                                dataCSS<?php echo $this->uniqId ?> += ' .progress-bar<?php echo $this->uniqId . '_' . $sk . '_' . $sak; ?> {width: <?php echo isset($ans['answerpercent']) ? $ans['answerpercent'] : '0' ?>% } ';
                                                            }
                                                        </script>
                                                        <style type="text/css">
                                                            
                                                            .progress-bar<?php echo $this->uniqId . '_' . $sk . '_' . $sak; ?> {
                                                                width: <?php echo isset($ans['answerpercent']) ? $ans['answerpercent'] : '0' ?>%;
                                                            }
                                                        </style>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            <?php 
                            $qnumber++;
                            }
                        } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="tab-pane fade" id="poll_notvote_<?php echo $this->uniqId ?>">
                <table style="width: 100%;">
                    <tbody>
                        <?php if (issetParam($this->notvote)) {
                            $iii = 1;
                            foreach ($this->notvote as $row) { ?>
                                <tr style="border-bottom: 1px solid #d8d8d8;">
                                    <td style="width: 80px"><?php echo $iii++; ?></td>
                                    <td style="text-align: left;"><?php echo $row['personname'] ?><br></td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr style="border-bottom: 1px solid #d8d8d8;">
                                <td style="text-align: left" colspan="2">
                                    <div class="alert alert-info alert-styled-left alert-dismissible mt-3">
                                        <span class="font-weight-semibold">Бүгд санал өгсөн.</span>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="filelibrary" style="display:none;">
        <div class="card border-radius-0 border-0">
            <div id="filelibrarybody" class="row">
            </div>
        </div>
    </div>
    <div class="fileviewer-<?php echo $this->uniqId ?>"></div>
    <!-- forum -->
    <?php if ($this->data['iscomment']) { ?>
        <div id="forum" class="card w-100" style="border: 0; box-shadow: none;">
            <div class="card-header header-elements-inline">
                <h5 class="card-title mb-3 font-weight-bold">Сэтгэгдэл</h5>
            </div>
            <div class="card-body pl-0 pr-0 communication-<?php echo $this->uniqId ?>" data-post-id="<?php echo $this->data['id'] ?>">
                <div class="mb-3">
                    <textarea rows="3" cols="3" class="form-control comment_writing<?php echo $this->uniqId ?>" id="comment_writing" placeholder="Та сэтгэгдлээ бичээд enter товч дарна уу." style="margin-top: 0px; margin-bottom: 0px; height: 76px;" required></textarea>
                </div>
                <div class="w-100 dv-process-buttons pull-right"><button type="button" id="save_comment" onclick="saveComment_<?php echo $this->uniqId ?>(this, 'comment')" class="btn btn-success btn-circle mb-3 pull-right ">Хадгалах</button></div>
                <div id="commentbody-<?php echo $this->uniqId ?>" class="w-100 pull-left mb-4"></div>
            </div>
        </div>
    <?php } ?>
</div>
    <style type="text/css">
        .main-content-<?php echo $this->uniqId ?> .title_span {
            width: 199px !important;
            float: left;
            text-align: right;
            margin-right: 10px;
        }
        .main-content-<?php echo $this->uniqId ?> .body_span {
            color: #2196f3 !important;
        }
    </style>
<script type="text/javascript">

    $(function () {
        Core.initComponentSwitchery('#savePollForm_<?php echo $this->uniqId ?>');
        Core.initUniform('#savePollForm_<?php echo $this->uniqId ?>');
        
        <?php if ($this->data['iscomment']) { ?>
            getComments_<?php echo $this->uniqId ?>('<?php echo $this->data['id'] ?>');
        <?php } ?>

        $('.comment_writing<?php echo $this->uniqId ?>').keypress(function(e) {
            if (e.keyCode == 13 && !e.shiftKey) {
                e.preventDefault();
                var $this = $(this);
                $this.closest('.communication-<?php echo $this->uniqId ?>').find('button[id="save_comment"]').trigger('click');
            }
        });
        
    });

    function changeAnswer<?php echo $this->uniqId ?>(element) {
        var $this = $(element);
        var $parent = $this.closest('.questiondv');
        var $tagName = $this.prop("tagName").toLowerCase();
        
        if ($tagName === 'textarea') {
            if ($this.val()) {
                $parent.find('input[name="'+ $this.attr('data-req') +'"]').val('1');
            }
        } else {

            var $limitcount = $this.closest('.questiondv').attr('data-limitcount');
            var $ticket = true;
            $this.attr('data-checked-cc', '0');
            var $descParentFound = $this.closest('.questiondv').find('textarea[data-other-path]');
            var $descSelector = $this.closest('.form-group').find('textarea[data-other-path]');
                $descSelector.val('');

            if ($this.is(':checked') && $this.attr('type') == 'checkbox') {
                $ticket = false;
                if (typeof $this.attr('data-req') !== 'undefined') {
                    $parent.find('input[name="'+ $this.attr('data-req') +'"]').val('1');
                }
                
                if ($limitcount != '0' && $limitcount != '') {
                    if ($parent.find('input[data-checked-cc="1"]').length < $limitcount) {
                        $this.attr('data-checked-cc', '1');
                        $ticket = true;
                    } else {
                        $this.prop('checked', false);
                        $this.parent().removeClass('checked');

                        PNotify.removeAll();
                        new PNotify({
                            title: 'Warning',
                            text: '<?php echo Lang::line('limit_count'); ?>: ' +$limitcount,
                            type: 'warning', 
                            sticker: false
                        }); 
                    }
                }
            } else {
                if (typeof $this.attr('data-req') !== 'undefined') {
                    $parent.find('input[name="'+ $this.attr('data-req') +'"]').val('1');
                }
            }
            
            if ($descParentFound.closest('.form-group').find('input[type="checkbox"]').attr('data-checked-cc') != '1') {
                $this.closest('.questiondv').find('textarea[data-other-path]').hide();
            }
            
            if ($this.is(':checked') && $ticket && $this.attr('data-isother') == '1') {
                $descSelector.show("slide", {direction: "left"}, "500");
                $descSelector.focus();
            }             
        }
    }
</script>
<?php } else {
    echo '<h3 class="text-center mt-5 text-red">Алдаа</h3>';
} ?> 