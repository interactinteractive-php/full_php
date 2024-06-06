<div class="row mr0 ml0 multi-file-viewer" id="multi-file-viewer-<?php echo $this->uniqId; ?>">
    <div class="col-md-auto overflow-auto mfm-thumbnails-sidebar">
        <div class="mfm-thumbnails">
            <?php
            foreach ($this->fileArr as $k => $fileArr) {
            ?>
                <div class="mfm-thumbnail" data-id="<?php echo $fileArr['contentId']; ?>" data-path="<?php echo $fileArr['path']; ?>" data-extention="<?php echo $fileArr['extention']; ?>" data-version-count="<?php echo $fileArr['versionCount']; ?>">
                    <div class="mfm-thumbnail-name"><?php echo $fileArr['name']; ?></div>
                    
                    <?php
                    if ($fileArr['extention'] == 'png' 
                        || $fileArr['extention'] == 'jpg' 
                        || $fileArr['extention'] == 'jpeg' 
                        || $fileArr['extention'] == 'gif' 
                        || $fileArr['extention'] == 'bmp') {
                    ?>
                    <div class="mfm-thumbnail-photo">
                        <img src="<?php echo $fileArr['thumbPath'] ? $fileArr['thumbPath'] : 'api/image_thumbnail?width=73&src='.$fileArr['path']; ?>">
                    </div>
                    <?php
                    } elseif ($fileArr['extention'] == 'pdf') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-pdf"></i>
                    </div>
                    <?php
                    } elseif ($fileArr['extention'] == 'doc' || $fileArr['extention'] == 'docx') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-word"></i>
                    </div>
                    <?php
                    } elseif ($fileArr['extention'] == 'xls' || $fileArr['extention'] == 'xlsx') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-excel"></i>
                    </div>
                    <?php
                    } elseif ($fileArr['extention'] == 'mp4' || $fileArr['extention'] == 'webm' || $fileArr['extention'] == 'avi' || $fileArr['extention'] == 'wmv') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-video"></i>
                    </div>
                    <?php
                    } elseif ($fileArr['extention'] == 'mp3' || $fileArr['extention'] == 'wav' || $fileArr['extention'] == 'ogg') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-music"></i>
                    </div>
                    <?php
                    } else {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-download"></i>
                    </div>
                    <?php
                    }
                    
                    if ($fileArr['versionCount']) {
                    ?>
                    <span class="mfm-version-icon" title="Зассан түүхтэй файл">
                        <i class="far fa-copy"></i>
                    </span>
                    <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="col text-center mfm-viewer"></div>
</div>

<style type="text/css">
.multi-file-viewer .mfm-thumbnails-sidebar {
    width: 230px;
    background-color: #fff;
    padding-left: 0;
}
.ui-dialog-content .multi-file-viewer .mfm-thumbnails-sidebar {
    padding-left: .625rem;
}
.multi-file-viewer .mfm-thumbnails {
    height: 500px;
    padding: 0 0 10px 0;
}
.ui-dialog-content .multi-file-viewer .mfm-thumbnails {
    padding-top: 10px;
}
.multi-file-viewer .mfm-viewer {
    padding: 0;
    background-color: #edf0f2;
    text-align: center;
}
.multi-file-viewer .mfm-viewer i.icon-file-download {
    font-size: 80px;
}
.multi-file-viewer .mfm-viewer img, 
.multi-file-viewer .mfm-viewer video, 
.multi-file-viewer .mfm-viewer audio {
    max-height: 100%;
    max-width: 100%;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail {
    position: relative;
    padding: 10px;
    border: 1px #fff solid;
    border-bottom: 1px #ddd solid;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail:hover {
    border: 1px #5796e1 solid;
    border-radius: 4px;
    cursor: pointer;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail.active {
    border: 1px #5796e1 solid;
    border-radius: 4px;
    cursor: pointer;
    background-color: #f3faff;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-name {
    text-align: center;
    margin-bottom: 5px;
    line-height: 16px;
    word-break: break-word;
    color: #888;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail.active .mfm-thumbnail-name {
    color: #000;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-photo {
    text-align: center;
    border: 1px #ddd solid;
    padding: 5px;
    border-radius: 4px;
    width: 85px;
    margin-left: auto;
    margin-right: auto;
    background-color: #fff;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-photo img {
    max-width: 100%;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file {
    text-align: center;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file i {
    font-size: 80px;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file .icon-file-pdf {
    color: #fa3f38;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file .icon-file-word {
    color: #1b5ab5;
}
.multi-file-viewer .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file .icon-file-excel {
    color: #1c6c40;
}
.multi-file-viewer .mfm-viewer button.viewer-content-print {
    position: absolute;
    right: 20%;
    top: 5px;
}
.multi-file-viewer .mfm-version-icon {
    position: absolute;
    bottom: 4px;
    right: 7px;
}
</style>

<script type="text/javascript">
var isFilePreviewLog = <?php echo $this->isFilePreviewLog ? 'true' : 'false'; ?>; 

$(function() {
    
    var $parent_<?php echo $this->uniqId; ?> = $('#multi-file-viewer-<?php echo $this->uniqId; ?>');
    var $dialog_<?php echo $this->uniqId; ?> = $('#dialog-multi-file-viewer-<?php echo $this->uniqId; ?>');
    
    if (!$dialog_<?php echo $this->uniqId; ?>.length) {
        setTimeout(function() {
            $parent_<?php echo $this->uniqId; ?>.find('.mfm-thumbnails, .mfm-viewer').css('height', ($(window).height() - $parent_<?php echo $this->uniqId; ?>.offset().top - 36)+'px');
        }, 1);
    } 
    
    $parent_<?php echo $this->uniqId; ?>.on('click', '.mfm-thumbnail', function() {
        var $this = $(this), 
            $parent = $this.closest('.mfm-thumbnails'), 
            contentId = $this.attr('data-id'), 
            filePath = $this.attr('data-path'), 
            fileExt = $this.attr('data-extention'), 
            $viewer = $parent_<?php echo $this->uniqId; ?>.find('.mfm-viewer'), 
            $viewed = $viewer.find('[data-id="'+contentId+'"]'), 
            fileName = $this.find('.mfm-thumbnail-name').text(),
            $logUniqId = $parent.find('[data-log-uniqid]');
        
        $parent.find('.active').removeClass('active');
        $this.addClass('active');
        
        $viewer.find('[data-id]').hide();
        
        if (isFilePreviewLog && $logUniqId.length) {
            var logUniqId = $logUniqId.attr('data-log-uniqid');
            $logUniqId.removeAttr('data-log-uniqid');
            
            $.ajax({
                type: 'post',
                url: 'mdlog/ecmContentViewLog',
                data: {logUniqId: logUniqId}, 
                dataType: 'json',
                success: function(data) {}
            });
        }
        
        if ($viewed.length) {
            $viewed.show();
        } else {
            
            var notViewerFile = '<div data-id="'+contentId+'" class="text-center mt-5"><div class="mb-2">'+fileName+'</div><a href="mdobject/downloadFile?file='+filePath+'&fileName='+fileName+'&fDownload=1"><i class="icon-file-download"></i></a></div>';
            
            if (fileExt == 'png' || fileExt == 'jpg' || fileExt == 'jpeg' || fileExt == 'gif' || fileExt == 'bmp') {
                
                var imagePrintButton = '<button type="button" class="btn btn-sm default viewer-content-print" data-id="'+contentId+'" data-type="image"><i class="far fa-print"></i></button>';
                $viewer.append(imagePrintButton + '<img src="'+filePath+'" data-id="'+contentId+'">');
                
            } else if (fileExt == 'pdf') {
                
                $viewer.append('<iframe src="api/pdf/web/viewer.html?file=<?php echo URL; ?>'+filePath+'#zoom=page-actual" style="border: 0; width: 100%; height: 100%; margin: 0;" data-id="'+contentId+'"></iframe>');
                
            } else if (fileExt == 'mp4' || fileExt == 'webm' || fileExt == 'avi') {
                
                $viewer.append('<video controls data-id="'+contentId+'"><source src="'+filePath+'" type="video/'+fileExt+'">Your browser does not support HTML5 video.</video>');
                
            } else if (fileExt == 'mp3' || fileExt == 'wav' || fileExt == 'ogg') {
                
                $viewer.append('<audio controls data-id="'+contentId+'"><source src="'+filePath+'" type="audio/'+fileExt+'">Your browser does not support the audio element.</audio>');
                
            }
            <?php
            if ($this->isIframe) {
            ?>
            else if (fileExt == 'doc' || fileExt == 'docx') {
                
                bpBlockMessageStart('Loading...');
                
                UrlExists('<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0', function(status) {
                    
                    if (status === 200) {
                        
                        var iframePrintButton = '<button type="button" class="btn btn-sm default viewer-content-print" data-id="'+contentId+'" data-type="iframe"><i class="far fa-print"></i></button>';
                        
                        $viewer.append(iframePrintButton + '<iframe src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0&url=<?php echo URL; ?>'+filePath+'" style="border: 0; width: 100%; height: 100%; margin: 0;" data-id="'+contentId+'"></iframe>').promise().done(function() {
                    
                            var $iframe = $viewer.find('iframe[data-id="'+contentId+'"]');

                            $iframe.on('load', function() {
                                bpBlockMessageStop();
                            });
                        });
                       
                    } else {
                        
                        $viewer.append(notViewerFile);
                        bpBlockMessageStop();
                    }
                });

            } else if (fileExt == 'xls' || fileExt == 'xlsx') {
                
                bpBlockMessageStart('Loading...');
                
                UrlExists('<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0', function(status) {
                    
                    if (status === 200) {
                       
                        $viewer.append('<iframe src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url=<?php echo URL; ?>'+filePath+'" style="border: 0; width: 100%; height: 100%; margin: 0;" data-id="'+contentId+'"></iframe>').promise().done(function() {
                    
                            var $iframe = $viewer.find('iframe[data-id="'+contentId+'"]');

                            $iframe.on('load', function() {
                                bpBlockMessageStop();
                            });
                        });
                       
                    } else {
                        
                        $viewer.append(notViewerFile);
                        bpBlockMessageStop();
                    }
                });
            } 
            <?php
            }
            ?>
            else {
                $viewer.append(notViewerFile);
            }
        }
        
        if (isFilePreviewLog) {
            $.ajax({
                type: 'post',
                url: 'mdlog/ecmContentViewLog',
                data: {contentId: contentId}, 
                dataType: 'json',
                success: function(data) {
                    $this.attr('data-log-uniqid', data.uniqId);
                }
            });
        }
    });
    
    $parent_<?php echo $this->uniqId; ?>.on('click', '.viewer-content-print', function() {
        var $this = $(this), fileType = $this.attr('data-type');
        
        if (fileType == 'iframe') {
            
            var $iframeEl = $this.next('iframe');
            $iframeEl[0].contentWindow.postMessage('print', '*');
            
        } else if (fileType == 'image') {
            
            var $img = $this.next('img');
            var iframe = document.createElement('iframe');

            iframe.style.height = 0;
            iframe.style.visibility = 'hidden';
            iframe.style.width = 0;

            iframe.setAttribute('srcdoc', '<html><body></body></html>');

            document.body.appendChild(iframe);
            
            iframe.addEventListener('load', function () {

                var image = $img[0].cloneNode();
                image.style.maxWidth = '100%';

                var body = iframe.contentDocument.body;
                body.style.textAlign = 'center';
                body.appendChild(image);
                
                image.addEventListener('load', function() {
                    iframe.contentWindow.print();
                });
            });
        }
    });
    
    $dialog_<?php echo $this->uniqId; ?>.on('dialogclose', function() {
        if (isFilePreviewLog) {
            var $logUniqId = $parent_<?php echo $this->uniqId; ?>.find('.mfm-thumbnails').find('[data-log-uniqid]');
            if ($logUniqId.length) {
                var logUniqId = $logUniqId.attr('data-log-uniqid');
                $logUniqId.removeAttr('data-log-uniqid');

                $.ajax({
                    type: 'post',
                    url: 'mdlog/ecmContentViewLog',
                    data: {logUniqId: logUniqId}, 
                    dataType: 'json',
                    success: function(data) {}
                });
            }
        }
    });
    
    $.contextMenu({
        selector: '#multi-file-viewer-<?php echo $this->uniqId; ?> .mfm-thumbnail',
        trigger: 'right',
        build: function($trigger, e) {
            
            var options =  {
                callback: function (key, opt) {
                    eval(key);
                },
                items: {}
            };
            
            if ($trigger.attr('data-version-count') != '0') {
                options.items.fileVersion = {
                    name: 'Зассан түүх харах', 
                    icon: 'copy', 
                    callback: function(key, opt) {
                        if (key === 'fileVersion') {
                            var $this = opt.$trigger;
                            var contentId = $this.attr('data-id');
                            
                            bpCallDataViewByExp($this, $this, '1674643975556186', contentId+'@contentId', 'width:1000');
                        }
                    }
                };
            }
            
            options.items.download = {
                name: plang.get('download_btn'), 
                icon: 'download', 
                callback: function(key, opt) {
                    if (key === 'download') {
                        var $this = opt.$trigger;
                        var fileName = $this.find('.mfm-thumbnail-name').text();
                        $.fileDownload(URL_APP + 'mdobject/downloadFile?file='+$this.attr('data-path')+'&fileName='+fileName+'&contentId='+$this.attr('data-id')+'&fDownload=1', {
                            httpMethod: 'POST'
                        });
                    }
                }
            };

            return options;
        }
    });
    
    setTimeout(function() {
        $parent_<?php echo $this->uniqId; ?>.find('.mfm-thumbnail:eq(0)').click();
    }, 50);
    
});    

function UrlExists(url, cb) {
    $.ajax({
        type: 'HEAD',
        url: url,
        success: function() {
            cb.apply(this, [200]);
        },
        error: function() {
            cb.apply(this, [404]);
        }
    });
}
</script>