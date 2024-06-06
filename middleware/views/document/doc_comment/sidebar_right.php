<div class="sidebar sidebar-light sidebar-right sidebar-expand-md ecommerce-right-sidebar d-block height-scroll" id="sidebarRight-<?php echo $this->uniqid ?>">
    <div class="sidebar-content">
        <div>
            <div class="title-right header-elements-inline">
                <div class="list_name">
                    <span>
                        <i class="icon-task mr-1 small"></i>
                    </span>
                    <span>Үйлдэл</span>
                </div>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-toggle="collapse" href="#card-collapse-options" role="button" aria-expanded="false" aria-controls="card-collapse-options">
                            <i class="icon-chevron-down"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div>
                <div id="card-collapse">
                    <div class="workflow-buttons">
                        <a class="btn btn-sm" onclick="sidebarRefreshCustom_<?php echo $this->rowId ?>()" href="javascript:;">
                            <i class="icon-database-refresh"></i>Дахин ачааллах</a>
                    </div>
                    <div class="workflow-buttons workflow-buttons-<?php echo $this->uniqid; ?>"></div>
                    <div class="workflow-buttons">

                        <?php if (($this->getRow['wfmstatusid'] == 16100320577176 || $this->getRow['wfmstatusid'] == 16100320577076 || $this->getRow['wfmstatusid'] == 1520321008250590 || $this->getRow['wfmstatusid'] == 1551934286116338 || $this->getRow['wfmstatusid'] == 1551168339725918 || $this->getRow['wfmstatusid'] == 1560682229378955) && ($this->getRow['ismylasttransferaction'] == 1)) { ?>
                            <a class="btn btn-sm" onclick="runProcessFromDetail('DOC_ACTION_TRANSFER_DOWN_EDIT', '1582258946134', <?php echo $this->rowId; ?>,
                            'id=<?php echo $this->rowId; ?>&documentcode=<?php echo $this->getRow['documentcode']; ?>&documentname=<?php echo $this->getRow['documentname']; ?>&wfmstatusid=<?php echo $this->getRow['wfmstatusid']; ?>&assignuserid=<?php echo $this->getRow['assignuserid']; ?>&currentuserid=<?php echo $this->getRow['currentuserid']; ?>&directionid=<?php echo $this->getRow['directionid']; ?>');" href="javascript:;">
                                <i class="fa fa-edit"></i> Шилжүүлэг засах</a>
                        <?php } ?>

                        <?php if (($this->getRow['wfmstatusid'] == 16100320577176 || $this->getRow['wfmstatusid'] == 16100320577076 || $this->getRow['wfmstatusid'] == 1520321008250590 || $this->getRow['wfmstatusid'] == 1551934286116338 || $this->getRow['wfmstatusid'] == 1551168339725918 || $this->getRow['wfmstatusid'] == 1560682229378955) && ($this->getRow['ismylasttransferaction'] == 1)) { ?>
                            <a class="btn btn-sm" onclick="runProcessFromDetail('DOC_LIST_TULUVLULT_DOCUMENT', '1577698588765', <?php echo $this->rowId; ?>,
                            'id=<?php echo $this->rowId; ?>&documentcode=<?php echo $this->getRow['documentcode']; ?>&documentname=<?php echo $this->getRow['documentname']; ?>&wfmstatusid=<?php echo $this->getRow['wfmstatusid']; ?>&assignuserid=<?php echo $this->getRow['assignuserid']; ?>&currentuserid=<?php echo $this->getRow['currentuserid']; ?>&directionid=<?php echo $this->getRow['directionid']; ?>');" href="javascript:;">
                                <i class="fa fa-ban"></i> Шилжүүлэг цуцлах</a>
                        <?php } ?>

                        <?php
                        if (issetParam($this->getRow['hideregistrationcard']) !== '1') {
                            if (isset($this->sideButtonConf['docDocumentWfmHistory']) && $this->sideButtonConf['docDocumentWfmHistory']['VALUE'] == 1) { ?>
                                <a class="btn btn-sm" data-advanced-criteria="" onclick="popupRegistrationCard_<?php echo $this->uniqid ?>(1562320061554, 1563518701221);" href="javascript:;">
                                    <i class="fa fa-refresh"></i> Бүртгэл хяналтын карт</a>
                        <?php }
                        }
                        ?>
                        <?php if (isset($this->sideButtonConf['docDocumentWfmHistory']) && $this->sideButtonConf['docDocumentWfmHistory']['VALUE'] == 1) { ?>
                            <?php if (isset($this->showPostponeHistory['documentcnt']) && $this->showPostponeHistory['documentcnt'] == '1') { ?>
                                <a class="btn btn-sm" data-advanced-criteria="" onclick="popupRegistrationCard_<?php echo $this->uniqid ?>(1567575160148, 1568623694892);" href="javascript:;">
                                    <i class="fa fa-refresh"></i> Хугацаа сунгасан түүх</a>
                            <?php } ?>
                        <?php } ?>

                        <?php if (issetParam($this->getRow['directionid']) !== '2' && (isset($this->sideButtonConf['docDocumentCard']) && $this->sideButtonConf['docDocumentCard']['VALUE'] == 1)) { ?>
                            <a class="btn btn-sm" onclick="runProcessFromDetail('ECM_COMMENT_DOC_PLAN_DV_001', '1551083998057', <?php echo $this->rowId; ?>, 'recordid=<?php echo $this->rowId; ?>');" href="javascript:;">
                                <i class="fa fa-list-alt"></i> Явцын тэмдэглэл</a>
                            <!-- runProcessFromDetail -->
                        <?php } ?>

                        <?php if (isset($this->sideButtonConf['docDocumentCard']) && $this->sideButtonConf['docDocumentCard']['VALUE'] == 1) { ?>
                            <a class="btn btn-sm" onclick="runProcessFromDetail('DOC_PLAN_DV_VIEW_002', '1596443148951', <?php echo $this->rowId; ?>, 'id=<?php echo $this->rowId; ?>&documentdate=<?php echo $this->getRow['documentdate']; ?>&documenttypeid=<?php echo $this->getRow['documenttypeid']; ?>&documentnumber=<?php echo $this->getRow['documentnumber']; ?>&documentname=<?php echo $this->getRow['documentname']; ?>');" href="javascript:;">
                                <i class="fa fa-eye"></i> Харах эрх олгох</a>
                            <!-- runProcessFromDetail -->
                        <?php } ?>

                        <?php if (isset($this->sideButtonConf['docDocumentResponse']) && $this->sideButtonConf['docDocumentResponse']['VALUE'] == 1) { ?>
                            <!-- <a class="btn btn-success btn-circle btn-sm" onclick="runProcessFromDetail('DOC_PLAN_DV_00111', '1552466548565', <?php echo $this->rowId; ?>,  'recordid=<?php echo $this->rowId; ?>');" href="javascript:;">
                            <i class="fa fa-mail-forward"></i> Хариу явуулах</a> -->
                        <?php } ?>
                    </div>
                </div>

                <?php if (isset($this->getRow['closeTypeId']) && !empty($this->getRow['closeTypeId'])) { ?>
                    <div class="collapse show" id="card-collapse">
                        <div class="card-header bg-transparent header-elements-inline">
                            <div class="table-responsive">
                                <table class="table table-striped double-td-width">
                                    <tbody>
                                        <tr>
                                            <td><i class="icon-file-text mr-2"></i> Хаасан хэлбэр:</td>
                                            <td><a href="javascript:void(0);"><?php echo Arr::get($this->getRow, 'closetypename'); ?></a></td>
                                        </tr>
                                        <tr>
                                            <td><i class="icon-files-empty mr-2"></i> Шийдсэн огноо:</td>
                                            <td><?php echo Arr::get($this->getRow, 'introducedate'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="icon-file-check mr-2"></i> Харилцсан утас:</td>
                                            <td><?php echo Arr::get($this->getRow, 'closephonenumber'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="icon-file-check mr-2"></i> Харилцсан имэйл:</td>
                                            <td><?php echo Arr::get($this->getRow, 'closeemail'); ?></td>
                                        </tr>
                                        <tr>
                                            <td><i class="icon-file-check mr-2"></i> Шийдвэрлэсэн байдал:</td>
                                            <td><?php echo Arr::get($this->getRow, 'description'); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="e-detailview-tab">
                    <ul class="nav nav-tabs nav-tabs-bottom mb-0">
                        <?php if (isset($this->getRow['doclogs']) && $this->getRow['doclogs']) { ?>
                            <li class="nav-item"><a href="#e-history-<?php echo $this->uniqid; ?>" class="nav-link active p-2" data-toggle="tab">Түүх </a></li>
                        <?php } ?>
                        <?php if (isset($this->sideButtonConf['docDocumentWfmHistory']) && $this->sideButtonConf['docDocumentWfmHistory']['VALUE'] == 1) { ?>
                            <li class="nav-item"><a href="#e-note-<?php echo $this->uniqid; ?>" class="nav-link p-2 doccomment-counter" data-toggle="tab">Явц/тэмдэглэл
                                    <?php if (isset($this->getRow['doccomments']) && $this->getRow['doccomments']) {
                                        echo '(' . sizeof($this->getRow['doccomments']) . ')';
                                    } ?>
                                </a></li>
                        <?php } ?>
                    </ul>
                    <div class="tab-content">
                        <?php if (isset($this->getRow['doclogs']) && $this->getRow['doclogs']) { ?>
                            <div class="tab-pane fade show active" id="e-history-<?php echo $this->uniqid; ?>">
                                <div id="card">
                                    <div class="card-body p-2">
                                        <ul class="media-list">
                                            <?php if (isset($this->getRow['doclogs']) && $this->getRow['doclogs']) {
                                                foreach ($this->getRow['doclogs'] as $key => $row) { ?>
                                                    <li class="media border-bottom-1 border-gray pb-1 mb-1 align-items-center" id="prepDivAudio_<?php echo $row['commentaudio']; ?>">
                                                        <div class="mr-2">
                                                            <?php
                                                            $col = ($row['wfmstatusid'] !== '1560685299762127') ? 'bg-primary' : 'red';
                                                            $styl = ($row['wfmstatusid'] == '1560685299762127') ? 'style="color: white;"' : '';
                                                            ?>
                                                            <button type="button" class="btn <?php echo $col; ?> btn-icon rounded-round">
                                                                <i class="<?php echo $row['actionicon']; ?>" <?php echo $styl; ?>></i>
                                                            </button>
                                                        </div>
                                                        <div class="media-body" style="text-overflow: clip; overflow: hidden;">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="line-height-normal">
                                                                    <a href="javascript:void(0);"><?php echo $row['username']; ?></a>
                                                                </span>

                                                                <?php if ($row['commentaudio'] != '0') { ?>
                                                                    <span class="text-center">
                                                                        <a id="prepAtAudio_<?php echo $row['commentaudio']; ?>" onclick="playAudioComment('<?php echo $row['commentaudio']; ?>')">
                                                                            <i class="fa fa-play-circle-o fa-2x" style="color: dodgerblue;"></i>
                                                                        </a>
                                                                    </span>
                                                                <?php } else { ?>
                                                                    <span class="text-center">
                                                                        <i class="icon-arrow-right7 text-muted"></i>
                                                                    </span>
                                                                <?php } ?>

                                                                <span class="line-height-normal">
                                                                    <a href="javascript:void(0);"><?php echo $row['username2']; ?></a>
                                                                </span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="font-size-12 text-muted mt2">
                                                                    <?php echo $row['createddate']; ?>
                                                                </span>
                                                                <span class="text-center">
                                                                    <?php if ($row['hasfile'] == '1') { ?>
                                                                        <a onclick="statusLogPdfPopUp('<?php echo $row['physicalpath']; ?>', '<?php echo $row['wfmstatusname']; ?> (<?php echo $row['username']; ?>) /<?php echo $row['createddate']; ?>/')">
                                                                            <i class="icon-file-pdf text-red font-size-18"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                </span>
                                                                <span class="font-size-12 text-muted mt2">
                                                                    <?php echo $row['createddate2']; ?>
                                                                </span>
                                                            </div>
                                                            <span class="font-size-12 text-muted line-height-normal d-flex flex-column">
                                                                <?php echo $row['assigncomment']; ?>
                                                            </span>
                                                        </div>
                                                    </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (isset($this->sideButtonConf['docDocumentWfmHistory']) && $this->sideButtonConf['docDocumentWfmHistory']['VALUE'] == 1) { ?>

                            <div class="tab-pane fade <?php if (!$this->getRow['doclogs']) {
                                                            echo 'show active';
                                                        } ?>" id="e-note-<?php echo $this->uniqid; ?>">
                                <div id="card">
                                    <div class="card-body p-2">
                                        <ul class="media-list">
                                            <?php if (isset($this->getRow['doccomments']) && $this->getRow['doccomments']) {
                                                foreach ($this->getRow['doccomments'] as $key => $row) { ?>
                                                    <li class="media border-bottom-1 border-gray pb-1 mb-1" id="prepDivAudio_<?php echo $row['commentaudio']; ?>">
                                                        <div class="mr-2">
                                                            <img src="assets/custom/img/user.png<?php //echo $row['picture']; 
                                                                                                ?>" class="rounded-circle" width="36" height="36">
                                                        </div>
                                                        <div class="media-body" style="text-overflow: clip; overflow: hidden;">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="line-height-normal">
                                                                    <a href="javascript:void(0);">
                                                                        <?php echo $row['username']; ?>
                                                                    </a>
                                                                </span>

                                                                <?php if ($row['commentaudio'] != '0') { ?>
                                                                    <span class="text-center">
                                                                        <a id="prepAtAudio_<?php echo $row['commentaudio']; ?>" onclick="playAudioComment('<?php echo $row['commentaudio']; ?>')">
                                                                            <i class="fa fa-play-circle-o fa-2x" style="color: dodgerblue;"></i>
                                                                        </a>
                                                                    </span>
                                                                <?php } ?>

                                                                <span class="font-size-12 text-muted mt2">
                                                                    <?php echo $row['createddate']; ?>
                                                                </span>
                                                            </div>
                                                            <span class="mr-2">
                                                                <i class="icon-comment text-orange mr-1"></i>
                                                                <?php if ($row['iscreated'] == '1') { ?>
                                                                    <a style="cursor: pointer;" class="mr-1" title="Засах" onclick="runProcessFromDetail('DOC_LIST_TULUVLULT_DOCUMENT222', '21553236799888', '<?php echo $row['commentid']; ?>', '');" href="javascript:;"><i class="far fa-pencil text-orange"></i></a>
                                                                    <a style="cursor: pointer;" class="" title="Устгах" onclick="deleteDocCommentById('<?php echo $row['commentid']; ?>', this);" href="javascript:;"><i class="far fa-trash text-danger"></i></a>
                                                                <?php } ?>
                                                            </span>
                                                            <span class="font-size-12 text-muted line-height-normal d-flex flex-column">
                                                                <?php echo $row['commenttext']; ?>
                                                            </span>
                                                        </div>
                                                    </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="e-time-<?php echo $this->uniqid; ?>">
                                <div id="card">
                                    <div class="card-body p-2">
                                        <ul class="media-list">
                                            <?php if (isset($this->getRow['docextenddates']) && $this->getRow['docextenddates']) {
                                                foreach ($this->getRow['docextenddates'] as $key => $row) { ?>
                                                    <li class="media border-bottom-1 border-gray pb-1 mb-1">
                                                        <div class="mr-2">
                                                            <img src="assets/custom/img/user.png" class="rounded-circle" width="36" height="36">
                                                        </div>
                                                        <div class="media-body" style="text-overflow: clip; overflow: hidden;">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="line-height-normal">
                                                                    <a href="javascript:void(0);">
                                                                        <?php echo $row['username']; ?>
                                                                    </a>
                                                                </span>
                                                                <span class="font-size-12 text-muted mt2">

                                                                </span>
                                                            </div>
                                                            <span class="font-size-12 text-muted line-height-normal d-flex flex-column">

                                                            </span>
                                                            <div class="d-flex flex-row mt-1 align-items-center">
                                                            </div>
                                                            <span class="font-size-12 text-muted line-height-normal">
                                                                <?php echo $row['olddate']; ?> <i class="icon-arrow-right7 text-muted"></i> <?php echo $row['newdate']; ?>
                                                            </span>
                                                        </div>
                                                    </li>
                                            <?php }
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>