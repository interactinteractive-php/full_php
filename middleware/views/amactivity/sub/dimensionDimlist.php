<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable" style="max-height: 400px; overflow-y: auto">
            <table class="table table-hover table-striped" id="dimInputData">
                <thead>
                    <tr>
                        <th colspan="2">Dimension Input</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(empty($this->getRows[0]['META_DATA_CODE'])) {
                            $jsonData = json_decode($this->getRows[0]['JSON_DATA'], true);
                            foreach ($jsonData as $key => $val) { ?>
                                <tr>
                                    <td style="text-align: right;"><?php echo $val['labelName']; ?>:</td>
                                    <td><input type="text" id="" name="<?php echo $val['inputName']; ?>" class="form-control form-control-sm"/></td>
                                </tr>
                            <?php } ?>
                                <tr>
                                    <td style="text-align: right;">Тайлбар:</td>
                                    <td><textarea id="" name="description" class="form-control form-control-sm"></textarea><input type="hidden" id="" value="<?php echo $jsonData[0]['orderNum']; ?>" class=""/></td>
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
    });
</script>        