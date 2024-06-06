<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.');
    $rateIem = 0;
    $floor = $lift = $game = $security = $parking = $sunwindow = $mansard = '-';
    $fastselladj = $gameadj = $windowadj = $yearadj = $parkingadj = '';
    if(!empty($this->dcReport))
         $RateData = $this->dcReport['result'];

        if($RateData['corporatedtl2dv']['mansard'] == 'true'){
            $mansard = 'Тийм';
        }
        if($RateData['corporatedtl4dv']['sunwindow'] == 'true'){
            $sunwindow = 'Тийм';
        }

        if($RateData['corporatedtl4dv']['lift'] == '1'){
            $lift ='Тийм';
        }

        if($RateData['corporatedtl4dv']['game'] == '1'){
            $game ='Тийм';
        }else{ $game ='Үгүй';}
        if($RateData['corporatedtl4dv']['parking'] == 'true'){
            $parking ='Тийм';
        }else{ $parking ='Үгүй';}
        if($RateData['corporatedtl4dv']['security'] == '1'){
            $security ='Тийм';
        }else{ $security ='Үгүй';}
        
        if($RateData['corporatedtl6dv']['flooradj'] === '0'){
            $floor ='Суурь үнэлгээнд үнэлсэн';
        }else{ $floor ='Үнэлсэн';}

        if($RateData['corporatedtl6dv']['yearadj'] === '0'){
            $yearadj ='Суурь үнэлгээнд үнэлсэн';
        }else{ $yearadj ='Үнэлсэн';}

        if($RateData['corporatedtl6dv']['parkingadj'] === '0'){
            $parkingadj ='Суурь үнэлгээнд үнэлсэн';
        }else{ $parkingadj ='Үнэлсэн';}

        if($RateData['corporatedtl6dv']['windowadj'] === '0'){
            $windowadj ='Суурь үнэлгээнд үнэлсэн';
        }else{ $windowadj ='Үнэлсэн';}
        if($RateData['corporatedtl6dv']['fastselladj'] === '0'){
            $fastselladj ='Суурь үнэлгээнд үнэлсэн';
        }else{ $fastselladj ='Үнэлсэн';}
        if($RateData['corporatedtl6dv']['gameadj'] === '0'){
            $gameadj ='Суурь үнэлгээнд үнэлсэн';
        }else{ $gameadj ='Үнэлсэн';}
       
?>
<div id="dcreport" class="dcReport">
    <div class="dcreport_ dcreport1<?php echo $this->uniqId; ?>">
        <div class="row mt30">
            <div class="col-md-12 col-sm-12 repHead">
                <h2 class="m-0">Орон сууцны үнэлгээний тайлан</h2>
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
                        <h4><?php echo $RateData['valuationname'] ;?></h4>
                        <h4><?php echo (isset($RateData['investmenttype']) ? $RateData['investmenttype'] : '');?></h4>
                        <h4><?php echo (isset($RateData['permissionnumber']) ? $RateData['permissionnumber'] : '');?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 mt-0">
                <h3>DC16325663</h3>
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">
                        <h4>Үнэлгээний компанийн нэр</h4>
                        <h4>Хүчинтэй хугацаа</h4>
                        <h4>Үнэлгээний зориулалт</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo $RateData['valcompanyname']; ?></h4>
                        <h4><?php echo $RateData['validdate']; ?></h4>
                        <h4><?php echo $RateData['purpose']; ?></h4>
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
                        <h4>Ерөнхий бүс</h4>
                        <h4>Дүүрэг</h4>
                        <h4>Бүсчлэл</h4>
                        <h4>Хотхоны нэр</h4>
                        <h4>Барилгын нэр дугаар</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($RateData['corporatedtl1dv']['mainzone']) ? $RateData['corporatedtl1dv']['mainzone'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl1dv']['district']) ? $RateData['corporatedtl1dv']['district'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl1dv']['zone']) ? $RateData['corporatedtl1dv']['zone'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl1dv']['townname']) ? $RateData['corporatedtl1dv']['townname'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl1dv']['apartnumber']) ? $RateData['corporatedtl1dv']['apartnumber'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6  ">
                <div class="row main-card ">
                    <h4  class="title"> <b> Байрны мэдээлэл </b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Талбайн хэмжээ</h4>
                        <h4>Өрөөний тоо</h4>
                        <h4>Давхарын тоо</h4>
                        <h4>Мансартын давхартай эсэх</h4>
                        <h4>Наран талдаа цонхтой эсэх</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($RateData['corporatedtl2dv']['fieldsize']) ? $RateData['corporatedtl2dv']['fieldsize'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl2dv']['roomnumber']) ? $RateData['corporatedtl2dv']['roomnumber'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl2dv']['floornumber']) ? $RateData['corporatedtl2dv']['floornumber'] : '-'); ?></h4>
                        <h4><?php echo $mansard ?></h4>
                        <h4><?php echo $sunwindow; ?></h4>
                    </div>
                </div>
            </div>   
        </div>

        <div class="row mt30">
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <h4 class="title"><b> Барилгын үндсэн мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">             
                        <h4>Ашиглалтанд орсон он</h4>
                        <h4>Хийц</h4>
                        <h4>Давхарын тоо</h4>
                        <h4>Хотын төв хүртэлх зай , км </h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo (isset($RateData['corporatedtl3dv']['year']) ? $RateData['corporatedtl3dv']['year'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl3dv']['design']) ? $RateData['corporatedtl3dv']['design'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl3dv']['floor']) ? $RateData['corporatedtl3dv']['floor'] : '-'); ?></h4>
                        <h4><?php echo (isset($RateData['corporatedtl3dv']['distance']) ? $RateData['corporatedtl3dv']['distance'] : '-'); ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <h4  class="title"><b> Барилгын нэмэлт мэдээлэл</b></h4>
                    <div class="col-md-6 col-sm-6">
                        <h4>Лифттэй эсэх</h4>
                        <h4>Тоглоомын талбайтай эсэх</h4>
                        <h4>Дулаан зогсоолтой эсэх</h4>
                        <h4>Нэгдсэн хамгаалалтай эсэх</h4>
                    </div>
                    <div class="col-md-6 col-sm-6 ta-r reportbold">
                        <h4><?php echo $lift; ?></h4>
                        <h4><?php echo $game; ?></h4>
                        <h4><?php echo $parking; ?></h4>
                        <h4><?php echo $security ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt30 main-card map">
            <div class="col-md-6 col-sm-6">
                <h4><b> Бүсчлэлийн зураг</b></h4>
                <img src="middleware/assets/img/layout-themes/news/map.jpg" alt="Бүсчлэлийн зураг"/>
            </div>
            <div class="col-md-6 col-sm-6 mapimage">
                <h4> <b>Хөрөнгийн Зураг</b></h4>
                <img src="middleware/assets/img/layout-themes/news/demo.jpg" alt="Бүсчлэлийн зураг"/>
            </div>
        </div>  

        <h3> II. ХӨРӨНГИЙН ҮНЭЛГЭЭ </h3>
        <div class="row mt30 main-card ">
            <div class="col-md-12 col-sm-12">
                <h4><b> Ерөнхий мэдээлэл</b></h4>
                <p>Тухайн хорооны мэдээллийг инфографик байдлаар оруулах</p>
            </div>
        </div>
        <div class="row mt30">
            <h4 class="title" style="padding-left:35px"><b>Тохируулгууд</b></h4>
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">
                        <ul class="r2position"> 
                            <li> <img src="middleware/assets/img/icon/step.png" alt="Бүсчлэлийн зураг"/></li>
                            <li><b><p>Давхарын тохируулга</p></b><?php echo $floor ?></li>
                        </ul>
                        <ul class="r2position"> 
                            <li> <img src="middleware/assets/img/icon/stage.png" alt="Бүсчлэлийн зураг"/></li>
                            <li><b><p>Цонхны тохируулга</p></b><?php echo $windowadj ?></li>
                        </ul>
                       
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <ul class="r2position"> 
                            <li> <img src="middleware/assets/img/icon/parking.png" alt="Бүсчлэлийн зураг"/></li>
                            <li><b><p>Зогсоолийн тохируулга</p></b><?php echo $parkingadj ?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="row main-card">
                    <div class="col-md-6 col-sm-6">
                        <ul class="r2position"> 
                            <li> <img src="middleware/assets/img/icon/calendar.png" alt="Бүсчлэлийн зураг"/></li>
                            <li><b><p>Ашиглалтад орсон оны тохируулга</p></b><?php echo $yearadj ?></li>
                        </ul>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <ul class="r2position"> 
                            <li> <img src="middleware/assets/img/icon/slide.png" alt="Бүсчлэлийн зураг"/></li>
                            <li><b><p>Тоглоомын талбайн тохируулга</p></b><?php echo $gameadj ?></li>
                        </ul>
                        <ul class="r2position"> 
                            <li> <img src="middleware/assets/img/icon/price.png" alt="Бүсчлэлийн зураг"/></li>
                            <li><b><p>Түргэн борлогдох үнэ</p></b><?php echo $fastselladj ?></li>
                        </ul>
                    </div>
                </div>
            </div>   
        </div>

        <div class="row mt30">
            <div class="col-md-12 col-sm-12">
                <div class="row main-card position">
                    <h4 class="title"><b>Үнэлгээний үр дүн</b></h4>
                        <div class="col-md-6">
                            <ul class="rposition">
                                <li style="border-right:1px solid #000;"><b>1 м.кв-ийн суурь үнэлгээ (м2)</b><p><?php echo (isset($RateData['corporatedtl7dv']['evaluationadj']) ? $RateData['corporatedtl7dv']['evaluationadj'] : '-'); ?></p></li>
                                <li><b>Нийт үнэлгээ</b><p><?php echo (isset($RateData['corporatedtl7dv']['evaluationsum']) ? $RateData['corporatedtl7dv']['evaluationsum'] : '-'); ?></p></li>
                            <ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="rposition">
                                <li style="border-right:1px solid #000;"><b>Түргэн борлогдох үнэ</b><p><?php echo (isset($RateData['corporatedtl6dv']['apartfastsellprice']) ? $RateData['corporatedtl6dv']['apartfastsellprice'] : '-'); ?></p></li>
                                <li><b>Харилцагчийн үнэлгээ</b><p><?php echo (isset($RateData['corporatedtl6dv']['valuation']) ? $RateData['corporatedtl6dv']['valuation'] : '-'); ?></p></li>
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
                        <h4>Нийт Үнэлгээ:</h4>
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

    $(".dcreport").promise().done(function() {
        $(".dcreport1<?php echo $this->uniqId; ?>").printThis({
            debug: true,             
            importCSS: true,           
            printContainer: false,      
            loadCSS: URL_APP+"assets/custom/css/print/dcreport.css",
            removeInline: false        
        });
    });
    
    if ($("body").find(".dcreport1<?php echo $this->uniqId; ?>").length > 0) {
        $("body").find(".dcreport1<?php echo $this->uniqId; ?>").remove();
    }                 
    //sswindow.print();
}
function callTempProcess() {
    $.ajax({
        type: "post",
        url: "mdwidget/dcreportprocess",
        data: {
            indicator200: $(".dcreport1<?php echo $this->uniqId; ?>").find('input[name="indicator200"]').val(),
            indicator201: $(".dcreport1<?php echo $this->uniqId; ?>").find('input[name="indicator201"]').val(),
            indicator202: $(".dcreport1<?php echo $this->uniqId; ?>").find('textarea[name="indicator202"]').val(),
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
.dcReport {
    background-color: #f6f6f6 !important;
    overflow-x:hidden;
}
.dcreport_ .reportbold h4 {
    font-weight:600 !important;
}
.dcReport h3 {
    font-weight: 600;
    text-transform: capitalize;
}
.dcReport .dcreport_ select {
    width: 100%;
    padding: 5px 0;
}
.dcReport .dcreport_ .repHead h2 {
    color: #fff;
    margin: 0 -8px;
    background: #3c3c3c;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    color: #fff;
    padding: 10px 20px;
}
.dcReport .dcreport_ .main-card {
    background: #fff;
    margin: 0px -8px;
    padding: 10px;
}
.dcReport .dcreport_ .main-card .title {
    padding-left: 15px;
}
.dcReport .dcreport_ select {
    padding-left: 15px;
}
.reportbutton .btn{
    margin-top:30px;
}
.dcReport .dcreport_ .main-card button {
    float: right;
    margin-right: 15px;
    margin-bottom: 30px;
}
.dcReport .dcreport_ .main-card.map {
    padding: 20px 10px 15px 10px;
}
.dcReport .dcreport_ .main-card.map .mapimage h4{
    padding-left:10px;
}
.dcReport .dcreport_ .main-card.map .mapimage img {
    margin-left: 10px;
    width:98%;
}
.dcReport .dcreport_ .main-card.map img {
    width: 100%;
    height: 300px;
}
.dcReport .dcreport_ .main-card.position b {
    font-size: 15px;
}
.dcReport .dcreport_ .main-card.position p {
    color: #00a9e4;
    font-size: 15px;
    padding-top: 7px;
    font-weight: 700;
}
.dcReport .dcreport_ .main-card .rposition {
    padding: 0;
    border: 1px solid;
    display: inline-block;
    width: 95%;
    margin: 5px 16px;
}
.dcReport .dcreport_ .main-card .rposition li {
    float: left;
    width: 50%;
    text-align: center;
    padding: 8px 0;
    list-style-type: none;
}
.dcReport .dcreport_ .main-card .rposition li:last-child {
    border-left: 1px solid;
}
.dcReport .dcreport_ .main-card .rposition li h3 {
    margin: 10px;
}
.dcReport .dcreport_ .main-card .r2position {
    padding: 0;
    display: inline-block;
    width: 100%;
    margin: 4px 0;
}
.dcReport .dcreport_ .main-card .r2position li {
    float: left;
    text-align: center;
    padding: 6px 0;
    list-style-type: none;
}
.dcReport .dcreport_ .main-card .r2position li:first-child {
    width: 40%;
}
.dcReport .dcreport_ .main-card .r2position li:first-child img {
    width: 60px;
}
.dcReport .dcreport_ .main-card .r2position li:last-child {
    width: 58%;
}
.dcReport .dcreport_ .main-card .r2position li h3 {
    margin: 10px;
}
.dcReport .dcreport_ .methodDc {
    color: #fff;
    margin-left: -8px;
    margin-right: -8px;
}
.dcReport .dcreport_ .methodDc h3 {
    color: #000;
}
/*# sourceMappingURL=main.css.map */
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
.vnelgeeTable{
    padding: 0 60px;
}
</style>
