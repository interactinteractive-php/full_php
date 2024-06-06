<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="table-scrollable" style="max-height: 450px; overflow-y: auto">
            <table class="table table-hover table-striped" id="reorderActivityList">
                <thead>
                    <tr>
                        <th style="width: 20px">№</th>
                        <th>Тайлбар</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($this->getRows['status'] === 'success') {
                        foreach ($this->getRows['getRows'] as $key => $val) {
                            echo "<tr style='cursor:pointer;' id='".$val['id']."'>";
                            echo "<td>".++$key.".</td>";
                            echo "<td>".$val['description']."</td>";
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
        $("#reorderActivityList tbody tr").on("click", function() {
            var _this = $(this);
            $("#reorderActivityList tbody tr").removeClass("selected");
            _this.addClass("selected");        
        });    
    });
</script>        