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

</style>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'lifeCycleFileForm', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>

<div class="row">
    <div class="col-md-12">
        <ul class="grid cs-style-2 list-view0 list-view-file-new">
            <?php if (!is_null($this->taskId)) {
                ?>
                <li class="meta" data-attach-id="0">
                    <a href="javascript:;" class="btn fileinput-button btn-block btn-xs" title="Файл нэмэх">
                        <i class="icon-plus3 big"></i>
                        <input type="hidden" name="metaDataId" value="<?php echo $this->mapId; ?>" />
                        <input type="hidden" name="metaValueId" value="<?php echo $this->taskId; ?>" />
                        <input type="file" name="bp_file" class="" onchange="onChangeAttachFIle(this)" />
                    </a>
                </li>
            <?php }
            ?>

            <?php
            if ($this->metaValueFileRows) {
                foreach ($this->metaValueFileRows as $file) {
                    $bigIcon = "assets/core/global/img/filetype/64/" . $file['FILE_EXTENSION'] . ".png";
                    ?>
                    <li class="meta" data-attach-id="<?php echo $file['ATTACH_ID']; ?>">
                        <figure class="directory">
                            <div class="img-precontainer">
                                <div class="img-container directory">
                                    <?php
                                    if ($file['FILE_EXTENSION'] == 'png' or
                                            $file['FILE_EXTENSION'] == 'gif' or
                                            $file['FILE_EXTENSION'] == 'jpeg' or
                                            $file['FILE_EXTENSION'] == 'pjpeg' or
                                            $file['FILE_EXTENSION'] == 'jpg' or
                                            $file['FILE_EXTENSION'] == 'x-png' or
                                            $file['FILE_EXTENSION'] == 'bmp') {
                                        if (file_exists($file['ATTACH'])) {
                                            $bigIcon = $file['ATTACH'];
                                        }
                                        echo '<a href="' . $bigIcon . '" class="fancybox-button main" data-rel="fancybox-button" title="' . $file['ATTACH_NAME'] . '">';
                                        echo '<img src="' . $bigIcon . '"/>';
                                        echo '</a>';
                                    } else {
                                        echo '<a href="' . $file['ATTACH'] . '" title="' . $file['ATTACH_NAME'] . '" target="_blank" class="main">';
                                        echo '<img src="' . $bigIcon . '"/>';
                                        echo '</a>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="box">
                                <h4 class="ellipsis fileNameCl">
                                    <?php echo $file['ATTACH_NAME']; ?>
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
<?php echo Form::close(); ?>    

<script type="text/javascript">
<?php if (!is_null($this->taskId)) {
    ?>
        var $listViewFile=$('.list-view-file-new');
        $(function(){
            initFileContentMenu();
        });
        function onChangeAttachFIle(input){
            if($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx",
                "zip", "rar"])){
                $(input).closest("form").ajaxSubmit({
                    type: 'post',
                    url: 'mdlifecycle/uploadFileLifeCycle',
                    dataType: 'json',
                    beforeSend: function(){
                        Core.blockUI({
                            animate: true
                        });
                    },
                    success: function(data){
                        PNotify.removeAll();
                        if(data.status === 'success'){
                            new PNotify({
                                title: 'Success',
                                text: data.message,
                                type: 'success',
                                sticker: false
                            });
                            var li='<li class="meta" data-attach-id="' + data.attachId + '">' +
                                    '<figure class="directory">' +
                                    '<div class="img-precontainer">' +
                                    '<div class="img-container directory">';

                            if(data.attachFile != ''){
                                if(data.extension == 'png' ||
                                        data.extension == 'gif' ||
                                        data.extension == 'jpeg' ||
                                        data.extension == 'pjpeg' ||
                                        data.extension == 'jpg' ||
                                        data.extension == 'x-png' ||
                                        data.extension == 'bmp'){
                                    li+='<a href="storage/uploads/contentui/' + data.attachFile +
                                            '" class="fancybox-button main" data-rel="fancybox-button">';
                                    li+='<img src="storage/uploads/contentui/' + data.attachFile + '"/>';
                                    li+='</a>';
                                } else {
                                    li+='<a href="storage/uploads/contentui/' + data.attachFile + '" title="' + data.attachName + '">';
                                    li+='<img src="assets/core/global/img/filetype/64/' + data.fileExtension + '.png"/>';
                                    li+='</a>';
                                }
                            }

                            li+='</div>' +
                                    '</div>' +
                                    '<div class="box">' +
                                    '<h4 class="ellipsis fileNameCl">' + data.attachName + '</h4>' +
                                    '</div>' +
                                    '</a>' +
                                    '</figure>' +
                                    '</li>';
                            var $listViewFile=$('.list-view-file-new');
                            $listViewFile.append(li);
                            Core.initFancybox($listViewFile);

                            initFileContentMenu();
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
                alert('Файл сонгоно уу.');
                $(input).val('');
            }
        }
        function initFileContentMenu(){
            $.contextMenu({
                selector: 'ul.list-view-file-new li.meta',
                callback: function(key, opt){
                    if(key === 'delete'){
                        deleteBpTabFile(opt.$trigger);
                    } else if(key === 'edit'){
                        updateBpTabFile(opt.$trigger);
                    }
                },
                items: {
                    "edit": {name: plang.get('edit_btn'), icon: "edit"},
                    "delete": {name: "Устгах", icon: "trash"}
                }
            });
        }
        function updateBpTabFile(li){
            var dialogName='#update-form-dialog';
            if(!$(dialogName).length){
                $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
            }

            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderBpTabUpdateFileForm',
                data: {metaDataId: '<?php echo $this->mapId; ?>', metaValueId: '<?php echo $this->taskId; ?>', attachId: li.attr('data-attach-id')},
                dataType: "json",
                beforeSend: function(){
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function(data){
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
                            {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function(){
                                    $('form#update-attach-file-form').ajaxSubmit({
                                        type: 'post',
                                        url: 'mdlifecycle/updateFileLifeCycle',
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
                        _this.find('a.main').attr('href', 'storage/uploads/contentui/' + data.attachFile);
                        _this.find('a.main img').attr('src', 'storage/uploads/contentui/' + data.attachFile);
                    } else {
                        _this.find('a.main').attr('href', 'storage/uploads/contentui/' + data.attachFile);
                        _this.find('a.main img').attr('src', 'assets/core/global/img/filetype/64/' + data.extension + '.png');
                    }
                }

                if(data.attachName != '' || data.attachName != 'undefined'){
                    _this.find('.fileNameCl').html(data.attachName);
                    _this.find('a.main').attr('title', data.attachName);
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
        function deleteBpTabFile(li){
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
                                data: {metaDataId: '<?php echo $this->mapId; ?>', metaValueId: '<?php echo $this->taskId; ?>', attachId: li.attr('data-attach-id')},
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
<?php }
?>

</script>