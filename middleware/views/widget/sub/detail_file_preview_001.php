<style type="text/css">
.bp-detail-file-viewer-001 .mfm-thumbnails-sidebar {
    width: 95px;
    background-color: #fff;
    padding-left: 0;
}
.bp-detail-file-viewer-001 .mfm-thumbnails {
    height: 500px;
    padding: 0 0 10px 0;
}
.bp-detail-file-viewer-001 .mfm-viewer {
    padding: 0;
    background-color: #edf0f2;
    text-align: center;
}
.bp-detail-file-viewer-001 .mfm-viewer i.icon-file-download {
    font-size: 80px;
}
.bp-detail-file-viewer-001 .mfm-viewer img, 
.bp-detail-file-viewer-001 .mfm-viewer video, 
.bp-detail-file-viewer-001 .mfm-viewer audio {
    max-height: 100%;
    max-width: 100%;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail {
    padding: 4px;
    border: 1px #fff solid;
    border-bottom: 1px #ddd solid;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail:hover {
    border: 1px #5796e1 solid;
    border-radius: 4px;
    cursor: pointer;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail.active {
    border: 1px #5796e1 solid;
    border-radius: 4px;
    cursor: pointer;
    background-color: #f3faff;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-name {
    text-align: center;
    margin-bottom: 5px;
    line-height: 12px;
    word-break: break-word;
    color: #888;
    font-size: 11px;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail.active .mfm-thumbnail-name {
    color: #000;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-photo {
    text-align: center;
    border: 1px #ddd solid;
    padding: 5px;
    border-radius: 4px;
    width: 75px;
    margin-left: auto;
    margin-right: auto;
    background-color: #fff;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-photo img {
    max-width: 100%;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file {
    text-align: center;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file i {
    font-size: 50px;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file .icon-file-pdf {
    color: #fa3f38;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file .icon-file-word {
    color: #1b5ab5;
}
.bp-detail-file-viewer-001 .mfm-thumbnails .mfm-thumbnail .mfm-thumbnail-file .icon-file-excel {
    color: #1c6c40;
}
.bp-detail-file-viewer-001 .mfm-viewer button.viewer-content-print {
    position: absolute;
    right: 52px;
    top: 9px;
}
</style>

<div class="row mr0 ml0 bp-detail-file-viewer-001" id="multi-file-viewer-<?php echo $this->uniqId; ?>">
    <div class="col-md-auto overflow-auto mfm-thumbnails-sidebar">
        <div class="mfm-thumbnails">
            <?php
            $row = $this->row;
            
            $isDownload = !Config::getFromCache('PF_IGNORE_FILE_DOWNLOAD');
            $isIframe = false;
                
            if (defined('CONFIG_FILE_VIEWER_ADDRESS') && CONFIG_FILE_VIEWER_ADDRESS) {
                $isIframe = true;
            }
                
            foreach ($this->fillParamData as $rk => $rowData) {
                
                $position = array();
                $isIgnoreDownload = issetParam($rowData['isignoredownload']);
                
                foreach ($row['data'] as $ind => $val) {
                    if ($val['THEME_POSITION_NO']) {
                        $position[$val['THEME_POSITION_NO']] = issetParam($rowData[$val['LOWER_PARAM_NAME']]);
                    } 
                }
                
                $contentId = issetParam($position[1]);
                $extention = strtolower(issetParam($position[2]));
                $path = issetParam($position[3]);
                $name = issetParam($position[4]);
            ?>
                <div class="mfm-thumbnail<?php echo $isIgnoreDownload ? ' not-dwnld' : ''; ?>" data-id="<?php echo $contentId; ?>" data-path="<?php echo $path; ?>" data-extention="<?php echo $extention; ?>">
                    <div class="mfm-thumbnail-name"><?php echo $name; ?></div>
                    
                    <?php
                    if ($extention == 'png' 
                        || $extention == 'jpg' 
                        || $extention == 'jpeg' 
                        || $extention == 'gif' 
                        || $extention == 'bmp') {
                    ?>
                    <div class="mfm-thumbnail-photo">
                        <img src="<?php echo 'api/image_thumbnail?width=73&src='.$path; ?>">
                    </div>
                    <?php
                    } elseif ($extention == 'pdf') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-pdf"></i>
                    </div>
                    <?php
                    } elseif ($extention == 'doc' || $extention == 'docx') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-word"></i>
                    </div>
                    <?php
                    } elseif ($extention == 'xls' || $extention == 'xlsx') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-excel"></i>
                    </div>
                    <?php
                    } elseif ($extention == 'mp4' || $extention == 'webm' || $extention == 'avi') {
                    ?>
                    <div class="mfm-thumbnail-file">
                        <i class="icon-file-video"></i>
                    </div>
                    <?php
                    } elseif ($extention == 'mp3' || $extention == 'wav' || $extention == 'ogg') {
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
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="col text-center mfm-viewer"></div>
</div>

<script type="text/javascript">
$(function() {
    
    var $parent_<?php echo $this->uniqId; ?> = $('#multi-file-viewer-<?php echo $this->uniqId; ?>');
    
    setTimeout(function() {
        $parent_<?php echo $this->uniqId; ?>.find('.mfm-thumbnails, .mfm-viewer').css('height', ($(window).height() - $parent_<?php echo $this->uniqId; ?>.offset().top - 36)+'px');
    }, 1);
    
    $parent_<?php echo $this->uniqId; ?>.on('click', '.mfm-thumbnail', function() {
        var $this = $(this), 
            $parent = $this.closest('.mfm-thumbnails'), 
            contentId = $this.attr('data-id'), 
            filePath = $this.attr('data-path'), 
            fileExt = $this.attr('data-extention'), 
            $viewer = $parent_<?php echo $this->uniqId; ?>.find('.mfm-viewer'), 
            $viewed = $viewer.find('[data-id="'+contentId+'"]'), 
            fileName = $this.find('.mfm-thumbnail-name').text();
        
        $parent.find('.active').removeClass('active');
        $this.addClass('active');
        
        $viewer.find('[data-id]').hide();
        
        if ($viewed.length) {
            $viewed.show();
        } else {
            
            var notViewerFile = '<div data-id="'+contentId+'" class="text-center mt-5"><div class="mb-2">'+fileName+'</div><a href="mdobject/downloadFile?file='+filePath+'&fileName='+fileName+'&fDownload=1"><i class="icon-file-download"></i></a></div>';
            
            if (fileExt == 'png' || fileExt == 'jpg' || fileExt == 'jpeg' || fileExt == 'gif' || fileExt == 'bmp') {
                
                var imagePrintButton = '<button type="button" class="btn btn-sm default viewer-content-print" data-id="'+contentId+'" data-type="image"><i class="far fa-print"></i></button>';
                    imagePrintButton += '<button type="button" class="btn btn-sm default viewer-content-print viewer-content-fullscreen" data-id="'+contentId+'" style="right: 9px"><i class="far fa-expand"></i></button>';
                    
                $viewer.append(imagePrintButton + '<img src="'+filePath+'" data-id="'+contentId+'">');
                
            } else if (fileExt == 'pdf') {
                
                $viewer.append('<iframe src="api/pdf/web/viewer.html?file=<?php echo URL; ?>'+filePath+'#zoom=page-actual" style="border: 0; width: 100%; height: 100%; margin: 0;" data-id="'+contentId+'"></iframe>');
                
            } else if (fileExt == 'mp4' || fileExt == 'webm' || fileExt == 'avi') {
                
                $viewer.append('<video controls data-id="'+contentId+'"><source src="'+filePath+'" type="video/'+fileExt+'">Your browser does not support HTML5 video.</video>');
                
            } else if (fileExt == 'mp3' || fileExt == 'wav' || fileExt == 'ogg') {
                
                $viewer.append('<audio controls data-id="'+contentId+'"><source src="'+filePath+'" type="audio/'+fileExt+'">Your browser does not support the audio element.</audio>');
                
            }
            <?php
            if ($isIframe) {
            ?>
            else if (fileExt == 'doc' || fileExt == 'docx') {
                
                bpBlockMessageStart('Loading...');
                
                UrlExists('<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>DocEdit.aspx?showRb=0', function(status) {
                    
                    if (status === 200) {
                        
                        var iframePrintButton = '<button type="button" class="btn btn-sm default viewer-content-print" data-id="'+contentId+'" data-type="iframe"><i class="far fa-print"></i></button>';
                        iframePrintButton += '<button type="button" class="btn btn-sm default viewer-content-print viewer-content-fullscreen" data-id="'+contentId+'" style="right: 9px"><i class="far fa-expand"></i></button>';
                        
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
                       
                        var imagePrintButton = '<button type="button" class="btn btn-sm default viewer-content-print viewer-content-fullscreen" data-id="'+contentId+'" style="right: 9px"><i class="far fa-expand"></i></button>';
                        $viewer.append(imagePrintButton + '<iframe src="<?php echo CONFIG_FILE_VIEWER_ADDRESS; ?>SheetEdit.aspx?showRb=0&url=<?php echo URL; ?>'+filePath+'" style="border: 0; width: 100%; height: 100%; margin: 0;" data-id="'+contentId+'"></iframe>').promise().done(function() {
                    
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
    });
    
    $parent_<?php echo $this->uniqId; ?>.on('click', '.viewer-content-print:not(.viewer-content-fullscreen)', function() {
        var $this = $(this), fileType = $this.attr('data-type');
        
        if (fileType == 'iframe') {
            
            var $iframeEl = $this.nextAll('iframe:eq(0)');
            $iframeEl[0].contentWindow.postMessage('print', '*');
            
        } else if (fileType == 'image') {
            
            var $img = $this.nextAll('img:eq(0)');
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
    
    $parent_<?php echo $this->uniqId; ?>.on('click', '.viewer-content-fullscreen', function() {
        var $this = $(this), $parent = $this.closest('.bp-detail-file-viewer-001'),
            $buttons = $parent.find('.viewer-content-fullscreen');
            
        if ($this.hasAttr('data-full-screen')) {
            
            $parent.removeClass('bp-dtl-fullscreen');
            $buttons.find('i').removeClass('fa-compress').addClass('fa-expand');
            $buttons.removeAttr('data-full-screen');
            
            $parent.find('.mfm-thumbnails, .mfm-viewer').css('height', $parent.attr('data-old-height'));
            $('html').css('overflow', '');
            
        } else {
            $parent.addClass('bp-dtl-fullscreen');
            $buttons.find('i').removeClass('fa-expand').addClass('fa-compress');
            $buttons.attr('data-full-screen', 1);
            
            $parent.find('.mfm-thumbnails, .mfm-viewer').css('height', ($(window).height() - 10)+'px');
            $parent.attr('data-old-height', $parent.find('.mfm-thumbnails, .mfm-viewer').css('height'));
            $('html').css('overflow', 'hidden');
        }
    });
    
    $.contextMenu({
        selector: '#multi-file-viewer-<?php echo $this->uniqId; ?> .mfm-thumbnail:not(.not-dwnld)',
        trigger: 'right',
        callback: function (key, opt) {
            if (key === 'download') {
                var $this = opt.$trigger;
                var fileName = $this.find('.mfm-thumbnail-name').text();
                $.fileDownload(URL_APP + 'mdobject/downloadFile?file='+$this.attr('data-path')+'&fileName='+fileName+'&contentId='+$this.attr('data-id')+'&fDownload=1', {
                    httpMethod: 'POST'
                });
            }
        },
        items: {
            'download': {name: plang.get('download_btn'), icon: 'download'}
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