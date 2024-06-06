<div class="row mv-value-map-render-parent" id="mv-value-map-render-parent-<?php echo $this->uniqId; ?>">
    <div class="col-md-auto" style="width:16.875rem;">
        <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md" style="height: 86vh">
            
            <div class="sidebar-content">

                <div class="card mb-2 pb-2" style="border-bottom: 1px solid rgba(0,0,0,.125);">
                    <div class="card-body">
                        
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <label class="col-md-2 col-form-label text-lg-right">ID:</label>
                                    <div class="col-md-10">
                                        <?php echo issetParam($this->selectedRow[strtolower($this->standartField['idField'])]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <label class="col-md-2 col-form-label text-lg-right">Code:</label>
                                    <div class="col-md-10">
                                        <?php echo issetParam($this->selectedRow[strtolower($this->standartField['codeField'])]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <label class="col-md-2 col-form-label text-lg-right">Name:</label>
                                    <div class="col-md-10">
                                        <?php echo issetParam($this->selectedRow[strtolower($this->standartField['nameField'])]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0">
                        <ul class="nav nav-sidebar" data-nav-type="accordion">
                            <?php
                            foreach ($this->structureList as $k => $row) {
                                
                                $kpiTypeId = $row['KPI_TYPE_ID'];
                                
                                $hiddenParams = Arr::encode(array(
                                    'srcMapId'       => $row['MAP_ID'],
                                    'srcIndicatorId' => $this->mainIndicatorId, 
                                    'srcRecordId'    => $this->recordId, 
                                    'trgIndicatorId' => $row['ID'], 
                                    'typeCode'       => $this->typeCode
                                ));
                                
                                if ($kpiTypeId == 2008) {
                                    $name = '<i class="far fa-window font-size-14"></i> '.$row['STRUCTURE_NAME'];
                                    $onClick = "mvValueMapStructureRender(this, '".$row['MAP_ID']."', '".$this->mainIndicatorId."', '".$row['STRUCTURE_INDICATOR_ID']."', '".$row['ID']."', '".$this->typeCode."', '".$this->recordId."');";
                                } else {
                                    $name = '<i class="far fa-list-ol font-size-14"></i> '.$row['NAME'];
                                    $onClick = "mvValueMapGridRender(this, '".$row['MAP_ID']."', '".$this->mainIndicatorId."', '".$row['ID']."', '".$this->typeCode."', '".$this->recordId."');";
                                }
                            ?>
                            <li class="nav-item">
                                <a href="javascript:;" class="nav-link font-weight-bold <?php echo ($k == 0 ? ' active' : ''); ?>" onclick="<?php echo $onClick; ?>" data-map-id="<?php echo $row['MAP_ID']; ?>" data-trg-id="<?php echo $row['ID']; ?>" data-hidden-params="<?php echo $hiddenParams; ?>" data-selected-row="<?php echo $this->selectedRowEncode; ?>">
                                    <?php echo $name; ?>
                                </a>
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col overflow-auto pl-3 mv-value-map-render">
        
    </div>
</div>

<script type="text/javascript">
$(function() {
    $('#mv-value-map-render-parent-<?php echo $this->uniqId; ?>').find('ul.nav-sidebar > li.nav-item > a.nav-link.active').click();
    
    $('#mv-value-map-render-parent-<?php echo $this->uniqId; ?>').on('click', '.mv-value-map-render .bp-btn-save', function() {
        PNotify.removeAll();
        
        var $saveBtn = $(this);
        $saveBtn.attr('disabled', 'disabled').prepend('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
        
        var $form = $saveBtn.closest('form'), uniqId = $form.find('div[data-bp-uniq-id]').attr('data-bp-uniq-id');
        
        if (window['kpiIndicatorBeforeSave_' + uniqId]($saveBtn) && bpFormValidate($form)) {
            
            $form.ajaxSubmit({
                type: 'post',
                url: 'mdform/saveKpiDynamicDataByList',
                dataType: 'json',
                beforeSubmit: function(formData, jqForm, options) {
                    var $active = $('#mv-value-map-render-parent-<?php echo $this->uniqId; ?>').find('ul.nav-sidebar > li.nav-item > a.nav-link.active');
                    formData.push({name: 'mapHidden[params]', value: $active.attr('data-hidden-params')});
                    formData.push({name: 'mapHidden[selectedRow]', value: $active.attr('data-selected-row')});
                },
                beforeSend: function () {
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {

                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: pnotifyPosition
                    });
                    
                    if (data.status == 'success') {
                        $form.find('input[name="kpiTblId"]').val(data.rowId);
                    }

                    Core.unblockUI();
                }
            });
        }
        
        $saveBtn.removeAttr('disabled').find('i:eq(0)').remove();
    });
});  

function mvValueMapStructureRender(elem, mapId, mainIndicatorId, structureIndicatorId, trgIndicatorId, typeCode, recordId) {
    var $this = $(elem), $parent = $this.closest('.nav-sidebar'), 
        $render = $this.closest('.mv-value-map-render-parent').find('.mv-value-map-render'), 
        $childRender = $render.find('#mv-value-map-render-child-' + mainIndicatorId + '-' + structureIndicatorId);    

    $parent.find('a.nav-link.active').removeClass('active');
    $this.addClass('active');
    
    $render.find('.mv-value-map-render-child').hide();    
    
    if ($childRender.length == 0) {
        
        var renderHeader = '<div class="card-footer">'+
            '<div class="row">'+
                '<div class="col-lg-12">'+
                    '<div class="d-flex justify-content-between align-items-center">'+
                        '<h3 class="mb-0">' + $this.text() + '</h3>'+
                        '<button type="button" class="btn green-meadow btn-circle bp-btn-save ml-3"><i class="far fa-check-circle"></i> '+plang.get('save_btn')+'</button>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>';
        var selectedRow = $this.attr('data-selected-row');
        
        $.ajax({
            type: 'post',
            url: 'mdform/renderValueMapStructure',
            data: {
                mainIndicatorId: mainIndicatorId, 
                structureIndicatorId: structureIndicatorId, 
                trgIndicatorId: trgIndicatorId, 
                typeCode: typeCode, 
                recordId: recordId, 
                srcMapId: mapId, 
                selectedRow: selectedRow
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                var html = [];
                
                html.push('<div id="mv-value-map-render-child-'+mainIndicatorId+'-'+structureIndicatorId+'" class="mv-value-map-render-child">');
                    html.push('<form method="post" enctype="multipart/form-data">');
                        html.push(renderHeader);
                        html.push(dataHtml.html);
                    html.push('</form>');
                html.push('</div>');
                
                $render.append(html.join('')).promise().done(function() {
                    Core.unblockUI();
                });
            }
        });
    } else {
        $childRender.show();
    }
}
function mvValueMapGridRender(elem, mapId, mainIndicatorId, structureIndicatorId, typeCode, recordId) {
    var $this = $(elem), $parent = $this.closest('.nav-sidebar'), 
        $render = $this.closest('.mv-value-map-render-parent').find('.mv-value-map-render'), 
        $childRender = $render.find('#mv-value-map-render-child-' + mainIndicatorId + '-' + structureIndicatorId);    

    $parent.find('a.nav-link.active').removeClass('active');
    $this.addClass('active');
    
    $render.find('.mv-value-map-render-child').hide();    
    
    if ($childRender.length == 0) {
        
        var renderHeader = '<div class="card-footer">'+
            '<div class="row">'+
                '<div class="col-lg-12">'+
                    '<div class="d-flex justify-content-between align-items-center">'+
                        '<h3 class="mb-0">' + $this.text() + '</h3>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>';
        var hiddenParams = $this.attr('data-hidden-params');
        var selectedRow = $this.attr('data-selected-row');
        
        $.ajax({
            type: 'post',
            url: 'mdform/indicatorList/' + structureIndicatorId,
            data: {
                mainIndicatorId: mainIndicatorId, 
                srcMapId: mapId, 
                recordId: recordId, 
                hiddenParams: hiddenParams, 
                selectedRow: selectedRow, 
                isIgnoreFilter: 1, 
                isIgnoreTitle: 1, 
                dynamicHeight: $(window).height() - 230
            }, 
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(dataHtml) {
                var html = [];
                
                html.push('<div id="mv-value-map-render-child-'+mainIndicatorId+'-'+structureIndicatorId+'" class="mv-value-map-render-child">');
                    html.push(renderHeader);
                    html.push('<div class="col-md-12">');
                        html.push(dataHtml);
                    html.push('</div>');
                html.push('</div>');
                
                $render.append(html.join('')).promise().done(function() {
                    Core.unblockUI();
                });
            }
        });
        
    } else {
        $childRender.show();
        $(window).trigger('resize');
    }
}
</script>