<?php
$columnDvId = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['columnDvId']);

if ($columnDvId) {
    
    $columnDvNameField = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['columnDvNameField']);
    $map = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['map']);
    $title = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['title']);
    
    if (!$columnDvNameField) {
        echo html_tag('div', array('class' => 'alert alert-info'), 'columnDvNameField тохируулаагүй байна!'); exit;
    }
    
    if (!$map) {
        echo html_tag('div', array('class' => 'alert alert-info'), 'map тохируулаагүй байна!'); exit;
    }
    
    if (!$title) {
        echo html_tag('div', array('class' => 'alert alert-info'), 'title тохируулаагүй байна!'); exit;
    }
    
    $isIcon1 = false;
    $icon1icon = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['icon1icon']);
    $icon1field = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['icon1field']);
    
    $isIcon2 = false;
    $icon2icon = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['icon2icon']);
    $icon2field = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['icon2field']);
    
    $isIcon3 = false;
    $icon3icon = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['icon3icon']);
    $icon3field = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['icon3field']);
    
    if ($icon1icon && $icon1field) {
        $isIcon1 = true;
    }
    
    if ($icon2icon && $icon2field) {
        $isIcon2 = true;
    }
    
    if ($icon3icon && $icon3field) {
        $isIcon3 = true;
    }
    
    $columnData = (new Mddatamodel())->getDataMartDvRowsModel($columnDvId);
    
    if ($columnData) {
?>
<div class="dv-kanban-board">
    
    <?php
    parse_str($map, $mapConfigs);

    $obj = new Mdobject();
    
    $_POST['page'] = 1;
    $_POST['rows'] = 30;
    
    $columnDvCircleColorField = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['columnDvCircleColorField']);
    $avatargroup = issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['avatargroup']);
    $progressBarPercent = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['progressBarPercent']));
    $progressBarColor = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['progressBarColor']));
    
    $eventDropProcessCode = strtolower(issetParam($this->row['dataViewLayoutTypes']['explorer']['fields']['eventDropProcessCode']));
    
    $columnAddProcess = false;
    
    if (isset($this->dataViewProcessCommand['commandAddMeta']) && is_countable($this->dataViewProcessCommand['commandAddMeta'])) {
        
        $columnAddProcess = true;
        
        $commandAddMeta = $this->dataViewProcessCommand['commandAddMeta'];
        $firstMetaId = isset($commandAddMeta[0]['PROCESS_META_DATA_ID']) ? $commandAddMeta[0]['PROCESS_META_DATA_ID'] : '';
        $firstMetaTypeId = isset($commandAddMeta[0]['META_TYPE_ID']) ? $commandAddMeta[0]['META_TYPE_ID'] : '';
        $columnDvFooterButtonName = $this->lang->line(checkDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['columnDvFooterButtonName'], 'add_btn'));
    }
    
    foreach ($columnData as $columnRow) {
        
        $uriParams = array();
        
        foreach ($mapConfigs as $mapConfigKey => $mapConfigVal) {
            $uriParams[$mapConfigKey] = $columnRow[$mapConfigVal];
        }
        
        $_POST['uriParams'] = json_encode($uriParams);
        
        $childRowsResult = $obj->getDataViewRowsByCriteria(true, $this->dataViewId);
        
        $total = 0;
        $childRows = array();
        
        if ($childRowsResult['status'] == 'success') {
            $childRows = $childRowsResult['rows'];
            $total = $childRowsResult['total'];
        }
        
        $columnRowJson = htmlentities(json_encode($columnRow, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
    ?>
    <div class="dv-kanban-board-col" data-total="<?php echo $total; ?>" data-row-data="<?php echo $columnRowJson; ?>">
        <div class="mt-0 dv-kanban-board-header">
            <?php 
            if ($columnDvCircleColorField) {
                echo '<i class="fas fa-circle font-size-11 mr-1" style="color: '.$columnRow[$columnDvCircleColorField].'"></i> ';
            }
            ?>
            <span class="dv-kanban-board-header-title"><?php echo $columnRow[$columnDvNameField]; ?></span>
        </div>

        <div class="dv-kanban-board-items">
            <?php 
            if ($childRows) {
                foreach ($childRows as $childRow) {
                    $rowJson = htmlentities(json_encode($childRow, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
            ?>
            <div class="card dv-kanban-row dv-explorer-row" data-row-data="<?php echo $rowJson; ?>">
                <div class="card-body">

                    <div class="card-title">
                        <?php echo $childRow[$title]; ?>
                    </div>
                    
                    <?php
                    if ($progressBarPercent) {
                    ?>
                    <div class="progress mb-2" style="height: 3px;">
                        <div class="progress-bar" style="background-color: <?php echo checkDefaultVal($childRow[$progressBarColor], 'orange'); ?>; width: <?php echo $childRow[$progressBarPercent]; ?>%"></div>
                    </div>
                    <?php
                    }
                    ?>
                    
                    <div class="card-meta d-flex justify-content-between">
                        
                        <?php
                        if ($isIcon1 || $isIcon2 || $isIcon3) {
                        ?>
                        <div class="d-flex align-items-center">
                            <?php
                            if ($isIcon1) {
                            ?>
                            <span class="card-meta-item">
                                <i class="far <?php echo $icon1icon; ?>"></i>
                                <span><?php echo issetParam($childRow[$icon1field]); ?></span>
                            </span>
                            <?php
                            }
                            if ($isIcon2) {
                            ?>
                            <span class="card-meta-item">
                                <i class="far <?php echo $icon2icon; ?>"></i>
                                <span><?php echo issetParam($childRow[$icon2field]); ?></span>
                            </span>
                            <?php
                            }
                            if ($isIcon3) {
                            ?>
                            <span class="card-meta-item">
                                <i class="far <?php echo $icon3icon; ?>"></i>
                                <span><?php echo issetParam($childRow[$icon3field]); ?></span>
                            </span>
                            <?php
                            }
                            ?>
                        </div>
                        <?php
                        }
                        
                        if ($avatargroup && issetParam($childRow[$avatargroup])) {
                            $avatarGroups = explode(',', $childRow[$avatargroup]);
                        ?>
                        <ul class="avatars">
                            <?php
                            foreach ($avatarGroups as $avatarImgPath) {
                            ?>
                            <li>
                                <img src="<?php echo $avatarImgPath; ?>" onerror="onUserImageError(this);" class="avatar">
                            </li>
                            <?php
                            }
                            ?>
                        </ul>
                        <?php
                        }
                        ?>
                        
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div> 
        
        <?php
        if ($columnAddProcess) {
        ?>
        <div class="mt-0 mb-0 dv-kanban-board-footer">
            <button type="button" class="btn btn-link d-flex align-items-center dv-kbb-footer-btn" onclick="dvKbbFooterBtn_<?php echo $this->mid; ?>(this);">
                <i class="far fa-plus"></i> <?php echo $columnDvFooterButtonName; ?>
            </button>
        </div>
        <?php
        }
        ?>
    </div>
    <?php
    }
    ?>
    
</div>

<style type="text/css">
#object-value-list-<?php echo $this->dataViewId; ?> .explorer-table, 
#object-value-list-<?php echo $this->dataViewId; ?> .explorer-table-row, 
#object-value-list-<?php echo $this->dataViewId; ?> .explorer-table-cell {
    display: block !important;
}
.div-objectdatagrid-<?php echo $this->dataViewId; ?>.explorer-table-cell {
    background-color: transparent!important;
    border: 0!important;
} 
.dv-kanban-board {
    /*flex: 1;
    white-space: nowrap;
    overflow-x: scroll;
    display: flex;*/
    display: block;
    white-space: nowrap;
    width: 100%;
    overflow-x: auto;
    overflow-y: hidden;
    padding-top: 5px;
}
.dv-kanban-board .dv-kanban-board-col {
    /*flex: 1 0 auto;*/
    display: inline-block;
    width: 18rem;
    padding: 0 .6rem 0 .6rem;
    vertical-align: top;
    margin-bottom: 10px;
    background-color: #e6e9ec;
    border: 1px solid #eee;
    border-radius: 8px;
    /*box-shadow: 0px 6px 7px rgba(0, 0, 0, 0.1);*/
}
.dv-kanban-board .dv-kanban-board-col.dv-kanban-board-col:not(:last-child) {
    margin-right: .6rem;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-header {
    padding: .7rem 0;
    margin: 0;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-footer {
    padding: .7rem 0;
    margin: 0;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items {
    /*position: relative;*/
    min-height: 100px;
    overflow-x: hidden;
    overflow-y: auto;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-header-title {
    font-weight: 600;
    text-transform: uppercase;
}
.dv-kanban-board .dv-kanban-board-col .dv-kbb-footer-btn {
    padding: 0 5px;
    color: #444;
}
.dv-kanban-board .dv-kanban-board-col .dv-kbb-footer-btn i {
    font-size: 16px;
    margin-right: 5px;
    vertical-align: middle;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items .card {
    box-shadow: 0 0.1875rem 0.375rem rgb(33 37 41 / 5%);
    margin-bottom: 0.75rem;
    border-radius: 0.5rem;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items .card:hover {
    background-color: #f4f5f7;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items .card.selected-row {
    border: 1px #999 solid;
}
/*.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items .card:last-child {
    margin-bottom: 2px;
}*/
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items .card .card-body {
    padding: 6px 8px;
}
.dv-kanban-board .dv-kanban-board-col .dv-kanban-board-items .card-title {
    font-weight: 600;
    font-size: 13px;
    margin-bottom: .4375rem;
}
.dv-kanban-board .dv-kanban-board-col .card-meta {
    color: #A0A0A0;
}
.dv-kanban-board .dv-kanban-board-col .card-meta .card-meta-item {
    padding-right: 10px;
}
.dv-kanban-board .dv-kanban-board-col .card-meta i {
    margin-right: 0.12rem;
}
.dv-kanban-board .dv-kanban-board-col .card-meta .avatars {
    padding-left: 0;
    list-style: none;
    margin: 0;
}
.dv-kanban-board .dv-kanban-board-col .card-meta .avatars > li {
    display: inline-block;
}
.dv-kanban-board .dv-kanban-board-col .card-meta .avatars > li + li {
    margin-left: -0.75rem;
}
.dv-kanban-board .dv-kanban-board-col .card-meta .avatars .avatar {
    display: inline-block;
    width: 1.875rem;
    height: 1.875rem;
    background: #fff;
    border-radius: 50%;
    border: 2px solid #fff;
}
.dv-kanban-board .dv-kanban-board-col .card-rotate {
    -ms-transform: rotate(6deg); /* IE 9 */
    -webkit-transform: rotate(6deg); /* Chrome, Safari, Opera */
    transform: rotate(6deg);
}
.dv-kanban-board .dv-kanban-board-col .card-current { 
    border-top: 1px #999 dashed; 
    border-bottom: 1px #999 dashed; 
    border-right: 1px #999 dashed; 
    filter: alpha(opacity=30); 
    opacity: 0.3;
}
.dv-kanban-board .dv-kanban-board-col.drop-hover {
    background-color: #dcdcdc;
}
</style>

<script type="text/javascript">
$(function() {
    
    var isIgnoreWfmHistory_<?php echo $this->mid; ?> = <?php echo (issetParam($this->row['IS_IGNORE_WFM_HISTORY']) == '1' ? 'true' : 'false'); ?>;
    var dynamicHeight = $(window).height() - objectdatagrid_<?php echo $this->mid; ?>.offset().top - 150;
    
    objectdatagrid_<?php echo $this->mid; ?>.find('.dv-kanban-board-items').css({'max-height': dynamicHeight});
    
    <?php
    if ($eventDropProcessCode) {
    ?>
    objectdatagrid_<?php echo $this->mid; ?>.find('.dv-kanban-row').draggable({
        stack: '.dv-kanban-board .dv-kanban-board-items',
        revert: 'invalid',
        helper: 'clone',
        cursor: 'move',
        scroll: true,
        drag: function(event, ui) {
            ui.helper.width($(this).width()).addClass('bg-grey-gallery card-rotate').css('z-index', '9999');
            $(ui.helper.prevObject).addClass('card-current');
        },
        stop: function(event, ui) {
            ui.helper.width($(this).width());
            $(ui.helper.prevObject).removeClass('card-current').css('z-index', '');
        }
    });
    
    objectdatagrid_<?php echo $this->mid; ?>.find('.dv-kanban-board-items').droppable({
        accept: '.dv-kanban-row',
        helper: 'clone',
        over: function(){
            $(this).closest('.dv-kanban-board-col').addClass('drop-hover');
        },
        out: function(){
            $(this).closest('.dv-kanban-board-col').removeClass('drop-hover');
        },
        drop: function(event, ui) {
            
            var $this = $(this);
            var $colBody = $this.closest('.dv-kanban-board-col');
            var colRowData = JSON.parse($colBody.attr('data-row-data'));
            var $draggable = $(ui.draggable);
            var rowData = JSON.parse($draggable.attr('data-row-data'));
            
            $colBody.removeClass('drop-hover');
            
            <?php
            if ($eventDropProcessCode == 'set_row_wfm_status') {
            ?>
            
            var currStatusId = rowData.wfmstatusid;
            var nextStatusId = colRowData.id;
            
            if (currStatusId != nextStatusId) {

                $draggable.fadeOut('fast', function() {
                    $this.prepend(ui.draggable);
                    $draggable.addClass('animated bounceIn').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
                        $draggable.removeClass('animated bounceIn');
                    });
                });
                $draggable.fadeIn('fast');
                $draggable.removeClass('card-current');
                
                var nextStatusName = $colBody.find('.dv-kanban-board-header-title').html();
                
                $.ajax({
                    type: 'post',
                    url: 'mdobject/setRowWfmStatus',
                    data: {
                        metaDataId: '<?php echo $this->dataViewId; ?>', 
                        newWfmStatusid: nextStatusId, 
                        newWfmStatusName: nextStatusName, 
                        description: nextStatusName + ' төлөвт шилжүүлэв.', 
                        dataRow: rowData
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status != 'success') {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                addclass: pnotifyPosition,
                                sticker: false
                            });
                        } else {
                            rowData.wfmstatusid = nextStatusId;
                            $draggable.attr('data-row-data', JSON.stringify(rowData).replace(/&quot;/g, '\\&quot;'));
                        }
                    }
                });
            }
            <?php
            } else {
            ?>
            
            var paramData = [];
            paramData.push({
                fieldPath: 'id', 
                inputPath: 'id', 
                value: rowData.id
            }, {
                fieldPath: 'updateField', 
                inputPath: 'updateField', 
                value: colRowData.id
            });
        
            $.ajax({
                type: 'post',
                url: 'mdwebservice/execProcess', 
                data: {processCode: '<?php echo $eventDropProcessCode; ?>', paramData: paramData},
                dataType: 'json',
                async: false, 
                success: function(response) {
                    if (response.status != 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: response.status,
                            text: response.text,
                            type: response.status,
                            sticker: false, 
                            addclass: pnotifyPosition
                        });
                    } else {
                        explorerRefresh_<?php echo $this->mid; ?>(this);
                    }
                }
            });
            
            <?php
            }
            ?>
        }
    });
    <?php
    }
    ?>
    
    $.contextMenu({
        selector: '#objectdatagrid-<?php echo $this->mid; ?> .dv-kanban-row',
        events: {
            show: function(opt) {
                var $this = opt.$trigger;
                var $parent = $this.closest('.not-datagrid');
                
                $parent.find('.selected-row').removeClass('selected-row');
                $this.addClass('selected-row');
            }
        },
        build: function($trigger, e) {
            
            var rows = $trigger.data('row-data');
            var contextMenuData = {
                <?php 
                $commandContextArray = Arr::sortBy('ORDER_NUM', $this->dataViewProcessCommand['commandContext'], 'asc');
                $cmi = 1;
                foreach ($commandContextArray as $cm => $row) {

                    $contextMenuIcon = str_replace('fa-', '', $row['ICON_NAME']);

                    if (isset($row['STANDART_ACTION'])) {

                        if ($row['STANDART_ACTION'] == 'criteria') {

                            echo '"' . $cmi . '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . $contextMenuIcon . '", ';

                            if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                            }

                            echo 'callback: function(key, options) {'
                            . 'transferProcessCriteria(\'' . $this->mid . '\', \'' . $row['BATCH_NUMBER'] . '\', \'context\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'});'
                            . '}'
                            . '},';

                        } elseif ($row['STANDART_ACTION'] == 'processCriteria') {

                            echo '"' . $cmi . '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . $contextMenuIcon . '", ';

                            if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                            }

                            echo 'callback: function(key, options) {';

                            if ($row['ADVANCED_CRITERIA'] != '') {
                                echo '_dvAdvancedCriteria = "'.$row['ADVANCED_CRITERIA'].'";';
                            }

                            echo 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->mid . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'processCriteria\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                            . '}'
                            . '},';
                            
                            if (!isset($firstEditProcess)) {
                                $firstEditProcess = 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->mid . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', $this, {callerType: \''.$this->metaDataCode.'\'}, \'\');';
                            }

                        } else {

                            echo '"' . $cmi. '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . $contextMenuIcon . '", ';

                            if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                            }

                            echo 'callback: function(key, options) {'
                            . 'transferProcessAction(\'\', \'' . $this->mid . '\', \'' . $row['STANDART_ACTION'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                            . '}'
                            . '},';
                            
                            if (!isset($firstEditProcess)) {
                                $firstEditProcess = 'transferProcessAction(\'\', \'' . $this->mid . '\', \'' . $row['STANDART_ACTION'] . '\', \'' . Mdmetadata::$businessProcessMetaTypeId . '\', \'grid\', $this, {callerType: \''.$this->metaDataCode.'\'}, \'\');';
                            }
                        }

                    } else {

                        echo '"' . $cmi. '": {'
                            . 'name: "' . $this->lang->line($row['PROCESS_NAME']) . '", '
                            . 'icon: "' . $contextMenuIcon . '", ';

                            if (isset($row['CRITERIA']) && $row['CRITERIA'] != '') {
                                echo '_dvSimpleCriteria: "'.$row['CRITERIA'].'",';
                            }

                            echo 'callback: function(key, options) {'
                            . 'transferProcessAction(\'' . $row['PASSWORD_PATH'] . '\', \'' . $this->mid . '\', \'' . $row['PROCESS_META_DATA_ID'] . '\', \'' . $row['META_TYPE_ID'] . '\', \'grid\', options.$trigger, {callerType: \''.$this->metaDataCode.'\'}, \'\');'
                            . '}'
                            . '},';
                    }
                    $cmi++;
                }
                ?>
            };
            
            $.each(contextMenuData, function ($indexCn, $contextR) {
                if (typeof $contextR['_dvSimpleCriteria'] !== 'undefined' && $contextR['_dvSimpleCriteria']) {
                    var evalcriteria = $contextR['_dvSimpleCriteria'].toLowerCase();

                    if (evalcriteria.indexOf('#') > -1) {
                        var criteriaSplit = evalcriteria.split('#');
                        evalcriteria = trim(criteriaSplit[0]);
                    }

                    $.each(rows, function(index, row) {
                        if (evalcriteria.indexOf(index) > -1) {
                            row = (row === null) ? '' : row.toLowerCase();
                            var regex = new RegExp('\\b' + index + '\\b', 'g');
                            evalcriteria = evalcriteria.replace(regex, "'" + row.toString() + "'");
                        }
                    });

                    try {
                        if (!eval(evalcriteria)) {
                            ticket = false;
                            delete contextMenuData[$indexCn];
                        }
                    } catch (err) {
                        delete contextMenuData[$indexCn];
                        console.log(evalcriteria);
                    }
                }
            });
            
            <?php
            if (isset($this->dataViewWorkFlowBtn) && $this->dataViewWorkFlowBtn == true) { 
            ?>
            
            contextMenuData['sep1'] = "---------";
            
            $.ajax({
                type: 'post',
                url: 'mdobject/getWorkflowNextStatus',
                data: {metaDataId: '<?php echo $this->mid ?>', dataRow: rows},
                dataType: 'json',
                async: false,
                success: function(response) {
                    if (response.status === 'success' && response.datastatus && response.data) {
                        
                        var rowId = '', realWfmName = '', advancedCriteria = '', wfmIcon = '';
                        
                        if (typeof rows.id !== 'undefined') {
                            rowId = rows.id;
                        }
                        
                        $.each(response.data, function (i, v) {
                            
                            if (typeof v.wfmstatusname != 'undefined' && typeof v.processname != 'undefined' && v.processname != '') {
                                v.wfmstatusname = plang.get(v.processname);
                            }
                            
                            if (v.wfmstatusicon) {
                                wfmIcon = '<i class="fa '+v.wfmstatusicon+'" style="color: '+v.wfmstatuscolor+'"></i> ';
                            }
                            
                            if (typeof v.usedescriptionwindow != 'undefined' && !v.usedescriptionwindow && typeof v.wfmuseprocesswindow != 'undefined' && !v.wfmuseprocesswindow) {
                                
                                contextMenuData[v.wfmstatusid] = {
                                    name: wfmIcon + v.wfmstatusname, 
                                    isHtmlName: true,  
                                    callback: function(key, options) {
                                        
                                        var $el = $('<span />', {text: v.wfmstatusname});
                                        
                                        if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                            $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                        }
                            
                                        changeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->mid ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname, '', '', '');
                                    }
                                };
                                
                            } else {
                                if (typeof v.wfmstatusname != 'undefined' && v.wfmstatusname != '' && (v.wfmstatusprocessid == '' || v.wfmstatusprocessid == 'null' || v.wfmstatusprocessid == null)) {
                                    
                                    if (v.wfmisneedsign == '1') {
                                        
                                        contextMenuData[v.wfmstatusid] = {
                                            name: wfmIcon + v.wfmstatusname + ' <i class="fa fa-key"></i>', 
                                            isHtmlName: true,  
                                            callback: function(key, options) {
                                                
                                                var $el = $('<span />', {text: v.wfmstatusname});
                                                $el.attr('id', v.wfmstatusid);
                                                
                                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                    $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                }
                                        
                                                beforeSignChangeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->mid ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname);
                                            }
                                        };
                                
                                    } else if (v.wfmisneedsign == '2') {
                                        
                                        contextMenuData[v.wfmstatusid] = {
                                            name: wfmIcon + v.wfmstatusname +' <i class="fa fa-key"></i>', 
                                            isHtmlName: true,  
                                            callback: function(key, options) {
                                                
                                                var $el = $('<span />', {text: v.wfmstatusname});
                                                $el.attr('id', v.wfmstatusid);
                                                
                                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                    $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                }
                                                
                                                beforeHardSignChangeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->mid ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname);
                                            }
                                        };
                                        
                                    } else {
                                    
                                        contextMenuData[v.wfmstatusid] = {
                                            name: wfmIcon + v.wfmstatusname, 
                                            isHtmlName: true,  
                                            callback: function(key, options) {
                                                
                                                var $el = $('<span />', {text: v.wfmstatusname});
                                                
                                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                    $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                }
                                                
                                                changeWfmStatusId($el, v.wfmstatusid, '<?php echo $this->mid ?>', '<?php echo $this->refStructureId ?>', v.wfmstatuscolor, v.wfmstatusname);
                                            }
                                        };
                                        
                                    }
                                } else if (v.wfmstatusprocessid != '' && v.wfmstatusprocessid != 'null' && v.wfmstatusprocessid != null) {
                                
                                    var wfmStatusCode = ('wfmstatuscode' in Object(v)) ? v.wfmstatuscode : ''; 
                                    var metaTypeId = ('metatypeid' in Object(v)) ? v.metatypeid : '200101010000011';
                                    
                                    if (v.wfmisneedsign == '1') {
                                    
                                        contextMenuData[v.wfmstatusid] = {
                                            name: wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i>', 
                                            isHtmlName: true,  
                                            callback: function(key, options) {
                                                
                                                var $el = options.$trigger;
                                                
                                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                    $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                }
                                                
                                                transferProcessAction('signProcess', '<?php echo $this->mid ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode}, 'dataViewId=<?php echo $this->mid ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                            }
                                        };
                                        
                                    } else if (v.wfmisneedsign == '2') {
                                    
                                        contextMenuData[v.wfmstatusid] = {
                                            name: wfmIcon+v.wfmstatusname+' <i class="fa fa-key"></i>', 
                                            isHtmlName: true,  
                                            callback: function(key, options) {
                                                
                                                var $el = options.$trigger;
                                                
                                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                    $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                }
                                                
                                                transferProcessAction('hardSignProcess', '<?php echo $this->mid ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode ?>', isWorkFlow: true, wfmStatusId: +v.wfmstatusid, wfmStatusCode: wfmStatusCode}, 'dataViewId=<?php echo $this->mid ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                            }
                                        };
                                        
                                    } else {
                                    
                                        contextMenuData[v.wfmstatusid] = {
                                            name: wfmIcon + v.wfmstatusname, 
                                            isHtmlName: true,  
                                            callback: function(key, options) {
                                                
                                                var $el = options.$trigger;
                                                
                                                if (typeof v.advancedCriteria !== "undefined" && v.advancedCriteria !== null) {
                                                    $el.attr('data-advanced-criteria', v.advancedCriteria.replace(/\"/g, ''));
                                                }
                                                
                                                transferProcessAction('', '<?php echo $this->mid ?>', v.wfmstatusprocessid, metaTypeId, 'toolbar', $el, {callerType: '<?php echo $this->metaDataCode ?>', isWorkFlow: true, wfmStatusId: v.wfmstatusid, wfmStatusCode: wfmStatusCode}, 'dataViewId=<?php echo $this->mid ?>&refStructureId=<?php echo $this->refStructureId; ?>&statusId='+v.wfmstatusid+'&statusName='+v.wfmstatusname+'&statusColor='+v.wfmstatuscolor+'&rowId='+rowId);
                                            }
                                        };
                                        
                                    }
                                }    
                            }
                            
                        });
                    }
                }
            });
            
            if (!isIgnoreWfmHistory_<?php echo $this->mid; ?>) {
                
                contextMenuData['wfmHistory'] = {
                    name: plang.getDefault('wfm_log_history', 'Өөрчлөлтийн түүх харах'), 
                    isHtmlName: true,  
                    callback: function(key, options) {
                        seeWfmStatusForm(this, '<?php echo $this->mid ?>');
                    }
                };
            }
            <?php
            }
            ?>
            
            var options =  {
                callback: function (key, opt) {
                    eval(key);
                },
                items: contextMenuData
            };
            
            return options;            
        }
    });    
    
    objectdatagrid_<?php echo $this->mid; ?>.on('click', '.dv-kanban-row', function(){
        var $this = $(this), $parent = $this.closest('.dv-kanban-board');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
    }); 
    
    <?php
    if (isset($firstEditProcess)) {
    ?>
    objectdatagrid_<?php echo $this->mid; ?>.on('dblclick', '.dv-kanban-row', function(){
        var $this = $(this), $parent = $this.closest('.dv-kanban-board');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
        
        <?php echo $firstEditProcess; ?>
    });  
    <?php
    }
    ?>
    
});

<?php
if ($columnAddProcess) {
?>
function dvKbbFooterBtn_<?php echo $this->mid; ?>(elem) {
    
    var $this = $(elem), $parent = $this.closest('.dv-kanban-board-col'), 
        colRowData = $parent.data('row-data'), postParams = {};

    <?php 
    foreach ($mapConfigs as $mapConfigKey => $mapConfigVal) {
        echo 'postParams.'.$mapConfigKey.' = colRowData.'.$mapConfigVal.'; ';
    }
    ?>
                
    _processAddonParam['addonJsonParam'] = JSON.stringify(postParams);
    transferProcessAction('', '<?php echo $this->mid; ?>', '<?php echo $firstMetaId; ?>', '<?php echo $firstMetaTypeId; ?>', 'toolbar', elem, {callerType: '<?php echo $this->metaDataCode; ?>'}, undefined, undefined, undefined, undefined, '');
}
<?php
}
?>
</script>

<?php
    }
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), 'columnDvId тохируулаагүй байна!');
}
?>