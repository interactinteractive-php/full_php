<form method="post" id="banknotesForm">
    <table class="table table-bordered table-hover mb0 tbl-money-bill">
        <thead>
            <tr>
                <th class="text-center" style="width: 40%">Дэвсгэрт</th>
                <th class="text-center" style="width: 20%">Тоо</th>
                <th class="text-center" style="width: 40%">Дүн</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left">20,000-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[20000]" class="form-control form-control-sm bigdecimalInit" data-money="20000"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">10,000-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[10000]" class="form-control form-control-sm bigdecimalInit" data-money="10000"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">5,000-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[5000]" class="form-control form-control-sm bigdecimalInit" data-money="5000"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">1,000-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[1000]" class="form-control form-control-sm bigdecimalInit" data-money="1000"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">500-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[500]" class="form-control form-control-sm bigdecimalInit" data-money="500"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">100-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[100]" class="form-control form-control-sm bigdecimalInit" data-money="100"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">50-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[50]" class="form-control form-control-sm bigdecimalInit" data-money="50"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">20-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[20]" class="form-control form-control-sm bigdecimalInit" data-money="20"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">10-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[10]" class="form-control form-control-sm bigdecimalInit" data-money="10"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">5-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[5]" class="form-control form-control-sm bigdecimalInit" data-money="5"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
            <tr>
                <td class="text-left">1-н дэвсгэрт</td>
                <td class="stretchInput"><input type="text" name="banknote[1]" class="form-control form-control-sm bigdecimalInit" data-money="1"></td>
                <td class="text-right bigdecimalInit"></td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td class="text-right">Нийт</td>
                <td class="text-right bigdecimalInit money-bill-total-qty"></td>
                <td class="text-right bigdecimalInit money-bill-total-amount"></td>
            </tr>
        </tfoot>
    </table>
</form>

<style type="text/css">
table.tbl-money-bill > tbody > tr > td {
    padding: 3px 8px 3px 8px;
}    
table.tbl-money-bill > tfoot > tr > td {
    padding: 3px 8px 3px 8px;
    font-weight: 700;
}    
table.tbl-money-bill > tbody > tr > td.stretchInput {
    padding: 0 !important;
}
table.tbl-money-bill td.stretchInput input[type="text"] {
    border: 1px transparent solid;
    font-size: 12px !important;
    margin: 0 !important;
    padding: 0 7px 0 3px !important;
    outline:none;
    box-sizing: border-box; 
    display: inline-block;
    width: 100%;
    height: 24px;
    border-radius: 0 !important;
    -moz-border-radius: 0 !important;
    -webkit-border-radius: 0 !important;
}
table.tbl-money-bill td.stretchInput input[type="text"]:focus {
    outline: none;
}
</style>

<script type="text/javascript">
$(function(){
    $('.tbl-money-bill > tbody > tr > td > input').on('keyup', function(){
        var $this = $(this), 
            defaultMoney = Number($this.attr('data-money')), 
            qty = Number($this.autoNumeric('get')), 
            moneyProduct = defaultMoney * qty;
        
        if (moneyProduct > 0) {
            $this.closest('tr').find('td.bigdecimalInit').autoNumeric('set', moneyProduct);
        } else {
            $this.closest('tr').find('td.bigdecimalInit').autoNumeric('set', '');
        }
        
        $('td.money-bill-total-qty').autoNumeric('set', $('.tbl-money-bill > tbody > tr > td > input').sum());
        $('td.money-bill-total-amount').autoNumeric('set', $('.tbl-money-bill > tbody > tr > td.bigdecimalInit').sum());
    });
    
    $('.tbl-money-bill > tbody > tr > td > input').on('keydown', function(e){
        var keyCode = (e.keyCode ? e.keyCode : e.which), 
            $this = $(this);
        
        if (keyCode === 38) { // up
            
            var $rowCell = $this.closest('td'), 
                $row = $this.closest('tr'), 
                $prevRow = $row.prevAll('tr:visible:eq(0)'), 
                $colIndex = $rowCell.index();
            
            if ($prevRow.length) {
                $prevRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();
            }
            
            return e.preventDefault();
            
        } else if (keyCode === 40) { // down
            
            var $rowCell = $this.closest('td'), 
                $row = $this.closest('tr'), 
                $nextRow = $row.nextAll('tr:visible:eq(0)'), 
                $colIndex = $rowCell.index();
            
            if ($nextRow.length) {
                $nextRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();
            }
            
            return e.preventDefault();
            
        } else if (keyCode === 13) { // enter
            
            var $rowCell = $this.closest('td'), 
                $row = $this.closest('tr'), 
                $nextRow = $row.nextAll('tr:visible:eq(0)'), 
                $colIndex = $rowCell.index();
            
            if ($nextRow.length) {
                $nextRow.find('td:eq('+$colIndex+') input:not(:hidden):first').focus().select();
            } 
            
            return e.preventDefault();
        }
    });
});    
</script>