<style type="text/css">
    .float-right>.dropdown-menu {
        right: auto;
    }
    .dropdown > .dropdown-menu.float-left:before, 
    .dropdown-toggle > .dropdown-menu.float-left:before, 
    .btn-group > .dropdown-menu.float-left:before {
        left: 9px;
        right: auto;
    }
    .fileinput-button .big {
        font-size: 70px;
        line-height: 112px;
        text-align: center;
        color: #ddd;
    }
    li._shadow:hover {
        background: #e5f3fb;
        border: 1px solid #70c0e7;
    }
</style>

<div class="row main-bp-photo-container" id="bp_phototab_wrap_<?php echo $this->uniqId; ?>">
    <div class="col-md-12">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <div class="fileinput-button">
                        <input type="file" name="bp_photo[]" onchange="onChangePhotoAttach_<?php echo $this->uniqId; ?>(this);" multiple="multiple" accept="image/*"/>
                    </div>
                    <div class="btn-group bp-view-photo-action">
                        <div class="btn-group mr5">
                            <button class="btn green-meadow btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="icon-plus3 font-size-12"></i> Нэмэх
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="javascript:;" onclick="onClickPhotoAttach_<?php echo $this->uniqId; ?>(this);" id="ATTACH">
                                        <i class="fa fa-photo"></i> Зураг сонгох
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="bpAddPhotoFromWebcam(this, '', '');">
                                        <i class="fa fa-camera"></i> Веб камер
                                    </a>
                                </li>
                                <!--<li>
                                    <a href="javascript:;" onclick="bpAddFolder(this, '<?php echo issetParam($this->refStructureId); ?>', '');">
                                        <i class="fa fa-folder"></i> <?php echo Lang::line('create_folder'); ?>
                                    </a>
                                </li>-->
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="list-view-photo">
        </ul>
    </div>
</div> 

<script type="text/javascript">
var $bp_phototab_wrap_<?php echo $this->uniqId; ?> = $('#bp_phototab_wrap_<?php echo $this->uniqId; ?>');
var $fileMsg = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('script[data-msg-tab="photo"]');
var $isDialog_<?php echo $this->uniqId; ?> = $bp_phototab_wrap_<?php echo $this->uniqId; ?>.closest('.ui-dialog-content').length ? true : false;
    
$(function() {
    
    if ($fileMsg.length) {
        var msgPosition = $fileMsg.attr('data-position');
        if (msgPosition == 'bottom') {
            $bp_phototab_wrap_<?php echo $this->uniqId; ?>.find('ul.list-view-photo').after($fileMsg.text());
        }
    }
    
    $('.list-view-photo').on('click' , '.dropdown-toggle', function(event){
        if ($isDialog_<?php echo $this->uniqId; ?>) {
            var self = $(this);
            var selfHeight = self.parent().height();
            var selfWidth = self.parent().width();
            var selfOffset = self.offset();
            var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetRigth, width: '160px'});
        }
    });
    
    /*
    $bp_phototab_wrap_<?php echo $this->uniqId; ?>.on('show.bs.dropdown', '.bp-view-photo-action', function() {
        
        if ($isDialog_<?php echo $this->uniqId; ?>) {
        
            var self = $(this);
            var selfParent = self.parent();
            var selfOffset = self.offset();
            var dropDown = selfParent.find('ul');

            dropDown.css({position:'fixed', top: selfOffset.top + self.height(), left: 'auto', width: '160px', display: '', transform: ''});
        }
    });
    
    $bp_phototab_wrap_<?php echo $this->uniqId; ?>.on('hide.bs.dropdown', '.bp-view-photo-action', function() {
        if ($isDialog_<?php echo $this->uniqId; ?>) {
            $(this).find('ul').hide();
        }
    });
    */
});
function onClickPhotoAttach_<?php echo $this->uniqId; ?>(elem) {
    $('input[onchange*="onChangePhotoAttach_<?php echo $this->uniqId; ?>"]').trigger('click');
}
function onChangePhotoAttach_<?php echo $this->uniqId; ?>(input) {
    
    $bp_phototab_wrap_<?php echo $this->uniqId; ?>.find('.bp-view-photo-action').dropdown('hide');
    
    if ($(input).hasExtension(['png','gif','jpeg','pjpeg','jpg','x-png','bmp'])) {
        $(input).closest("form").ajaxSubmit({
            type: 'post',
            url: 'mdprocess/addBpUploadPhoto',
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                    
                if (data.status === 'success') {
                    var li = '';
                    var imageData = data.imageData;
                    var parentid = typeof $(input).closest('.main-bp-photo-container').attr('data-parentid') !== 'undefined' ? $(input).closest('.main-bp-photo-container').attr('data-parentid') : '';
                    
                    $.each(imageData, function(i, r) {
                        li += '<li class="shadow _shadow" data-parentid="'+ parentid +'">';
                        li += '<a href="data:'+r.mimeType+';base64,'+r.origBase64Data+'" class="fancybox-button main" data-fancybox="images" data-rel="fancybox-button">';
                        li += '<img src="data:'+r.mimeType+';base64,'+r.thumbBase64Data+'"/>';
                        li += '</a>';
                        li += '<div class="btn-group float-right padding-5">';
                            li += '<button aria-expanded="false" class="btn default btn-xs dropdown-toggle" type="button" data-toggle="dropdown"></button>';
                            li += '<ul class="dropdown-menu float-right" role="menu">';
                            li += '<li>';
                                li += '<a href="javascript:;" onclick="deleteAddBpPhoto(this);"><i class="fa fa-trash"></i> <?php echo $this->lang->line('delete_btn'); ?></a>';
                            li += '</li>';
                        li += '</ul>';
                        li += '</div>';
                        li += '<div class="title-photo"></div>';
                        li += '<input type="hidden" name="bp_photo_orig_data[]" value="'+r.origBase64Data+'"/>';
                        li += '<input type="hidden" name="bp_photo_thumb_data[]" value="'+r.thumbBase64Data+'"/>';
                        li += '<input type="hidden" name="bp_photo_extension[]" value="'+r.extension+'"/>';
                        li += '<input type="hidden" name="bp_folderid[]" value="'+parentid+'"/>';
                        li += '<input type="hidden" name="bp_photo_name[]" value=""/>';
                        li += '</li>';
                    });
                    
                    $(input).closest('.main-bp-photo-container').find('.list-view-photo').append(li);
                    Core.initFancybox($('.list-view-photo'));
                    Core.initUniform($('.list-view-photo'));
                } 
                Core.unblockUI();
            }
        });
    } else {
        alert('Зурган файл сонгоно уу.');
        $(input).val('');
    }
}

function deleteAddBpPhoto(elem) {
    var dialogName = '#deleteConfirm';
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
            {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
                var _this = $(elem);
                _this.parents('li.shadow').remove();
                
                $(dialogName).dialog('close');
            }},
            {text: plang.get('no_btn'), class: 'btn blue-madison btn-sm', click: function () {
                $(dialogName).dialog('close');
            }}
        ]
    });
    $(dialogName).dialog('open');
}
</script>
