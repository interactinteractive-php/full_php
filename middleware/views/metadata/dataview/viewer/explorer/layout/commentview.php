<div id="commentview-<?php echo $this->dataViewId; ?>" class="commentview">
    <div id="commentview-board" class="u-fancy-scrollbar js-no-higher-edits js-list-sortable">
    <?php
    $cnt = count($this->recordList);
    if ($cnt && isset($this->recordList[0])) {
    ?>
        <div class="mh15 card-columns">
        <?php
            $firstRow = $this->recordList[0];
            
            if (array_key_exists('createduserid', $firstRow) && $this->nameLinkId1) {
                
                $sessionUserKeyId = Ue::sessionUserKeyId();
            }
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['picture'])) {
                $picture = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['picture']); 
            } else {
                $picture= '';
            }
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['backgroundColor'])) {
                $bgcolor = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['backgroundColor']); 
            }
            
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['color'])) {
                $textcolor = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['color']); 
            } else { 
                $textcolor = '#fff';
            }
            
            if (isset($this->dataViewProcessCommand['commandContext'][0]['PROCESS_META_DATA_ID'])) {
                $editProcessId = $this->dataViewProcessCommand['commandContext'][0]['PROCESS_META_DATA_ID'];
                $editProcessName = $this->dataViewProcessCommand['commandContext'][0]['PROCESS_NAME'];
            }
            
            foreach ($this->recordList as $recordRow) {
              
                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
                
                if (isset($bgcolor)) {
                    $bgitemcolor = isset($recordRow[$bgcolor]) ? $recordRow[$bgcolor] : 'bg-dark';
                } else {
                    $bgitemcolor = 'bg-dark';
                }
               
                $textitemcolor = isset($recordRow[$textcolor]) ? $recordRow[$textcolor] : '#fff';
                $userpic = isset($recordRow[$picture]) ? $recordRow[$picture] : 'assets/core/global/img/user.png';
               
                if (empty($recordRow['parentid'])) { 
            ?>
                    <div class="card commentnewlist" data-id="<?php echo $recordRow['id']; ?>" data-level="1">
                        <div class="media-body">
                            <div onclick="dvCommentViewlistDropDownClick(this, '<?php echo issetParam($recordRow['id']); ?>');" class="card-header <?php echo $bgitemcolor; ?> text-white d-flex justify-content-between align-items-sm-center" style="background-color:<?php echo $bgitemcolor; ?> !important" data-row-data="<?php echo $rowJson; ?>">
                                <h6 class="card-title d-flex align-items-sm-center lh-normal" style="color:<?php echo $textitemcolor; ?> !important">
                                    <img src="<?php echo $userpic; ?>" class="img-circle media-object mr-2 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:auto; height:35px"> <?php echo $recordRow['firstname']; ?>
                                </h6>
                                <span class="text-uppercase font-weight-semibold talign-right lh-normal"><?php echo $recordRow['createddate']; ?></span>
                            </div>
                            <div class="card-body">
                                <?php
                                if (isset($recordRow[$this->name2])) { 
                                    echo '<p class="card-text line-height-lg font-weight-bold">' . $recordRow[$this->name2] . '</p>';
                                }    

                                    $photoArr = array();
                                    $fileStr = '';

                                    if (isset($recordRow['attachment1'])) {
                                        
                                        $fileExtension = strtolower(substr($recordRow['attachment1'], strrpos($recordRow['attachment1'], '.') + 1));
                                        if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                            array_push($photoArr, $recordRow['attachment1']);
                                        } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                            $fileStr .= '<a href="mdobject/downloadFile?file=' . $recordRow['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                        } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                            $fileStr .= '<a href="mdobject/downloadFile?file=' . $recordRow['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';
                                        } elseif (in_array($fileExtension, array('pdf')) === true) {
                                            $fileStr .= ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$recordRow['attachment1'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                        } else {
                                            $fileStr .= ' <a href="mdobject/downloadFile?file=' . $recordRow['attachment1'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                        }
                                    }
                                    
                                    if (isset($recordRow['attachment2'])) {
                                        
                                        $fileExtension = strtolower(substr($recordRow['attachment2'], strrpos($recordRow['attachment2'], '.') + 1));
                                        if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                            array_push($photoArr, $recordRow['attachment2']);
                                        } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                            $fileStr .= ' <a href="mdobject/downloadFile?file=' . $recordRow['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                        } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                            $fileStr .= '<a href="mdobject/downloadFile?file=' . $recordRow['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                        } elseif (in_array($fileExtension, array('pdf')) === true) {
                                            $fileStr .= ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$recordRow['attachment2'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                        } else {
                                            $fileStr .= ' <a href="mdobject/downloadFile?file=' . $recordRow['attachment2'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                        }
                                    }
                                    
                                    if (isset($recordRow['attachment3'])) {
                                        $fileExtension = strtolower(substr($recordRow['attachment3'], strrpos($recordRow['attachment3'], '.') + 1));
                                        if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                            array_push($photoArr, $recordRow['attachment3']);
                                        } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                            $fileStr .= ' <a href="mdobject/downloadFile?file=' . $recordRow['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                        } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                            $fileStr .= '<a href="mdobject/downloadFile?file=' . $recordRow['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                        } elseif (in_array($fileExtension, array('pdf')) === true) {
                                            $fileStr .= ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$recordRow['attachment3'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                        } else {
                                            $fileStr .= ' <a href="mdobject/downloadFile?file=' . $recordRow['attachment3'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                        }
                                    }

                                    if (count($photoArr)) { 
                                ?>
                                        <div>
                                            <div id="imgcarousel<?php echo $recordRow['id'];?>" class="carousel slide" data-ride="carousel">
                                                <div class="carousel-inner">
                                                <?php foreach ($photoArr as $pKey => $pVal) { ?>
                                                    <div class="carousel-item <?php echo $pKey == 0 ? 'active' : ''; ?>">
                                                        <a href="javascript:;" class="aimg" data-rel="fancybox-button"><img src="<?php echo $pVal; ?>" class="img-responsive"></a>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                                <a class="carousel-control-prev" href="#imgcarousel<?php echo $recordRow['id'];?>" role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#imgcarousel<?php echo $recordRow['id'];?>" role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>                   
                                            <div class="mt15"></div>                                 
                                        </div>
                                    <?php } ?>                
                                <?php if (count($photoArr)) { ?>
                                <div class="card-footer pl-0 pr-0 bg-white">
                                <?php } ?>
                                    <div class="text-justify">
                                        <?php echo Str::nlTobr(html_entity_decode($recordRow['description']), ENT_QUOTES); ?> 
                                    </div>
                                    <div class="float-right mt-1">
                                        <?php 
                                        if (isset($editProcessId)) {
                                            echo html_tag('a', 
                                                array(
                                                    'href' => 'javascript:;', 
                                                    'class' => 'btn btn-primary btn-circle btn-sm', 
                                                    'onclick' => 'commentEditProcess(this, \''.$recordRow['id'].'\', \''.$editProcessId.'\');'
                                                ), 
                                                $editProcessName
                                            );
                                        }
                                        
                                        if (!empty($this->nameLinkId4)) { 
                                        ?>
                                            <a href="javascript:;" onclick="commentLinkProcess(this, '<?php echo $this->nameLinkId4; ?>', '<?php echo $recordRow['id']; ?>', '<?php echo issetParam($recordRow['recordid']); ?>')">Хариу бичих</a>
                                        <?php 
                                        }
                                        if (isset($sessionUserKeyId) && $sessionUserKeyId == $recordRow['createduserid']) {
                                        ?>
                                            <i class="fas fa-circle" style="font-size: 6px;color: #ababab;"></i>
                                            <a href="javascript:;" onclick="commentDeleteProcess(this, '<?php echo $this->nameLinkId1; ?>', '<?php echo $recordRow['id']; ?>');"><?php echo $this->lang->line('delete_btn'); ?></a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                <?php if (count($photoArr)) { ?>    
                                </div>
                                <?php } ?>
                                <div class="clearfix"></div>
                                <?php

                                echo $fileStr;
                                $hasChild = false;                                
                                for ($i = 0; $i < $cnt; $i++) {

                                    if (isset($this->recordList[$i]['parentid']) && isset($recordRow['id']) && $this->recordList[$i]['parentid'] == $recordRow['id']) {
                                        $hasChild = true;
                                        break;
                                    }
                                }

                                if ($hasChild) {
                                    ?>
                                    <div class="tab-content mt-2">
                                        <ul class="media-list">
                                            <?php
                                            for ($i = 0; $i < $cnt; $i++) {
                                              
                                                if ($this->recordList[$i]['parentid'] == $recordRow['id']) {
                                                    $rowJson = htmlentities(json_encode($this->recordList[$i]), ENT_QUOTES, 'UTF-8');
                                                    ?>
                                                    <li class="media border-top-1 border-gray pt-2" data-id="<?php echo $this->recordList[$i]['id']; ?>" data-level="2">
                                                        <div class="mr-1 position-relative">
                                                            <img src="<?php echo $this->recordList[$i]['picture']; ?>" class="media-object mr-1 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:35px; height:35px">
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="d-flex justify-content-between" data-row-data="<?php echo $rowJson; ?>">
                                                                <a href="javascript:void(0);" class="lh-normal"><?php echo $this->recordList[$i]['firstname']; ?></a>
                                                                <span class="font-size-sm text-muted talign-right"><?php echo $this->recordList[$i]['createddate']; ?></span>
                                                            </div>
                                                            <div class="lh-normal text-justify">
                                                                <?php echo Str::nlTobr(html_entity_decode($this->recordList[$i]['description']), ENT_QUOTES); ?>
                                                            </div>
                                                            <div class="float-right mt-1">
                                                                <?php
                                                                if (isset($editProcessId)) {
                                                                    echo html_tag('a', 
                                                                        array(
                                                                            'href' => 'javascript:;', 
                                                                            'class' => 'btn btn-primary btn-circle btn-sm', 
                                                                            'onclick' => 'commentEditProcess(this, \''.$this->recordList[$i]['id'].'\', \''.$editProcessId.'\');'
                                                                        ), 
                                                                        $editProcessName
                                                                    );
                                                                }
                                                                ?>
                                                                <a href="javascript:;" onclick="commentLinkProcess(this, '<?php echo $this->nameLinkId4; ?>', '<?php echo $this->recordList[$i]['id']; ?>', '<?php echo issetParam($this->recordList[$i]['recordid']); ?>')">Хариу бичих</a>
                                                                <?php
                                                                if (isset($sessionUserKeyId) && $sessionUserKeyId == $this->recordList[$i]['createduserid']) {
                                                                ?>
                                                                <i class="fas fa-circle" style="font-size: 6px;color: #ababab;"></i>
                                                                <a href="javascript:;" onclick="commentDeleteProcess(this, '<?php echo $this->nameLinkId1; ?>', '<?php echo $this->recordList[$i]['id']; ?>');"><?php echo $this->lang->line('delete_btn'); ?></a>
                                                                <?php
                                                                }
                                                                ?>
                                                            </div>
                                                            <?php
                                                            if (isset($this->recordList[$i]['attachment1'])) {
                                                                $fileExtension = strtolower(substr($this->recordList[$i]['attachment1'], strrpos($this->recordList[$i]['attachment1'], '.') + 1));
                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                ?>
                                                                    <a href="<?php echo $this->recordList[$i]['attachment1'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$i]['attachment1'] ?>"></a>
                                                            <?php 
                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';
                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$i]['attachment1'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                } else {
                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment1'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                }
                                                            }

                                                            if (isset($this->recordList[$i]['attachment2'])) {
                                                                $fileExtension = strtolower(substr($this->recordList[$i]['attachment2'], strrpos($this->recordList[$i]['attachment2'], '.') + 1));
                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                ?>
                                                                    <a href="<?php echo $this->recordList[$i]['attachment2'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$i]['attachment2'] ?>"></a>
                                                            <?php 
                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$i]['attachment2'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                } else {
                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment2'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                }
                                                            }

                                                            if (isset($this->recordList[$i]['attachment3'])) {
                                                                $fileExtension = strtolower(substr($this->recordList[$i]['attachment3'], strrpos($this->recordList[$i]['attachment3'], '.') + 1));
                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                ?>
                                                                    <a href="<?php echo $this->recordList[$i]['attachment3'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$i]['attachment3'] ?>"></a>
                                                            <?php 
                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$i]['attachment3'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                } else {
                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$i]['attachment3'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </li>
                                                    <?php

                                                    $hasChild2 = false;                                
                                                    for ($ii = 0; $ii < $cnt; $ii++) {
                                                        if ($this->recordList[$i]['id'] == $this->recordList[$ii]['parentid']) {
                                                            $hasChild2 = true;
                                                            break;
                                                        }
                                                    }                                                    
                                                    
                                                    if ($hasChild2) {
                                                        ?>
                                                        <?php
                                                        for ($ii = 0; $ii < $cnt; $ii++) {
                                                            if ($this->recordList[$i]['id'] == $this->recordList[$ii]['parentid']) {
                                                                $rowJson = htmlentities(json_encode($this->recordList[$ii]), ENT_QUOTES, 'UTF-8');
                                                                ?>
                                                                <li class="media ml45 mt-2 pt-2 border-top-1 border-gray" data-id="<?php echo $this->recordList[$ii]['id']; ?>" data-level="3" data-parent="-<?php echo $this->recordList[$i]['id']; ?>-">
                                                                    <div class="mr-1 position-relative">
                                                                        <img src="assets/custom/img/user.png" class="media-object mr-1 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:35px; height:35px">
                                                                    </div>
                                                                    <div class="media-body">
                                                                        <div class="d-flex justify-content-between" data-row-data="<?php echo $rowJson; ?>">
                                                                            <a href="javascript:void(0);" class="lh-normal"><?php echo $this->recordList[$ii]['firstname']; ?></a>
                                                                            <span class="font-size-sm text-muted talign-right"><?php echo $this->recordList[$ii]['createddate']; ?></span>
                                                                        </div>
                                                                        <div class="lh-normal">
                                                                            <div class="lh-normal text-justify">
                                                                                <?php echo html_entity_decode($this->recordList[$ii]['description'], ENT_QUOTES); ?>
                                                                            </div>
                                                                            <div class="float-right mt-1">
                                                                                <?php
                                                                                if (isset($editProcessId)) {
                                                                                    echo html_tag('a', 
                                                                                        array(
                                                                                            'href' => 'javascript:;', 
                                                                                            'class' => 'btn btn-primary btn-circle btn-sm', 
                                                                                            'onclick' => 'commentEditProcess(this, \''.$this->recordList[$ii]['id'].'\', \''.$editProcessId.'\');'
                                                                                        ), 
                                                                                        $editProcessName
                                                                                    );
                                                                                }
                                                                                ?>
                                                                                <a href="javascript:;" onclick="commentLinkProcess(this, '<?php echo $this->nameLinkId4; ?>', '<?php echo $this->recordList[$ii]['id']; ?>', '<?php echo issetParam($this->recordList[$ii]['recordid']); ?>')">Хариу бичих</a>
                                                                                <?php
                                                                                if (isset($sessionUserKeyId) && $sessionUserKeyId == $this->recordList[$ii]['createduserid']) {
                                                                                ?>
                                                                                <i class="fas fa-circle" style="font-size: 6px;color: #ababab;"></i>
                                                                                <a href="javascript:;" onclick="commentDeleteProcess(this, '<?php echo $this->nameLinkId1; ?>', '<?php echo $this->recordList[$ii]['id']; ?>');"><?php echo $this->lang->line('delete_btn'); ?></a>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                            <?php
                                                                            if (isset($this->recordList[$ii]['attachment1'])) {
                                                                                $fileExtension = strtolower(substr($this->recordList[$ii]['attachment1'], strrpos($this->recordList[$ii]['attachment1'], '.') + 1));
                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                                ?>
                                                                                    <a href="<?php echo $this->recordList[$ii]['attachment1'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$ii]['attachment1'] ?>"></a>
                                                                            <?php 
                                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';
                                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$ii]['attachment1'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                                } else {
                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment1'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                                }
                                                                            }

                                                                            if (isset($this->recordList[$ii]['attachment2'])) {
                                                                                $fileExtension = strtolower(substr($this->recordList[$ii]['attachment2'], strrpos($this->recordList[$ii]['attachment2'], '.') + 1));
                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                                ?>
                                                                                    <a href="<?php echo $this->recordList[$ii]['attachment2'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$ii]['attachment2'] ?>"></a>
                                                                            <?php 
                                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$ii]['attachment2'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                                } else {
                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment2'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                                }
                                                                            }

                                                                            if (isset($this->recordList[$ii]['attachment3'])) {
                                                                                $fileExtension = strtolower(substr($this->recordList[$ii]['attachment3'], strrpos($this->recordList[$ii]['attachment3'], '.') + 1));
                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                                ?>
                                                                                    <a href="<?php echo $this->recordList[$ii]['attachment3'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$ii]['attachment3'] ?>"></a>
                                                                            <?php 
                                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$ii]['attachment3'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                                } else {
                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$ii]['attachment3'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <?php
                                                                $hasChild3 = false;                                
                                                                for ($iii = 0; $iii < $cnt; $iii++) {
                                                                    if ($this->recordList[$ii]['id'] == $this->recordList[$iii]['parentid']) {
                                                                        $hasChild3 = true;
                                                                        break;
                                                                    }
                                                                }                                                    

                                                                if ($hasChild3) {
                                                                    ?>
                                                                    <div class="clearfix"></div>
                                                                    <div class="panel panel-default" style="margin-left: 40px" data-parent="-<?php echo $this->recordList[$i]['id'].'-'.$this->recordList[$ii]['id']; ?>-">
                                                                        <div class="panel-body">
                                                                            <?php
                                                                            for ($iii = 0; $iii < $cnt; $iii++) {
                                                                                if ($this->recordList[$ii]['id'] == $this->recordList[$iii]['parentid']) {
                                                                                    $rowJson = htmlentities(json_encode($this->recordList[$iii]), ENT_QUOTES, 'UTF-8');
                                                                                    ?>
                                                                                    <div data-id="<?php echo $this->recordList[$iii]['id']; ?>" data-level="4">
                                                                                    <a href="javascript:;" class="pull-left">
                                                                                        <img src="<?php echo $this->recordList[$iii]['picture']; ?>" class="media-object rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:35px; height:35px; margin-right: 10px">
                                                                                    </a>
                                                                                    <div class="comment-detail">
                                                                                        <div class="media-heading">
                                                                                            <div class="caption" data-row-data="<?php echo $rowJson; ?>">
                                                                                                <span class="caption-subject font-weight-bold portlet-subject-customed portlet-subject-customed-name">
                                                                                                    <?php echo $this->recordList[$iii]['firstname']; ?>
                                                                                                </span>
                                                                                                <span class="caption-subject portlet-subject-customed" style="padding-left: 2px"></span>
                                                                                                <span class="comment-date"><?php echo $this->recordList[$iii]['createddate']; ?></span>
                                                                                                <div style="margin-bottom: 6px;">
                                                                                                    <?php echo html_entity_decode($this->recordList[$iii]['description'], ENT_QUOTES); ?>
                                                                                                    
                                                                                                    <?php
                                                                                                    if (isset($sessionUserKeyId) && $sessionUserKeyId == $this->recordList[$iii]['createduserid']) {
                                                                                                    ?>
                                                                                                    <div class="clearfix"></div>
                                                                                                    <a href="javascript:;" onclick="commentDeleteProcess(this, '<?php echo $this->nameLinkId1; ?>', '<?php echo $this->recordList[$iii]['id']; ?>');" class="float-right"><?php echo $this->lang->line('delete_btn'); ?></a>
                                                                                                    <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="clearfix"></div>
                                                                                            <?php
                                                                                            if (isset($this->recordList[$iii]['attachment1'])) {
                                                                                                $fileExtension = strtolower(substr($this->recordList[$iii]['attachment1'], strrpos($this->recordList[$iii]['attachment1'], '.') + 1));
                                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                                                ?>
                                                                                                    <a href="<?php echo $this->recordList[$iii]['attachment1'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$iii]['attachment1'] ?>"></a>
                                                                                            <?php 
                                                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment1'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';
                                                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$iii]['attachment1'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                                                } else {
                                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment1'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                                                }
                                                                                            }

                                                                                            if (isset($this->recordList[$iii]['attachment2'])) {
                                                                                                $fileExtension = strtolower(substr($this->recordList[$iii]['attachment2'], strrpos($this->recordList[$iii]['attachment2'], '.') + 1));
                                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                                                ?>
                                                                                                    <a href="<?php echo $this->recordList[$iii]['attachment2'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$iii]['attachment2'] ?>"></a>
                                                                                            <?php 
                                                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment2'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$iii]['attachment2'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                                                } else {
                                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment2'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                                                }
                                                                                            }

                                                                                            if (isset($this->recordList[$iii]['attachment3'])) {
                                                                                                $fileExtension = strtolower(substr($this->recordList[$iii]['attachment3'], strrpos($this->recordList[$iii]['attachment3'], '.') + 1));
                                                                                                if (in_array($fileExtension, array('jpg', 'jpeg', 'png', 'gif')) === true) {
                                                                                                ?>
                                                                                                    <a href="<?php echo $this->recordList[$iii]['attachment3'] ?>" class="fancybox-button aimg" data-rel="fancybox-button"><img class="img-thumbnail media-object"  src="<?php echo $this->recordList[$iii]['attachment3'] ?>"></a>
                                                                                            <?php 
                                                                                                } elseif (in_array($fileExtension, array('xlsx', 'xls')) === true) {
                                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-excel-o" style=""></i></a>';
                                                                                                } elseif (in_array($fileExtension, array('doc', 'docx')) === true) {
                                                                                                    echo '<a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment3'] . '" class="" title="Файл татах"><i class="fa fa-file-word-o" style=""></i></a>';                                        
                                                                                                } elseif (in_array($fileExtension, array('pdf')) === true) {
                                                                                                    echo ' <a href="javascript:;" onclick="dataViewFileViewer(this, \'\', \''.$fileExtension.'\', \'\', \''.URL.$this->recordList[$iii]['attachment3'].'\');" class="" title=""><i class="fa fa-file-pdf-o" style=""></i></a>';
                                                                                                } else {
                                                                                                    echo ' <a href="mdobject/downloadFile?file=' . $this->recordList[$iii]['attachment3'] . '" title="Файл татах"><i class="fa fa-file" style=""></i></a>';
                                                                                                }
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
                                                                    </div>
                                                                <?php 
                                                                }                                                                      
                                                            }
                                                        }
                                                        ?>
                                                    <?php 
                                                    } 
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </div>
        <?php
        } elseif ($cnt && isset($this->recordList['message'])) {
            echo html_tag('div', array('class' => 'alert alert-info'), $this->recordList['message']);
        }
        ?>
    </div>
    <div class="lightbox">
        <figure></figure>
    </div>
</div>

<script type="text/javascript">

    $(function () {
        Core.initFancybox($("#commentview-<?php echo $this->dataViewId; ?>"));
        
        $("#commentview-<?php echo $this->dataViewId; ?>").closest(".row").find(".table-toolbar").find(".col-md-3").addClass("hidden");
        
        $(".commentview .fa-reply").click(function () {
            var commentTxt = $(this).parents(".media-body").find(".sub-comment");
            if (commentTxt.is(":visible")) {
                commentTxt.hide();
            } else {
                commentTxt.show();
            }
        });
        
        $("#commentview-<?php echo $this->dataViewId; ?>").on('click', '.caption', function(){
            var elem=this;
            var _this=$(elem);
            var _parent=_this.closest('.comment-list-section');
            _parent.find('.selected-row').removeClass('selected-row');
            _this.addClass('selected-row');
        });        

        $("#commentview-fileupload").click(function () {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpFileTab',
                beforeSend: function () {
                    if (!$("link[href='assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                        $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                    }
                    Core.blockUI({
                        animate: true
                    });
                },
                success: function (data) {
                    $("#file-upload-area").html(data);
                    Core.unblockUI();
                },
                error: function () {
                    Core.unblockUI();
                }
            });
        });

        $("#postComment").click(function () {
            var commentForm = $("#commentInputForm");
            commentForm.validate({
                ignore: "",
                highlight: function (element) {
                    $(element).addClass('error');
                    $(element).parent().addClass('error');
                },
                unhighlight: function (element) {
                    $(element).removeClass('error');
                    $(element).parent().removeClass('error');
                },
                errorPlacement: function () {}
            });
            if (commentForm.valid()) {
                commentForm.ajaxSubmit({
                    type: 'post',
                    url: 'mdwebservice/runProcess',
                    dataType: 'json',
                    beforeSend: function () {
                        if (!$("link[href='assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                            $("head").prepend('<link rel="stylesheet" type="text/css" href="assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                        }
                        Core.blockUI({
                            boxed: true,
                            message: 'Түр хүлээнэ үү'
                        });
                    },
                    success: function (responseData) {
                        Core.unblockUI();
                        location.reload();
                    },
                    error: function () {
                        Core.unblockUI();
                    }
                });
            }
        });

        var $galleryDv = $('.commentview'),
                $lightbox = $galleryDv.find('.lightbox'),
                $figure = $lightbox.find('figure');

        $galleryDv.find('.img-thumbnail').on('click', function () {
            var full = $(this).attr('data-full');
            toggleLightbox(full);
        });

        function toggleLightbox(url) {

            if ($lightbox.is('.open')) {
                $lightbox.removeClass('open').fadeOut(200);
            } else {
                $figure.css('background-image', 'url(' + url + ')');
                $lightbox.addClass('open').fadeIn(200);
            }
        }

        $lightbox.on('click', toggleLightbox);

    });
    
    function commentLinkProcess(elem, pid, parentid, recordid) {
        var $dialogName = 'dialog-comment-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), fillDataParams = '', 
            saveUrl = 'mdwebservice/runProcess';
            fillDataParams = 'parentid='+parentid+'&recordid='+recordid;

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: pid, 
                isDialog: true, 
                isSystemMeta: false,
                fillDataParams: fillDataParams,
                dmMetaDataId: '<?php echo $this->dataViewId; ?>',
                oneSelectedRow: {'recordid': recordid}
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                        if (window['processBeforeSave_'+processUniqId]($(e.target))) {     

                            $processForm.validate({ 
                                ignore: '', 
                                highlight: function(element) {
                                    $(element).addClass('error');
                                    $(element).parent().addClass('error');
                                    if ($processForm.find("div.tab-pane:hidden:has(.error)").length) {
                                        $processForm.find("div.tab-pane:hidden:has(.error)").each(function(index, tab){
                                            var tabId = $(tab).attr('id');
                                            $processForm.find('a[href="#'+tabId+'"]').tab('show');
                                        });
                                    }
                                },
                                unhighlight: function(element) {
                                    $(element).removeClass('error');
                                    $(element).parent().removeClass('error');
                                },
                                errorPlacement: function(){} 
                            });

                            var isValidPattern = initBusinessProcessMaskEvent($processForm);

                            if ($processForm.valid() && isValidPattern.length === 0) {
                                $processForm.ajaxSubmit({
                                    type: 'post',
                                    url: saveUrl,
                                    dataType: 'json',
                                    beforeSubmit: function (formData, jqForm, options) {
                                    },
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
                                            var defaultCriteria = {};
                                            defaultCriteria.defaultCriteriaData = $("div#dv-search-<?php echo $this->dataViewId; ?> form#default-criteria-form").serialize();
                                            explorerRefresh_<?php echo $this->dataViewId; ?>(elem, defaultCriteria);
                                            $dialog.dialog('close');
                                        } 
                                        Core.unblockUI();
                                    },
                                    error: function () {
                                        alert("Error");
                                        Core.unblockUI();
                                    }
                                });
                            }
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

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
                    width: dialogWidth,
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
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }    
    
    function commentDeleteProcess(elem, pid, recordid) {
        
        var $this = $(elem);
        var $table = $this.closest('.not-datagrid');
        var $parent = $this.closest('.media-body').find('[data-row-data]');
        
        $table.find('.selected-row').removeClass('selected-row');
        $parent.addClass('selected-row');
        
        transferProcessAction('', '<?php echo $this->dataViewId; ?>', pid, '200101010000011', 'toolbar', this, {callerType: '<?php echo $this->row['META_DATA_CODE']; ?>'}, undefined, undefined, undefined, undefined, '', undefined, undefined, function() {
            new PNotify({
                title: 'Success',
                text: plang.get('msg_delete_success'),
                type: 'success',
                sticker: false
            });

            var $commentItem = $(elem), $parent = $commentItem.closest('[data-id]'), 
                level = $parent.attr('data-level');

            if (level == '2' || level == '3') {

                var $ul = $commentItem.closest('ul');
                $ul.find('[data-parent*="-'+recordid+'-"]').remove();
            } 

            $parent.remove();
        });
        
        return;
        
        var $dialogName = 'dialog-confirm';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $dialog.empty().append(plang.get('msg_delete_confirm'));
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: plang.get('msg_title_confirm'),
            width: 370,
            height: "auto",
            modal: true,
            closeOnEscape: isCloseOnEscape,
            open: function() {
                var $thisDialogButton = $(this).parent().find('.ui-dialog-buttonpane');
                $thisDialogButton.on('keydown', 'button', function(e) {
                    var keyCode = (e.keyCode ? e.keyCode : e.which);
                    if (keyCode == 39) {
                        var $thisButton = $(this);
                        $thisButton.next().focus();
                    }
                    if (keyCode == 37) {
                        var $thisButton = $(this);
                        $thisButton.prev().focus();
                    }
                });
            },
            close: function() {
                $dialog.empty().dialog('close');
            },
            buttons: [{
                    text: plang.get('yes_btn'),
                    class: 'btn green-meadow btn-sm',
                    click: function() {
                        
                        var paramData = [];
                        paramData.push({fieldPath: 'id', inputPath: 'id', value: recordid});
        
                        $.ajax({
                            type: 'post',
                            url: 'mdwebservice/execProcess',
                            data: {processId: pid, paramData: paramData},
                            dataType: 'json',
                            beforeSend: function() {
                                Core.blockUI({message: 'Loading...', boxed: true});
                            },
                            success: function(dataSub) {

                                PNotify.removeAll();
                                
                                if (dataSub.hasOwnProperty('status')) {

                                    if (dataSub.status === 'success') {
                                        
                                        new PNotify({
                                            title: dataSub.status,
                                            text: plang.get('msg_delete_success'),
                                            type: dataSub.status,
                                            sticker: false
                                        });
                                    
                                        var $commentItem = $(elem), $parent = $commentItem.closest('[data-id]'), 
                                            level = $parent.attr('data-level');

                                        if (level == '2' || level == '3') {
                                            
                                            var $ul = $commentItem.closest('ul');
                                            $ul.find('[data-parent*="-'+recordid+'-"]').remove();
                                        } 
                                        
                                        $parent.remove();
                                        
                                    } else {
                                        new PNotify({
                                            title: dataSub.status,
                                            text: dataSub.message,
                                            type: dataSub.status,
                                            sticker: false
                                        });
                                    }

                                    $dialog.dialog('close');
                                
                                } else {
                                    
                                    new PNotify({
                                        title: 'Error',
                                        text: 'Unknown error!',
                                        type: 'error',
                                        sticker: false
                                    });
                                }
                                
                                Core.unblockUI();
                            },
                            error: function() { alert('Error'); }
                        });
                    }
                },
                {
                    text: plang.get('no_btn'),
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');
        bpSoundPlay('ring');
    }
    function commentEditProcess(elem, rowId, processId) {
        var $dialogName = 'dialog-comment-bp';
        if (!$('#' + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName), 
            saveUrl = 'mdwebservice/runProcess';

        $.ajax({
            type: 'post',
            url: 'mdwebservice/callMethodByMeta',
            data: {
                metaDataId: processId, 
                isDialog: true, 
                isSystemMeta: false,
                dmMetaDataId: '<?php echo $this->dataViewId; ?>',
                oneSelectedRow: {id: rowId}
            },
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {

                $dialog.empty().append(data.Html);

                var $processForm = $dialog.find('#wsForm'), processUniqId = $processForm.parent().attr('data-bp-uniq-id');
                var buttons = [
                    {text: data.run_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function (e) {
                            
                        if (window['processBeforeSave_'+processUniqId]($(e.target)) && bpFormValidate($processForm)) {     
                            
                            $processForm.ajaxSubmit({
                                type: 'post',
                                url: saveUrl,
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({boxed: true, message: 'Loading...'});
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
                                        var resultData = responseData.resultData;
                                        
                                        if (resultData.hasOwnProperty('commenttext')) {
                                            var $this = $(elem), $parent = $this.closest('.float-right'), 
                                                $commentTxt = $parent.prev('.text-justify');
                                            
                                            if ($commentTxt.length) {
                                                $commentTxt.html(html_entity_decode(resultData.commenttext, 'ENT_QUOTES'));
                                            }
                                        }
                                        $dialog.dialog('close');
                                    } 
                                    Core.unblockUI();
                                },
                                error: function () {
                                    alert("Error");
                                    Core.unblockUI();
                                }
                            });
                        }    
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ];

                var dialogWidth = data.dialogWidth, dialogHeight = data.dialogHeight;

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
                    width: dialogWidth,
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
                    $dialog.dialogExtend('maximize');
                }
                $dialog.dialog('open');
            },
            error: function () {
                alert('Error');
                Core.unblockUI();
            }
        }).done(function () {
            Core.initBPAjax($dialog);
            Core.unblockUI();
        });
    }
    function dvCommentViewlistDropDownClick(elem, id) {
        var index = $(elem).closest('[datagrid-row-index]').index();
        objectdatagrid_<?php echo $this->dataViewId; ?>.addClass('not-datagrid');
        objectdatagrid_<?php echo $this->dataViewId; ?>.val(id);
    }    
    <?php
    if ($this->dataGridOptionData['DRILLDBLCLICKROW'] == 'true' && $this->dataGridOptionData['DRILL_CLICK_FNC']) {
    ?>
        $(document.body).on("dblclick", ".commentnewlist", function () {
            $(this).find(".card-header").click();
            <?php echo $this->dataGridOptionData['DRILL_CLICK_FNC']; ?>
        });        
    <?php
    } ?>    
</script>

<style type="text/css">
    .commentview .card .card-header {
        padding: .6375rem 1.25rem;
    }
    .commentview .card .card-body{
        padding:25px;
    }
    .commentview .comment-list-section .fa {
        font-size: 1.2em;
    }
    .commentview .comment-list-section .caption {
        cursor: pointer;
    }
    .commentview .comment-list-section .caption .fa {
        font-size: 0.7em;
        color: black;
    }
    .commentview .comment-list-section .caption.selected-row {
        background-color: #ebebeb;
    }
    .commentview .comment-list-section{
        background-color: #EBEBEB;
    }
    .commentview .media{
        /* background-color: #fff;
        margin-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 10px; */
        border-radius: 0px;
        position: relative;
    }
    .commentview .comment-date{
        font-size: 0.8em;
        color: #8e8e8e;
        margin-left: 10px;
        /*    margin-bottom: 1px;
            margin-top: 1px;*/
    }
    .commentview .counts {
        font-size: 0.7em;
        padding-right: 20px;
    }

    .commentview .comment-detail {
        margin-left: 40px;
    }
    .commentview .media > .pull-left {
        padding-right: 10px;
    }
    .commentview .media img {
        /*height: 54px;*/
        position: relative;
        top: 3px;
        /*width: 54px;*/
    }
    .commentview img.img-thumbnail {
        cursor:zoom-in;
    }
    .commentview{
        bottom: 0;
        content: "";
        display: block;
        top: 0;
        margin-left: -18px;
        margin-right: -18px;
    }
    .commentview .comment-hr{
        border-color: #929595 -moz-use-text-color -moz-use-text-color;
        width: 100%;
    }
    .commentview .panel{
        /*margin-top: 20px;*/
        padding-top: 10px;
        /*padding-right: 20px;*/
        padding-bottom: 0px;
        margin-bottom: 0px;
        border-radius: 0px !important;
        border-style: solid;
        border-width: medium;
        position: relative;
        border: none !important;
        /*background-color: #1111;*/
    }
    .commentview .panel-body{
        padding-top: 0px !important;
        padding-left: 0px !important;
        padding-right: 0px !important;
        padding-bottom: 0px !important;
    }
    .commentview .media-body p {
        font-size: 12px;
        font-family: "Open Sans", sans-serif;
        color: #333 !important;
        margin-bottom: 5px;
        margin-top: -10px;
    }
    .commentview .media-heading{
        margin-top: 5px;
        margin-bottom: 6px;
    }
    .commentview .portlet-subject-customed{
        color: #555757 !important;
        font-size: 12px;
        margin-top: 3px;
    }
    .commentview .portlet-subject-customed-name{
        color: #333 !important;
    }
    .panel-body hr:last-child{
        display: none;
    }
    .commentnewlist .card .card-body {
        padding: 1.25rem;
    }
    .commentnewlist .media {
        margin-top: 0.3rem;
    }
    .commentnewlist .border-gray {
        border-color: #e0e0e0;
    }
    .commentnewlist .lh-normal {
        line-height: normal;
    }
    .commentnewlist .card-header {
        padding: .6375rem 1.25rem;
    }
    .commentnewlist .talign-right {
        text-align: right;
    }
    .commentnewlist a.aimg {
        width: 100%;
        margin-top: 10px;
        background: none;
        display: block;
        height: auto;
        padding: 0;
    }
    .commentnewlist a.aimg img {
        width: 100%;
        top: 10px;
        margin-bottom: 20px;
        max-height: 420px;
        position: relative;
        display:block;
    }
</style>