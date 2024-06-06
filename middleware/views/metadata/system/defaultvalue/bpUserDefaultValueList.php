<div class="col-md-12">
    <div class="table-scrollable">
        <table class="table table-bordered table-advance table-hover" id="bp-default-value-list">
            <thead>
                <tr>
                    <th style="width: 150px;"><?php echo $this->lang->line('META_00075'); ?></th>
                    <th style="width: 60%"><?php echo $this->lang->line('META_00125'); ?></th>
                    <th style="width: 136px;">Үүсгэсэн огноо</th>
                </tr>    
            </thead> 
            <tbody>
                <?php
                if ($this->dataList) {
                    foreach ($this->dataList as $row) {
                ?>
                <tr>
                    <td>
                        <?php echo Form::hidden(array('name'=>'packageId[]','value'=>$row['PACKAGE_ID'])); ?>
                        <?php echo $row['PACKAGE_CODE']; ?>
                    </td>
                    <td><?php echo $row['PACKAGE_NAME']; ?></td>
                    <td><?php echo $row['CREATED_DATE']; ?></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>  
    </div>    
</div>

<script type="text/javascript">
$(function(){
    $("#bp-default-value-list tbody").on("click", "tr", function(){
        var _this = $(this);
        $("#bp-default-value-list tbody tr").removeClass("selected");
        _this.addClass("selected");
    });
});    
</script>    