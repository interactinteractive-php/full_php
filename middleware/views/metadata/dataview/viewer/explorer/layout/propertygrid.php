<div class="table-scrollable table-scrollable-borderless" style="margin-top: 0 !important">
    <table class="table table-hover table-light">
        <tbody>
            <?php
            if ($this->recordList) {
                
                $name = $this->row['dataViewLayoutTypes']['explorer']['fields']['name'];
                $value = $this->row['dataViewLayoutTypes']['explorer']['fields']['value'];
        
                foreach ($this->recordList as $row) {
            ?>
            <tr>
                <td class="text-left" style="font-weight: 600"><?php echo $row[$name]; ?></td>
                <td class="text-right"><?php echo Number::amount($row[$value], true); ?></td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>