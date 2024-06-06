<span id="editMetaMainConfigContainer">
    <div class="panel panel-default bg-inverse">
        <table class="table sheetTable<?php echo isset($this->isLocked) ? ' sheetTable-lock' : ''; ?>">
            <tbody>
                <tr>
                    <td style="width: 170px; height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00024'); ?></td>
                    <td style="vertical-align: middle">
                        <div class="meta-folder-tags">
                            <?php echo $this->folderIdsNames; ?>
                        </div>
                        <a href="javascript:;" class="btn btn-sm purple-plum float-left" onclick="commonFolderDataGrid('multi', '', 'chooseMetaParentFolder', this);">...</a>
                        <input type="hidden" name="isFolderManage" value="0"/>
                    </td>
                </tr>
                <tr>
                    <td style="height: 32px;" class="left-padding">Tags:</td>
                    <td style="vertical-align: middle">
                        <div class="meta-folder-tags">
                            <?php echo $this->tagIdsNames; ?>
                        </div>
                        <a href="javascript:;" class="btn btn-sm purple-plum float-left" onclick="metaTagSelectable(this);">...</a>
                        <input type="hidden" name="isTagsManage" value="0"/>
                    </td>
                </tr>
                <tr>
                    <td class="left-padding" style="height: 32px;"><?php echo $this->lang->line('META_00197'); ?></td>
                    <td>
                        <div class="metaChoosedIcon">
                            <div class="iconpath">
                                <?php
                                if (!empty($this->metaRow['META_ICON_ID'])) {
                                    echo '<img src="assets/core/global/img/metaicon/small/' . $this->metaRow['META_ICON_CODE'] . '">';
                                }
                                ?>
                            </div>
                            <?php echo Form::hidden(array('name' => 'metaIconId', 'value' => $this->metaRow['META_ICON_ID'])); ?>
                        </div>
                        <a href="javascript:;" class="btn btn-sm purple-plum" onclick="metaIconChoose(this);">...</a>
                        
                        <?php echo Form::hidden(array('name' => 'metaIconName', 'value' => $this->metaRow['ICON_NAME'])); ?>
                        <button id="meta-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" data-icon="<?php echo $this->metaRow['ICON_NAME']; ?>" name="name" role="iconpicker"></button>
                    </td>
                </tr>
                <tr>
                    <td class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                    <td>
                        <?php
                        if (isset($this->checkMetaData)) {
                            echo Form::select(
                                array(
                                    'id' => 'SYS_META_TYPE_ID',
                                    'data' => (new Mdmetadata())->getMetaTypeList(),
                                    'value' => $this->metaRow['META_TYPE_ID'],
                                    'op_text' => 'META_TYPE_NAME',
                                    'op_value' => 'META_TYPE_ID',
                                    'class' => 'form-control select2',
                                    'required' => 'required',
                                    'disabled' => 'disabled'
                                )
                            );
                            echo Form::hidden(array('name' => 'META_TYPE_ID', 'value' => $this->metaRow['META_TYPE_ID']));
                        } else {
                            echo Form::select(
                                array(
                                    'name' => 'META_TYPE_ID',
                                    'id' => 'SYS_META_TYPE_ID',
                                    'data' => (new Mdmetadata())->getMetaTypeList(),
                                    'value' => $this->metaRow['META_TYPE_ID'],
                                    'op_text' => 'META_TYPE_NAME',
                                    'op_value' => 'META_TYPE_ID',
                                    'class' => 'form-control select2',
                                    'required' => 'required'
                                )
                            );
                        }
                        echo Form::hidden(array('name' => 'oldMetaTypeId', 'value' => $this->metaRow['META_TYPE_ID']));
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div id="objectTableLinks"></div>
</span>

<div class="hide" id="singleMetaGroupConfig"></div>

<script type="text/javascript">
    $(function () {
        
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
        
        $('.bs-select').selectpicker({
            iconBase: 'fa',
            showIcon: false, 
            tickIcon: ''
        });
        
        $('#meta-data-status').on('change', function (e) {
            var $this = $(this);
            var $id = $this.val();
            if ($id == 1) {
                $this.selectpicker('setStyle', 'green-meadow', 'remove');
                $this.selectpicker('setStyle', 'red-sunglo', 'remove');
                $this.selectpicker('setStyle', 'purple-plum');
            } else if ($id == 2) {
                $this.selectpicker('setStyle', 'purple-plum', 'remove');
                $this.selectpicker('setStyle', 'red-sunglo', 'remove');
                $this.selectpicker('setStyle', 'green-meadow');
            } else if ($id == 3) {
                $this.selectpicker('setStyle', 'purple-plum', 'remove');
                $this.selectpicker('setStyle', 'green-meadow', 'remove');
                $this.selectpicker('setStyle', 'red-sunglo');
            }
            
            $('#metaDataStatus').attr('status-id', $id);
        });
        
        var sysMetaTypeId = $("#SYS_META_TYPE_ID").val();

        if (sysMetaTypeId === '200101010000010') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/objectBookmarkLinksEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            });
        } else if (sysMetaTypeId === '200101010000011') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/businessProcessLinksEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000012') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/reportLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000013') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/dashboardLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000016') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/groupLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
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
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000017') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/fieldLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000023') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/contentLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000024') {
            $.ajax({
                type: 'post',
                url: 'mdmeta/googleMapLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000025') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/menuLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
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
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000027') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/calendarLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000028') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/donutLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000029') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/reportTemplateEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
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
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000035') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/statementEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                    if (typeof tinymce === 'undefined') {
                        $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() {      
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                            $.cachedScript('assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js');
                        });
                    }
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000031') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/cardLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
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
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000032') {
            var checkMetaData = '<?php echo isset($this->checkMetaData) ? 'true' : 'false' ?>';
            if (checkMetaData === 'true') {
                $('#SYS_META_TYPE_ID').attr('readonly', 'readonly');
            }
            $.ajax({
                type: 'post',
                url: 'mdmetadata/diagramLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>, checkMeta: '<?php echo isset($this->checkMetaData) ? 'true' : 'false' ?>'},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                    if (!$().colorpicker) {
                        $.cachedScript('assets/custom/addon/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js').done(function() {      
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-colorpicker/css/colorpicker.css"/>');
                        });
                    } 
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '<?php echo Mdmetadata::$packageMetaTypeId; ?>') {

            $.ajax({
                type: 'post',
                url: 'mdmetadata/packageLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });

        } else if (sysMetaTypeId === '200101010000034') {
            $.ajax({
                type: 'post',
                url: 'mdmeta/workSpaceLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000036') {
        
            loadLayout(sysMetaTypeId);
            
        } else if (sysMetaTypeId === '<?php echo Mdmetadata::$proxyMetaTypeId; ?>') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/proxyEditMode',
                data: {metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
            
        } else if (sysMetaTypeId === '200101010000041') {

            $.ajax({
                type: 'post',
                url: 'mdbpmn/bpmLinkEditMode',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (data) {
                    $("#objectTableLinks").empty().append(data);
                    Core.unblockUI('#objectTableLinks');
                }
            });
        } else if (sysMetaTypeId === '200101010000042') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/dmLinkEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({
                        target: '#objectTableLinks',
                        animate: true
                    });
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } else if (sysMetaTypeId === '200101010000043') {
            $.ajax({
                type: 'post',
                url: 'mdmetadata/taskFlowLinksEditMode',
                data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                beforeSend: function () {
                    Core.blockUI({target: '#objectTableLinks', animate: true});
                },
                success: function (dataTableLinks) {
                    $("#objectTableLinks").empty().append(dataTableLinks);
                    Core.unblockUI('#objectTableLinks');
                }
            }).done(function () {
                Core.initAjax($("#objectTableLinks"));
            });
        } 

        $('#SYS_META_TYPE_ID').on("change", function () {
            var metaTypeId = $(this).val();

            if (metaTypeId === '200101010000010') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/objectBookmarkLinks',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                });
            } else if (metaTypeId === '200101010000011') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/businessProcessLinks',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000012') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/reportLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000016') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/groupLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000017') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/fieldLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000024') {
                $.ajax({
                    type: 'post',
                    url: 'mdmeta/googleMapLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000025') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/menuLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
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
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000027') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/calendarLink',
                    data: {metaTypeId: metaTypeId},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000028') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/donutLink',
                    data: {metaTypeId: metaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000029') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/donutLink',
                    data: {metaTypeId: metaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                        if (typeof tinymce === 'undefined') {
                            $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                $.cachedScript('assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js');
                            });
                        }
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000034') {
                $.ajax({
                    type: 'post',
                    url: 'mdmeta/workSpaceLinkEditMode',
                    data: {metaTypeId: metaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (metaTypeId === '200101010000035') {
                $.ajax({
                    type: 'post',
                    url: 'mdmeta/statementEditMode',
                    data: {metaTypeId: metaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                        if (typeof tinymce === 'undefined') {
                            $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function() {      
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/autocomplete.css"/>');
                                $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/tinymce/plugins/mention/rte-content.css"/>');
                                $.cachedScript('assets/custom/addon/plugins/tinymce/plugins/mention/plugin.min.js');
                            });
                        }
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
                
            } else if (metaTypeId === '200101010000036') {
            
                loadLayout(metaTypeId);
                
            } else if (metaTypeId === '200101010000033') {

                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/packageLinkEditMode',
                    data: {metaTypeId: metaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
                
            } else if (metaTypeId === '200101010000041') {

                $.ajax({
                    type: 'post',
                    url: 'mdbpmn/bpmLink',
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (data) {
                        $("#objectTableLinks").empty().append(data);
                        Core.unblockUI('#objectTableLinks');
                    }
                });
                
            } else if (sysMetaTypeId === '200101010000042') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/dmLinkEditMode',
                    data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({
                            target: '#objectTableLinks',
                            animate: true
                        });
                    },
                    success: function (dataTableLinks) {
                        $("#objectTableLinks").empty().append(dataTableLinks);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else if (sysMetaTypeId === '200101010000043') {
                $.ajax({
                    type: 'post',
                    url: 'mdmetadata/taskFlowLinksEditMode',
                    data: {metaTypeId: sysMetaTypeId, metaDataId: <?php echo $this->metaDataId; ?>},
                    beforeSend: function () {
                        Core.blockUI({target: '#objectTableLinks', animate: true});
                    },
                    success: function (dataTableLinks) {
                        $("#objectTableLinks").empty().append(dataTableLinks);
                        Core.unblockUI('#objectTableLinks');
                    }
                }).done(function () {
                    Core.initAjax($("#objectTableLinks"));
                });
            } else {
                $("#objectTableLinks").empty();
            }
        });
    });

    function loadLayout(metaTypeId) {
        $.ajax({
            type: 'post',
            url: 'mdmeta/layoutEditMode',
            data: {metaTypeId: metaTypeId, metaDataId: '<?php echo $this->metaDataId; ?>'},
            beforeSend: function () {
                Core.blockUI({
                    target: '#objectTableLinks',
                    animate: true
                });
            },
            success: function (data) {
                $("#objectTableLinks").empty().html(data);
                Core.unblockUI('#objectTableLinks');
            }
        }).done(function () {
            Core.initAjax($("#objectTableLinks"));
        });
    }
</script>