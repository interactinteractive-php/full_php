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
</style>
<div class="row main-bp-photo-container">
    <div class="col-md-12" id="bp_phototab_wrap_<?php echo $this->uniqId; ?>">
        
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group bp-view-photo-action">
                        <div class="btn-group mr5">
                            <button class="btn green-meadow btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="icon-plus3 font-size-12"></i> Нэмэх
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="javascript:;" class="fileinput-button" id="ATTACH">
                                        <i class="fa fa-photo"></i> Зураг сонгох
                                        <input type="hidden" name="metaDataId" value="<?php echo $this->metaDataId;?>" />
                                        <input type="hidden" name="metaValueId" value="<?php echo $this->metaValueId;?>" />
                                        <input type="file" name="bp_photo" onchange="onChangePhotoAttach(this)" accept="image/*"/>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:;" onclick="bpAddPhotoFromWebcam(this, '<?php echo $this->metaDataId; ?>', '<?php echo $this->metaValueId; ?>');">
                                        <i class="fa fa-camera"></i> Веб камер
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <ul class="list-view-photo">
            <?php
            if ($this->metaValuePhotoRows) {
                foreach ($this->metaValuePhotoRows as $photo) {
                    $bigIcon = "assets/core/global/img/meta/photo.png";
                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";
                    if (file_exists($photo['ATTACH_THUMB'])) {
                        $bigIcon = $photo['ATTACH'];
                        $smallIcon = $photo['ATTACH_THUMB'];
                    } else {
                        $bigIcon = 'assets/core/global/img/filetype/64/'.$photo['FILE_EXTENSION'].'.png';
                        $smallIcon = 'assets/core/global/img/filetype/64/'.$photo['FILE_EXTENSION'].'.png';
                    }
                ?>
                <li class="shadow" data-attach-id="<?php echo $photo['ATTACH_ID'];?>">
                    <a href="<?php echo $bigIcon; ?>" class="fancybox-button main" data-fancybox="images" data-rel="fancybox-button" title="<?php echo $photo['ATTACH_NAME']; ?>">
                        <img src="<?php echo $smallIcon; ?>"/>
                    </a>
                    <div class="btn-group float-right padding-5">
                        <button aria-expanded="false" class="btn default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                        </button>
                        <ul class="dropdown-menu float-right" role="menu">
                            <li>
                                <a href="javascript:;" onclick="updateBpTabPhoto(this);"><i class="fa fa-edit"></i> <?php echo $this->lang->line('edit_btn'); ?></a>
                            </li>
                            <li>
                                <a href="javascript:;" onclick="deleteBpTabPhoto(this);"><i class="fa fa-trash"></i> <?php echo $this->lang->line('delete_btn'); ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="title-photo">
                        <?php echo $photo['ATTACH_NAME']; ?>
                    </div>
                    <div class="bp_file_sendmail">
                        <?php echo $photo['IS_EMAIL'] == '1' ? '<i class="fa fa-check"></i> ' . $this->lang->line('sendmail') : ''; ?>
                    </div>                                
                </li>
                <?php
                }
            }
            ?>
        </ul>
    </div>
</div>  
<script type="text/javascript">
$(function(){
    var $fileMsg = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('script[data-msg-tab="photo"]');
    if ($fileMsg.length) {
        var msgPosition = $fileMsg.attr('data-position');
        if (msgPosition == 'bottom') {
            $('#bp_phototab_wrap_<?php echo $this->uniqId; ?>').find('ul.list-view-photo').after($fileMsg.text());
        }
    }
        
    $('.list-view-photo').on('click' , '.dropdown-toggle', function(event){
        var self = $(this);
        var selfHeight = $(this).parent().height();
        var selfWidth = $(this).parent().width();
        var selfOffset = $(self).offset();
        var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
        var dropDown = self.parent().find('ul');
        $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetRigth, width: '160px'});
    });
    $('.bp-view-photo-action').on('click' , '.dropdown-toggle', function(event){
        var self = $(this);
        var selfHeight = $(this).parent().height();
        var selfWidth = $(this).parent().width();
        var selfOffset = $(self).offset();
        var selfOffsetLeft = $(document).width() - selfOffset.right - selfWidth;
        var dropDown = self.parent().find('ul');
        $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetLeft, width: '160px'});
    });
});
    function onChangePhotoAttach(input) {
        if ($(input).hasExtension(["png","gif","jpeg","pjpeg","jpg","x-png","bmp"])) {
            Core.blockUI({
                animate: true
            });
            $(input).closest("form").ajaxSubmit({
                type: 'post',
                url: 'mdwebservice/renderBpTabUploadPhoto',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
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
                        var li = '<li class="shadow" data-attach-id="'+data.attachId+'">';
                        li += '<a href="'+data.photoPath+'" class="fancybox-button main" data-fancybox="images" data-rel="fancybox-button">';
                        li += '<img src="'+data.photoThumbPath+'"/>';
                        li += '</a>';
                        li += '<div class="btn-group float-right padding-5">';
                            li += '<button aria-expanded="false" class="btn default btn-xs dropdown-toggle" type="button" data-toggle="dropdown"></button>';
                            li += '<ul class="dropdown-menu float-right" role="menu">';
                            li += '<li>';
                                li += '<a href="javascript:;" onclick="updateBpTabPhoto(this)"><i class="fa fa-edit"></i> <?php echo $this->lang->line('edit_btn'); ?></a>';
                            li += '</li>';
                            li += '<li>';
                                li += '<a href="javascript:;" onclick="deleteBpTabPhoto(this)"><i class="fa fa-trash"></i> <?php echo $this->lang->line('delete_btn'); ?></a>';
                            li += '</li>';
                        li += '</ul>';
                        li += '</div>';
                        li += '<div class="title-photo"></div>';
                        li += '</li>';
                        $('.list-view-photo').append(li);
                        Core.initFancybox($('.list-view-photo'));
                    } 
                    Core.unblockUI();
                }
            });
        } else {
            alert('Зурган файл сонгоно уу.');
            $(input).val('');
        }
    }
    function updateBpTabPhoto(elem) {
        var dialogName = '#update-form-dialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var _this = $(elem);
        var li = _this.parents('li.shadow');
        $.ajax({
            type: 'post',
            url: 'mdwebservice/renderBpTabUpdatePhotoForm',
            data: {metaDataId: '<?php echo $this->metaDataId;?>', metaValueId: '<?php echo $this->metaValueId;?>', attachId:li.attr('data-attach-id')},
            dataType: "json",
            beforeSend: function(){
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data) {
                $(dialogName).html(data.Html);
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
                        {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                            $('form#update-attach-form').ajaxSubmit({
                                type: 'post',
                                url: 'mdwebservice/renderBpTabUpdatePhoto',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({
                                        animate: true
                                    });
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
                                        var _this = $('li[data-attach-id="'+data.attachId+'"]', 'body');
                                        if (data.photoFileName != '') {
                                            _this.find('a').attr('href', data.photoPath);
                                            _this.find('a img').attr('src', data.photoThumbPath);
                                        }
                                        if (data.photoName != '' || data.photoName != 'undefined') {
                                            _this.find('.title-photo').html(data.photoName);
                                            _this.find('a.main').attr('title', data.photoName);
                                        }
                                        if(data.isEmail == '1'){
                                          _this.find('.bp_file_sendmail').empty().html('<i class="fa fa-check"></i> <?php echo $this->lang->line('sendmail'); ?>');
                                        } else {
                                            _this.find('.bp_file_sendmail').empty();
                                        }                                        
                                        $(dialogName).dialog('close');
                                    } 
                                    Core.unblockUI();
                                }
                            });
                        }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $(dialogName).dialog('close');
                        }}
                    ]
                });
                $(dialogName).dialog('open');                
                Core.unblockUI();
            },
            error: function() {
                alert("Error update form");
            }
        });
    }
    function deleteBpTabPhoto(elem) {
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
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        var _this = $(elem);
                        var li = _this.parents('li.shadow');
                    $.ajax({
                        type: 'post',
                        url: 'mdwebservice/renderBpTabDeletePhoto',
                        data: {metaDataId: '<?php echo $this->metaDataId;?>', metaValueId: '<?php echo $this->metaValueId;?>', attachId:li.attr('data-attach-id')},
                        dataType: "json",
                        success: function(data) {
                            if (data.status === 'success') {
                                new PNotify({
                                    title: 'Success',
                                    text: 'Амжилттай устгагдлаа.',
                                    type: 'success',
                                    sticker: false
                                });
                                li.remove();
                            } else {
                                new PNotify({
                                    title: 'Error',
                                    text: 'Алдаа гарлаа',
                                    type: 'error',
                                    sticker: false
                                });
                            }
                        },
                        error: function() {
                            alert("Error delete photo");
                        }
                    });
                    $(dialogName).dialog('close');
                }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).dialog('close');
                }}
            ]
        });
        $(dialogName).dialog('open');
    }
</script>