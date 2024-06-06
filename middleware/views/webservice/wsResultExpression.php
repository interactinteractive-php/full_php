<div class="alert alert-success">Success</div>

<?php
if (isset($this->resultMsg)) {
    
    if (!is_array($this->resultMsg)) {
        
        if (is_numeric($this->resultMsg)) {
            echo "Үр дүн: ".Number::formatMoney($this->resultMsg, true);
        } else {
            echo "Үр дүн: ".$this->resultMsg;
        }
        
    } else {
?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 30%">Талбар</th>
                <th>Утга</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($this->resultMsg as $k=>$v) {
            ?>
            <tr>
                <td><?php echo $k; ?></td>
                <td>
                    <?php 
                    if (is_array($v)) {
                        print_array($v);
                    } else {
                        echo $v;
                    }
                    ?>
                </td>
            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
<?php
    }
} 

if (isset($this->wsResult['dataelement'])) {
    
    $output = $this->wsResult['dataelement'];
    if (isset($output['value'])) {
        if ($output['value'] == 'true' || $output['value'] == 'false') {
            echo 'Үр дүн: '.(($output['value'] == 'true') ? 'Үнэн' : 'Худал');
        } else {
            echo 'Үр дүн: '.Number::formatMoney($output['value'], true);
        }
    }
    
} else {
    $output = $this->wsResult;
    if (isset($output['value'])) {
        if ($output['value'] == 'true' || $output['value'] == 'false') {
            echo 'Үр дүн: '.(($output['value'] == 'true') ? 'Үнэн' : 'Худал');
        } else {
            echo 'Үр дүн: '.Number::formatMoney($output['value'], true);
        }
    }
}
?>