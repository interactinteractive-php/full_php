<div class="col-md-12 dataview-path">
    <div class="page-title mb20">
        <h4><strong><?php echo $this->metaDataName; ?> </strong></h4>
        <?php
        foreach ($this->path as $k=>$row) {
            echo '<div><a href="javascript:;" onclick="childRecordView(\'' . $k . '\', \'folder\', \'\');"><i class="fa fa-caret-right"></i> ' . $row . '</a></div>';
        }
        ?>
    </div>
    
    <div class="table-scrollable overflowYauto" style="max-height: 500px;">
        <table class="table table-sm table-bordered table-hover meta-path" cellspacing="0" width="100%">
            <tbody>
                <?php 
                    echo $this->inResult;
                    echo $this->outResult;
                    echo $this->result;
                ?>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(function () {
    $(".meta-path").tabletree({
        initialState: 'collapsed',
        expanderExpandedClass: 'fa fa-minus',
        expanderCollapsedClass: 'icon-plus3 font-size-12'
    });
});
</script>