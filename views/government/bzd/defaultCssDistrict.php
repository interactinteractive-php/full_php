<style type="text/css">
    
    .government_<?php echo $this->uniqId ?> .data-tooltip {
        display:inline-block;
        position:relative;
        text-align:left;
    }

    .government_<?php echo $this->uniqId ?> .data-tooltip h5 {
        padding: 10px;
        border-bottom: 1px dashed #FFF;
    }

    .government_<?php echo $this->uniqId ?> .data-tooltip .tooltipright {
        min-width:293px;
        max-width:293px;
        top:50%;
        left:100%;
        margin-left:20px;
        transform:translate(0, -50%);
        padding:0;
        color:#EEEEEE;
        background-color:#444444;
        font-weight:normal;
        font-size:13px;
        border-radius:8px;
        position:absolute;
        z-index:99999999;
        box-sizing:border-box;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
        visibility:hidden; opacity:0; transition:opacity 0.8s;
    }

    .government_<?php echo $this->uniqId ?> .data-tooltip:hover .tooltipright {
        visibility:visible; opacity:1;
    }

    .government_<?php echo $this->uniqId ?> .data-tooltip .tooltipright img {
        width:400px;
        border-radius:8px 8px 0 0;
    }
    
    .government_<?php echo $this->uniqId ?> .data-tooltip .text-content {
        padding:10px 20px;
    }

    .government_<?php echo $this->uniqId ?> .data-tooltip .tooltipright i {
        position:absolute;
        top:50%;
        right:100%;
        margin-top:-12px;
        width:12px;
        height:24px;
        overflow:hidden;
    }
    .government_<?php echo $this->uniqId ?> .data-tooltip .tooltipright i::after {
        content:'';
        position:absolute;
        width:12px;
        height:12px;
        left:0;
        top:50%;
        transform:translate(50%,-50%) rotate(-45deg);
        background-color:#444444;
        box-shadow:0 1px 8px rgba(0,0,0,0.5);
    }
    
    .government_<?php echo $this->uniqId ?> .text-blue { 
        color: #2196f3 !important;
    }
    .government_<?php echo $this->uniqId ?> {
        border-radius: 10px;
        background: #EEE;
    }
    .status-dialog-process {
        overflow: scroll; 
        overflow-x: hidden; 
        max-height: 550px;
    }
    
    .government_<?php echo $this->uniqId ?> ::-webkit-scrollbar,
    .status-dialog-process::-webkit-scrollbar
    {
        width: 4px !important;
    }
    
    /* .conferencing-reviewgov-list li.c-issue-list,
    .conferencing-issue-list li.c-issue-list {
        border-left: 3px solid #ec407a !important;
        padding-left:7px;
    }
     */
    .member-list-conference .media-body{
        width: 10rem;
    }
    
    .member-list-conference .memberposition1, 
    .member-list-conference .memberposition2, 
    .member-list-conference .memberposition {
        float: left;
        width: 100%;
        word-wrap: break-word;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* .conferencing-reviewgov-list li.c-issue-list,
    .conferencing-issue-list li.c-issue-list {
        padding-left:10px;
    } */

    .government_<?php echo $this->uniqId ?> ::-webkit-scrollbar-thumb,
    .status-dialog-process::-webkit-scrollbar-thumb {
        background: rgb(2, 166, 105) !important;
    }

    .government_<?php echo $this->uniqId ?> .sidebar-right2 {
        width:25rem;
        margin-left: 15px;
    }
    
    .participants {
        font-size: 12px;
        font-weight: 600;
        color: var(--root-color1);
        position: relative;
        bottom: 0;
    }
    
    .conferencing-issue-list li .badge {
        position: absolute;
        top: 12px;
        z-index: 5;
        padding: 1px 4px;
        right: 5px;
        font-size: 14px;
    }
    .conferencing-issue-list li {
        position:relative;
    }
    
    .modal .modal-header {
        background: none;
    }
    
    @media (min-width: 576px) {
        .modal-dialog {
            max-width: 600px !important;
            margin: 1.75rem auto;
        }
    }
    
    .form-check-label p {
        margin: 0;
        padding: 0 25px;
    }
    
    .form-check-label input {
        margin-top: .3rem;
    }
    
    .isitem .btn.finishadd {
        z-index: 2 !important;
    }
    
    .icon-p {
        color: #2196f3;
        margin-left: 5px;
    }
    
    .icon-p i.icon-pencil {
        font-size: 12px;
    }
    
    .conf-issuelist-timer:hover .icon-p {
        /* display: initial !important; */
    }
    
    .conf-issuelist-timer span{
        color:#000;
    }
    .conf-issuelist-timer span,
    
    .conf-issuelist-timer {
        font-size: 12px !important;
    }
    .timebtnadd{
        position: relative;
        right: -40px;
        background: #fff;
        padding: 1px 5px;
        z-index: 1;
        cursor: pointer;
    }
    .conf-issuelist-timer{
        position:relative;
        right:-40px;
        bottom: -10px;
    }
    .icon-p .icon-alarm:hover {
        opacity:.7
    }
    .icon-p .icon-alarm {
        background: #fff;
        padding: 5px 6px;
        cursor: pointer;
        z-index: 1;
        border-radius: 3px;
    }
    .conf-issuelist-start.conf-issuelist-timer{
        color:#000 !important;
    }  
    
    /* Nauka tur nemew */

    .government_<?php echo $this->uniqId ?> {
        margin-top: 10px;
        overflow: hidden;
    }

    .page-content.government_<?php echo $this->uniqId ?> {
        display: inherit;
    }

    .government_<?php echo $this->uniqId ?>  .card .card-header {
        padding: 0.9375rem 1.25rem;
    }

    .government_<?php echo $this->uniqId ?>  .card .card-body {
        padding: 1.25rem;
    }

    .government_<?php echo $this->uniqId ?>  .media {
        margin-top: 0;
        margin-bottom: 0.5rem;
    }

    @media (min-width: 768px) {
        .government_<?php echo $this->uniqId ?> .header-elements-md-inline .header-elements.d-none {
            display: none !important;
        }
    }

    .government_<?php echo $this->uniqId ?> .general_number {
        background: #f2635f;
        width: 74px;
        height: 64px;
        align-items: center;
        display: flex;
        justify-content: center;
        font-size: 44px;
        border-radius: 5px;
        font-weight: bold;
        margin-right: 20px;
    }

    .government_<?php echo $this->uniqId ?> .general_number .numb {
        font-size: 24px;
    }

    .government_<?php echo $this->uniqId ?> .general_number .type {
        margin-top: 2px;
        font-size: 12px;
        padding-top: 5px;
    }

    .dblock {
        max-width: 50px !important;
        display: none !important;
    }

    .government_<?php echo $this->uniqId ?> .general_number_conf {
        background: none;
        width: auto;
        height: 60px;
        align-items: center;
        display: flex;
        justify-content: center;
        font-size: 44px;
        border-radius: 5px;
        font-weight: bold;
        margin-right: 20px;
        padding: 0 10px;
    }

    @media (min-width: 768px) {
        .dblock {
            max-width: 50px !important;
            display: none !important;
        }
    }

    .government_<?php echo $this->uniqId ?> .general_number_conf .numb {
        font-size: 24px;
    }

    .government_<?php echo $this->uniqId ?> .general_number_conf .type {
        margin-top: 0;
        font-size: 12px;
        border-top: 1px solid #c0c0c0;
        padding-top: 5px;
    }

    .government_<?php echo $this->uniqId ?> .posname {
        font-size: 15px;
        color: #aaa;
        text-transform: uppercase;
        /* font-weight: bold; */
        margin-top: 5px;
        margin-bottom: 0;
    }

    .government_<?php echo $this->uniqId ?> .posname2 {
        font-size: 12px;
        color: #aaa;
        text-transform: uppercase;
        font-weight: bold;
        margin-top: 5px;
        margin-bottom: 0;
    }

    .government_<?php echo $this->uniqId ?> .problem {
        margin-right: 20px;
        margin-bottom: 0;
    }

    .government_<?php echo $this->uniqId ?> .text-muted {
        color: #777 !important;
    }

    .government_<?php echo $this->uniqId ?> .btn.timer-start {
        /* background: #2196f3 !important; */
        background: var(--root-color1) !important;
    }

    .government_<?php echo $this->uniqId ?> .btn.timer-start i {
        font-size: 25px !important;
    }

    .government_<?php echo $this->uniqId ?> .btn.clock {
        font-size: 22px;
        padding: 7px 10px;
        width: 110px;
        height: 64px;
        border-left: 1px solid #e0e0e0;
        background: var(--root-color1);
        border-radius: 5px;
    }

    .government_<?php echo $this->uniqId ?> .pstatus {
        background: #298c29;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        text-align: center;
        position: relative;
    }

    .government_<?php echo $this->uniqId ?> .pstatus i {
        color: #fff;
        font-size: 17px;
        line-height: 14px;
        padding-top: 16px;
    }

    .government_<?php echo $this->uniqId ?> .pstatus .isactive {
        background: #f00;
    }

    .government_<?php echo $this->uniqId ?> .list-icons-extended p {
        font-size: 15px;
        text-transform: capitalize;
    }

    .government_<?php echo $this->uniqId ?> .addothermem {
        position: absolute;
        right: 20px;
        top: 15px;
        font-size: 14px;
        background: #2196f3;
        color: #fff;
        padding: 0 10px;
        border-radius: 3px;
    }

    .government_<?php echo $this->uniqId ?> .btn.stopbtn i {
        font-size: 25px;
    }

    .government_<?php echo $this->uniqId ?> .btn.clock2 {
        width: 60px;
        font-size: 22px;
        padding: 7px 10px;
        height: 60px;
        border-left: 1px solid #e0e0e0;
        background: #eee;
        border-radius: 5px;
    }

    .government_<?php echo $this->uniqId ?> .media-list-linked .media {
        padding: 0rem 1.25rem;
    }

    .government_<?php echo $this->uniqId ?> .table-xs td {
        padding: 0.5rem 1.25rem;
    }

    @media (min-width: 768px) {
        .government_<?php echo $this->uniqId ?> .sidebar-expand-md.sidebar-component-right {
            margin-left: 1.25rem;
            margin-right: 0;
        }
    }

    .table-xs th {
        padding: 0.5rem 1.25rem;
    }

    .government_<?php echo $this->uniqId ?> img.main_img {
        width: 110px;
        border-radius: 8px;
        margin-right: 15px;
    }

    .government_<?php echo $this->uniqId ?> .breadcrumb-line {
        margin-bottom: 0;
    }

    .government_<?php echo $this->uniqId ?> .header_icon_box a i {
        font-size: 22px;
    }

    .government_<?php echo $this->uniqId ?> .text-green {
        color: green !important;
    }

    .government_<?php echo $this->uniqId ?> .text-red {
        color: #f00 !important;
    }

    .government_<?php echo $this->uniqId ?> .header_icon_box h5 {
        line-height: normal;
    }

    .government_<?php echo $this->uniqId ?> .sidebar {
        margin-left: 0;
        background: #fff;
        border: 0;
        height: auto;
        margin-right: 12px;
    }

    .government_<?php echo $this->uniqId ?> .list-inline-dotted .list-inline-item:not(:last-child):after {
        display: none;
    }

    .government_<?php echo $this->uniqId ?> #accordion .card {
        border: 0;
    }

    .government_<?php echo $this->uniqId ?> #accordion .card .card-header {
        background: #aaa;
        border-radius: 5px;
        padding: 10px 20px;
    }

    .isitem .btn.finishadd {
        width: inherit;
        background: #fff !important;
        color: green;
        position: absolute;
        right: 10px;
        padding: 3px 7px;
        border: none;
        top: 10px;
        z-index: 2;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .timer-window {
        background: #eee;
        padding: 0px 15px;
        border-radius: 5px;
        min-width: 95px;
        border: none;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .timer-window span {
        font-size: 12px;
        text-transform: uppercase;
        color: #aaa;
        font-weight: 600;
        display: inline-block;
        margin-top: 7px;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .timer-window h5 {
        font-size: 22px;
        font-weight: 600;
    }

    .government_<?php echo $this->uniqId ?> #accordion .card .btn .card-header h6 {
        color: #fff;
        font-weight: bold;
        text-align: left;
    }

    .government_<?php echo $this->uniqId ?> .modal-dialog {
        /*    max-width: 1000px;
        margin: 1.75rem auto;*/
    }

    .government_<?php echo $this->uniqId ?> .modal .modal-header {
        background: none;
        display: none !important;
    }

    .modal-footer {
        background: none;
        /*display: none !important;*/
    }

    .government_<?php echo $this->uniqId ?> .card-table table tbody tr:hover,
    .government_<?php echo $this->uniqId ?> .card-table table tbody tr:active,
    .government_<?php echo $this->uniqId ?> .card-table table tbody tr:focus {
        background: #cce6ff;
    }

    .government_<?php echo $this->uniqId ?> .modal .modal-footer .closebtn {
        background: #e0e0e0;
        color: #000;
    }

    .government_<?php echo $this->uniqId ?> .confnum {
        width: 30px;
        height: 20px;
        font-weight: bold;
        padding: 1px 10px 1px 8px;
    }

    .government_<?php echo $this->uniqId ?> .confnum span {
        line-height: 21px;
        letter-spacing: -1.5px;
    }

    .government_<?php echo $this->uniqId ?> .startbtn.small {
        width: 44px;
        height: 34px;
        justify-content: center;
        align-items: center;
        padding: 0 !important;
        background: #f2f2f2 !important;
        padding-left: 3px !important;
        border: 1px solid #e5e5e5;
        margin-top: -5px;
    }

    .government_<?php echo $this->uniqId ?> .card .media-list .badge {
        margin-top: -10px;
    }

    .government_<?php echo $this->uniqId ?> .card .media-list .badge.badge-info {
        background: #2196f3;
    }

    .government_<?php echo $this->uniqId ?> .startbtn.small i {
        font-size: 15px;
        color: #2196f3;
    }

    .government_<?php echo $this->uniqId ?> .startbtn.green {
        background: #f2635f !important;
    }

    .government_<?php echo $this->uniqId ?> .startbtn i {
        font-size: 34px;
    }

    .government_<?php echo $this->uniqId ?> .timestart,
    .government_<?php echo $this->uniqId ?> .timeend {
        font-size: 12px;
        text-transform: uppercase;
        color: #aaa;
    }

    .line-height-normal {
        line-height: normal;
    }

    .government_<?php echo $this->uniqId ?> .confasname {
        font-size: 15px;
    }

    .government_<?php echo $this->uniqId ?> .confasname2 {
        font-size: 15px;
        line-height: normal;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
        overflow: hidden;
    }

    .government_<?php echo $this->uniqId ?> .membername {
        font-size: 16px;
        color: #252F4A;
    }

    .government_<?php echo $this->uniqId ?> .memberposition1,
    .government_<?php echo $this->uniqId ?> .memberposition2,
    .government_<?php echo $this->uniqId ?> .memberposition {
        font-size: 10px;
        width: 100%;
        color: #999;
        text-transform: uppercase;
    }

    .government_<?php echo $this->uniqId ?> .parliament_logo {
        height: 50px;
    }

    .government_<?php echo $this->uniqId ?> .text-gray {
        color: #999;
    }

    .government_<?php echo $this->uniqId ?> .font-size-vs {
        font-size: 12px;
    }

    .government_<?php echo $this->uniqId ?> .font-size-m {
        font-size: 14px;
    }

    .government_<?php echo $this->uniqId ?> .font-size-lg {
        font-size: 15px;
    }

    .government_<?php echo $this->uniqId ?> .bg-gray-400 {
        background: #c0c0c0;
    }

    .government_<?php echo $this->uniqId ?> .badgecus {
        background: transparent;
        width: auto;
        height: auto;
        border-radius: 50px;
        padding: 1px 6px 1px 5px;
        width: 16px;
        position: relative;
        height: 14px;
        color: #fff;
        font-weight: bold;
        font-size: 11px;
        margin-left: 5px;
        margin-top: -1px;
        letter-spacing: -1px;
    }

    .government_<?php echo $this->uniqId ?> .badgecus i {
        font-size: 11px;
        position: absolute;
        z-index: 1;
        color: #2196f3;
        cursor: pointer;
    }

    .v1 .tab-content li {
        cursor: pointer;
        border-bottom: 1px solid #e0e0e0;
    }

    .v1 .tab-content li.cancel {
        background: #f3e8e8 !important;
    }

    button i.icon-pause {
        cursor: pointer;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .timerInit {
        border: none !important;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .breakTimer h5,
    .government_<?php echo $this->uniqId ?> .header-elements .durationTimer h5 {
        border: none;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .breakTimer h5 span,
    .government_<?php echo $this->uniqId ?> .header-elements .durationTimer h5 span {
        color: green;
        font-size: 22px;
        font-weight: 600;
        margin: 0;
        border: none;
    }

    .government_<?php echo $this->uniqId ?> .header-elements .breakTimer h5 span {
        color: #ff9800 !important;
    }

    .btnstatus .icon-briefcase3,
    .btnstatus .icon-plus3 {
        color: #ee9248 !important;
    }

    .btnstatus .icon-subtract {
        color: #f85656 !important;
    }

    .btnstatus .icon-lock {
        color: #89a652 !important;
    }

    .btnstatus .icon-alarm {
        color: #9d8aa5 !important;
    }

    .btnstatus .icon-circle2 {
        color: green !important;
    }


    /** issui*/


    .conferencing-reviewgov-list li.active span,
    .conferencing-issue-list li.active span{
    /* color:#fff !important; */
    }

    .conferencing-reviewgov-list li.issue-stop,
    .conferencing-issue-list li.issue-stop {
        background: #9cf9aa;
    }

    .conferencing-reviewgov-list li.issue-start,
    .conferencing-issue-list li.issue-start {
        background: #f9e3c3;
    }

    .conferencing-reviewgov-list li.active,
    .conferencing-issue-list li.active {
        background: #c8e7ff;
    }

    .conferencing-reviewgov-list li .startbtn i,
    .conferencing-issue-list li .startbtn i {
        font-size: 22px;
    }

    .conferencing-reviewgov-list li .startbtn,
    .conferencing-issue-list li .startbtn {
        width: 60px !important;
        height: 50px !important;
        border: none !important;
    }

    .media-list li {
        /* transition: 0.5s; */
    }

    .conferencing-reviewgov-list li ul li,
    .conferencing-issue-list li ul li {
        padding: 0;
    }

    .conferencing-reviewgov-list li ul li,
    .conferencing-issue-list li ul li:hover {
        padding: 0;
        background: transparent;
    }

    .media-list li:last-child {
        border: none !important;
    }
    /* .government_<?php echo $this->uniqId ?> .not-tab > a {
        background: #f2f2f2;
    } */

    .government_<?php echo $this->uniqId ?> .form-control[disabled], .government_<?php echo $this->uniqId ?> .form-control[readonly], .government_<?php echo $this->uniqId ?> fieldset[disabled] .form-control {
        background-color: #FFF;
        color: #000;
        border: none;
    }

    .bg-huraldaan{
        border-color: var(--root-color1) !important;
        background-image: none !important;
        background-color: var(--root-color1) !important;
        color: #FFFFFF !important;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        border-radius: 10px;
    }

    .bg-white-huraldaan{
        border-color: #FFFFFF !important;
        background-image: none !important;
        background-color: #FFFFFF !important;
        color: var(--root-color1) !important;
        font-size: 14px;
        font-style: normal;
        font-weight: 500;
        line-height: normal;
        border-radius: 10px;
    }

    .huraldaan-color{
        color:var(--root-color1) !important;
    }

    .huraldaan-btn{
        color:#fff;
        width: 60px;
        height: 50px;
        background: var(--root-color1);
        justify-content: center;
        align-items: center;
        padding: 0 !important;
        padding-left: 5px !important;
        font-size:22px;
        height:64px;
    }

    .huraldaan-total{
        color:var(--root-color1) !important;
        font-size:12px;
        font-weight:700;
    }
    
    .government_<?php echo $this->uniqId ?> .general_number_conf {
        width: auto;
        align-items: center;
        display: flex;
        justify-content: center;
        font-weight: bold;
        margin-right: 5px;
    }

    .br-10{
        border-radius:10px;
    }

    .government_<?php echo $this->uniqId ?> .not-tab > a {
        background: #fff;
    } 

    .government_<?php echo $this->uniqId ?> .parliament_logo {
        height: 60px;
    }

    .conferencing-reviewgov-list li.active, .conferencing-issue-list li.active {
        background: #fff;
    }

    .crm-title{
        color: #A0A0A0;
        font-size: 12px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }

    .conferencing-reviewgov-list li.issue-stop,
    .conferencing-issue-list li.issue-stop {
        background: #fff;
    }

    .conferencing-reviewgov-list li,
    .conferencing-issue-list li {
        border-bottom: 1px dotted #e0e0e0;
        padding: 15px 30px 15px 5px;
    }

    .isitem .btn.finishadd {
        width: inherit;
        background: var(--root-color1) !important;
        color: #fff;
        position: absolute;
        right: 10px;
        padding: 3px 7px;
        border: none;
        top: 10px;
        z-index: 2;
    }

    .isitem .btn.finishFeedback {
        width: inherit;
        background: var(--root-color1) !important;
        color: #fff;
        position: absolute;
        right: 50px;
        padding: 3px 7px;
        border: none;
        top: 10px;
        z-index: 2;
    }

    .conferencing-reviewgov-list li.issue-start,
    .conferencing-issue-list li.issue-start {
        background: #fff;
    }

    .modultitle {
        color: #000;
        font-size: 18px;
        font-style: normal;
        font-weight: 500;
        line-height: 23px; /* 127.778% */
    }

    .protocalTxt {
        color: var(--root-color1);
        font-size:16px;
        font-weight:700; 
    }

    .base-timer {
        position: relative;
        width: 200px;
        height: 200px;
    }

    .base-timer__svg {
        transform: scaleX(-1);
    }

    .base-timer__circle {
        fill: none;
        stroke: none;
    }

    .base-timer__path-elapsed {
        stroke-width: 7px;
        stroke: grey;
    }

    .base-timer__path-remaining {
        stroke-width: 7px;
        stroke-linecap: round;
        transform: rotate(90deg);
        transform-origin: center;
        transition: 1s linear all;
        fill-rule: nonzero;
        stroke: currentColor;
    }

    .base-timer__path-remaining.green {
        color: rgb(65, 184, 131);
    }

    .base-timer__path-remaining.orange {
        color: orange;
    }

    .base-timer__path-remaining.red {
        color: red;
    }

    .base-timer__label {
        position: absolute;
        width: 200px;
        height: 200px;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--root-color1);
        font-weight:700; 
    }


    
</style>
