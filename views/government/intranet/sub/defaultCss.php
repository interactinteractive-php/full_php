<style type="text/css">
    /* .intranet-<?php echo $this->uniqId; ?> ._progress-bar:before {
            display: inline-block;
            font: normal normal normal 14px/1 "Font Awesome 5 Pro";
            font-size: inherit;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            position: absolute;
            white-space: nowrap;
            height: 20px;
            content: "\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183\f183";
    }
    .intranet-<?php echo $this->uniqId; ?> ._progress-bar1 {
        position: absolute;
        white-space: nowrap;
        height: 20px;
        overflow: hidden;
    } */
    /*  {
        background-color: initial;
        background-image: url("assets/custom/img/ico/blue.png");
        background-repeat: repeat;
        width: 100%;
        height: 35px;
    } */
    .intranet-<?php echo $this->uniqId; ?> .spinner,
    .modal .spinner {
        width: initial !important;
        height: initial !important;
    }
    .intranet-<?php echo $this->uniqId; ?> *::-webkit-scrollbar-track {
        background: #ffffff;
    }
    .intranet .sidebar li:not(.activing).active {
        background-color: var(--root-color01);
        border-left: 3px solid var(--root-color1);
    }
    .intranet .sidebar li.activing {
        border-radius: 5px;
        padding: 5px !important;
    }
    .intranet .sidebar li.activing > a:hover {
        border-radius: 5px;
    }
    .intranet .sidebar li.activing.active {
        background: var(--root-color1);
    }
    .intranet .sidebar li.activing.active > a > span {
        color: #FFF;
    }
    .intranet-<?php echo $this->uniqId; ?> .sidebar-main .dv-twocol-f-selected {
        background-color: rgba(93, 173, 226, 0.5) !important;
    }
    .intranet-<?php echo $this->uniqId; ?> .sidebar-main .dv-twocol-f-sub-selected {
        background-color: rgba(93, 173, 226, 0.1) !important;
    }
    .intranet-<?php echo $this->uniqId; ?> .nav.nav-group-sub .nav-item a { 
        padding-left: 20px;
    }
    .intranet .media-chat-item {
        background-color: rgba(93, 173, 226,0.3) !important;
        border-radius: 20px;
    }
    .intranet-<?php echo $this->uniqId; ?> .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }
    
    .intranet-<?php echo $this->uniqId ?> .mini-dialog {
        max-width: 300px !important;
        margin: 1.75rem auto !important;
    }
    .intranet-<?php echo $this->uniqId ?> .modal .modal-header,
    .intranet-<?php echo $this->uniqId ?> .modal .modal-footer {
        background: none;
    }
    .intranet-<?php echo $this->uniqId ?> #intranet-right .fancybox-button, .fancybox-button:link, .fancybox-button:visited {
        background: none;
    }
    .intranet-<?php echo $this->uniqId ?> .sidebar.v2 .nav-sidebar .nav-item:not(.nav-item-divider) {
        border-bottom: none;
    }
    
    .intranet-<?php echo $this->uniqId ?> .float-right>.dropdown-menu {
        right: auto;
    }
    .intranet-<?php echo $this->uniqId ?> .dropdown > .dropdown-menu.float-left:before, .dropdown-toggle > .dropdown-menu.float-left:before, .btn-group > .dropdown-menu.float-left:before {
        left: 9px;
        right: auto;
    }
    #modal-intranet<?php echo $this->uniqId ?> .fileinput-button .big {
        font-size: 70px;
        line-height: 112px;
        text-align: center;
        color: #ddd;
    }
  
    #modal-intranet<?php echo $this->uniqId ?> .list-view-file-new::-webkit-scrollbar {
        width: 4px !important;
    }
    
    #modal-intranet<?php echo $this->uniqId ?> .list-view-file-new::-webkit-scrollbar-thumb {
        background: #4b76a5 !important;
    }
    
    #modal-intranet<?php echo $this->uniqId ?> .list-view-file-new {
        max-height: 168.2px;
        overflow: auto; 
        margin: 0;
    }
    
    #modal-intranet<?php echo $this->uniqId ?> .fancybox-button {
        background: none;
        height: initial;
        width: initial;
        color: #2196f3;
        padding: 0;
    }
    #modal-intranet<?php echo $this->uniqId ?> .mce-edit-area {
        border-width: 1px 0px 0px 1px !important;
    }
    #modal-intranet<?php echo $this->uniqId ?> .create_reg_btn button:hover,
    #modal-intranet<?php echo $this->uniqId ?> .create_reg_btn button:focus,
    #modal-intranet<?php echo $this->uniqId ?> .create_reg_btn button:active,
    #modal-intranet<?php echo $this->uniqId ?> .create_reg_btn button.active{
        opacity: 1 !important;
    }
    
    #modal-intranet<?php echo $this->uniqId ?> .create_reg_btn button.active i.icon-checkmark4 {
        display: flex !important;
        position: relative;
        top: 0;
        left: 67px;
        height: 0;
        font-size: 12px;
    }
    
    .ui-widget[aria-describedby="dialog-dataview-selectable-1565070936581248"],
    .ui-widget[aria-describedby="dialog-confirm-<?php echo $this->uniqId ?>"],
    .ui-widget[aria-describedby="dialog-dataview-selectable-1565070690138433"]
    {
        z-index: 1052 !important; 
    }
    
    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1565070936581248"],
    .ui-widget-overlay[aria-describedby="dialog-confirm-<?php echo $this->uniqId ?>"],
    .ui-widget-overlay[aria-describedby="dialog-dataview-selectable-1565070690138433"]
    {
        z-index: 1051 !important; 
    }
    
    
    /*tooltip*/
    .intranet_content_<?php echo $this->uniqId ?> .data-tooltip {
        display:block;
        text-align:left;
    }

    .intranet_content_<?php echo $this->uniqId ?> .data-tooltip h5 {
        padding: 10px;
        border-bottom: 1px dashed #FFF;
    }

    .intranet_content_<?php echo $this->uniqId ?> .data-tooltip .tooltipright {
        min-width:320px;
        /*max-width:320px;*/
/*        top:50%;
        left:100%;*/
        top: 125px;
        /*left: 120px;*/
        /*margin-left:20px;*/
        transform:translate(0, -50%);
        padding:0;
        color:black;
        background-color:white;
        font-weight:normal;
        font-size:13px;
        border-radius:4px;
        position:absolute;
        z-index:99999999;
        box-sizing:border-box;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
        visibility:hidden; transition:opacity 0.8s;
    }

    .intranet_content_<?php echo $this->uniqId ?> .data-tooltip:hover .tooltipright {
        visibility:visible;
    }

    .intranet_content_<?php echo $this->uniqId ?> .data-tooltip .tooltipright img {
        width:400px;
        border-radius:8px 8px 0 0;
    }
    
    .intranet_content_<?php echo $this->uniqId ?> .data-tooltip .text-content {
        padding:10px 20px;
    }


    /*end tooltip*/
    
    #saveform_<?php echo $this->uniqId ?> .mce-wordcount {
        display: none !important;
    }
    
    
    #saveform_<?php echo $this->uniqId ?> .select2-choices {
        /*height: 38px !important;*/
    }
    
    
    #modal-intranet<?php echo $this->uniqId ?> .select2-container-multi .select2-choices .select2-search-field input
    {
        padding: 2px !important;
    }
    
    #modal-intranet<?php echo $this->uniqId ?> .select2-container-multi .select2-choices
    {
        /*min-height: 40px !important;*/
    }
    
    .socialIntranet<?php echo $this->uniqId ?> {
        background: #DAE0E6;
        height: 100vh;
        float: left;
        overflow: hidden;
    }
    
    .socialIntranet<?php echo $this->uniqId ?> .mainpost {
        height: 100vh;
        overflow-x: hidden;
        overflow-y: auto;
        padding-bottom: 40px;
    }
    
    .socialIntranet<?php echo $this->uniqId ?> .panel-post:hover{
        border: 1px solid #989494 !important;
    }

    .socialIntranet<?php echo $this->uniqId ?> {
/*        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;*/
    }
    
    .socialPin<?php echo $this->uniqId ?> .ui-block {
        height: 140px;
    }
    
    .socialPin<?php echo $this->uniqId ?> .friend-header-thumb,
    .socialPin<?php echo $this->uniqId ?> .friend-header-thumb img
    {
        height: 140px;
        fill: inherit;
    }
    
    .socialPin<?php echo $this->uniqId ?> .friend-item-content {
        position: absolute;
        z-index: 10;
        padding: 12px;
        float: left;
        height: 40px;
        width: 90%;
        padding: 0 5px 5px 5px;
        text-align: left;
        color: #FFF;
        background: url(assets/custom/img/app_dashboard/hsprite.png) 0px -96px repeat-x;
        bottom: 16px;
    }
    
    .intranet-<?php echo $this->uniqId; ?>.intranet .leftsidebar-<?php echo $this->uniqId ?> .badge {
        background: none !important;
        color: #5dade2 !important;
        border: 1px solid #5dade2 !important;
        background-color: white !important;
        min-width: 30px;
    }
    
    .search_menu_<?php echo $this->uniqId ?> {
        height: 100vh;
        padding: 10px;
    }
    
    .intranet-<?php echo $this->uniqId ?> .custom-input-search {
        border-radius: 0;
        height: 40px !important;
        padding: 5px;
        padding-left: 48px !important;
        border: none;
    }
    
    .intranet-<?php echo $this->uniqId ?> .form-group-feedback-left .form-control-feedback {
        top: 5px;
    }
    
    .search_menu_<?php echo $this->uniqId ?> {
        
    }
    
    .intranet-<?php echo $this->uniqId ?> .custom-descr-ss {
        width: 50%;
        margin-left: 10px;
        position: relative;
        bottom: 5px;
/*        border:none;
        border-bottom: 1px dashed #CCC;
        border-top: 1px dashed #CCC;*/
    }
    
    .dynamic-width-breadcrumb {
        min-width: 920px;
        
    }
    @media (max-width: 1366px) {
        .intranet-<?php echo $this->uniqId ?> .dynamic-width-int {
            max-width: 150px;
            float: left;
            overflow: auto;
            overflow: hidden;
        }
        
        .intranet-<?php echo $this->uniqId ?> .dynamic-width-breadcrumb {
            min-width: 650px !important;
        }
    }

    @media (max-width: 1440px) {
        .intranet-<?php echo $this->uniqId ?> .dynamic-width-int {
            max-width: 200px;
            float: left;
            overflow: auto;
            overflow: hidden;
        }
        
        .intranet-<?php echo $this->uniqId ?> .dynamic-width-breadcrumb {
            min-width: 700px !important;
        }
    }
    
</style>