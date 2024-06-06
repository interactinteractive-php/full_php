<?php if ($this->firstLoadContent) {
    
    $rowJson = htmlentities(json_encode($this->firstLoadContent), ENT_QUOTES, 'UTF-8');
    $datarowJson = htmlentities(json_encode($this->selectedRow), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="page-header page-header-light bg-white">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <tbody>
                            <tr>
                                <td class="pl-0 pr-3" style="border-right: 1px solid #e0e0e0;">
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2">
                                            <img src="assets/custom/img/user.png" class="rounded-circle" width="40" height="40">
                                        </div>
                                        <div>
                                            <a href="javascript:void(0);" class="text-default font-weight-bold letter-icon-title"><?php echo $this->firstLoadContent['personname'] ?></a>
                                            <div class="desc text-blue"><?php echo $this->firstLoadContent['positionname'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="pl-3 pr-3 border-right-1 border-gray">
                                    <div class="desc"><span class="text-gray">Бүртгэсэн:</span> <?php echo $this->firstLoadContent['createddate'] ?></div>
                                    <span class="desc"><?php echo Lang::line($this->firstLoadContent['dday']) ?> <?php echo $this->firstLoadContent['timer'] ?></span>
                                </td>
                                <td class="pl-3">
                                    <div class="desc text-gray">Хэнд: </div>
                                </td>
                                <td class="pl-0 pr-3 border-right-1 border-gray">
                                    <div class="d-flex align-items-center">
                                        <?php 
                                        $ticket = false;
                                        if ($this->recipentList) {
                                            foreach ($this->recipentList as $key =>  $row) {
                                                if ($key <= 2) { ?>
                                                    <div class="mr-1">
                                                        <?php echo $row['personname']; ?>,
                                                        <!-- <img src="<?php echo (isset($row['picture']) && $row['picture']) ? $row['picture'] : 'assets/custom/img/user.png' ?>" class="rounded-circle" onerror="onUserImageError(this);" width="40" height="40" data-toggle="tooltip" data-placement="bottom" title="<?php echo $row['personname']; ?>" > -->
                                                    </div>
                                                <?php   
                                                    } else {
                                                        $ticket = true;
                                                    }
                                                }
                                        if ($ticket) { ?>
                                            <div class="mr-1">
                                                <a href="javascript:;" class="dropdown-toggle navbar-nav-link p-0 pl-2" data-toggle="dropdown" data-close-others="true" aria-expanded="false"></a>
                                                    <ul class="dropdown-menu dropdown-menu-default dropdown-menu-default-<?php echo $this->uniqId ?>" style="height: 350px;overflow: auto;">
                                                    <?php foreach ($this->recipentList as $key =>  $row) {
                                                        if ($key > 2) { ?>
                                                        <li><a href="javascript:;"><!--<i class="icon-user"></i>--> <?php echo $row['personname']; ?></a></li>            
                                                        <?php 
                                                        }
                                                    }
                                                }
                                                ?>
                                                </ul>  
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="header-elements d-none right-icons">
                <a href="javascript:void(0);" class="mr-1" data-rowdata="<?php echo $datarowJson ?>" data-toggle="tooltip" data-placement="bottom" title="Хариулах" data-id="<?php echo $this->selectedRow['id'] ?>" data-getprocesscode="<?php echo $this->processCode ?>" onclick="replyforward_<?php echo $this->uniqId ?> (this, 'r')">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light bg-white">
                            <i class="fa fa-mail-reply"></i>
                        </button>
                    </div>
                </a>
                <a href="javascript:void(0);" class="mr-1" data-rowdata="<?php echo $datarowJson ?>" data-toggle="tooltip" data-placement="bottom" title="Явуулах" data-id="<?php echo $this->selectedRow['id'] ?>" data-getprocesscode="<?php echo $this->processCode ?>" onclick="replyforward_<?php echo $this->uniqId ?> (this, 'f')">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light bg-white">
                            <i class="fa fa-mail-forward"></i>
                        </button>
                    </div>
                </a>
                <a href="government/printContent/<?php echo Crypt::encrypt($this->processCode . '{mainId}=' . $this->selectedRow['id']) ?>" target="_blank" class="mr-1" data-toggle="tooltip" data-placement="bottom" title="Хэвлэх" onclick="printMail_<?php echo $this->uniqId ?>()">
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-light bg-white">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>
                </a>
                <?php if (isset($this->firstLoadContent['trashprocesscode'])) { ?>
                    <a href="javascript:void(0);" data-toggle="tooltip" data-placement="bottom" title="Устгах" data-rowdata="<?php echo $rowJson ?>" onclick="trash_<?php echo $this->uniqId ?> (this)">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-light bg-white">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="bg-white height-scroll pl-2 pr-2">
        <div class="card" style="border: 0;box-shadow: none;background: transparent;">
            <div class="row mt-3 main-body-<?php echo $this->uniqId ?>">
                <div class="col">
                    <h5 class="mb-10 text-blue"><?php echo $this->firstLoadContent['subject'] ?></h5>
                    <p class="mb-0"><?php echo $this->firstLoadContent['body'] ?></p>
                </div>
            </div>
            <div id="filelibrarybody" class="row">
                <?php if (isset($this->firstLoadContent['mm_content_get_list'])) {
                    
                    foreach ($this->firstLoadContent['mm_content_get_list'] as $key => $content) { 
                        $fileview = '';
                                            
                        switch ($content['fileextension']) {
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
                        ?>
                        <div class="col-lg-3">
                            <div class="card card-body">
                                <div class="d-flex align-items-center">
                                    <i class="<?php echo $icon; ?> icon-2x mr-2"></i>
                                    <div class="d-flex flex-column w-100">
                                        <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" ><?php echo $content['filename'] ?></a>
                                        <span class="text-muted font-size-sm line-height-normal w-100" style="text-transform: uppercase;">
                                            <a href="government/downloadFileUser?file=<?php echo $content['physicalpath'] ?>&fileName=<?php echo $content['filename'] ?>&contentId=<?php echo $content['contentid'] ?>&type=2" target="_blank" class="w-50 pull-left">Татах</a>
                                            <a href="javascript:;" onclick="fileview_<?php echo $this->uniqId ?>(this, '<?php echo $content['contentid']; ?>')" class="w-50 pull-right">Харах</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-none fileviewcontent_<?php echo $content['contentid']; ?>" file-ext="<?php echo $content['fileextension']; ?>">
                            <h5 class="modal-title"><?php echo $content['filename'] ?></h5>
                            <hr>
                            <?php if ($content['fileextension'] == 'pdf') { ?>
                                <div id="file_viewer_<?php echo $content['contentid']; ?>"></div>
                                <style type="text/css">
                                    #file_viewer_<?php echo $content['contentid']; ?> .pdfobject { border: 1px solid #666; }
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
                                        PDFObject.embed("<?php echo URL . $content['physicalpath']; ?>", "#file_viewer_<?php echo $content['contentid']; ?>", options);
                                    } else {
                                       alert("PDF are not supported by this browser");
                                    }
                                </script>
                            <?php } else {
                                echo $fileview;
                            } ?>
                        </div>
                    <?php }
                } ?>
                <div class="fileviewer w-100" style="padding: 10px;">

                </div>
            </div>
        </div>
    </div>
<?php } else { ?>
    <div class="page-header page-header-light bg-white">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                
            </div>
        </div>
    </div>
<?php } ?>

<style type="text/css">
    .dropdown-menu-default-<?php echo $this->uniqId ?> {
        top: 50px !important;
        left: 6px !important;
    }
</style>