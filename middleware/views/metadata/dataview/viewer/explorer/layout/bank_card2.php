<?php $uid = getUID(); ?>

<style type="text/css">
    .slick-carousel<?php echo $uid; ?> {
        display: flex;
        align-items: center;
    }
    .slick-carousel<?php echo $uid; ?> .slick-slide {
        width: 310px;
        height: 200px;
        margin: 0 12px;
    }
    .slick-carousel<?php echo $uid; ?> .slick-list {
        margin-left: 20px;
        margin-right: 20px;
    }
    .dv-bank-card2 .dropdown-toggle::after {
        content: "";
    }
    .namefield<?php echo $uid; ?> {
        background-color: #f0f5ffcc;
        color: #333;
        border-radius: 50px;
        padding: 5px 8px 5px 8px;
        font-weight: 500;
        border-color: transparent;
        width: 80px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;                                
        margin-top: 10px;
    }    
    .stylecustom<?php echo $uid; ?> {
        text-align: center;
        margin-top: -85px;
        margin-left: -100px;
        opacity: .06;
        color: #fff;
        border: 10px solid #fff;
        padding: 10px;
        border-radius: 55px;
        height: 115px;
        width: 115px;
    }    
    .stylecustom2<?php echo $uid; ?> {
        text-align: center;
        margin-top: -85px;
        margin-left: -100px;
        color: #fff;
        padding: 10px;
    }    
    .stylecustom3<?php echo $uid; ?> {
        background-color: #f0f5ffcc;border-radius: 50px;padding: 5px 24px 5px 24px;border-color: #f0f5ffcc;margin-top: 10px;color:#333;
    }    
    .stylecustom4<?php echo $uid; ?> {
        text-align: right;font-size: 10px;font-weight: bold;color: #fff;text-transform: uppercase;width: 80px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;
    }    
</style>

<div class="bg-white dv-bank-card2 d-none">
    <div class="slick-carousel<?php echo $uid; ?>">
            <?php
            $fields = $this->row['dataViewLayoutTypes']['explorer']['fields'];            
            if ($this->recordList) {

                if (isset($this->recordList['status'])) {
                    echo html_tag('div', array('class' => 'alert alert-danger'), 'DV error message: ' . $this->recordList['message']);
                    exit();
                }

                $firstRow = $this->recordList[0];
                
                $backgroundField = strtolower(issetParam($fields['backgroundImage']));
                $photoField = strtolower(issetParam($fields['photo']));
                $name1Field = strtolower(issetParam($fields['name1']));
                $name2Field = strtolower(issetParam($fields['name2']));
                $name3Field = strtolower(issetParam($fields['name3']));
                $name4Field = strtolower(issetParam($fields['name4']));
                $name5Field = strtolower(issetParam($fields['name5']));
                $name16Field = strtolower(issetParam($fields['name16']));
                $name17Field = strtolower(issetParam($fields['name17']));
                $name18Field = strtolower(issetParam($fields['name18']));
                $name19Field = strtolower(issetParam($fields['name19']));
                
                $name6Field = strtolower(issetParam($fields['name6']));
                $name7Field = strtolower(issetParam($fields['name7']));
                $name8Field = strtolower(issetParam($fields['name8']));
                $name9Field = strtolower(issetParam($fields['name9']));
                $name10Field = strtolower(issetParam($fields['name10']));
                $name11Field = strtolower(issetParam($fields['name11']));
                $name12Field = strtolower(issetParam($fields['name12']));
                $name13Field = strtolower(issetParam($fields['name13']));
                $name14Field = strtolower(issetParam($fields['name14']));
                $name15Field = strtolower(issetParam($fields['name15']));

                $background = $photo = $name1 = $name2 = $name3 = $name4 = $name5 = $name6 = $name16 = $name17 = $name18 = $name19 = ' echo "";';
                
                if ($backgroundField && isset($firstRow[$backgroundField])) {
                    $background = 'echo $recordRow[$backgroundField];';
                }
                
                if ($photoField && isset($firstRow[$photoField])) {
                    $photo = 'echo $recordRow[$photoField];';
                }

                if ($name1Field && isset($firstRow[$name1Field])) {
                    $name1 = 'echo $recordRow[$name1Field];';
                }

                if ($name2Field && isset($firstRow[$name2Field])) {
                    $name2 = 'echo $recordRow[$name2Field];';
                }

                if ($name3Field && isset($firstRow[$name3Field])) {
                    $name3 = 'echo $recordRow[$name3Field];';
                }

                if ($name4Field && isset($firstRow[$name4Field])) {

                    $name4 = 'echo $recordRow[$name4Field];';

                    if ($name4FieldLabelName = issetParam($fields['name4_labelname'])) {
                        $name4FieldLabelName = Lang::line($name4FieldLabelName);
                    }
                }

                if ($name5Field && isset($firstRow[$name5Field])) {
                    $name5 = 'echo $recordRow[$name5Field];';

                    if ($name5FieldLabelName = issetParam($fields['name5_labelname'])) {
                        $name5FieldLabelName = Lang::line($name5FieldLabelName);
                    }
                }

                if ($name16Field && isset($firstRow[$name16Field])) {
                    $name16 = 'echo $recordRow[$name16Field];';

                    if ($name16FieldLabelName = issetParam($fields['name16_labelname'])) {
                        $name16FieldLabelName = Lang::line($name16FieldLabelName);
                    }
                }

                if ($name17Field && isset($firstRow[$name17Field])) {
                    $name17 = 'echo $recordRow[$name17Field];';
                }

                if ($name18Field && isset($firstRow[$name18Field])) {
                    $name18 = 'echo $recordRow[$name18Field];';
                }

                if ($name19Field && isset($firstRow[$name19Field])) {
                    $name19 = 'echo $recordRow[$name19Field];';
                }

                // if ($name6Field && isset($firstRow[$name6Field])) {
                //     $name6 = 'echo $recordRow[$name6Field];';
                // }                
                
                $drillCount = 0;
                
                for ($i = 6; $i <= 16; $i++) {
                    
                    if (isset(${'name'.$i.'Field'}) 
                        && ${'name'.$i.'Field'} 
                        && isset($firstRow[${'name'.$i.'Field'}]) 
                        && issetParam($fields['name'.$i.'_labelname']) 
                        && isset($this->drillDownLink[${'name'.$i.'Field'}])) {
                        
                        $drillCount ++;
                    }
                }
                
                $nameFieldLink = $nameFieldDropLink = '';
                
                for ($i = 6; $i <= 16; $i++) {
                    
                    if (isset(${'name'.$i.'Field'}) 
                        && ${'name'.$i.'Field'} 
                        && isset($firstRow[${'name'.$i.'Field'}]) 
                        && issetParam($fields['name'.$i.'_labelname']) 
                        && isset($this->drillDownLink[${'name'.$i.'Field'}])) {
                        
                        $drillLabelName = Lang::line($fields['name'.$i.'_labelname']);
                        
                        if ($i >= 8 && $drillCount > 3) {
                            
                            $nameFieldDropItem = html_tag('a', array(
                                'href' => 'javascript:;', 
                                'class' => 'dropdown-item',                        
                                'onclick' => $this->drillDownLink[${'name'.$i.'Field'}]['link']
                            ), '<i class="far fa-chevron-circle-right"></i>' . $drillLabelName);

                            $nameFieldDropItem = str_replace("\'", "'", $nameFieldDropItem);
                            $nameFieldDropItem = str_replace("'", "\'", $nameFieldDropItem);
                            $nameFieldDropItem = str_replace("$\\'", "'", $nameFieldDropItem);
                            $nameFieldDropLink .= $nameFieldDropItem;
                            
                            $isNameFieldDrop = true;
                        
                            continue;
                        }
                        
                        $nameFieldBtn = html_tag('button', array(
                            'type' => 'button', 
                            'title' => $drillLabelName, 
                            'class' => 'namefield'.$uid.' btn rounded-round ' . $this->drillDownLink[${'name'.$i.'Field'}]['linkStyle'], 
                            'onclick' => $this->drillDownLink[${'name'.$i.'Field'}]['link']
                        ), $drillLabelName);

                        $nameFieldBtn = str_replace("\'", "'", $nameFieldBtn);
                        $nameFieldBtn = str_replace("'", "\'", $nameFieldBtn);
                        $nameFieldLink .= $nameFieldBtn . '<div class="clearfix"></div>';
                        
                        $isNameFieldLink = true;
                    }
                }

                $nameFieldLink = 'echo \''.$nameFieldLink.'\';';
                $nameFieldDropLink = 'echo \''.$nameFieldDropLink.'\';';

                foreach ($this->recordList as $recordRow) {
                    $rowJson = htmlentities(json_encode($recordRow), ENT_QUOTES, 'UTF-8');
            ?>
                <div class="rounded-xl dv-explorer-row" style="background-color:<?php eval($name5); ?>;height: 200px;" data-row-data="<?php echo $rowJson; ?>">
                    <div class="flex justify-between w-full h-full">
                        <div class="p-3">
                            <div class="flex">
                                <div class="p-4 rounded-3xl flex items-center justify-center" style="height: 50px;width: 50px;background-size: cover; color: rgb(118, 51, 107);background-color:#C0DCFF;background-image:url(<?php eval($name1); ?>);background-position: center;border: 2px solid #fff;">
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm lg:text-base text-base text-gray-700 block font-bold" style="color:#fff;font-size:14px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 112px;"><?php eval($name2); ?></span>
                                    <span class="" style="color:#fff;">
                                        <span class="line-clamp-0 d-block" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 112px;"><?php eval($name3); ?></span>
                                    </span>
                                </div>
                            </div>
                            <div class="text-white" style="margin-top: 75px;">
                                <div style="width: 120px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                    <?php echo issetParam($name16FieldLabelName); ?>
                                </div>
                                <div style="width: 120px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                    <?php eval($name16); ?>
                                </div>
                                <div style="width: 120px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;">
                                    <?php eval($name17); ?>
                                </div>
                            </div>
                        </div>
                        <div style="background-color:<?php eval($name19); ?>;width: 110px;border-top-left-radius: 0;border-bottom-left-radius: 0;" class="h-full p-3 rounded-xl">
                            <div class="stylecustom4<?php echo $uid; ?>"><?php eval($name4); ?></div>
                            <!-- <div class="font-bold p-1 mt-5" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                Орлого
                            </div>
                            <div class="font-bold p-1 mt-2" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                Зарлага
                            </div>
                            <div class="font-bold p-1 mt-2" style="background-color: #fff;opacity: .6;border-radius: 50px;text-align: center;">
                                ...
                            </div> -->
                            <div style="margin-top: 2.3rem!important;">
                            <?php
                                if (isset($isNameFieldLink)) {
                                    eval($nameFieldLink);
                                }                            
                                if (isset($isNameFieldDrop)) {
                                    ?>
                                    <div class="btn-group dv-bank-card-dropdown dropup">
                                        <button type="button" style='' class="stylecustom3<?php echo $uid; ?> btn btn-danger rounded-round dropdown-toggle" data-toggle="dropdown">
                                            <i class="far fa-ellipsis-h" style="padding-left: 6px;font-weight: 500;"></i>
                                        </button>
                                        <div class="dropdown-menu" x-placement="top">
                                            <?php 
                                            eval($nameFieldDropLink); 
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                            </div>
                            <div class="absolute stylecustom<?php echo $uid; ?>"></div>
                            <div class="absolute stylecustom2<?php echo $uid; ?>">
                                <img src="<?php eval($name18); ?>" onerror="onUserImageError<?php echo $uid; ?>(this);" style="width: 66px;margin-top: 18px;margin-left: 14px;">
                            </div>                            
                        </div>
                    </div>
                </div>
        <?php
                }
            }
        ?>
    </div>
</div> 

<script type="text/javascript">
$(function() {
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?> .dv-bank-card2').removeClass('d-none');
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('show.bs.dropdown', '.dv-bank-card2-dropdown', function() {
        var $this = $(this);
        $this.closest('li.dv-explorer-row').click();
    });
    
    $('#objectdatagrid-<?php echo $this->dataViewId; ?>').on('click', '.dv-explorer-row', function(){
        var $this = $(this);
        var $parent = $this.closest('.dv-bank-card2');
        $parent.find('.selected-row').removeClass('selected-row');
        $this.addClass('selected-row');
    });

    $('.slick-carousel<?php echo $uid; ?>').slick({
        // autoplay: true,
        // autoplaySpeed: 1500,
        infinite: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        variableWidth: true,
        dots: false,
        prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
        nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       ,
        responsive: [
            {
            breakpoint: 1400,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    });        
    $(".slick-carousel<?php echo $uid; ?>").css("width", $(window).width() -460);
    // setTimeout(function() {
    // }, 300);    
});
function onUserImageError<?php echo $uid; ?>(source) {
    source.src = "assets/custom/img/tugrik.png";
    source.onerror = "";
    return true;
}
</script>