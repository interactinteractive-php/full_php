<form method="post" id="kpiDataMartVisualConfigForm">
    
    <table class="table table-hover kpi-datamart-criterias-config">
        <thead>
            <tr>
                <th style="width: 10px">№</th>
                <th style="width: 300px;">Target</th>
                <th style="width: 300px;">Source</th>
                <th style="width: 50px;"></th>
            </tr>
        </thead>
        <tbody>
        <?php
            if (isset($this->criterias)) {
                foreach ($this->criterias as $c => $criteria) {
            ?>
            <tr>
                <td><?php echo (++$c); ?>.</td>
                <td>
                    <?php 
                    echo Form::select(array(
                        'name' => 'objectAttr[]', 
                        'class' => 'form-control form-control-sm', 
                        'data' => $this->columns, 
                        'op_value' => 'SRC_COLUMN_NAME', 
                        'value' => $criteria['objectAttr'],
                        'op_text' => 'SRC_COLUMN_NAME| |-| |LABEL_NAME'
                    )); 
                    ?>
                </td>
                <td>
                    <?php 
                    echo Form::select(array(
                        'name' => 'criteriaValue[]', 
                        'class' => 'form-control form-control-sm', 
                        'data' => $this->mainColumns, 
                        'op_value' => 'SRC_COLUMN_NAME', 
                        'value' => $criteria['criteriaValue'],
                        'op_text' => 'SRC_COLUMN_NAME| |-| |LABEL_NAME'
                    )); 
                    ?>                
                </td>
                <td class="text-center">
                    <a href="javascript:;" class="btn red btn-xs kpi-datamart-criterias-remove" title="<?php echo $this->lang->line('delete_btn'); ?>">
                        <i class="far fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php
                }
            }
            ?>            
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">
                    <a href="javascript:;" class="btn green btn-xs mircroflow-criterias-addrow">
                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('add_btn'); ?> 
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>    
    <input type="hidden" name="id" value="<?php echo $this->id; ?>" data-kpidatamart-id="1"/>
</form>    

<script type="text/javascript">
$(function() {
        $('#dialog-microflow-addparams').on('click', '.kpi-datamart-criterias-remove', function() {
            var $this = $(this), 
                $tbody = $this.closest('tbody');
                
            $this.closest('tr').remove();
            
            var $rows = $tbody.find('> tr');
            
            $rows.each(function(i) {
                $(this).find('td:eq(0)').text((i + 1) + '.');
            });
        });
        
        $('#dialog-microflow-addparams').on('click', '.mircroflow-criterias-addrow', function() {
            var $this = $(this), 
                $table = $this.closest('table'),
                $tbody = $table.find('> tbody'),
                html = [], 
                select_btn = plang.get('select_btn');
                
            html.push('<tr>');
                html.push('<td></td>');
                html.push('<td>');
                    html.push('<?php 
                    echo Form::select(array(
                        'name' => 'objectAttr[]', 
                        'class' => 'form-control form-control-sm', 
                        'data' => $this->columns, 
                        'op_value' => 'SRC_COLUMN_NAME', 
                        'op_text' => 'LABEL_NAME| |-| |SRC_COLUMN_NAME'
                    )); 
                    ?>');
                html.push('</td>');
                html.push('<td>');
                    html.push('<?php 
                    echo Form::select(array(
                        'name' => 'criteriaValue[]', 
                        'class' => 'form-control form-control-sm', 
                        'data' => $this->mainColumns, 
                        'op_value' => 'SRC_COLUMN_NAME', 
                        'op_text' => 'LABEL_NAME| |-| |SRC_COLUMN_NAME'
                    )); 
                    ?>');                
                html.push('</td>');
                html.push('<td class="text-center">');
                    html.push('<a href="javascript:;" class="btn red btn-xs kpi-datamart-criterias-remove" title="'+plang.get('delete_btn')+'">');
                        html.push('<i class="far fa-trash"></i>');
                    html.push('</a>');
                html.push('</td>');
            html.push('</tr>');
            
            $tbody.append(html.join(''));
            
            var $rows = $tbody.find('> tr');
            
            $rows.each(function(i) {
                $(this).find('td:eq(0)').text((i + 1) + '.');
            });
        });        
    });    
</script>