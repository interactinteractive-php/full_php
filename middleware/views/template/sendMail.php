<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'report-mail-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Хэнд', 'for' => 'emailTo', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailTo', 'value' => $this->emailTo, 'id' => 'emailTo', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Cc', 'for' => 'emailToCc', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailToCc', 'id' => 'emailToCc', 'class'=>'form-control form-control-sm')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Bcc', 'for' => 'emailToBcc', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailToBcc', 'id' => 'emailToBcc', 'class'=>'form-control form-control-sm')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Гарчиг', 'for' => 'emailSubject', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailSubject', 'value' => $this->emailSubject, 'id' => 'emailSubject', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <div class="col-md-12">
            <?php echo Form::textArea(array('name' => 'emailBody', 'id' => 'emailBody', 'class'=>'form-control form-control-sm', 'rows' => 15, 'required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <div class="col-md-12">
            <div class="checkbox-list">
                <label class="checkbox-inline">
                    <input type="checkbox" name="isSendPdf" value="1" checked="checked"> Pdf
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="isSendExcel" value="1"> Excel
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="isSendWord" value="1"> Word
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="isSendSelectedRows" value="1"> Жагсаалт
                </label>                
            </div>
        </div>
    </div>
    <div class="form-group row fom-row mt15 mb0">
        <?php echo Form::label(array('text' => 'Хавсралт', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <div class="fileinput fileinput-new fileform-control-small" data-provides="fileinput">
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
            <div class="fileinput fileinput-new fileform-control-small" data-provides="fileinput">
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
</div>
<?php echo Form::hidden(array('name' => 'emailFileName', 'id' => 'emailFileName', 'value' => $this->emailFileName)); ?>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(document).ready(function(){
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
                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight'
            ],
            toolbar1: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            toolbar2: 'styleselect | forecolor backcolor | fontselect | fontsizeselect',
            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 16px 18px 20px 24px 36px',
            image_advtab: true,
            toolbar_items_size: 'small',
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            paste_data_images: true,
            menubar: false,
            statusbar: false,
            paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
            mentions: {
                delimiter: '#'
            },
            document_base_url: URL
        });
    }
    
    if (!$("link[href='assets/custom/addon/plugins/bootstrap-fileinput/bootstrap-fileinput.css']").length){
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-fileinput/bootstrap-fileinput.css"/>');
    }
    $.getScript("assets/custom/addon/plugins/bootstrap-fileinput/bootstrap-fileinput.js").done(function() {
        $('.send-mail-fileinput').on('change.bs.fileinput', function(e) {
            var _this = $(this);
            if (_this.val() !== '') {
                var getExtension = _this.attr("data-valid-extension");
                var removeWhiteSpace = getExtension.replace(/\s+/g, '');
                if (!_this.hasExtension(removeWhiteSpace.split(','))) {
                    alert('Та ('+getExtension+') эдгээр файлаас сонгоно уу!');
                    _this.val('');
                    return false;
                }
            }
        });
    });
});
</script>