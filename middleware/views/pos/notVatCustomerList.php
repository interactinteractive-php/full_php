<table class="table table-bordered table-hover mb0 tbl-notvat-crm">
    <thead>
        <tr>
            <th class="text-center" style="width: 10px">№</th>
            <th class="text-center" style="width: 25%">Код</th>
            <th class="text-center" style="width: 70%">Нэр</th>
            <th class="text-center" style="width: 20%">ТТД</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($this->crmList) {
            foreach ($this->crmList as $k => $row) {
        ?>
        <tr data-done="1">
            <td class="text-center"><?php echo ++$k; ?></td>
            <td><?php echo $row['CUSTOMER_CODE']; ?></td>
            <td data-crm-name="1"><?php echo $row['CUSTOMER_NAME']; ?></td>
            <td data-crm-ttd="1"><?php echo $row['POSITION_NAME']; ?></td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<style type="text/css">
table.tbl-notvat-crm > tbody > tr > td {
    padding: 8px;
    cursor: pointer;
    line-height: 15px;
}       
</style>

<script type="text/javascript">
$(function(){
    $('.tbl-notvat-crm > tbody > tr[data-done="1"]').on('click', function(){
        var $this = $(this), $parentRow = $this.closest('tr');
        $('.tbl-notvat-crm > tbody > tr.selected').removeClass('selected');
        $parentRow.addClass('selected');
    });
});    
</script>