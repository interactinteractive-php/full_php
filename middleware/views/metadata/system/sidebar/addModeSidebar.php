<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px; height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00024'); ?></td>
                <td>
                    <div class="meta-folder-tags">
                        <?php
                        if ($this->folderId != '') {
                        ?>
                        <div class="meta-folder-tag">
                            <?php echo Form::hidden(array('name' => 'folderId[]', 'value' => $this->folderId)); ?> 
                            <span class="parent-folder-name"><?php echo $this->folderName; ?></span>
                            <span class="meta-folder-tag-remove" onclick="removeMetaFolderTag(this);"><i class="fa fa-times"></i></span>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                    <a href="javascript:;" class="btn btn-sm purple-plum float-left" onclick="commonFolderDataGrid('multi', '', 'chooseMetaParentFolder', this);">...</a>
                </td>
            </tr>
            <tr>
                <td style="height: 32px;" class="left-padding">Tags:</td>
                <td style="vertical-align: middle">
                    <div class="meta-folder-tags"></div>
                    <a href="javascript:;" class="btn btn-sm purple-plum float-left" onclick="metaTagSelectable(this);">...</a>
                </td>
            </tr>
            <tr>
                <td class="left-padding" style="height: 32px;"><?php echo $this->lang->line('META_00197'); ?></td>
                <td>
                    <div class="metaChoosedIcon">
                        <div class="iconpath"></div>
                        <?php echo Form::hidden(array('name' => 'metaIconId')); ?>
                    </div>
                    <a href="javascript:;" class="btn btn-sm purple-plum" onclick="metaIconChoose(this);">...</a>
                    
                    <?php echo Form::hidden(array('name' => 'metaIconName')); ?>
                    <button id="meta-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" role="iconpicker"></button>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'META_TYPE_ID',
                            'id' => 'SYS_META_TYPE_ID',
                            'data' => (new Mdmetadata())->getMetaTypeListByAddMode($this->typeIds),
                            'op_text' => 'META_TYPE_NAME',
                            'op_value' => 'META_TYPE_ID',
                            'class' => 'form-control select2',
                            'required' => 'required', 
                            'tabindex' => '3' 
                        )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div id="objectTableLinks"></div>

<script type="text/javascript">
    $(function() {
        
        $('button[role="iconpicker"]').iconpicker({
            arrowPrevIconClass: 'fa fa-arrow-left',
            arrowNextIconClass: 'fa fa-arrow-right'
        });
        $('#meta-iconpicker').on('change', function(e){ 
            if (e.icon === 'empty' || e.icon === 'fa-empty') {
                $("input[name='metaIconName']").val('');
            } else {
                $("input[name='metaIconName']").val(e.icon);
            }
        });
    
        $('#SYS_META_TYPE_ID').one('select2-focus', select2Focus).on('select2-blur', function () {
            $(this).one('select2-focus', select2Focus);
        });
        
        function select2Focus() {
            var select2 = $(this).data('select2');
            setTimeout(function() {
                if (!select2.opened()) {
                    select2.open();
                }
            }, 0);  
        }
        
        $('#SYS_META_TYPE_ID').on('change', function() {
            var metaTypeId = $(this).val();
            var $objectTableLinks = $('#objectTableLinks');
            
            if (metaTypeId === '200101010000010') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/objectBookmarkLinks',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $objectTableLinks.empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                });
            } else if (metaTypeId === '200101010000011') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/businessProcessLinks',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $objectTableLinks.empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000012') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/reportLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $objectTableLinks.empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($objectTableLinks);
                });
            } else if (metaTypeId === '200101010000013') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/dashboardLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $objectTableLinks.empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($objectTableLinks);
                });
            } else if (metaTypeId === '200101010000016') {
                
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/groupLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: $objectTableLinks,
                            animate: true
                        });
                    },
                    success: function(data) {
                        
                        $objectTableLinks.empty().append(data);
                        
                        Core.initAjax($objectTableLinks);
                        Core.unblockUI($objectTableLinks);
                        
                        $('#groupType').select2('open');
                    }
                });
                
            } else if (metaTypeId === '200101010000017') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/fieldLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000024') {
                $.ajax({
                    type: 'post',
                    url: 'mdmeta/googleMapLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000025') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/menuLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                        if (!$().iconpicker) {
                            $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                            });
                        }
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000027') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/calendarLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000028') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/donutLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax();
                });
            } else if (metaTypeId === '200101010000031') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/cardLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                        if (!$().iconpicker) {
                            $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                            });
                        }
                        if (!$().colorpicker) {
                            $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                            });
                        } 
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000032') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/diagramLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000029') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/reportTemplateLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                        if (typeof tinymce === 'undefined') {
                            $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function(script, textStatus) {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                $.cachedScript('assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js');
                            });
                        }
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000034') {
                $.ajax({
                    type: 'post',
                    url: 'mdmeta/workSpaceLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000035') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/statementLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                        if (typeof tinymce === 'undefined') {
                            $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function(script, textStatus) {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                $.cachedScript('assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js');
                            });
                        }
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000036') {
                $.ajax({
                    type: 'post',
                    url: 'mdmeta/layout',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000033') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/packageLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000040') {
                
                var $metaTabs = $('#add-meta-tabs');
                
                $metaTabs.find('ul.nav-tabs > li:eq(0)').after('<li><a aria-expanded="false" href="#metatab_6" data-toggle="tab">Proxy map</a></li>');
                $metaTabs.find('.tab-content > .tab-pane:eq(0)').after('<div class="tab-pane" id="metatab_6">'+
                '<div class="row">'+
                    '<div class="col-md-12">'+
                        '<div class="table-toolbar">'+
                            '<div class="row">'+
                                '<div class="col-md-6">'+
                                    '<div class="btn-group">'+
                                        '<button type="button" class="btn btn-xs green-meadow" onclick="proxyCommonMetaDataGrid();"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?></button>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</div>'+
                    '</div>'+
                    '<div class="row">'+
                        '<div class="col-md-12">'+
                            '<ul class="grid cs-style-2 list-view0" id="proxy-meta-sortable">'+
                            '</ul>'+
                        '</div>'+
                    '</div>'+
                '</div>');
        
                var $proxyMetaSortable = $('#proxy-meta-sortable');
                $proxyMetaSortable.sortable({
                    revert: true, 
                    placeholder: 'meta-sortable-placeholder',
                    start: function(event, ui){
                        ui.placeholder.html(ui.item.html());
                        ui.item.toggleClass("meta-sortable-highlight");
                    },
                    stop: function (event, ui){
                        ui.item.toggleClass("meta-sortable-highlight");
                    }
                });
                $proxyMetaSortable.disableSelection();
                
                $("#objectTableLinks").empty();
                
            } else if (metaTypeId === '200101010000041') {
                $.ajax({
                    type: 'post',
                    url: 'mdbpmn/bpmLink',
                    beforeSend: function() {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function(data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
                
            } else if (metaTypeId === '200101010000043') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/taskFlowLinks',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function() {
                        Core.blockUI({target: '#objectTableLinks', animate: true});
                    },
                    success: function(data) {
                        $objectTableLinks.empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function() {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else {
                $("#objectTableLinks").empty();
            }
        });
    });
</script>