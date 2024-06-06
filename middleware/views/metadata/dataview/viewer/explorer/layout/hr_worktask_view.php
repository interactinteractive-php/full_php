<div class="worktask_view card worktask_view-<?php echo $this->dataViewId; ?>">
    <?php
    if ($this->recordList) {
        $cnt = count($this->recordList);
        $rowJson = htmlentities(json_encode($this->recordList), ENT_QUOTES, 'UTF-8');
        $isMore = Input::isEmpty('drillDownDefaultCriteria');
    ?>
    <div class="card-header bg-color">
        <div class="row desc">
            <div class="col-md-6 col-sm-12">
                <span><b>Total:</b> <?php echo $cnt; ?></span> 
            </div>
            <div class="col-md-6 col-sm-12">
                <?php if (isset($this->row['dataViewLayoutTypes']['explorer']['fields']['name5'])) { ?>
                <span><b>Percent:</b>  
                    <?php
                        $percent = strtolower($this->row['dataViewLayoutTypes']['explorer']['fields']['name5']); 
                        echo ($this->recordList['0'][$percent]);
                    ?> %</span> 
                <?php } ?>
            </div>
        </div>
    </div>
    <ul class="list-group list-group-flush bg-color">
        <?php 
        $i = 0;
        foreach ($this->recordList as $row) {
            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            if($i < 6 || !$isMore) {
                $bgitemcolor = isset($row[$this->row['dataViewLayoutTypes']['explorer']['fields']['bgColor']]) ? $row[$this->row['dataViewLayoutTypes']['explorer']['fields']['bgColor']] : '#2196f3';
            ?>
                <li class="list-group-item" data-bg-color="<?php echo $bgitemcolor;?>"> <span class="icon-file-text2 mr8"></span>
                    <?php
                        if (isset($row[$this->name1])) { ?>
                        <?php
                            echo '<div class="head1">' . $row[$this->name1] . '</div>';
                        }    
                    ?>
                    <?php
                        if (isset($row[$this->name2])) { ?>
                        <?php
                            echo '<div class="head1 ml10">' . $row[$this->name2] . '</div>';
                        }    
                    ?>
                    <?php
                        if (isset($row[$this->name3])) { ?>
                        <?php
                            $wsid = issetParam($row['workspaceid']);
                            echo '<div class="head1 ml10"><a style="color:#000" href="javascript:;" data-row="'.$rowJson.'" onclick="gridDrillDownLink(this, \'\', \'workspace\', \'1\', \'\', \'\', \'code\', \''.$wsid.'\', \'\', false, \'\',  \'\',  \'\')">' . $row[$this->name3] . '</a></div>';
                        }    
                    ?>
                    <?php
                        if (isset($row[$this->name4])) { ?>
                        <?php
                            echo '<div class="head1 ml10">' . $row[$this->name4] . '</div>';
                        }    
                    ?>
                </li>
                <?php
                 $i ++;
            }
        }
        ?>
    </ul>
    <div class="card-footer text-right bg-color">
        <?php
            $metaInstance = (new Mdmetadata())->getMetaData($this->dataViewId);
            if ($metaInstance && $isMore) { ?>
                <a href="javascript:;" class="card-link" data-row="{}" onclick="gridDrillDownLink(this, '<?php echo $metaInstance['META_DATA_CODE']; ?>', 'metagroup', '1','', '<?php echo $this->dataViewId; ?>', '','<?php echo $this->dataViewId; ?>','aaaa=aaaa')"> Бүгдийг харах</a>
        <?php
            }
        ?>
    </div>
    <?php } ?>
</div>

<script>    

    var color=$('.worktask_view-<?php echo $this->dataViewId; ?>').find('li').attr("data-bg-color");
    $('.worktask_view-<?php echo $this->dataViewId; ?>').find('.bg-color').css('background',color);

</script>

<style>
    .worktask_view .card-header .desc{
        padding: 5px 10px;
        background: #d8d8d8;
        margin: 0;
        color: #000;
        font-size: 15px;
    }
   
    .worktask_view .card-header{
        padding:15px 0 0 0 ;
    }
    .worktask_view .card-footer .card-link{
        color: #000 !important;
        text-transform: uppercase;
    }
    .worktask_view .card-footer{
        opacity: .7;
        border-radius: 0 !important;
    }
    .worktask_view .list-group{
        opacity: .7;
        border-radius: 0 !important;
    }
    .worktask_view .list-group li{
        padding: 5px 10px;
        margin: 2px 11px;
        background: #b1b1b1f2;
        color: #000 !important;
    }
</style>