<?php echo Form::hidden(array('name' => 'layoutLinkId', 'value' => $this->layoutLink['ID'])); ?>
<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#layout_link_main_tab" data-toggle="tab" class="nav-link pt0 active"><?php echo $this->lang->line('META_00008'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#layout_link_other_tab" data-toggle="tab" class="nav-link pt0"><?php echo $this->lang->line('META_00098'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="layout_link_main_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable border-bottom-grey" >
                    <tbody>
                        <tr>
                            <td style="width: 100px" class="left-padding">Layout:</td>
                            <td>
                                <button type="button" class="btn btn-sm purple-plum" onclick="viewTheme(this);">...</button>
                                <span class="ml-1"><?php echo Str::firstUpper($this->layoutLink['THEME_CODE']) ?></span>
                                <input type="hidden" id="themeCode" name="themeCode" value="<?php echo $this->layoutLink['THEME_CODE'] ?>" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table class="table sheetTable border-bottom-grey layoutLoadName">
                    <tbody><?php echo $this->layoutParamMap; ?></tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="layout_link_other_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable border-bottom-grey">
                    <tbody>  
                        <tr class="datamodel-is-treeview">
                            <td style="width: 170px" class="left-padding">
                                <label for="isHideButton">
                                    <?php echo $this->lang->line('metadata_isHideButton'); ?>:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isHideButton',
                                            'id' => 'isHideButton',
                                            'value' => '1',
                                            'saved_val' => $this->layoutLink['IS_HIDE_BUTTON']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-is-treeview">
                            <td style="width: 170px" class="left-padding">
                                <label for="useBorder">
                                    Хүрээ нуух эсэх:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'useBorder',
                                            'id' => 'useBorder',
                                            'value' => '1',
                                            'saved_val' => $this->layoutLink['USE_BORDER']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-is-treeview">
                            <td style="width: 170px" class="left-padding">
                                <label for="useBorder">
                                    DataView Criteria:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=200101010000016">
                                    <div class="input-group double-between-input">
                                        <input id="dataViewIdLayout" name="dataViewIdLayout" type="hidden" value="<?php echo $this->layoutLink['CRITERIA_DATA_VIEW_ID']; ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete ui-autocomplete-input" placeholder="<?php echo $this->lang->line('META_00068'); ?>" value="<?php echo isset($this->getMetRow) ? Arr::get($this->getMetRow, 'META_DATA_CODE') : ''; ?>" type="text" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                                        </span>     
                                        <span class="input-group-btn not-group-btn">
                                            <div class="btn-group pf-meta-manage-dropdown">
                                                <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                                <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                            </div>
                                        </span>  
                                        <span class="input-group-btn flex-col-group-btn">
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" value="<?php echo isset($this->getMetRow) ? Arr::get($this->getMetRow, 'META_DATA_NAME') : ''; ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="">
                            <td class="left-padding">Refresh timer:</td>
                            <td colspan="2">
                                <input type="text" id="refreshTimerLayout" name="refreshTimerLayout" class="form-control longInit" value="<?php echo $this->layoutLink['REFRESH_TIMER']; ?>" placeholder="<?php echo $this->lang->line('META_00189'); ?>">
                            </td>
                        </tr>                           
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="showTypeModalDiv"></div>
<style type="text/css">
.layout-single-theme:hover {
    outline: 3px solid orange;
    cursor: pointer;
}
.layout-single-theme {
    margin: 25px;
}
.layout-single-theme.selected {
    outline: 3px solid orange;
}    
.layout-single-theme.selected::after {
    clear: both;
    content: "\ee73";
    position: absolute;
    top: 22px;
    right: 39px;
    font-family: 'icomoon' !important;
    color: #f19c00;
    font-size: 26px;
}
</style>

<script type="text/javascript">
    var layoutThemes = <?php echo json_encode(array(
                                            array(
                                                'code' => 'theme1',
                                                'name' => 'Theme1'
                                            ),
                                            array(
                                                'code' => 'theme2',
                                                'name' => 'Theme2'
                                            ),
                                            array(
                                                'code' => 'theme3',
                                                'name' => 'Theme3'
                                            ),
                                            array(
                                                'code' => 'theme4',
                                                'name' => 'Theme4'
                                            ),
                                            array(
                                                'code' => 'theme5',
                                                'name' => 'Theme5'
                                            ),
                                            array(
                                                'code' => 'theme6',
                                                'name' => 'Theme6'
                                            ),
                                            array(
                                                'code' => 'theme7',
                                                'name' => 'Theme7'
                                            ),
                                            array(
                                                'code' => 'theme8',
                                                'name' => 'Theme8'
                                            ),
                                            array(
                                                'code' => 'theme9',
                                                'name' => 'Theme9'
                                            ),
                                            array(
                                                'code' => 'theme10',
                                                'name' => 'Theme10'
                                            ),
                                            array(
                                                'code' => 'theme11',
                                                'name' => 'Theme11'
                                            ),
                                            array(
                                                'code' => 'theme12',
                                                'name' => 'Theme12'
                                            ),
                                            array(
                                                'code' => 'theme13',
                                                'name' => 'Theme13'
                                            ),
                                            array(
                                                'code' => 'theme13_1',
                                                'name' => 'Theme13_1'
                                            ),
                                            array(
                                                'code' => 'theme14',
                                                'name' => 'Theme14'
                                            ),
                                            array(
                                                'code' => 'theme15',
                                                'name' => 'Theme15'
                                            ),
                                            array(
                                                'code' => 'theme19',
                                                'name' => 'Theme19'
                                            ),
                                            array(
                                                'code' => 'theme20',
                                                'name' => 'Theme20'
                                            ),
                                            array(
                                                'code' => 'theme21',
                                                'name' => 'Theme21'
                                            ),
                                            array(
                                                'code' => 'theme22',
                                                'name' => 'theme22'
                                            ),
                                            array(
                                                'code' => 'theme23',
                                                'name' => 'theme23 Extend Rows'
                                            ),
                                            array(
                                                'code' => 'theme24',
                                                'name' => 'theme24 R'
                                            ),                                            
                                            array(
                                                'code' => 'theme25',
                                                'name' => 'theme25 R'
                                            ),                                            
                                            array(
                                                'code' => 'theme26',
                                                'name' => 'theme26 R'
                                            ),   
                                            array(
                                                'code' => 'theme27',
                                                'name' => 'theme27'
                                            ),   
                                            array(
                                                'code' => 'theme28',
                                                'name' => 'theme28'
                                            ),   
                                            array(
                                                'code' => 'theme29',
                                                'name' => 'Theme29'
                                            ),
                                            array(
                                                'code' => 'theme30',
                                                'name' => 'Theme30'
                                            ),
                                            array(
                                                'code' => 'theme31',
                                                'name' => 'Theme31'
                                            ),
                                            array(
                                                'code' => 'theme32',
                                                'name' => 'Theme32'
                                            ),
                                            array(
                                                'code' => 'theme33',
                                                'name' => 'Theme33'
                                            ), 
                                            array(
                                                'code' => 'theme34',
                                                'name' => 'Theme34'
                                            ), 
                                            array(
                                                'code' => 'webpage1',
                                                'name' => 'Web Page 1 (4)'
                                            ),                  
                                            array(
                                                'code' => 'webpage2',
                                                'name' => 'Web Page 2 (6)'
                                            ),
                                            array(
                                                'code' => 'webpage3',
                                                'name' => 'Web Page 3 (8)'
                                            ), 
                                            array(
                                                'code' => 'dashboardv1',
                                                'name' => 'dashboard V1'
                                            ), 
                                            array(
                                                'code' => 'dashboardv2',
                                                'name' => 'dashboard V2'
                                            ), 
                                            array(
                                                'code' => 'dashboardv3',
                                                'name' => 'dashboard V3'
                                            ), 
                                        )) ?>;
                                            
    $(function() {
        
        $(document.body).on('click', '.layout-single-theme', function() {
            var $this = $(this), $themeCode = $("input[name='themeCode']");

            $('.layout-single-theme').removeClass('selected');
            $this.addClass('selected');

            $themeCode.val($this.attr('data-theme-code'));
            $themeCode.closest('td').find('span').text($this.find('p').text());

            $('#dialog-themeview').dialog('close');

            loadLayout();
        });
    });
    
    function loadLayout(elem){
        
        var $tbl = $('table.layoutLoadName'), $rows = $tbl.find('> tbody > tr'), prevPositionMeta = [];
        
        $rows.each(function() {
            var $this = $(this), $bpMetaDataId = $this.find('input[name="bpMetaDataId[]"]');
            if ($bpMetaDataId.val() != '') {
                prevPositionMeta.push({
                    'id': $bpMetaDataId.val(), 
                    'code': $this.find('.md-code-autocomplete').val(), 
                    'name': $this.find('.md-name-autocomplete').val(), 
                    'pos': $this.find('input[name="layoutPath[]"]').val()
                });
            }
        });
        
        $.ajax({
            type: 'post',
            url: 'mdmeta/layoutLoadName',
            data: {themeCode: $("input[name='themeCode']").val()},
            dataType: "json",
            success: function(data){

                var $obj = $(data).find('div[data-position]'), html = '', webPageBtn = '';

                $.each($obj, function() {
                    var positionId = $(this).attr('data-position'), 
                        metaId = '', metaCode = '', metaName = ''; 
                    
                    if (prevPositionMeta.length) {
                        for (var p in prevPositionMeta) {
                            if (prevPositionMeta[p]['pos'] == 'data-position-' + positionId) {
                                metaId = prevPositionMeta[p]['id'];
                                metaCode = prevPositionMeta[p]['code'];
                                metaName = prevPositionMeta[p]['name'];
                            }
                        }
                    }
                    
                    html+='<tr>';
                    html+='<td style="width: 100px" class="left-padding">Position ' +positionId +':</td>';
                    html+='<td>';
                    html+='<div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId . '|' . Mdmetadata::$cardMetaTypeId . '|' . Mdmetadata::$diagramMetaTypeId . '|' . Mdmetadata::$googleMapMetaTypeId . '|' . Mdmetadata::$calendarMetaTypeId. '|' . Mdmetadata::$packageMetaTypeId; ?>">';
                        html+='<div class="input-group double-between-input">';
                            html+='<input type="hidden" name="bpMetaDataId[]" value="'+metaId+'">';
                            html+='<input id="_displayField" value="'+metaCode+'" title="'+metaCode+'" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">';
                            html+='<span class="input-group-btn">';
                                html+='<button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>';
                            html+='</span>';
                            html+='<span class="input-group-btn not-group-btn">';
                                html+='<div class="btn-group pf-meta-manage-dropdown">';
                                    html+='<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>';
                                    html+='<ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>';
                                html+='</div>';
                            html+='</span>';
                            html+='<span class="input-group-btn flex-col-group-btn">';
                                html+='<input id="_nameField" value="'+metaName+'" title="'+metaName+'" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">';
                            html+='</span>';
                        html+='</div>';
                    html+='</div>';
                    html+='<input type="hidden" name="layoutPath[]" value="data-position-' +positionId +'">';
                    html+='<input type="hidden" name="orderNum[]" value="' + positionId + '">';
                    html+='<input type="hidden" name="layoutLinkParamMapId[]">';
                    html+='</td>';
                    html+='<td style="width: 40px; text-align: right">';
                    html+='<a href="javascript:;" class="btn btn-sm default ml0" onclick="removeLayoutLinkParamMap(this);"><i class="icon-cross2 font-size-12"></i></a>';
                    html+='</td>';
                    html+='</tr>';
                });
                
                $tbl.find('> tbody').empty().append(html);
            },
            error: function(){
                alert("Error");
            }
        });
    }
    function viewTheme(elem){
        var _row = $(elem).closest("tr");
        var _themeCode = _row.find("input[name='themeCode']");
        
        if (_themeCode.length > 0) {
            
            _themeCode = _themeCode.val();
            
            var $dialogName = 'dialog-themeview';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);
            var layoutHtml = '<div class="row">';

            for (var i = 0; i < layoutThemes.length; i++) {
                layoutHtml += '<div class="col-3"><div data-theme-code="'+layoutThemes[i]['code']+'" class="layout-single-theme'+(_themeCode == layoutThemes[i]['code'] ? ' selected' : '')+'"><p style="font-size: 15px;text-transform: capitalize;">'+layoutThemes[i]['name']+'</p>';
                layoutHtml += '<img src=\"middleware/views/layoutrender/themes/'+layoutThemes[i]['code']+'/thumb.png\" style=\"max-width:100%;\">';
                layoutHtml += '</div></div>'
            }
            
            $dialog.empty().append(layoutHtml);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Layout list',
                width: 600,
                minWidth: 600,
                height: 500,
                modal: false,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },                
                buttons: [
                    {text: plang.get('META_00033'), class: 'btn btn-sm blue-hoki', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            }).dialogExtend({
                'closable': true,
                'maximizable': true,
                'minimizable': true,
                'collapsable': true,
                'dblclick': 'maximize',
                'minimizeLocation': 'left',
                'icons': {
                    'close': 'ui-icon-circle-close',
                    'maximize': 'ui-icon-extlink',
                    'minimize': 'ui-icon-minus',
                    'collapse': 'ui-icon-triangle-1-s',
                    'restore': 'ui-icon-newwin'
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend('maximize');
            setTimeout(function() {
                $('#dialog-themeview').animate({
                    scrollTop: $('.layout-single-theme.selected').offset().top - 100
                }, 'slow');            
            }, 300);
        }
    }
    function removeLayoutLinkParamMap(elem){
        var $dialogName='dialog-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $row = $(elem).closest("tr");
        var $bpMetaDataName = $row.find('.md-code-autocomplete, .md-name-autocomplete');
        var bpMetaDataId = $row.find('input[name="bpMetaDataId[]"]');
        var layoutLinkParamMapId = $row.find('input[name="layoutLinkParamMapId[]"]');
        
        if (layoutLinkParamMapId.val().length > 0) {
            $.ajax({
                type: 'post',
                url: 'mdcommon/deleteConfirm',
                dataType: "json",
                beforeSend: function(){
                    Core.blockUI({animate: true});
                },
                success: function(data){
                    $("#" + $dialogName).empty().html(data.Html);
                    $("#" + $dialogName).dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 330,
                        height: "auto",
                        modal: true,
                        close: function(){
                            $("#" + $dialogName).empty().dialog('close');
                        },
                        buttons: [
                            {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function(){
                                $.ajax({
                                    type: 'post',
                                    url: 'mdmeta/deleteLayoutLinkParamMap',
                                    data: {layoutLinkParamMapId: layoutLinkParamMapId.val()},
                                    dataType: "json",
                                    success: function(data){
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });
                                            
                                        if (data.status === 'success') {
                                            $bpMetaDataName.val('').attr('title', '');
                                            bpMetaDataId.val('');
                                            layoutLinkParamMapId.val('');
                                            $("#" + $dialogName).dialog('close');
                                        }
                                    },
                                    error: function(){ alert("Error"); }
                                });

                            }},
                            {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function(){
                                $("#" + $dialogName).dialog('close');
                            }}
                        ]
                    });
                    $("#" + $dialogName).dialog('open');
                    Core.unblockUI();
                },
                error: function(){
                    alert("Error");
                }
            });
        } else {
            $bpMetaDataName.val('').attr('title', '');
            bpMetaDataId.val('');
            layoutLinkParamMapId.val('');
        }
    } 
</script>