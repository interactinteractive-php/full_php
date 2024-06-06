<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <p>Харъяалагдах хэлтэс: <strong><?php echo $this->getRowsLog['depName']; ?></strong></p>
        <div class="table-scrollable" style="max-height: 400px; overflow-y: auto">
            <table class="table table-hover table-striped" id="salarySheetLogList">
                <thead>
                    <tr>
                        <th style="width: 20px">№</th>
                        <th>Утга</th>
                        <th>Огноо</th>
                        <th>Хэрэглэгч</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if(count($this->getRowsLog['sheetLogs']) > 0) {
                            foreach ($this->getRowsLog['sheetLogs'] as $key => $val) {
                                echo "<tr>";
                                echo "<td>".++$key.".</td>";
                                echo "<td style='color: #c30000' class='text-right'>".  Str::formatMoney($val['VALUE'])."</td>";
                                echo "<td>".$val['CREATED_DATE']."</td>";
                                echo "<td>".$val['USERNAME']."</td>";
                                echo "</tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>    
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $("table#salarySheetLogList tbody tr").on("click", function() {
            var _this = $(this);
            $("table#salarySheetLogList tbody tr").removeClass("selected");
            _this.addClass("selected");        
        });    
    });
</script>        