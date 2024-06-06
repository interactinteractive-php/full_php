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
    
    .conferencing-reviewgov-list li.c-issue-list:hover,
    .conferencing-issue-list li.c-issue-list:hover {
        border-left: 3px solid #ec407a !important;
        padding-left:7px;
    }
    
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
    
    .conferencing-reviewgov-list li.c-issue-list,
    .conferencing-issue-list li.c-issue-list {
        padding-left:10px;
    }

    .government_<?php echo $this->uniqId ?> ::-webkit-scrollbar-thumb,
    .status-dialog-process::-webkit-scrollbar-thumb {
        background: rgb(2, 166, 105) !important;
    }

    .government_<?php echo $this->uniqId ?> .sidebar-right2 {
        width:30rem;
        margin-left: 15px;
    }
    .participants {
        font-size: 12px;
        font-weight: 600;
        color: #2196f3;
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
        font-size: 14px !important;
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
</style>
