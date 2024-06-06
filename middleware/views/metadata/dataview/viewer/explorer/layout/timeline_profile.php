<?php
if ($this->recordList && !isset($this->recordList['status'])) {
    $fields = $this->row['dataViewLayoutTypes']['explorer']['fields'];
    $recordListArr = array();

    $name1 = strtolower($fields['name1']);
    $name2 = strtolower($fields['name2']);
    $name3 = strtolower($fields['name3']);
    $name4 = strtolower($fields['name4']);
    $name5 = strtolower($fields['name5']);    
    $name6 = strtolower($fields['name6']);    
    $name7 = strtolower($fields['name7']);    
    $name8 = strtolower($fields['name8']);    
    $name9 = strtolower($fields['name9']);    
    $photo = strtolower($fields['photo']);    
    $header1 = strtolower($fields['header1']);    
    $header2 = strtolower($fields['header2']);    

    $firstRow = $this->recordList[0];
    $name9Field = 0;
    $photoField = $name1Field = $name2Field = $name3Field = $name4Field = $name5Field = $name6Field = $name7Field = $name8Field = ' echo "";';

    if (isset($firstRow[$header1])) {
        $recordListArr = Arr::groupByArray($this->recordList, $header1);
    }

    if (isset($firstRow[$name1])) {
        $name1Field = 'echo $row[\''.$name1.'\'];';
    }    
    if (isset($firstRow[$name2])) {
        $name2Field = 'echo $row[\''.$name2.'\'];';
    }    
    if (isset($firstRow[$name3])) {
        $name3Field = 'echo $row[\''.$name3.'\'];';
    }    
    if (isset($firstRow[$name4])) {
        $name4Field = 'echo $rrrrow[\''.$name4.'\'];';
    }    
    if (isset($firstRow[$name5])) {
        $name5Field = 'echo $crow[\''.$name5.'\'];';
    }    
    if (isset($firstRow[$name6])) {
        $name6Field = 'echo $crow[\''.$name6.'\'];';
    }    
    if (isset($firstRow[$name7])) {
        $name7Field = 'echo $crow[\''.$name7.'\'];';
    }    
    if (isset($firstRow[$name8])) {
        $name8Field = 'echo $crow[\''.$name8.'\'];';
    }    
    if (isset($firstRow[$name9])) {
        $name9Field = 'echo $crow[\''.$name9.'\'];';
    }    
    if (isset($firstRow[$photo])) {
        $photoField = 'echo $row[\''.$photo.'\'];';
    }    
?>
<link href="middleware/assets/css/gridlayout/timeline-profile/style.css" rel="stylesheet">

<?php foreach($recordListArr as $rrow) { 
    $row = $rrow['row'];
    ?>
    <div class="row" id="intro">   
        <div class="col-md-2"></div>
        <!-- Beginning of Content -->
        <div class="col-md-8 resume-container">      

        <!-- =============== PROFILE INTRO ====================-->
        <div class="profile-intro row">
            <!-- Left Collum with Avatar pic -->
            <div class="col-md-4 profile-col">
            <!-- Avatar pic -->
            <div class="profile-pic">
                <div class="profile-border">
                <!-- Put your picture here ( 308px by 308px for retina display)-->
                <img src="<?php eval($photoField); ?>" alt="profile photo">
                <!-- /Put your picture here -->
                </div>          
            </div>
            <!-- /Avatar pic -->
            </div>
            <!-- /Left columm with avatar pic -->
    
            <!-- Right Columm -->
            <div class="col-md-7">
            <!-- Welcome Title-->
            <h1 class="intro-title1"><span class="color1 bold"><?php eval($name1Field); ?></span></h1>
            <!-- /Welcome Title -->
            <!-- Job - -->
            <h2 class="intro-title2"><?php eval($name2Field); ?></h2>
            <!-- /job -->
            <!-- Description -->
            <p><?php eval($name3Field); ?></p>
            <!-- /Description -->
            </div>
            <!-- /Right Collum -->
        </div>
        <!-- ============  /PROFILE INTRO ================= -->
        
        <?php  
        $recordChildListArr = Arr::groupByArray($rrow['rows'], $header2);

        foreach($recordChildListArr as $rrrow) {
            $rrrrow = $rrrow['row'];
        ?>
        <!-- ============  TIMELINE ================= -->
        <div class="timeline-wrap">
            <div class="timeline-bg">

            <!-- ====>> SECTION: EDUCATION <<====-->
            <section class="timelinepro mt50 education" id="education">

                <!-- SECTION TITLE -->
                <div class="line row">
                <!-- Margin Collums (necessary for the timelinepro effect) -->
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs" style="height: 70px;"></div>
                <div class="col-md-2 timeline-progress hidden-sm hidden-xs timeline-title full-height" style="height: 70px;">
                </div>              
                <!-- /Margin Collums -->
                <!-- Item Content -->
                <div class="col-md-8 content-wrap bg1">
                    <!-- Section title -->
                    <h2 class="section-title"><?php eval($name4Field); ?></h2>
                    <!-- /Section title -->
                </div>
                <!-- /Item Content -->
                <!-- Margin Collum-->
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs" style="height: 70px;"></div>
                <!-- /Margin Collum-->
                </div>
                <!-- /SECTION TITLE -->

                <?php foreach ($rrrow['rows'] as $crow) { ?>
                <!-- SECTION ITEM -->
                    <div class="line row">
                    <!-- Margin Collums (necessary for the timelinepro effect) -->
                        <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs" style="height: 164px;"></div>
                        <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-education " style="height: 164px;"></div>
                        <!-- /Margin Collums -->
                        <!-- Item Content -->
                        <div class="col-md-8 content-wrap bg1">
                            <div class="line-content line-content-education">
                            <!-- Graduation title -->
                            <h3 class="section-item-title-1"><?php eval($name5Field); ?></h3>
                            <span>
                                <?php 
                                if ($name9Field) {
                                for($i=0; $i < $crow[$name9]; $i++) {
                                    echo '<i class="icon-star-full2 font-size-base text-orange-300"></i>';
                                }} ?>
                            </span>
                            <!-- /Graduation title -->
                            <!-- Graduation time -->
                            <h4 class="graduation-time"><i class="fa fa-university"></i> <?php eval($name6Field); ?> - <span class="graduation-date"><?php eval($name7Field); ?></span></h4>
                            <!-- /Graduation time -->
                            <!-- content -->
                            <div class="graduation-description">
                                <p><?php eval($name8Field); ?></p>
                            </div>
                            <!-- /Content -->
                            </div>
                        </div>
                        <!-- /Item Content -->
                        <!-- Margin Collum -->
                        <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs" style="height: 164px;"></div>
                        <!-- /Margin Collum -->
                    </div>
                <?php } ?> 
            </section>

            <!-- ====>> SECTION: THANK YOU <<====-->
            <section class="timelinepro profile-infos">

                <!-- SECTION ITEM -->
                <div class="line row line-thank-you">
                <!-- Margin Collums (necessary for the timeline effect) -->
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs" style="height: 78px;"></div>
                <div class="col-md-2 timeline-progress hidden-sm hidden-xs full-height timeline-point " style="height: 78px;"></div>
                <!-- /Margin Collums -->
                <!-- Item Content -->
                <div class="col-md-8 content-wrap bg1">
                    <div class="line-content">
                    <!-- Subtitle -->
                    <!-- <h3 class="thank-you">Thank You!</h3> -->
                    <!-- /Subtitle -->
                    </div>
                </div>
                <!-- /Item Content -->
                <!-- Margin Collum-->
                <div class="col-md-1 bg1 timeline-space full-height hidden-sm hidden-xs" style="height: 78px;"></div>
                <!-- / Margin Collum-->
                </div>
                <!-- /SECTION ITEM -->            
            </section>          
            
            </div>
        </div>
        <?php } ?>
        <!-- ============  /TIMELINE ================= -->
        </div> 
        <div class="col-md-2"></div>
    </div>
    <?php
    }
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), (isset($this->recordList['message']) ? $this->recordList['message'] : 'No data!') );
}
?>