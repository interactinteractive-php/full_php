<?php 
    $rowJson = htmlentities(json_encode($this->firstLoadContent), ENT_QUOTES, 'UTF-8');
?>
<div class="mail_forward mail_forward_<?php echo $this->uniqId ?> mailcontainer_<?php echo $this->uniqId ?> w-100">
    <div class="flex-fill overflow-auto">
        <div class="card border-0">
            <div class="bg-light rounded-top">
                <div class="navbar navbar-light bg-light navbar-expand-lg pb8 rounded-top">
                    <div class="text-center d-lg-none w-100">
                        <button type="button" class="navbar-toggler w-100 h-100" data-toggle="collapse" data-target="#inbox-toolbar-toggle-write">
                            <i class="icon-circle-down2"></i>
                        </button>
                    </div>
                    <div class="navbar-collapse text-center text-lg-left flex-wrap collapse inbox-toolbar-toggle-write-<?php echo $this->uniqId ?>" id="inbox-toolbar-toggle-write">
                        <div class="mt-3 mt-lg-0 mr-lg-1">
                            <button type="button" class="btn btn-sm bg-blue" onclick="send_<?php echo $this->uniqId ?>('0', '<?php echo $this->type == 'r' ? '#modal_theme_primary_'. $this->uniqId .' .modal-content' : '' ?>', '<?php echo $this->otherUniqId ?>')"><i class="icon-paperplane mr-2"></i> Илгээх</button>
                        </div>
                        <div class="mt-3 mt-lg-0 mr-lg-3">
                            <div class="btn-group">
                                <?php if (!isset($this->iscanceled)) { ?>
                                    <button type="button" class="btn btn-sm btn-light bg-white" onclick="send_<?php echo $this->uniqId ?>('1', '.mail_forward_<?php echo $this->uniqId ?>')">
                                        <i class="icon-checkmark3"></i>
                                        <span class="d-none d-lg-inline-block ml-2">Хадгалах</span>
                                    </button>
                                <?php } ?>
                                <button type="button" class="btn btn-sm btn-light bg-white" data-getprocesscode="<?php echo $this->processCode ?>"  data-rowdata="<?php echo $rowJson ?>" onclick="<?php echo isset($this->iscanceled) ? 'returncontent_'. $this->uniqId .'(this)' : 'trash_'. $this->uniqId .'(this)' ?>">
                                    <i class="icon-cross2"></i>
                                    <span class="d-none d-lg-inline-block ml-2"><?php echo isset($this->iscanceled) ? 'Цуцлах' : 'Устгах' ?></span>
                                </button>
                            </div>
                        </div>
                        <div class="ml-lg-auto mb-3 mb-lg-0">
                            <div class="btn-group" data-toggle="tooltip" data-placement="bottom" title="Хэвлэх">
                                <button type="button" class="btn btn-sm btn-light bg-white">
                                    <i class="fa fa-print"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'sentmail_' . $this->otherUniqId, 'method' => 'post', 'enctype' => 'multipart/form-data'));
                if (!isset($this->iscanceled)) {
                    if (isset($this->isdraft) && $this->isdraft) {
                        echo Form::hidden(array('name' => 'isdraft', 'value' => $this->isdraft));
                    }
                }
                    echo Form::hidden(array('name' => 'id', 'value' => $this->firstLoadContent['id']));
                    echo Form::hidden(array('name' => 'processCode', 'value' => $this->processCode));
                    echo Form::hidden(array('name' => 'type', 'value' => $this->type));
            ?>
            <div class="p-3">
                <div class="d-flex flex-row">
                    <div class="pt7 mb-3" style="width:50px;">Хэнд</div>
                    <div class="col pl-0 pr-0 mr-auto">
                        <div class="input-group">
                            <select name="receiverId[]" class="form-control form-control-sm dropdownInput select2 select2-offscreen bp-field-with-popup-combo" data-path="MM_RECIPIENT_DV.receiverId" data-field-name="receiverId" data-row-data="{&quot;META_DATA_ID&quot;:&quot;1585648701083&quot;,&quot;ATTRIBUTE_ID_COLUMN&quot;:&quot;id&quot;,&quot;ATTRIBUTE_NAME_COLUMN&quot;:&quot;name&quot;,&quot;ATTRIBUTE_CODE_COLUMN&quot;:null,&quot;PARAM_REAL_PATH&quot;:&quot;MM_RECIPIENT_DV.receiverId&quot;,&quot;PROCESS_META_DATA_ID&quot;:&quot;1571133709810&quot;,&quot;CHOOSE_TYPE&quot;:&quot;multicomma&quot;}" multiple="multiple" data-isclear="0" data-placeholder="- Сонгох -" tabindex="-1">
                                <?php if (issetParam($this->recipentList)) {
                                    foreach ($this->recipentList as $row) {
                                        $drowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                                        echo '<option selected="selected" value="'. $row['userid'] .'" data-row-data="'. $drowJson .'">'. $row['personname'] .'</option>';
                                    }
                                } else {
                                    echo '<option value=""></option>';
                                } ?>
                            </select>
                            <span class="input-group-append"> 
                                <button class="btn btn-primary mr0" type="button" data-lookupid="1585648701083" data-paramcode="receiverId" data-processid="1567154435267" data-choosetype="multicomma" data-idfield="id" data-namefield="name" onclick="bpBasketDvWithPopupCombo(this);">..</button>
                                <button class="btn btn-danger mr0 removebtn" type="button" data-lookupid="1585648701083" onclick="bpRemoveAllBasketWithPopupCombo(this);" style="display: none;"><i class="fa fa-trash"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div class="pt7 mb-3" style="width:50px;">Гарчиг</div>
                    <div class="col pl-0 pr-0 mr-auto">
                        <input type="text" placeholder="Гарчиг" class="form-control mb-0" name="subject" value="<?php echo $this->firstLoadContent['subject'] ?>">
                    </div>
                </div>
            </div>
            <div class="card-body p-3 pt0 pb0 border-0">
                <div class="mb-2">
                    <a href="javascript:;" class="btn btn-sm btn-outline bg-primary border-primary text-primary-800 fileinput-button" title="Файл нэмэх">
                        <i class="icon-attachment mr-1"></i>Файл нэмэх
                        <input type="file" name="bp_file[]" class="" multiple onchange="onChangeAttachFIleAddMode(this, 'forward')" />
                    </a>
                </div>
                <ul class="row list-inline mb-0 list-view-file-new filelist-<?php echo $this->uniqId ?>">
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
                                    $icon = "icon-file-video text-danger-400";
                                    break;
                                case 'doc':
                                case 'docx':
                                    $icon = "icon-file-word text-blue-400";
                                    $fileview = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                    break;
                                case 'ppt':
                                    $icon = "icon-file-presentation text-danger-400";
                                    $fileview = '<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="'. CONFIG_FILE_VIEWER_ADDRESS .'documentviewer.aspx?showRb=0&url='. URL . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                                case 'pptx':
                                    $icon = "icon-file-presentation text-danger-400";
                                    $fileview = '<iframe id="file_viewer_<?php echo $this->rowId; ?>" src="'. CONFIG_FILE_VIEWER_ADDRESS .'documentviewer.aspx?showRb=0&url='. URL . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;"></iframe>';
                                    break;
                                case 'xls':
                                case 'xlsx':
                                    $icon = "icon-file-excel text-green-400";
                                    $fileview = '<iframe id="viewFileMain" src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL . $content['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>';
                                    break;
                                default:
                                    $icon = "icon-file-empty text-danger-400";
                                    break;
                            }
                            ?>
                            <li class="col-4 pl-0 list-inline-item mr-0 mb-2">
                                <div class="card bg-light py-2 px-3 mt-0 mb-0 filecontent-tag">
                                    <input type="hidden" name="contentId[]" value="<?php echo $content['contentid'] ?>" />
                                    <div class="media my-1">
                                        <div class="mr-2 align-self-center"><i class="<?php echo $icon; ?> icon-2x top-0"></i></div>
                                        <div class="media-body">
                                            <div class="font-weight-semibold"><?php echo $content['filename'] ?></div>
                                            <ul class="list-inline list-inline-condensed mb-0">
                                                <li class="list-inline-item text-muted"><?php echo $content['filesize'] ?></li>
                                                <li class="list-inline-item"><a href="javascript:void(0);" data-toggle="modal" data-target="#modal_default_<?php echo $this->uniqId . '_' . $content['contentid'] ?>">Харах</a></li>
                                                <li class="list-inline-item"><a href="javascript:void(0);" onclick="removecontent_<?php echo $this->uniqId ?>(this)">Устгах</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <div id="modal_default_<?php echo $this->uniqId . '_' . $content['contentid'] ?>" class="modal fade" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?php echo $content['filename'] ?></h5>
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                        </div>
                                        <div class="modal-body" file-ext="<?php echo $content['fileextension']; ?>">
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
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                            <!--<a href="javascript:void(0);" class="btn btn-sm btn-primary">Татаж авах</a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }
                    } ?>
                </ul>
            </div>
            <div class="card-body border-0 p-0">
                <div class="overflow-auto mw-100 p-3">
                    <textarea rows="12" class="form-control border-radius-0 bodyforward_<?php echo $this->uniqId ?>" placeholder="Захидал" style="height:calc(100vh - 400px);" name="body"><?php echo $this->firstLoadContent['body'] ?></textarea>
                </div>
            </div>
            <?php echo Form::close(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    
    $(function () {
        if (typeof tinymce === 'undefined') {
            $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function() {
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function(script, textStatus){
                    initTinyMceEditor_<?php echo $this->uniqId ?>('.bodyforward_<?php echo $this->uniqId ?>', '460');
                });
            });
        } else {
            tinymce.remove('textarea#body_<?php echo $this->uniqId ?>');
            setTimeout(function(){
                initTinyMceEditor_<?php echo $this->uniqId ?>('.bodyforward_<?php echo $this->uniqId ?>', '460');
            }, 100);
        }
    });

    function removecontent_<?php echo $this->uniqId ?>(element) {
        var $element = $(element);
        var $contentTag = $element.closest('.filecontent-tag');

        $contentTag.fadeOut( "slow", function() {
            $contentTag.remove();
        });
    }

</script>