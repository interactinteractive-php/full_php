<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'dashboard-mail-form', 'method' => 'post', 'enctype' => 'multipart/form-data', 'autocomplete'=>'off')); ?>
<div class="col-md-12 xs-form">
  <div class="col-md-1 no-padding">
    <button type="button" class="btn btn-sm btn-success send-mail-btn-ot sendMailBtnOt" style="height: 110px;width: 76px;">
      <i class="fa fa-send"></i> <?php echo $this->lang->line('send_btn'); ?>
    </button>    
  </div>
  <div class="col-md-11 no-padding">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('subject_to'), 'for' => 'emailTo', 'class' => 'col-form-label col-md-1',
            'required' => 'required'));
        ?>
      <div class="col-md-11">
        <?php echo Form::text(array('name' => 'emailTo', 'id' => 'emailTo', 'class' => 'form-control form-control-sm', 'required' => 'required')); ?>
          <span class="help-inline">Олон и-мэйл хаяг бичихээр бол <strong style="color: #000; font-size: 18px">,</strong> эсвэл <strong style="color: #000; font-size: 18px">;</strong> авч бичнэ үү.</span>
      </div>
    </div>
    <div class="form-group row fom-row">
          <?php echo Form::label(array('text' => 'Cc', 'for' => 'emailToCc', 'class' => 'col-form-label col-md-1')); ?>
      <div class="col-md-11">
        <?php echo Form::text(array('name' => 'emailToCc', 'id' => 'emailToCc', 'class' => 'form-control form-control-sm')); ?>
      </div>
    </div>
    <div class="form-group row fom-row">
          <?php echo Form::label(array('text' => 'Bcc', 'for' => 'emailToBcc', 'class' => 'col-form-label col-md-1')); ?>
      <div class="col-md-11">
        <?php echo Form::text(array('name' => 'emailToBcc', 'id' => 'emailToBcc', 'class' => 'form-control form-control-sm')); ?>
      </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('subjectTitle'), 'for' => 'emailSubject', 'class' => 'col-form-label col-md-1', 'required' => 'required')); ?>
      <div class="col-md-11">
        <?php echo Form::text(array('name' => 'emailSubject', 'id' => 'emailSubject', 'class' => 'form-control form-control-sm', 'required' => 'required')); ?>
      </div>
    </div>    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Явуулах төрөл', 'for' => 'emailSendType', 'class' => 'col-form-label col-md-1', 'required' => 'required')); ?>
        <div class="col-md-10" style="padding-left: 32px;">
        <?php echo Form::radioMulti(array(
                array('name' => 'emailSendType', 'id' => 'emailSendType', 'class' => 'notuniform', 'label' => 'PDF', 'value' => 'pdf'), 
                array('name' => 'emailSendType', 'id' => 'emailSendType', 'class' => 'notuniform', 'label' => 'ЗУРАГ', 'value' => 'picture')
            ), 'pdf'); ?>
      </div>
    </div>    
  </div>
  <div class="form-group row fom-row">
      <div class="col-md-12">
        <?php echo Form::textArea(array('name' => 'emailBody', 'id' => 'emailBody', 'class' => 'form-control form-control-sm', 'rows' => 18)); ?>
      </div>
    </div>
</div>
<?php
echo Form::close();
?>

<script type="text/javascript">
    $(function(){
        
        /*var citynames = new Bloodhound({
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
            delimiter: ',', 
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
        */
      
      if(typeof tinymce === 'undefined'){ 
        $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function(){
          initInlineTinyMceEditor();
        });
      } else {
        tinymce.remove('textarea#emailBody');
        setTimeout(function(){
          initInlineTinyMceEditor();
        }, 100);
      }

      $(document).on('focusin', function(e){
        if($(event.target).closest(".mce-window").length){
          e.stopImmediatePropagation();
        }
      });

      function initInlineTinyMceEditor(){
        tinymce.dom.Event.domLoaded=true;
        tinymce.baseURL=URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix='.min';

        tinymce.init({
          selector: '#emailBody',
          plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager lineheight'
          ],
          toolbar1: 'bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | forecolor backcolor | fontselect | fontsizeselect | fullscreen',
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
          document_base_url: URL_APP, 
          content_css: URL_APP+'assets/custom/css/print/tinymce_email.css'
        });
      }
    });
</script>