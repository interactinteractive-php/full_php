<th style='width:20px;' class='text-center rowNumber'><?php echo Lang::lineDefault('PL_0045', 'Багц'); ?></th>
<th style='min-width:130px;' class='text-center'><?php echo Lang::lineDefault('MET_330575', 'Дансны код'); ?></th>
<th style='min-width:230px;' class='text-center'><?php echo Lang::lineDefault('PL_20104', 'Дансны нэр'); ?></th>
<th class='text-center customPartner' style='min-width:200px;'><?php echo Lang::lineDefault('FIN_1005', 'Харилцагч'); ?></th>
<th class='text-center glRowExpenseCenter' style='min-width:200px;'><?php echo Lang::lineDefault('PL_1015', 'Хариуцлагын төв'); ?></th>
<th style='min-width:180px;' class='text-center glRowDescr'><?php echo Lang::lineDefault('FIN_1006', 'Гүйлгээний утга'); ?></th>
<?php if (Config::getFromCache('isGLDescrEnglish')) { ?>
    <th style='min-width:180px;' class='text-center glRowDescr2'><?php echo 'Transaction value'; ?></th>
<?php } ?>
<?php if (Config::getFromCache('ISSHOWGLDTLREFNUMBER')) { ?>
    <th style='min-width:120px;' class='text-center glRowRefNumber'><?php echo 'Гүйлгээний дугаар'; ?></th>
<?php } ?>
<th style='width:50px;min-width:50px;' class='text-center glRowCurrency'><i class="fa fa-money"></i></th>
<th data-usebase='usebase' style='min-width:60px;max-width:60px;width:60px;' class='text-center usebase glRowRate'><?php echo Lang::lineDefault('rate', 'Ханш'); ?></th>
<th data-usebase='usebase' class='usebase'>Дебит валют</th>
<th style="min-width:115px;" class='text-center'><?php echo Lang::lineDefault('FIN_1007', 'Дебит дүн'); ?></th>
<th data-usebase='usebase' class='usebase'>Кредит валют</th>
<th style="min-width:115px;" class='text-center'><?php echo Lang::lineDefault('FIN_1008', 'Кредит дүн'); ?></th>
<th style='width:25px;min-width:30px;font-size:10px !important;'><?php echo Lang::lineDefault('PL_2074', 'НӨАТ'); ?></th>
<?php if ($this->incomeTaxDeduction === '1') { ?>
<th style='width:25px;min-width:30px;font-size:10px !important;'><?php echo Lang::lineDefault('FIN_1009', 'ХХАОТ'); ?></th>
<?php } ?>
<th style='min-width:82px;'></th>