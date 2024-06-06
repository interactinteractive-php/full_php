/* global tinymce, Core */

var contentHtml=function(){
    //<editor-fold defaultstate="collapsed" desc="variables">
    var contentUniqId,
            $contentForm;
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="events">
    var initEvent=function(){
        $contentForm=$("#contentForm-" + contentUniqId);

        if($contentForm.closest('div').find('contenthtml').length > 0){
            $contentForm.find('input#name').val($contentForm.closest('div').find('contenthtml').data('title'));
            $contentForm.find('input#menuId').val($contentForm.closest('div').find('contenthtml').data('menu-id'));
            $contentForm.find('input#moduleId').val($contentForm.closest('div').find('contenthtml').data('module-id'));
        }

        initTinymce();

        $("#dialog-contentui").click(function(){
            tinymce.execCommand('mceFocus', false, 'tempEditor-' + contentUniqId);
        });
    };

    var initTinymce=function(){
        if(typeof tinymce === 'undefined'){
            $.cachedScript(URL_APP + 'assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() { 
                $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css');
                $.getStylesheet(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css');
                $.getStylesheet(URL_APP + 'middleware/assets/css/contentui/contentHtml.css');
                $.cachedScript(URL_APP + 'assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js').done(function(){
                    initInlineTinyMceEditor();
                });
            });
        } else {
            tinymce.remove('#tempEditor-' + contentUniqId);
            initInlineTinyMceEditor();
        }
    };

    var initInlineTinyMceEditor=function(){
        $('html > head').find('tinymcestyle#' + contentUniqId).remove();
        $('html > head').append('<tinymcestyle id="' + contentUniqId + '"><style>' + $('#tempEditor-' + contentUniqId).find('style').html() +
                '</style></tinymcestyle>');
        tinymce.dom.Event.domLoaded=true;
        tinymce.baseURL=URL_APP + 'assets/custom/addon/plugins/tinymce';
        tinymce.suffix=".min";

        tinymce.init({
            selector: '#tempEditor-' + contentUniqId,
            inline: true,
            plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight fullpage'
            ],
            toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen | saveContentHtmlBtn',
            fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 16px 18px 20px 24px 36px',
            image_advtab: true,
            toolbar_items_size: 'small',
            force_br_newlines: true,
            force_p_newlines: false,
            forced_root_block: '',
            paste_data_images: true,
            paste_word_valid_elements: "b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font",
            mentions: {
                delimiter: '#'
            },
            document_base_url: URL_APP,
            inline_styles: true,
            setup: function(editor){
                editor.addButton('saveContentHtmlBtn', {
                    text: 'Хадгалах',
                    onclick: function(){
                        saveContentHtml();
                    }
                });
                editor.on('keydown', function(evt) {    
                    if (evt.keyCode == 9) {
                        editor.execCommand('mceInsertContent', false, '&emsp;&emsp;');
                        evt.preventDefault();
                        return false;
                    }
                });
            }
        });
    };

    var saveContentHtml=function(){
        tinymce.triggerSave();
        var data=$contentForm.serialize();
        Core.blockUI({
            message: 'Loading...',
            boxed: true
        });
        $.ajax({
            url: $contentForm.attr('action'),
            type: "POST",
            data: data,
            dataType: "JSON",
            success: function(data){
                if(typeof data.status !== "undefined"){
                    executeAfterSaveEvent();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });

                    if(data.status === 'success' && typeof data.dataViewId !== 'undefined'){
                        dataViewReload(data.dataViewId);
                    }
                }
            },
            error: function(jqXHR, exception){
                Core.unblockUI();
            }
        }).complete(function(){
            Core.unblockUI();
        });
    };

    var executeAfterSaveEvent=function(){
        var $inlineMceTiny=$('.mce-tinymce-inline');
        $contentForm.find('#tempEditor-' + contentUniqId).removeClass('mce-edit-focus');
        $inlineMceTiny.hide();
        document.activeElement.blur();

        if($contentForm.closest('div').find('contenthtml').length > 0){
            location.reload();
        }
    };
    //</editor-fold>

    return {
        init: function(id){
            contentUniqId=id;
            initEvent();
        }
    };
}();