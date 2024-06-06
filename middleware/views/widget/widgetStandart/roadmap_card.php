<?php $renderAtom = new Mdwidget(); ?>

<style>
    .card-roadmap-wrap {
    padding: 0px 90px 90px 90px;
    max-width:1300px;
    margin: 0 auto;
    }
    .card-roadmap-wrap .card-three {
        display:flex;
    }
    /* .card-roadmap-wrap .card-three.odd {
        float: right;
    } */
    .card-roadmap-wrap .seperator {
    width:15%;
    min-width: 15px;
    background-color:#585858;
    height:26px;
    margin-top:100px;
    background: url("<?php echo URL; ?>assets/custom/img/landingpage/Group_7199.png") repeat;
    }
    .card-roadmap-wrap .seperator div {
        margin-top: 3px;
        margin-left: 5px;
        margin-right: 3px;        
        text-align: center;
    }
    .card-roadmap-wrap .cardroadmap {
        background: #FFFFFF;
        box-shadow: 0px 4px 14px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        display: flex;
        padding: 15px 20px 15px 20px;
        width:300px;
        min-width:300px;
        margin-top:80px;
        cursor:pointer;
    }
    .card-roadmap-wrap .cardroadmap .ordernumber {
        background: #dbe7ff;
        border-radius: 50px;
        height: 40px;
        width: 40px;
        text-align: center;
        padding-top: 9px;
    }
    .card-roadmap-wrap .cardroadmap .ordernumber div {
        color:#699BF7;
        font-size:16px;
        font-weight:bold
    }
    .card-roadmap-wrap .cardroadmap .description {
        margin-left:10px;
        margin-top:10px;
        white-space:nowrap;
        overflow:hidden;
        font-weight:bold;
    }
    .card-roadmap-wrap .cardroadmap.last::after {
        content:"";
        width:89px;
        background-color:#585858;
        height:177px;
        margin-top:5px;
        background: url("<?php echo URL; ?>assets/custom/img/landingpage/Group_7201.png") repeat;
        position:absolute;
        margin-left:281px;
    }
    .card-roadmap-wrap .cardroadmap.first::after {
        content:"";
        width:89px;
        background-color:#585858;
        height:177px;
        margin-top:6px;
        background: url("<?php echo URL; ?>assets/custom/img/landingpage/Group_7198.png") repeat;
        position:absolute;
        margin-left:-109px;
    }
    .card-roadmap-wrap .cardroadmap.start-rocket::after {
        content:"";
        width:40px;
        background-color:#585858;
        height:40px;
        margin-top:4px;
        background: url("<?php echo URL; ?>assets/custom/img/landingpage/image_44157.png") repeat;
        position:absolute;
        margin-left:-70px;
        animation: wiggle 4s linear infinite;
    }
    .card-roadmap-wrap .cardroadmap.end-rocket::after {
        content:"";
        width:55px;
        background-color:#585858;
        height:53px;
        margin-top:-22px;
        background: url("<?php echo URL; ?>assets/custom/img/landingpage/Group_7196.png") repeat;
        position:absolute;
        margin-left:281px;
    }
    .card-roadmap-wrap .cardroadmap.odd-end-rocket::after {
        content:"";
        width:55px;
        background-color:#585858;
        height:53px;
        margin-top:-22px;
        background: url("<?php echo URL; ?>assets/custom/img/landingpage/Group_7200.png") repeat;
        position:absolute;
        margin-left:-75px;
    }
    .card-roadmap-wrap .cardroadmap .more {
        color:#585858;
        margin-left: auto;
        margin-top:12px;  
        cursor:pointer;
    }
    .card-roadmap-wrap .cardroadmap .info-icon {
        background:#7380D9;
        border-radius:40px;
        text-align:center;
        height:40px;
        width:40px;
        opacity:.7;
        position: absolute;
        margin-left: 263px;
        margin-top: 33px;
    }
    .card-roadmap-wrap .cardroadmap:hover .info-icon, .card-roadmap-wrap .cardroadmap:hover .info-icon-hover {
        opacity:1;
        display:block;
    }
    .card-roadmap-wrap .cardroadmap .info-icon-hover {
        content:"";
        height:50px;
        width:50px;
        border: 1px solid #7380D9;
        border-radius: 50px;
        position:absolute;
        margin-left: 258px;
        margin-top: 28px;
        display:none;
    }
    .card-roadmap-wrap .cardroadmap .info-icon i {
        margin-top: 13px;
        color: #fff;
        font-size: 15px;
    }
    .card-roadmap-wrap .cardroadmap .dropdown-toggle::after {
        content: "";
    }
    .banner-wrap {
        height:300px;
        background: url("<?php echo URL.issetDefaultVal($this->jsonAttr['bannerimage'], 'assets/custom/img/landingpage/banner.png?v=3'); ?>") no-repeat top center;
        background-size: cover;
    }
    /* Keyframes */
    @keyframes wiggle {
    0%, 7% {
        transform: rotateZ(0);
    }
    15% {
        transform: rotateZ(-15deg);
    }
    20% {
        transform: rotateZ(10deg);
    }
    25% {
        transform: rotateZ(-10deg);
    }
    30% {
        transform: rotateZ(6deg);
    }
    35% {
        transform: rotateZ(-4deg);
    }
    40%, 100% {
        transform: rotateZ(0);
    }
    }    
</style>

<div class="banner-wrap"></div>
<div class="card-roadmap-wrap">
<?php 
    if ($this->datasrc) {
        $this->datasrc = $this->datasrc['parentdtl'];
        $chunkArr = array_chunk($this->datasrc, 3);
        $total = count($this->datasrc);
        $chunkArrTotal = count($chunkArr);
        $order = 0;
        $order2 = 0;
        $order3 = 0;
        foreach($chunkArr as $key => $row) { ?>
        <div class="card-three <?php echo $key % 2 == 0 ? "" : "odd"; ?>" <?php echo $key % 2 != 0 && $key == ($chunkArrTotal-1) ? " style='float: right;'" : ""; ?>>
        <?php 
            if ($key % 2 != 0) {
                $row = array_reverse($row);
                $order = $order + count($row) + 1;
                $order2 = $order;
            } else {
                $order = $order2 ? $order2-1 : 0;
            }
            foreach($row as $key2 => $child) {                 
                $order3++;
                if ($key % 2 != 0) {                    
                    $order--;       
                } else {
                    $order++;
                }         
                ?>               
                <div class="cardroadmap 
                    <?php 
                        echo $order3 == $total && $key % 2 == 0 ? "end-rocket " : ""; 
                        echo $key == ($chunkArrTotal-1) && $key % 2 != 0 && !$key2 ? "odd-end-rocket " : ""; 
                        echo !$key && !$key2 ? "start-rocket" : ""; 
                        echo $key2 == 2 && $key % 2 == 0 ? " last" : ""; 
                        echo $key2 == 0 && $key != 0 && $key % 2 != 0 ? " first" : ""; 
                    ?>
                ">
                    <div data-processid="<?php echo $renderAtom->renderAtom($child, "position4", $this->positionConfig) ?>" class="ordernumber cloud-call-process">
                        <div><?php echo $order; ?></div>
                    </div>
                    <div data-processid="<?php echo $renderAtom->renderAtom($child, "position4", $this->positionConfig) ?>" class="description cloud-call-process"><?php echo $renderAtom->renderAtom($child, "position1", $this->positionConfig) ?></div>
                    <?php if (!empty($child['childdtl'])) { ?>
                        <div class="more">
                            <i class="fas fa-ellipsis-h dropdown-toggle" data-toggle="dropdown"></i>
                            <div class="dropdown-menu">
                                <?php foreach($child['childdtl'] as $crow) { ?>
                                <a href="javascript:;" data-processid="<?php echo $renderAtom->renderAtom($crow, "position4", $this->positionConfig) ?>" class="dropdown-item cloud-call-subprocess"><?php echo $renderAtom->renderAtom($crow, "position1", $this->positionConfig) ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="info-icon" style="background-color:<?php echo $renderAtom->renderAtom($child, "position3", $this->positionConfig, "#7380D9") ?>">         
                        <i class="fas <?php echo $renderAtom->renderAtom($child, "position2", $this->positionConfig, "fa-smile") ?>"></i>
                    </div>
                    <div class="info-icon-hover" style="border-color:<?php echo $renderAtom->renderAtom($child, "position3", $this->positionConfig, "#7380D9") ?>">         
                    </div>           
                </div>
                <?php if ($total != $order3 && $key2 < 2) { ?>
                    <div class="seperator">
                        <div>
                            <img src="<?php echo $key % 2 == 0 ? URL."assets/custom/img/landingpage/Polygon_10.png" : URL."assets/custom/img/landingpage/Polygon_11.png"; ?>">
                        </div>
                    </div>            
                <?php } ?>
    <?php }
    echo "</div>";
    }
    } ?>      
</div>

<script>
    var sepwidth = $(".card-roadmap-wrap").find(".card-three").find(".seperator:first").width();
    function sepwidthfn(){
        $(".card-roadmap-wrap").find(".card-three").each(function(key, row){
            if(key) {
                $(row).find(".seperator").css("width", sepwidth+"px");
            }
        });

        $(".card-roadmap-wrap").css("height", $(window).height() - $(".system-header").offset().top - 400);        
    }
    sepwidthfn();
    $(window).resize(function () {
        sepwidth = $(".card-roadmap-wrap").find(".card-three").find(".seperator:first").width();
        sepwidthfn();
    });    
    $(".cloud-call-process").click(function(e){
        if (e.target.className != 'fas fa-ellipsis-h dropdown-toggle') {
            callWebServiceByMeta($(this).data("processid"), true);
        }
    });    
    $(".cloud-call-subprocess").click(function(e){
        callWebServiceByMeta($(this).data("processid"), true);
    });    
</script>