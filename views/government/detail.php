<?php try { ?>
    <div class="government government_<?php echo $this->uniqId ?>"> 
        <div class="col-9">
            <div class="card">
                <div class="header-elements-md-inline">
                    <div class="d-flex align-items-center w-100">
                        <div class="badge general_number badge-primary d-flex flex-column" style='<?php echo (in_array('TAGNAME_015', $this->tagArr)) ? 'width: 144px;' : '' ?> <?php echo (isset($this->mainData['statuscolor']) && $this->mainData['statuscolor']) ? 'background: ' . $this->mainData['statuscolor'] . ' !important;' : 'background: #fff; color: #000; border: 1px solid; border-color: grey;'; ?>'>
                            <!--'statuscolor'-->
                            <?php
                            if (isset($this->tagArr) && $this->tagArr) { 
                                if (!in_array('TAGNAME_015', $this->tagArr)) { ?>
                                    <span class="type text-uppercase"><?php echo (isset($this->mainData['typename']) && $this->mainData['typename']) ? $this->mainData['typename'] : 'Хэлэлцэх' ?></span>
                                <?php }
                            }
                            ?>
                            <?php if(isset($this->mainData['reviewenddate']) && $this->mainData['reviewenddate']) {?>
                                <span class="year" style="font-size: 14px; color:#000"><i class="icon-calendar"></i><br><?php echo $this->mainData['reviewenddate'] ?></span>
                            <?php }?>
                        </div>

                        <?php if(isset($this->tagArr) && $this->tagArr) { 
                                if(in_array('TAGNAME_015', $this->tagArr)) { ?>
                                    <div class="d-flex flex-column">
                                        <h5 class="title mb-0 font-weight-bold"><?php echo $this->mainData['subjectname']; ?></h5>
                                        <span class="description text-grey"><?php echo $this->mainData['typename']; ?></span>
                                    </div>
                                <?php } else { ?>
                                    <div class="d-flex flex-column">
                                        <h4 class="mb-1 font-weight-bold line-height-normal font-size-16"><?php echo $this->mainData['subjectname']; ?></h4>
                                        <p class="posname"><?php echo $this->mainData['departmentname']; ?></p>
                                    </div>
                                <?php }
                            }
                            ?>

                        <div class="ml-auto float-right additionalbutton <?php echo (in_array('TAGNAME_000', $this->tagArr)) ? '' : 'd-none' ?>" tagname_000>
                            <?php
                            if (isset($this->mainData['buttonview'])) {
                                $buttonParam = explode(', ', $this->mainData['buttonview']);
                                foreach ($buttonParam as $key => $row) {
                                    $bParam = explode('#', $row);
                                    $className = ($bParam[0] === 'cancel' || $bParam[0] === 'ignore') ? 'cancel' : '';

                                    echo '<a href="javascript:;" '
                                    . 'onclick="drillDownTransferProcessActionCustom'.$this->uniqId  .'(this, \'' . $this->did . '\', \'' . (isset($bParam[1]) ? $bParam[1] : '') . '\')">'
                                    . '<span title="' . Lang::line($bParam[0]) . '" class="label label-sm">'
                                    . '<div class="btn rightbutton ' . $className . '">'
                                    . '<i class="' . (isset($bParam[2]) ? $bParam[2] : '') . '"></i>'
                                    . '</div>'
                                    . '</span>'
                                    . '</a>';
                                }
                            }
                            ?>
                        </div>
                        <?php $rowJson = htmlentities(json_encode($this->mainData), ENT_QUOTES, 'UTF-8'); ?> 
                        <?php if(isset($this->mainData['isshowsend']) && $this->mainData['isshowsend']) { 
                            if($this->mainData['isshowsend'] == '1') { ?>
                                <div class="ml-auto p-2 bd-highlight">
                                    <a class="btn btn-success" title="ЗГХЭГ - ын референт руу илгээх" onclick="transferProcessAction('', '<?php echo $this->did ?>', '1572006756339', '200101010000011', 'toolbar', this, {callerType: 'CMS_SUBJECT_SANAL_FOR_YAM_LIST'}, undefined, undefined, <?php echo $rowJson ?>, undefined, '');" data-actiontype="update" data-dvbtn-processcode="CMS_SUBJECT_TUSGAH_DV_002" data-ismain="0" href="javascript:;">
                                        <i class="icon-checkmark-circle2" style="color:"></i> Илгээх
                                    </a>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
                <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                    <div class="card-body d-md-flex align-items-md-center flex-md-wrap pt-2 pb-2 pl-0 pr-0">
                        <div class="d-flex align-items-center mr-3 mb-3 mb-md-0 header_icon_box <?php echo (in_array('TAGNAME_001', $this->tagArr)) ? '' : 'd-none' ?> tagname_001">
                            <a href="javascript:void(0);" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
                                <i class="icon-alarm-check"></i>
                            </a>
                            <div class="d-flex flex-row">
                                <span class="text-muted mr-1">Эхэлсэн цаг:</span>
                                <h5 class="font-weight-bold mb-0"><?php echo isset($this->mainData['starttime']) ? $this->mainData['starttime'] : ''; ?></h5>
                            </div>
                        </div>
                        <div class="<?php echo (in_array('TAGNAME_002', $this->tagArr)) ? '' : 'd-none' ?> d-flex align-items-center mr-3 mb-3 mb-md-0 header_icon_box tagname_002">
                            <a href="javascript:void(0);" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
                                <i class="icon-alarm"></i>
                            </a>
                            <div class="d-flex flex-row">
                                <span class="text-muted mr-1">Дууссан цаг:</span>
                                <h5 class="font-weight-bold mb-0"><?php echo isset($this->mainData['endtime']) ? $this->mainData['endtime'] : ''; ?></h5>
                            </div>
                        </div>
                        <div class="<?php echo (in_array('TAGNAME_003', $this->tagArr)) ? '' : 'd-none' ?> d-flex align-items-center mb-3 mb-md-0 header_icon_box tagname_003">
                            <a href="javascript:void(0);" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
                                <i class="icon-alarm"></i>
                            </a>
                            <div class="d-flex flex-row">
                                <span class="text-muted mr-1">Асуудлын үргэлжилсэн хугацаа:</span>
                                <h5 class="font-weight-bold mb-0"><?php echo isset($this->mainData['runtime']) ? $this->mainData['runtime'] : ''; ?></h5>
                            </div>
                        </div>
                        <div class="<?php echo (in_array('TAGNAME_004', $this->tagArr)) ? 'd-none' : 'd-none' ?> tagname_004 ml-auto mr-3 d-flex flex-row align-items-center">
                            <h5 class="problem font-weight-bold">Хариуцсан сайд</h5>
                            <?php
                            $result['saidphoto'] = isset($this->mainData['saidphoto']) ?  explode(',', $this->mainData['saidphoto']) : array();
                            $result['saidname'] = isset($this->mainData['saidname']) ? explode(', </br>', $this->mainData['saidname']) : array();
                            
                            if (issetParamArray($result['saidphoto']) && issetParamArray($result['saidname'])) {
                                foreach (array_combine($result['saidphoto'], $result['saidname']) as $saidphoto => $saidname) {
                                    echo "<img src='" . $saidphoto . "' width='36' height='36' class='rounded-circle mr-1' alt='' id='tooltip-demo' data-html='true' data-toggle='tooltip' title='" . $saidname . "'>";
                                } 
                            } ?>
                        </div>
                        <div class="<?php echo (in_array('TAGNAME_015', $this->tagArr)) ? '' : 'd-none' ?> d-flex align-items-center header_icon_box tagname_015">
                            <div class="d-flex flex-row mr-4">
                                <span class="text-muted mr-1">Шийдвэрийн төрөл:</span>
                                <div class="font-weight-bold mb-0 d-flex flex-row align-items-center">
                                    <div>
                                        <i class="icon-file-empty font-size-14 mr-1 text-blue"></i>
                                    </div>
                                    <div class="text-blue"><?php echo isset($this->mainData['decisionname']) ? $this->mainData['decisionname'] : ''; ?></div>
                                </div>
                            </div>
                            <div class="d-flex flex-row">
                                <span class="text-muted mr-1">Иргэнээс санал авах эсэх:</span>
                                <div class="font-weight-bold mb-0 d-flex flex-row align-items-center">
                                    <div>
                                        <i class="icon-checkbox-checked font-size-14 mr-1 text-blue"></i>
                                    </div>
                                    <div class="text-blue"><?php echo isset($this->mainData['isirgen']) ? $this->mainData['isirgen'] : ''; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_017', $this->tagArr)) ? '' : 'd-none' ?> tagname_017">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Тайлбар</h6>
                    <div class="row">
                        <p style="padding: 0px 10px;">
                            <?php echo isset($this->mainData['description']) ? $this->mainData['description'] : ''; ?>
                        </p>                           
                    </div>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_005', $this->tagArr)) ? '' : 'd-none' ?> tagname_005">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Хавсралт файл <?php echo defined('ISCOVID') ? '' : '';  ?></h6>
                    <div class="row">
                        <?php
                        $i = 1;
                        foreach ($this->attachFilesDv as $row => $file) {
                            if (isset($file['filename']) && $file['filename']) {
                                ?>
                                <div class="col-lg-4">
                                    <div class="card card-body">
                                        <div class="d-flex align-items-center">
                                            <?php 
                                            switch ($file['fileextension']) {
                                                case 'pptx':
                                                    $icon = 'openoffice';
                                                    $style = 'style="color: #f44336;"';
                                                    break;
                                                case 'docx':
                                                case 'doc':
                                                    $icon = 'word';
                                                    $style = 'style="color: #2196f3;"';
                                                    break;
                                                case 'xlsx':
                                                case 'xls' :
                                                    $icon = 'excel';
                                                    $style = 'style="color: #26a69a;"';
                                                    break;
                                                case 'pdf':
                                                    $icon = 'pdf';
                                                    $style = 'style="color: #CC0000;"';
                                                    break;
                                                default:
                                                    $icon = '';
                                                    $style = '';
                                                    break;
                                            }  ?>
                                            <i class="icon-file-<?php echo $icon ?> text-success-400 icon-2x mr-2" <?php echo $style ?>></i>
                                            <?php if ($file['fileextension'] == 'xlsx' || $file['fileextension'] == 'xls') { ?>
                                                <!--<a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_default<?php echo $i; ?>"><?php echo $file['filename']; ?></a>-->
                                                <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $file['fileextension'] ?>', '<?php echo $file['physicalpath'] ?>', '<?php echo URL.$file['physicalpath']?>', 'undefined');" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;"><?php echo $file['filename']; ?></a>
                                                <?php if ($file['count']) { ?>
                                                    <div class="btn btn-outline-info btn-sm ml-2">
                                                        <a href="javascript:void(0);" onclick="dataViewFileViewLog('<?php echo $file['contentid'] ?>');" class="d-flex align-items-center p-0"><i class="fa fa-eye mr-1"></i><?php echo $file['count']; ?></a>
                                                    </div>
                                                <?php } ?>
                                                <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!--<iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?><?php echo $file['physicalpath']; ?>' width='100%' height='550px' frameborder='0'></iframe>-->
                                                                <?php echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>'; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $file['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($file['fileextension'] == 'pptx' || $file['fileextension'] == 'ppt') { ?>
                                                <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_default<?php echo $i; ?>"><?php echo $file['filename']; ?></a>
                                                <?php if ($file['count']) { ?>
                                                    <div class="btn btn-outline-info btn-sm ml-2">
                                                        <a href="javascript:void(0);" onclick="dataViewFileViewLog('<?php echo $file['contentid'] ?>');" class="d-flex align-items-center p-0"><i class="fa fa-eye mr-1"></i><?php echo $file['count']; ?></a>
                                                    </div>
                                                <?php } ?>
                                                <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?><?php echo $file['physicalpath']; ?>' width='100%' height='550px' frameborder='0'></iframe>
                                                                <?php //echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>'; ?>
                                                                <?php //echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>'; ?>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $file['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>    
                                            <?php } elseif ($file['fileextension'] == 'docx' || $file['fileextension'] == 'doc') { ?>
                                                <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $file['fileextension'] ?>', '<?php echo $file['physicalpath'] ?>', '<?php echo URL.$file['physicalpath']?>', 'undefined');" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;"><?php echo $file['filename']; ?></a>
                                                <?php if ($file['count']) { ?>
                                                    <div class="btn btn-outline-info btn-sm ml-2">
                                                        <a href="javascript:void(0);" onclick="dataViewFileViewLog('<?php echo $file['contentid'] ?>');" class="d-flex align-items-center p-0"><i class="fa fa-eye mr-1"></i><?php echo $file['count']; ?></a>
                                                    </div>
                                                <?php } ?>
                                                <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!--<iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?><?php echo $file['physicalpath']; ?>' width='100%' height='550px' frameborder='0'></iframe>-->
                                                                <?php echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>'; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $file['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($file['fileextension'] == 'pdf') { ?>
                                                <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $file['fileextension'] ?>', '<?php echo $file['physicalpath'] ?>', '<?php echo URL.$file['physicalpath']?>', 'undefined');" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;"><?php echo $file['filename']; ?></a>
                                                <?php if ($file['count']) { ?>
                                                    <div class="btn btn-outline-info btn-sm ml-2">
                                                        <a href="javascript:void(0);" onclick="dataViewFileViewLog('<?php echo $file['contentid'] ?>');" class="d-flex align-items-center p-0"><i class="fa fa-eye mr-1"></i><?php echo $file['count']; ?></a>
                                                    </div>
                                                <?php } ?>
                                                <!--<a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_default<?php echo $i; ?>"><?php echo $file['filename']; ?></a>-->
                                                <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!--<iframe src="<?php echo $file['physicalpath']; ?>" style="width:100%; height:550px;" frameborder="0"></iframe>-->
                                                                <?php echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>'; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $file['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_007', $this->tagArr)) ? '' : 'd-none' ?> tagname_007">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Хавсрал файл /Боловсруулалт хийсэн/</h6>
                    <div class="row">
                        <?php
                        $i = 1;
                        foreach ($this->result8 as $row => $result8) {
                            if (isset($result8['filename']) && $result8['filename']) { ?>
                                <div class="col-lg-4">
                                    <div class="card card-body">
                                        <div class="d-flex align-items-center">
                                            <?php 
                                            switch ($result8['fileextension']) {
                                                case 'pptx':
                                                    $icon = 'openoffice';
                                                    $style = 'style="color: #f44336;"';
                                                    break;
                                                case 'docx':
                                                case 'doc' :
                                                    $icon = 'word';
                                                    $style = 'style="color: #2196f3;"';
                                                    break;
                                                case 'xlsx':
                                                case 'xls' :
                                                    $icon = 'excel';
                                                    $style = 'style="color: #26a69a;"';
                                                    break;
                                                case 'pdf':
                                                    $icon = 'pdf';
                                                    $style = 'style="color: #CC0000;"';
                                                    break;
                                                default:
                                                    $icon = '';
                                                    $style = '';
                                                    break;
                                            }  ?>
                                            <i class="icon-file-<?php echo $icon ?> text-success-400 icon-2x mr-2" <?php echo $style ?>></i>

                                                <?php if ($result8['fileextension'] == 'xlsx' || $result8['fileextension'] == 'xls') { ?>
                                                <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $result8['fileextension'] ?>', '<?php echo $result8['physicalpath'] ?>', '<?php echo URL.$result8['physicalpath']?>', 'undefined');" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;"><?php echo $result8['filename']; ?></a>     
                                            <!--<a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>-->
                                                <div id="modal_defaultt<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $result8['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!--<iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?><?php echo $result8['physicalpath']; ?>' width='100%' height='550px' frameborder='0'></iframe>-->
                                                                <?php echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'SheetEdit.aspx?showRb=0&url='.URL.$result8['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>'; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $result8['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($result8['fileextension'] == 'pptx' || $result8['fileextension'] == 'ppt') { ?>
                                                <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>
                                                <div id="modal_defaultt<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $result8['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?><?php echo $result8['physicalpath']; ?>' width='100%' height='550px' frameborder='0'></iframe>
                                                                <?php //echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>'; ?>
                                                                <?php //echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.URL.$file['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>'; ?>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $result8['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>    
                                            <?php } elseif ($result8['fileextension'] == 'docx' || $result8['fileextension'] == 'doc') { ?>
                                                <!--<a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>-->
                                                <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $result8['fileextension'] ?>', '<?php echo $result8['physicalpath'] ?>', '<?php echo URL.$result8['physicalpath']?>', 'undefined');" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;"><?php echo $result8['filename']; ?></a>
                                                <div id="modal_defaultt<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $result8['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!--<iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?><?php echo $result8['physicalpath']; ?>' width='100%' height='550px' frameborder='0'></iframe>-->
                                                                <?php echo '<iframe src="'.CONFIG_FILE_VIEWER_ADDRESS.'DocEdit.aspx?showRb=0&url='.URL.$result8['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px !important;"></iframe>'; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $result8['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } elseif ($result8['fileextension'] == 'pdf') { ?>
                                                <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $result8['fileextension'] ?>', '<?php echo $result8['physicalpath'] ?>', '<?php echo URL.$result8['physicalpath']?>', 'undefined');" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;"><?php echo $result8['filename']; ?></a>
                                                <!--<a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>-->
                                                <div id="modal_defaultt<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"><?php echo $result8['filename']; ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <!--<iframe src="<?php echo $result8['physicalpath']; ?>" style="width:100%; height:550px;" frameborder="0"></iframe>-->
                                                                <?php echo '<iframe src="'.URL.'api/pdf/web/viewer.html?file='.URL.$result8['physicalpath'].'" frameborder="0" style="width: 100%;height: 760px;" id="iframe-detail-'.$this->uniqId.'"></iframe>'; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-link closebtn" data-dismiss="modal">Хаах</button>
                                                                <a href="<?php echo $result8['physicalpath']; ?>" class="btn btn-sm btn-primary">Татаж авах</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card bg-transparent <?php echo (in_array('TAGNAME_013', $this->tagArr)) ? '' : 'd-none' ?> tagname_013">
                <div class="header-box">
                    <div class="d-flex align-items-center w-100">
                        <div class="col-1 pl-0">
                            <div class="duedate d-flex flex-column">
                                <span class="year"><i class="icon-chart"></i><br>Санал</span>
                            </div>
                        </div>
                        <div class="col-6 d-flex flex-row align-items-center organization-progressbar bg-white mr-2 pr-0">
                            <div class="col">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex flex-row">
                                        <div class="mt15 mr-2 d-flex align-items-center">
                                            <i class="icon-office mr-2 text-green"></i>
                                            Яам
                                        </div>
                                        <div class="w-100 d-flex flex-column align-items-center">
                                            <div style="height:22px;">
                                                <span class="text-green font-size-14"><?php echo isset($this->mainData['yampercentage']) ? $this->mainData['yampercentage'] : '' ?></span>
                                            </div>
                                            <div class="progress w-100 mr-2" style="height: 0.500rem;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: <?php echo $this->mainData['yampercentage'] ?>;">
                                                    <span class="sr-only"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt13 ml-2 font-weight-bold font-size-16"><?php echo $this->mainData['yamproperty'] ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto pr-0">
                                <div class="like-box d-flex flex-row align-items-center border-3 border-white">
                                    <span class="like-dislike">
                                        <i class="icon-thumbs-up2 mr-2 text-blue"></i>Дэмжсэн:<span class="ml-1 font-weight-bold"><?php echo $this->mainData['demjsencount'] ?></span>
                                    </span>
                                    <span class="like-dislike">
                                        <i class="icon-thumbs-down2 mr-2 text-orange"></i>Дэмжээгүй:<span class="ml-1 font-weight-bold"><?php echo $this->mainData['demjeeguicount'] ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex flex-row align-items-center user-progressbar bg-white pr-0">
                            <div class="col">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="icon-user mr-2 text-black"></i>
                                        Иргэн
                                    </div>
                                    <div>
                                        <p class="mb-0 font-size-16 text-black font-weight-bold"><?php echo $this->mainData['citizencount'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-auto pr-0">
                                <div class="like-box d-flex flex-row align-items-center border-3 border-white">
                                    <span class="like-dislike">
                                        <i class="icon-thumbs-up2 mr-2 text-blue"></i>Дэмжсэн:<span class="ml-1 font-weight-bold"><?php echo $this->mainData['demjsencitizencount'] ?></span>
                                    </span>
                                    <span class="like-dislike">
                                        <i class="icon-thumbs-down2 mr-2 text-orange"></i>Дэмжээгүй:<span class="ml-1 font-weight-bold"><?php echo $this->mainData['demjeeguicitizencount'] ?></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_014', $this->tagArr)) ? 'd-none' : 'd-none' ?> tagname_014">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Саналын товъёог</h6>
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item"><a href="#organization-tab" class="nav-link p-1 pl-4 pr-4 text-uppercase active show" data-toggle="tab"><i class="icon-office mr-1"></i> Яам</a></li>
                        <li class="nav-item"><a href="#user-tab" class="nav-link p-1 pl-4 pr-4 text-uppercase" data-toggle="tab"><i class="icon-user mr-1"></i> Иргэн</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="organization-tab">
                            <div class="card-table table-responsive shadow-0">
                                <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-circle green mr-1" onclick="print_<?php echo $this->uniqId ?>('<?php echo $this->did ?>', true, 'toolbar', <?php echo htmlentities(json_encode(array('param' => $this->selectedRow)), ENT_QUOTES, 'UTF-8') ?>,'','1576828315165813');"><i class="fa fa-print"></i> Баримт хэвлэх</button>
                                <?php if($this->cmsYamReviewList['yamdtl'] && $this->cmsYamReviewList['yamdtl']) { ?>
                                    <?php if(isset($this->mainData['isyambuttonshow']) && $this->mainData['isyambuttonshow']) { 
                                        if($this->mainData['isyambuttonshow'] == 1) { ?>
                                            <button type="button" onclick="saveSubjectReflection<?php echo $this->uniqId ?>('subjectReflectionForm_');" class="btn btn-sm btn-circle btn-success saveReview_014_1 bp-btn-save"><i class="icon-checkmark-circle2"></i> Хадгалах</button>
                                        <?php }
                                        } ?>
                                <?php } ?>
                                </div>
                                <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'subjectReflectionForm_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold">#</th>
                                            <th class="font-weight-bold" style="width: 16%;">Яам, газар</th>
                                            <th class="font-weight-bold" style="width: 10%;">Дэмжсэн эсэх</th>
                                            <th class="font-weight-bold">Хавсралт файл</th>
                                            <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                            <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        if(isset($this->cmsYamReviewList['yamdtl']) && $this->cmsYamReviewList) {
                                        foreach($this->cmsYamReviewList['yamdtl'] as $row => $yamdtl) { ?>
                                                <input type="hidden" name="subjectId" value="<?php echo $yamdtl['subjectid'] ?>">
                                                <input type="hidden" name="id[]" value="<?php echo $yamdtl['id'] ?>">
                                                <tr style="cursor: pointer;" class="btn_add_class<?php echo $i; ?>" id="btn_add_class<?php echo $i; ?>" tr-status="0">
                                                    <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                    <td><span class="font-weight-bold"><?php echo $yamdtl['departmentname']; ?></span></td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center mr30">
                                                            <span class="font-weight-semibold">
                                                                <?php 
                                                                if (isset($yamdtl['name']) && $yamdtl['name']) {
                                                                    if ($yamdtl['name'] == 'Дэмжсэн') {
                                                                        echo "<span class='badge' title='".$yamdtl['name']."' style='background-color: #4CAF50 !important;'>".$yamdtl['name']."</span>";
                                                                    } else if($yamdtl['name'] == 'Дэмжээгүй') {
                                                                        echo "<span class='badge' title='".$yamdtl['name']."' style='background-color: #f44336 !important;'>".$yamdtl['name']."</span>";
                                                                    } else {

                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <?php
                                                            if(isset($yamdtl['filedtl']) && $yamdtl['filedtl']) {
                                                                foreach($yamdtl['filedtl'] as $str10 => $str20) {
                                                                    if ($str20['fileextension'] == 'pptx' || $str20['fileextension'] == 'ppt') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');"><i class='icon-file-openoffice text-success-400 icon-2x mr-2' style='color: #f44336;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php } elseif($str20['fileextension'] == 'docx' || $str20['fileextension'] == 'doc') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');"><i class='icon-file-word text-success-400 icon-2x mr-2' style='color: #2196f3;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php } elseif($str20['fileextension'] == 'xlsx' || $str20['fileextension'] == 'xls') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');"><i class='icon-file-excel text-success-400 icon-2x mr-2' style='color: #26a69a;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php } elseif($str20['fileextension'] == 'pdf') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');" class='text-default font-weight-bold media-title font-weight-semibold mb-0' style='line-height: normal;'><i class='icon-file-pdf text-success-400 icon-2x mr-2' style='color: #CC0000;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php }
                                                                }
                                                            }?>
                                                        </div>
                                                    </td>
                                                    <td><?php echo '<p class="text-justify">' . $yamdtl['description'] . '</p>'; ?></td>
                                                    <td>
                                                    <?php if(isset($yamdtl['name']) && $yamdtl['name']) { ?>
                                                            <textarea id="autoheight<?php echo $i; ?>" rows="4" cols="50" name="reflection[]" value="<?php echo $yamdtl['reflection'] ?>" placeholder="Энд бичнэ үү"><?php echo $yamdtl['reflection'] ?></textarea> 
                                                    <?php } else { ?>
                                                            <textarea rows="4" cols="50"  class="d-none" name="reflection[]" value="<?php echo $yamdtl['reflection'] ?>" placeholder="Энд бичнэ үү"><?php echo $yamdtl['reflection'] ?></textarea> 
                                                    <?php } ?>
                                                    </td>
                                                </tr>
                                                <script type="text/javascript">
                                                    $(function () {

                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
                                                        });
                                                        var rowHeight = $("#btn_add_class<?php echo $i; ?>").height();
                                                        $("#autoheight<?php echo $i; ?>").height(rowHeight);
                                                        console.log('h = '+ rowHeight);
                                                    });

                                                </script>
                                                <style type="text/css`">
                                                    .government .btn_add_class<?php echo $i; ?> .text-justify {
                                                        display: -webkit-box;
                                                        -webkit-box-orient: vertical;
                                                        -webkit-line-clamp: 3;
                                                        overflow: hidden;
                                                        height: 68px;
                                                    }
                                                    .government .btn_add_class<?php echo $i; ?>.heightauto<?php echo $i; ?> .text-justify {
                                                        display: inherit !important;
                                                        -webkit-box-orient: inherit !important;
                                                        -webkit-line-clamp: inherit !important;
                                                        overflow: inherit !important;
                                                        height: auto !important;
                                                    }
                                                </style>
                                            <?php  
                                                $i++; 
                                            } 
                                        }?>
                                    </tbody>
                                </table>
                                <?php echo Form::close(); ?>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="user-tab">
                            <div class="card-table table-responsive shadow-0">
                                <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-circle green mr-1" onclick="print_<?php echo $this->uniqId ?>('<?php echo $this->did ?>', true, 'toolbar', <?php echo htmlentities(json_encode(array('param' => $this->selectedRow)), ENT_QUOTES, 'UTF-8') ?>,'','1576824384717353');"><i class="fa fa-print"></i> Баримт хэвлэх</button>
                                <?php if(isset($this->subjectReviewCitizen) && $this->subjectReviewCitizen) { ?>

                                <?php if(isset($this->mainData['isirgenbuttonshow']) && $this->mainData['isirgenbuttonshow']) { 
                                        if($this->mainData['isirgenbuttonshow'] == 1) { ?>    
                                            <button type="button" onclick="saveSubjectReflection<?php echo $this->uniqId ?>('subjectReflectionCitizenForm_');" class="btn btn-sm btn-circle btn-success saveReview_014_2 bp-btn-save"><i class="icon-checkmark-circle2"></i> Хадгалах</button>
                                        <?php } 
                                    }?>

                                <?php } ?>
                                </div>
                                <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'subjectReflectionCitizenForm_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold" style="width: 5%;">#</th>
                                            <th class="font-weight-bold" style="width: 16%;">Нэр, хаяг</th>
                                            <th class="font-weight-bold" style="width: 10%;">Дэмжсэн эсэх</th>
                                            <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                            <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach($this->subjectReviewCitizen as $row => $subCitizen) {
                                            if(isset($subCitizen['citizenname']) && $subCitizen['citizenname']) { ?>
                                            <input type="hidden" name="subjectId" value="<?php echo $subCitizen['subjectid'] ?>">
                                            <input type="hidden" name="id[]" value="<?php echo $subCitizen['id'] ?>">
                                                <tr style="cursor: pointer;" class="btn_add_class<?php echo $i; ?>" id="btn_add_class_2_<?php echo $i; ?>" tr-status="0">
                                                    <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                    <td><span class="font-weight-bold"><?php echo $subCitizen['citizenname']; ?></span><br>
                                                        <span class="font-weight-semibold text-grey font-size-12"><i><?php echo $subCitizen['address']; ?></i></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center mr30">
                                                            <span class="font-weight-semibold">
                                                                <?php 
                                                                if(isset($subCitizen['name']) && $subCitizen['name']) {
                                                                    if ($subCitizen['name'] == 'Дэмжсэн') {
                                                                        echo "<span class='badge' title='".$subCitizen['name']."' style='background-color: #4CAF50 !important;'>".$subCitizen['name']."</span>";
                                                                    } else if($subCitizen['name'] == 'Дэмжээгүй') {
                                                                        echo "<span class='badge' title='".$subCitizen['name']."' style='background-color: #f44336 !important;'>".$subCitizen['name']."</span>";
                                                                    } else {

                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo '<p class="text-justify">' . $subCitizen['description'] . '</p>'; ?></td>
                                                    <td><?php //echo '<p class="text-justify">' . $subCitizen['reflection'] . '</p>'; ?>

                                                    <textarea id="autoheight_2_" rows="4" cols="50" name="reflection[]" value="<?php echo $subCitizen['reflection'] ?>" placeholder="Энд бичнэ үү"><?php echo $subCitizen['reflection'] ?></textarea>    
                                                    </td>
                                                </tr>
                                                <script type="text/javascript">

                                                    $(function () {

                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
                                                        });

                                                        var rowHeight = $("#btn_add_class_2_<?php echo $i; ?>").height();
                                                        $("#autoheight_2_<?php echo $i; ?>").height(rowHeight);
                                                        console.log('h = '+ rowHeight);

                                                    });


                                                </script>
                                                <style type="text/css`">
                                                    .government .btn_add_class<?php echo $i; ?> .text-justify {
                                                        display: -webkit-box;
                                                        -webkit-box-orient: vertical;
                                                        -webkit-line-clamp: 3;
                                                        overflow: hidden;
                                                        height: 68px;
                                                    }
                                                    .government .btn_add_class<?php echo $i; ?>.heightauto<?php echo $i; ?> .text-justify {
                                                        display: inherit !important;
                                                        -webkit-box-orient: inherit !important;
                                                        -webkit-line-clamp: inherit !important;
                                                        overflow: inherit !important;
                                                        height: auto !important;
                                                    }
                                                </style>
                                            <?php  
                                                $i++; 
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                                <?php echo Form::close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_016', $this->tagArr)) ? 'd-none' : 'd-none' ?> tagname_014">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Саналын товъёог</h6>
                    <ul class="nav nav-tabs nav-tabs-highlight">
                        <li class="nav-item"><a href="#organization-tab1" class="nav-link p-1 pl-4 pr-4 text-uppercase active show" data-toggle="tab"><i class="icon-office mr-1"></i> Яам</a></li>
                        <li class="nav-item"><a href="#user-tab1" class="nav-link p-1 pl-4 pr-4 text-uppercase" data-toggle="tab"><i class="icon-user mr-1"></i> Иргэн</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="organization-tab1">
                            <div class="card-table table-responsive shadow-0">
                                <?php if(issetParamArray($this->cmsYamReviewList['yamdtl'])) { ?>
                                    <?php if(isset($this->mainData['isyambuttonshow']) && $this->mainData['isyambuttonshow']) { 
                                        if($this->mainData['isyambuttonshow'] == 1) { ?>
                                            <div class="d-flex justify-content-end"><button type="button" onclick="saveSubjectReflection<?php echo $this->uniqId ?>('subjectReflectionForm_');" class="btn btn-sm btn-circle btn-success saveReview_014_1 bp-btn-save"><i class="icon-checkmark-circle2"></i> Хадгалах</button></div>
                                        <?php }
                                        } ?>
                                <?php } ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold">#</th>
                                            <th class="font-weight-bold" style="width: 16%;">Яам, газар</th>
                                            <th class="font-weight-bold" style="width: 10%;">Дэмжсэн эсэх</th>
                                            <th class="font-weight-bold">Хавсралт файл</th>
                                            <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                            <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        if(isset($this->cmsYamReviewList['yamdtl']) && $this->cmsYamReviewList) {
                                        foreach($this->cmsYamReviewList['yamdtl'] as $row => $yamdtl) { ?>
                                                <input type="hidden" name="subjectId" value="<?php echo $yamdtl['subjectid'] ?>">
                                                <input type="hidden" name="id[]" value="<?php echo $yamdtl['id'] ?>">
                                                <tr style="cursor: pointer;" class="btn_add_class<?php echo $i; ?>" id="btn_add_class<?php echo $i; ?>" tr-status="0">
                                                    <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                    <td><span class="font-weight-bold"><?php echo $yamdtl['departmentname']; ?></span></td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center mr30">
                                                            <span class="font-weight-semibold">
                                                                <?php 
                                                                if(isset($yamdtl['name']) && $yamdtl['name']) {
                                                                    if ($yamdtl['name'] == 'Дэмжсэн') {
                                                                        echo "<span class='badge' title='".$yamdtl['name']."' style='background-color: #4CAF50 !important;'>".$yamdtl['name']."</span>";
                                                                    } else if($yamdtl['name'] == 'Дэмжээгүй') {
                                                                        echo "<span class='badge' title='".$yamdtl['name']."' style='background-color: #f44336 !important;'>".$yamdtl['name']."</span>";
                                                                    } else {

                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex">
                                                            <?php
                                                            if(isset($yamdtl['filedtl']) && $yamdtl['filedtl']) {
                                                                foreach($yamdtl['filedtl'] as $str10 => $str20) {
                                                                    if($str20['fileextension'] == 'pptx') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');"><i class='icon-file-openoffice text-success-400 icon-2x mr-2' style='color: #f44336;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php } elseif($str20['fileextension'] == 'docx') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');"><i class='icon-file-word text-success-400 icon-2x mr-2' style='color: #2196f3;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php } elseif($str20['fileextension'] == 'xlsx') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');"><i class='icon-file-excel text-success-400 icon-2x mr-2' style='color: #26a69a;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php } elseif($str20['fileextension'] == 'pdf') { ?>
                                                                        <a href="javascript:void(0);" onclick="dataViewFileViewer('undefined', '1', '<?php echo $str20['fileextension'] ?>', '<?php echo $str20['physicalpath'] ?>', '<?php echo URL.$str20['physicalpath']?>', 'undefined');" class='text-default font-weight-bold media-title font-weight-semibold mb-0' style='line-height: normal;'><i class='icon-file-pdf text-success-400 icon-2x mr-2' style='color: #CC0000;' data-toggle="tooltip" data-placement="bottom" title="<?php echo $str20['filename']; ?>"></i></a>
                                                                    <?php }
                                                                }
                                                            }?>
                                                        </div>
                                                    </td>
                                                    <td><?php echo '<p class="text-justify">' . $yamdtl['description'] . '</p>'; ?></td>
                                                    <td>
                                                        <?php echo '<p class="text-justify">' . $yamdtl['reflection'] . '</p>'; ?>
                                                    </td>
                                                </tr>
                                                <script type="text/javascript">
                                                    $(function () {

                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
                                                        });
                                                        var rowHeight = $("#btn_add_class<?php echo $i; ?>").height();
                                                        $("#autoheight<?php echo $i; ?>").height(rowHeight);
                                                        console.log('h = '+ rowHeight);
                                                    });

                                                </script>
                                                <style type="text/css`">
                                                    .government .btn_add_class<?php echo $i; ?> .text-justify {
                                                        display: -webkit-box;
                                                        -webkit-box-orient: vertical;
                                                        -webkit-line-clamp: 3;
                                                        overflow: hidden;
                                                        height: 68px;
                                                    }
                                                    .government .btn_add_class<?php echo $i; ?>.heightauto<?php echo $i; ?> .text-justify {
                                                        display: inherit !important;
                                                        -webkit-box-orient: inherit !important;
                                                        -webkit-line-clamp: inherit !important;
                                                        overflow: inherit !important;
                                                        height: auto !important;
                                                    }
                                                </style>
                                            <?php  
                                                $i++; 
                                            } 
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="user-tab1">
                            <div class="card-table table-responsive shadow-0">
                                <?php if(isset($this->subjectReviewCitizen) && $this->subjectReviewCitizen) { ?>

                                <?php if(isset($this->mainData['isirgenbuttonshow']) && $this->mainData['isirgenbuttonshow']) { 
                                        if($this->mainData['isirgenbuttonshow'] == 1) { ?>    
                                            <div class="d-flex justify-content-end"><button type="button" onclick="saveSubjectReflection<?php echo $this->uniqId ?>('subjectReflectionCitizenForm_');" class="btn btn-sm btn-circle btn-success saveReview_014_2 bp-btn-save"><i class="icon-checkmark-circle2"></i> Хадгалах</button></div>
                                        <?php } 
                                    }?>

                                <?php } ?>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="font-weight-bold" style="width: 5%;">#</th>
                                            <th class="font-weight-bold" style="width: 16%;">Нэр, хаяг</th>
                                            <th class="font-weight-bold" style="width: 10%;">Дэмжсэн эсэх</th>
                                            <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                            <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach($this->subjectReviewCitizen as $row => $subCitizen) {
                                            if(isset($subCitizen['citizenname']) && $subCitizen['citizenname']) { ?>
                                            <input type="hidden" name="subjectId" value="<?php echo $subCitizen['subjectid'] ?>">
                                            <input type="hidden" name="id[]" value="<?php echo $subCitizen['id'] ?>">
                                                <tr style="cursor: pointer;" class="btn_add_class<?php echo $i; ?>" id="btn_add_class_2_<?php echo $i; ?>" tr-status="0">
                                                    <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                    <td><span class="font-weight-bold"><?php echo $subCitizen['citizenname']; ?></span><br>
                                                        <span class="font-weight-semibold text-grey font-size-12"><i><?php echo $subCitizen['address']; ?></i></span>
                                                    </td>
                                                    <td>
                                                        <div class="d-inline-flex align-items-center mr30">
                                                            <span class="font-weight-semibold">
                                                                <?php 
                                                                if(isset($subCitizen['name']) && $subCitizen['name']) {
                                                                    if ($subCitizen['name'] == 'Дэмжсэн') {
                                                                        echo "<span class='badge' title='".$subCitizen['name']."' style='background-color: #4CAF50 !important;'>".$subCitizen['name']."</span>";
                                                                    } else if($subCitizen['name'] == 'Дэмжээгүй') {
                                                                        echo "<span class='badge' title='".$subCitizen['name']."' style='background-color: #f44336 !important;'>".$subCitizen['name']."</span>";
                                                                    } else {

                                                                    }
                                                                }
                                                                ?>
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td><?php echo '<p class="text-justify">' . $subCitizen['description'] . '</p>'; ?></td>
                                                    <td><?php echo '<p class="text-justify">' . $subCitizen['reflection'] . '</p>'; ?></td>
                                                </tr>
                                                <script type="text/javascript">

                                                    $(function () {

                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
                                                        });

                                                        var rowHeight = $("#btn_add_class_2_<?php echo $i; ?>").height();
                                                        $("#autoheight_2_<?php echo $i; ?>").height(rowHeight);
                                                        console.log('h = '+ rowHeight);

                                                    });


                                                </script>
                                                <style type="text/css`">
                                                    .government .btn_add_class<?php echo $i; ?> .text-justify {
                                                        display: -webkit-box;
                                                        -webkit-box-orient: vertical;
                                                        -webkit-line-clamp: 3;
                                                        overflow: hidden;
                                                        height: 68px;
                                                    }
                                                    .government .btn_add_class<?php echo $i; ?>.heightauto<?php echo $i; ?> .text-justify {
                                                        display: inherit !important;
                                                        -webkit-box-orient: inherit !important;
                                                        -webkit-line-clamp: inherit !important;
                                                        overflow: inherit !important;
                                                        height: auto !important;
                                                    }
                                                </style>
                                            <?php  
                                                $i++; 
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_006', $this->tagArr)) ? '' : 'd-none' ?> tagname_006">
                <div class="card-body">
                    <?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'subjectForm_' . $this->uniqId, 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
                        <input type="hidden" name="result6Data" value="<?php echo $this->reviewDetailData ?>" />
                        <h6 class="font-weight-bold text-uppercase font-size-14">Саналын товъёог</h6>
                        <div class="card-table table-responsive shadow-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">#</th>
                                        <th class="font-weight-bold" style="width: 16%;">Яам, газар</th>
                                        <th class="font-weight-bold" style="width: 10%;">Дэмжсэн эсэх</th>
                                        <th class="font-weight-bold">Хавсралт файл</th>
                                        <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                        <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i = 1;
                                    if ($this->reviewDetail) {
                                        foreach($this->reviewDetail as $row => $reviewDet) { ?>
                                                <?php if (isset($reviewDet['reflection']) && $reviewDet['reflection']){ ?>
                                                    <script type="text/javascript">
                                                        $(".tag006save_<?php echo $this->uniqId ?>").hide();
                                                    </script>
                                                <?php } ?>
                                                <input type="hidden" name="id[]" value="<?php echo $reviewDet['id'] ?>">
                                                <input type="hidden" name="subjectId[]" value="<?php echo $reviewDet['subjectid'] ?>">
                                                <input type="hidden" name="departmentId[]" value="<?php echo $reviewDet['departmentid'] ?>">
                                                <tr style="cursor: pointer; background-color: <?php echo $reviewDet['rowcolor'] ?>" class="btn_add_class<?php echo $i; ?>" id="btn_add_class<?php echo $i; ?>" tr-status="0">
                                                    <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                    <td><span class="font-weight-bold"><?php echo $reviewDet['departmentname']; ?></span></td>
                                                    <td>
                                                        <?php if (!isset($reviewDet['reflection']) && !$reviewDet['reflection'] && !$reviewDet['isdisabled'] == '1') {
                                                            foreach ($this->reviewLookup as $key => $type) { ?>
                                                                <label><input type="radio" name="reviewTypeId[]" value="<?php echo $type['id'] ?>" <?php echo ($type['id'] == $reviewDet['reviewtypeid']) ? 'checked' : '' ?>> <?php echo $type['name'] ?></label><br>
                                                            <?php 
                                                            }
                                                        } 
                                                        else {
                                                            echo '<p class="text-justify">' . $reviewDet['name'] . '</p>'; 
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex"><?php
                                                            (String) $html = $html2 = '';
                                                            if ($reviewDet['filedtl']) {
                                                                foreach($reviewDet['filedtl'] as $keyF => $filedtl) {
                                                                    $html .= '<div class="btn-group mt3 mb3">';
                                                                    $html .= '<input type="hidden" name="reviewFile[]" value="'. $filedtl['id'] .'" />';

                                                                    switch ($filedtl['fileextension']) {
                                                                        case 'pptx':
                                                                        case 'ppt':
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-sm mr0" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');" title="'.$filedtl['filename'].'" style="height: 24px;padding: 1px 5px;line-height: 12px;">'.$filedtl['filename'].'..</button>';
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-icon btn-sm" title="Устгах" onclick="removeFileHtml'. $this->uniqId .'(this);" data-id="1583934931199" data-r-path="fileAttach" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>';

                                                                            $html2 .= '<a href="javascript:void(0);" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');"><i class="icon-file-openoffice text-success-400 icon-2x mr-3" style="color: #f44336;" data-toggle="tooltip" data-placement="bottom" title="'.$filedtl['filename'].'"></i></a>';
                                                                            break;
                                                                        case 'docx':
                                                                        case 'doc':
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-sm mr0" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');" title="'.$filedtl['filename'].'" style="height: 24px;padding: 1px 5px;line-height: 12px;">'.$filedtl['filename'].'..</button>';
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-icon btn-sm" title="Устгах" onclick="removeFileHtml'. $this->uniqId .'(this);" data-id="1583934931199" data-r-path="fileAttach" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>';

                                                                            $html2 .= '<a href="javascript:void(0);" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');"><i class="icon-file-word text-success-400 icon-2x mr-3" style="color: #2196f3;" data-toggle="tooltip" data-placement="bottom" title="'.$filedtl['filename'].'"></i></a>';
                                                                            break;
                                                                        case 'xlsx':
                                                                        case 'xls':
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-sm mr0" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');" title="'.$filedtl['filename'].'" style="height: 24px;padding: 1px 5px;line-height: 12px;">'.$filedtl['filename'].'..</button>';
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-icon btn-sm" title="Устгах" onclick="removeFileHtml'. $this->uniqId .'(this);" data-id="1583934931199" data-r-path="fileAttach" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>';

                                                                            $html2 .= '<a href="javascript:void(0);" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');"><i class="icon-file-excel text-success-400 icon-2x mr-3" style="color: #26a69a;" data-toggle="tooltip" data-placement="bottom" title="'.$filedtl['filename'].'"></i></a>';
                                                                            break;
                                                                        case 'pdf':
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-sm mr0" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');" title="'.$filedtl['filename'].'" style="height: 24px;padding: 1px 5px;line-height: 12px;">'.$filedtl['filename'].'..</button>';
                                                                            $html .= '<button type="button" class="btn btn-outline-info btn-icon btn-sm" title="Устгах" onclick="removeFileHtml'. $this->uniqId .'(this);" data-id="1583934931199" data-r-path="fileAttach" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>';

                                                                            $html2 .= '<a href="javascript:void(0);" onclick="dataViewFileViewer(this, \'1\', \''.$filedtl['fileextension'].'\', \''.$filedtl['physicalpath']. '\', \''.URL.$filedtl['physicalpath'].'\', \'undefined\');"><i class="icon-file-pdf text-success-400 icon-2x mr-3" style="color: #CC0000;" data-toggle="tooltip" data-placement="bottom" title="'.$filedtl['filename'].'"></i></a>';
                                                                            break;
                                                                    }

                                                                    $html .= '</div>';
                                                                }
                                                            }
                                                            ?>
                                                            <div class="hiddenFileDiv mb-1">
                                                                <?php echo (!isset($reviewDet['reflection']) && !$reviewDet['reflection'] && !$reviewDet['isdisabled'] == '1') ? $html : $html2; ?>
                                                            </div>
                                                        </div>
                                                        <?php if (!isset($reviewDet['reflection']) && !$reviewDet['reflection'] && !$reviewDet['isdisabled'] == '1') { ?>
                                                            <a href="javascript:;" class="btn btn-sm btn-outline bg-primary border-primary text-primary-800 btn-icon border-1 btn fileinput-button" title="Файл нэмэх">
                                                                <i class="icon-attachment mr-1"></i> Файл нэмэх
                                                                <input type="file" name="bp_file[<?php echo $reviewDet['id'] ?>][]" class="" multiple="" onchange="onChangeAttachFIleAddMode<?php echo $this->uniqId ?>(this)">
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!isset($reviewDet['reflection']) && !$reviewDet['reflection'] && !$reviewDet['isdisabled'] == '1') { ?>
                                                            <?php if (isset($reviewDet['description'])) { ?>
                                                                <textarea rows="4" class="descriptionAuto_1" cols="50" name="description[]" style="width: 90%;" value="<?php echo $reviewDet['description'] ?>" placeholder="Энд бичнэ үү"><?php echo $reviewDet['description'] ?></textarea>
                                                            <?php } else { ?>
                                                                <textarea rows="4" class="descriptionAuto_1" cols="50" name="description[]" style="width: 90%;" placeholder="Энд бичнэ үү"></textarea>
                                                            <?php } ?> 
                                                        <?php } else {
                                                            echo '<p class="text-justify">' . $reviewDet['description'] . '</p>'; 
                                                        } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (isset($reviewDet['reflection']) && $reviewDet['reflection']) { 
                                                            echo '<p class="text-justify">' . $reviewDet['reflection'] . '</p>';
                                                        } else { 
                                                            echo '<p class="text-grey">Тэмдэглэл бүртгэгдээгүй байна</p>';
                                                        } ?>
                                                    </td>
                                                </tr>
                                                <script type="text/javascript">

                                                    $(function() {
                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
                                                        });
                                                    });

                                                </script>
                                                <style type="text/css`">
                                                    .government .btn_add_class<?php echo $i; ?> .text-justify {
                                                        display: -webkit-box;
                                                        -webkit-box-orient: vertical;
                                                        -webkit-line-clamp: 3;
                                                        overflow: hidden;
                                                        height: 68px;
                                                    }
                                                    .government .btn_add_class<?php echo $i; ?>.heightauto<?php echo $i; ?> .text-justify {
                                                        display: inherit !important;
                                                        -webkit-box-orient: inherit !important;
                                                        -webkit-line-clamp: inherit !important;
                                                        overflow: inherit !important;
                                                        height: auto !important;
                                                    }
                                                </style>
                                            <?php  
                                            $i++; 
                                        }
                                    }
                                ?>
                                </tbody>         
                            </table>
                            <div class="d-flex justify-content-end">
                                <?php if($this->mainData['savebutton'] == '1') { ?>
                                    <button type="button" onclick="saveSubjectReview<?php echo $this->uniqId ?>(0);" class="btn btn-sm btn-circle mr-1 btn-success tag006save_<?php echo $this->uniqId ?> bp-btn-save"><i class="icon-checkmark-circle2"></i> Хадгалах</button>     
                                <?php } ?>
                                <?php if($this->mainData['sendbutton'] == '1') { ?>
                                    <button type="button" onclick="confirmDialog();" class="btn btn-sm btn-circle tag006save_<?php echo $this->uniqId ?> btn-primary "><i class="icon-checkmark-circle2"></i> Илгээх</button>
                                <?php } ?>
                            </div>
                        </div>
                    <?php echo Form::close(); ?>
                </div>
            </div>
            <div class="card <?php echo (in_array('TAGNAME_008', $this->tagArr)) ? '' : 'd-none' ?> tagname_008">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Асуудлын шийдвэрлэлт</h6>
                    <?php if(isset($this->result7) && $this->result7) {
                     
                        foreach($this->result7['attachfile'] as $key => $result7) {
                        ?>
                        <?php if(isset($result7) && $result7) { ?>
                            <h5><?php echo $result7['decisiontypename'] ?></h5>
                            <!-- <div class="d-flex flex-row poll-info mt10 mb10">
                                <h2 class="mr-3"><span>Дугаар:</span> <?php echo $result7['text_1'] ?></h2>
                                <h2 class="mr-3"><span>Хуудасны тоо:</span> <?php echo $result7['text_3'] ?></h2>
                            </div> -->
                            <div class="card-body p-0 border-0">
                                <ul class="list-inline mb-0">
                                    <?php $fileExtArr = explode(' , ', $result7['physicalpath']);
                                            $str = trim($result7['filename']);
                                            $strArray = explode(',', $str);
                                            $str2 = trim($result7['physicalpath']);
                                            $strArray2 = explode(',', $str2);
                                            $index_ = 0;
                                            $n = 1;

                                            foreach (array_combine($strArray, $strArray2) as $str => $str2) { 
                                                $fileExtension = strtolower(substr($fileExtArr[$index_], strrpos($fileExtArr[$index_], '.') + 1));
                                                $icon = '';
                                                $color = '';
                                                if($fileExtension === 'pdf') {
                                                    $icon = 'icon-file-pdf';
                                                    $color = '#CC0000;';
                                                } else if($fileExtension === 'doc' || $fileExtension === 'docx') {
                                                    $icon = 'icon-file-word';
                                                    $color = '#2196f3;';
                                                } else if($fileExtension === 'ppt' || $fileExtension === 'pptx') {
                                                    $icon = 'icon-file-openoffice';
                                                    $color = '#f44336;';
                                                } else if($fileExtension === 'xls' || $fileExtension === 'xlsx') {
                                                    $icon = 'icon-file-excel';
                                                    $color = '#26a69a;';
                                                } 
                                                ?> 
                                                <li class="list-inline-item">
                                                    <div class="card bg-light py-1 px-2 mt-0 mb-0">
                                                        <div class="media my-1">
                                                            <div class="mr-2 align-self-center"><i class="<?php echo $icon ?> icon-2x text-danger-400 top-0" style="color: <?php echo $color ?>"></i></div>
                                                            <div class="media-body">
                                                                <div class="font-weight-semibold"><?php echo $result7['filename'] ?></div>
                                                                <ul class="list-inline list-inline-condensed mb-0">
                                                                    <li class="list-inline-item"><a href="javascript:;" onclick="dataViewFileViewer('undefined', '1', '<?php echo $fileExtension ?>', '<?php echo $str2 ?>', '<?php echo URL.$str2 ?>', 'undefined');">Харах</a></li>
                                                                    <li class="list-inline-item"><a href="<?php echo $result7['physicalpath'] ?>" target="_blank">Татах</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                    <?php }} ?> 
                </div>
            </div>
            <div class="card d-none">
                <div class="card-body">
                    <h6 class="font-weight-bold text-uppercase font-size-14">Асуудлын шийдвэрлэлт</h6>
                    <?php if(isset($this->subjectDecisionGet['subjectdecisiondtl']) && $this->subjectDecisionGet['subjectdecisiondtl']) { ?>
                    <?php foreach($this->subjectDecisionGet['subjectdecisiondtl'] as $key => $data) { ?>     
                    <hr style="border-top: 2px solid #a3a3a3;">
                    <h5><?php echo $data['decisionname'] ?></h5>
                    <div class="d-flex flex-row poll-info mt10 mb10">
                        <h2 class="mr-3"><span>Дугаар:</span> <?php echo $data['decisionnumber'] ?></h2>
                    </div>
                    <div class="d-flex flex-row poll-info mt10 mb10">
                        <h2 class="mr-3"><span>Огноо:</span> <?php echo $data['decisiondate'] ?></h2>
                    </div>
                    <div class="d-flex flex-row poll-info mt10 mb10">
                        <h2 class="mr-3"><span>Шийдвэр:</span> <?php echo $data['decision'] ?></h2>
                    </div>
                    <?php if(isset($data['taskdtl']) && $data['taskdtl']) { ?>
                        <div class="table-responsive mb-3">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold">№</th>
                                        <th class="font-weight-bold">Гүйцэтгэх ажилтан</th>
                                        <th class="font-weight-bold">Ажлын төлөв</th>
                                        <th class="font-weight-bold">Гүйцэтгэлийн үнэлгээ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['taskdtl'] as $key=> $task) { ?>
                                    <tr>
                                        <td><?php echo $key+1 ?></td>
                                        <td><?php echo $task['employeename'] ?></td>
                                        <td><?php echo $task['wfmstatusname'] ?></td>
                                        <td><?php echo $task['estimatedescription'] ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php if(isset($data['attachfiledtl']) && $data['attachfiledtl']) { ?>
                    <div class="d-flex flex-row poll-info mt10 mb10">
                        <h2 class="mr-3"><span>Шийдвэрийн хавсралт</span></h2>
                    </div>
                    <div class="card-body p-0 border-0">
                        <ul class="list-inline mb-0">
                        <?php foreach($data['attachfiledtl'] as $files) { ?>
                            <?php 
                                $icon = '';
                                $color = '';
                                if($files['fileextension'] === 'pdf') {
                                    $icon = 'icon-file-pdf';
                                    $color = '#CC0000;';
                                } else if($files['fileextension'] === 'doc' || $files['fileextension'] === 'docx') {
                                    $icon = 'icon-file-word';
                                    $color = '#2196f3;';
                                } else if($files['fileextension'] === 'ppt' || $files['fileextension'] === 'pptx') {
                                    $icon = 'icon-file-openoffice';
                                    $color = '#f44336;';
                                } else if($files['fileextension'] === 'xls' || $files['fileextension'] === 'xlsx') {
                                    $icon = 'icon-file-excel';
                                    $color = '#26a69a;';
                                } 
                                ?> 
                                <li class="list-inline-item">
                                    <div class="card bg-light py-1 px-2 mt-0 mb-0">
                                        <div class="media my-1">
                                            <div class="mr-2 align-self-center"><i class="<?php echo $icon ?> icon-2x text-danger-400 top-0" style="color: <?php echo $color ?>"></i></div>
                                            <div class="media-body">
                                                <div class="font-weight-semibold"><?php echo $files['filename'] ?></div>
                                                <ul class="list-inline list-inline-condensed mb-0">
                                                    <li class="list-inline-item"><a href="javascript:;" onclick="dataViewFileViewer('undefined', '1', '<?php echo $files['fileextension'] ?>', '<?php echo $files['physicalpath'] ?>', '<?php echo URL.$files['physicalpath'] ?>', 'undefined');">Харах</a></li>
                                                    <li class="list-inline-item"><a href="<?php echo $files['physicalpath'] ?>" target="_blank">Татах</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>
                            </ul>
                        </div>
                            <?php } ?>
                    <?php } }?>
                </div>
            </div>
        </div>
        <div class="col-3 <?php echo (in_array('TAGNAME_012', $this->tagArr)) ? '' : 'd-none' ?> tagname_012">
            <div class="sidebar-content">
                <div class="card <?php echo (in_array('TAGNAME_009', $this->tagArr)) ? 'd-none' : 'd-none' ?> tagname_009">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-weight-bold">Чеклист</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <ul class="media-list media-list-linked">
                        <?php
                        $i = 0;
                        foreach ($this->result4 as $row => $result4) {
                            if (isset($result4['id']) && $result4['id']) {
                                $statusColor = (isset($result4['statuscolor']) && $result4['statuscolor']) ? 'color: #FFF !important; background: ' . $result4['statuscolor'] . ' !important;' : '';
                                ?>
                                <li style="cursor: pointer;" data-toggle="collapse" data-target="#collapse_checklist<?php echo $i; ?>" aria-expanded="true" aria-controls="collapse_checklist<?php echo $i; ?>">
                                    <div class="media mb-0">
                                        <div class="mr-2">
                                            <?php
                                                if ($result4['picture'] == null) {
                                                    echo "<img src='assets/core/global/img/user.png' width='36' height='36' class='rounded-circle' alt=''>";
                                                } else {
                                                    echo "<img src='" . $result4['picture'] . "' width='36' height='36' class='rounded-circle' alt=''>";
                                                }
                                            ?>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-title d-flex">
                                                <span class="font-weight-bold"><?php echo $result4['employeename']; ?></span>
                                                <span class="text-muted ml-auto"><?php echo $result4['userstatusdate']; ?></span>
                                            </div>
                                            <div class="media-title d-flex">
                                                <span class="text-muted" style="line-height: normal;"><?php echo $result4['positionname']; ?></span>
                                                <span style="font-size: 12px; padding: 1px 6px 1px 6px; <?php echo $statusColor ?>; max-height: 20.4px;" class="text-muted ml-auto font-weight-bold text-uppercase text-green" style=""><?php echo $result4['assignedstatusname']; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <div id="accordion<?php echo $i; ?>" class="accordion">
                                    <div class="card">
                                        <div id="collapse_checklist<?php echo $i; ?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion<?php echo $i; ?>">
                                            <div class="card-body pt-0 pb-0">
                                                <p class="text-justify">
                                                    <?php
                                                    if (isset($result4['assignedstatusname']) && $result4['assignedstatusname']) {
                                                        echo $result4['assignedtablename'];
                                                    } else {
                                                        echo "";
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $i++;
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="card <?php echo (in_array('TAGNAME_010', $this->tagArr)) ? '' : 'd-none' ?> tagname_010">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-weight-bold">Хариуцсан ажилтан</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <ul class="media-list">
                            <?php
                                foreach ($this->result3 as $row => $result3) {
                                    if (isset($result3['id']) && $result3['id']) { ?>
                                    <li class="media">
                                        <a href="javascript:void(0);" class="mr-2 position-relative">
                                            <?php
                                                if ($result3['picture'] == null) {
                                                echo "<img src='assets/core/global/img/user.png' width='36' height='36' class='rounded-circle' alt=''>";
                                            } else {
                                                echo "<img src='" . $result3['picture'] . "' width='36' height='36' class='rounded-circle' alt=''>";
                                            }
                                            ?>
                                        </a>
                                        <div class="media-body">
                                            <div class="font-weight-bold"><?php echo $result3['name']; ?></div>
                                            <span class="text-muted"><?php echo $result3['positionname']; ?></span>
                                        </div>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
                <div class="card <?php echo (in_array('TAGNAME_011', $this->tagArr)) ? '' : 'd-none' ?> tagname_011">
                    <div class="card-header bg-transparent header-elements-inline">
                        <span class="text-uppercase font-weight-bold">Ажиглагч нар</span>
                        <div class="header-elements">
                            <div class="list-icons">
                                <a class="list-icons-item" data-action="collapse"></a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-1">
                        <ul class="media-list">
                            <?php
                            foreach ($this->result5 as $row => $result5) {
                                if (isset($result5['id']) && $result5['id']) { ?>
                                    <li class="media mb-3">
                                        <div class="mr-3">
                                            <?php
                                            if ($result5['isparticipated'] == 1) {
                                                echo "<button type='button' class='btn bg-green-600 btn-icon rounded-round'><i class='icon-checkmark'></i></button>";
                                            } else {
                                                echo "<button type='button' class='btn bg-pink-600 btn-icon rounded-round'><i class='icon-cross2 font-weight-bold'></i></button>";
                                            }
                                            ?>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <span class="font-weight-bold"><?php echo $result5['name']; ?></span>
                                                <span class="font-weight-semibold" style="color:#999; line-height: normal;"><?php echo $result5['positionname']; ?> - <?php echo $result5['organizationname']; ?></span>
                                            </div>
                                        </div>
                                    </li>
                                <?php }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        .government_<?php echo $this->uniqId ?> .d-none {
            display: none !important;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            if (!$("link[href='assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
            }
        });

        function drillDownTransferProcessActionCustom<?php echo $this->uniqId ?>(elem, did, processId) {
            var $dialogName = 'dialog-businessprocess-' + processId;
            if (!$('#' + $dialogName).length) {
                $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
            }

            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdwebservice/callMethodByMeta',
                data: {
                    metaDataId: processId,
                    isDialog: true,
                    dmMetaDataId: did,
                    isSystemMeta: false,
                    oneSelectedRow: {id: '<?php echo $this->mainData['id'] ?>'}
                },
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {

                    $dialog.empty().append(data.Html);

                    var $processForm = $('#wsForm', '#' + $dialogName),
                            processUniqId = $processForm.parent().attr('data-bp-uniq-id');

                    var buttons = [
                        {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                                if (window['processBeforeSave_' + processUniqId]($(e.target))) {

                                    $processForm.validate({
                                        ignore: '',
                                        highlight: function (element) {
                                            $(element).addClass('error');
                                            $(element).parent().addClass('error');
                                            if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                                $processForm.find("div.tab-pane:hidden:has(.error)").each(function (index, tab) {
                                                    var tabId = $(tab).attr('id');
                                                    $processForm.find('a[href="#' + tabId + '"]').tab('show');
                                                });
                                            }
                                        },
                                        unhighlight: function (element) {
                                            $(element).removeClass('error');
                                            $(element).parent().removeClass('error');
                                        },
                                        errorPlacement: function () {}
                                    });

                                    var isValidPattern = initBusinessProcessMaskEvent($processForm);

                                    if ($processForm.valid() && isValidPattern.length === 0) {
                                        $processForm.ajaxSubmit({
                                            type: 'post',
                                            url: 'mdwebservice/runProcess',
                                            dataType: 'json',
                                            beforeSend: function () {
                                                Core.blockUI({
                                                    boxed: true,
                                                    message: 'Түр хүлээнэ үү'
                                                });
                                            },
                                            success: function (responseData) {
                                                PNotify.removeAll();
                                                new PNotify({
                                                    title: responseData.status,
                                                    text: responseData.message,
                                                    type: responseData.status,
                                                    sticker: false
                                                });

                                                if (responseData.status === 'success') {
                                                    $(elem).closest('.fc-hrm-time-duration').prepend('<div class="fc-hrm-time-descr fc-hrm-time-wfmstatus" title="' + responseData.resultData.description + '">' + responseData.resultData.description + '</div>');
                                                    $dialog.dialog('close');
                                                    reload_<?php echo $this->uniqId ?>();
                                                }
                                                Core.unblockUI();
                                            },
                                            error: function () {
                                                alert("Error");
                                            }
                                        });
                                    }
                                }
                            }},
                        {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                    ];
                    var dialogWidth = data.dialogWidth,
                            dialogHeight = data.dialogHeight;

                    if (data.isDialogSize === 'auto') {
                        dialogWidth = 1200;
                        dialogHeight = 'auto';
                    }

                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 500,
                        height: dialogHeight,
                        modal: true,
                        closeOnEscape: (typeof isCloseOnEscape == 'undefined' ? true : isCloseOnEscape),
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        buttons: buttons
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });
                    if (data.dialogSize === 'fullscreen') {
                        $dialog.dialogExtend("maximize");
                    }

                    setTimeout(function () {
                        $dialog.dialog('open');
                        Core.unblockUI();
                    }, 1);

                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initBPAjax($dialog);
            });
        }

        function reload_<?php echo $this->uniqId ?>() {
            $.ajax({
                type: 'post',
                url: 'government/government',
                dataType: 'json',
                data: {
                    dataViewId: '<?php echo $this->did ?>',
                    recordId: '<?php echo $this->mainData['id'] ?>',
                    selectedRow: {id: '<?php echo $this->mainData['id'] ?>'}
                },
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {
                    $('.government_<?php echo $this->uniqId ?>').empty().append(data.html).promise().done(function () {});
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        }

        function confirmDialog() {
            var $dialogName = 'dialogConfirm_<?php echo $this->uniqId ?>';
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }

            var $confirmDialog_<?php echo $this->uniqId ?> = $("#dialogConfirm_<?php echo $this->uniqId ?>");
            $confirmDialog_<?php echo $this->uniqId ?>.empty().append('Санал илгээсний дараа буцаах боломжгүй тул та дахин шалгана уу. Саналаа илгээхдээ итгэлтэй байна уу?');
            $confirmDialog_<?php echo $this->uniqId ?>.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: 'Илгээх',
                width: 370,
                height: "auto",
                modal: true,
                open: function() {
                },
                close: function() {
                    $confirmDialog_<?php echo $this->uniqId ?>.empty().dialog('destroy').remove();
                },
                buttons: [{
                        text: plang.get('yes_btn'),
                        class: 'btn green-meadow btn-sm',
                        click: function() {
                            saveSubjectReview<?php echo $this->uniqId ?>(1);

                            $confirmDialog_<?php echo $this->uniqId ?>.dialog('close');
                        }
                    },
                    {
                        text: plang.get('no_btn'),
                        class: 'btn blue-madison btn-sm',
                        click: function() {
                            $confirmDialog_<?php echo $this->uniqId ?>.dialog('close');
                        }
                    }
                ]
            });
            $confirmDialog_<?php echo $this->uniqId ?>.dialog('open');
        }

        function saveSubjectReview<?php echo $this->uniqId ?>(type) {

            $('#subjectForm_<?php echo $this->uniqId ?>').ajaxSubmit({
                type: 'post',
                url: 'government/saveSubjectReview',
                dataType: 'json',
                data : {issend: type},
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү'
                    });
                },
                success: function(data) {
                    console.log(data);
                    console.log(data.status);
                    
                    if(data.status === 'success') {
                        if(type === 1) {
                            bpMultiTabClose('', 'mdassetgovernment', '1');
                            dataViewReload('1569895114933');
                        }

                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                }
            });
        }

        function savereflection<?php echo $this->uniqId ?>(element) {
            $.ajax({
                type: 'post',
                url: 'mdasset/saveSubjectReflection',
                dataType: 'json',
                data: {reflection: $(element).val(), id: $(element).attr('data-id'), departmentId: $(element).attr('data-departmentid'), subjectId: $(element).attr('data-subjectid')},
                success: function(data){
                    if(data.status === 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    } 
                }
            });
        }

        function onChangeAttachFIleAddMode<?php echo $this->uniqId ?>(input, $type) {
            if ($(input).hasExtension(["png", "gif", "jpeg", "pjpeg", "jpg", "x-png", "bmp", "doc", "docx", "xls", "xlsx", "pdf", "ppt", "pptx",
                "zip", "rar", "mp3", "mp4"])) {
                var ext = input.value.match(/\.([^\.]+)$/)[1],
                    i = 0;
                if (typeof ext !== "undefined") {

                    for (i; i < input.files.length; i++) {
                        ext = input.files[i].name.match(/\.([^\.]+)$/)[1];
                        var li = '',
                                fileImgUniqId = Core.getUniqueID('file_img'),
                                fileAUniqId = Core.getUniqueID('file_a'),
                                extension = ext.toLowerCase();
                        var $icon = 'icon-file-pdf';
                        switch (extension) {
                            case 'png':
                            case 'gif':
                            case 'jpeg':
                            case 'pjpeg':
                            case 'jpg':
                            case 'x-png':
                            case 'bmp':
                                $icon = "icon-file-picture text-danger-400";
                                break;
                            case 'zip':
                            case 'rar':
                                $icon = "icon-file-zip text-danger-400";
                                break;
                            case 'mp3':
                                $icon = "icon-file-music text-danger-400";
                                break;
                            case 'mp4':
                                $icon = "icon-file-video text-danger-400";
                                break;
                            case 'doc':
                            case 'docx':
                                $icon = "icon-file-word text-blue-400";
                                break;
                            case 'pdf':
                                $icon = "icon-file-pdf text-danger-400";
                                break;
                            case 'ppt':
                            case 'pptx':
                                $icon = "icon-file-presentation text-danger-400";
                                break;
                            case 'xls':
                            case 'xlsx':
                                $icon = "icon-file-excel text-green-400";
                                break;
                            default:
                                $icon = "icon-file-empty text-danger-400";
                                break;
                        }
                        var $liAfter = '<div class="btn-group mt3 mb3">'
                                        + '<button type="button" class="btn btn-outline-info btn-sm mr0" title="'+ input.files[i].name +'" style="height: 24px;padding: 1px 5px;line-height: 12px;">'+ input.files[i].name +'..</button>'
                                        + '<button type="button" class="btn btn-outline-info btn-icon btn-sm" title="Устгах" onclick="removeFileHtml<?php echo $this->uniqId ?>(this)" data-id="1583934931199" data-r-path="fileAttach" style="height: 24px;padding: 1px 5px; width: 20px;padding: 2px 2px 2px 1px;line-height: 18px;"><i class="icon-cross"></i></button>'
                                    + '</div>';
                        $('.hiddenFileDiv').append($liAfter);
                    }
                }
            } else {
                alert('Файл сонгоно уу.');
                $(input).val('');
            }
        }

        function removeFileHtml<?php echo $this->uniqId ?>(element){
            var $element = $(element).closest('.btn-group');
            $element.remove();
        }

        function saveSubjectReflection<?php echo $this->uniqId ?>(form){
            var formId = form;

            $('#'+formId+'<?php echo $this->uniqId ?>').ajaxSubmit({
                type: 'post',
                url: 'mdasset/saveSubjectReflection',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү'
                    });
                },
                success: function(data){
                    console.log(data);
                    console.log(data.status);
                    if(data.status === 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                }
            });
        }

        function sendSubject<?php echo $this->uniqId ?>(form) {
            $('#subjectForm_<?php echo $this->uniqId ?>').ajaxSubmit({
                type: 'post',
                url: 'mdasset/sendSubject',
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        boxed: true,
                        message: 'Түр хүлээнэ үү'
                    });
                },
                success: function(data){
                    console.log(data);
                    console.log(data.status);
                    if(data.status === 'success') {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    } else {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.text,
                            type: data.status,
                            sticker: false
                        });
                    }
                    Core.unblockUI();
                }
            });
        }

        function processPrintPreviewCust_<?php echo $this->uniqId ?>(element, methodId, rowId) {
            var $row = JSON.parse($(element).attr('data-row'));
            processPrintPreview(element, methodId, rowId, '', $row);
        }

        function print_<?php echo $this->uniqId ?>(mainMetaDataId, isDialog, whereFrom, elem, isOneRow, templateId) {

            var rows = <?php echo json_encode($this->selectedRow) ?>;

            if (rows.length === 0) {
                alert(plang.get('msg_pls_list_select'));
                return;
            }

            var $configStr = '';

            if (typeof rows.configstr !== 'undefined') {
                $configStr = JSON.parse(html_entity_decode(rows.configstr, true));
            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Warning',
                    text: 'Тохиргооны мэдээлэлийг бүрэн бөглөнө үү',
                    type: 'warning',
                    addclass: pnotifyPosition,
                    sticker: false
                });
                return;
            }

            var $dialogName = 'dialog-printSettings';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $('#' + $dialogName);

            $.ajax({
                type: 'post',
                url: 'mdtemplate/checkCriteria',
                data: {
                    metaDataId: '<?php echo $this->did ?>', 
                    dataRow: rows, 
                    isProcess: false
                },
                dataType: "json",
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function(response) {
                    PNotify.removeAll();
                    var print_options = {
                        numberOfCopies: $configStr.numberOfCopies,
                        isPrintNewPage: $configStr.isPrintNewPage,
                        isSettingsDialog: $configStr.isSettingsDialog,
                        isShowPreview: $configStr.isShowPreview,
                        isPrintPageBottom: $configStr.isPrintPageBottom,
                        isPrintPageRight: $configStr.isPrintPageRight,
                        pageOrientation: $configStr.pageOrientation,
                        isPrintSaveTemplate: $configStr.isPrintSaveTemplate,
                        paperInput: $configStr.paperInput,
                        pageSize: $configStr.pageSize,
                        printType: $configStr.printType,
                        templates: templateId, 
                        templateIds: templateId ,
                        templateMetaIds: '1576737696166_1576737645048'
                    }; 
                    callTemplate(rows, '<?php echo $this->did ?>', print_options);
                    Core.unblockUI();
                }
            }).done(function() {
                Core.initDVAjax($dialog);
            });
        }

        function dataViewFileViewLog(id) {
            $.ajax({
                type: "post",
                dataType: "json",
                url: 'conference/fileViewLog', 
                data:{
                    id:id,
                },
                beforeSend: function () {
                },
                success: function (data) {
                    var $dialogName = "dialog-total-" + id;

                    var additionHtml = "";
                    $.each(data.data.viewedempployees, function (i, item) {
                        additionHtml +='<div class="bs-callout-info callout-bordered mt-1 mb-2">';
                            additionHtml += '<div class="media align-items-center">';
                                additionHtml += '<div class="d-flex align-items-center p-2 mr-2">';
                                    additionHtml += '<img src="'+ item.picture  +'" width="40" height="40" class="rounded-circle mr-1" alt="">';
                                additionHtml += "</div>";
                                additionHtml += '<div class="media-body">';
                                    additionHtml += "<p>"+ item.name +"</p>";
                                additionHtml += "</div>";
                            additionHtml += "</div>";
                        additionHtml += "</div>";
                    })

                    $('<div class="modal fade" id="' +$dialogName +'" tabindex="-1">' +
                            '<div class="modal-dialog modal-sm">' +
                                '<div class="modal-content border-info">' +
                                    '<div class="modal-header">' +
                                        '<button type="button" class="bootbox-close-button close" data-dismiss="modal" aria-hidden="true">×</button>' +
                                    "</div>" +
                                    '<div class="modal-body" style="max-height: 450px; overflow:auto">'+
                                       additionHtml +
                                    "</div>" +
                                "</div>" +
                            "</div>" +
                        "</div>"
                    ).appendTo("body");

                    var $dialog = $("#" + $dialogName);
                    $dialog.modal({
                        show: false,
                        keyboard: false,
                        backdrop: "static",
                    });
                    $(".modal-dialog").draggable({
                        handle: ".modal-header, .modal-footer",
                    });
                    $dialog.on("shown.bs.modal", function () {
                    });
                    $dialog.on("hidden.bs.modal", function () {
                        $dialog.remove();
                    });
                    $dialog.modal("show");
                },
            });
        };

    </script>
    
<?php } catch (Exception $ex) {
    var_dump($ex); 
} ?>