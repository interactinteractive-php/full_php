<style type="text/css">
</style>

<div class="dv-process-buttons mt-2 ml-2 hidden">
    <div class="btn-group btn-group-devided">
        <?php //echo implode('', $this->actions['buttons']); ?>
    </div>     
</div>        
        
<div class="mv_tablelist_001_main">
    <table>
        <thead>
            <tr>
                <td></td>
                <td class="px-2"><div style="background: #F3F6F9;padding: 10px 30px 10px 30px;border-radius: 6px;color:#20BDBE;font-weight: bold;text-wrap: nowrap;">Барааны ангилал</div></td>
                <td class="px-2"><div style="background: #F3F6F9;padding: 10px 30px 10px 30px;border-radius: 6px;color:#20BDBE;font-weight: bold;text-wrap: nowrap;">Тоо ширхэг</div></td>
                <td class="px-2"><div style="background: #F3F6F9;padding: 10px 30px 10px 30px;border-radius: 6px;color:#20BDBE;font-weight: bold;text-wrap: nowrap;">Мөнгөн дүн</div></td>
            </tr>
        </thead>
        <tbody>
        <?php                                            
        $dataResult = $this->response['rows'];
        foreach ($dataResult as $index => $row) {
            $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
            if ($index < 5) {
        ?>        
                <tr>
                    <td><div style="margin-top: 5px;margin-bottom: 5px;background: #F3F6F9;border-radius: 6px;padding: 10px;font-weight: bold"><?php echo issetParam($row[$this->relationViewConfig['position-1']]) ?></div></td>
                    <td style="text-align: center;font-weight: bold;border-bottom: 1px solid #ccc;"><?php echo issetParam($row[$this->relationViewConfig['position-2']]) ?></td>
                    <td style="text-align: center;border-bottom: 1px solid #ccc;"><?php echo issetParam($row[$this->relationViewConfig['position-3']]) ?></td>
                    <td style="text-align: center;border-bottom: 1px solid #ccc;"><?php echo Number::formatMoney(issetParam($row[$this->relationViewConfig['position-4']])) ?></td>
                </tr>        
        <?php
            }
        } 
        ?>                          
        </tbody>
    </table>
</div>

<script type="text/javascript">
</script>