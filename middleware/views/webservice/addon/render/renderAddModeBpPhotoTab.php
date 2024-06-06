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
    <div class="col-md-12">
        <div class="table-toolbar">
            <div class="row">
                <div class="col-md-12">
                    <div class="btn-group bp-view-photo-action">
                        <div class="btn-group mr5">
                            <a href="javascript:;" class="fileinput-button btn btn-sm btn-success" id="ATTACH">
                                <i class="icon-plus3 font-size-12"></i> Зураг сонгох
                                <input type="file" name="bp_photo[]" class="" onchange="onChangePhotoAttach(this);" multiple="multiple"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="list-view-photo"></ul>
    </div>
</div> 

<script type="text/javascript">
    $(function () {
        $('.list-view-photo').on('click', '.dropdown-toggle', function (event) {
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetRigth = $(document).width() - selfOffset.left - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position: 'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetRigth, width: '160px'});
        });
        $('.bp-view-photo-action').on('click', '.dropdown-toggle', function (event) {
            var self = $(this);
            var selfHeight = $(this).parent().height();
            var selfWidth = $(this).parent().width();
            var selfOffset = $(self).offset();
            var selfOffsetLeft = $(document).width() - selfOffset.right - selfWidth;
            var dropDown = self.parent().find('ul');
            $(dropDown).css({position: 'fixed', top: selfOffset.top + selfHeight, left: 'auto', right: selfOffsetLeft, width: '160px'});
        });
    });

    function onChangePhotoAttach(input) {
        if ($(input).hasExtension(['png', 'gif', 'jpeg', 'pjpeg', 'jpg', 'x-png', 'bmp'])) {
            $(input).closest("form").ajaxSubmit({
                type: 'post',
                url: 'mdprocess/addBpUploadPhoto',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    PNotify.removeAll();
                    if (data.status === 'success') {
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success',
                            sticker: false
                        });
                        var li = '';
                        var imageData = data.imageData;
                        $.each(imageData, function (i, r) {
                            li += '<li class="shadow">';
                                li += '<a href="data:' + r.mimeType + ';base64,' + r.origBase64Data + '" class="fancybox-button main" data-rel="fancybox-button">';
                                    li += '<img src="data:' + r.mimeType + ';base64,' + r.thumbBase64Data + '"/>';
                                li += '</a>';
                                li += '<div class="btn-group float-right padding-5">';
                                    li += '<button aria-expanded="false" class="btn default btn-xs dropdown-toggle" type="button" data-toggle="dropdown"></button>';
                                    li += '<ul class="dropdown-menu float-right" role="menu">';
                                        li += '<li>';
                                           
                                        li += '</li>';
                                        li += '<li>';
                                            li += '<a href="javascript:;" onclick="deleteAddBpPhoto(this);"><i class="fa fa-trash"></i> <?php echo $this->lang->line('delete_btn'); ?></a>';
                                        li += '</li>';
                                    li += '</ul>';
                                li += '</div>';
                                 li += '<?php 
                                            $attr = array(
                                                'class' => 'form-control select2 form-control-sm',
                                                'data' => $this->tagData,
                                                'op_value' => 'ID', 
                                                'op_text' => 'CODE| |-| |NAME', 
                                                'style' => 'width: 100%',
                                                'multiple' => 'multiple',
                                                'text' => 'notext',
                                                'onchange' => 'onChangePhotoAttachTag(this)'
                                            );
                                            echo Form::select($attr) 
                                        ?>';
                                li += '<div class="title-photo"></div>';
                                li += '<input type="hidden" name="bp_photo_orig_data[]" value="' + r.origBase64Data + '"/>';
                                li += '<input type="hidden" name="bp_photo_thumb_data[]" value="' + r.thumbBase64Data + '"/>';
                                li += '<input type="hidden" name="bp_photo_extension[]" value="' + r.extension + '"/>';
                                li += '<input type="hidden" name="bp_photo_name[]" value=""/>';
                                li += '<div class="hidden"><select data-path="bp_photo_tag" multiple="multiple" name="bp_photo_tag[]" ><option value=""></option></select></div>';
                            li += '</li>';
                        });

                        $(input).closest('.main-bp-photo-container').find('.list-view-photo').append(li);
                        Core.initFancybox($('.list-view-photo'));
                        Core.initSelect2($('.list-view-photo'));
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
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {
                        var _this = $(elem);
                        _this.parents('li.shadow').remove();
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
        $this.closest('li').find('select[data-path="bp_photo_tag"]').children().val($this.val()).attr('selected', 'selected');
    }
</script>