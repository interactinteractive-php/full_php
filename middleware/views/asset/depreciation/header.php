<tr>
    <th class='text-center rowNumber' style="width:30px;">№</th>
    <th class='text-center rowNumber' style="min-width:30px;"></th>
    <th class='text-center' data-class='bp-head-sort' style="width:190px;min-width:190px;"><?php echo Lang::lineDefault('1557885514958_1639119000775511', 'Данс'); ?></th>
    <th class='text-center' style="width:70px;min-width:70px;"><?php echo Lang::lineDefault('FIN_CODE', 'Код'); ?></th>
    <?php if (Config::getFromCacheDefault('IS_SHOW_CUSTOMER_DEPR', null, '') == '1') { ?>
        <th class='text-center' style="width:190px;min-width:190px;"><?php echo 'Харилцагч'; ?></th>
    <?php } ?>
    <th class='text-center' style="width:150px;min-width:150px;"><?php echo Lang::lineDefault('1461563549096_1632291822285712', 'Үндсэн хөрөнгө'); ?></th>
    <th class='text-center' style="width:100px;min-width:100px;"><?php echo Lang::lineDefault('FIN_01340', 'Баркод'); ?></th>
    <th class='text-center' style="width:70px;min-width:70px;"><?php echo Lang::lineDefault('PL_2056', 'Сериал'); ?></th>
    <th class='text-center rowNumber' style="min-width:30px;"><?php echo Lang::lineDefault('1564707378427_1632205843222910', 'Х.Н'); ?></th>
    <th class='text-center' style="width:90px;min-width:90px;"><?php echo Lang::lineDefault('start_owner_date', 'Ашиглаж эхэлсэн огноо'); ?></th>
    <th class='text-center' style="width:50px;"><?php echo Lang::lineDefault('1458153727213_1632217726871490', 'Сүүлд элэгдүүлсэн огноо'); ?></th>
    <th class='text-center' style="width:30px;"><?php echo Lang::lineDefault('FIN_1015', 'Элэгдэл тооцох өдөр'); ?></th>
    <th class='text-center' style="width:50px;"><?php echo Lang::lineDefault('1466751723184_1632218539412684', 'Үлдэгдэл тоо'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('PL_2051', 'Нэгж өртөг'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('PL_2050', 'Нийт өртөг'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('PL_2048', 'Анхны өртөг'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('FIN_1025', 'Санхүү тооцох өртөг'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('FIN_1026', 'Татвар тооцох өртөг'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('FIN_1027', 'Санхүүгийн элэгдэл'); ?></th>
    <th class='text-center stnHead' style="width:80px;"><?php echo Lang::lineDefault('FIN_1028', 'Татварын элэгдэл'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('FIN_CURRENT_COST', 'Одоогийн өртөг'); ?></th>
    <th class='text-center' style="width:110px;min-width:110px;"><?php echo Lang::lineDefault('PL_2047', 'ХУР.ЭЛЭГДЭЛ'); ?></th>
    <th class='text-center' style="width:150px;"><?php echo Lang::lineDefault('fin_9911', 'ТАТ.ХУР.ЭЛЭГДЭЛ'); ?></th>
    <th class='text-center' style="width:60px;"><?php echo Lang::lineDefault('FIN_1029', 'Сан.жил/норм'); ?></th>
    <th class='text-center' style="width:60px;"><?php echo Lang::lineDefault('FIN_1030', 'Тат.жил/норм'); ?></th>
    <th class='text-center' style="width:50px;"><?php echo Lang::lineDefault('HR_00261', 'Үйлдлүүд'); ?></th>
</tr>
<tr class="bp-filter-row">
    <th class="rowNumber"></th>
    <th class="rowNumber" style="min-width:30px;"></th>
    <th><input type="text" data-fieldname="accountname" data-condition="like"/></th>
    <?php if (Config::getFromCacheDefault('IS_SHOW_CUSTOMER_DEPR', null, '') == '1') { ?>
    <th><input type="text" data-fieldname="customercode" data-condition="like"/></th>
    <?php } ?>
    <th><input type="text" data-fieldname="assetcode" data-condition="like"/></th>
    <th><input type="text" data-fieldname="assetname" data-condition="like"/></th>
    <th><input type="text" data-fieldname="assetnumber" data-condition="like"/></th>
    <th><input type="text" data-fieldname="serialnumber" data-condition="like"/></th>
    <th class="rowNumber"><input type="text" data-fieldname="measurecode" data-condition="like"/></th>
    <th><input type="text" class="dateMaskInit" data-fieldname="disposeddate" data-condition="="/></th>
    <th><input type="text" class="dateMaskInit" data-fieldname="actiondate" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="countqty" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="inqty" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="incost" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="incostamt" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="originalcost" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="calculatecost" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="salvageamt" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="indepramt" data-condition="="/></th>
    <th class='stnHead'><input type="text" class="bigdecimalInit" data-fieldname="standartindepramt" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="actualcost" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="outdepramt" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="intaxdepramt" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="usageyear" data-condition="="/></th>
    <th><input type="text" class="bigdecimalInit" data-fieldname="stusageyear" data-condition="="/></th>
    <th></th>
</tr>