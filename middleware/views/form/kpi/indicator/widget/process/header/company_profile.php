<style type="text/css">
.newdes-wrapper {
    background-color: #f3f4f6;
    margin-top: -10px;
    margin-left: -15px;
    margin-right: -15px;
    margin-bottom: -4px;
    min-height: 92vh;
}
.newdes_menu_wrapper {
    flex: 0 0 300px;
    margin-right: 20px;    
    background: #fff;
    padding: 12px 11px;
    overflow: hidden;
    border-radius: 10px;
    color: #585858;    
    min-height: 90vh;
}
.newdes_menu_wrapper li a {
    color: #585858;
    text-transform: none !important;
    font-size: 12px;
}
.newdes_menu_wrapper .nav-sidebar .nav-link {
    padding: 6px 12px;
    border-radius: 30px;
}
.newdes_menu_wrapper .nav-sidebar .nav-link span {
    display: block;
    padding-top: 3px;
    font-weight: bold;
    font-size: 13px;
    line-height: 16px;
}
.newdes_menu_wrapper .nav-item-open>.nav-link, 
.newdes_menu_wrapper .nav-item-selected {
    background-color: #E2F4FF;
    border-radius: 30px;
    padding: 10px 15px;
}
.newdes_menu_wrapper .nav-sidebar .nav-item-selected:after {
    content: "";
    font-family: icomoon;
    position: absolute;
    top: 18%;
    right: 0.8rem;
    font-size: 15px;
}
.newdes_menu_wrapper .nav-sidebar .nav-link i {
    margin-right: 0.75rem;
    font-size: 16px;
}
.newdes_menu_wrapper .nav-item-selected span {
    color: #468ce2;
}
.newdes {
    padding: 20px; 
    margin-left: auto;
    margin-right: auto;
    max-width: 1600px;
}
.newdes-body {    
    width: 100%;
    color: #67748E;
    font-size: 12px;
}
.newdes-wrapper .full-header-info {
    border-radius: 10px;
    background: white;
}
.newdes-wrapper .full-header-info img {
    width: 100%;
    background-size: cover;
    height: 300px;
    border-radius: 10px 10px 0px 0px;
    border-bottom: 1px #f5f5f5 solid;
}
.newdes-wrapper .info-text-sub1 {
    font-size: 20px;
    font-weight: 700;
    color: #585858;
}
.newdes-wrapper .info-text-sub2 {
    font-weight: normal;
    font-size: 15px;
}
.newdes-wrapper .info-text {
    padding: 20px;
}
.newdes-wrapper .card-info {
    padding: 10px;
    background: #F2F2F2;
    border-radius: 10px;    
    min-width: 120px;
    text-align: center;
}
.newdes-wrapper .card-info p:first-child {
    font-size: 16px;
    line-height: 22px;
    margin-bottom: 0;
    color: #585858;
    font-weight: 700;
}
.newdes-wrapper .card-info p:last-child {
    line-height: 16px;
    margin-bottom: 0;
    margin-top: 6px;
    font-size: 13px;
}
.newdes-wrapper .newdes-content-section {
    border-radius: 10px;
    background: white;    
    padding: 20px;
}
.newdes-wrapper .newdes-content-sidebar-section {
    border-radius: 10px;
    background: white;    
    padding: 20px;
}
.newdes-wrapper .newdes-title {
    color: #585858;
    font-size: 16px;
}
.newdes-wrapper .newdes-partner {
    gap: 10px;
}
.newdes-wrapper .newdes-activity {
    gap: 10px;
}
.newdes-wrapper .newdes-activity > div {
    width: 230px;
}
.newdes-wrapper .newdes-partner img {
    max-width: 130px;
    background-size: cover;
}
.newdes-logo {
    position: absolute;
    width: 100px;
    height: 100px;
    background: #FFFFFF;
    border: 2px solid #E1E1E1;
    border-radius: 10px;
    background-image: url('middleware/assets/img/company-profile/company-logo.png');
    background-size: cover;
    margin-top: -48px;
}
#newdes-dialog-change-logo input[type="file"] {
    display: none;
}
#newdes-dialog-change-logo .custom-file-upload {
    border: 1px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 10px;
}
#newdes-dialog-change-logo .custom-file-upload i {
    font-size: 18px;
}
#newdes-dialog-change-logo .newdes-preview-image {
    width: 100%;
    height: 200px;
    background: #FFFFFF;
    border: 2px solid #E1E1E1;
    border-radius: 10px;    
    background-image: url('middleware/assets/img/company-profile/200.png');
    background-size: cover;
}
.newdes-body .mv-profile-body {
    border-radius: 10px;
    background-color: #fff;
    padding: 18px;
    min-height: 90vh;
}
.newdes-body .change-newdes-logo:hover i {
    box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
}
.newdes-body .mv-pw-inline-label, 
.newdes-body .mv-pw-inline-value {
    font-size: 14px;
}
.newdes-body .mv-pw-inline-value {
    font-weight: bold;
}
</style>
<div class="newdes-wrapper" data-widget-parent="tag" id="<?php echo $this->uniqId; ?>">
    
    <input type="hidden" name="hiddenParams" value="<?php echo $this->hiddenParams; ?>">
    
    <div class="newdes d-flex">
        <div class="newdes_menu_wrapper">
            <ul class="nav nav-sidebar" data-nav-type="accordion" style="display: block; overflow: auto;">
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-item-selected" onclick="mvWidgetPart_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', 'general');">
                        <i class="icon-folder2" style="color: #FEC345;"></i><span>Ерөнхий</span>
                    </a>
                </li>
                <?php
                foreach ($this->rowsPath as $rowsPath) {
                ?>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link<?php echo ($this->isCreateMode ? ' disabled' : ''); ?>" onclick="mvWidgetRows_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', '<?php echo $rowsPath['ID']; ?>');">
                        <i class="icon-folder2" style="color: #FEC345;"></i>
                        <span><?php echo $rowsPath['NAME']; ?></span>
                    </a>
                </li>
                <?php
                }
                ?>
            </ul>
        </div>
        <div class="newdes-body">
            
            <div data-part="general">
                <div class="full-header-info">
                    <div style="position: relative;">
                        <a href="javascript:;" class="change-newdes-logo" style="position:absolute; top:12px; right:12px;" onclick="mvWidgetCustomPosition_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', '1', '<?php echo issetParam($this->positionData[1]['COLUMN_NAME']); ?>');">
                            <i class="icon-camera font-size-16 border-radius-100 p-2" style="color:#000;background-color: #fff"></i>
                        </a>
                        <img src="<?php echo issetParam($this->positionData[1]['VALUE']); ?>" data-default-image="middleware/assets/img/company-profile/cover-image.png" onerror="onDataViewImgError(this);" data-position="1" data-position-column="<?php echo issetParam($this->positionData[1]['COLUMN_NAME']); ?>"/>
                    </div>
                    <div class="info-text">
                        <div class="newdes-logo" style="background-image: url('<?php echo checkFileDefaultVal($this->positionData[2]['VALUE'], 'middleware/assets/img/company-profile/company-logo.png'); ?>')" data-position="2" data-position-column="<?php echo issetParam($this->positionData[2]['COLUMN_NAME']); ?>">
                            <a href="javascript:;" class="change-newdes-logo" style="position:absolute; bottom:-12px; right:-12px;" onclick="mvWidgetCustomPosition_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', '2', '<?php echo issetParam($this->positionData[2]['COLUMN_NAME']); ?>');">
                                <i class="icon-camera font-size-16 border-radius-100 p-2" style="color:#000;background-color: #ebebeb"></i>
                            </a>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class="info-text-sub1 ml100 pl20">
                                <div data-edit-position="1" data-position="3" data-position-column="<?php echo issetParam($this->positionData[3]['COLUMN_NAME']); ?>">
                                    <?php echo issetParam($this->positionData[3]['VALUE']); ?> 
                                </div>
                                <div class="info-text-sub2" data-edit-position="1" data-position="4" data-position-column="<?php echo issetParam($this->positionData[4]['COLUMN_NAME']); ?>">
                                    <?php echo issetParam($this->positionData[4]['VALUE']); ?> 
                                </div>
                            </div>
                            <div>
                                <a href="javascript:;" class="change-newdes-logo" onclick="mvWidgetEditPosition_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', '1');">
                                    <i class="icon-pencil3 font-size-16 border-radius-100 p-2" style="color:#3A416F;background: #F2F2F2"></i>
                                </a>
                            </div>
                        </div>
                        <div class="info-text-sub2 row">
                            <div class="col-lg-4 col-md-12 mt25">
                                <div data-edit-position="1" data-position="5" data-position-column="<?php echo issetParam($this->positionData[5]['COLUMN_NAME']); ?>">
                                    <?php echo nl2br(issetParam($this->positionData[5]['VALUE'])); ?>                           
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-12 mt25">
                                <div class="d-flex justify-content-end" style="gap:10px">
                                    <div class="card-info">
                                        <p data-edit-position="1" data-position="6" data-position-column="<?php echo issetParam($this->positionData[6]['COLUMN_NAME']); ?>">
                                            <?php echo issetParam($this->positionData[6]['VALUE']); ?> 
                                        </p>
                                        <p><?php echo issetParam($this->positionData[6]['LABEL_NAME']); ?></p>
                                    </div>
                                    <div class="card-info">
                                        <p data-edit-position="1" data-position="7" data-position-column="<?php echo issetParam($this->positionData[7]['COLUMN_NAME']); ?>">
                                            <?php echo issetParam($this->positionData[7]['VALUE']); ?> 
                                        </p>
                                        <p><?php echo issetParam($this->positionData[7]['LABEL_NAME']); ?></p>
                                    </div>
                                    <div class="card-info">
                                        <p data-edit-position="1" data-position="8" data-position-column="<?php echo issetParam($this->positionData[8]['COLUMN_NAME']); ?>">
                                            <?php echo issetParam($this->positionData[8]['VALUE']); ?> 
                                        </p>
                                        <p><?php echo issetParam($this->positionData[8]['LABEL_NAME']); ?></p>
                                    </div>
                                    <div class="card-info">
                                        <p data-edit-position="1" data-position="9" data-position-column="<?php echo issetParam($this->positionData[9]['COLUMN_NAME']); ?>">
                                            <?php echo issetParam($this->positionData[9]['VALUE']); ?> 
                                        </p>
                                        <p><?php echo issetParam($this->positionData[9]['LABEL_NAME']); ?></p>
                                    </div>
                                    <div class="card-info">
                                        <p data-edit-position="1" data-position="10" data-position-column="<?php echo issetParam($this->positionData[10]['COLUMN_NAME']); ?>">
                                            <?php echo issetParam($this->positionData[10]['VALUE']); ?> 
                                        </p>
                                        <p><?php echo issetParam($this->positionData[10]['LABEL_NAME']); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($this->positionData[12]) || isset($this->positionData[13]) || isset($this->positionData[14]) || isset($this->positionData[15]) || isset($this->positionData[16])) {
                ?>
                <div class="row">
                    <div class="col-lg-12 mt20 col-md-12">
                        <div class="newdes-content-section">
                            <a href="javascript:;" class="change-newdes-logo" style="position:absolute; top:12px; right:27px; z-index:1;" onclick="mvWidgetEditPosition_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', '3');">
                                <i class="icon-pencil3 font-size-16 border-radius-100 p-2" style="color:#3A416F;background: #F2F2F2"></i>
                            </a>
                            <div class="row">
                                <?php
                                if ($pos12Label = issetParam($this->positionData[12]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos12Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="12" data-position-column="<?php echo $this->positionData[12]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[12]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[12]['SHOW_TYPE'], $this->positionData[12]['VALUE']); ?>  
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos13Label = issetParam($this->positionData[13]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos13Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="13" data-position-column="<?php echo $this->positionData[13]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[13]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[13]['SHOW_TYPE'], $this->positionData[13]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos14Label = issetParam($this->positionData[14]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos14Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="14" data-position-column="<?php echo $this->positionData[14]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[14]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[14]['SHOW_TYPE'], $this->positionData[14]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos15Label = issetParam($this->positionData[15]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos15Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="15" data-position-column="<?php echo $this->positionData[15]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[15]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[15]['SHOW_TYPE'], $this->positionData[15]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos16Label = issetParam($this->positionData[16]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos16Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="16" data-position-column="<?php echo $this->positionData[16]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[16]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[16]['SHOW_TYPE'], $this->positionData[16]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos17Label = issetParam($this->positionData[17]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos17Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="17" data-position-column="<?php echo $this->positionData[17]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[17]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[17]['SHOW_TYPE'], $this->positionData[17]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos18Label = issetParam($this->positionData[18]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos18Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="18" data-position-column="<?php echo $this->positionData[18]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[18]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[18]['SHOW_TYPE'], $this->positionData[18]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos19Label = issetParam($this->positionData[19]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos19Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="19" data-position-column="<?php echo $this->positionData[19]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[19]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[19]['SHOW_TYPE'], $this->positionData[19]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos20Label = issetParam($this->positionData[20]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12 mb-2">
                                    <span class="mv-pw-inline-label"><?php echo $pos20Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="20" data-position-column="<?php echo $this->positionData[20]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[20]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[20]['SHOW_TYPE'], $this->positionData[20]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                if ($pos21Label = issetParam($this->positionData[21]['LABEL_NAME'])) {
                                ?>
                                <div class="col-md-12">
                                    <span class="mv-pw-inline-label"><?php echo $pos21Label; ?>:</span> 
                                    <span class="mv-pw-inline-value" data-edit-position="3" data-position="21" data-position-column="<?php echo $this->positionData[21]['COLUMN_NAME']; ?>" data-show-type="<?php echo $this->positionData[21]['SHOW_TYPE']; ?>">
                                        <?php echo Mdform::mvValueRender($this->positionData[21]['SHOW_TYPE'], $this->positionData[21]['VALUE']); ?> 
                                    </span>
                                </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                if (isset($this->positionData[11])) {
                ?>
                <div class="row">
                    <div class="col-lg-12 mt20 col-md-12">
                        <div class="newdes-content-section">
                            <div class="d-flex justify-content-between line-height-0 mb20">
                                <p class="newdes-title mt15">
                                    <?php echo issetParam($this->positionData[11]['LABEL_NAME']); ?>
                                </p>
                                <a href="javascript:;" class="change-newdes-logo" onclick="mvWidgetEditPosition_<?php echo $this->uniqId; ?>(this, '<?php echo $this->mainIndicatorId; ?>', '2');">
                                    <i class="icon-pencil3 font-size-16 border-radius-100 p-2" style="color:#3A416F;background: #F2F2F2"></i>
                                </a>
                            </div>
                            <p data-edit-position="2" data-position="11" data-position-column="<?php echo issetParam($this->positionData[11]['COLUMN_NAME']); ?>">
                                <?php echo Str::cleanOut(nl2br(issetParam($this->positionData[11]['VALUE']))); ?>
                            </p>
                        </div>
                    </div>
                    <!--<div class="col-lg-4 col-md-12 mt20">
                        <div class="newdes-content-sidebar-section">
                            <p class="newdes-title text-center">Render chart</p>
                        </div>
                    </div>-->
                </div>
                <?php
                }
                ?>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('.newdes_menu_wrapper').on('click', '.nav-link', function() {
        $('.newdes_menu_wrapper').find('.nav-item-selected').removeClass('nav-item-selected');
        $(this).addClass('nav-item-selected');
    });
});
    
function mvWidgetCustomPosition_<?php echo $this->uniqId; ?>(elem, mainIndicatorId, positionCode, columnName) {
    
    if (positionCode == '1') {
        
        var $dialogName = "newdes-dialog-change-logo";
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName);
        
        $dialog.empty().append('<form method="post" enctype="multipart/form-data">'+
            '<div class="justify-content-center d-flex flex-column" style="gap:10px">'+
                '<div style="align-self: center;" class="newdes-preview-image"></div>'+
                '<div class="d-flex justify-content-center">'+
                    '<label style="align-self: center;" title="Зураг сонгох" for="newdes-file-upload" class="custom-file-upload">'+
                        '<i class="icon-cloud-upload"></i>'+
                    '</label>'+
                    '<label style="align-self: center;" title="Зураг устгах" id="newdes-delete-file-upload" class="custom-file-upload ml8">'+
                        '<i class="icon-cross3"></i>'+
                    '</label>'+
                '</div>'+
                '<input type="file" id="newdes-file-upload" name="kpiTbl['+columnName+']"/>'+
                '<input type="hidden" name="kpiTbl['+columnName+']">'+
            '</div>'+
        '</form>');
    
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Cover image",
            width: 800,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {
                    text: plang.get('save_btn'),
                    class: "btn btn-sm green-meadow",
                    click: function () {
                        
                        var $form = $dialog.find('form');    
                        var $parent = $('#<?php echo $this->uniqId; ?>');
                        
                        $form.ajaxSubmit({
                            type: 'post',
                            url: 'mdform/saveMvWidgetSave',
                            dataType: 'json',
                            beforeSubmit: function(formData, jqForm, options) {
                                formData.push({name: 'hiddenParams', value: $parent.find('input[name="hiddenParams"]').val()});
                            },
                            beforeSend: function () {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function (data) {

                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false, 
                                    addclass: pnotifyPosition
                                });

                                if (data.status == 'success') {
                                    $dialog.dialog('close');
                                    
                                    var dataResult = data.result;
                                    
                                    $parent.find('img[data-position="'+positionCode+'"]').attr('src', dataResult[columnName]);
                                    $parent.find('input[name="hiddenParams"]').val(data.hiddenParams);
                                    $parent.find('.nav-link.disabled').removeClass('disabled');
                                } 

                                Core.unblockUI();
                            }
                        });
                    }
                },
                {
                    text: plang.get('close_btn'),
                    class: 'btn btn-sm blue-madison',
                    click: function () {
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');        
        
        $(document.body).on('change', '#newdes-file-upload', function(){
            var input = $(this)[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.newdes-preview-image').css('background-image', 'url("'+e.target.result+'")');
                };
                reader.readAsDataURL(input.files[0]);
            }
        });    

        $(document.body).on('click', '#newdes-delete-file-upload', function(){
            $('.newdes-preview-image').css('background-image', '');
            $('#newdes-file-upload').val('');
        });    
        
    } else if (positionCode == '2') {
        
        var $dialogName = "newdes-dialog-change-logo";
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }
        var $dialog = $("#" + $dialogName);
        
        $dialog.empty().append('<form method="post" enctype="multipart/form-data">'+
            '<div class="justify-content-center d-flex flex-column" style="gap:10px">'+
                '<div style="align-self: center;" class="newdes-preview-image"></div>'+
                '<div class="d-flex justify-content-center">'+
                    '<label style="align-self: center;" title="Зураг сонгох" for="newdes-file-upload" class="custom-file-upload">'+
                        '<i class="icon-cloud-upload"></i>'+
                    '</label>'+
                    '<label style="align-self: center;" title="Зураг устгах" id="newdes-delete-file-upload" class="custom-file-upload ml8">'+
                        '<i class="icon-cross3"></i>'+
                    '</label>'+
                '</div>'+
                '<input type="file" id="newdes-file-upload" name="kpiTbl['+columnName+']"/>'+
                '<input type="hidden" name="kpiTbl['+columnName+']">'+
            '</div>'+
        '</form>');
    
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Logo",
            width: 400,
            height: "auto",
            modal: true,
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {
                    text: plang.get('save_btn'),
                    class: "btn btn-sm green-meadow",
                    click: function () {
                        
                        var $form = $dialog.find('form');    
                        var $parent = $('#<?php echo $this->uniqId; ?>');
                        
                        $form.ajaxSubmit({
                            type: 'post',
                            url: 'mdform/saveMvWidgetSave',
                            dataType: 'json',
                            beforeSubmit: function(formData, jqForm, options) {
                                formData.push({name: 'hiddenParams', value: $parent.find('input[name="hiddenParams"]').val()});
                            },
                            beforeSend: function () {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function (data) {

                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false, 
                                    addclass: pnotifyPosition
                                });

                                if (data.status == 'success') {
                                    $dialog.dialog('close');
                                    
                                    var dataResult = data.result;
                                    
                                    $parent.find('[data-position="'+positionCode+'"]').css('background-image', 'url(' + dataResult[columnName] + ')');
                                    $parent.find('input[name="hiddenParams"]').val(data.hiddenParams);
                                    $parent.find('.nav-link.disabled').removeClass('disabled');
                                } 

                                Core.unblockUI();
                            }
                        });
                    }
                },
                {
                    text: plang.get('close_btn'),
                    class: 'btn btn-sm blue-madison',
                    click: function () {
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');        
        
        $(document.body).on('change', '#newdes-file-upload', function(){
            var input = $(this)[0];
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.newdes-preview-image').css('background-image', 'url("'+e.target.result+'")');
                };
                reader.readAsDataURL(input.files[0]);
            }
        });    

        $(document.body).on('click', '#newdes-delete-file-upload', function(){
            $('.newdes-preview-image').css('background-image', '');
            $('#newdes-file-upload').val('');
        });    
    }
}
function mvWidgetEditPosition_<?php echo $this->uniqId; ?>(elem, mainIndicatorId, editPositionCode) {
    
    var $parent = $('#<?php echo $this->uniqId; ?>'), 
        $editPosition = $parent.find('[data-edit-position="'+editPositionCode+'"]'),
        columns = [];
    
    $editPosition.each(function() {
        var $this = $(this), positionColumn = $this.attr('data-position-column');
        columns.push(positionColumn);
    });
    
    if (columns.length) {
        $.ajax({
            type: 'post',
            url: 'mdform/mvWidgetColumnsRender',
            data: {hiddenParams: $parent.find('input[name="hiddenParams"]').val(), columns: columns}, 
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                PNotify.removeAll();

                if (data.status == 'success') {
                    
                    var $dialogName = 'dialog-kpiindicatorvalue-'+getUniqueId(1);
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName), uniqId = data.uniqId;
                    
                    $dialog.empty().append('<form method="post" enctype="multipart/form-data">' + data.html + '</form>');
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: 'Edit',
                        width: 850,
                        height: 'auto',
                        modal: true,
                        close: function() {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: [
                            {
                                text: plang.get('save_btn'),
                                class: "btn btn-sm green-meadow",
                                click: function () {

                                    var $form = $dialog.find('form');    

                                    $form.ajaxSubmit({
                                        type: 'post',
                                        url: 'mdform/saveMvWidgetSave',
                                        dataType: 'json',
                                        beforeSubmit: function(formData, jqForm, options) {
                                            formData.push({name: 'hiddenParams', value: $parent.find('input[name="hiddenParams"]').val()});
                                        },
                                        beforeSend: function () {
                                            Core.blockUI({message: 'Loading...', boxed: true});
                                        },
                                        success: function (data) {

                                            PNotify.removeAll();
                                            new PNotify({
                                                title: data.status,
                                                text: data.message,
                                                type: data.status,
                                                sticker: false, 
                                                addclass: pnotifyPosition
                                            });

                                            if (data.status == 'success') {
                                                $dialog.dialog('close');

                                                var dataResult = data.result;
                                                
                                                $editPosition.each(function() {
                                                    var $this = $(this), positionColumn = $this.attr('data-position-column'), showType = '', setValue = '';
                                                    
                                                    if ($this.hasAttr('data-show-type')) {
                                                        showType = $this.attr('data-show-type');
                                                    }
                                                    
                                                    if (dataResult.hasOwnProperty(positionColumn)) {
                                                        var getValue = dataResult[positionColumn];
                                                        if (showType == 'check' || showType == 'boolean') {
                                                            setValue = (getValue == '1' || getValue == 'true') ? 'Тийм' : 'Үгүй';
                                                        } else {
                                                            setValue = html_entity_decode(convertNlToBr(getValue), 'ENT_QUOTES');
                                                        }
                                                        $this.html(setValue);
                                                    }
                                                });

                                                $parent.find('input[name="hiddenParams"]').val(data.hiddenParams);
                                                $parent.find('.nav-link.disabled').removeClass('disabled');
                                            } 

                                            Core.unblockUI();
                                        }
                                    });
                                }
                            },
                            {
                                text: plang.get('close_btn'),
                                class: 'btn btn-sm blue-madison',
                                click: function () {
                                    $dialog.dialog('close');
                                }
                            }
                        ]
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });
                    $dialog.dialog('open');
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                }
                
                Core.unblockUI();
            }, 
            error: function () { alert('Error'); Core.unblockUI(); }
        });
    }
}
function mvWidgetPart_<?php echo $this->uniqId; ?>(elem, mainIndicatorId, partName) {
    var $parent = $('#<?php echo $this->uniqId; ?>');
    var $part = $parent.find('div[data-part="'+partName+'"]');
    
    if ($part.length) {
        $parent.find('div[data-part]').hide();
        $parent.find('div[data-part="'+partName+'"]').show();
    }
}
function mvWidgetRows_<?php echo $this->uniqId; ?>(elem, mainIndicatorId, mapId) {
    var $parent = $('#<?php echo $this->uniqId; ?>');
    var $part = $parent.find('div[data-part="'+mapId+'"]');
    
    $parent.find('div[data-part]').hide();
    
    if ($part.length) {
        $parent.find('div[data-part="'+mapId+'"]').show();
    } else {
        $.ajax({
            type: 'post',
            url: 'mdform/mvWidgetGridRender',
            data: {hiddenParams: $parent.find('input[name="hiddenParams"]').val(), mapId: mapId}, 
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                
                $parent.find('.newdes-body').append('<div data-part="'+mapId+'" class="mv-profile-body">'+data+'</div>').promise().done(function() {
                    Core.unblockUI();
                });
            },
            error: function () { alert('Error'); Core.unblockUI(); }
        });
    }
}
</script>