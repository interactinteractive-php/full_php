<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

if (isset($this->contentData['CONTENT_ID'])) {
    $action = 'mdcontentui/updateContent';
} else {
    $action = 'mdcontentui/saveContent';
}

echo Form::create(array('class' => 'form-horizontal', 'id' => 'contentForm-' . $this->contentUniqId, 'action' => $action, 'method' => 'POST', 'enctype' => 'multipart/form-data'));
?>

<div id="tempEditor-<?php echo $this->contentUniqId; ?>" class="tempEditor editable-content content-inline-mode">
    <div class="contentHtml_<?php echo $this->contentId ?>">
        <?php echo $this->html; ?>
    </div>
</div>

<?php
echo Form::hidden(
    array(
        'name' => 'name',
        'id' => 'name',
        'class' => 'form-control form-control-sm border-0 focus-border-grey',
        'required' => 'required',
        'value' => isset($this->contentData['FILE_NAME']) ? $this->contentData['FILE_NAME'] : (!is_null($this->contentHtmlName) ? $this->contentHtmlName : ''),
        'placeholder' => 'Нэр'
    )
);
echo Form::hidden(
    array(
        'name' => 'id',
        'required' => 'required',
        'value' => isset($this->contentData['CONTENT_ID']) ? $this->contentData['CONTENT_ID'] : ''
    )
);
echo Form::hidden(
    array(
        'name' => 'defaultPath',
        'required' => 'required',
        'value' => isset($this->contentData['DEFAULT_PATH']) ? $this->contentData['DEFAULT_PATH'] : ''
    )
);
echo Form::hidden(
    array(
        'name' => 'typeId',
        'required' => 'required',
        'value' => isset($this->contentData['TYPE_ID']) ? $this->contentData['TYPE_ID'] : Mdcontentui::$contentHtmlTypeId
    )
);
?>

<input type="hidden" name="contentUniqId" value="<?php echo $this->contentUniqId; ?>"/>
<?php
if (!isset($this->contentData['CONTENT_ID'])) {
?>
    <input type="hidden" name="srcDataViewId" value="<?php echo $this->srcDataViewId; ?>"/>
    <input type="hidden" name="srcRecordId" value="<?php echo $this->srcRecordId; ?>"/>
<?php
}
?>
<input type="hidden" name="moduleId" id="moduleId"/>
<input type="hidden" name="menuId" id="menuId"/>
<?php echo Form::close(); ?>    

<script type="text/javascript">
    $(function(){
        /* global contentHtml */
        $(document).bind('keydown', 'Shift+c', function(){
            var $body = $('body');
            var $contentInlineElement = $body.find('div.content-inline-mode');
            var $visibleContentInlineElement = $body.find('div.content-inline-mode:visible');

            if ($contentInlineElement.length > 0 && $contentInlineElement.is(':visible') && !$visibleContentInlineElement.hasAttr('contenteditable')){

                var contentUniqId = $visibleContentInlineElement.attr('id').replace('tempEditor-', '');

                if (typeof contentHtml === 'undefined'){
                    $.getScript(URL_APP + 'middleware/assets/js/contentui/contentHtml.js', function(){
                        contentHtml.init(contentUniqId);
                    });
                } else {
                    contentHtml.init(contentUniqId);
                }
            }
        });
        $("button.content-preview-pdf").on("click", function(){
            var $this = $(this);
            var $parent = $this.closest("div.report-preview");

            Core.blockUI({
                message: 'Exporting...',
                boxed: true
            });

            $.fileDownload(URL_APP + 'mdstatement/pdfExport', {
                httpMethod: "POST",
                data: {
                    reportName: '<?php echo isset($this->contentData['FILE_NAME']) ? $this->contentData['FILE_NAME'] : (!is_null($this->contentHtmlName) ? $this->contentHtmlName : ''); ?>',
                    htmlContent: $(".contentHtml_<?php echo $this->contentId ?>").html(),
                    orientation: 'portrait',
                    size: 'a4',
                    top: '1.5cm',
                    left: '2cm',
                    bottom: '1.5cm',
                    right: '1.5cm',
                    width: '',
                    height: ''
                }
            }).done(function(){
                Core.unblockUI();
            }).fail(function(){
                alert("File download failed!");
                Core.unblockUI();
            });
            Core.unblockUI();
        });
    });
</script>