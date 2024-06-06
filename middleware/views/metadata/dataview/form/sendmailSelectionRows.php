<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'dataview-mail-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="col-md-12 xs-form">
    <?php
    if ($this->isSetFromCombo == 'true') {
    ?>
    <div class="form-group row">
        <?php echo Form::label(array('text' => $this->lang->line('MET_99990096'), 'for' => 'setFrom', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-4">
            <?php 
            echo Form::select(
                array(
                    'name' => 'setFrom', 
                    'id' => 'setFrom', 
                    'class' => 'form-control form-control-sm', 
                    'data' => $this->setFromEmails, 
                    'op_value' => 'EMAIL', 
                    'op_text' => 'EMAIL', 
                    'value' => Ue::getSessionEmail()
                )
            ); 
            ?>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="form-group row">
        <?php echo Form::label(array('text' => $this->lang->line('subject_to'), 'for' => 'emailTo', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <?php echo $this->emailToControl; ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Cc', 'for' => 'emailToCc', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailToCc', 'id' => 'emailToCc', 'class'=>'form-control form-control-sm', 'value' => issetParam($this->emailCc))); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Bcc', 'for' => 'emailToBcc', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailToBcc', 'id' => 'emailToBcc', 'class'=>'form-control form-control-sm')); ?>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text' => $this->lang->line('MET_330477'), 'for' => 'emailSubject', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailSubject', 'value' => $this->emailSubject, 'id' => 'emailSubject', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
    <?php
    if (isset($this->emailTplCombo)) {
    ?>
    <div class="form-group row">
        <?php echo Form::label(array('text' => $this->lang->line('MET_99990923'), 'for' => 'setFrom', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-6">
            <?php 
            echo Form::select(
                array(
                    'id' => 'emailTplCombo', 
                    'class' => 'form-control form-control-sm', 
                    'data' => $this->emailTplCombo, 
                    'op_value' => 'CODE', 
                    'op_text' => 'NAME', 
                    'value' => $this->emailTplCombo[0]['CODE']
                )
            ); 
            ?>
        </div>
    </div>
    <?php
    }
    if ($this->isRowsAttachType == 'true') {
    ?>
    <div class="form-group row">
        <?php echo Form::label(array('text' => 'Хавсаргах төрөл', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-4">
            <div class="radio-list">
                <label class="radio-inline">
                    <input type="checkbox" name="rowsAttachType[]" value="excel" checked="checked"> Эксель
                </label>
                <label class="radio-inline">
                    <input type="checkbox" name="rowsAttachType[]" value="pdf"> PDF
                </label>
                <label class="radio-inline">
                    <input type="checkbox" name="rowsAttachType[]" value="body"> Body
                </label>
            </div>
        </div>
        <div class="col-md-7" id="isFilePasswordRow" style="display: none">
            <div class="row">
                <div class="col-md-12">
                    <div class="radio-list">
                        <label class="radio-inline">
                            <input type="checkbox" name="isFilePassword" value="1"> Түгжих эсэх
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
    <div class="form-group row mt20">
        <div class="col-md-12">
            <?php echo Form::textArea(array('name' => 'emailBody', 'id' => 'emailBody', 'class'=>'form-control form-control-sm', 'rows' => 15, 'value' => $this->emailBody)); ?>
        </div>
    </div>
    <div class="form-group row <?php echo $this->ignoreCheckBox == '1' ? ' hidden' : ''; ?>">
        <?php echo Form::label(array('text' => 'Илгээх горим', 'for' => 'sendMode', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <div class="radio-list">
                <label class="radio-inline<?php echo (issetParam($this->emailHide) == 'allrowsemailhide') ? ' d-none' : ''; ?>">
                    <input type="radio" name="sendMode" value="allRowsEmail"<?php echo (issetParam($this->sendModeChecked) == 'allrowsemail') ? ' checked="checked"' : ''; ?>> И-мейл тус бүр рүү сонгосон бүх мөрийг илгээх
                </label>
                <label class="radio-inline">
                    <input type="radio" name="sendMode" value="ccGroupEmail"<?php echo (issetParam($this->sendModeChecked) == 'ccgroupemail') ? ' checked="checked"' : ''; ?>> Cc дээрхи и-мейл-д хамааралтай мөрүүдийг илгээх
                </label>
                <label class="radio-inline">
                    <input type="radio" name="sendMode" value="mailToEach"<?php echo (issetParam($this->sendModeChecked) == 'mailtoeach') ? ' checked="checked"' : ''; ?>> Сонгосон мөр болгон руу и-мейл илгээх
                </label>
            </div>
        </div>
    </div>
    <?php
    if (isset($this->ecmContentAttachs) && $this->ecmContentAttachs) {
    ?>
    <div class="form-group row mt15 mb0">
        <?php echo Form::label(array('text' => $this->lang->line('File'), 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <?php
            $deleteBtn = Lang::line('delete_btn');
            $fileView = '';

            foreach ($this->ecmContentAttachs as $k => $fileName) {
                
                if ($fileName['PHYSICAL_PATH'] && file_exists($fileName['PHYSICAL_PATH'])) {
                    $btnClass = 'btn-outline-primary';

                    if ($fileName['FILE_EXTENSION'] == 'xls' || $fileName['FILE_EXTENSION'] == 'xlsx') {
                        $btnClass = 'btn-outline-success';
                    } elseif ($fileName['FILE_EXTENSION'] == 'pdf') {
                        $btnClass = 'btn-outline-danger';
                    } 

                    $fileView .= '<div class="btn-group mt3 mb3">
                        <button type="button" class="btn '.$btnClass.' btn-sm text-one-line mr0" onclick="bpFilePreview(this);" data-fileurl="'.$fileName['PHYSICAL_PATH'].'" data-filename="'.$fileName['FILE_NAME'].'" data-extension="'.$fileName['FILE_EXTENSION'].'" title="'.$fileName['FILE_NAME'].'" style="height: 24px;padding: 1px 5px;">'.$fileName['FILE_NAME'].'</button>
                        '.Form::hidden(array('name' => 'ecmContentAttachPath[]', 'value' => $fileName['PHYSICAL_PATH'])).'
                        '.Form::hidden(array('name' => 'ecmContentAttachFileName[]', 'value' => $fileName['FILE_NAME'])).'
                        <button type="button" class="btn '.$btnClass.' btn-icon btn-sm" title="'.$deleteBtn.'" onclick="ecmContentAttachFileRemove(this);" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>
                    </div>';
                }
            }

            echo $fileView;
            ?>
        </div>    
    </div>
    <?php 
    }
    if ($this->ignoreChooseFile != 'true') { 
    ?>
    <div class="form-group row mt15 mb0">
        <?php echo Form::label(array('text' => $this->lang->line('META_00090'), 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <div class="fileinput fileinput-new fileform-control-small w-100" data-provides="fileinput">
                <div class="input-group">
                    <div class="form-control uneditable-input" data-trigger="fileinput">
                        <i class="fa fa-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn default btn-file">
                        <span class="fileinput-new">Choose file</span>
                        <span class="fileinput-exists">Change file</span>
                        <input type="file" name="file1" class="send-mail-fileinput" data-valid-extension="xls, xlsx, doc, docx, pdf, ppt, pptx, jpeg, jpg, png, gif, bmp">
                    </span>
                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>    
            <div class="fileinput fileinput-new fileform-control-small w-100" data-provides="fileinput">
                <div class="input-group">
                    <div class="form-control uneditable-input" data-trigger="fileinput">
                        <i class="fa fa-file fileinput-exists"></i> <span class="fileinput-filename"></span>
                    </div>
                    <span class="input-group-addon btn default btn-file">
                        <span class="fileinput-new">Choose file</span>
                        <span class="fileinput-exists">Change file</span>
                        <input type="file" name="file2" class="send-mail-fileinput" data-valid-extension="xls, xlsx, doc, docx, pdf, ppt, pptx, jpeg, jpg, png, gif, bmp">
                    </span>
                    <a href="javascript:;" class="input-group-addon btn red fileinput-exists" data-dismiss="fileinput">Remove</a>
                </div>
            </div>    
        </div>
    </div>
    <?php 
    } 
    ?>
</div>
<?php 
echo Form::hidden(array('name' => 'ignoreList', 'value' => $this->ignoreList));  
echo Form::hidden(array('name' => 'groupEmail', 'value' => issetParam($this->groupEmail))); 
echo Form::hidden(array('name' => 'ignoreFromOwnMail', 'value' => $this->ignoreFromOwnMail)); 
echo Form::hidden(array('name' => 'emailTplCode', 'value' => issetParam($this->emailTplCode))); 
echo Form::hidden(array('name' => 'drillDownField', 'value' => issetParam($this->drillDownField))); 
echo Form::hidden(array('name' => 'ref_structure_id', 'value' => issetParam($this->refStructureId))); 
echo Form::hidden(array('name' => 'footerSumCount', 'value' => Arr::encode(issetParam($this->footerSumCount)))); 
echo Form::hidden(array('name' => 'fileAttachDrillField', 'value' => issetParam($this->fileAttachDrillField)));
echo Form::close(); 
?>

<script type="text/javascript">
var emailSelectedRow = <?php echo json_encode($this->selectedRows); ?>;

$(function () {
    
    var citynames = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: 'mddatamodel/getEmailAutoComplete',
            cache: false, 
            filter: function(list) {
                return $.map(list, function(cityname) {
                    return {name: cityname}; 
                });
            }
        }
    });
    citynames.initialize();

    $('#emailTo, #emailToCc, #emailToBcc').tagsinput({
        confirmKeys: [13, 32, 44, 59, 186, 188], 
        cancelConfirmKeysOnEmpty: true, 
        delimiter: ';', 
        freeInput: true, 
        addOnBlur: true, 
        typeaheadjs: {
            name: 'citynames', 
            displayKey: 'name', 
            valueKey: 'name',
            source: citynames.ttAdapter()
        }
    });
    $(".twitter-typeahead").css('display', 'inline');    
    
    if (typeof tinymce === 'undefined') {
        $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() { 
            initInlineTinyMceEditor();
        });
    } else {
        tinymce.remove('textarea#emailBody');
        setTimeout(function(){
            initInlineTinyMceEditor();
        }, 100);
    }

    $(document).on('focusin', function(e){
        if ($(event.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });

    function initInlineTinyMceEditor(){
        tinymce.dom.Event.domLoaded = true;
        tinymce.baseURL = URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix = '.min';

        tinymce.init({
            selector: '#emailBody',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager lineheight'
            ],
            toolbar1: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | forecolor backcolor | fontselect | fontsizeselect',
            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 16px 18px 20px 24px 36px',
            image_advtab: true,
            toolbar_items_size: 'small',
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            paste_data_images: true,
            menubar: false,
            statusbar: true,
            paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
            table_toolbar: '', 
            resize: true,
            theme_advanced_statusbar_location: '',
            elementpath: false,
            table_default_styles: {
                width: '100%', 
                height: '100%'
            }, 
            convert_urls: false,
            document_base_url: URL_APP, 
            content_css: URL_APP + 'assets/custom/css/print/tinymce_user.css'
        });
    }
      
    if (!$("link[href='assets/custom/addon/plugins/bootstrap-fileinput/bootstrap-fileinput.css']").length) {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>');
    }
    
    $.getScript("assets/custom/addon/plugins/bootstrap-fileinput/bootstrap-fileinput.js").done(function() {
        $('.send-mail-fileinput').on('change.bs.fileinput', function(e) {
            var $this = $(this);
            if ($this.val() !== '') {
                var getExtension = $this.attr("data-valid-extension");
                var removeWhiteSpace = getExtension.replace(/\s+/g, '');
                if (!$this.hasExtension(removeWhiteSpace.split(','))) {
                    alert('Та ('+getExtension+') эдгээр файлаас сонгоно уу!');
                    $this.val('');
                    return false;
                }
            }
        });
    });
    
    $('input[name="rowsAttachType[]"]').on('click', function(){
        var $this = $(this);
        var val = $this.val();

        if (val == 'pdf') {
            if ($this.is(':checked')) {
                $('#isFilePasswordRow').show();
            } else {
                $('#isFilePasswordRow').hide();
            }
        }
    });
    
    $('#emailTplCombo').on('change', function(){
        var emailTplCode = $(this).val();
        if (emailTplCode) {
            $.ajax({
                type: 'post',
                url: 'mddatamodel/getEmailTplDataByCode',
                data: {code: emailTplCode, selectedRows: emailSelectedRow},
                dataType: 'json',
                success: function(data) {
                    $('#emailSubject').val(data.SUBJECT);
                    $('input[name="emailTplCode"]').val(emailTplCode);
                    tinymce.get('emailBody').setContent(html_entity_decode(data.MESSAGE, 'ENT_QUOTES'));
                }
            });
        } else {
            $('input[name="emailTplCode"]').val('');
            tinymce.get('emailBody').setContent('');
        }
    });
    
});

function ecmContentAttachFileRemove(elem) {
    $(elem).closest('.btn-group').remove();
}
</script>