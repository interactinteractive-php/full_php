<div id="orgchart-<?php echo $this->dataViewId; ?>"></div>

<style type="text/css">
    #orgchart-<?php echo $this->dataViewId; ?> .get-text-0 {
        min-height: 150px;
        max-height: 150px;
        height: 150px;
    }
    #orgchart-<?php echo $this->dataViewId; ?> .get-text-0 div {
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
    }
    #orgchart-<?php echo $this->dataViewId; ?> .get-text-0 div:hover {
        text-decoration: underline;
        cursor: pointer;
    }
    #orgchart-<?php echo $this->dataViewId; ?> .get-text-1 {
        height: 32px;
    }
</style>

<link href="<?php echo autoVersion('assets/custom/addon/plugins/jquery-orgchart/getorgchart/getorgchart.css'); ?>" rel="stylesheet"/>
<script type="text/javascript" src="assets/custom/addon/plugins/saveSvgAsPng.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/jquery-orgchart/getorgchart/getorgchart.js"></script>

<script type="text/javascript">

    var getOrgChartSource_<?php echo $this->dataViewId; ?> = [
        <?php
        $id = $this->row['dataViewLayoutTypes']['explorer']['fields']['id'];
        $parent = $this->row['dataViewLayoutTypes']['explorer']['fields']['parent'];
        $dependencydepartmentid = 'dependencydepartmentid';

        $name1 = $this->row['dataViewLayoutTypes']['explorer']['fields']['name1'];
        $name2 = $this->row['dataViewLayoutTypes']['explorer']['fields']['name2'];

        $colorValue = $photoValue = 'return "";';
        $isColorField = $isNameRunProcess = $isNameRunDataview = $isNameRunPackage = $isNameRunWorkspace = false;
        
        if ($this->recordList) {
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['color']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['color'] != '') {

                $colorField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['color']);

                if (array_key_exists($colorField, $this->recordList[0])) {    
                    $colorValue = 'return $row[$colorField];';
                    $isColorField = true;
                }
            }

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['photo']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['photo'] != '') {

                $photoField = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['photo']);

                if (array_key_exists($photoField, $this->recordList[0])) {
                    $photoValue = 'return $row[$photoField];';
                }
            }

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['process']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['process'] != '') {

                $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['process'];
                $isNameRunProcess = true;
            }

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['dataview']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['dataview'] != '') {

                $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['dataview'];
                $isNameRunDataview = true;
            }

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['package']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['package'] != '') {

                $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['package'];
                $isNameRunPackage = true;
            }

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['workspace']) 
                && $this->row['dataViewLayoutTypes']['explorer']['fields']['workspace'] != '') {

                $processId = $this->row['dataViewLayoutTypes']['explorer']['fields']['workspace'];
                $isNameRunWorkspace = true;
            }

            foreach ($this->recordList as $row) {
                $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                echo '{id: '.$row[$id].', parentId: \''.$row[$parent].'\', name: \''.str_replace("\\", '', $row[$name1]).'\', title: \''.$row[$name2].'\', color: \''.eval($colorValue).'\', image: \''.eval($photoValue).'\', rowdata: "'.$rowJson.'"},';
            }
        }
        ?>
    ]; 
      
    $(function(){
            
        // $.when(
        //     $.getStylesheet(URL_APP+'assets/custom/addon/plugins/jquery-orgchart/getorgchart/getorgchart.css'), 
        //     $.getScript(URL_APP+'assets/custom/addon/plugins/saveSvgAsPng.js'),
        //     $.getScript(URL_APP+'assets/custom/addon/plugins/jquery-orgchart/getorgchart/getorgchart.js')
        // ).then(function () {
            
            orgChartResizer_<?php echo $this->dataViewId; ?>();
            
            if (dv_var_<?php echo $this->dataViewId; ?>.hasOwnProperty('orientation')) {
                redrawChart_<?php echo $this->dataViewId; ?>(dv_var_<?php echo $this->dataViewId; ?>['orientation']);
            } else {
                redrawChart_<?php echo $this->dataViewId; ?>(0);
            }

        // }, function () {
        //     console.log('an error occurred somewhere');
        // });

        $('#orgchart-<?php echo $this->dataViewId; ?>').on('click', '.node', function(){
            var $elem = this;
            var $this = $($elem);
            var $parent = $this.closest('.orgchart');
            $parent.find('.selected-row').removeClass('selected-row');
            $this.addClass('selected-row');
        });

        $('#orgchart-<?php echo $this->dataViewId; ?>').on('contextmenu', '.node', function(e) {
            e.preventDefault();
            var $elem = this;
            var $this = $($elem);
            var $parent = $this.closest('.orgchart');
            $parent.find('.selected-row').removeClass('selected-row');
            $this.addClass('selected-row');
        });
        
        $('#orgchart-<?php echo $this->dataViewId; ?>').on('click', '.get-orientation', function(e) {
            var $self = $(this);
            var selfHeight = $self.parent().height();
            var selfWidth = $self.parent().width();
            var selfOffset = $self.offset();
            var selfOffsetLeft = $(document).width() - selfOffset.left - selfWidth + 15;
            var dropDown = $self.parent().find('ul');
            dropDown.css({position:'fixed', top: selfOffset.top + selfHeight - 10, left: 'auto', right: selfOffsetLeft, width: '160px'});
            
            var orientations = '<li data-code="0">Top</li>';
            orientations += '<li data-code="1">Bottom</li>';
            orientations += '<li data-code="2">Right</li>';
            orientations += '<li data-code="3">Left</li>';
            orientations += '<li data-code="4">Top parent left</li>';
            
            dropDown.html(orientations);
        });
        
        $('#orgchart-<?php echo $this->dataViewId; ?>').on('click', '.getorgchart-dropdown-arrow-right li', function(e) {
            var $this = $(this);
            var orientationCode = $this.attr('data-code');
            dv_var_<?php echo $this->dataViewId; ?>['orientation'] = orientationCode;
            explorerRefresh_<?php echo $this->dataViewId; ?>(this);
        });    
        
        $('#orgchart-<?php echo $this->dataViewId; ?>').on('click', '.get-save', function(e) {
            var $el = $(this).closest('.get-org-chart');
            saveSvgAsPng($el.find('.get-oc-c svg')[0], 'OrgChart.png', {scale: 1});
            
            /*var wrapper = $el.find('.get-oc-c')[0];
            var svg = wrapper.querySelector("svg");

            if (typeof window.XMLSerializer != "undefined") {
                var svgData = (new XMLSerializer()).serializeToString(svg);
            } else if (typeof svg.xml != "undefined") {
                var svgData = svg.xml;
            }

            var canvas = document.createElement("canvas");
            var svgSize = svg.getBBox();
            canvas.width = 1000;
            canvas.height = svgSize.height;
            var ctx = canvas.getContext("2d");

            var img = document.createElement("img");
            img.setAttribute("src", "data:image/svg+xml;base64," + btoa(unescape(encodeURIComponent(svgData))) );

            img.onload = function() {
                ctx.drawImage(img, 0, 0);
                var imgsrc = canvas.toDataURL("image/png");

                var a = document.createElement("a");
                a.download = "789.png";
                a.href = imgsrc;
                a.click();
            };*/
        });
        
    });   

    function redrawChart_<?php echo $this->dataViewId; ?>(orientation) {
        getOrgChart.themes.veriCustomTheme =
        {
            size: [500, 220],
            toolbarHeight: 46,
            textPoints: [
                {
                    x: 10,
                    y: 50,
                    width: 480
                }, 
                {
                    x: 10,
                    y: 10,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 65,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 90,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 115,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 140,
                    width: 490
                }
            ],
            textPointsNoImage: [
                {
                    x: 10,
                    y: 50,
                    width: 480
                }, 
                {
                    x: 10,
                    y: 10,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 65,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 90,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 115,
                    width: 490
                }, 
                {
                    x: 10,
                    y: 140,
                    width: 490
                }
            ],
            expandCollapseBtnRadius: 20,
            box: '<path class="get-box" d="M0 0 L500 0 L500 220 L0 220 Z"/>',
            text: '<foreignObject x="[x]" y="[y]" class="get-text get-text-[index]" width="[width]"><div style="color: #fff; line-height: 1.2em;" xmlns="http://www.w3.org/1999/xhtml">[text]</div></foreignObject>',
            image: '<clipPath id="hvwr0cgxzncjbo0z7xw74"><circle cx="464" cy="35" r="63"></circle></clipPath><image xlink:href="[href]" x="400" y="-30" width="130" height="130" clip-path="url(#hvwr0cgxzncjbo0z7xw74)" preserveAspectRatio="xMidYMid slice"/>'
        };    
        
        var orgChart = new getOrgChart(document.getElementById('orgchart-<?php echo $this->dataViewId; ?>'),{
            theme: 'veriCustomTheme',
            layout: getOrgChart.MIXED_HIERARCHY_RIGHT_LINKS,
            orientation: getOrgChart.<?php echo issetDefaultVal($this->row['dataViewLayoutTypes']['explorer']['fields']['orientation'], 'RO_LEFT'); ?>,
            enableSearch: true,
            enableEdit: false,
            enableDetailsView: false,
            enablePrint: false,
            enableOrientation: true,
            enableSave: true,
            maxDepth: 100,
            expandToLevel: <?php echo isset($this->row['dataViewLayoutTypes']['explorer']['fields']['expandtolevel']) ? $this->row['dataViewLayoutTypes']['explorer']['fields']['expandtolevel'] : '1'; ?>,
            linkType: "B",
            primaryFields: ["title", "name"], 
            photoFields: ["image"],
            gridView: true, 
            dataSource: getOrgChartSource_<?php echo $this->dataViewId; ?>, 
            renderNodeEvent: renderNodeEventHandler_<?php echo $this->dataViewId; ?> 
        });   
    }

    function renderNodeEventHandler_<?php echo $this->dataViewId; ?>(sender, args) {
        var nodeItem = args.node;
        var nodeData = nodeItem.data;
        
        <?php
        if ($isColorField) {
        ?>
        if (nodeData.hasOwnProperty('color') && (nodeData.color != '' || nodeData.color != null)) {
            args.content[1] = args.content[1].replace("<path", "<path style='fill: " + nodeData.color + "; stroke: " + nodeData.color + ";'");
        }
        <?php
        }
        if ($isNameRunProcess) {
        ?>       
        args.content[2] = args.content[2].replace('class="get-text get-text-0"', 'class="get-text get-text-0" style="font-size:32px;" onclick="orgChartRunProcess(\'<?php echo $processId; ?>\', \''+nodeItem.id+'\')"');            
        args.content[3] = args.content[3].replace('class="get-text get-text-1"', 'class="get-text get-text-1" style="font-size:26px;"').replace('<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="120">', '<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="80">');
        <?php
        } elseif ($isNameRunDataview) {
        ?>       
        args.content[2] = args.content[2].replace('class="get-text get-text-0"', 'class="get-text get-text-0" style="font-size:32px;" onclick="orgChartRunDataview(\'<?php echo $processId; ?>\', \''+nodeItem.id+'\')"');            
        args.content[3] = args.content[3].replace('class="get-text get-text-1"', 'class="get-text get-text-1" style="font-size:26px;"').replace('<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="120">', '<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="80">');
        <?php        
        } elseif ($isNameRunPackage) {
        ?>       
        args.content[2] = args.content[2].replace('class="get-text get-text-0"', 'class="get-text get-text-0" style="font-size:32px;" onclick="orgChartRunPackage(\'<?php echo $processId; ?>\', \''+nodeItem.id+'\')"');            
        args.content[3] = args.content[3].replace('class="get-text get-text-1"', 'class="get-text get-text-1" style="font-size:26px;"').replace('<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="120">', '<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="80">');
        <?php        
        } elseif ($isNameRunWorkspace) {
        ?>       
        args.content[2] = args.content[2].replace('class="get-text get-text-0"', 'class="get-text get-text-0" style="font-size:32px;" onclick="orgChartRunWorkspace(\'<?php echo $processId; ?>\', \''+nodeItem.id+'\')"');            
        args.content[3] = args.content[3].replace('class="get-text get-text-1"', 'class="get-text get-text-1" style="font-size:26px;"').replace('<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="120">', '<foreignObject x="10" y="10" class="get-text get-text-1" style="font-size:24px;" width="490" height="80">');
        <?php        
        }
        ?>          
    }

    function orgChartResizer_<?php echo $this->dataViewId; ?>() {
        var $orgChartElement = $('#orgchart-<?php echo $this->dataViewId; ?>');
        var getHeight = $(window).height() - $orgChartElement.offset().top - 20;
        $orgChartElement.height(getHeight);
    }
    function orgChartRunProcess(processId, recordId) {
        _processRecordId = recordId;
        callWebServiceByMeta(processId, true, '', false, {callerType: '<?php echo $this->row['META_DATA_CODE']; ?>', isMenu: false})
    }
    function orgChartRunDataview(dvid, recordId) {
        var defaultCriteriaParams = {};
        defaultCriteriaParams.filterDepartmentId = recordId;
        var $dialogName = 'dialog-dataview-orgchar-extract';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            url: 'mdobject/dataview/'+dvid+'/0/json',
            data: {
                uriParams: JSON.stringify(defaultCriteriaParams)
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    boxed: true, 
                    message: 'Loading...'
                });
            },
            success: function (data) {
                
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: $(window).height() - 90,
                    modal: true,
                    position: {my:'top', at:'top+50'},
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert('Error');
            }
        }).done(function () {
            Core.initDVAjax($dialog);
        });        
    }
    function orgChartRunPackage(dvid, recordId) {
        var defaultCriteriaParams = {};
        defaultCriteriaParams.filterDepartmentId = recordId;
        var $dialogName = 'dialog-package-orgchar-extract';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdobject/package/' + dvid + '/json',
            data: {metaDataId: dvid},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: $(window).height() - 90,
                    modal: true,
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
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
                $dialog.dialogExtend("maximize");
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($dialog);
        });
    }
    function orgChartRunWorkspace(dvid, recordId) {
        var defaultCriteriaParams = {};
        defaultCriteriaParams.filterDepartmentId = recordId;
        var $dialogName = 'dialog-workspace-orgchar-extract';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);
        
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'mdworkspace/renderWorkSpace',
            data: { metaDataId: dvid, dmMetaDataId: '<?php echo $this->dataViewId; ?>', selectedRow: {departmentid: recordId, id: recordId} },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $("link[href='middleware/assets/theme/" + data.theme + "/css/main.css']").remove();
                $("head").append('<link rel="stylesheet" type="text/css" href="middleware/assets/theme/' + data.theme + '/css/main.css"/>');

                if (data.theme == 'theme10') {
                    $.getScript("assets/custom/addon/plugins/jquery-easypiechart/jquery.easypiechart.min.js");
                    $.getScript("assets/custom/addon/plugins/jquery.sparkline.min.js");
                }
                
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: false,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1000,
                    height: $(window).height() - 90,
                    modal: true,
                    closeOnEscape: isCloseOnEscape, 
                    close: function () {
                        $dialog.empty().dialog('close');
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
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
                $dialog.dialogExtend("maximize");
                $dialog.css('overflow-x', 'hidden');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($dialog);
        });
    }
</script>