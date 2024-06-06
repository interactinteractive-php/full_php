<div class="row processBannerContent">
    <div class="col-md-3">
        <div class="list-group metaProcessTypeList h-100">
            <?php
            if ($this->processType) {
                foreach ($this->processType as $k => $row) {
                    ?>
                    <a href="javascript:;" class="list-group-item list-group-item-action" id="<?php echo $row['CONTENT_TYPE_ID']; ?>" data-default="<?php echo (empty($k) ? '1' : '');?>">
                        <?php echo $row['CONTENT_TYPE_NAME']; ?> 
                        <span class="badge badge-pill bg-grey-700 ml-auto"><?php echo $row['COUNT_ICON']; ?></span>
                    </a>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-md-12 mb-1">
                <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'banner-attach-file-form', 'method' => 'post', 'enctype' => 'multipart/form-data'));  ?>
                <?php echo Form::hidden(array('name'=> 'bannerProcessTypeId', 'value'=>'')) ?>
                <div class="btn-group bp-view-photo-action">
                    <div class="btn-group mr5">
                        <a href="javascript:;" class="fileinput-button btn btn-sm btn-success" id="ATTACH">
                            <i class="icon-plus3 font-size-12"></i> Зураг сонгох
                            <input type="file" name="bp_photo[]" class="" onchange="onChangeProcessPhotoAttach(this);" multiple="multiple"/>
                        </a>
                    </div>
                </div>
                <?php echo Form::close(); ?>
            </div>
            <div class="col-md-12">
                <div class="scroller" id="processIconRenderDiv" style="height: 350px;" data-handle-color="#637283" data-always-visible="1" data-rail-visible="1" data-handle-size="8px">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" name="contentId" id="contentId">
<input type="hidden" name="contentType" id="contentType">
<input type="hidden" name="contentData" id="contentData">
<input type="hidden" name="contentFilePath" id="contentFilePath">
<input type="hidden" name="contentName" id="contentName">
<input type="hidden" name="rowId" id="rowId" value="0">
<script type="text/javascript">
    $(function () {
        $.contextMenu({
            selector: ".view-icon",
            callback: function (key, opt) {
                if (key === 'view-big') {
                    viewBig(this);
                }
                if (key === 'delete') {
                    deleteBannerPic(this);
                }
            }, 
            items: {
                "view-big": {name: "Томоор харах", icon: "eye"},
                /* "delete": {name: "Устгах", icon: "trash"} */
            }
        });
        $("a.list-group-item-action.active").trigger("click");
        $(".metaProcessTypeList").on("click", "a.list-group-item-action", function () {
            var _this = $(this);
            var processTypeId = _this.attr("id");
            $(".metaProcessTypeList a.list-group-item-action").removeClass("active");
            _this.addClass("active");

            _this.closest('.processBannerContent').find('input[name="bannerProcessTypeId"]').val(processTypeId);
            renderProcessIcon(processTypeId);
        });

        $(".metaProcessTypeList a.list-group-item-action").each(function () {
            var _this = $(this);
            var isDefault = _this.attr("data-default");
            if (isDefault === "1") {
                $(".metaProcessTypeList a.list-group-item-action").removeClass("active");
                _this.addClass("active");
                _this.closest('.processBannerContent').find('input[name="bannerProcessTypeId"]').val(_this.attr("id"));
                renderProcessIcon(_this.attr("id"));
                return;
            }
        });
    });

    function onChangeProcessPhotoAttach(input) {
        if ($(input).hasExtension(['png', 'gif', 'jpeg', 'pjpeg', 'jpg', 'x-png', 'bmp'])) {
            $(input).closest("form").ajaxSubmit({
                type: 'post',
                url: 'mdprocess/addBpUploadBannerPhoto',
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

                        renderProcessIcon($(input).closest("form").find('input[name="bannerProcessTypeId"]').val());
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

    function renderProcessIcon(processTypeId) {
        $.ajax({
            type: 'post',
            url: 'mdmeta/processIconList',
            data: {processTypeId: processTypeId},
            beforeSend: function () {
                Core.blockUI();
            },
            success: function (data) {
                $("#processIconRenderDiv").empty().html(data);
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax();
        });
    }
    function processIconSelect(contentId, contentType, contentData, contentName, contentFilePath) {
        $("#contentId").val(contentId);
        $("#contentType").val(contentType);
        $("#contentData").val(contentData);
        $("#contentName").val(contentName);
        $("#contentFilePath").val(contentFilePath);
    }
    function viewBig(elem) {
        var _this = elem;
        var filepath = _this.attr('data-filepath');
        var dialogName = '#view-big-dialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('<img src="'+filepath+'">');
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Томоор харах',
            width: 'auto',
            height: 'auto',
            modal: true,
            close: function() {
                $(dialogName).empty().dialog('destroy').remove();
            },
            buttons: [
                {text: '<?php echo $this->lang->line('META_00033'); ?>', class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).dialog('close');
                }}
            ]
        });
        $(dialogName).dialog('open');
    }
    function deleteBannerPic(elem) {
        var _this = elem;
        var dataId = _this.attr('data-id');
        var dialogName = '#delete-bannerpic-dialog';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        
        $(dialogName).empty().append('Та Баннэр зургийг устгахдаа итгэлтэй байна уу?');
        $(dialogName).dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Confirm',
            width: 400,
            height: 'auto',
            modal: true,
            close: function() {
                $(dialogName).empty().dialog('destroy').remove();
            },
            buttons: [{
                    text: plang.get('yes_btn'),
                    class: 'btn green-meadow btn-sm',
                    click: function() {
                        $.ajax({
                            type: 'post',
                            url: 'mdprocess/deleteProcessBanner',
                            data: {id: dataId},
                            beforeSend: function () {
                                Core.blockUI();
                            },
                            success: function (data) {
                                _this.closest('.bannerItem').remove();
                                $(dialogName).dialog('close');
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        }).done(function () {
                            Core.initAjax();
                        });
                    }
                },
                {
                    text: plang.get('no_btn'),
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(dialogName).dialog('close');
                    }
                }
            ]
        });

        $(dialogName).dialog('open');
    }
</script>