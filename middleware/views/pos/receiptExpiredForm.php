<table class="table table-no-bordered receipt-result-view mb5">
    <tbody>
        <tr>
            <td style="width: 25%" class="text-right">Хяналтын дугаар:</td>
            <td style="width: 25%; font-size: 18px; font-weight: bold"><?php echo $this->receiptData['receiptNumber']; ?></td>
            <td style="width: 25%;" class="text-right">Бүртгэсэн огноо:</td>
            <td style="width: 25%" class="bold"><?php echo date('Y-m-d H:i', substr($this->receiptData['receiptDate'], 0, 10)); ?></td>
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
            <td class="text-right">Хүчинтэй хугацаа:</td>
            <td class="bold"><?php echo date('Y-m-d H:i', substr($this->receiptData['receiptExpireDate'], 0, 10)); ?></td>
        </tr>
        <tr>
            <td class="text-right">Хэвлэсэн огноо:</td>
            <td class="bold"><?php echo date('Y-m-d H:i', substr($this->receiptData['receiptPrintedDate'], 0, 10)); ?></td>
            <td class="text-right">Төлөв:</td>
            <td class="bold">
                <?php 
                if ($this->receiptData['status'] == 1) {
                    echo 'Идэвхитэй'; 
                } elseif ($this->receiptData['status'] == 0) {
                    echo 'Идэвхигүй'; 
                } elseif ($this->receiptData['status'] == 2) {
                    echo 'Энгийн жор'; 
                } elseif ($this->receiptData['status'] == 3) {
                    echo 'Худалдаж авсан'; 
                } elseif ($this->receiptData['status'] == 4) {
                    echo 'Хянасан'; 
                } elseif ($this->receiptData['status'] == 5) {
                    echo 'Хугацаа дууссан'; 
                }
                ?>
            </td>
        </tr>
        <tr>
            <td class="text-right">Өрхийн эмнэлэг сум, дүүрэг:</td>
            <td class="bold"><?php echo $this->receiptData['hosOfficeName']; ?></td>
            <td class="text-right">Өрхийн эмнэлгийн баг, хороо:</td>
            <td class="bold"><?php echo $this->receiptData['hosSubOffName']; ?></td>
        </tr>
        <tr>
            <td class="text-right">Эмчийн шифр код:</td>
            <td class="bold"><?php echo $this->receiptData['cipherCode']; ?></td>
            <td class="text-right">Эмийн тоо:</td>
            <td class="bold"><?php echo $this->receiptData['tbltCount']; ?></td>
        </tr>
        <tr>
            <td class="text-right">Өрхийн эмнэлгийн нэр:</td>
            <td colspan="3" class="bold"><?php echo $this->receiptData['hosName']; ?></td>
        </tr>
    </tbody>
</table>

<table class="table table-bordered table-hover mb0">
    <thead>
        <tr>
            <th style="width: 20px">№</th>
            <th>Эмийн нэр</th>
            <th class="text-right">Эмийн тун хэмжээ</th>
            <th class="text-center">Хөнгөлөлттэй эсэх</th>
            <th>Эмийн тэмдэглэл</th>
            <th>Эмийн дугаар</th>
            <th class="text-center">Төлөв</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($this->receiptData['receiptDetails'])) {
            foreach ($this->receiptData['receiptDetails'] as $k => $dtl) {
        ?>
            <tr>
                <td><?php echo ++$k; ?></td>
                <td><?php echo $dtl['tbltName']; ?></td>
                <td class="text-right font-weight-bold"><?php echo $dtl['tbltSize']; ?></td>
                <td class="text-center"><?php echo ($dtl['isDiscount'] == 1 ? 'Тийм' : 'Үгүй'); ?></td>
                <td><?php echo $dtl['tbltDesc']; ?></td>
                <td><?php echo $dtl['tbltId']; ?></td>
                <td class="text-center"><?php echo ($dtl['status'] == 1 ? 'Борлуулсан' : 'Борлуулаагүй'); ?></td>
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
    padding: 7px 4px;
}
</style>