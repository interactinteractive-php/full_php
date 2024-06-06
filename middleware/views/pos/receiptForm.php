<table class="table table-no-bordered receipt-result-view mb5">
    <tbody>
        <tr>
            <td style="width: 15%" class="text-right">Хяналтын дугаар:</td>
            <td style="width: 38%; font-size: 18px; font-weight: bold"><?php echo $this->receiptData['receiptNumber']; ?></td>
            <td style="width: 22%;" class="text-right">Хүчинтэй хугацаа:</td>
            <td style="width: 25%" class="bold"><?php echo date('Y-m-d H:i', substr($this->receiptData['receiptExpireDate'], 0, 10)); ?></td>
        </tr>
        <tr>
            <td class="text-right">Регистрийн дугаар:</td>
            <td class="bold"><?php echo $this->receiptData['patientRegNo']; ?></td>
            <td class="text-right">Хуудасны ангилал:</td>
            <td class="bold">
                <?php 
                if ($this->receiptData['receiptType'] == 1) {
                    echo $this->lang->line('POS_0204');
                } elseif ($this->receiptData['receiptType'] == 2) {
                    echo $this->lang->line('POS_0065');
                } elseif ($this->receiptData['receiptType'] == 3) {
                    echo '13А';
                } elseif ($this->receiptData['receiptType'] == 4) {
                    echo $this->lang->line('POS_0066');
                } elseif ($this->receiptData['receiptType'] == 5) {
                    echo $this->lang->line('POS_0067');
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="text-right">Овог нэр:</td>
            <td class="bold"><?php echo $this->receiptData['patientLastName'].' '.$this->receiptData['patientFirstName']; ?></td>
            <td class="text-right">Эмчийн шифр код:</td>
            <td class="bold"><?php echo $this->receiptData['cipherCode']; ?></td>
        </tr>
        <tr>
            <td class="text-right">Онош:</td>
            <td class="bold"><?php echo $this->receiptData['receiptDiag']; ?></td>
            <td class="text-right">Өрхийн эмнэлэг сум, дүүрэг:</td>
            <td class="bold"><?php echo $this->receiptData['hosOfficeName']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-hover receipt-result-view-dtl mb0">
    <thead>
        <tr>
            <th style="width: 20px">№</th>
            <th style="width: 60px">Эмийн ID</th>
            <th>Эмийн нэр</th>
            <th class="text-right">Эмийн тун хэмжээ</th>
            <th>Эмийн хэлбэр</th>
            <th>Эмийн тэмдэглэл</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($this->receiptData['receiptDetails'])) {
            foreach ($this->receiptData['receiptDetails'] as $k => $dtl) {
        ?>
            <tr data-tbltId="<?php echo $dtl['tbltId']; ?>">
                <td class="text-center"><?php echo ++$k; ?></td>
                <td><?php echo $dtl['tbltId']; ?></td>
                <td><?php echo $dtl['tbltName']; ?></td>
                <td class="text-right font-weight-bold"><?php echo $dtl['tbltSize']; ?></td>
                <td>
                <?php 
                if (isset($dtl['tbltType']) && $dtl['tbltType']) {
                    
                    if ($dtl['tbltType'] == '0') {
                        
                        $tbltTypeLabel = 'Хүүхэд';
                        
                    } elseif ($dtl['tbltType'] == '1') {
                        
                        $tbltTypeLabel = 'Насанд хүрэгч';
                        
                    } elseif ($dtl['tbltType'] == '2') {
                        
                        $tbltTypeLabel = 'Шахмал';
                        
                    } elseif ($dtl['tbltType'] == '3') {
                        
                        $tbltTypeLabel = 'Капсул';
                        
                    } elseif ($dtl['tbltType'] == '4') {
                        
                        $tbltTypeLabel = 'Лаа';
                        
                    } elseif ($dtl['tbltType'] == '5') {
                        
                        $tbltTypeLabel = 'Крем';
                        
                    } elseif ($dtl['tbltType'] == '6') {
                        
                        $tbltTypeLabel = 'Сироп';
                        
                    } elseif ($dtl['tbltType'] == '7') {
                        
                        $tbltTypeLabel = 'Гель';
                        
                    } elseif ($dtl['tbltType'] == '8') {
                        
                        $tbltTypeLabel = 'Тосон түрхлэг';
                        
                    } elseif ($dtl['tbltType'] == '9') {
                        
                        $tbltTypeLabel = 'Цацлага';
                        
                    } elseif ($dtl['tbltType'] == '10') {
                        
                        $tbltTypeLabel = 'Дусаалга';
                    }
                    
                    echo $tbltTypeLabel;
                }
                ?>
                </td>
                <td><?php echo $dtl['tbltDesc']; ?></td>
            </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<style type="text/css">
table.receipt-result-view>tbody>tr>td {
    line-height: 14px !important;
    padding: 5px 4px;
}
table.receipt-result-view-dtl>thead>tr>th {
    line-height: 14px !important;
    padding: 5px 4px;
    font-weight: 600;
}
table.receipt-result-view-dtl>tbody>tr>td {
    line-height: 14px !important;
    padding: 5px 4px;
}
</style>