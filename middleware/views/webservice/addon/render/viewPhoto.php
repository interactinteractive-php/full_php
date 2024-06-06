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
li._shadow:hover,
img.selected {
    background: #e5f3fb;
    border: 1px solid #70c0e7;
}
</style>
<div class="row main-bp-photo-container" id="bp_phototab_wrap_<?php echo $this->uniqId; ?>" data-section-path="pfProcessPhotoWidget">
    <div class="col-md-12 data-filter">
        <?php 
        if (isset($this->selectTagData) && $this->selectTagData) { 
        ?>
            <a href="javascript:;" class="btn green-meadow btn-circle mb5" data-filter="all"><?php echo 'Бүгд'; ?></a>
            <?php 
            foreach ($this->selectTagData as $tag) { 
            ?>
            <a href="javascript:;" class="btn btn-secondary btn-circle mb5" title="<?php echo $tag['NAME']; ?>" data-filter=".<?php echo $tag['ID']; ?>"><?php echo $tag['CODE'].' | '. substr($tag['NAME'], 0, 2) ?></a>
        <?php 
            }
        } 
        ?>
    </div>
    <div class="col-md-12">
        <?php
        if ($this->actionType != 'view') {
        ?>   
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group bp-view-photo-action">
                        <div class="btn-group bp-view-photo-action">
                            <div class="btn-group mr5">
                                <button class="btn green-meadow btn-circle btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="icon-plus3 font-size-12"></i> Нэмэх
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="javascript:;" class="fileinput-button" id="ATTACH">
                                            <i class="icon-plus3 font-size-12"></i> Зураг сонгох
                                            <input type="hidden" name="metaDataId" value="<?php echo $this->metaDataId;?>" />
                                            <input type="hidden" name="metaValueId" value="<?php echo $this->metaValueId;?>" />
                                            <input type="hidden" name="bp_folder_id[]" data-path="bp_folder_id" />
                                            <input type="file" name="bp_photo[]" onchange="onChangePhotoAttach(this);" multiple="multiple" accept="image/*"/>
                                        </a>
                                    </li>
                                    <!--<li>
                                        <a href="javascript:;" onclick="bpAddFolder(this, '<?php echo issetParam($this->metaDataId); ?>', '<?php echo $this->metaValueId; ?>');">
                                            <i class="fa fa-folder"></i> <?php echo Lang::line('create_folder'); ?>
                                        </a>
                                    </li>-->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>  
        <ul class="list-view-photo">
            <?php
            if ($this->metaValuePhotoFolderRows) {
                foreach ($this->metaValuePhotoFolderRows as $dir) {
            ?>
                <li class="border-none p-1 mr-1 _shadow parent" data-id="<?php echo $dir['FOLDER_ID']; ?>" data-structureId="<?php echo $this->metaDataId; ?>" data-srcid="<?php echo $this->metaValueId; ?>" data-parentid="0">
                    <a href="javascript:;" ondblclick="getChildFolder(this, '<?php echo $dir['FOLDER_ID']; ?>', '0', '<?php echo $this->metaValueId; ?>');">
                        <img src="assets/core/global/img/meta/folder.png" class="text-center w-100 gotoChild"/>
                    </a>
                    <div class="btn-group float-left pt5">
                        <input type="text" name="bp_folder_name" onchange="bpFolderNameChange(this, '<?php echo $dir['FOLDER_ID']; ?>');" class="float-left w-100" placeholder="<?php echo Lang::line('folder_name'); ?>" value="<?php echo $dir['FOLDER_NAME']; ?>">
                        <a class="btn default btn-xs" onclick="deleteAddBpPhotoFolder(this, '<?php echo $dir['FOLDER_ID']; ?>');" type="button"><i class="fa fa-trash"></i></a>
                    </div>                                
                </li>
            <?php
                }
            }
            
            if ($this->metaValuePhotoRows) {
                foreach ($this->metaValuePhotoRows as $photo) {
                    $bigIcon = "assets/core/global/img/meta/photo.png";
                    $smallIcon = "assets/core/global/img/meta/photo-mini.png";
                    if (file_exists($photo['ATTACH_THUMB'])) {
                        $bigIcon = $photo['ATTACH'];
                        $smallIcon = $photo['ATTACH_THUMB'];
                    } elseif (file_exists($photo['ATTACH'])) {
                        $bigIcon = $photo['ATTACH'];
                        $smallIcon = 'api/resizer?action=resize&width=150&file='.$photo['ATTACH'];
                    } else {
                        $bigIcon = 'assets/core/global/img/filetype/64/'.$photo['FILE_EXTENSION'].'.png';
                        $smallIcon = 'assets/core/global/img/filetype/64/'.$photo['FILE_EXTENSION'].'.png';
                    }
            ?>
                <li class="shadow _shadow parent <?php echo $photo['TRG_TAG_IDC']; ?>" data-attach-id="<?php echo $photo['ATTACH_ID']; ?>" data-src-id="<?php echo $photo['TRG_TAG_ID']; ?>" data-parentid="0">
                    <a href="<?php echo $bigIcon; ?>" class="fancybox-button main" data-rel="fancybox-button" data-fancybox="images" title="<?php echo $photo['ATTACH_NAME']; ?>">
                        <img src="<?php echo $smallIcon; ?>"/>
                    </a>
                    <?php
                    if ($this->actionType != 'view') {
                    ?>  
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
                    <?php
                    }
                    ?>
                </li>
            <?php
                }
            }
            ?>
        </ul>
    </div>
</div>  
<script type="text/javascript">
    
    $(function() {
        var $bp_phototab_wrap_<?php echo $this->uniqId; ?> = $('#bp_phototab_wrap_<?php echo $this->uniqId; ?>');
        var $fileMsg = $('div[data-bp-uniq-id="<?php echo $this->uniqId; ?>"]').find('script[data-msg-tab="photo"]');
        var $isDialog_<?php echo $this->uniqId; ?> = $bp_phototab_wrap_<?php echo $this->uniqId; ?>.closest('.ui-dialog-content').length ? true : false;
        
        if ($fileMsg.length) {
            var msgPosition = $fileMsg.attr('data-position');
            if (msgPosition == 'bottom') {
                $('#bp_phototab_wrap_<?php echo $this->uniqId; ?>').find('ul.list-view-photo').after($fileMsg.text());
            }
        }
        Core.initSelect2($('.list-view-photo'));
        
        $('.list-view-photo').on('click', '.dropdown-toggle', function(event){
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetRigth, width: '160px'});
        });
        
        /*$bp_phototab_wrap_<?php echo $this->uniqId; ?>.on('show.bs.dropdown', '.bp-view-photo-action', function() {
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetLeft = $(document).width() - selfOffset.right - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position:'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetLeft, width: '160px'});
        });*/
        
        $('body').on('click', '.data-filter > a', function(e) {
            var $this = $(this);
        
            $('.data-filter').find('a').removeClass('green-meadow').addClass('btn-secondary');
            $this.addClass('green-meadow').removeClass('btn-secondary');
            var filterType = $this.attr('data-filter').replace('.', ',');
            
            if (filterType === 'all') {
                $('ul.list-view-photo li.shadow').removeClass('hidden');
            } else {
                $('ul.list-view-photo li.shadow').addClass('hidden');
                $('ul.list-view-photo li[data-src-id*="'+filterType+'"]').removeClass('hidden');
            }
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
                        if (typeof data.isMulti !== 'undefined' && data.isMulti === '1') {
                            $.each(data.dataAttachPhotoArr, function($index, $row) {
                                
                                appendPhotoContent({
                                    attachId: $row.CONTENT_ID,
                                    photoPath: $row.PHYSICAL_PATH,
                                    photoThumbPath: $row.THUMB_PHYSICAL_PATH
                                }, input);
                            });
                        } else {
                            appendPhotoContent(data, input);
                        }
                        
                        Core.initSelect2($('.list-view-photo'));
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
    
    function appendPhotoContent(data, element) {
        var $parentid = ($(element).closest('.main-bp-photo-container').attr('data-parentid')) ? $(element).closest('.main-bp-photo-container').attr('data-parentid') : '0';
        var li = '<li class="shadow _shadow parent" data-attach-id="'+data.attachId+'" data-parentid="'+ $(element).closest('.main-bp-photo-container').attr('data-parentid') +'">';
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
                /*
                li += '<?php 
                        echo Form::select(array(
                            'class' => 'form-control select2 form-control-sm',
                            'data' => $this->tagData,
                            'op_value' => 'ID', 
                            'op_text' => 'CODE| |-| |NAME', 
                            'style' => 'width: 100%',
                            'multiple' => 'multiple',
                            'text' => 'notext',
                            'onchange' => 'onChangePhotoAttachTag(this)'
                        ));
                        ?>'; */
                li += '<div class="title-photo"></div>';
            li += '</li>';
        $('.list-view-photo').append(li);
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
                                        var $this = $('li[data-attach-id="'+data.attachId+'"]', 'body');
                                        if (data.photoFileName != '') {
                                            $this.find('a').attr('href', data.photoPath);
                                            $this.find('a img').attr('src', data.photoThumbPath);
                                        }
                                        if (data.photoName != '' || data.photoName != 'undefined') {
                                            $this.find('.title-photo').html(data.photoName);
                                            $this.find('a.main').attr('title', data.photoName);
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
    
    function onChangePhotoAttachTag (element) {
        var $this = $(element);
        var li = $this.parents('li.shadow');
        
        $.ajax({
            type: 'post',
            url: 'mdwebservice/tagupdate',
            data: {metaDataId: '<?php echo $this->metaDataId;?>', metaValueId: '<?php echo $this->metaValueId;?>', attachId:li.attr('data-attach-id'), tagId: $this.val()},
            dataType: "json",
            success: function(data) {},
            error: function() {}
        });
    }
    
</script>