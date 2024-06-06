<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable" style="max-height: 400px; overflow-y: auto">
            <table class="table table-hover table-striped" id="reorderActivityList">
                <thead>
                    <tr>
                        <th style="width: 20px">№</th>
                        <th>Жагсаалтын нэр</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($this->getRows as $key => $val) { 
                            if(empty($val['META_DATA_CODE']))
                                continue;
                            ?>
                            <tr style='cursor:pointer;' 
                                    id="<?php echo $val['CODE'].'_'.$val['FIELD_PATH'].'_'.$val['FIELD_PATH_CODE']; ?>" 
                                    data-criteria="<?php echo $val['CRITERIA']; ?> " 
                                    data-metadatacode="<?php echo $val['META_DATA_CODE'] ?>">
                                <td><?php echo ++$key ?></td>
                                <td><?php echo $val['META_DATA_NAME'] ?></td>
                            </tr>
                        <?php }
                    ?>
                </tbody>
            </table>
        </div>    
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("table#reorderActivityList tbody tr").on("click", function() {
            var _this = $(this);
            $("table#reorderActivityList tbody tr").removeClass("selected");
            _this.addClass("selected");        
        });    
    });
</script>        