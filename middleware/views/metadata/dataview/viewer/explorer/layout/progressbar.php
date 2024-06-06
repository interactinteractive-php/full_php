<div class="margin">
    <?php
    if ($this->recordList) {
        
        $name = $this->row['dataViewLayoutTypes']['explorer']['fields']['name'];
        $percent = $this->row['dataViewLayoutTypes']['explorer']['fields']['percent'];
        
        foreach ($this->recordList as $row) {
    ?>
    <div><?php echo $row[$name]; ?> <span class="float-right"><?php echo $row[$percent]; ?>%</span></div>
    <div class="progress mb10" style="height: 10px; background-color: #b3d4f5">
        <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $row[$percent]; ?>%; background-color: #428fd7"></div>
    </div>
    <?php
        }
    }
    ?>
</div>