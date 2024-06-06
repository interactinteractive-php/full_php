<div class="government_<?php echo $this->uniqId ?>">
    <link href="<?php echo autoVersion('middleware/assets/css/government/style.css'); ?>" rel="stylesheet"/>
    <div class="government overflow-hidden">
        <div class="d-flex align-items-start flex-column flex-md-row">
            <div class="order-2 order-md-1">
                <div class="card">
                    <div class="header-box">
                        <div class="d-flex align-items-center w-100">
                            <div class="col-1 pl-0">
                                <div class="duedate d-flex flex-column">
                                    <span class="year"><i class="icon-calendar"></i><br>2019-07-08</span>
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex flex-column">
                                    <h5 class="title">Garchig</h5>
                                    <span class="description">Turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul turul</span>
                                </div>
                            </div>
                            <div class="ml-auto float-right additionalbutton <?php echo (in_array('TAGNAME_000', $this->tagArr)) ? '' : 'd-none' ?>" tagname_000>
                                <?php
                                if (isset($this->mainData['buttonview'])) {
                                    $buttonParam = explode(', ', $this->mainData['buttonview']);
                                    foreach ($buttonParam as $key => $row) {
                                        $bParam = explode('#', $row);
                                        $className = ($bParam[0] === 'cancel' || $bParam[0] === 'ignore') ? 'cancel' : '';

                                        echo '<a href="javascript:;" '
                                        . 'onclick="drillDownTransferProcessActionCustom(this, \'' . $this->did . '\', \'' . (isset($bParam[1]) ? $bParam[1] : '') . '\')">'
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
                        </div>
                    </div>
                    <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline py-2">
                        <div>
                            <div class="<?php //echo (in_array('TAGNAME_002', $this->tagArr)) ? '' : 'd-none' ?> d-flex align-items-center header_icon_box tagname_002">
                                <div class="d-flex flex-row mr-4">
                                    <span class="text-muted mr-1">Шийдвэрийн төрөл:</span>
                                    <div class="font-weight-bold mb-0 d-flex flex-row align-items-center">
                                        <div>
                                            <i class="icon-file-empty font-size-14 mr-1 text-blue"></i>
                                        </div>
                                        <div class="text-blue">Засгийн газрын тогтоол</div>
                                        <?php //echo $this->mainData['reviewEndDate']; ?>
                                    </div>
                                </div>
                                <div class="d-flex flex-row">
                                    <span class="text-muted mr-1">Иргэнээс санал авах эсэх:</span>
                                    <div class="font-weight-bold mb-0 d-flex flex-row align-items-center">
                                        <div>
                                            <i class="icon-checkbox-checked font-size-14 mr-1 text-blue"></i>
                                        </div>
                                        <div class="text-blue">Тийм</div>
                                        <?php //echo $this->mainData['reviewEndDate']; ?>
                                    </div>
                                    <div class="font-weight-bold mb-0 d-flex flex-row align-items-center" style="display:none !important;">
                                        <div>
                                            <i class="icon-checkbox-partial font-size-14 mr-1 text-blue"></i>
                                        </div>
                                        <div class="text-blue">Үгүй</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="header_icon_box tagname_002">
                                <div class="d-flex flex-row">
                                    <span class="text-muted mr-1">Хавсралт файл:</span>
                                    <div class="font-weight-bold mb-0 d-flex flex-row align-items-center">
                                        <a href="javascript:void(0);">
                                            <i class="icon-file-pdf icon-2x mr-1 text-red"></i>
                                        </a>
                                        <a href="javascript:void(0);">
                                            <i class="icon-file-excel icon-2x mr-1 text-green"></i>
                                        </a>
                                        <a href="javascript:void(0);">
                                            <i class="icon-file-word icon-2x mr-1 text-blue"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-transparent">
                    <div class="header-box">
                        <div class="d-flex align-items-center w-100">
                            <div class="col-1 pl-0">
                                <div class="duedate d-flex flex-column">
                                    <span class="year"><i class="icon-chart"></i><br>Саналын явц</span>
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
                                                    <span class="text-green font-size-14">34%</span>
                                                </div>
                                                <div class="progress w-100 mr-2" style="height: 0.500rem;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: 34%;">
                                                        <span class="sr-only"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt13 ml-2 font-weight-bold font-size-16">0/8</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto pr-0">
                                    <div class="like-box d-flex flex-row align-items-center border-3 border-white">
                                        <span class="like-dislike">
                                            <i class="icon-thumbs-up2 mr-2 text-blue"></i>Дэмжсэн:<span class="ml-1 font-weight-bold">56</span>
                                        </span>
                                        <span class="like-dislike">
                                            <i class="icon-thumbs-down2 mr-2 text-orange"></i>Дэмжээгүй:<span class="ml-1 font-weight-bold">12</span>
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
                                            <p class="mb-0 font-size-16 text-black font-weight-bold">123</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-auto pr-0">
                                    <div class="like-box d-flex flex-row align-items-center border-3 border-white">
                                        <span class="like-dislike">
                                            <i class="icon-thumbs-up2 mr-2 text-blue"></i>Дэмжсэн:<span class="ml-1 font-weight-bold">56</span>
                                        </span>
                                        <span class="like-dislike">
                                            <i class="icon-thumbs-down2 mr-2 text-orange"></i>Дэмжээгүй:<span class="ml-1 font-weight-bold">12</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="card <?php echo (in_array('TAGNAME_005', $this->tagArr)) ? '' : 'd-none' ?> tagname_005">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-uppercase">Хавсралт файл</h6>
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
                                                        $icon = 'word';
                                                        $style = 'style="color: #2196f3;"';
                                                        break;
                                                    case 'xlsx':
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
                                                    <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_default<?php echo $i; ?>"><?php echo $file['filename']; ?></a>
                                                    <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
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
                                                    <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_default<?php echo $i; ?>"><?php echo $file['filename']; ?></a>
                                                    <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
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
                                                    <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_default<?php echo $i; ?>"><?php echo $file['filename']; ?></a>
                                                    <div id="modal_default<?php echo $i; ?>" class="modal fade" tabindex="-1">
                                                        <div class="modal-dialog modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"><?php echo $file['filename']; ?></h5>
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                </div>
                                                                <div class="modal-body">
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
                </div> -->
                <div class="card <?php echo (in_array('TAGNAME_006', $this->tagArr)) ? '' : 'd-none' ?> tagname_006">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-uppercase">Саналын товъёог</h6>
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li class="nav-item"><a href="#organization-tab" class="nav-link p-1 pl-4 pr-4 active show" data-toggle="tab"><i class="icon-office mr-1"></i> Яам</a></li>
                            <li class="nav-item"><a href="#user-tab" class="nav-link p-1 pl-4 pr-4" data-toggle="tab"><i class="icon-user mr-1"></i> Иргэн</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="organization-tab">
                                <div class="card-table table-responsive shadow-0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="font-weight-bold">#</th>
                                                <th class="font-weight-bold" style="width: 16%;">Яам, газар</th>
                                                <th class="font-weight-bold" style="width: 10%;">Санал өгсөн эсэх</th>
                                                <th class="font-weight-bold">Хавсралт файл</th>
                                                <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                                <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach($this->result6 as $row => $result6) {
                                                if(isset($result6['name']) && $result6['name']) { ?>
                                                    <tr style="cursor: pointer;" class="btn_add_class<?php echo $i; ?>" id="btn_add_class<?php echo $i; ?>" tr-status="0">
                                                        <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                        <td><span class="font-weight-bold"><?php echo $result6['departmentname']; ?></span></td>
                                                        <td>
                                                            <div class="d-inline-flex align-items-center mr30">
                                                                <span class="font-weight-semibold">
                                                                    <?php 
                                                                        if ($result6['name'] == 0) {
                                                                            echo "<button type='button' class='btn bg-green-600 btn-icon rounded-round'><i class='icon-checkmark'></i></button>";
                                                                        } else {
                                                                            echo "<button type='button' class='btn bg-pink-600 btn-icon rounded-round'><i class='icon-cross2 font-weight-bold'></i></button>";
                                                                        }
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <?php
                                                                    $fileExtArr2 = explode(' , ', $result6['attachfile']);
                                                                    $str10 = trim($result6['attachfilename']);
                                                                    $strArray10 = explode(',',$str10);
                                                                    $str20 = trim($result6['attachfile']);
                                                                    $strArray20 = explode(',',$str20);
                                                                    $index2_ = 0;
                                                                    $iii = 100;
                                                                    foreach(array_combine($strArray10, $strArray20) as $str10 => $str20) {
                                                                        $fileExtension = strtolower(substr($fileExtArr2[$index2_], strrpos($fileExtArr2[$index2_], '.') + 1));
                                                                        if($fileExtension == 'pptx') {
                                                                            echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-openoffice text-success-400 icon-2x mr-2' style='color: #f44336;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" .$str10. "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" .$str20. "' width='100%' height='550px' frameborder='0'></iframe>
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" .$str20. "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        } elseif($fileExtension == 'docx') {
                                                                            echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-word text-success-400 icon-2x mr-2' style='color: #2196f3;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" .$str10. "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='".CONFIG_FILE_VIEWER_ADDRESS."DocEdit.aspx?showRb=0&url=".URL.$str20."' frameborder='0' style='width: 100%;height: 760px !important;'></iframe>
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" .$str20. "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        } elseif($fileExtension == 'xlsx') {
                                                                            echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-excel text-success-400 icon-2x mr-2' style='color: #26a69a;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" .$str10. "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='".CONFIG_FILE_VIEWER_ADDRESS."SheetEdit.aspx?showRb=0&url=".URL.$str20."' frameborder='0' style='width: 100%;height: 760px !important;'></iframe>
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" .$str20. "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        } elseif($fileExtension == 'pdf') {
                                                                            echo "<a href='javascript:void(0);' class='text-default font-weight-bold media-title font-weight-semibold mb-0' style='line-height: normal;' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-pdf text-success-400 icon-2x mr-2' style='color: #CC0000;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" . $str10 . "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='".URL."api/pdf/web/viewer.html?file=".URL.$str10."' frameborder='0' style='width: 100%;height: 760px;' id='iframe-detail-".$this->uniqId."'></iframe>        
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" . $str20 . "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        }
                                                                        $iii++;
                                                                        $index2_++;
                                                                } ?>
                                                            </div>
                                                        </td>
                                                        <td><?php echo '<p class="text-justify">' . $result6['description'] . '</p>'; ?></td>
                                                        <td><?php echo '<p class="text-justify">' . $result6['reflection'] . '</p>'; ?></td>
                                                    </tr>
                                                    <script type="text/javascript">
                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
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
                            <div class="tab-pane fade" id="user-tab">
                                <div class="card-table table-responsive shadow-0">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="font-weight-bold">#</th>
                                                <th class="font-weight-bold" style="width: 16%;">Яам, газар</th>
                                                <th class="font-weight-bold" style="width: 10%;">Санал өгсөн эсэх</th>
                                                <th class="font-weight-bold">Хавсралт файл</th>
                                                <th class="font-weight-bold" style="width: 28%;">Санал</th>
                                                <th class="font-weight-bold" style="width: 28%;">Тусгасан байдал</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach($this->result6 as $row => $result6) {
                                                if(isset($result6['name']) && $result6['name']) { ?>
                                                    <tr style="cursor: pointer;" class="btn_add_class<?php echo $i; ?>" id="btn_add_class<?php echo $i; ?>" tr-status="0">
                                                        <td class="font-weight-bold" style="color: #2196f3;"><?php echo $i; ?>.</td>
                                                        <td><span class="font-weight-bold"><?php echo $result6['departmentname']; ?></span></td>
                                                        <td>
                                                            <div class="d-inline-flex align-items-center mr30">
                                                                <span class="font-weight-semibold">
                                                                    <?php 
                                                                        if ($result6['name'] == 0) {
                                                                            echo "<button type='button' class='btn bg-green-600 btn-icon rounded-round'><i class='icon-checkmark'></i></button>";
                                                                        } else {
                                                                            echo "<button type='button' class='btn bg-pink-600 btn-icon rounded-round'><i class='icon-cross2 font-weight-bold'></i></button>";
                                                                        }
                                                                    ?>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <?php
                                                                    $fileExtArr2 = explode(' , ', $result6['attachfile']);
                                                                    $str10 = trim($result6['attachfilename']);
                                                                    $strArray10 = explode(',',$str10);
                                                                    $str20 = trim($result6['attachfile']);
                                                                    $strArray20 = explode(',',$str20);
                                                                    $index2_ = 0;
                                                                    $iii = 100;
                                                                    foreach(array_combine($strArray10, $strArray20) as $str10 => $str20) {
                                                                        $fileExtension = strtolower(substr($fileExtArr2[$index2_], strrpos($fileExtArr2[$index2_], '.') + 1));
                                                                        if($fileExtension == 'pptx') {
                                                                            echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-openoffice text-success-400 icon-2x mr-2' style='color: #f44336;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" .$str10. "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=" .$str20. "' width='100%' height='550px' frameborder='0'></iframe>
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" .$str20. "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        } elseif($fileExtension == 'docx') {
                                                                            echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-word text-success-400 icon-2x mr-2' style='color: #2196f3;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" .$str10. "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='".CONFIG_FILE_VIEWER_ADDRESS."DocEdit.aspx?showRb=0&url=".URL.$str20."' frameborder='0' style='width: 100%;height: 760px !important;'></iframe>
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" .$str20. "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        } elseif($fileExtension == 'xlsx') {
                                                                            echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-excel text-success-400 icon-2x mr-2' style='color: #26a69a;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" .$str10. "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='".CONFIG_FILE_VIEWER_ADDRESS."SheetEdit.aspx?showRb=0&url=".URL.$str20."' frameborder='0' style='width: 100%;height: 760px !important;'></iframe>
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" .$str20. "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        } elseif($fileExtension == 'pdf') {
                                                                            echo "<a href='javascript:void(0);' class='text-default font-weight-bold media-title font-weight-semibold mb-0' style='line-height: normal;' data-toggle='modal' data-target='#modal_default200". $iii .''.$i."'><i class='icon-file-pdf text-success-400 icon-2x mr-2' style='color: #CC0000;'></i></a>
                                                                            <div id='modal_default200" . $iii .''.$i. "' class='modal fade' tabindex='-1'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <h5 class='modal-title'>" . $str10 . "</h5>
                                                                                            <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <iframe src='".URL."api/pdf/web/viewer.html?file=".URL.$str10."' frameborder='0' style='width: 100%;height: 760px;' id='iframe-detail-".$this->uniqId."'></iframe>        
                                                                                        </div>
                                                                                        <div class='modal-footer'>
                                                                                            <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                            <a href='" . $str20 . "' class='btn bg-primary'>Татаж авах</a>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>";
                                                                        }
                                                                        $iii++;
                                                                        $index2_++;
                                                                } ?>
                                                            </div>
                                                        </td>
                                                        <td><?php echo '<p class="text-justify">' . $result6['description'] . '</p>'; ?></td>
                                                        <td><?php echo '<p class="text-justify">' . $result6['reflection'] . '</p>'; ?></td>
                                                    </tr>
                                                    <script type="text/javascript">
                                                        $('#btn_add_class<?php echo $i; ?>').click(function() {
                                                            if ($(this).attr('tr-status') === '0') {
                                                                $(this).addClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '1');
                                                            } else {
                                                                $(this).removeClass("heightauto<?php echo $i; ?>");
                                                                $(this).attr('tr-status', '0');
                                                            }
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
                <div class="card <?php echo (in_array('TAGNAME_007', $this->tagArr)) ? '' : 'd-none' ?> tagname_007">
                    <div class="card-body">
                        <h6 class="font-weight-bold text-uppercase">Саналын товъёог файл</h6>
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
                                                        $icon = 'word';
                                                        $style = 'style="color: #2196f3;"';
                                                        break;
                                                    case 'xlsx':
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
                                                    <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>
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
                                                    <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>
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
                                                    <a href="javascript:void(0);" class="text-default font-weight-bold media-title font-weight-semibold mb-0" style="line-height: normal;word-break: break-all;" data-toggle="modal" data-target="#modal_defaultt<?php echo $i; ?>"><?php echo $result8['filename']; ?></a>
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
                <div class="card <?php echo (in_array('TAGNAME_008', $this->tagArr)) ? '' : 'd-none' ?> tagname_008">
                    
                    <div class="card-body">
                        <h6 class="font-weight-bold text-uppercase">Асуудлын шийдвэрлэлт</h6>
                        <ul class="nav nav-tabs nav-tabs-bottom border-bottom-0 ">
                            <?php foreach ($this->result7 as $key => $row) {
                                if (isset($row['id']) && $row['id']) {  ?>
                                    <li class="nav-item"><a href="#forum_<?php echo $row['mapid']; ?>" class="nav-link font-weight-bold text-uppercase font-size-12 pt-2 pb-2 pl-10 pr-10 <?php echo ($key === 0) ? 'active show' : '' ?>" data-toggle="tab"><?php echo $row['decisiontypename']; ?></a></li>
                            <?php }
                            } ?>
                        </ul>
                        <div class="tab-content">
                            <?php 
                            $n = 1;
                            foreach ($this->result7 as $row => $result7) { 
                                if (isset($result7['id']) && $result7['id']) { ?>
                                    <div class="tab-pane fade <?php echo ($row === 0) ? 'active show' : '' ?>" id="forum_<?php echo $result7['mapid']; ?>">
                                        <table class="table table-sm table-no-bordered bp-header-param">
                                            <tbody>
                                                <tr data-cell-path="name">                                            
                                                    <td class="text-right middle" data-cell-path="name" style="width: 10%">
                                                        <label for="param[name]" data-label-path="name">№:</label>
                                                    </td>
                                                    <td class="middle" data-cell-path="name" style="width: 55%">
                                                        <div data-section-path="name">
                                                            <input class="form-control form-control-sm " disabled="disabled"  value="<?php echo isset($result7['text_1']) ? $result7['text_1'] : '' ?>" spellcheck="false" data-isclear="0" style="width: 113px" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr data-cell-path="name">                                            
                                                    <td class="text-right middle" data-cell-path="name" style="width: 10%">
                                                        <label for="param[name]" data-label-path="name">Огноо:</label>
                                                    </td>
                                                    <td class="middle" data-cell-path="name" style="width: 55%">
                                                        <div data-section-path="name">
                                                            <input class="form-control form-control-sm " disabled="disabled" value="<?php echo isset($result7['date_1']) ? $result7['date_1'] : '' ?>" spellcheck="false" data-isclear="0" style="width: 113px" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr data-cell-path="name">                                            
                                                    <td class="text-right middle" data-cell-path="name" style="width: 10%">
                                                        <label for="param[name]" data-label-path="name">Шийдвэр:</label>
                                                    </td>
                                                    <td class="middle" data-cell-path="name" style="width: 55%">
                                                        <div data-section-path="name">
                                                            <input class="form-control form-control-sm " disabled="disabled"  value="<?php echo isset($result7['text_2']) ? $result7['text_2'] : '' ?>" spellcheck="false" data-isclear="0" style="width: 50%" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="row mt-4">
                                            <?php
                                            $fileExtArr = explode(' , ', $result7['attachfile']);
                                            $str = trim($result7['attachfilename']);
                                            $strArray = explode(',', $str);
                                            $str2 = trim($result7['attachfile']);
                                            $strArray2 = explode(',', $str2);
                                            $index_ = 0;
                                            $n = 1;
                                            foreach (array_combine($strArray, $strArray2) as $str => $str2) {
                                                ?>
                                                <div class="col-lg-4">
                                                    <div class="card card-body" style="border: 1px solid #e0e0e0;">
                                                        <div class="d-flex align-items-center">
                                                            <?php
                                                            $fileExtension = strtolower(substr($fileExtArr[$index_], strrpos($fileExtArr[$index_], '.') + 1));
                                                            if ($fileExtension == 'pptx') {
                                                                echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default100" . $n . "'><i class='icon-file-openoffice text-success-400 icon-2x mr-2' style='color: #f44336;'></i>" . $str . "</a>
                                                                                <div id='modal_default100" . $n . "' class='modal fade' tabindex='-1'>
                                                                                    <div class='modal-dialog modal-lg'>
                                                                                        <div class='modal-content'>
                                                                                            <div class='modal-header'>
                                                                                                <h5 class='modal-title'>" . $str . "</h5>
                                                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                            </div>
                                                                                            <div class='modal-body'>
                                                                                                <iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?>" . $str2 . "' width='100%' height='550px' frameborder='0'></iframe>
                                                                                            </div>
                                                                                            <div class='modal-footer'>
                                                                                                <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                                <a href='" . $str2 . "' class='btn bg-primary'>Татаж авах</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>";
                                                            } elseif ($fileExtension == 'docx') {
                                                                echo "<a href='javascript:void(0);' class='text-default font-weight-bold media-title font-weight-semibold mb-0' style='line-height: normal;' data-toggle='modal' data-target='#modal_default100" . $n . "'><i class='icon-file-word text-success-400 icon-2x mr-2' style='color: #2196f3;'></i>" . $str . "</a>
                                                                                <div id='modal_default100" . $n . "' class='modal fade' tabindex='-1'>
                                                                                    <div class='modal-dialog modal-lg'>
                                                                                        <div class='modal-content'>
                                                                                            <div class='modal-header'>
                                                                                                <h5 class='modal-title'>" . $str . "</h5>
                                                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                            </div>
                                                                                            <div class='modal-body'>
                                                                                                <!--<iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?>" . $str2 . "' width='100%' height='550px' frameborder='0'></iframe>-->
                                                                                                <iframe src='".CONFIG_FILE_VIEWER_ADDRESS."'DocEdit.aspx?showRb=0&url='".URL.$str2."' frameborder='0' style='width: 100%;height: 760px !important;'></iframe>
                                                                                            </div>
                                                                                            <div class='modal-footer'>
                                                                                                <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                                <a href='" . $str2 . "' class='btn bg-primary'>Татаж авах</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>";
                                                            } elseif ($fileExtension == 'xlsx') {
                                                                echo "<a href='javascript:void(0);' data-toggle='modal' data-target='#modal_default100" . $n . "'><i class='icon-file-excel text-success-400 icon-2x mr-2' style='color: #26a69a;'></i>" . $str . "</a>
                                                                                <div id='modal_default100" . $n . "' class='modal fade' tabindex='-1'>
                                                                                    <div class='modal-dialog modal-lg'>
                                                                                        <div class='modal-content'>
                                                                                            <div class='modal-header'>
                                                                                                <h5 class='modal-title'>" . $str . "</h5>
                                                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                            </div>
                                                                                            <div class='modal-body'>
                                                                                                <!--<iframe src='https://view.officeapps.live.com/op/view.aspx?src=<?php echo URL; ?>" . $str2 . "' width='100%' height='550px' frameborder='0'></iframe>-->
                                                                                                <iframe src='".CONFIG_FILE_VIEWER_ADDRESS."'SheetEdit.aspx?showRb=0&url='".URL.$str2."' frameborder='0' style='width: 100%;height: 760px !important;'></iframe>
                                                                                            </div>
                                                                                            <div class='modal-footer'>
                                                                                                <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                                <a href='" . $str2 . "' class='btn bg-primary'>Татаж авах</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>";
                                                            } elseif ($fileExtension == 'pdf') {
                                                                echo "<a href='javascript:void(0);' class='text-default font-weight-bold media-title font-weight-semibold mb-0' style='line-height: normal;' data-toggle='modal' data-target='#modal_default100" . $n . "'><i class='icon-file-pdf text-success-400 icon-2x mr-2' style='color: #CC0000;'></i>" . $str . "</a>
                                                                                <div id='modal_default100" . $n . "' class='modal fade' tabindex='-1'>
                                                                                    <div class='modal-dialog modal-lg'>
                                                                                        <div class='modal-content'>
                                                                                            <div class='modal-header'>
                                                                                                <h5 class='modal-title'>" . $str . "</h5>
                                                                                                <button type='button' class='close' data-dismiss='modal'>&times;</button>
                                                                                            </div>
                                                                                            <div class='modal-body'>
                                                                                                <!--<iframe src='" . $str2 . "' style='width:100%; height:550px;' frameborder='0'></iframe>-->
                                                                                                <iframe src='".URL."api/pdf/web/viewer.html?file=".URL.$str2."' frameborder='0' style='width: 100%;height: 760px;'></iframe>
                                                                                            </div>
                                                                                            <div class='modal-footer'>
                                                                                                <button type='button' class='btn btn-link closebtn' data-dismiss='modal'>Хаах</button>
                                                                                                <a href='" . $str2 . "' class='btn bg-primary'>Татаж авах</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>";
                                                            }
                                                            $n++;
                                                            $index_++;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
    <?php } ?>

                                        </div>
                                    </div>
                                <?php }
                                $n++;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right wmin-350 border-0 shadow-0 order-1 order-md-2 sidebar-expand-md <?php echo (in_array('TAGNAME_012', $this->tagArr)) ? '' : 'd-none' ?> tagname_012">
                <div class="sidebar-content">
                    <div class="card <?php echo (in_array('TAGNAME_009', $this->tagArr)) ? '' : 'd-none' ?> tagname_009">
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
                            <span class="text-uppercase font-weight-bold">Ажлын хэсэг</span>
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
    </div>
    <div class="clearfix w-100"></div>
</div>
<style type="text/css">
    .government_<?php echo $this->uniqId ?> .d-none {
        display: none !important;
    }
</style>
<script type="text/javascript">

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    function drillDownTransferProcessActionCustom(elem, did, processId) {
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
            url: 'mdasset/government',
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

</script>