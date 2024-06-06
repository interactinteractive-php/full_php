<div class="row pivot-iframe" style="margin-top: -5px; margin-bottom: -10px" id="<?php echo $this->uniqId; ?>" data-pivot-dvid="<?php echo $this->dvId; ?>">
    <?php
    if (isset($this->templateList) && $this->templateList) {
    ?>
    <div class="mt10 mb10">
        <div class="float-left text-right" style="width: 394px;">
            Загвар сонгох:
        </div>
        <div class="float-left pl5">
            <?php
            echo Form::select(array(
                'id' => 'pivotTemplateId', 
                'class' => 'form-control form-control-sm select2', 
                'data' => $this->templateList, 
                'op_value' => 'ID',
                'op_text' => 'NAME', 
                'value' => $this->pivotTemplateId, 
                'style' => 'width: 390px;'
            ));
            ?>
        </div>
        <div class="clearfix w-100"></div>
    </div>
    <?php
    }
    if (isset($this->popupSearch)) { 
    ?>
    <div class="pivot-tools-button d-none">
        <?php
        if ($this->popupSearch) {
        ?>
        <button type="button" class="btn btn-sm btn-outline bg-orange-600 text-grey-500 border-grey-300 dv-pivot-filter-btn"><?php echo $this->lang->line('filter'); ?></button>
        <?php 
        }
        
        echo $this->buttonFilterWithPlay;
        
        if (isset($this->dataViewProcessCommand['commandAddMeta'])) {
            
            foreach ($this->dataViewProcessCommand['commandAddMeta'] as $bp) {
                
                if ($bp['IS_MAIN'] == '1') {
                    $commandAddMeta = true;
        ?>
        
        <button type="button" onclick="dvPivotRunMeta_<?php echo $this->uniqId; ?>(this, '<?php echo $bp['PROCESS_META_DATA_ID']; ?>', '<?php echo $bp['META_TYPE_ID']; ?>');" class="btn btn-sm btn-outline bg-orange-600 text-grey-500 border-grey-300">
            <?php echo $this->lang->line($bp['PROCESS_NAME']); ?>
        </button>
        
        <?php
                }
            }
        }
        ?>
    </div>
    <div id="objectdatagrid-<?php echo $this->dataViewId; ?>" class="not-datagrid pivot-dataview"></div>
    <?php
    }
    ?>
    <div class="w-100"></div>
    <iframe src="<?php echo $this->iframeUrl; ?>" frameborder="0" style="width: 100%;height: <?php echo $this->windowHeight; ?>px; border: 0"></iframe>
</div>

<style type="text/css">
.pivot-tools-button .btn {
    font: 12px 'Segoe UI', Helvetica, 'Droid Sans', Tahoma, Geneva, sans-serif;
    line-height: 14px;
    height: 25px;
    border-radius: 0;
    border: 1px #c0c0c0 solid;
    margin-right: 5px;
}
.pivot-tools-button .button-list {
    display: inline-block;
}
.pivot-tools-button .dv-button-inline {
    margin-bottom: 0!important;
}
.pivot-tools-button .dv-button-inline.dv-button-inline-active {
    border-bottom: 4px #868686 solid;
}
</style>

<script type="text/javascript">
var $iframe_<?php echo $this->uniqId; ?> = $('#<?php echo $this->uniqId; ?>');
var dvButtonCriteriaTimer;

$(function() {
    
    bpBlockMessageStart('Loading...');
    
    <?php 
    if (!$this->postWindowHeight) {
    ?>
        var iframeHeight = $(window).height() - 140, $prevContent = $iframe_<?php echo $this->uniqId; ?>.prev('div');
        if ($prevContent.length) {
            iframeHeight = parseInt(iframeHeight - $prevContent.outerHeight(true));
        }
        $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').css('height', iframeHeight).attr('src', '<?php echo $this->iframeUrl; ?>&height=' + iframeHeight);
    <?php 
    }
    ?>
    
    $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').on('load', function() {
        $iframe_<?php echo $this->uniqId; ?>.find('.pivot-tools-button').removeClass('d-none');
        bpBlockMessageStop();
    });
    
    $iframe_<?php echo $this->uniqId; ?>.on('change', '#pivotTemplateId', function() {
        var templateId = $(this).val();
        if (templateId != '') {
            $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').attr('src', '<?php echo $this->iframeMainUrl; ?>&templateId=' + templateId);
        } else {
            $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').attr('src', '<?php echo $this->iframeMainUrl; ?>');
        }
        
        bpBlockMessageStart('Loading...');
    
        $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').on('load', function () {
            bpBlockMessageStop();
        });
        
        $.ajax({
            type: 'post',
            url: 'mdpivot/saveLastChangeTemplateId',
            data: {dvId: '<?php echo $this->dvId; ?>', templateId: templateId}, 
            dataType: 'json',
            success: function (data) {
                console.log(data);
            }
        });
    });
    
    $iframe_<?php echo $this->uniqId; ?>.on('click', '.dv-pivot-filter-btn', function() {
        var $dialogName = 'dvpivot-popup-form-<?php echo $this->uniqId; ?>';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);   

        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('filter'),
            width: 500, 
            height: "auto",
            modal: true,
            buttons: [
                {text: plang.get('do_filter'), class: 'btn green-meadow btn-sm', click: function () {
                    
                    PNotify.removeAll();
                    
                    var $validForm = $dialog.find('form');
                    $validForm.validate({errorPlacement: function () {}});

                    if ($validForm.valid()) { 
                        
                        $dialog.dialog('close');
                        
                        pivotFilter_<?php echo $this->uniqId; ?>($validForm, true);
                    }
                }},
                {text: plang.get('close_btn'), class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        Core.initAjax($dialog);
        
        $dialog.dialog('open');
        dvFilterDateCheckInterval($dialog); 
    });
    
    $iframe_<?php echo $this->uniqId; ?>.on('click', '.dv-button-inline', function() {
        var $this = $(this), $parent = $this.parent(), $hidden = $parent.find('input[type="hidden"]'), 
            path = $hidden.attr('data-path'), value = $this.attr('data-criteria'),
            $form = $('dvpivot-popup-form-<?php echo $this->uniqId; ?> form'), formData = '';
        
        $hidden.val(value);
        
        $parent.find('.dv-button-inline-active').removeClass('dv-button-inline-active');
        $this.addClass('dv-button-inline-active');
        
        if ($form.length) {
            formData = $form.serialize();
        }
        
        pivotFilter_<?php echo $this->uniqId; ?>(formData+'&criteriaCondition['+path+']==&param['+path+']='+value, false);
    });
    
    $iframe_<?php echo $this->uniqId; ?>.on('click', '.dv-button-criteria-play', function() {
        var $this = $(this);
        
        if ($this.hasAttr('data-mode')) {
            $this.removeAttr('data-mode');
            $this.find('i').removeClass('icon-stop2').addClass('icon-play4');
            
            clearTimeout(dvButtonCriteriaTimer);
            
        } else {
            $this.attr('data-mode', 'playing');
            $this.find('i').removeClass('icon-play4').addClass('icon-stop2');
            
            var $buttonList = $this.prev('div').find('.btn');
            
            autoPivotFilter_<?php echo $this->uniqId; ?>($buttonList, 0);
        }
    });
});    

function pivotFilter_<?php echo $this->uniqId; ?>($validForm, isForm, $buttonList, index) {
    
    if (isForm) {
        var defaultCriteriaData = $validForm.serialize();
    } else {
        var defaultCriteriaData = $validForm;
    }
    
    $.ajax({
        type: 'post',
        url: 'mdpivot/dataViewPivotView',
        data: {
            metaDataId: '<?php echo $this->dvId; ?>', 
            templateid: '<?php echo issetParam($this->pivotTemplateId); ?>', 
            isignoretemplatelist: 1, 
            /*readonly: 1,*/
            defaultCriteriaData: defaultCriteriaData, 
            windowHeight: $(window).height() - parseInt($iframe_<?php echo $this->uniqId; ?>.offset().top) + 25, 
            isIgnorePopupSearch: 1, 
            hiderowtotal: '<?php echo issetParam($this->hideRowTotal); ?>',
            hidecolumntotal: '<?php echo issetParam($this->hideColumnTotal); ?>',
            collapse: '<?php echo issetParam($this->collapse); ?>'
        }, 
        dataType: 'json',
        success: function (data) {

            if (data.status == 'success') {
                $iframe_<?php echo $this->uniqId; ?>.find('iframe:eq(0)').attr('src', data.iframeUrl);
                
                if (typeof $buttonList !== 'undefined' && typeof index !== 'undefined') {
                    
                    dvButtonCriteriaTimer = setTimeout(function() { 
                        autoPivotFilter_<?php echo $this->uniqId; ?>($buttonList, index + 1);
                    }, 10000);
                } 
                
            } else {
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            }
        }
    });
}

function autoPivotFilter_<?php echo $this->uniqId; ?>($buttonList, index) {
    var $this = $buttonList.eq(index); 
    
    if ($this.length) {
        
        var $parent = $this.parent(), $hidden = $parent.find('input[type="hidden"]'), 
            path = $hidden.attr('data-path'), value = $this.attr('data-criteria'),
            $form = $('dvpivot-popup-form-<?php echo $this->uniqId; ?> form'), formData = '', 
            count = $buttonList.length;

        $hidden.val(value);
        
        $parent.find('.dv-button-inline-active').removeClass('dv-button-inline-active');
        $this.addClass('dv-button-inline-active');

        if ($form.length) {
            formData = $form.serialize();
        }

        pivotFilter_<?php echo $this->uniqId; ?>(formData+'&criteriaCondition['+path+']==&param['+path+']='+value, false, $buttonList, index);
        
        if (count == (index + 1)) {
            
            var $parent = $buttonList.parent(), $this = $parent.next('button');
        
            $this.removeAttr('data-mode');
            $this.find('i').removeClass('icon-stop2').addClass('icon-play4');

            clearTimeout(dvButtonCriteriaTimer);
        }
        
    } else {
        
        var $parent = $buttonList.parent(), $this = $parent.next('button');
        
        $this.removeAttr('data-mode');
        $this.find('i').removeClass('icon-stop2').addClass('icon-play4');

        clearTimeout(dvButtonCriteriaTimer);
    }
}

<?php
if (isset($commandAddMeta)) {
?>
var objectdatagrid_<?php echo $this->dataViewId; ?> = $('#objectdatagrid-<?php echo $this->dataViewId; ?>');
function dvPivotRunMeta_<?php echo $this->uniqId; ?>(elem, processId, metaTypeId) {
    transferProcessAction('', '<?php echo $this->dataViewId; ?>', processId, metaTypeId, 'toolbar', elem, {callerType: '<?php echo $this->metaDataCode; ?>'}, undefined, undefined, undefined, undefined, '');
}
function explorerRefresh_<?php echo $this->dataViewId; ?>(elem, dvSearchParam, uriParams) {
    console.log('pivot refresh');
}
<?php
}
?>
</script>

<?php 
if (isset($this->popupSearch) && $this->popupSearch) { 
?>
<div id="dvpivot-popup-form-<?php echo $this->uniqId; ?>" style="display: none" data-other-dom="<?php echo $this->dvId; ?>" data-pivot-filter="<?php echo $this->uniqId; ?>">
    <?php echo $this->popupSearch; ?>
</div>
<?php
}
?>