<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="card-body form m-0" id="mainRenderDiv">
    <div class="m-0 no-padding">
        <?php if (isset($this->content['CONTENT_ID'])) {
            $action = 'mdcontentui/updateContent';
        } else {
            $action = 'mdcontentui/saveContent';
        }; ?>
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'contentForm', 'action' => $action, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>

        <div class="col-md-12 m-0 no-padding">
            <div class="form-body">
                <div class="form-group row fom-row">
                    <div class="col-md-12">
                        <?php
                        echo Form::text(
                                array(
                                    'name' => 'name',
                                    'id' => 'name',
                                    'class' => 'form-control form-control-sm border-0 focus-border-grey',
                                    'required' => 'required',
                                    'value' => isset($this->content['FILE_NAME']) ? $this->content['FILE_NAME'] : '',
                                    'placeholder' => 'Нэр'
                                )
                        );

                        echo Form::hidden(
                                array(
                                    'name' => 'id',
                                    'required' => 'required',
                                    'value' => isset($this->content['CONTENT_ID']) ? $this->content['CONTENT_ID'] : ''
                                )
                        );

                        echo Form::hidden(
                                array(
                                    'name' => 'defaultPath',
                                    'required' => 'required',
                                    'value' => isset($this->content['DEFAULT_PATH']) ? $this->content['DEFAULT_PATH'] : ''
                                )
                        );

                        echo Form::hidden(
                                array(
                                    'name' => 'typeId',
                                    'required' => 'required',
                                    'value' => isset($this->content['TYPE_ID']) ? $this->content['TYPE_ID'] : ''
                                )
                        );
                        ?>
                    </div>
                </div>
                <div class="form-group row fom-row">
                    <div class="col-md-12">
                        <?php
                        echo Form::text(
                                array(
                                    'name' => 'description',
                                    'id' => 'description',
                                    'class' => 'form-control form-control-sm border-0 focus-border-grey',
                                    'required' => 'required',
                                    'value' => isset($this->content['DESCRIPTION']) ? $this->content['DESCRIPTION'] : '',
                                    'placeholder' => 'Тайлбар'
                                )
                        );
                        ?>
                    </div>
                </div>
                <!--            <div class="form-group row fom-row">
                                <div class="col-md-12">
                                    <label class="">
                <?php
                echo Form::checkbox(
                        array(
                            'name' => 'isDefault',
                            'id' => 'isDefault',
                            'value' => '1',
                        )
                );
                ?>
                                        Is default
                                    </label>
                                </div>
                            </div>-->
                        <?php if (isset($this->content['CONTENT_ID'])) { ?>
                    <div class="form-group row fom-row">
                        <div class="col-md-12">
                            <?php
                            $url = 'mdcontentui/contentHtmlRender/';
                            $url .= isset($this->content['CONTENT_ID']) ? $this->content['CONTENT_ID'] : '';
                            echo Form::text(
                                    array(
                                        'name' => 'url',
                                        'id' => 'url',
                                        'class' => 'form-control form-control-sm border-0 focus-border-grey',
                                        'value' => $url,
                                        'readonly' => 'readonly',
                                        'placeholder' => 'URL'
                                    )
                            );
                            ?>
                        </div>
                    </div>
<?php } ?>
            </div>
        </div>
        <div class="clearfix w-100"></div><hr>
        <div class="col-md-12 m-0 no-padding">
            <div class="form-body">
                <textarea name="tempEditor" id="tempEditor" class="contentHtmlEditor"><?php echo isset($this->content['HTML']) ? $this->content['HTML'] : ''; ?></textarea>
            </div>
        </div> 
<?php echo Form::close(); ?>    
    </div> 
</div> 

<script type="text/javascript">
    $(function(){
        if(typeof tinymce === 'undefined'){
            $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js', function(){
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                $.getScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function(script, textStatus){
                    initTinyMceEditor();
                });
            });
        } else {
            tinymce.remove('textarea#tempEditor');
            setTimeout(function(){
                initTinyMceEditor();
            }, 100);
        }

        $(document).on('focusin', function(e){
            if($(event.target).closest(".mce-window").length){
                e.stopImmediatePropagation();
            }
        });

        Core.initUniform($("#contentForm"));
    });

    function initTinyMceEditor(){
        tinymce.dom.Event.domLoaded=true;
        tinymce.baseURL=URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix=".min";
        tinymce.init({
            selector: '.contentHtmlEditor',
            height: '400px',
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight fullpage'
            ],
            toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen',
            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt', 
            lineheight_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0',
            image_advtab: true, 
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            paste_data_images: true,
            paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
            mentions: {
                delimiter: '#'
            },
            document_base_url: URL_APP,
            content_css: URL_APP + 'assets/custom/css/print/tinymce.css'
        });
    }

    function saveContentHtml($dialog){
        tinyMCE.triggerSave();
        Core.blockUI({
            message: 'Loading...',
            boxed: true
        });
        $("#contentForm").ajaxSubmit({
            type: 'post',
            url: '<?php echo $action; ?>',
            dataType: 'json',
            success: function(data){
                if(data.status === 'success'){
                    new PNotify({
                        title: 'Success',
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    $dialog.dialog('close');
                } else {
                    new PNotify({
                        title: 'Error',
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                }

                clearForm();
                Core.unblockUI();
            }
        });
    }

    function clearForm(){
        console.clear();
    }
</script>






