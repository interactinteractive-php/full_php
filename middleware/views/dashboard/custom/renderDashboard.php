<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

$percentHeight = 0;
?>

<div class="col-md-12 mt10 pl0 pr0 dashboard-render">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container" processMetaDataId="<?php echo isset($this->processMetaDataId) ? $this->processMetaDataId : '0'; ?>">        
        <div class="card light bordered mb0 pb5 mddashboard-card">
            <div class="card-title mddashboard-card-title" id="card-title-<?php echo $this->metaDataId; ?>">
                <div class="caption mddashboard-caption">
                    <span class="caption-subject font-weight-bold mddashboard-title" title="" id="dashboard-title-<?php echo $this->metaDataId; ?>"><?php echo $this->diagram['TITLE']; ?></span>
                    <span class="caption-helper mddashboard-helper" id="dashboard-helper-<?php echo $this->metaDataId; ?>"></span>
                </div>
            </div>
            <div class="card-body dashboard-content-<?php echo $this->metaDataId; ?>">
                <div id="dashboard-<?php echo $this->metaDataId; ?>">
                    <div id="water_gauge1_<?php echo $this->metaDataId; ?>" class="">
                        <div class="vertical-line-<?php echo $this->metaDataId; ?>"></div>
                    </div>	
                    <ul class="label-<?php echo $this->metaDataId; ?>">
                        <?php if($this->data['status'] === 'success' && array_key_exists(0, $this->data['result'])) {
                                $getRow = $this->data['result'][0];
                                $chart1_V = $getRow[$this->diagram['XAXIS']];
                                $chart1_EV = $getRow[$this->diagram['YAXIS']];
                                $percentHeight = $chart1_EV * 100 / $chart1_V;                                
                                
                                 foreach ($this->columnData as $row) { ?>
                                    <li><?php echo $row['LABEL_NAME']; ?>: <span class="bold"><?php echo $getRow[$row['FIELD_PATH']]; ?></span></li>
                            <?php }
                            } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #water_gauge1_<?php echo $this->metaDataId; ?> {
        height: 320px;
        width: 320px;
        -moz-border-radius: 50%; 
        -webkit-border-radius: 50%; 
        border-radius: 50%;
        border: 2px solid #000;
        background: linear-gradient(to top, <?php echo $this->diagram['COLOR']; ?> <?php echo $percentHeight; ?>%, #fff <?php echo $percentHeight; ?>%, #fff 100%);
    }
    .vertical-line-<?php echo $this->metaDataId; ?> {
        width: 2px;
        background-color: black;
        height: 100%;
        margin: 0 auto;
    }
    .label-<?php echo $this->metaDataId; ?> {
        position: absolute;
        top: 40px;
        left: 400px;
        list-style: none;
        font-size: 12px;
        line-height: 2.5;
    }
    .water_gauge_title_<?php echo $this->metaDataId; ?> {
        height: 35px;
        border: 1px solid #b7b7b7;
        background-color: #ccc;
        padding: 8px;
        margin-bottom: 18px;        
    }
</style>