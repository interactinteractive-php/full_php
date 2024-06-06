<style type="text/css">
  .singleRowTmp {
    position: relative;
    display: inline-block;
  }
  .singleRowTmp .removeRowBtn {
    color: #F3565D;
    font-size: 12px;
    border-radius: 50%;
    border: 0px;
    padding: 2px 6px;
    margin-right: 5px;
    position: absolute;
    top: 0;
    right: 0;
    z-index: 98;
    display: hide;
  }
  .disabledControl {
    color: #adadad;
  }
  .singleRowTmp .removeRowBtn:focus {
    outline: 0;
  }
  .bp-theme-mode .form-control.error {
    border: 1px solid #A94442 !important;
  }
</style>

<?php
$ws = new Mdwebservice();
$processsMainContentClassBegin = '';
$processsMainContentClassEnd = '';
$processsDialogContentClassBegin = '';
$processsDialogContentClassEnd = '';
$dialogProcessLeftBanner = '';
$mainProcessLeftBanner = '';
$isBanner = false;

if ($this->isDialog == false) {
    $mainProcessBtnBar = '<div class="meta-toolbar">';
    
    if (Config::getFromCache('CONFIG_MULTI_TAB')) {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', ($this->dmMetaDataId ? true : false)
            );
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    } else {
        if ($this->isHeaderName) {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border bp-btn-back',
                'onclick' => 'backFormMeta();'
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= ' <span class="font-weight-bold text-uppercase card-subject-blue">' . $this->lang->line('business_process') . ' - </span>';
            $mainProcessBtnBar .= '<span class="font-weight-bold text-uppercase text-gray2">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        } else {
            $mainProcessBtnBar .= html_tag('a', array(
                'href' => 'javascript:;',
                'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10',
                'onclick' => 'backFirstContent(this);',
                'data-dm-id' => $this->dmMetaDataId
                ), '<i class="icon-arrow-left7"></i>', true
            );
            $mainProcessBtnBar .= '<span class="text-uppercase">' . $this->lang->line($this->methodRow['META_DATA_NAME']) . '</span>';
        }
    }
    
    $reportPrint = '';
    if ($this->isPrint) {
        $reportPrint = '<button type="button" class="btn btn-sm btn-circle green ml5 '.(($this->isEditMode == true) ? '' : 'disabled').'" id="printReportProcess" onclick="processPrintPreview(this, \'' . $this->methodId . '\',  \'' . (($this->isEditMode == true) ? $this->sourceId : '') . '\', \'' . (isset($this->getProcessId) ? $this->getProcessId : '') . '\');"><i class="fa fa-print"></i> ' . $this->lang->line('printTemplate') . '</button>';
    }

    $mainProcessBtnBar .= '<div class="float-right">
            ' . Form::button(
            array(
                'class' => 'btn btn-info btn-circle btn-sm mr-1 bp-btn-help',
                'value' => $this->lang->line('menu_system_guide'),
                'onclick' => "redirectHelpContent(this, '".$this->helpContentId."', '".$this->methodId."', 'meta_process');"
            ), ($this->helpContentId ? true : false)
            ) . html_tag('button', array(
                    'type' => 'button', 
                    'class' => 'btn btn-sm btn-circle btn-success mr5 bp-btn-saveadd',
                    'onclick' => 'runBusinessProcess(this, \''.$this->dmMetaDataId.'\', \''.$this->uniqId.'\', '.json_encode($this->isEditMode).', \'saveadd\');', 
                    'data-dm-id' => $this->dmMetaDataId 
                ), 
                '<i class="fa fa-save"></i> ' . $this->runMode, 
                (!$this->isEditMode) ? (($this->runMode) ? true : false) : false  
            ) . html_tag('button', array(
                    'type' => 'button', 
                    'class' => 'btn btn-sm btn-circle hide btn-success mr5 bp-btn-saveedit',
                    'onclick' => 'runAutoEditBusinessProcess(this, \''.$this->dmMetaDataId.'\', \''.$this->uniqId.'\', '.json_encode($this->isEditMode).');', 
                    'data-dm-id' => $this->dmMetaDataId 
                ), 
                '<i class="fa fa-pencil"></i> ' . $this->lang->line('save_btn_edit')
            ) . html_tag('button', array(
                    'type' => 'button', 
                    'class' => 'btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save',
                    'onclick' => 'runBusinessProcess(this, \''.$this->dmMetaDataId.'\', \''.$this->uniqId.'\', '.json_encode($this->isEditMode).');',
                    'data-dm-id' => $this->dmMetaDataId,
                    'data-uniq-id' => $this->uniqId,
                ), 
                '<i class="fa fa-save"></i> ' . $this->processActionBtn   
            ) . Form::button(
                array(
                    'class' => 'btn btn-sm btn-circle purple-plum ml5 bp-btn-print',
                    'value' => '<i class="fa fa-download"></i> ' . $this->lang->line('print_view_btn'),
                    'onclick' => 'printProcess(this);'
                ), isset($this->isPrintView) ? $this->isPrintView : false
            ) . $reportPrint .
            '
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <div class="hide mt10" id="boot-fileinput-error-wrap"></div>
    <div class="clearfix w-100"></div>';

    $mainProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);
    if ($mainProcessLeftBanner != '') {
        $processsMainContentClassBegin = '<div class="processs-main-content">';
        $processsMainContentClassEnd = '</div>';
        $isBanner = true;
    }
} else {
    $mainProcessBtnBar = '';

    $dialogProcessLeftBanner = $ws->showBanner($this->methodId, 'left', $this->isBanner);
    $mainProcessLeftBanner = '';
    if ($dialogProcessLeftBanner != '') {
        $processsDialogContentClassBegin = '<div class="processs-main-content">';
        $processsDialogContentClassEnd = '</div>';
        $isBanner = true;
    }
}
?>

<div class="xs-form bp-banner-container bp-template-mode bp-theme-mode  " id="bp-window-<?php echo $this->methodId; ?>" data-meta-type="process" data-process-id="<?php echo $this->methodId; ?>" data-bp-uniq-id="<?php echo $this->uniqId; ?>">
    <form id="wsForm" class="" method="post" enctype="multipart/form-data">
      
        <?php 
        if (isset($this->selectedRowData) && isset($this->newStatusParams) && $this->newStatusParams) {
            $this->selectedRowsData = $this->selectedRowData;

            if (isset($this->selectedRowData[0])) {
                if (is_array($this->selectedRowData[0]))
                    $this->selectedRowData = $this->selectedRowData[0];
                else
                    $this->selectedRowsData = array($this->selectedRowsData);
            } else {
                $this->selectedRowsData = array($this->selectedRowsData);
            }
            $arrayToStrParam = Arr::encode($this->selectedRowsData);
        }
        
        echo $mainProcessBtnBar;
        echo $this->bpTab['tabStart'];
        echo $this->themeContent; 
        echo $this->bpTab['tabEnd'];
        ?>

        <div id="bprocessCoreParam">
            <?php 
            echo Form::hidden(array('name' => 'methodId', 'value' => $this->methodId));    
            echo Form::hidden(array('name' => 'processSubType', 'value' => $this->processSubType));     
            echo Form::hidden(array('name' => 'create', 'value' => ($this->processActionType == 'insert' ? '1' : '0'))); 
            echo Form::hidden(array('name' => 'responseType', 'value' => $this->responseType)); 
            echo Form::hidden(array('name' => 'wfmStatusParams', 'value' => isset($this->newStatusParams) ? $this->newStatusParams : ''));
            echo Form::hidden(array('name' => 'wfmStringRowParams', 'value' => isset($arrayToStrParam) ? $arrayToStrParam : ''));
            echo Form::hidden(array('id' => 'openParams', 'value' => $this->openParams)); 
            echo Form::hidden(array('name' => 'isSystemProcess', 'value' => $this->isSystemProcess)); 
            echo Form::hidden(array('id' => 'saveAddEventInput')); 
            echo Form::hidden(array('name' => 'windowSessionId', 'value' => $this->uniqId));
            ?>
        </div> 

    </form>
</div>
<?php
$isDtlTbl = isset($this->isDtlTbl) ? $this->isDtlTbl : true;
require getBasePath() . 'middleware/views/webservice/sub/script/main.php';
?>
<script>
    $(function () {        
        $('.removeThemeRowBtn').click(function () {
            deleteRowsOneRow(this);

        });
        
        var foundin=$("*:contains('{{pos'), *:contains('{{section')")
                        .filter(function(){
                          return $(this).
                                  children().length === 0;
                        });
        foundin.addClass('hidden');
        foundin.parents('.pos-parent').addClass('hidden');
        
        var $singleRow = $('.singleRowTmp');
        $singleRow.find('.removeRowBtn').click(function () {
            var $targetBtn = $(this),
                    $parentTarget  = $targetBtn.parents('.singleRowTmp');
                    
            $parentTarget.find('.tmpAddedRowState').remove();
            
            if(!$targetBtn.hasClass('isRemove')){                
                $parentTarget.append('<input type="hidden" class="tmpAddedRowState" name="param[' + 
                        $parentTarget.find('input[name="paramPath"]').data('parampath') + '.rowState][' + $parentTarget.data('rowindex') + '][]" value="REMOVED"/>');
                $targetBtn.addClass('isRemove');
                $targetBtn.html('<i class="fa fa-undo"></i>');
                $targetBtn.css('color', '#279ade');
                $parentTarget.find('.form-control, .select2-chosen').addClass('disabledControl');
                $parentTarget.find('div:eq(0)').css('pointer-events', 'none');
            }else{
                $targetBtn.removeClass('isRemove');
                $targetBtn.html('<i class="fa fa-times"></i>');
                $targetBtn.css('color', '#F3565D');
                $parentTarget.find('.form-control, .select2-chosen').removeClass('disabledControl');
                $parentTarget.find('div:eq(0)').css('pointer-events', 'visible');
            }            
        });
        
        
        $singleRow.hover(function(){
           $(this).find('.removeRowBtn').show();
        }, function(){
          $('.removeRowBtn').hide();
        }
        );
        
        if ($(".boot-file-input-wrap-theme", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            $.each($(".boot-file-input-wrap-theme", bp_window_<?php echo $this->methodId; ?>), function(){
                var bootFileInput = $(this);
                var infile = bootFileInput.find("input[type='file']")
                       , fileprev = '';
                var infilePath = bootFileInput.parent().find("input[name='editfile_param[picture]']").val(); 
                var defaultHeight = '120';

                if(typeof bootFileInput.attr('defaultHeight')!=="undefined"){
                   defaultHeight = bootFileInput.attr('defaultHeight');
                }
                
                if(typeof infile.attr('data-valid-extension') !== "undefined"){
                    var getExtension = infile.attr('data-valid-extension').replace(/\s+/g, '');
                    getExtension = getExtension.split(',');
                    if (typeof infilePath !== 'undefined') {
                        var ext = ["jpg", "jpeg", "png", "gif"];
                        if (ext.indexOf(infilePath.split('.').pop().toLowerCase()) !== -1)
                            fileprev = '<img src="' + infilePath + '" style="height: 100%" class="file-preview-image" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);">';
                        else
                            fileprev = '<img src="assets/core/global/img/user.png" style="height: '+defaultHeight+'px" class="file-preview-image" alt="Default photo">';
                    } else {
                        fileprev = '<img src="assets/core/global/img/user.png" style="height: '+defaultHeight+'px" class="file-preview-image" alt="Default photo">';
                    }
                    
                    infile.fileinput({
                        showCaption: false,
                        showUpload: false,
                        browseClass: "btn btn-xs btn-primary",
                        removeClass: "btn btn-xs",
                        removeLabel: "",
                        defaultPreviewContent: '<img src="assets/core/global/img/user.png" style="height: '+defaultHeight+'px" class="file-preview-image" alt="Default photo">',
                        previewFileIcon: '<i class="fa fa-file-o fa-2x text-success"></i>',
                        allowedFileExtensions: getExtension,
                        elErrorContainer: '#boot-fileinput-error-wrap',
                        msgErrorClass: 'alert alert-block alert-danger',
                        previewFileIconSettings: {
                            'docx': '<i class="fa fa-file-word-o fa-2x text-success"></i>',
                            'doc': '<i class="fa fa-file-word-o fa-2x text-success"></i>',
                            'xlsx': '<i class="fa fa-file-excel-o fa-2x text-success"></i>',
                            'pptx': '<i class="fa fa-file-powerpoint-o fa-2x text-success"></i>',
                            'pdf': '<i class="fa fa-file-pdf-o fa-2x text-success"></i>',
                            'zip': '<i class="fa fa-file-archive-o fa-2x text-success"></i>'
                        },
                        previewSettings: {
         //                   image: {width: "auto", height: "120px"},
                            text: {width: "120px", height: "120px"},
                            other: {width: "120px", height: "120px"}
                        },
                        initialPreview: [
                            fileprev
                        ]
                    });
                }
            });
       }
       
       if ($("#bpAddonTabArea", bp_window_<?php echo $this->methodId; ?>).length > 0) {
            var $bpWindow = bp_window_<?php echo $this->methodId; ?>,
                    $bpAddonTabArea = $("#bpAddonTabArea", bp_window_<?php echo $this->methodId; ?>);
            $bpAddonTabArea.find('ul li').find('a[href*="#bp_main_tab_' + $bpWindow.data('bp-uniq-id') + '"]').parent().remove();
            $bpAddonTabArea.find('#bp_main_tab_' + $bpWindow.data('bp-uniq-id')).remove();            
            $bpAddonTabArea.find('ul li:eq(0) a').trigger('click');
            if ($bpAddonTabArea.find('ul li').length === 1) {
                $bpAddonTabArea.find('ul li').hide();
            }
       }
       
       $('#dialog-businessprocess-'+<?php echo $this->methodId; ?>).css('overflow', 'hidden');

       $('.theme-data-area .theme-row:last').find('.select2').trigger("select2-opening", [true]);
    });

    function themeEdit(panelId) {
        var cloneItem = "";
        var $targetPanel = $("#" + panelId);
        var $target = $targetPanel.find(".theme-row:last");        
        $target.find('select').select2("destroy").end();
        cloneItem = $target.clone();

        cloneItem.addClass('tmpThemeRow');
        cloneItem.find('.removeThemeRowBtn').remove();
        cloneItem.append('<a class="btn btn-circle btn-icon-only btn-secondary removeThemeRowBtn" href="javascript: ;" style=\"float: right;padding: 2px;\"><i class="icon-trash"></i></a>');
        $targetPanel.find('.theme-data-area').append(cloneItem);
        $targetPanel.find('.theme-data-area').append('<div class="clearfix w-100"></div>');

        commonThemeEdit($target, $targetPanel);
        
        Core.scrollTo($targetPanel.find(".theme-row:last"));
    }
    
    function themeEditBox(panelId){
        var cloneItem="";
        var $targetPanel = $("#" + panelId);
        var $target=$targetPanel.find(".theme-row:last");
        $target.find('select').select2("destroy").end();
        cloneItem=$target.clone();
        cloneItem.find('.removeThemeRowBtn').remove();
        cloneItem.find('.theme-content').append('<a class="btn btn-circle btn-icon-only btn-secondary removeThemeRowBtn" href="javascript: ;" style=\"float: right;padding: 2px;\"><i class="icon-trash"></i></a>');
        $targetPanel.find('.theme-data-area').append('<div class="singleRowTmp"><div class="theme-row tmpThemeRow">' + cloneItem.html() + '</div></div>');

        commonThemeEdit($target, $targetPanel);
    }
    
    function selectedRowsBpAddRowForTheme(rows, panelId){
      var $targetThemeDataArea = $("#" + panelId).find('.theme-data-area');
      
      $.each(rows, function(key, value){
        themeEditBox(panelId);
        $.each($targetThemeDataArea.find('.theme-row:last .sestion-positions'), function(index, position){
          var $targetPosition=$(position);
          var $targetPostionControl=$targetPosition.find($('*[data-metadataid="' + $targetPosition.attr('id') + '"]'));
          var controlValue=($targetPosition.data('valuefield') === '') ? $targetPosition.data('metadatacode') : $targetPosition.data('valuefield');

          if($targetPostionControl.is('select')){
              $targetPostionControl.select2('val', value[controlValue]);
          } else {
            $targetPostionControl.val(value[controlValue]);
          }
        });
        
        if($targetThemeDataArea.find('.theme-row.hidden').length > 0) {
            $targetThemeDataArea.append('<input type="hidden" name="param['+$("#" + panelId).find("#sectionParamPath").data('parampath')+'.mainRowCount][' + (key + 1) + ']">');
        } else {
            $targetThemeDataArea.append('<input type="hidden" name="param['+$("#" + panelId).find("#sectionParamPath").data('parampath')+'.mainRowCount][]">');
        }
      });
      
      $targetThemeDataArea.find('.theme-row.hidden').next('input[type="hidden"]').remove();
      $targetThemeDataArea.find('.theme-row.hidden').remove();
      $("#bp-window-<?php echo $this->methodId; ?>").find('.hiddenMainRowCount').remove();
    }
    
    function commonThemeEdit($target, $targetPanel) {
        // Бүх select - үүдийг select2 болгох
        $target.find("select").select2();


        // datepicker set format
        $targetPanel.find(".dateInit").each(function(){
          $(this).inputmask("y-m-d");
          $(this).datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
          });

        });

        $targetPanel.find(".theme-row:last").find("select").each(function(){
          $(this).val('');
          var tmpName=$(this).attr('name');
          var newTempName=increaseIndex(tmpName);
          $(this).attr('name', newTempName);
          $(this).attr('id', newTempName);

        });

        $targetPanel.find(".theme-row:last").find("input").each(function(){
          $(this).val('');
          var tmpName=$(this).attr('name');
          var newTempName=increaseIndex(tmpName);
          $(this).attr('name', newTempName);
          $(this).attr('id', newTempName);
        });

        $targetPanel.find(".theme-row:last").find("select").select2();

        $targetPanel.find('.removeThemeRowBtn').click(function(){
          $(this).parents('.tmpThemeRow').remove();
        });        
    }

    function increaseIndex(text) {
        if(typeof text != 'undefined') {
            var firstPosition = text.indexOf("[", text.indexOf("[") + 1);
            var firstElement = text.substring(0, firstPosition + 1);
            var lastElementWithNumber = text.substring(firstPosition + 1);
            var lastPosition = firstElement.length + lastElementWithNumber.indexOf("]");
            var lastElement = text.substring(lastPosition);
            var number = parseInt(text.substring(firstPosition + 1, lastPosition));
            number = number + 1;
            
            return firstElement + (!isNaN(number) ? number : '') + lastElement;
        } else {
            return null;
        }
    }

    function deleteRowsOneRow(elem) {
        $(this).parents('.theme-row').remove();
    }

</script>


