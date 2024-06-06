<div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md ecommerce-left-sidebar" style="min-width: 320px;height:100vh;" id="sidebarLeft-<?php echo $this->uniqid ?>">
    <div class="sidebar-content">
        <div class="card-body p-0">
            <div class="mb-2">
                <ul class="nav nav-tabs nav-tabs-bottom mb-0">
                    <li class="nav-item"><a href="#e-general-<?php echo $this->uniqid; ?>" class="nav-link active p-2" data-toggle="tab"><span><i class="icon-info22"></i></span> Үндсэн мэдээлэл</a></li>
                    <?php if (isset($this->getRow['ecmcontentmap']) && $this->getRow['ecmcontentmap']) { ?>
                        <li class="nav-item"><a href="#e-attach-file-<?php echo $this->uniqid; ?>" class="nav-link p-2" data-toggle="tab">
                            <span><i class="icon-attachment"></i></span> Хавсралт файл (<?php echo sizeof($this->getRow['ecmcontentmap']) ?>)</a></li>
                    <?php } ?>
                </ul>
                <!-- <ul class="nav nav-tabs nav-tabs-bottom nav-justified mb-0">
                    <li class="nav-item d-flex flex-row align-items-center">
                        <a href="javascript:;" class="nav-link v2 active-hide" data-toggle="tab">
                            <span><i class="icon-info22 mr-1"></i></span>
                            <span>Үндсэн мэдээлэл</span>
                        </a>
                    </li>
                </ul> -->
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="e-general-<?php echo $this->uniqid; ?>">
                        <div id="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table double-td-width">
                                        <tbody>
                                            <tr>
                                                <td colspan="3"><i class="icon-files-empty mr-1"></i> Нэр төрөл:</td>
                                                <td colspan="4" ><?php echo Arr::get($this->getRow, 'documenttypename'); ?></td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><i class="icon-file-plus mr-1"></i> Бүртгэлийн дугаар:</td>
                                                <td colspan="4" >
                                                    <a href="javascript:void(0);">
                                                        <?php if (!empty(Arr::get($this->getRow, 'documentcode'))) {
                                                            echo Arr::get($this->getRow, 'documentcode');
                                                            } else {
                                                                echo "&nbsp;";
                                                            }
                                                        ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3"><i class="icon-file-plus mr-1"></i> Баримтын №:</td>
                                                <td colspan="4" >
                                                    <a href="javascript:void(0);">
                                                        <?php if (!empty(Arr::get($this->getRow, 'documentnumber'))) {
                                                            echo Arr::get($this->getRow, 'documentnumber');
                                                            } else {
                                                                echo "&nbsp;";
                                                            }
                                                        ?>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php if(!empty($this->getRow['customerlist'])){ ?>
                                                <tr>
                                                    <td colspan="3" rowspan="<?php echo sizeof($this->getRow['customerlist']); ?>"><i class="icon-file-eye mr-1"></i> Хаана:</td>
                                                    <?php foreach ($this->getRow['customerlist'] as $key => $cus) { ?>
                                                        <td colspan="4" ><?php echo Arr::get($cus, 'customername'); ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>    

                                            <?php if(!empty($this->getRow['directionid'] !== '3')){ ?>
                                            <tr>
                                                <td colspan="3"><i class="icon-file-eye mr-1"></i> Харилцагч:</td>
                                                <td colspan="4" ><?php echo Arr::get($this->getRow, 'customername'); ?></td>
                                            </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="3"><i class="fa fa-calendar-o mr-1"></i> Огноо:</td>
                                                <td colspan="4" ><?php echo Arr::get($this->getRow, 'createddate'); ?></td>
                                            </tr>
                                            <?php if(!empty($this->getRow['directionid'] == '3')){ ?>
                                                <tr>
                                                    <td colspan="3"><i class="fa fa-clone mr-1"></i> Боловсруулсан:</td>
                                                    <td colspan="4" ><?php echo Arr::get($this->getRow, 'developedname'); ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><i class="icon-file-eye mr-1"></i>  Хянасан:</td>
                                                    <td colspan="4" ><?php echo Arr::get($this->getRow, 'reviewedname'); ?> </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3"><i class="fa fa-pencil mr-1"></i> Гарын үсэг зурсан:</td>
                                                    <td colspan="4" ><?php echo Arr::get($this->getRow, 'signedname'); ?></td>
                                                </tr>
                                            <?php } ?>

                                            <tr>
                                                <?php if($this->getRow['isneedreply'] == 1){ ?>
                                                <td colspan="3"><i class="fa fa-calendar-o mr-1"></i> Хариуны огноо:</td>
                                                <td colspan="4" class="font-weight-bold">
                                                    <?php if(isset($this->getRow['docextenddates']) && sizeof($this->getRow['docextenddates']) > 0){
                                                        echo $this->getRow['docextenddates'][0]['olddate'];
                                                    }else{
                                                        echo $this->getRow['responsedate'];
                                                    } ?>
                                                </td>
                                                <?php } ?>
                                                    <?php 
                                                    if(isset($this->getRow['docextenddates']) && sizeof($this->getRow['docextenddates'])){
                                                    foreach ($this->getRow['docextenddates'] as $key => $val) { ?>
                                                    <tr>
                                                        <?php if($key == 0){ ?>
                                                            <td colspan="3"><i class="fa fa-clock-o mr-1"></i>Хугацаа сунгасан</td>
                                                        <?php }else{ ?>
                                                            <td colspan="3"></td>
                                                        <?php } ?>
                                                        <td colspan="4" class="font-weight-bold">
                                                            <span title="Хугацаа сунгасан"> <i class="fa icon-plus3 font-size-12" style="font-size:13px;color: palevioletred;"></i> </span>
                                                        <?php echo $val['newdate']; ?>
                                                        </td>
                                                    </tr>
                                                    <?php }} ?>
                                            </tr>

                                            <tr>
                                                <td colspan="3"><i class="icon-file-spreadsheet mr-1"></i> Тэргүү:</td>
                                                <td colspan="4" ><?php echo Arr::get($this->getRow, 'documentname'); ?></td>
                                            </tr>

                                            <?php if(!empty($this->getRow['docviewers'])){ ?>
                                                <tr>
                                                    <td colspan="3"><i class="icon-eye mr-1"></i> Бичиг харах эрхтэй:</td>
                                                        <td colspan="4" >
                                                        <?php foreach ($this->getRow['docviewers'] as $key => $viewers) { ?>
                                                            <?php echo Arr::get($viewers, 'username') . ', '; ?>
                                                        <?php } ?>
                                                        </td>
                                                </tr>
                                            <?php } ?>  
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="e-attach-file-<?php echo $this->uniqid; ?>">
                        <div id="card">
                            <div class="card-body p-2 pb0">
                                <?php if (isset($this->getRow['ecmcontentmap']) && $this->getRow['ecmcontentmap']) {
                                    foreach ($this->getRow['ecmcontentmap'] as $key => $row) { ?>
                                <li class="media border-bottom-1 border-gray d-flex align-items-center pb-1 mb-1">
                                    <div class="mr-2">
                                        <a><i class="icon-files-empty text-red font-size-22"></i></a>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="line-height-normal">
                                                <a><?php echo $row['ecmcontent']['filename']; ?></a>
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span title="Хүлээн авсан" class="font-size-12 text-muted mt2">
                                                <a download href="mdobject/downloadFile?fDownload=1&file=<?php echo $row['ecmcontent']['physicalpath']; ?>&fileName=<?php echo $row['ecmcontent']['filename']; ?>">
                                                Татах <?php 
                                                    $decimals = 2;
                                                    $bytes = $row['ecmcontent']['filesize'];
                                                    $sz = 'BKMGTP';
                                                    $factor = floor((strlen($bytes) - 1) / 3);
                                                    echo sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor]; 
                                                ?>
                                                </a>
                                            </span>

                                            <span title="Хүлээн авсан" class="font-size-12 text-muted mt2">
                                                <a href="javascript:;" onclick="statusLogPdfPopUp('<?php echo $row['ecmcontent']['physicalpath']; ?>', '<?php echo $row['ecmcontent']['filename']; ?>')">
                                                Харах
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                                <?php }} ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>