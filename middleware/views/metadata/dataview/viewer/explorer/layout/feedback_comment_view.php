<div id="commentview-<?php echo $this->dataViewId; ?>" class="commentview feedback_comment_view ">
    <div id="commentview-board" >
        <div class="pv25 row coment-wrapper">
            <?php
            $cnt = count($this->recordList);
            if ($cnt) {
            includeLib('Detect/Browser');
            $browser = new Browser();

           
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['picture'])) {
                $picture = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['picture']); 
            }else{
                $picture ='';
            }

            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['backgroundColor'])) {
                $bgcolor = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['backgroundColor']); 
            }
            if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['color'])) {
                $textcolor = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['color']); 
            }else{$textcolor = '#fff';}
           
            foreach ($this->recordList as $recordRow) {

                $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');

                if (isset($bgcolor)) {
                    $bgitemcolor = isset($recordRow[$bgcolor]) ? $recordRow[$bgcolor] : 'bg-dark';
                }else{$bgitemcolor = 'bg-dark';}
               
                $textitemcolor = isset($recordRow[$textcolor]) ? $recordRow[$textcolor] : '#fff';

                $userpic = isset($recordRow[$picture]) ? $recordRow[$picture] : 'assets/core/global/img/user.png';

                if (empty($recordRow['parentid'])) { ?>
                    <article class="w-100 feed-item" style="background-color:<?php echo $bgitemcolor; ?> !important" data-row-data="<?php echo $rowJson; ?>">
                        <div class="row">
                            <div class="col-md-2 col-sm-2 hidden-xs">
                                <figure class="thumbnail">
                                    <img src="<?php echo $userpic; ?>" class="img-circle media-object mr-2 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:auto; height:60px">
                                    <figcaption class="text-center"></figcaption>
                                </figure>
                            </div>
                            <div class="col-md-10 col-sm-10">
                                <div class="panel panel-default arrow left">
                                    <div class="panel-body card-body row">
                                        <div class="col-md-4">
                                            <header>
                                                <div class="d-flex justify-content-between  ">
                                                    <div class="justify-content-between">
                                                        <?php
                                                            if (isset($recordRow[$this->name1])) { ?>
                                                            <?php
                                                                echo '<div class="float-left mr-1 head1">' . $recordRow[$this->name1] . '</div>';
                                                            }    
                                                        ?>
                                                        <?php
                                                            if (isset($recordRow[$this->name2])) { ?>
                                                            <?php
                                                                echo '<div class="float-left mr-1 head2">' . $recordRow[$this->name2] . '</div>';
                                                            }    
                                                        ?>
                                                        <?php
                                                            if (isset($recordRow[$this->name3])) { ?>
                                                            <?php
                                                                echo '<div class="float-left mr-1 head3">' . $recordRow[$this->name3] . '</div>';
                                                            }    
                                                        ?>
                                                    </div>
                                                    
                                                </div>
                                            </header>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="comment-post">
                                                <div class="text-justify">
                                                    <?php echo Str::nlTobr(html_entity_decode($recordRow[$this->body]), ENT_QUOTES); ?> 
                                                </div>
                                                <div class="d-flex mt20">
                                                    <div class="d-flex justify-content-between">
                                                        <?php
                                                            if (isset($recordRow[$this->name6])) { ?>
                                                            <?php
                                                                echo ' <div class="f1 mr-1">' . $recordRow[$this->name6] . '</div>';
                                                            }    
                                                        ?>  
                                                        <?php
                                                            if (isset($this->name7)) { ?>
                                                            <?php
                                                                echo '<div class="f2"> ' . $recordRow[$this->name7] . '</div>';
                                                            }    
                                                        ?> 
                                                        <?php
                                                            if (isset($this->name8)) { ?>
                                                            <?php
                                                                echo '<div class="f3">' . $recordRow[$this->name8] . '</div>';
                                                            }    
                                                        ?>  
                                                    
                                                    </div>
                                                </div>
                                            
                                                
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex">
                                            <?php
                                                if (isset($recordRow[$this->name5])) { ?>
                                                <?php
                                                    echo '<div class="head5">  ' . $recordRow[$this->name5] . '</div>';
                                                }    
                                            ?>
                                            <div class="d-flex align-items-end">
                                                <?php
                                                    if (isset($recordRow[$this->name4])) { ?>
                                                    <?php
                                                        echo '<div class="head4">' . $recordRow[$this->name4] . '</div>';
                                                    }    
                                                ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                            <div class="clearfix"></div>
                            <?php
                            $hasChild = true;                                
                            for ($i = 0; $i < $cnt; $i++) {
                                if (isset($this->recordList[$i]['parentid']) && isset($recordRow['id']) && $this->recordList[$i]['parentid'] == $recordRow['id']) {
                                    $hasChild = true;
                                    break;
                                }
                            }

                            if ($hasChild) {
                                ?>
                                <div class="tab-content mt-2 ml30">
                                    <ul class="media-list">
                                        <?php
                                        for ($i = 0; $i < $cnt; $i++) {
                                            if ($this->recordList[$i]['parentid'] == $recordRow['id']) {
                                                $rowJson = htmlentities(json_encode($this->recordList[$i]), ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <li class="media border-top-1 border-gray pt-2">
                                                    <div class="mr-1 position-relative">
                                                        <img src="<?php echo $this->recordList[$i]['picture']; ?>" class="media-object mr-1 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:35px; height:35px">
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="d-flex justify-content-between" data-row-data="<?php echo $rowJson; ?>">
                                                            <a href="javascript:void(0);" class="lh-normal"><?php echo $this->recordList[$i]['username']; ?></a>
                                                            <span class="font-size-sm text-muted talign-right"><?php echo Date::formatter($this->recordList[$i]['createddate'], 'Y-m-d H:i:s'); ?></span>
                                                        </div>
                                                        <div class="lh-normal text-justify">
                                                            <?php echo Str::nlTobr(html_entity_decode($this->recordList[$i]['commenttext']), ENT_QUOTES); ?>
                                                        </div>
                                                        <div class="float-right mt-1">
                                                            <a href="javascript:;" onclick="commentLinkProcess(this, '<?php echo $this->nameLinkId4; ?>', '<?php echo $this->recordList[$i]['id']; ?>', '<?php echo issetParam($this->recordList[$i]['recordid']); ?>')" class="btn btn-text btn-sm"><i class="fa fa-reply"></i>Хариу бичих</a>
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
                                                            <li class="media ml60mt-2 pt-2 border-top-1 border-gray">
                                                                <div class="mr-1 position-relative">
                                                                    <img src="<?php //echo $this->recordList[$ii]['picture']; ?> ?>assets/custom/img/user.png" class="media-object mr-1 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:35px; height:35px">
                                                                </div>
                                                                <div class="media-body">
                                                                    <div class="d-flex justify-content-between" data-row-data="<?php echo $rowJson; ?>">
                                                                        <a href="javascript:void(0);" style="height: 40px;overflow: hidden;"  class="lh-normal"><?php echo $this->recordList[$ii]['username']; ?></a>
                                                                        <span class="font-size-sm text-muted talign-right" style="height: 40px;overflow: hidden;"><?php echo Date::formatter($this->recordList[$ii]['createddate'], 'Y-m-d H:i:s'); ?></span>
                                                                    </div>
                                                                    <div class="lh-normal">
                                                                        <div class="lh-normal text-justify">
                                                                            <?php echo html_entity_decode($this->recordList[$ii]['commenttext'], ENT_QUOTES); ?>
                                                                        </div>
                                                                        <div class="float-right mt-1">
                                                                            <a href="javascript:;" onclick="commentLinkProcess(this, '<?php echo $this->nameLinkId4; ?>', '<?php echo $this->recordList[$ii]['id']; ?>', '<?php echo issetParam($this->recordList[$ii]['recordid']); ?>')" class="btn btn-text"><i class="fa fa-reply"></i>  Хариу бичих</a>
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
                                                                <div class="" style="margin-left: 65px">
                                                                    <div class="">
                                                                        <?php
                                                                        for ($iii = 0; $iii < $cnt; $iii++) {
                                                                            if ($this->recordList[$ii]['id'] == $this->recordList[$iii]['parentid']) {
                                                                                $rowJson = htmlentities(json_encode($this->recordList[$iii]), ENT_QUOTES, 'UTF-8');
                                                                                ?>
                                                                            <li class="media ml45 mt-2 pt-2 border-top-1 border-gray">
                                                                                <div class="mr-1 position-relative">
                                                                                    <img src="<?php //echo $this->recordList[$ii]['picture']; ?> ?>assets/custom/img/user.png" class="media-object mr-1 rounded-circle" data-default-image="assets/core/global/img/user.png" onerror="onDataViewImgError(this);" style="width:35px; height:35px">
                                                                                </div>
                                                                                <div class="media-body">
                                                                                    <div class="d-flex justify-content-between" data-row-data="<?php echo $rowJson; ?>">
                                                                                        <a href="javascript:void(0);" style="height: 40px;overflow: hidden;" class="lh-normal"><?php echo $this->recordList[$iii]['username']; ?></a>
                                                                                        <span class="font-size-sm text-muted talign-right" style="height: 40px;overflow: hidden;"><?php echo Date::formatter($this->recordList[$iii]['createddate'], 'Y-m-d H:i:s'); ?></span>
                                                                                    </div>
                                                                                    <div class="lh-normal">
                                                                                        <div class="lh-normal text-justify">
                                                                                            <?php echo html_entity_decode($this->recordList[$iii]['commenttext'], ENT_QUOTES); ?>
                                                                                        </div>
                                                                                        <div class="float-right mt-1">
                                                                                            <a href="javascript:;" onclick="commentLinkProcess(this, '<?php echo $this->nameLinkId4; ?>', '<?php echo $this->recordList[$ii]['id']; ?>', '<?php echo issetParam($this->recordList[$iii]['recordid']); ?>')" class="btn btn-text"><i class="fa fa-reply"></i>  Хариу бичих</a>
                                                                                        </div>
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
                                                                            </li>     
                                                                                <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                            <?php 
                                                            } else {
                                                                // echo '<hr class="comment-hr">';
                                                            }                                                                        
                                                        }
                                                    }
                                                    ?>
                                                <?php 
                                                } else {
                                                    // echo '<hr class="comment-hr">';
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            <?php } ?>
                    </article>
            <?php
                }
            }
            }
            ?>
        </div>
    </div>
    <div class="lightbox">
        <figure></figure>
    </div>
</div>

<script>
    // $('.coment-wrapper').isotope({
    //     itemSelector: '.c_item',
    //     layoutMode: 'fitRows'
    // });
    $(document).ready(function() { 
        $('.coment-wrapper').isotope({
            // itemSelector: '.c_item',
            masonry: {
                columnWidth: '.col-md-4'
            }
        });
    });

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
            var _parent=_this.closest('.feedback_comment_view-section');
            _parent.find('.selected-row').removeClass('selected-row');
            _this.addClass('selected-row');
        });        

        $("#commentview-fileupload").click(function () {
            $.ajax({
                type: 'post',
                url: 'mdwebservice/renderAddModeBpFileTab',
                beforeSend: function () {
                    if (!$(
                            "link[href='assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                        $("head").prepend(
                                '<link rel="stylesheet" type="text/css" href="assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
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
                        if (!$(
                                "link[href='assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css']").length) {
                            $("head").prepend(
                                    '<link rel="stylesheet" type="text/css" href="assets/core/global/plugins/jquery-file-upload/css/jquery.fileupload.css"/>');
                        }
                        Core.blockUI({
                            boxed: true,
                            message: 'Түр хүлээнэ үү'
                        });
                    },
                    success: function (responseData) {
                        Core.unblockUI();
                        location.reload();
//              if(responseData.status === 'success'){
//                new PNotify({
//                  title: 'Success',
//                  text: responseData.message,
//                  type: 'success',
//                  sticker: false
//                });
//              } else {
//                new PNotify({
//                  title: 'Error',
//                  text: responseData.message,
//                  type: 'error',
//                  sticker: false
//                });
//              }
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
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true
                });
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
                                            /*if (isMulti) {
                                                dataGrid.datagrid('reload');
                                            } else {
                                                $(elem).closest('div.datagrid-view').children('table').datagrid('reload');
                                            }*/
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
</script>

<style>
   
    .feedback_comment_view .feed-item{
        position: relative !important;
    }
    .feedback_comment_view{
        background: #fff;
    }
    .feedback_comment_view .thumbnail{
        margin: 20px 5px;
    }
    .feedback_comment_view .row {
        margin-bottom: 0px;
    }
    .feedback_comment_view .panel .panel-heading {
        padding: 4px 15px;
        /* position: absolute; */
        border:none;
        border-top-right-radius:0px;
        top: 1px;
    }
    
    #objectdatagrid-<?php echo $this->dataViewId; ?> {
        background-color: transparent;
    }
</style>