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
<div class="row">
    <div class="col-md-12">
        <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'finishLogForm_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data', 'style' => '')); ?>
        <input type="hidden" name="id" value='<?php echo $this->id ?>' />
        <input type="hidden" name="typeId" value='<?php echo $this->dataRow['typeid'] ?>' />
        <div class="col-md-12">
            <table class="table table-sm table-no-bordered bp-header-param" style="width:600px;">
                <tbody>
                    <tr data-cell-path="areaId">
                        <td class="text-right middle" data-cell-path="areaId" style="width: 20%">
                            <label for="param[areaId]" data-label-path="areaId">Шийдвэр:</label>
                        </td>
                        <td class="middle" data-cell-path="areaId" style="width: 80%" colspan="">
                            <?php
                                echo Form::select(
                                    array(
                                        'name' => 'areaId[]',
                                        'id' => 'areaId',
                                        'text' => 'notext',
                                        'class' => 'form-control select2 form-control-sm mt10',
                                        'data' => $this->dataList,
                                        'op_value' => 'META_VALUE_ID',
                                        'op_text' => 'META_VALUE_NAME',
                                        'multiple' => 'multiple',
                                        'required' => 'required'
                                    )
                                );
                            ?>
                        </td>
                    </tr>
                    <tr data-cell-path="decision">
                        <td class="text-right middle" data-cell-path="decision" style="width: 20%">
                            <label for="decision" data-label-path="decision">Тайлбар:</label>
                        </td>
                        <td class="middle" data-cell-path="decision" style="width: 80%" colspan="">
                            <div data-section-path="decision">
                                <textarea name="decision" class="form-control form-control-sm description_autoInit" data-path="decision" data-field-name="decision" value="" spellcheck="false" data-isclear="0" style="height: 39px;overflow: hidden;" placeholder="Тайлбар"></textarea>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <fieldset>
            <legend>
                Файл нэмэх
            </legend>
            <div class="col-md-12">
                <ul class="grid cs-style-2 list-view0 list-view-file-new">
                    <li class="meta" data-attach-id="0">
                        <a href="javascript:;" class="btn fileinput-button btn-block btn-xs" title="Файл нэмэх">
                            <i class="icon-plus3 big"></i>
                            <input type="file" name="bp_file[]" class="" multiple onchange="onChangeAttachFIleAddMode(this)" />
                        </a>
                    </li>
                </ul>
                <div class="hiddenFileDiv hidden"></div>
            </div>
        </fieldset>
        <?php echo Form::close(); ?>
    </div>
</div> 
<script type="text/javascript">
    $(function () {
        $('.list-view-file-new').on('click', '.bp_file_sendmail', function () {
            if ($(this).is(':checked')) {
                $(this).closest('.ellipsis').find('input[name="bp_file_sendmail[]"]').val('1');
            } else {
                $(this).closest('.ellipsis').find('input[name="bp_file_sendmail[]"]').val('');
            }
        });
    });
    function onChangeAttachFIleAddMode(input) {
        if ($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx",
            "zip", "rar", "mp3", "mp4"])) {
            var ext = input.value.match(/\.([^\.]+)$/)[1],
                    i = 0;
            if (typeof ext !== "undefined") {

                for (i; i < input.files.length; i++) {
                    ext = input.files[i].name.match(/\.([^\.]+)$/)[1];

                    var li = '',
                            fileImgUniqId = Core.getUniqueID('file_img'),
                            fileAUniqId = Core.getUniqueID('file_a'),
                            extension = ext.toLowerCase();

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
                        li += '<a href="javascript:;" id="' + fileAUniqId + '" class="fancybox-button main" data-rel="fancybox-button">';
                        li += '<img src="" id="' + fileImgUniqId + '"/>';
                        li += '</a>';
                    } else {
                        li += '<a href="javascript:;" title="">';
                        li += '<img src="assets/core/global/img/filetype/64/' + extension + '.png"/>';
                        li += '</a>';
                    }

                    li += '</div>' +
                            '</div>' +
                            '<div class="box">';
                    li +=
                            '<h4 class="ellipsis"><input type="text" name="bp_file_name[]" class="form-control col-md-12 bp_file_name" placeholder="Тайлбар"/></h4>' +
                            '<h4 class="ellipsis"><input type="checkbox" class="bp_file_sendmail"><input type="hidden" name="bp_file_sendmail[]"><?php echo $this->lang->line('sendmail'); ?></h4>' +
                            '</div>' +
                            '</a>' +
                            '</figure>' +
                            '</li>';
                    var $listViewFile = $('.list-view-file-new');
                    $listViewFile.append(li);
                    Core.initFancybox($listViewFile);
                    Core.initUniform($listViewFile);

                    previewPhotoAddMode(input.files[i], $listViewFile.find('#' + fileImgUniqId), $listViewFile.find('#' + fileAUniqId));

                    initFileContentMenuAddMode();
                }
                var $this = $(input), $clone = $this.clone();
                $this.after($clone).appendTo($('.hiddenFileDiv'));

            }
        } else {
            alert('Файл сонгоно уу.');
            $(input).val('');
        }
    }

    function previewPhotoAddMode(input, $targetImg, $targetAnchor) {
        if (input) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $targetImg.attr('src', e.target.result);
                $targetAnchor.attr('href', e.target.result);
            };
            reader.readAsDataURL(input);
        }
    }

    function initFileContentMenuAddMode() {
        $.contextMenu({
            selector: 'ul.list-view-file-new li.meta',
            callback: function (key, opt) {
                if (key === 'delete') {
                    deleteBpTabFileAddMode(opt.$trigger);
                }
            },
            items: {
                "delete": {name: "Устгах", icon: "trash"}
            }
        });
    }

    function deleteBpTabFileAddMode(li) {
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
                        li.remove();
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