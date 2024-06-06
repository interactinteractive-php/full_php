<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'report-mail-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Хэнд', 'for' => 'emailTo', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailTo', 'value' => '', 'id' => 'emailTo', 'class'=>'form-control form-control-sm', 'placeholder' => 'mail1@mail.com;mail2@mail.com')); ?>
            <?php
                $selectHtml = '<select multiple name="emailToArr[]" class="form-control form-control-sm select2 mt3" placeholder="Имэйл сонгох">';
                foreach ($this->mailList as $row) {
                    if ($row['email'])
                        $selectHtml .= '<option value="'.$row['email'].'">'.$row['email'].'</option>';
                }
                $selectHtml .= '</select>';
                echo $selectHtml;
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Cc', 'for' => 'emailToCc', 'class' => 'col-form-label col-md-1')); ?>
        <div class="col-md-11">
            <?php 
            echo Form::text(array('name' => 'emailToCc', 'id' => 'emailToCc', 'class'=>'form-control form-control-sm', 'placeholder' => 'mail1@mail.com;mail2@mail.com')); 
            echo str_replace('emailToArr[]', 'emailToCcArr[]', $selectHtml);
            ?>
        </div>
    </div>    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Гарчиг', 'for' => 'emailSubject', 'class' => 'col-form-label col-md-1', 'required'=>'required')); ?>
        <div class="col-md-11">
            <?php echo Form::text(array('name' => 'emailSubject', 'value' => '', 'id' => 'emailSubject', 'class'=>'form-control form-control-sm', 'required'=>'required')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <div class="col-md-12">
            <?php echo Form::textArea(array('name' => 'emailBody', 'id' => 'emailBody', 'class'=>'form-control form-control-sm', 'rows' => 15, 'required'=>'required')); ?>
        </div>
    </div>
</div>
<?php echo Form::hidden(array('name' => 'emailFile', 'id' => 'emailFile', 'value' => '')); ?>
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
    
});
</script>