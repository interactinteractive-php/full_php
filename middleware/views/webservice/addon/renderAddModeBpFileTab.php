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

<div class="row" id="bp_filetab_wrap_<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <ul class="grid cs-style-2 list-view0 list-view-file-new">
            <li class="meta" data-attach-id="0">
                <a href="javascript:;" class="btn fileinput-button btn-block btn-xs" title="Файл нэмэх">
                    <i class="icon-plus3 big"></i>
                    <input type="file" name="bp_file_temp[]" multiple onchange="onChangeAttachFIleAddMode_<?php echo $this->uniqId; ?>(this);"/>
                </a>
            </li>
        </ul>
        <div class="hiddenFileDiv hidden"></div>
    </div>
</div>
<script type="text/javascript">
$(function(){
    var $fileMsg = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('script[data-msg-tab="file"]');
    if ($fileMsg.length) {
        var msgPosition = $fileMsg.attr('data-position');
        if (msgPosition == 'bottom') {
            $('#bp_filetab_wrap_<?php echo $this->uniqId; ?>').find('ul.list-view-file-new').after($fileMsg.text());
        }
    }
    $('.list-view-file-new').on('click', '.bp_file_sendmail', function(){
        if ($(this).is(':checked')) {
            $(this).closest('.ellipsis').find('input[name="bp_file_sendmail[]"]').val('1');
        } else {
            $(this).closest('.ellipsis').find('input[name="bp_file_sendmail[]"]').val('');
        }
    });
    
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

                    var $ul = $this.closest('.list-view-file-new'), $li = $this.closest('.meta'), 
                        liIndex = $li.index();
                    $('#bp_filetab_wrap_<?php echo $this->uniqId; ?>').find('.hiddenFileDiv').find('input:eq('+(liIndex-1)+')').remove();
                    $li.remove();

                    $dialog.dialog('close');
                }},
                {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    });
});

var $tabName_<?php echo $this->uniqId; ?> = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('.bp-addon-tab > li > a[data-addon-type="file"]');
var $customext_<?php echo $this->uniqId; ?> = $tabName_<?php echo $this->uniqId; ?>.attr('data-ext');
var $exttype_<?php echo $this->uniqId; ?> = <?php echo json_encode(explode(',', Str::remove_whitespace(Config::getFromCache('CONFIG_FILE_EXT')))); ?>;
var $fileSize_<?php echo $this->uniqId; ?> = Number('<?php echo Config::getFromCacheDefault('CONFIG_FILE_EXT', null, '20000000'); ?>');
var $customFileSize_<?php echo $this->uniqId; ?> = $tabName_<?php echo $this->uniqId; ?>.attr('data-file-size');

if ($customext_<?php echo $this->uniqId; ?>) {
    var customexts_<?php echo $this->uniqId; ?> = $customext_<?php echo $this->uniqId; ?>.split(',');
    var $exttype_<?php echo $this->uniqId; ?> = customexts_<?php echo $this->uniqId; ?>;
}
if ($customFileSize_<?php echo $this->uniqId; ?>) {
    $fileSize_<?php echo $this->uniqId; ?> = Number($customFileSize_<?php echo $this->uniqId; ?>);
}

function onChangeAttachFIleAddMode_<?php echo $this->uniqId; ?>(input) {
    
    PNotify.removeAll();
    
    if ($(input).hasExtension($exttype_<?php echo $this->uniqId; ?>)) {
        
        var ext = input.value.match(/\.([^\.]+)$/)[1], f = 0, i = 0;
        
        if (typeof ext !== "undefined") {
            
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

            for (i; i < input.files.length; i++) {
                var fileName = input.files[i].name;
                ext = fileName.match(/\.([^\.]+)$/)[1];

                var li = '', fileImgUniqId = Core.getUniqueID('file_img'), 
                    fileAUniqId = Core.getUniqueID('file_a'), extension = ext.toLowerCase();

                li = '<li class="meta">' +
                        '<figure class="directory">' +
                        '<div class="img-precontainer">' +
                        '<div class="img-container directory">';
                
                if (extension == 'png' ||
                                extension == 'gif' ||
                                extension == 'jpeg' ||
                                extension == 'pjpeg' ||
                                extension == 'jpg' ||
                                extension == 'x-png' ||
                                extension == 'bmp') {

                    li += '<a href="javascript:;" id="' + fileAUniqId + '" class="fancybox-img main" data-rel="fancybox-button">';
                    li += '<img src="" id="' + fileImgUniqId + '"/>';
                    li += '</a>';
                    
                } else {
                    
                    li += '<a href="javascript:;">';
                    li += '<img src="assets/core/global/img/filetype/64/' + extension + '.png"/>';
                    li += '</a>';
                }

                li += '</div>' +
                        '</div>' +
                        '<div class="box">';
                li += '<h4 class="ellipsis"><input type="text" name="bp_file_name[]" class="form-control col-md-12 bp_file_name" placeholder="Тайлбар" value="'+fileName+'"/></h4>' +
                    '</div>' +
                    '</a>' +
                    '</figure>' +
                    '<a href="javascript:;" class="btn red btn-xs file-remove-row" title="'+plang.get('delete_btn')+'"><i class="icon-cross3"></i></a>'+
                    '</li>';

                var $listViewFile = $('.list-view-file-new');
                $listViewFile.append(li);
                Core.initFancybox($listViewFile);
                Core.initUniform($listViewFile);

                previewPhotoAddMode(input.files[i], $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));

                initFileContentMenuAddMode_<?php echo $this->uniqId; ?>();
            }

            var $this = $(input), $clone = $this.clone();
            $this.after($clone).appendTo($('.hiddenFileDiv'));          
            $('.hiddenFileDiv > input').each(function(){
               $(this).attr('name', 'bp_file[]');
            });
        }
        
    } else {
        if ($customext_<?php echo $this->uniqId; ?>) {
            new PNotify({
                title: 'Файл хавсаргах',
                text: 'Та ' + $exttype_<?php echo $this->uniqId; ?> + ' өргөтгөлтэй файл хавсаргана уу.',
                type: 'info'
            });
            $(input).val('');
        } else {
            alert('Та ' + $exttype_<?php echo $this->uniqId; ?> + ' өргөтгөлтэй файл хавсаргана уу.')
        }
    }
}

function previewPhotoAddMode(input, $targetImg, $targetAnchor){
    if (input) {
        var reader=new FileReader();
        reader.onload=function(e){
            $targetImg.attr('src', e.target.result);
            $targetAnchor.attr('href', e.target.result);
        };
        reader.readAsDataURL(input);
    }
}

function initFileContentMenuAddMode_<?php echo $this->uniqId; ?>(){ 
    $.contextMenu({
        selector: '#bp_filetab_wrap_<?php echo $this->uniqId; ?> ul.list-view-file-new li.meta',
        callback: function(key, opt){
            if (key === 'delete') {
                deleteBpTabFileAddMode(opt.$trigger);
            }
        },
        items: {
            "delete": {name: "Устгах", icon: "trash"}
        }
    });
}

function deleteBpTabFileAddMode(li){
    var dialogName='#deleteConfirm';
    if (!$(dialogName).length) {
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
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function(){
                li.remove();
                $(dialogName).dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function(){
                $(dialogName).dialog('close');
            }}
        ]
    });
    $(dialogName).dialog('open');
}
</script>