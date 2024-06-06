<link href="<?php echo autoVersion('middleware/assets/css/covid19/main2.css'); ?>" rel="stylesheet" type="text/css" media="screen"/>    
<style type="text/css">
    .layout-section-title {
        border-bottom: 1px solid #385dae;
        text-align: center;
        margin-bottom: 15px;
    }

    .layout-title {
        margin: 7px 0px 0px;
        text-align: center;
        color: #fff;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 15px !important;
    }

    .layout-title-trapezoid { 
        border-bottom: 23px solid #00bcff; 
        border-left: 12px solid transparent; 
        border-right: 12px solid transparent;
        height: 0;   
    }

    .layout-title-trapezoid.outer { 
        width: 450px;
        position: relative;
        margin-bottom: -5px;
        display: inline-block;
    }

    .layout-title-trapezoid.inner { 
        border-bottom: 40px solid #385dae; 
        border-left: 18px solid transparent; 
        border-right: 18px solid transparent;
        height: 0;   
        position: absolute;
        width: 400px;
        top: -17px;
        left: 11px;
    }

    .layout-section-with-transparent {
        position: relative;
        background: #555555;
        overflow: hidden;
        padding: 40px 0 20px;
        .container {
            z-index: 100;
            position: relative;
        }
    }

    .covid19 {
        font-family: Roboto,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
    }
    .hover-default:hover {
        cursor: pointer;
    }
    .hover-default .box-shadow {
        height: 75px;
        min-height: 75px;
        max-height: 75px;
    }
    .airs_dashboard .box-shadow {
        background-color: #e5e5e5d4 !important;
    }
    
    /*.container {
        width: 80% !important;
    }*/

    #mapSection {
        background: url(/assets/covid/img/portal/map-bg.jpg);
        background-size: cover;
        height: 810px;
    }

    .section-title {
        color: #fff;
        font-size: 24px !important;
        text-transform: uppercase;
        font-weight: bold;
    }

    .map-title {
        color: #fff;
        text-transform: uppercase;
    }

    #layoutMap{
/*        margin-left: -60px;
        margin-right: -30px;*/
    }

    #layout-location-menu-header{
        width: 180px;
        height: 60px;
        background-color: #58a2f7;
    }

    #layout-location-menu-header p{
        padding: 8px 58px 8px 17px !important;
        width: 105px;
        height: 49px;
        color: white;
        font-size: 11px;
        font-weight: 300;
        text-transform: uppercase;

    }

    #layout-location-menu{
        width: 180px;
        background-color: #ebebeb;
    }

    .layout-location-right{
        width: 350px;
        height: 690px;  
        background: rgb(255 255 255 / 61%);
        border-top: 5px solid #0091ea;
        padding: 5px 15px;
        margin-top: 20px;
    }

    .card-ind {
        margin-bottom: 10px;
        padding: 0px 15px 0px 20px;
        color: #fff;
        text-transform: uppercase;
        height: 55px;
    }

    .card-ind .first {
        padding: 5px 0px; 
        width: 60%;
        float: left;
    }

    .card-ind .second {
        padding: 5px; 
        width: 40%;
        float: left;
        text-align: center;  
        font-size: 18px;
        font-weight: bold;
        height: 45px;
    }

    .card-ind .second.brown {
        background: #7b6856;
    }

    .card-ind .second.yellow {
        background: #ed7800;
    }

    .card-ind .first span.title {
        font-size: 11px;
        width: 90%;
        display: block;
    }

    .card-ind .second span {
        margin: 0 auto;
        display: table;
        color: #fff;
    }

    .card-ind .second a:hover > span {
        color: #0091ea;
    }

    .card-ind.brown {
        background: #998675;
    }

    .card-ind.yellow {
        background: #fbaf5d;
    }

    ul.license-ind {
        margin-top: 15px;
    }

    ul.license-ind li {
        border-bottom: 1px solid #94d1f6;
        width: 100%;
        padding: 7px 0px;
        display: inline-block;
    }

    ul.license-ind li i.fa {
        font-size: 15px;
        color: #f8fc37;
    }

    .cus-col-md {
        width: 50%;
        float: left;
        padding: 0px 0px 0px 20px;
    }

    .cus-col-md.first {
        border-right: 1px solid #94d1f6;
    }

    p.amount-lis {
        color: #fff;
        font-size: 15px;
        margin: 0;
    }

    p.link-o {
        color: #fff;
        font-size: 11px;
        margin: 0;
    }

    .resourse {
        color: #f8fc37;
    }

    a.resourse:hover {
        color: #0091ea;
    }

    .title-lis {
        text-transform: uppercase;
        color: #000;
        font-size: 11px;
    }

    .title-lis.child {
        font-size: 10px;
    }

    ul.license-ind li:first-child {
        border-top: 1px solid #94d1f6;
    }

    #layout-location-right-inner{
        padding-left: 11px;
        padding-bottom: 10px;
        padding-right: 11px;
        padding-top: 10px;
    }

    .layout-location-right .row{
        margin-left: 0px !important;
        margin-right: 0px !important;
    }

    .layout-location-right .left-label{
        height: 12px;
        color: #666;
        font-weight: 400;
        line-height: 12px;
    }

    .layout-location-right .left-label-value{
        width: 144px;
        height: 13px;
        color: #3a71bd;
        font-size: 18px;
        font-weight: 700;
        line-height: 12px;
    }

    .layout-location-right-line{
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid white;
    }

    #layout-location-license-count{
        width: 100px;
        height: 76px;
        background-color: #58a2f7;
        float: left;
    }

    #layout-location-license-count label, #layout-location-company-count label{
        width: 38px;
        height: 19px;
        color: white;
        background-color: #87c4fa;
        font-size: 11px;
        font-weight: 800;
        line-height: 14px;
        border-radius: 25px;
        margin-top: 18px;
        margin-left: 56px;
        padding-left: 8px;
        padding-top: 3px;
        padding-right: 5px;
        padding-bottom: 5px;
    }

    #layout-location-company-count{
        margin-left: 6px;
        width: 100px;
        height: 76px;
        background-color: #3a71bd;
        float: left;
    }

    #layout-location-count-label {
        width: 72px;
        height: 33px;
        color: white;
        font-size: 13px;
        font-weight: 400;
        line-height: 12px;  
    }

    #layout-location-count-label {
        color: white;
        font-size: 13px;
        font-weight: 400;
        line-height: 12px;
        padding-left: 9px;
        padding-top: 7px;
    }

    #layout-location-menu .list-inline{
        margin-left: 0px;
    }

    #layout-location-menu li{
        border-bottom: 1px solid #ccc;
        padding-left: 1px;
        width: 100%;
    }

    #layout-location-menu .list-inline li.root-li{
        padding: 5px 0px 5px 14px;
    }

    #layout-location-menu .list-inline li.root-li a{
        color: #333;
    }

    #layout-location-menu .list-inline li.item-li{
        padding: 5px 0px 5px 23px;
    }

    #layout-location-menu .list-inline li.item-li a{
        color: #666;
    }

    #layout-location-menu .list-inline li a{
        font-size: 13px;
        font-weight: 400;
    }

    #layout-location-menu .list-inline .fa-menu-right{
        background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAALCAYAAABGbhwYAAAAQElEQVQYV2NkQAMzZ878n56ezogujiEAUoBNMVaF2BTjVIiuGK9CZMWMIPegOxwbn3gT8ZmG7HvKfE1UOOKKGQDQiyrWRIgBPQAAAABJRU5ErkJggg==") !important;
        float: right;
        margin-right: 10px;
        font-size: 10px;
        font-weight: 100;
    }

    /*#layoutDashboard*/
    #layoutDashboard path {  stroke: #fff; }
    #layoutDashboard path:hover {  opacity:0.9; }
    #layoutDashboard rect:hover {  fill:blue; }
    #layoutDashboard .axis {  font: 10px sans-serif; }
    #layoutDashboard .legend tr{    border-bottom:1px solid grey; }
    #layoutDashboard .legend tr:first-child{    border-top:1px solid grey; }

    #layoutDashboard .col1{
        width: 50%;
        height: 300px;
    }

    #layoutDashboard .col2{
        width: 30%;
        height: 250px;
        padding-left: 50px;
    }

    #layoutDashboard .col3{
        width: 20%;
        /*height: 250px;*/
    }

    #layoutDashboard .axis path,
    #layoutDashboard .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }

    #layoutDashboard .x.axis path {  display: none; }
    #layoutDashboard .legend{
        margin-bottom:76px;
        display:inline-block;
        border-collapse: collapse;
        border-spacing: 0px;
    }
    #layoutDashboard .legend td{
        padding:4px 5px;
        vertical-align:bottom;
    }
    #layoutDashboard .legendFreq, .legendPerc{
        align:right;
        width:50px;
    }

    /*layoutGroupedBarChart*/
    #layoutGroupedBarChart .axis path,
    #layoutGroupedBarChart .axis line {
        fill: none;
        stroke: #000;
        shape-rendering: crispEdges;
    }

    #layoutGroupedBarChart .bar {
        fill: steelblue;
    }

    #layoutGroupedBarChart .x.axis path {
        display: none;
    }

    .pieLicenseAreaOfArea {
        height: 150px; 
        background: #0091ea; 
        margin: -5px -15px 5px -10px;
    }

    span.triangle-chart {
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 30px 30px 30px;
        border-color: transparent transparent #0091ea transparent;
        display: inline-block;
    }

    .custom-title {
        color: #fff;
        font-size: 20px !important;
        text-transform: uppercase;
        font-weight: bold;
    }
    
    span.sub-chart-title {
        color: #fff;
        margin-top: -2px;
        display: block;
    }
    
    #chartdiv {
      width: 100%;
      height: 500px;
    }

    .amcharts-axis-label {
        cursor: pointer;
    }

    div.radio, div.checker {
        margin-left: -2px !important;
        margin-top: 4px !important;
        padding-top: 0px !important;
    }

    .hide-it {
        display: none;
    }
    .search-companies-btn {
        top: 0;
        right: 0;
        z-index: 100;
        position: fixed;
        background: #0056b8;
        color: #FFF;
        margin-top: 220px;
        padding: 6px;
    }
    
    .search-companies-btn:hover {
        color: #FFF;
    }

    .panelCompaniesList {
        width: 800px;
        position: absolute;
        top: 140px;
        right: 0;
        z-index: 200;
        margin: 100px 0px;
        padding: 30px 20px;
        background: #FFF;
        display: none;
        border: 2px solid #6BABE5;
    }
    .close-companies-panel {
        top: 0;
        right: 0;
        z-index: 300;
        position: absolute;
        color: #9c9e9f;
        color: #6BABE5;
    }

    .chart-data {
        overflow: auto;
        width: 100%;
        padding: 5px 0px;
    }
</style>
<script type="text/javascript" src="assets/custom/addon/plugins/amcharts/amcharts/amChartMinify.js"></script>
<script type="text/javascript" src="middleware/assets/js/dashboard/charts_amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/visualization/echarts/echarts.min.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/amcharts.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/serial.js"></script>
<script type="text/javascript" src="assets/core/js/plugins/charts/amcharts/pie.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/highcharts/js/highcharts.src.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/highcharts/js/modules/map.src.js"></script>
<script type="text/javascript" src="assets/custom/addon/plugins/highcharts/js/modules/drilldown.src.js"></script>
<script type="text/javascript" src="assets/custom/js/map-invitation-chart_cv.js"></script>
<script type="text/javascript" src="assets/custom/js/aimag/administrative_regions.js"></script>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>