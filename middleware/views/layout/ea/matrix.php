<!-- banner -->
<div class="row">
    <div class="col-md-12 center-sidebar"> 
        <?php if (isset($this->mainData) && $this->mainData) { ?>
        <div class="simple-scroller">
            <div style="width: 4040px;">
               <div class="dashCorner"></div>
               <div class="dashXHeader backColour5 textColour7">
                    <div style="padding-top: 44.5px;">
                        <div class="dashXHeaderLabel fontBlack xlarge">Overall</div>
                    </div>
               </div>
                <?php if (isset($this->mainData[0]['columns'])) {
                    foreach ($this->mainData[0]['columns'] as $key => $row) { ?>
                    <div class="dashXHeader" id="serviceeip_demo_baseline_20160927v1_Class<?php echo $row['id'] ?>">
                        <div style="padding-top: 26px;">
                            <div class="dashXHeaderLabel fontBlack"><?php echo $row['name'] ?></div>
                        </div>
                    </div>  
                    <?php }
                } ?>
               <div class="clear"></div>
                <div class="dashBodyScroller" style="height: 225px;">
                    <?php foreach ($this->mainData as $key => $row) { ?>
                        <div class="dashYHeader" id="appeas_prj_HH_baseline_rep_v1_Class<?php echo $row['id']; ?>">
                            <div style="padding-top: 11px;">
                                <div class="dashYHeaderLabel fontBlack">
                                    <a id="<?php echo $row['name']; ?>" class="dashYHeaderLabel fontBlack context-menu-appProviderGenMenu" href="javascript:;"><?php echo $row['name']; ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="backColourLightGreen dashYOverall xlarge fontBlack appeas_prj_HH_baseline_rep_v1_Class<?php echo $row['id']; ?>" style="opacity: 1;">
                            <div style="padding-top: 6px;"><span><?php echo isset($row['value']) ? $row['value'] : ''; ?></span></div>
                        </div>
                        <?php 
                        if (isset($row['columns']) && $row['columns']) {
                            foreach ($row['columns'] as $kRow) { 
                                if (isset($kRow['value']) && $kRow['value']) { ?>
                                    <div class="dashCell serviceeip_demo_baseline_20160927v1_Class<?php echo $kRow['id'] ?> appeas_prj_HH_baseline_rep_v1_Class<?php echo $row['id']; ?>" style="opacity: 0.5;">
                                        <div style="padding-top: 11px;">
                                            <div class="backColourYellow dashScore fontBlack">
                                                <div style="padding-top: 0px;"><?php echo $kRow['value']; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                <div class="dashCell serviceeip_demo_baseline_20160927v1_Class<?php echo $kRow['id'] ?> appeas_prj_HH_baseline_rep_v1_Class<?php echo $row['id']; ?>" style="opacity: 1;">
                                    <div style="padding-top: 11px;">
                                        <div class="backColourGrey dashScore fontBlack">
                                            <div style="padding-top: 10px;"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php }
                            }
                            echo '<div class="clear"></div>';
                        }
                    } ?>


               </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<div class="clearfix w-100"></div>

<style type="text/css">
    
    .xlarge {
        font-size: 150%;
    }
    
    .fontBlack {
        font-weight: 700;
    }
    
    .simple-scroller {
        width: 100%;
        overflow-x: auto;
        display: block;
        box-sizing: border-box;
        padding-bottom: 10px;
    }
    
    .backColourRed{
        background-color: #CB0E3A;
        color: white;
    }

    .backColourOrange{
        background-color: #F59C3D;
        color: white;
    }

    .ragTextYellow,
    .backColourYellow{
        background-color: #edd827;
        color: white;
    }

    .ragTextLightGreen,
    .backColourLightGreen{
        background-color: #C8DE39;
        color: white;
    }

    .ragTextGreen,
    .backColourGreen{
        background-color: #1EAE4E;
        color: white;
    }

    .ragTextGrey,
    .backColourGrey{
        background-color: #aaa;
        color: white;
    }

    .ragTextBlue,
    .backColourBlue{
        background-color: #1B51A5;
        color: white;
    }

    .ragTextLightBlue,
    .backColourLightBlue{
        background-color: #29A7E6;
        color: white;
    }

    .dashXHeader,.dashYOverall,.dashCell {
        width:89px;
    }

    .dashCorner{
        width:280px;
        height:120px;
        border:1px solid #fff;
        float:left;
        text-align:center;
        box-sizing:border-box;
        padding:3px;
    }

    .dashXHeader{
        height: 179px !important;
        border:1px solid #eee;
        float:left;
        text-align:left;
        box-sizing:border-box;
        padding:3px;

    }

    .dashXHeaderLabel {
        position:relative;
        margin-top: 10px;
        line-height: 1.1em;
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
    }

    .dashYHeader {
        width: 280px;
        height: 90px !important;
        border:1px solid #eee;
        float:left;
        text-align:left;
        box-sizing:border-box;
        padding:3px;
    }

    .dashXHeader:hover,.dashYHeader:hover {
        cursor:pointer;
    }

    .dashYOverall{
        height:90px;
        border:1px solid #eee;
        float:left;
        text-align:center;
        box-sizing:border-box;
        padding:3px;
    }

    .dashCell {
        height:90px;
        border:1px solid #eee;
        float:left;
        text-align:center;
        box-sizing:border-box;
        padding:3px;
    }

    .dashScore {
        width: 40px;
        height: 20px;
        border-radius: 10px;
        position:relative;
        margin: 0 auto;
    }

    .dashBodyScroller {
        min-height: 380px;
        overflow-y: scroll;
        overflow-x: none;
    }
    .clear {
        clear: both;
    }
</style>
<script type="text/javascript">
    (function ($) {
        $.fn.vAlign = function(container) {
            return this.each(function(i){
               if(container == null) {
                  container = 'div';
               }
               var paddingPx = 0; //change this value as you need (It is the extra height for the parent element)
               $(this).html("<" + container + ">" + $(this).html() + "</" + container + ">");
               var el = $(this).children(container + ":first");
               var elh = $(el).height(); //new element height
               var ph = $(this).height(); //parent height
               if(elh > ph) { //if new element height is larger apply this to parent
                   $(this).height(elh + paddingPx);
                   ph = elh + paddingPx;
               }
               var nh = (ph - elh) / 2; //new margin to apply
               $(el).css('padding-top', nh);
            });
         };
    })(jQuery);
    $(document).ready(function(){
        $(".dashXHeader").vAlign();
        $(".dashYHeader").vAlign();
        $(".dashYOverall").vAlign();
        $(".dashCell").vAlign();
        $(".dashScore").vAlign();
    });
    
    $(document).ready(function() {
            $(".dashXHeader").hover(function(){
            var serviceHeaderID = $(this).attr('ID');
            $('.dashCell').css('opacity','0.5');
            $('.' + serviceHeaderID).css('opacity','1.0');
           // $('.dashYOverall').css('opacity','0.5');
            $('.' + serviceHeaderID).css('opacity','1.0');
        });
        $(".dashYHeader").hover(function(){
            var appHeaderID = $(this).attr('ID');
            $('.dashCell').css('opacity','0.5');
            $('.' + appHeaderID).css('opacity','1.0');
          //  $('.dashYOverall').css('opacity','0.5');
            $('.' + appHeaderID).css('opacity','1.0');
        });
        $(".pageWidthContainer").click(function(){
            $('.dashCell').css('opacity','1.0');
           // $('.dashYOverall').css('opacity','1.0');
        });
        var windowHeight = $(window).height();
        $('.dashBodyScroller').css('height',windowHeight-400);
    });
    
    $(document).ready(function() {
             $("#historyLink").click(function(){
                     $('#feedbackOverlay').hide();
                     $('#searchOverlay').hide();
                     $('#historyOverlay').slideToggle();	
             });
             $("#feedbackLink").click(function(){					
                     $('#searchOverlay').hide();
                     $('#historyOverlay').hide();
                     $('#feedbackOverlay').slideToggle();
             });
             $("#searchLink").click(function(){
                     $('#feedbackOverlay').hide();
                     $('#historyOverlay').hide();
                     $('#searchOverlay').slideToggle();
             });
     });
 </script>