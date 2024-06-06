<div class="col-md-12">
     <div class="table-scrollable">
        <table class="table table-sm table-bordered table-hover">
            <thead>
                <tr style="background-color: #EDEDED; font-weight: 600;">
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">№</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Баримтын дугаар</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Харьцсан данс</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Код</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Харилцагч</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Огноо</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Ханш</td>
                    <td class="text-center" colspan="4">Гүйлгээ</td>
                    <td class="text-center" rowspan="2" style="vertical-align: middle;">Баримтын утга</td>
                </tr>
                <tr style="background-color: #EDEDED; font-weight: 600;">
                    <td class="text-center">ДТ /валют/</td>
                    <td class="text-center">ДТ /төгрөг/</td>
                    <td class="text-center">КТ /валют/</td>
                    <td class="text-center">КТ /төгрөг/</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $sumDbBase = 0;
                    $sumDb = 0;
                    $sumCrBase = 0;
                    $sumCr = 0;
                    foreach($this->dataList as $key => $value){
                        echo '<tr>';
                        echo '<td>'.++$key.'</td>';
                        echo '<td class="text-center">'.$value['BOOK_NUMBER'].'</td>';
                        echo '<td class="text-center">'.$value['TRG_ACCOUNT_CODE'].'</td>';
                        echo '<td class="text-center">'.$value['CUSTOMER_CODE'].'</td>';
                        echo '<td>'.$value['CUSTOMER_NAME'].'</td>';
                        echo '<td>'.Date::format('Y-m-d', $value['BOOK_DATE']).'</td>';
                        echo '<td class="text-right">'.$value['RATE'].'</td>';
                        echo '<td class="text-right">'.Number::formatMoney($value['DEBIT_AMOUNT_BASE'], 2).'</td>';
                        echo '<td class="text-right">'.Number::formatMoney($value['DEBIT_AMOUNT'], 2).'</td>';
                        echo '<td class="text-right">'.Number::formatMoney($value['CREDIT_AMOUNT_BASE'], 2).'</td>';
                        echo '<td class="text-right">'.Number::formatMoney($value['CREDIT_AMOUNT'], 2).'</td>';
                        echo '<td>'.$value['DESCRIPTION'].'</td>';
                        echo '</tr>';
                        $sumDbBase = $sumDbBase + $value['DEBIT_AMOUNT_BASE'];
                        $sumDb = $sumDb + $value['DEBIT_AMOUNT'];
                        $sumCrBase = $sumCrBase + $value['CREDIT_AMOUNT_BASE'];
                        $sumCr = $sumCr + $value['CREDIT_AMOUNT'];
                    }
                ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #EDEDED; font-weight: 600;">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right"><?php echo Number::formatMoney($sumDbBase, 2); ?></td>
                    <td class="text-right"><?php echo Number::formatMoney($sumDb, 2); ?></td>
                    <td class="text-right"><?php echo Number::formatMoney($sumCrBase, 2); ?></td>
                    <td class="text-right"><?php echo Number::formatMoney($sumCr, 2); ?></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>