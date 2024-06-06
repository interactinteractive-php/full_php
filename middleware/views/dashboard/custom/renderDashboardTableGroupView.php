<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 

$percentHeight = 0;
?>

<div class="col-md-12 mt10 pl0 pr0 dashboard-render">
    <div id="dashboard-container-<?php echo $this->metaDataId; ?>" class="dashboard-container" processMetaDataId="<?php echo isset($this->processMetaDataId) ? $this->processMetaDataId : '0'; ?>">        
    <div class="card light bordered mb0 pb5 mddashboard-card">
        <?php if( $this->diagram['IS_SHOW_TITLE'] == '1') { ?>
        <div class="card-title mddashboard-card-title" id="card-title-<?php echo $this->metaDataId; ?>">
            <div class="caption mddashboard-caption">
                <span class="caption-subject font-weight-bold mddashboard-title" title="" id="dashboard-title-<?php echo $this->metaDataId; ?>"><?php echo $this->diagram['TITLE']; ?></span>
                <span class="caption-helper mddashboard-helper" id="dashboard-helper-<?php echo $this->metaDataId; ?>"></span>
            </div>
        </div>
        <?php } ?>
        <div class="card-body dashboard-content-<?php echo $this->metaDataId; ?>">        
        <div id="dashboard-<?php echo $this->metaDataId; ?>" <?php echo !empty($this->diagram['HEIGHT']) ? 'style="height:'.$this->diagram['HEIGHT'].'; overflow-y: auto"' : ''; ?>>
        <?php if($this->data['status'] === 'success' && array_key_exists(0, $this->data['result'])) {
                $getGroupName = $this->data['result'][0][$this->diagram['XAXIS']];
                unset($this->data['result']['aggregatecolumns']);
                unset($this->data['result']['paging']);

                echo '<table class="table table-bordered" style="width: 100%"><body>';
                foreach ($this->data['result'] as $rowKey => $row) {
                    if($rowKey === 0) {
                        echo '<tr><td style="width: 35%;vertical-align:middle;text-align:center;background-color:' . $this->diagram['COLOR2'] . '" rowspan="' . count($this->data['result']) . '">' . $getGroupName . '</td><td style="width: 65%;background-color:' . $this->diagram['COLOR'] . '">' . $row[$this->diagram['YAXIS']] . '</td></tr>';
                    } else {
                        echo '<tr><td style="background-color:' . $this->diagram['COLOR'] . '">' . $row[$this->diagram['YAXIS']] . '</td></tr>';
                    }
                }
                echo '</tbody></table>';
            } ?>                    
                </div>
            </div>
        </div>
    </div>
</div>