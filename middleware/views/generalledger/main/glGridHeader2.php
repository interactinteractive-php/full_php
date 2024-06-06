<tr>
    <th style='width:20px;' rowspan='2' class='text-center rowNumber'><?php echo Lang::lineDefault('PL_0045', 'Багц'); ?></th>
    <th style='min-width:140px;' rowspan='2' class='text-center'><?php echo Lang::lineDefault('MET_330575', 'Дансны код'); ?></th>
    <th style='min-width:190px;' rowspan='2' class='text-center'><?php echo Lang::lineDefault('PL_20104', 'Дансны нэр'); ?></th>
    <th style='min-width:200px;' rowspan='2' class='text-center customPartner'><?php echo Lang::lineDefault('FIN_1005', 'Харилцагч'); ?></th>
    <th style='min-width:200px;' rowspan='2' class='text-center glRowExpenseCenter'><?php echo Lang::lineDefault('PL_1015', 'Хариуцлагын төв'); ?></th>
    <th style='min-width:180px;' rowspan='2' class='text-center glRowDescr'><?php echo Lang::lineDefault('FIN_1006', 'Гүйлгээний утга'); ?></th>
    <?php if (Config::getFromCache('isGLDescrEnglish')) { ?>
        <th style='min-width:180px;' rowspan='2' class='text-center glRowDescr2'><?php echo 'Transaction value'; ?></th>
    <?php } ?>    
    <?php if (Config::getFromCache('ISSHOWGLDTLREFNUMBER')) { ?>
        <th style='min-width:120px;' rowspan='2' class='text-center glRowRefNumber'><?php echo 'Гүйлгээний дугаар'; ?></th>
    <?php } ?>    
    <th style='min-width:50px;' rowspan='2' class='text-center glRowCurrency'><i class="fa fa-money"></i></th>
    <th style='min-width:60px;max-width:60px;width:60px;' rowspan='2' class='text-center glRowRate'><?php echo Lang::lineDefault('rate', 'Ханш'); ?></th>
    <th style='min-width:172px;' colspan='2' class='text-center'><?php echo Lang::lineDefault('FIN_1007', 'Дебит'); ?></th>
    <th style='min-width:172px;' colspan='2' class='text-center'><?php echo Lang::lineDefault('FIN_1008', 'Кредит'); ?></th>
    <th style='width:25px;min-width:25px;font-size:10px !important;' rowspan='2'><?php echo Lang::lineDefault('PL_2074', 'НӨАТ'); ?></th>
    <?php if ($this->incomeTaxDeduction === '1') { ?>
        <th style='width:25px;min-width:25px;font-size:10px !important;' rowspan='2'><?php echo Lang::lineDefault('FIN_1009', 'ХХАОТ'); ?></th>
    <?php } ?>
    <th style='min-width:82px;' rowspan='2'></th>
</tr>
<tr>
    <th class='text-center'><?php echo Lang::lineDefault('FIN_00758', 'валют'); ?></th>
    <th class='text-center'><?php echo Lang::lineDefault('FIN_00764', 'төгрөг'); ?></th>
    <th class='text-center'><?php echo Lang::lineDefault('FIN_00758', 'валют'); ?></th>
    <th class='text-center'><?php echo Lang::lineDefault('FIN_00764', 'төгрөг'); ?></th>
</tr>