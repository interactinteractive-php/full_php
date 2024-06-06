<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?> 

<div id="sales_widget_window_<?php echo $this->uniqId; ?>">
<div class="col-md-12">
    <br>
    <div>
        <h2 style="margin: 0 auto; display: table">Голомт банк</h2>
        <p style="font-weight: bold; font-size: 15px; margin: 0 auto; display: table; color: #005bab" class="mt5">Хүний нөөцийн хэлтэс үйл ажиллагааны тайлан</p>
        <p style="font-size: 15px; margin: 0 auto; display: table; color: #005bab" class="mt15">
            <input type="text" class="" value="<?php echo $this->startDate; ?>" style="border: 1px solid #ccc" id="start-date">&nbsp;&nbsp;&nbsp;
            <input type="text" value="<?php echo $this->endDate; ?>" style="border: 1px solid #ccc" id="end-date">&nbsp;&nbsp;&nbsp;
            <button id="date-filter" data-html2canvas-ignore>Filter</button>&nbsp;&nbsp;&nbsp;
            <button id="send_btn" data-html2canvas-ignore="true" class="">Email</button>
        </p>
    </div>
</div>
    
<div class="col-md-12 col-sm-12 mt20">
    <div class="" style="background-color:#fff;">
        <div class="col-md-6">
            <?php if($this->list1) { ?>
                <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                    <thead style="background-color: rgb(9, 173, 236)">
                        <tr style="">
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center"></td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center"><?php echo $this->list1[0]['classificationname']; ?></td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center"><?php echo $this->list1[1]['classificationname']; ?></td>
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center"><?php echo issetVar($this->list1[2]['classificationname']); ?></td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px !important;  color: #000">
                        <?php
                        echo '<tr>';
                            echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">Орон тоо</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[0]['vacancycnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[1]['vacancycnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . issetVar($this->list1[2]['vacancycnt']) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">Бодит ажилтан</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[0]['boditcnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[1]['boditcnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . issetVar($this->list1[2]['boditcnt']) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">Дадлагажигч</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[0]['dagaldancnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[1]['dagaldancnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . issetVar($this->list1[2]['dagaldancnt']) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">Гэрээт</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[0]['gereetcnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[1]['gereetcnt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . issetVar($this->list1[2]['gereetcnt']) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">Сул орон тоо</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[0]['sulorontoo'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[1]['sulorontoo'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . issetVar($this->list1[2]['sulorontoo']) . '</td>';
                        echo '</tr>';
                        echo '<tr>';
                            echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">Ажилтны хангалт</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[0]['ajiltniihangalt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . $this->list1[1]['ajiltniihangalt'] . '</td>';
                            echo '<td style="vertical-align: middle" class="text-center">' . issetVar($this->list1[2]['ajiltniihangalt']) . '</td>';
                        echo '</tr>';
                        ?>                
                    </tbody>
                </table>     
            <?php } ?>
        </div>   
        <div class="col-md-6">
            <?php
            if($this->list3) {
                $thead = Arr::groupByArrayOnlyKey($this->list3, 'statusname');
                $tbody = Arr::groupByArray($this->list3, 'currentstatusname');
            ?>
                <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                    <thead style="background-color: rgb(9, 173, 236)">
                        <tr style="">
                            <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center" colspan="<?php echo count($thead) * 2 + 3; ?>">Ажилтны төлөвийн ангилал</td>
                        </tr>
                        <tr>
                            <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Статус</td>
                        <?php foreach ($thead as $kthead => $rowThead) {
                            echo '<td style="font-size: 15px !important; color: #000; font-weight: bold; text-align: center">'.$kthead.'</td>';
                            echo '<td style="font-size: 15px !important; color: #000; font-weight: bold; text-align: center">%</td>';
                        } ?>      
                            <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Нийт</td>
                            <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">%</td>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px !important;  color: #000">
                        <?php
                        $sumVar = 0;
                        foreach ($tbody as $key => $row) {
                            echo '<tr>';
                            echo '<td style="vertical-align: middle;">' . $key . '</td>';
                            foreach ($row['rows'] as $childKey => $childRow) {
                                foreach ($thead as $kthead => $rowThead) {
                                    if($childRow['statusname'] == $kthead) {
                                        echo '<td style="vertical-align: middle" class="text-center">'.$childRow['cnt'].'</td>';
                                        echo '<td style="vertical-align: middle" class="text-center">'.$childRow['percentage'].'</td>';
                                    }
                                }
                            }
                            echo '<td style="vertical-align: middle" class="text-center">'.$row['row']['currentgroupcnt'].'</td>';
                            echo '<td style="vertical-align: middle" class="text-center">0</td>';                                
                            echo '</tr>';
                        }     
                        ?>                
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #fff">
                            <td style="font-size: 15px !important;  color: #000; font-weight: bold;" colspan="1">Нийт</td>
                            <?php foreach ($thead as $kthead => $rowThead) {
                                echo '<td style="font-size: 15px !important; color: #000; font-weight: bold; text-align: center">0</td>';
                                echo '<td style="font-size: 15px !important; font-weight: bold; text-align: center">0</td>';
                            } ?>      
                            <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">0</td>
                            <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">0</td>
                        </tr>
                    </tfoot>
                </table>           
            <?php } ?>
            <div class="clearfix w-100"></div>
            <div class="col-md-12">
                <div style="width: 210px; height: 75px; border: 3px solid rgb(9, 173, 236);" class="padding-5 text-center float-left">
                    <h4 class="mt5">Эрэгтэй</h4>
                    <h4 class="mt10"><?php echo $this->list25[1]['cnt'] . ' - ' . $this->list25[1]['percentage'] . '%'; ?></h4>
                </div>
                <div style="width: 210px; height: 75px; border: 3px solid rgb(247, 150, 70);" class="padding-5 text-center float-right">
                    <h4 class="mt5">Эмэгтэй</h4>
                    <h4 class="mt10"><?php echo $this->list25[0]['cnt'] . ' - ' . $this->list25[0]['percentage'] . '%'; ?></h4>
                </div>
            </div>
        </div>   
    </div>   
</div>
    
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 mt20 no-padding" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold">Албан тушаалын ангилал</p>
        <div id="hrm22_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
        
        <!--<p style="font-size: 15px; font-weight: bold" class="mt20">Хүний нөөцийн эргэц</p>
        <div id="hrm31_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
        -->
        
        <p style="font-size: 15px; font-weight: bold" class="mt20">Ажлаас гарсан ажилтнууд /төрлөөр/</p>
        <div id="serial1_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
    </div>    
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff; padding-right: 0px">
        <?php
        if($this->list24) {
            $thead = Arr::groupByArrayOnlyKey($this->list24, 'gender');
            $tbody = Arr::groupByArray($this->list24, 'age');
        ?>
            <table class="table table-hover" id="" style="border: 1px solid #ddd;    margin-top: 27px;">
                <thead style="background-color: rgb(9, 173, 236)">
                    <tr style="">
                        <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center" colspan="<?php echo count($thead) * 2 + 3; ?>">Нас, Хүйсийн ангилал</td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Нас</td>
                    <?php foreach ($thead as $kthead => $rowThead) {
                        echo '<td style="font-size: 15px !important; color: #000; font-weight: bold; text-align: center">'.$kthead.'</td>';
                        echo '<td style="font-size: 15px !important; font-weight: bold; text-align: center">%</td>';
                    } ?>      
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Нийт</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">%</td>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    $sumVar = 0;
                    foreach ($tbody as $key => $row) {
                        echo '<tr>';
                        echo '<td style="vertical-align: middle;">' . $key . '</td>';
                        foreach ($row['rows'] as $childKey => $childRow) {
                            foreach ($thead as $kthead => $rowThead) {
                                if($childRow['gender'] == $kthead) {
                                    echo '<td style="vertical-align: middle" class="text-center">'.$childRow['cnt'].'</td>';
                                    echo '<td style="vertical-align: middle" class="text-center">'.$childRow['percentage'].'</td>';
                                }
                            }
                        }
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['row']['groupcnt'].'</td>';
                        echo '<td style="vertical-align: middle" class="text-center">-</td>';                                
                        echo '</tr>';
                    }     
                    ?>                
                </tbody>
                <tfoot>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold;" colspan="1">Нийт</td>
                        <?php foreach ($thead as $kthead => $rowThead) {
                            echo '<td style="font-size: 15px !important; font-weight: bold; text-align: center">0</td>';
                            echo '<td style="font-size: 15px !important; font-weight: bold; text-align: center">0</td>';
                        } ?>      
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">0</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">0</td>
                    </tr>
                </tfoot>
            </table>           
        <?php } ?>        
        
        <?php
        if($this->list42) {
        ?>
            <table class="table table-hover" id="" style="border: 1px solid #ddd;    margin-top: 47px;">
                <thead style="background-color: rgb(9, 173, 236)">
                    <tr style="">
                        <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center" colspan="5">Ажлын байрны эргэц</td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Нэгжүүд</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Ажлаас гарсан</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">%</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Ажилд орсон</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">%</td>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    $sumVar = 0;
                    $sumVar1 = 0;
                    $sumVar2 = 0;
                    $sumVar3 = 0;
                    foreach ($this->list42 as $key => $row) {
                        echo '<tr>';
                        echo '<td style="vertical-align: middle; background-color: rgb(9, 173, 236); color: #fff">'.$row['classificationname'].'</td>';
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['garsancnt'].'</td>';
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['garsanpercent'].'%</td>';
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['orsoncnt'].'</td>';
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['orsonpercent'].'%</td>';
                        echo '</tr>';
                        $sumVar += (int) $row['garsancnt'];
                        $sumVar1 += (int) $row['garsanpercent'];
                        $sumVar2 += (int) $row['orsoncnt'];
                        $sumVar3 += (int) $row['orsonpercent'];
                    }     
                    ?>                
                </tbody>
                <tfoot>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #fff; background-color: rgb(9, 173, 236)" colspan="1">Нийт</td>
                        <td style="font-size: 15px !important;  color: #000; vertical-align: middle" class="text-center" colspan="1"><?php echo $sumVar; ?></td>
                        <td style="font-size: 15px !important;  color: #000; vertical-align: middle" class="text-center" colspan="1"><?php echo $sumVar1; ?>%</td>
                        <td style="font-size: 15px !important;  color: #000; vertical-align: middle" class="text-center" colspan="1"><?php echo $sumVar2; ?></td>
                        <td style="font-size: 15px !important;  color: #000; vertical-align: middle" class="text-center" colspan="1"><?php echo $sumVar3; ?>%</td>
                    </tr>
                </tfoot>
            </table>           
        <?php } ?>         
    </div>
</div> 
    
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 mt20 no-padding" style="background-color:#fff;">
        
        <p style="font-size: 15px; font-weight: bold">Төвийн нэгж</p>
        <div id="seria51_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
        
        <p style="font-size: 15px; font-weight: bold" class="mt20">Улаанбаатар СТТ</p>
        <div id="seria52_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
        
        <p style="font-size: 15px; font-weight: bold" class="mt20">Орон нутаг СТТ</p>
        <div id="seria53_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>        
        
    </div>    
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff; padding-right: 0px">     
        <?php
        if($this->list54) {
        ?>
            <table class="table table-hover" id="" style="border: 1px solid #ddd;margin-top: 27px;">
                <thead style="background-color: rgb(9, 173, 236)">
                    <tr style="">
                        <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center" colspan="2">Төвийн нэгж</td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Албан тушаал</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Тоо</td>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    $sumVar = 0;
                    foreach ($this->list54 as $key => $row) {
                        echo '<tr>';
                        echo '<td style="vertical-align: middle" class="">'.$row['positionname'].'</td>';
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['cnt'].'</td>';
                        echo '</tr>';
                        $sumVar += (int) $row['cnt'];
                    }     
                    ?>                
                </tbody>
                <tfoot>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold;" colspan="1">Нийт</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; vertical-align: middle" class="text-center" colspan="1"><?php echo $sumVar; ?></td>
                    </tr>
                </tfoot>
            </table>           
        <?php } ?>              
        
        <?php
        if($this->list55) {
        ?>
            <table class="table table-hover" id="" style="border: 1px solid #ddd;">
                <thead style="background-color: rgb(9, 173, 236)">
                    <tr style="">
                        <td style="font-size: 15px !important;  color: #fff; font-weight: bold; text-align: center" colspan="2">Салбар тооцооны төв</td>
                    </tr>
                    <tr>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Албан тушаал</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; text-align: center">Тоо</td>
                    </tr>
                </thead>
                <tbody style="font-size: 15px !important;  color: #000">
                    <?php
                    $sumVar = 0;
                    foreach ($this->list55 as $key => $row) {
                        echo '<tr>';
                        echo '<td style="vertical-align: middle" class="">'.$row['positionname'].'</td>';
                        echo '<td style="vertical-align: middle" class="text-center">'.$row['cnt'].'</td>';
                        echo '</tr>';
                        $sumVar += (int) $row['cnt'];
                    }     
                    ?>                
                </tbody>
                <tfoot>
                    <tr style="background-color: #fff">
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold;" colspan="1">Нийт</td>
                        <td style="font-size: 15px !important;  color: #000; font-weight: bold; vertical-align: middle" class="text-center" colspan="1"><?php echo $sumVar; ?></td>
                    </tr>
                </tfoot>
            </table>           
        <?php } ?>              
    </div>
</div> 
    
<div class="col-md-12 col-sm-12">
    <p style="font-size: 15px; font-weight: bold" class="mt20">Ажилд орсон ажилтнуудын мэдээлэл СТТ</p>
    <div id="seria61_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>        
</div>
    
<div class="col-md-12 col-sm-12">
    <p style="font-size: 15px; font-weight: bold" class="mt20">Ажилтны тоо</p>
    <div id="seria62_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>        
</div>
    
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 no-padding" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold" class="mt20">Шилжилт хөдөлгөөн ихтэй нэгжүүд</p>
        <div id="seria72_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
    </div>
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff;padding-right: 0px">
        <p style="font-size: 15px; font-weight: bold">Амралтын төрлөөр</p>
        <div id="hrm82_widget_chart_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>        
    </div>
</div>
    
<div class="col-md-12 col-sm-12">
    <div class="col-md-6 col-sm-6 no-padding" style="background-color:#fff;">
        <p style="font-size: 15px; font-weight: bold" class="mt20">Сахилгын шийтгэл</p>
        <div id="seria91_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>
    </div>
    <div class="col-md-6 col-sm-6 mt20" style="background-color:#fff;padding-right: 0px">
        <p style="font-size: 15px; font-weight: bold" class="">Албан тушаалаар</p>
        <div id="serial92_dashboard_<?php echo $this->uniqId; ?>" style="border: 1px solid rgb(9, 173, 236);"></div>        
    </div>
</div>
    
</div>

<?php
    $arrResult31 = array();
    $arrResult312 = array();
//    if($this->list31) {
//        foreach ($this->list31 as $k => $row) {
//            $arrResult31[$k]['name'] = Date::format('d', $row['BOOK_DATE']);
//            $arrResult31[$k]['y'] = (int) $row['DEBIT_AMOUNT'];
//
//            $arrResult312[$k]['name'] = Date::format('d', $row['BOOK_DATE']);
//            $arrResult312[$k]['y'] = (int) $row['CREDIT_AMOUNT'];
//        }
//    }
    
    $arrResult = array();
    $this->list41 = Arr::groupByArray($this->list41, 'statusname');
    
    if($this->list41) {
        foreach ($this->list41 as $k => $row) {
            $arrResult2 = array();
            
            foreach ($row['rows'] as $kk => $rrow) {
                array_push($arrResult2, array($rrow['mm'], (int) $rrow['cnt']));
            }
            
            array_push($arrResult, array(
                'name' => $k,
                'pointWidth' => 50,
                'data' => $arrResult2
            ));
        }
    }         
    
    $arrResult51 = array();
    if($this->list51) {
        foreach ($this->list51 as $k => $row) {
            array_push($arrResult51, array($row['departmentname'], (int) $row['cnt']));
        }
    }
    
    $arrResult52 = array();
    if($this->list52) {
        foreach ($this->list52 as $k => $row) {
            array_push($arrResult52, array($row['departmentname'], (int) $row['cnt']));
        }
    }
    
    $arrResult53 = array();
    if($this->list53) {
        foreach ($this->list53 as $k => $row) {
            array_push($arrResult53, array($row['departmentname'], (int) $row['cnt']));
        }
    }
    
    $arrResult61 = array();
    $this->list61 = Arr::groupByArray($this->list61, 'classificationname');
    
    if($this->list61) {
        foreach ($this->list61 as $k => $row) {
            $arrResult2 = array();
            
            foreach ($row['rows'] as $kk => $rrow) {
                if(!empty($rrow['mm']))
                    array_push($arrResult2, array($rrow['mm'], (int) $rrow['cnt']));
            }
            
            array_push($arrResult61, array(
                'name' => $k,
                'pointWidth' => 50,
                'data' => $arrResult2
            ));
        }
    }    
    
    $arrResult62 = array();
    if($this->list62) {
        foreach ($this->list62 as $k => $row) {
            array_push($arrResult62, array($row['departmentname'], (int) $row['cnt']));
        }
    }    
    
    $arrResult72 = array();
    if($this->list72) {
        foreach ($this->list72 as $k => $row) {
            array_push($arrResult72, array($row['departmentname'], (int) $row['cnt']));
        }
    }

    $arrResult92 = array();
    $this->list92 = Arr::groupByArray($this->list92, 'booktypename');
    
    if($this->list92) {
        foreach ($this->list92 as $k => $row) {
            $arrResult2 = array();
            
            foreach ($row['rows'] as $kk => $rrow) {
                array_push($arrResult2, array($rrow['positionname'], (int) $rrow['cnt']));
            }
            
            array_push($arrResult92, array(
                'name' => $k,
                'data' => $arrResult2
            ));
        }
    }    
    
    $arrResult22 = array();
    if($this->list22) {
        foreach ($this->list22 as $k => $row) {
            array_push($arrResult22, array($row['classificationname'], (int) $row['cnt']));
        }
    }    
    
    $arrResult82 = array();
    if($this->list82) {
        foreach ($this->list82 as $k => $row) {
            array_push($arrResult82, array($row['booktypename'], (int) $row['cnt']));
        }
    }    
    
    $arrResult91 = array();
    if($this->list91) {
        foreach ($this->list91 as $k => $row) {
            array_push($arrResult91, array($row['booktypename'], (int) $row['cnt']));
        }
    }    
?>

<style>
    #serial1_dashboard_<?php echo $this->uniqId; ?>, 
    #hrm31_widget_chart_<?php echo $this->uniqId; ?>, 
    #seria51_dashboard_<?php echo $this->uniqId; ?>, 
    #seria52_dashboard_<?php echo $this->uniqId; ?>, 
    #seria53_dashboard_<?php echo $this->uniqId; ?>, 
    #seria61_dashboard_<?php echo $this->uniqId; ?>, 
    #seria62_dashboard_<?php echo $this->uniqId; ?>, 
    #seria72_dashboard_<?php echo $this->uniqId; ?>, 
    #hrm82_widget_chart_<?php echo $this->uniqId; ?>, 
    #seria91_dashboard_<?php echo $this->uniqId; ?>, 
    #serial92_dashboard_<?php echo $this->uniqId; ?>, 
    #hrm22_widget_chart_<?php echo $this->uniqId; ?> {
        height: 420px;
    }
</style>

<script type="text/javascript">
    var widWindowId_<?php echo $this->uniqId; ?> = '#sales_widget_window_<?php echo $this->uniqId; ?>';
    
    Highcharts.setOptions({
        chart: {
            style: {
                fontSize: '13'
            }
        }
    });    
    
    $(function(){
        $('#start-date, #end-date').inputmask('y-m-d');
        $('#start-date, #end-date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true, 
            todayBtn: 'linked', 
            todayHighlight: true 
        });       
        
        $(document).on('click', '#date-filter', function(){
            window.location = URL_APP + 'dashboard/hrm_activity/' + $('#start-date').val() + '/' + $('#end-date').val();
        });
        
        var $openRoleStartDate = $('#start-date');
        var $openRoleEndDate = $('#end-date');
        
        $openRoleStartDate.on('changeDate', function(){
            
            if ($openRoleStartDate.val() != '' && $openRoleEndDate.val() != '') {
                var $thisStartDateVal = new Date($openRoleStartDate.val());
                var $thisEndDateVal = new Date($openRoleEndDate.val());

                if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                    $openRoleEndDate.datepicker('update', $openRoleStartDate.val());
                }
            }
        });
        
        $openRoleEndDate.on('changeDate', function(){
            
            if ($openRoleStartDate.val() != '' && $openRoleEndDate.val() != '') {
                var $thisStartDateVal = new Date($openRoleStartDate.val());
                var $thisEndDateVal = new Date($openRoleEndDate.val());

                if ($thisStartDateVal.getTime() > $thisEndDateVal.getTime()) {
                    $openRoleStartDate.datepicker('update', $thisEndDateVal.getFullYear()+'-01-01');
                }
            }
        });        
        
        $('#send_btn').on('click', function(){
            var $dialogName = 'dialog-email-'+getUniqueId(1);
            if (!$("#" + $dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            var $dialog = $("#" + $dialogName);

            $.ajax({
                type: 'post',
                url: 'dashboard/sendMailForm', 
                dataType: 'json',
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...', 
                        boxed: true 
                    });
                },
                success: function (data) {
                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: false,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 950,
                        height: "auto",
                        modal: true,
                        close: function () {
                            $dialog.empty().dialog('destroy').remove();
                        },
                        position: {my:'top', at:'top+55'}, 
                        buttons: [
                            {text: data.close_btn, class: 'btn btn-sm', click: function () {
                                $dialog.dialog('close');
                            }}
                        ]
                    });
                    $dialog.dialog('open');

                    $('.sendMailBtnOt').off().on('click', function(){
                        tinyMCE.triggerSave();

                        $("#dashboard-mail-form").validate({errorPlacement: function () {}});

                        if ($("#dashboard-mail-form").valid()) {
                            
                            Core.blockUI({
                                message: 'Sending email...',
                                boxed: true
                            });
                            
                            setTimeout(function(){
                                
                                $('body').find('.ui-dialog, .ui-widget-overlay, .blockUI').attr('data-html2canvas-ignore', 'true');
                                
                                html2canvas(document.body).then(function(canvas) {
                                    $('#dashboard-mail-form', '#' + $dialogName).ajaxSubmit({
                                        type: 'post',
                                        url: 'dashboard/sendMail',
                                        data: data,
                                        dataType: 'json',
                                        beforeSubmit: function (formData, jqForm, options) {
                                            formData.push(
                                                {name: 'base64image', value: canvas.toDataURL("image/png")}
                                            );
                                        },
                                        success: function (data) {
                                            PNotify.removeAll();

                                            if (data.status === 'success') {
                                                new PNotify({
                                                    title: 'Success',
                                                    text: data.message,
                                                    type: 'success',
                                                    sticker: false
                                                });
                                                $dialog.dialog('close');
                                            } else {
                                                new PNotify({
                                                    title: 'Error',
                                                    text: data.message,
                                                    type: 'error',
                                                    sticker: false
                                                });
                                            }
                                            Core.unblockUI();
                                        }
                                    });
                                });
                            }, 1000);
                        }
                    });

                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function () {
                Core.initAjax($dialog);
            });
        });        
    });

    $('#serial1_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        "title": {
          "text": ''
        },        
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            }
        },
        legend: {
            enabled: true
        },            
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                },
                stacking: 'normal'
            }            
        },
        series: <?php echo json_encode($arrResult); ?>
    });
    
    $('#hrm22_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'pie'
        },
        "title": "",
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.0f}, {point.percentage:.1f}%'
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },
        series: [{
            name: '', 
            colorByPoint: true, 
            data: <?php echo json_encode($arrResult22); ?>
        }]
    });    
    
    $('#hrm31_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        "title": "",        
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            },
            type: 'logarithmic'                 
        },
        legend: {
            enabled: true
        },            
        plotOptions: {
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b> of total<br/>'
        },
        series: [{
            color: "#ff9900",
            name: "1",
            data: <?php echo json_encode($arrResult31); ?>
        },{
            color: "#00b3b3",
            name: "2",
            data: <?php echo json_encode($arrResult312); ?>
        }]
    });    
    
    $('#seria51_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
                style: {
                    fontSize: '12px'
                }                    
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },            
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },
        series: [{name: '', data: <?php echo json_encode($arrResult51); ?>}]
    });    
    
    $('#seria52_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
                style: {
                    fontSize: '12px'
                }                    
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },            
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },
        series: [{name: '', data: <?php echo json_encode($arrResult52); ?>}]
    });    
    
    $('#seria53_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
                style: {
                    fontSize: '12px'
                }                
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },            
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },
        series: [{name: '', data: <?php echo json_encode($arrResult53); ?>}]
    });    

    $('#seria61_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        "title": {
          "text": ''
        },        
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            }
        },
        legend: {
            enabled: true
        },            
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },
        series: <?php echo json_encode($arrResult61); ?>
    });
    
    $('#seria62_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -90,
                style: {
                    fontSize: '12px'
                }                
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },            
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },
        series: [{name: '', data: <?php echo json_encode($arrResult62); ?>}]
    });     
    
    $('#seria72_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        xAxis: {
            type: 'category'
//            labels: {
//                rotation: -90,
//                style: {
//                    fontSize: '12px'
//                }                
//            }
        },
        plotOptions: {
            column: {
                dataLabels: {
                    enabled: true
                }
            }            
        },        
        yAxis: {
            min: 0,
            title: {
                text: ''
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: '<b>{point.y:.0f}</b>'
        },        
        series: [{name: '', data: <?php echo json_encode($arrResult72); ?>}]
    });    
    
    $('#hrm82_widget_chart_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'pie'
        },
        "title": "",
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.0f}, {point.percentage:.1f}%'
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },
        series: [{
            name: '', 
            colorByPoint: true, 
            data: <?php echo json_encode($arrResult82); ?>
        }]
    });    
    
    $('#seria91_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'pie'
        },
        "title": "",
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                    format: '{point.name}: {point.y:.0f}, {point.percentage:.1f}%'
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.0f}</b><br/>'
        },
        series: [{
            name: '', 
            colorByPoint: true, 
            data: <?php echo json_encode($arrResult91); ?>
        }]
    });
    
    $('#serial92_dashboard_<?php echo $this->uniqId; ?>').highcharts({
        chart: {
            type: 'bar'
        },
        "title": {
          "text": ''
        },        
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                enabled: false,
                text: 'Custom with <b>simple</b> <i>markup</i>',
                style: {
                    fontWeight: 'normal'
                }
            }
        },
        legend: {
            enabled: true
        },            
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true
                },
                stacking: 'normal'
            }            
        },
        series: <?php echo json_encode($arrResult92); ?>
    });    
</script>