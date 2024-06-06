<div id="permissionCriteriaMainWindow">
    <div class="caption buttons"> 
        <?php
            echo html_tag('a', 
                array(
                    'href' => 'javascript:;', 
                    'class' => 'btn btn-circle btn-secondary card-subject-btn-border mr10', 
                    'onclick'=>'backFirstContent(this);',
                    'style'=> 'padding: 3px 9px;'
                ), 
                '<i class="icon-arrow-left7"></i>', 
                true
            );
        ?>
        <span class="caption-subject font-weight-bold uppercase card-subject-blue glheader">
            <?php echo $this->title; ?>
        </span>
        <span id="permission_criteria_filter_save" class="hide">
            <button type="button" class="btn btn-sm btn-circle blue float-right" onclick="criteriaFilterSave()"><i class="fa fa fa-save"></i> Шүүлт хадгалах</button>
        </span>
    </div>
    <hr/>
    <form class="form-horizontal" method="post" id="permissionCriteria-addform">
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Data view сонгох', 'for' => 'permission_dataview_code', 'class' => 'col-form-label col-md-5')); ?>
                    <div class="col-md-5">
                        <div class="input-group double-between-input">
                           <?php echo Form::hidden(array('name' => 'permission_dataview_id', 'id' => 'permission_dataview_id'))?>
                           <?php echo Form::text(array('name' => 'permission_dataview_code', 'id' => 'permission_dataview_code', 'class' => 'form-control form-control-sm meta-autocomplete', 'placeholder'=>'кодоор хайх')); ?>
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewCustomSelectableGrid('<?php echo $this->dataViewMetacode; ?>', 'single', 'getRowDataPermissionCriteria', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn">
                                 <?php echo Form::text(array('name' => 'permission_dataview_name', 'id' => 'permission_dataview_name', 'class' => 'form-control form-control-sm', 'placeholder'=>'нэр')); ?>    
                            </span>     
                        </div>
                    </div>
                </div>
            </div>               
        </div>    
        <div class="clearfix w-100"></div>
        <div id="callDataViewTemplateId"></div>
    </form>
</div>

<script type="text/javascript">
    var permissionWindowId = "#permissionCriteriaMainWindow";

    $(function() {
        $(permissionWindowId).on("focus", '#permission_dataview_code', function(e){
            var _this = $(this);
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 7,
                delay: 100,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        type: 'post',
                        url: 'mdcommon/hardWindowAutoComplete',
                        dataType: "json",
                        data: {
                            type: 'code',
                            metaDataCode: '<?php echo $this->dataViewMetacode; ?>',
                            q: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.code,
                                    name: item.name,
                                    data: item
                                };
                            }));
                        }
                    });
                },
                focus: function() {
                    return false;
                },
                open: function() {
                    $(this).autocomplete('widget').zIndex(99999999999999);
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui) {
                    var origEvent = event;
                    var data = ui.item.data;
                    $('#permission_dataview_code', permissionWindowId).val(data.code);
                    $('#permission_dataview_name', permissionWindowId).val(data.name);
                    $('#permission_dataview_id', permissionWindowId).val(data.id);                    
                    $('#permission_criteria_filter_save', permissionWindowId).removeClass('hide');
                    callDataViewTemplate(data.id);
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };    
        });       
        
        $('#permission_dataview_code', permissionWindowId).focus();
        
    });
    
    function getRowDataPermissionCriteria(metaDataCode, chooseType, elem, rows){
        var row = rows[0];
        $('#permission_dataview_code', permissionWindowId).val(row.code);
        $('#permission_dataview_name', permissionWindowId).val(row.name);
        $('#permission_dataview_id', permissionWindowId).val(row.id);
        $('#permission_criteria_filter_save', permissionWindowId).removeClass('hide');
        callDataViewTemplate(row.id);
    }    
    
    function callDataViewTemplate(metaDataId) {
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/' + metaDataId,
            data: { permissionCriteria: '_ERP_' },
            beforeSend: function () {
                Core.blockUI({
                    target: permissionWindowId,
                    animate: true
                });
            },
            success: function (data) {
                $("#callDataViewTemplateId", permissionWindowId).empty().append("<fieldset class='collapsible'><legend style='font-size: 14px'>Dataview preview</legend>" + data + "</fieldset>");
                Core.unblockUI(permissionWindowId);
            },
            error: function () {
                alert("Error");
            }
        });
    }
    
    function criteriaFilterSave() {
        $.ajax({
            type: 'post',
            url: 'mdpermission/criteriaFilterAddForm',
            dataType: 'json',
            success: function (response) {
                var $dialogName = 'dialog-criteria-filter';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                $("#" + $dialogName).empty().html(response.html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: response.title,
                    width: 550,
                    height: "auto",
                    modal: true,
                    close: function () {
                        $("#" + $dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: response.save_btn, class: 'btn btn-sm green-meadow', click: function () {
                            $("#criteriaFilterSaveForm", "#" + $dialogName).validate({errorPlacement: function () {
                            }});
                            
                            if ($("#criteriaFilterSaveForm", "#" + $dialogName).valid()) {
                                var rvs = JSON.stringify($('#objectdatagrid-'+$('#permission_dataview_id').val()).datagrid('getSelected'));
                                    rvs = (rvs === 'null') ? '' : rvs,
                                $.ajax({
                                    type: 'post',
                                    url: 'mdpermission/criteriaFilterCreate',
                                    data: $("#criteriaFilterSaveForm", "#" + $dialogName).serialize() + '&' 
                                            + $("#default-criteria-form", permissionWindowId).serialize() + '&' 
                                            + $("#default-mandatory-criteria-form", permissionWindowId).serialize() + '&'
                                            + $('#permission_dataview_id').serialize() + '&' 
                                            + 'cardViewerFieldPath='+$("#cardViewerFieldPath", "#permissionCriteria-addform").val() + '&' 
                                            + 'cardViewerValue='+$("#cardViewerValue", "#permissionCriteria-addform").val() + '&' 
                                            + 'currentSelectedRowValues=' + rvs,
                                    dataType: "json",
                                    beforeSend: function () {
                                        Core.blockUI({
                                            target: permissionWindowId,
                                            animate: true
                                        });
                                    },
                                    success: function (res) {
                                        PNotify.removeAll();
                                        if (res.status === 'success') {
                                            new PNotify({
                                                title: res.status,
                                                text: res.message,
                                                type: res.status,
                                                sticker: false
                                            });                                            
                                            $("#" + $dialogName).dialog('close');
                                        } else {
                                            new PNotify({
                                                title: res.status,
                                                text: res.message,
                                                type: res.status,
                                                sticker: false
                                            });
                                        }
                                        Core.unblockUI(permissionWindowId);
                                    },
                                    error: function () {
                                        alert("Error");
                                    }
                                });
                                clearConsole();
                            }
                        }},
                        {text: response.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                            $("#" + $dialogName).dialog('close');
                        }}]
                });
                $("#" + $dialogName).dialog('open');
            },
            error: function () {
                alert("Error");
            }
        });
    }    

</script>