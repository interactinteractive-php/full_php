<style type="text/css">
.float-right>.dropdown-menu {
    right: auto;
}
.dropdown > .dropdown-menu.float-left:before, .dropdown-toggle > .dropdown-menu.float-left:before, .btn-group > .dropdown-menu.float-left:before {
    left: 9px;
    right: auto;
}
.fileinput-button .big {
    font-size: 70px;
    line-height: 112px;
    text-align: center;
    color: #ddd;
}
.list-view-file-new .meta {
    position: relative;
}
.list-view-file-new .meta .file-remove-row {
    display: none;
    position: absolute;
    top: -10px;
    right: -9px;
    height: 20px;
    width: 20px;
    border-radius: 100px;
    padding: 0;
}
.list-view-file-new .meta:hover .file-remove-row {
    display: inline-block;
}
</style>

<div class="row" data-section-path="pfProcessFileWidget">
    <div class="col-md-12" id="bp_filetab_wrap_<?php echo $this->uniqId; ?>">
        <ul class="grid cs-style-2 list-view0 list-view-file-new">
            <?php
            if ($this->actionType != 'view') {
            ?>   
            <li class="meta" data-attach-id="0">
                <a href="javascript:;" class="btn fileinput-button btn-block btn-xs" title="Файл нэмэх">
                    <i class="icon-plus3 big"></i>
                    <input type="hidden" name="metaDataId" value="<?php echo $this->metaDataId; ?>" />
                    <input type="hidden" name="metaValueId" value="<?php echo $this->metaValueId; ?>" />
                    <input type="file" name="bp_file_update[]" multiple onchange="onChangeAttachFIle(this)" />
                </a>
            </li>
            <?php
            }
            if ($this->metaValueFileRows) {
                foreach ($this->metaValueFileRows as $file) {
                    $bigIcon = "assets/core/global/img/filetype/64/" . $file['FILE_EXTENSION'] . ".png";
            ?>
                <li class="meta" data-attach-id="<?php echo $file['ATTACH_ID']; ?>">
                    
                    <?php
                    if (isset($file['PICTURE']) && $file['PICTURE'] && file_exists($file['PICTURE'])) {
                        echo Ue::getFullUrlPhoto($file['PICTURE'], 'class="rounded-circle avatar position-absolute" width="30" height="30" style="z-index: 99; top: 0; right: 0;box-shadow: -1px 1px 3px 1px #999;-moz-box-shadow: -1px 1px 3px 1px #999;-webkit-box-shadow: -1px 1px 3px 1px #999;"'); 
                    }
                    ?>
                    
                    <figure class="directory">
                        <div class="img-precontainer">
                            <div class="img-container directory">
                                <?php
                                if ($file['FILE_EXTENSION'] == 'png' || 
                                        $file['FILE_EXTENSION'] == 'gif' || 
                                        $file['FILE_EXTENSION'] == 'jpeg' || 
                                        $file['FILE_EXTENSION'] == 'pjpeg' || 
                                        $file['FILE_EXTENSION'] == 'jpg' || 
                                        $file['FILE_EXTENSION'] == 'x-png' || 
                                        $file['FILE_EXTENSION'] == 'bmp') {
                                    
                                    if (file_exists($file['ATTACH'])) {
                                        $bigIcon = $file['ATTACH'];
                                    }
                                    
                                    echo '<a href="' . $bigIcon . '" class="fancybox-img main" data-rel="fancybox-button">';
                                        echo '<img src="' . $bigIcon . '"/>';
                                    echo '</a>';
                                    
                                } else {
                                    
                                    if (issetParam($this->actionType) === 'view') {
                                        if (issetParam($this->callbackFnc) !== '') {
                                            echo '<a href="javascript:;" onclick="'. $this->callbackFnc .'(this, \'\',  \''. $file['ATTACH_ID'] .'\', \''. $file['FILE_EXTENSION'] .'\', \''. $file['ATTACH_NAME'] .'\', \''. URL . $file['ATTACH'] .'\', \''. $file['ATTACH_ID'] .'\', \''. $this->metaDataId .'\', \''. $this->metaDataId .'\');" class="main">';
                                        } else {
                                            echo '<a href="javascript:;" onclick="dataViewFileViewer(this, \''. $file['ATTACH_ID'] .'\', \''. $file['FILE_EXTENSION'] .'\', \''. $file['ATTACH_NAME'] .'\', \''. URL . $file['ATTACH'] .'\', \''. $file['ATTACH_ID'] .'\', \''. $this->metaDataId .'\', \''. $this->metaDataId .'\');" class="main">';
                                        }
                                    } else {
                                        //echo '<a href="' . $file['ATTACH'] . '" target="_blank" class="main">';
                                        echo '<a href="javascript:;" onclick="dataViewFileViewer(this, \''. $file['ATTACH_ID'] .'\', \''. $file['FILE_EXTENSION'] .'\', \''. $file['ATTACH_NAME'] .'\', \''. URL . $file['ATTACH'] .'\', \''. $file['ATTACH_ID'] .'\', \''. $this->metaDataId .'\', \''. $this->metaDataId .'\');" class="main">';
                                    }

                                    echo '<img src="' . $bigIcon . '"/>';
                                    echo '</a>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="box">
                            <h4 class="ellipsis">
                                <?php 
                                $disabled = '';
                                if (issetParam($this->actionType) === 'view') {
                                    $disabled = 'disabled="disabled"';
                                }
                                ?>
                                <input type="text" name="bp_file_name[]" <?php echo $disabled ?> class="form-control col-md-12 bp_file_name title-photo" placeholder="Тайлбар" value="<?php echo $file['ATTACH_NAME']; ?>"/>
                                <i class="fa fa-check updateFileNameBtn"></i>
                            </h4>                                   
                        </div>
                    </figure>
                </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>
</div>  
<script type="text/javascript">
    var $listViewFile_<?php echo $this->uniqId; ?> = $('#bp_filetab_wrap_<?php echo $this->uniqId; ?> .list-view-file-new');
    var $tabName_<?php echo $this->uniqId; ?> = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('.bp-addon-tab > li > a[data-addon-type="file"]');
    var $customext = $tabName_<?php echo $this->uniqId; ?>.attr('data-ext');
    var $exttype = <?php echo json_encode(explode(',', Str::remove_whitespace(Config::getFromCache('CONFIG_FILE_EXT')))); ?>;
    var $fileSize_<?php echo $this->uniqId; ?> = Number('<?php echo Config::getFromCacheDefault('CONFIG_FILE_EXT', null, '20000000'); ?>');
    var $customFileSize_<?php echo $this->uniqId; ?> = $tabName_<?php echo $this->uniqId; ?>.attr('data-file-size');
	
    if ($customext) {
        var customexts = $customext.split(',');
        $exttype = customexts;
    }
    
    if ($customFileSize_<?php echo $this->uniqId; ?>) {
        $fileSize_<?php echo $this->uniqId; ?> = Number($customFileSize_<?php echo $this->uniqId; ?>);
    }

    $(function(){
        var $fileMsg = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('script[data-msg-tab="file"]');
        if ($fileMsg.length) {
            var msgPosition = $fileMsg.attr('data-position');
            if (msgPosition == 'bottom') {
                $('#bp_filetab_wrap_<?php echo $this->uniqId; ?>').find('ul.list-view-file-new').after($fileMsg.text());
            }
        }

        initFileNameHoverEvent_<?php echo $this->uniqId; ?>();
      
        <?php
        if ($this->actionType != 'view') {
        ?> 
        $listViewFile_<?php echo $this->uniqId; ?>.on('click', '.updateFileNameBtn', function() {
            updateBpTabFileInline_<?php echo $this->uniqId; ?>($(this).closest('li.meta'));
        });                

        initFileContentMenu_<?php echo $this->uniqId; ?>();
        <?php
        }
        ?> 
                
        $('#bp_filetab_wrap_<?php echo $this->uniqId; ?>').on('click', '.file-remove-row', function() {
            var $this = $(this);
            var dialogName = '#dialog-bpfile-confirm';
            if (!$(dialogName).length) {
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }
            var $dialog = $(dialogName);

            $dialog.html(plang.get('msg_delete_confirm'));
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: plang.get('msg_title_confirm'), 
                width: 300,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function() {
                        $this.closest('.meta').remove();
                        $dialog.dialog('close');
                    }},
                    {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
        });
        
        var timerAttachHover;
        
        $listViewFile_<?php echo $this->uniqId; ?>.on('mouseenter', 'li[data-attach-id]:not([data-attach-id="0"]):not([data-hasqtip])', function() {
            
            var self = this;
            
            timerAttachHover = setTimeout(function() {
                $(self).qtip({
                    content: {
                        text: function(event, api) {
                            $.ajax({
                                method: 'post', 
                                url: 'mduser/getUserInfoByContentId',
                                data: {id: api.elements.target.attr('data-attach-id')},
                                loading: false, 
                                once: false
                            })
                            .then(function(data) {

                                api.set('content.text', data);

                            }, function(xhr, status, error) {
                                api.set('content.text', status + ': ' + error);
                            });

                            return 'Loading...';
                        }
                    },
                    position: {
                        effect: false,
                        at: 'top center',
                        my: 'bottom center',
                        viewport: $(window) 
                    },
                    show: {
                        ready: true,
                        effect: false
                    },
                    hide: {
                        effect: false, 
                        fixed: true,
                        delay: 70
                    },
                    style: {
                        classes: 'qtip-bootstrap',
                        width: 300, 
                        tip: {
                            width: 12,
                            height: 7
                        }
                    }
                });
            }, 600);
        });
        
        $listViewFile_<?php echo $this->uniqId; ?>.on('mouseleave', 'li[data-attach-id]:not([data-attach-id="0"])', function() {
            if (timerAttachHover) {
                clearTimeout(timerAttachHover);
            }
        });
    });

    function initFileNameHoverEvent_<?php echo $this->uniqId; ?>(){
        $listViewFile_<?php echo $this->uniqId; ?>.find(".bp_file_name").off().on({
            mouseenter: function(){
                $(this).parent().find('.updateFileNameBtn').show();
            },
            mouseleave: function(){
            }
        });
    }
    function onChangeAttachFIle(input) {
        PNotify.removeAll();
        if ($(input).hasExtension($exttype)) {
            
            var f = 0;
            
            for (f; f < input.files.length; f++) {
                var fileName = input.files[f].name;
                var fileSize = Number(input.files[f].size);
                
                if (fileSize > $fileSize_<?php echo $this->uniqId; ?>) {
                    new PNotify({
                        title: 'Info',
                        text: plang.getVar('PF_FILE_SIZE_EXCEEDED_MSG', {
                            'fileName': fileName, 
                            'maxFileSize': parseInt($fileSize_<?php echo $this->uniqId; ?> / 1000000) + 'mb'
                        }),
                        type: 'info',
                        addclass: 'pnotify-center',
                        sticker: false, 
                        delay: 10000000000
                    });    
                    return false;
                }
            }
            
            $(input).closest("form").ajaxSubmit({
                type: 'post',
                url: 'mdwebservice/renderBpTabUploadFile',
                dataType: 'json',
                beforeSend: function(){
                    Core.blockUI({animate: true});
                },
                success: function(data){
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    
                    if (data.status == 'success') {

                        var i = 0, fdata = data.fileRows, fdataLen = fdata.length;
                        
                        for (i; i < fdataLen; i++) {
                            data = fdata[i];
                            
                            var li = '<li class="meta" data-attach-id="' + data.attachId + '">' +
                                    '<figure class="directory">' +
                                    '<div class="img-precontainer">' +
                                    '<div class="img-container directory">';

                            if (data.attachFile != '') {
                                if (data.extension == 'png' ||
                                        data.extension == 'gif' ||
                                        data.extension == 'jpeg' ||
                                        data.extension == 'pjpeg' ||
                                        data.extension == 'jpg' ||
                                        data.extension == 'x-png' ||
                                        data.extension == 'bmp') {
                                    li+='<a href="' + data.attachFile +'" class="fancybox-img main" data-rel="fancybox-button">';
                                    li+='<img src="' + data.attachFile + '"/>';
                                    li+='</a>';
                                } else {
                                    li+='<a href="' + data.attachFile + '" target="_blank" title="' + data.fileName + '">';
                                    li+='<img src="assets/core/global/img/filetype/64/' + data.fileExtension + '.png"/>';
                                    li+='</a>';
                                }
                            }

                            li+='</div>' +
                                    '</div>' +
                                    '<div class="box">' +
                                    '<h4 class="ellipsis"><input type="text" name="bp_file_name[]" value="' + data.attachName + '" class="form-control col-md-12 bp_file_name title-photo" placeholder="Тайлбар"/><i class="fa fa-check updateFileNameBtn"></i></h4>' +
                                    '</div>' +
                                    '</a>' +
                                    '</figure>' +
                                    '</li>';
                            
                            var $listViewFile = $('#bp_filetab_wrap_<?php echo $this->uniqId; ?> .list-view-file-new');
                            $listViewFile.append(li);
                            Core.initFancybox($listViewFile);

                            initFileContentMenu_<?php echo $this->uniqId; ?>();
                            initFileNameHoverEvent_<?php echo $this->uniqId; ?>();
                        }

                        setBpTabFileCount_<?php echo $this->uniqId; ?>();
                    } 
                    Core.unblockUI();
                }
            });
        } else {
            if ($customext) {
                PNotify.removeAll();
                new PNotify({
                    title: 'Файл хавсаргах',
                    text: 'Та ' + $exttype + ' өргөтгөлтэй файл хавсаргана уу.',
                    type: 'info',
                    //sticker: false
                });
                $(input).val('');
            } else {
                alert('Та ' + $exttype + ' өргөтгөлтэй файл хавсаргана уу.')
            }
        }
    }
    function initFileContentMenu_<?php echo $this->uniqId; ?>(){
        $.contextMenu({
            selector: '#bp_filetab_wrap_<?php echo $this->uniqId; ?> ul.list-view-file-new li.meta',
            callback: function(key, opt) {
                if (key == 'edit') {
                    updateBpTabFile_<?php echo $this->uniqId; ?>(opt.$trigger, $fileSize_<?php echo $this->uniqId; ?>);
                } else if (key == 'delete') {
                    deleteBpTabFile_<?php echo $this->uniqId; ?>(opt.$trigger);
                } 
            },
            items: {
                "edit": {name: plang.get('edit_btn'), icon: "edit"},
                "delete": {name: plang.get('delete_btn'), icon: "trash"}
            }
        });
    }
    function updateBpTabFile_<?php echo $this->uniqId; ?>(li, fileSize){
      var dialogName='#update-form-dialog';
      if(!$(dialogName).length){
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
      }

      $.ajax({
        type: 'post',
        url: 'mdwebservice/renderBpTabUpdateFileForm',
        data: {metaDataId: '<?php echo $this->metaDataId; ?>', metaValueId: '<?php echo $this->metaValueId; ?>', attachId: li.attr('data-attach-id'), fileSize: fileSize},
        dataType: "json",
        beforeSend: function(){
          $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
          Core.blockUI({
            animate: true
          });
        },
        success: function(data){
          $(dialogName).empty().append(data.Html);
          $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: data.Title,
            width: '800',
            height: 'auto',
            modal: true,
            buttons: [
              {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function(){
                  $('form#update-attach-file-form').ajaxSubmit({
                    type: 'post',
                    url: 'mdwebservice/renderBpTabUpdateFile',
                    dataType: 'json',
                    beforeSend: function(){
                      Core.blockUI({
                        animate: true
                      });
                    },
                    success: function(data){
                      reloadBpTabFile(data, function(){
                        $(dialogName).dialog('close');
                      });
                    }
                  });
                }},
              {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                  $(dialogName).dialog('close');
                }}
            ]
          });
          $(dialogName).dialog('open');
          Core.unblockUI();
        },
        error: function(){
          alert("Error update form");
        }
      });
    }
    function updateBpTabFileInline_<?php echo $this->uniqId; ?>(li){
      var data={
        attachId: li.data('attach-id'),
        bp_file_name: li.find('.bp_file_name').val()
      };
      Core.blockUI({
        animate: true
      });
      $.ajax({
        url: 'mdwebservice/renderBpTabUpdateFile',
        type: "POST",
        data: data,
        dataType: "json",
        success: function(response){
          reloadBpTabFile(response, function(){
            $listViewFile_<?php echo $this->uniqId; ?>.find(".bp_file_name").blur();
          });
        },
        error: function(jqXHR, exception){
          Core.unblockUI();
        }
      }).complete(function(){
        Core.unblockUI();
      });
    }
    function reloadBpTabFile(data, callback){
      PNotify.removeAll();
      if(data.status === 'success'){
        new PNotify({
          title: 'Success',
          text: data.message,
          type: 'success',
          sticker: false
        });
        var _this=$('li[data-attach-id="' + data.attachId + '"]', 'body');
        if(data.attachFile != ''){
          if(data.extension == 'png' ||
                  data.extension == 'gif' ||
                  data.extension == 'jpeg' ||
                  data.extension == 'pjpeg' ||
                  data.extension == 'jpg' ||
                  data.extension == 'x-png' ||
                  data.extension == 'bmp'){
            _this.find('a.main').attr('href', 'storage/uploads/metavalue/file/' + data.attachFile);
            _this.find('a.main img').attr('src', 'storage/uploads/metavalue/file/' + data.attachFile);
          } else {
            _this.find('a.main').attr('href', 'storage/uploads/metavalue/file/' + data.attachFile);
            _this.find('a.main img').attr('src', 'assets/core/global/img/filetype/64/' + data.extension + '.png');
          }
        }

        if(data.attachName != '' || data.attachName != 'undefined'){
          _this.find('.title-photo').val(data.attachName);
          _this.find('a.main').attr('title', data.attachName);
        }
        if(data.isEmail == '1'){
          _this.find('.bp_file_sendmail').empty().html('<i class="fa fa-check"></i> <?php echo $this->lang->line('sendmail'); ?>');
        } else {
            _this.find('.bp_file_sendmail').empty();
        }
        
        if(typeof callback == "function"){
          callback();
        }
      } else {
        new PNotify({
          title: data.status,
          text: data.message,
          type: data.status,
          sticker: false
        });
      }
      Core.unblockUI();
    }
    function deleteBpTabFile_<?php echo $this->uniqId; ?>(li){
      var dialogName='#deleteConfirm';
      if(!$(dialogName).length){
        $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
      }
      $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
      $(dialogName).dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: 'Сануулах',
        width: '350',
        height: 'auto',
        modal: true,
        buttons: [
          {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function(){
              $.ajax({
                type: 'post',
                url: 'mdwebservice/renderBpTabDeleteFile',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>', metaValueId: '<?php echo $this->metaValueId; ?>', attachId: li.attr('data-attach-id')},
                dataType: "json",
                success: function(data){
                  if(data.status === 'success'){
                    new PNotify({
                      title: 'Success',
                      text: data.message,
                      type: 'success',
                      sticker: false
                    });
                    li.remove();
                    setBpTabFileCount_<?php echo $this->uniqId; ?>();
                  } else {
                    new PNotify({
                      title: 'Error',
                      text: data.message,
                      type: 'error',
                      sticker: false
                    });
                  }
                },
                error: function(){
                  alert("Error");
                }
              });
              $(dialogName).dialog('close');
            }},
          {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function(){
              $(dialogName).dialog('close');
            }}
        ]
      });
      $(dialogName).dialog('open');
    }
    function setBpTabFileCount_<?php echo $this->uniqId; ?>(){
        var $fileCountElem = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('a[data-addon-type="file"]');
        var fileCount = $listViewFile_<?php echo $this->uniqId; ?>.find('figure.directory').length;
        if ($fileCountElem.find('[data-file-count]').length) {
            $fileCountElem.find('[data-file-count]').text('('+fileCount+')').attr('data-file-count', fileCount);
        } else {
            $fileCountElem.append('<span data-file-count="'+fileCount+'">('+fileCount+')</span>');
        }
    }
</script>