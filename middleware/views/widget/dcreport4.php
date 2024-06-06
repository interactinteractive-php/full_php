<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    $rateIem = 0;
    if(!empty($this->dcReport4))
        $repDate4 = $this->dcReport4['result'];
        //var_dump($repDate4); die();

        $floor = $lift = $game = $security = $parking = $sunwindow = $mansard = ' - ';
        $fastselladj = $gameadj = $windowadj = $yearadj = $parkingadj = '';
        if(!empty($this->dcReport))
             $repDate4 = $this->dcReport['result'];
    
            if($repDate4['corporatedtl2dv']['mansard'] == 'true'){
                $mansard = 'Тийм';
            }else{$mansard = 'Үгүй';}
    
            if($repDate4['corporatedtl4dv']['lift'] == '1'){
                $lift ='Тийм';
            }else{ $lift ='Үгүй';}
    
            if($repDate4['corporatedtl4dv']['game'] == '1'){
                $game ='Тийм';
            }else{ $game ='Үгүй';}
            if($repDate4['corporatedtl4dv']['parking'] == 'true'){
                $parking ='Тийм';
            }else{ $parking ='Үгүй';}
            if($repDate4['corporatedtl4dv']['security'] == '1'){
                $security ='Тийм';
            }else{ $security ='Үгүй';}
            
            if($repDate4['corporatedtl6dv']['flooradj'] === '0'){
                $floor ='Суурь үнэлгээнд үнэлсэн';
            }else{ $floor ='Үнэлсэн';}
    
            if($repDate4['corporatedtl6dv']['yearadj'] === '0'){
                $yearadj ='Суурь үнэлгээнд үнэлсэн';
            }else{ $yearadj ='Үнэлсэн';}
    
            if($repDate4['corporatedtl6dv']['parkingadj'] === '0'){
                $parkingadj ='Суурь үнэлгээнд үнэлсэн';
            }else{ $parkingadj ='Үнэлсэн';}
    
            if($repDate4['corporatedtl6dv']['windowadj'] === '0'){
                $windowadj ='Суурь үнэлгээнд үнэлсэн';
            }else{ $windowadj ='Үнэлсэн';}
           
            if($repDate4['corporatedtl6dv']['gameadj'] === '0'){
                $gameadj ='Суурь үнэлгээнд үнэлсэн';
            }else{ $gameadj ='Үнэлсэн';}
           
?>
<div class="dcReport5">
   <div class="dcreport_ dcreport4<?php echo $this->uniqId; ?>">
       <div class="row mt30">
           <div class="col-md-12 col-sm-12 repHead">
               <h2 class="m-0">Хашаа байшин үнэлгээний тайлан</h2>
           </div>
       </div> 
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <h3><?php echo Date::beforeDate('Y.m.d', '-1 day'); ?></h3>
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">
                        <h4>Үнэлгээчний нэр</h4>
                        <h4>Тусгай зөвшөөрлийн дугаар </h4>
                        <h4>Үнэлж буй хөрөнгийн төрөл </h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['valuationname']) ? $repDate4['valuationname'] : '');?></h4>
                        <h4><?php echo (isset($repDate4['investmenttype']) ? $repDate4['investmenttype'] : '');?></h4>
                        <h4><?php echo (isset($repDate4['permissionnumber']) ? $repDate4['permissionnumber'] : '');?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 mt-0">
                <h3>DC16325663</h3>
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">
                        <h4>Үнэлгээний компанийн нэр </h4>
                        <h4>Хүчинтэй хугацаа </h4>
                        <h4>Үнэлгээний зориулалт </h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['valcompanyname']) ? $repDate4['valcompanyname'] : '');?></h4>
                        <h4><?php echo (isset($repDate4['validdate']) ? $repDate4['validdate'] : '');?></h4>
                        <h4><?php echo (isset($repDate4['purpose']) ? $repDate4['purpose'] : '');?></h4>
                    </div>
                </div>
            </div>   
        </div>

       <h3>I. ХӨРӨНГИЙН ҮНЭЛГЭЭНИЙ ЗҮЙЛ</h3>
        <div class="row mt30">
            <div class="col-md-6 col-sm-6">
                <div class="row  main-card">
                    <h4 class="title"><b>Хаягийн мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Дүүрэг</h4>
                        <h4>Хороо</h4>
                        <h4>Хороолол</h4>
                        <h4>Гудамжны дугаар</h4>
                        <h4>Хашааны тоот</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['district']) ? $repDate4['corporatedtl1dv']['district'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['citystreet']) ? $repDate4['corporatedtl1dv']['citystreet'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['subdistrict']) ? $repDate4['corporatedtl1dv']['subdistrict'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['streetnumber']) ? $repDate4['corporatedtl1dv']['streetnumber'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['doornumber']) ? $repDate4['corporatedtl1dv']['doornumber'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <h4  class="title"><b>Газрын мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">             
                        <h4>Улсын бүртгэлийн дугаар</h4>
                        <h4>Эзэмшлийн дугаар</h4>
                        <h4>Газрын хэмжээ</h4>
                        <h4>Газрын төлөв</h4>
                        <h4>Засмал зам хүртэлх зай</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['placeregnumber']) ? $repDate4['corporatedtl1dv']['placeregnumber'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['placeownershiptype']) ? $repDate4['corporatedtl1dv']['placeownershiptype'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['placesize']) ? $repDate4['corporatedtl1dv']['placesize'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['placetype']) ? $repDate4['corporatedtl1dv']['placetype'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['distancetoroad']) ? $repDate4['corporatedtl1dv']['distancetoroad'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt30">
            <div class="col-md-6 col-sm-6  ">
                <div class="row main-card ">
                    <h4  class="title"> <b> Байршлын мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Автобусны буудал хүртэлх зай</h4>
                        <h4>Худаг хүртэлх зай</h4>
                      
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['distancetobusstop']) ? $repDate4['corporatedtl1dv']['distancetobusstop'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['distancetowell']) ? $repDate4['corporatedtl1dv']['distancetowell'] : '-'); ?></h4>
                    </div>
                </div>
            </div>  
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <h4  class="title"></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Ойр байрлах сургууль </h4>
                        <h4>Ойр байрлах цэцэрлэг </h4>
                     
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['schoolnearby']) ? $repDate4['corporatedtl1dv']['schoolnearby'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['kindergartennearby']) ? $repDate4['corporatedtl1dv']['kindergartennearby'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt30">
            <div class="col-md-6 col-sm-6  ">
                <div class="row main-card ">
                    <h4  class="title"> <b> Үндсэн байшингийн мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Улсын бүртгэлийн дугаар</h4>
                        <h4>Объектийн төрөл</h4>
                        <h4>Үндсэн хийц </h4>
                        <h4>Давхарын тоо </h4>
                        <h4>Барилгын суурь </h4>
                        <h4>Халаалтын систем </h4>
                        <h4>Ашиглалтанд орсон он </h4>
                      
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['stateregnumber']) ? $repDate4['corporatedtl1dv']['stateregnumber'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['objecttype']) ? $repDate4['corporatedtl1dv']['objecttype'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['buildingmaterial']) ? $repDate4['corporatedtl1dv']['buildingmaterial'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['floornumber']) ? $repDate4['corporatedtl1dv']['floornumber'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['fundament']) ? $repDate4['corporatedtl1dv']['fundament'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['heatingsystem']) ? $repDate4['corporatedtl1dv']['heatingsystem'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['buildyear']) ? $repDate4['corporatedtl1dv']['buildyear'] : '-'); ?></h4>
                    </div>
                </div>
            </div>  
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <h4  class="title"><b>Нэмж үнэлсэн байшингийн мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Улсын бүртгэлийн дугаар</h4>
                        <h4>Объектийн төрөл</h4>
                        <h4>Үндсэн хийц </h4>
                        <h4>Давхарын тоо </h4>
                        <h4>Барилгын суурь </h4>
                        <h4>Халаалтын систем </h4>
                        <h4>Ашиглалтанд орсон он </h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['buildregnumber']) ? $repDate4['corporatedtl2dv']['buildregnumber'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['objecttypename']) ? $repDate4['corporatedtl2dv']['objecttypename'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['undsenhiits']) ? $repDate4['corporatedtl2dv']['undsenhiits'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['davhariintoo']) ? $repDate4['corporatedtl2dv']['davhariintoo'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['fundament']) ? $repDate4['corporatedtl2dv']['fundament'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['halaaltiinsystem']) ? $repDate4['corporatedtl2dv']['halaaltiinsystem'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl2dv']['buildyear']) ? $repDate4['corporatedtl2dv']['buildyear'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt30">
            <div class="col-md-6 col-sm-6  ">
                <div class="row main-card ">
                    <h4  class="title"> <b> Хашааний мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Хэдэн тал</h4>
                        <h4>Хийц</h4>
                        <h4>Хаалга</h4>
                        <h4>&nbsp;</h4>
                        <h4>&nbsp;</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['fenceside']) ? $repDate4['corporatedtl1dv']['fenceside'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['fencematerial']) ? $repDate4['corporatedtl1dv']['fencematerial'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['fencedoor']) ? $repDate4['corporatedtl1dv']['fencedoor'] : '-'); ?></h4>
                        <h4>&nbsp;</h4>
                        <h4>&nbsp;</h4>
                    </div>
                </div>
            </div>  
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <h4  class="title"> <b> Тохижилт</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Явган хүний зам</h4>
                        <h4>Хүүхдийн тоглоомын талбай</h4>
                        <h4>Мод, бут, жимс тариалалтын талбай</h4>
                        <h4>Сүүдрэвч</h4>
                        <h4>Хамгаалалт дохиолол, гэрэлтүүлэг</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['roadwalk']) ? $repDate4['corporatedtl1dv']['roadwalk'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['playground']) ? $repDate4['corporatedtl1dv']['playground'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['orchard']) ? $repDate4['corporatedtl1dv']['orchard'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['shade']) ? $repDate4['corporatedtl1dv']['shade'] : '-'); ?></h4>
                        <h4><?php echo (isset($repDate4['corporatedtl1dv']['security']) ? $repDate4['corporatedtl1dv']['security'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt30 main-card map">
            <div class="col-md-6 col-sm-6">
                <h4><b> Байшингийн зураг</b></h4>
                <img src="middleware/assets/img/layout-themes/news/map.jpg" alt="Бүсчлэлийн зураг"/>
            </div>
            <div class="col-md-6 col-sm-6 mapimage">
                <h4> <b>Газрын Зураг</b></h4>
                <img src="middleware/assets/img/layout-themes/news/demo.jpg" alt="Бүсчлэлийн зураг"/>
            </div>
        </div>  

       <h3 class="rowTitle"> II. ХӨРӨНГИЙН ҮНЭЛГЭЭ </h3>
    
       <div class="row mt30">
           <div class="col-md-6 col-sm-6">
               <div class="row main-card">
                   <h4  class="borderTitle"><b>Бүсийн дундаж үнийн мэдээлэл</b></h4>
                   <div class="col-md-6" style="width: 100%;">
                       <ul class="lposition">
                           <li style="border-right:1px solid #a6a6a6;"><b>Бүсчлэлийн үнийн төлөв</b></li>
                           <li><p class="lpositionPara" style="text-transform: capitalize;"><?php echo (isset($repDate4['corporatedtl1dv']['regionpriceindex']) ? $repDate4['corporatedtl1dv']['regionpriceindex'] : '-'); ?></p></li>
                       <ul>
                   </div>
                   <div class="col-md-6" style="width: 100%;">
                       <ul class="lposition">
                           <li style="border-right:1px solid #a6a6a6;"><b>Үнийн индекс</b></li>
                           <li><p class="lpositionPara"><?php echo (isset($repDate4['corporatedtl1dv']['priceindex']) ? $repDate4['corporatedtl1dv']['priceindex'] : '-'); ?></p></li>
                       <ul>
                   </div>
               </div>
           </div>  
           <div class="col-md-6 col-sm-6">
               <div class="row main-card">
                   <h4  class="borderTitle"><b>Үнийн тогтвортой байдалд нөлөөлж буй</b></h4>
                   <div class="col-md-6 col-sm-6">
                       <ul class="r2position"> 
                           <li> <img src="middleware/assets/img/icon/step.png" alt="Бүсчлэлийн зураг"/></li>
                           <li><b><p>Байршил</p></b><p>Үнэлсэн</p></li>
                       </ul>
                   </div>
                   <div class="col-md-6 col-sm-6">
                       <ul class="r2position"> 
                           <li> <img src="middleware/assets/img/icon/calendar.png" alt="Бүсчлэлийн зураг"/></li>
                           <li><b><p>Ашиглалтад орсон оны тохируулга</p></b><p>Үнэлсэн</p></li>
                       </ul>
                   </div>
                   <div class="col-md-6 col-sm-6">
                       <ul class="r2position"> 
                           <li> <img src="middleware/assets/img/icon/price_index.png" alt="Бүсчлэлийн зураг"/></li>
                           <li><b>Үнийн индекс</b><p>Үнэлсэн</p></li>
                       </ul>
                   </div>
                   <div class="col-md-6 col-sm-6">
                       <ul class="r2position"> 
                           <li> <img src="middleware/assets/img/icon/price.png" alt="Бүсчлэлийн зураг"/></li>
                           <li><b><p>Түргэн борлогдох үнэ</p></b><p>Үнэлсэн</p></li>
                       </ul>
                   </div>
               </div>
           </div> 
       </div>

       <div class="row mt30">
           <div class="col-md-12 col-sm-12">
               <div class="row main-card position">
                   <h4 class="borderTitle"><b>Үнэлгээний үр дүн</b></h4>
                       <div class="col-md-6">
                           <ul class="rposition">
                               <li style="border-right:1px solid #a6a6a6;"><b>Байшин, дэд бүтцийн үнэлгээ</b><p><?php echo (isset($repDate4['corporatedtl1dv']['infrastructureprice']) ? $repDate4['corporatedtl1dv']['infrastructureprice'] : '-'); ?></p></li>
                               <li><b>Газар болон хашааны үнэлгээ</b><p><?php echo (isset($repDate4['corporatedtl1dv']['landandfenceprice']) ? $repDate4['corporatedtl1dv']['landandfenceprice'] : '-'); ?></p></li>
                           <ul>
                       </div>
                       <div class="col-md-6">
                           <ul class="rposition">
                               <li style="border-right:1px solid #a6a6a6;"><b>Түргэн борлогдох үнэ</b><p><?php echo (isset($repDate4['corporatedtl1dv']['fastsellprice']) ? $repDate4['corporatedtl1dv']['fastsellprice'] : '-'); ?></p></li>
                               <li><b>Харилцагчийн үнэлгээ</b><p><?php echo (isset($repDate4['corporatedtl1dv']['valuation']) ? $repDate4['corporatedtl1dv']['valuation'] : '-'); ?></p></li>
                           <ul>
                       </div>
                   </ul>
               </div>
           </div>
       </div>
       <div class="row mt30 methodDc">
        <h3 style="padding-left: 10px;">III. МЭДЭЭЛЭЛ БОЛОН АРГА ЗҮЙ </h3>         
            <div class="col-md-6 col-sm-6" style="display: flex;width: 100%;">
               <div class="row main-card" style="width: 100%;">
                   <div class="col-md-6 col-sm-6 infotbl1" style="width: 100%;">                   
                       <table style="width: 100%;">
                       <tr>
                           <th class="table1Col1">Датаны эх сурвалж</th>
                           <th class="tableCol2"><?php echo (isset($RateData['corporatedtl8dv']['infosrc']) ? $RateData['corporatedtl8dv']['infosrc'] : '-'); ?></th>
                       </tr>
                       <tr>
                           <th class="table1Col1">Үнэлгээний суурь дата</th>
                           <th class="tableCol2"><?php echo (isset($RateData['corporatedtl8dv']['infotime']) ? $RateData['corporatedtl8dv']['infotime'] : '-'); ?></th>
                       </tr>
                       <tr>
                           <th class="table1Col1">Үнэлгээнд ашигласан дата</th>
                           <th class="tableCol2"><?php echo (isset($RateData['corporatedtl8dv']['infonumber']) ? $RateData['corporatedtl8dv']['infonumber'] : '-'); ?></th>
                       </tr>
                       </table>
                   </div>
               </div>
               <div class="row main-card" style="width: 100%;">
                   <div class="col-md-6 col-sm-6 infotbl2" style="width: 100%;">
                        <table style="width: 100%;">
                            <tr>
                                <th class="table1Col1">Аргачлал боловсруулсан</th>
                                <th class="tableCol2"><?php echo (isset($RateData['corporatedtl8dv']['evaluation']) ? $RateData['corporatedtl8dv']['evaluation'] : '-'); ?></th>
                            </tr>
                            <tr>
                                <th class="table1Col1">Үнэлгээний аргачлал</th>
                                <th class="tableCol2"><?php echo (isset($RateData['corporatedtl8dv']['basevalue']) ? $RateData['corporatedtl8dv']['basevalue'] : '-'); ?></th>
                            </tr>
                            <tr>
                                <th class="table1Col1">Баталгаажуулсан үнэлгээчин</th>
                                <th class="tableCol2"><?php echo (isset($RateData['corporatedtl8dv']['method']) ? $RateData['corporatedtl8dv']['method'] : '-'); ?></th>
                            </tr>
                        </table>
                   </div>
               </div>
            </div>
            <div class="row mt30 infotblfoot">
                <div class="col-md-6 col-sm-6 infotbltxt" style="width:100%">
                    <div>
                        <p class="vnelgeeTable">Үнэлгээг Монгол улсын холбогдох хууль тогтоомжийн хүрээнд Олон улсын стандартын дагуу үнэлж аргачлалыг мэргэжлийн үнэлгээчин хянан баталгаажуулсан болно. Харилцагчийн өгсөн мэдээллийн үнэн бодит байдалд хариуцлага хүлээхгүй болно.</p>
                    </div>
                </div>  
            </div>
        </div>
        <h3>IV. ҮНЭЛГЭЭНИЙ ТОХИРУУЛГА</h3>
        <div class="row mt30">
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">             
                        <h4>Байгууллага :</h4>
                        <h4>Ажилтан : </h4>
                    </div>
                    <div class="col-md-6 col-sm-6 reportbold"> 
                        <h4><?php echo (isset($RateData['corporatedtl9dv']['company']) ? $RateData['corporatedtl9dv']['company'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl9dv']['employee']) ? $RateData['corporatedtl9dv']['employee'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">             
                        <h4>Нийт үнэлгээ:</h4>
                        <h4>Өөрчлөлт оруулсан шалтгаан:</h4>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <h4><input type="text" name="indicator200" class="form-control form-control-sm" value="<?php echo (isset($RateData['corporatedtl9dv']['valuation']) ? $RateData['corporatedtl9dv']['valuation'] : ''); ?>"></h4>
                        <select>
                            <option value="1">Үнэлгээ бага</option>
                            <option value="2">Тухайн хөрөнгийн онцлогийг тусгасан</option>
                            <option value="3">Үнэлгээ өндөр</option>
                        </select>
                        <input type="text" name="indicator201" class="form-control form-control-sm hidden" value="<?php echo (isset($RateData['corporatedtl9dv']['changereason']) ? $RateData['corporatedtl9dv']['changereason'] : '35'); ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt30 main-card">
            <div class="col-md-12 col-sm-12">
                <h4>Тайлбар</h4>
                <p><?php echo (isset($RateData['corporatedtl9dv']['description']) ? $RateData['corporatedtl9dv']['description'] : ''); ?></p>
                <textarea name="indicator202" class="form-control form-control-sm" value=""></textarea>
            </div>
            <div class="reportbutton">
                <button class="btn btn-secondary" onclick="callTempProcess()">Хадгалах</button>
                <button class="btn btn-secondary" onclick="RatePrint()">Хэвлэх</button>
            </div>
        </div>
   </div>
  
</div>
<script>
function RatePrint() {
    window.print();
}
function callTempProcess() {
    $.ajax({
        type: "post",
        url: "mdwidget/dcreportprocess",
        data: {
            indicator200: $(".dcreport2<?php echo $this->uniqId; ?>").find('input[name="indicator200"]').val(),
            indicator201: $(".dcreport2<?php echo $this->uniqId; ?>").find('input[name="indicator201"]').val(),
            indicator202: $(".dcreport2<?php echo $this->uniqId; ?>").find('textarea[name="indicator202"]').val(),
            kpiId: "<?php echo $this->resultKpi; ?>"
        }, 
        dataType: 'json', 
        success: function(data){
            PNotify.removeAll();
            new PNotify({
                title: data.status,
                text: data.message,
                type: data.status,
                sticker: false
            });            
        }
    });    
}
</script>

<style>
.ta-r{
    text-align:right !important;
}
.mt30{
    margin-top:15px !important;
    }
.dcReport5 {
    background-color: #f6f6f6 !important;
    overflow-x:hidden;
}

.dcReport5 h3 {
    font-weight: 600;
    text-transform: capitalize;
}
.dcReport5 .dcreport_ .repHead{
    padding-left: 0px !important;
    padding-right: 0px !important;
}
.dcReport5 .dcreport_ .repHead h2 {
    color: #fff;
    margin: 0 -8px;
    background: #3c3c3c;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    color: #fff;
    padding: 10px 20px;
}

.dcReport5 .dcreport_ .main-card {
    background: #fff;
    margin: 0px -8px;
    padding: 10px;
}

.dcReport5 .dcreport_ .main-card .title {
    padding-left: 15px;
}
.dcReport5 .dcreport_ select {
    width: 100%;
    padding: 5px 0;
}
   

.reportbutton .btn{
    margin-top:30px;
}

.dcReport5 .dcreport_ .main-card button {
    float: right;
    margin-right: 15px;
    margin-bottom: 30px;
}

.dcReport5 .dcreport_ .main-card.map {
    padding: 20px 10px 15px 10px;
}
.dcReport5 .dcreport_ .main-card.map .mapimage h4{
    padding-left:10px;
}

.dcReport5 .dcreport_ .main-card.map img {
    width: 100%;
    height: 300px;
}

.dcReport5 .dcreport_ .main-card.position b {
    font-size: 15px;
}

.dcReport5 .dcreport_ .main-card.position p {
    color: #00a9e4;
    font-size: 15px;
    padding-top: 7px;
    font-weight: 700;
}

.dcReport5 .dcreport_ .main-card .rposition {
    padding: 0;
    border: 1px solid;
    display: inline-block;
    width: 95%;
    margin: 5px 16px;
}

.dcReport5 .dcreport_ .main-card .rposition li {
    float: left;
    width: 50%;
    text-align: center;
    padding: 8px 0;
    list-style-type: none;
}

.dcReport5 .dcreport_ .main-card .rposition li:last-child {
    border-left: 1px solid;
}

.dcReport5 .dcreport_ .main-card .rposition li h3 {
    margin: 10px;
}

.dcReport5 .dcreport_ .main-card .r2position {
    padding: 0;
    display: inline-block;
    width: 100%;
    margin: 4px 0;
}

.dcReport5 .dcreport_ .main-card .r2position li {
    float: left;
    text-align: center;
    padding: 6px 0;
    list-style-type: none;
}

.dcReport5 .dcreport_ .main-card .r2position li:first-child {
    width: 40%;
}

.dcReport5 .dcreport_ .main-card .r2position li:first-child img {
    width: 60px;
}

.dcReport5 .dcreport_ .main-card .r2position li:last-child {
    width: 58%;
}

.dcReport5 .dcreport_ .main-card .r2position li h3 {
    margin: 10px;
}

.dcReport5 .dcreport_ .methodDc {
    color: #fff;
    margin-left: -8px;
    margin-right: -8px;
}
.dcReport5 .dcreport_ .methodDc h3 {
    color: #000;
}
table, th, td {
    border: 1px solid #3c3c3c;
    border-collapse: collapse;
    font-weight: normal;
    font-size: 17px;
}
th, td {
    padding: 4px;
}
.table1Col1{
    background-color: #3c3c3c;
    width: 50%;
    padding-left: 10px;
}
.tableCol2{
    text-align: center;
    width: 50%;
    color: #000;
}
.table2Col1{
    background-color: #80b3ff;
    width: 50%;
    padding-left: 10px;
    color: #fff;
}
.infotbl1{
    padding: 0 4% 0 3%;
}
.infotbl2{
    padding: 0 4% 0 4%;
}
.infotblfoot{
    background-color: #fff;
    color: #000;
    text-align: center;
}
.infotbltxt{
    font-size: 14px;
    margin: 10px 0px;
}
.cardMapHead{
    background-color: #80b3ff;
    padding: 3px 0;
    color: #fff;
    font-size: 15px;
    text-align: center;
}
.cardMapImageHead{
    padding: 0 !important;
}
.cardMapImageHead img{
    padding: 0 15px;
}
.rowTitle{
    padding: 0 15px;
    color: #80b3ff;
}
.dcReport5 .dcreport_ .main-card .lposition {
    padding: 0;
    border: 1px solid;
    display: inline-block;
    width: 95%;
    margin: 5px 16px;
    height: 80px;
}

.dcReport5 .dcreport_ .main-card .lposition li {
    float: left;
    width: 50%;
    text-align: center;
    padding: 8px 0;
    height: 100%;
    list-style-type: none;
    display: flex;
    flex-direction: column; 
    justify-content: center;
    align-items: center;
}

.dcReport5 .dcreport_ .main-card .lposition li:last-child {
    border-left: 1px solid;
}

.dcReport5 .dcreport_ .main-card .lposition li h3 {
    margin: 10px;
}

.lpositionPara{
    color: #80b3ff;
    font-weight: 700;
    font-size: 15px;
}
.borderTitle{
    padding-left: 0;
    padding-bottom: 2px;
}
.vnelgeeTable{
    width: 100%;
    padding-left: 5px;
    text-align: center;
    font-size: 12px;
}
.vnelgeeTable{
    padding: 0 60px;
}
/*# sourceMappingURL=main.css.map */
</style>

