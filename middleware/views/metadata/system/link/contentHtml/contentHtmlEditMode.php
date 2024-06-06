<div class="panel panel-default bg-inverse">
  <table class="table sheetTable">
    <tbody>
      <tr>
        <td style="width: 170px" class="left-padding">Контент : </td>
        <td colspan="2">
          <a href="javascript:;" class="btn btn-sm purple-plum mr0" onclick="openContentHtmlPanel();">...</a>
          <div id="dialog-setContentHtml" style="display: none"></div>
          <textarea id="contentHtml" name="contentHtml" style="display: none"></textarea>
          <?php
            echo Form::text(
                    array(
                        'name' => 'defaultPath',
                        'id' => 'defaultPath',
                        'class' => 'form-control hidden',
                        'value' => $this->defaultPath,                        
                    )
            );
            ?>
          
        </td>
      </tr>
      <tr>
        <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00145'); ?> : </td>
        <td colspan="2">
            <?php
                echo Form::select(
                    array(
                        'name' => 'typeId',
                        'id' => 'typeId',
                        'required' => 'required',
                        'data' => array(
                            0 => array(
                                'id' => (int) $this->defaultContentTypeId, 
                                'name' => 'Загвар'
                            )
                        ),
                        'op_value' => 'id',
                        'op_text' => 'name',
                        'class' => 'form-control select2', 
                        'value' => (int) $this->contentHtml['TYPE_ID']
                    )
                );
            ?>
        </td>
      </tr>
      <tr>
        <td style="width: 170px" class="left-padding">URL : </td>
        <td colspan="2">
            <?php
                echo Form::text(
                    array(
                        'class' => 'form-control',
                        'value' => 'mdcontentui/contentHtmlRender/'.$this->metaDataId,                        
                        'readOnly' => 'readOnly'
                    )
                );
            ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<script type="text/javascript">
  $(function() {
      
  });
  
  function openContentHtmlPanel() {      
        var outputMetaDataId = $("select#outputMetaDataId").val();
        var $dialogName = 'dialog-setContentHtml';
        
        if ($("#" + $dialogName).children().length > 0) {
            $("#" + $dialogName).dialog({
                appendTo: "form#editMetaSystemForm",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'HTML EDITOR',
                width: '100%',
                minWidth: '100%',
                height: "auto",
                modal: false,
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                        saveContentHtml();
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                        $("#" + $dialogName).dialog('close');
                    }},
                    {text: "<?php echo $this->lang->line('META_00002'); ?>", class: 'btn btn-sm red', click: function () {
                        tinymce.remove('textarea#tempEditor');
                        $("#" + $dialogName).empty().dialog('close');
                    }}
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
            $("#" + $dialogName).dialog('open');
            $("#" + $dialogName).dialogExtend("maximize");
        } else {
            var metaDataId = '<?php echo $this->metaDataId; ?>';
            $.ajax({
                type: 'post',
                url: 'mdcontentui/contentHtmlPopup',
                data: {'metaDataId' : metaDataId},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $("#" + $dialogName).empty().html(data.Html);
                    $("#" + $dialogName).dialog({
                        appendTo: "form#editMetaSystemForm",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: '100%',
                        minWidth: '100%',
                        height: "auto",
                        modal: false,
                        open: function(){
                            initTinyMceEditor();
                        }, 
                        close: function () {
                            tinymce.remove('textarea');
                        },
                        buttons: [
                            {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                                tinymce.triggerSave();
                                saveContentHtml();
                                $("#" + $dialogName).dialog('close');
                            }},
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }}
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
                    $("#" + $dialogName).dialog('open');
                    $("#" + $dialogName).dialogExtend("maximize");
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initAjax($("#" + $dialogName));
            });
        }    
  }
  
  function saveContentHtml() {
    var reportValue = tinymce.get('tempEditor').getContent();
    $('#contentHtml').val(reportValue);
  }
  
  function initTinyMceEditor() {
    tinymce.dom.Event.domLoaded = true;
    tinymce.baseURL = URL_APP+'assets/custom/addon/plugins/tinymce';
    tinymce.suffix = ".min";
    tinymce.init({
        selector: 'textarea#tempEditor',
        height: '400px',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight'
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
        content_css: URL_APP+'assets/custom/css/print/tinymce.css'
    });
  }
</script>