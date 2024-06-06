<style type="text/css">
    .socialview .lightbox {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.7);
        padding: 75px;
        text-align: center;
        display: none;
        cursor: -webkit-zoom-out;
        cursor: -moz-zoom-out;
    }
    .socialview .lightbox figure {
        display: block;
        position: relative;
        width: 100%;
        height: 100%;
        white-space: no-wrap;
        background-repeat: no-repeat;
        background-position: center;
        -moz-background-size: contain;
        -o-background-size: contain;
        -webkit-background-size: contain;
        background-size: contain;
    }

    .socialview .comment-list-section .fa {
        font-size: 1.2em;
    }
    .socialview .comment-list-section .caption .fa {
        font-size: 0.7em;
        color: black;
    }
    .socialview .comment-list-section{
        background-color: #ecf2f6;
        padding-left: 5px;
        padding-right: 5px;
    }
    .socialview .media{
        background-color: #fff;
        margin-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 10px;
        border-radius: 0px;
        position: relative;
    }
    .socialview .comment-date{
        font-size: 0.7em;
        color: #5D5F5F;
        margin-left: 10px;
    }
    .socialview .counts {
        font-size: 15px;
        margin-right: 20px;
        margin-left: 5px;
    }

    .socialview .comment-detail {
        margin-left: 40px;
    }
    .socialview .media > .float-left {
        padding-right: 10px;
    }
    .socialview .media img {
        position: relative;
        top: 3px;
    }
    .socialview img.img-thumbnail {
        cursor:zoom-in;
    }
    .socialview{
        bottom: 0;
        content: "";
        display: block;
        top: 0;
        margin-left: -18px;
        margin-right: -18px;
    }
    .socialview .comment-hr{
        border-color: #929595 -moz-use-text-color -moz-use-text-color;
    }
    .socialview .panel{
        padding-left: 18px;
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 0px;
        border-radius: 0px !important;
        border-style: solid;
        border-width: medium;
        position: relative;
        background-color: #1111;
    }
    .socialview .panel-body{
        padding-top: 0px !important;
        padding-left: 0px !important;
        padding-right: 15px !important;
        padding-bottom: 15px !important;
    }
    .socialview .media-body p{
        font-size: 12px;
        color: #7a7e7e !important;
    }
    .socialview .media-heading{
        margin-top: 5px;
        margin-bottom: 0px;
    }
    .socialview .card-subject-customed{
        color: #555757 !important;
        font-size: 12px;
        margin-top: 3px;
    }
    .socialview .card-subject-customed-name{
        color: #4e86c1 !important;
    }
    .socialview .card-subject-customed-title{
        color: #7a7e7e !important;
        font-weight: normal !important;
    }
    .panel-body hr:last-child{
        display: none;
    }
    
    .content-left-<?php echo $this->metaDataId ?> {
        
    }
    #commentInputForm_<?php echo $this->metaDataId ?> {
        background-color: #FFF; 
        margin-right: 7px; 
        margin-left: -3px; 
        border-radius: 5px;
    }
    .content-right-<?php echo $this->metaDataId ?> {
        width: 200px;
        float: right;
        top: -30px;
    }
    
    .mainWindow-<?php echo $this->metaDataId ?> {
        margin: 0 auto;
        padding: 0;
        width: 1100px;
    }
    
    .mainSocialView .tabbable-line > .tab-content {
        background-color: inherit;
    }
    
    .mainSocialView .nav-tabs>li.active>a, .mainSocialView .nav-tabs>li.active>a:focus, .mainSocialView .nav-tabs>li.active>a:hover {
        background-color: inherit;
    }
    
    .mainSocialView ::-webkit-scrollbar {
        width: 4px;
    }
    
    .mainSocialView ::-webkit-scrollbar-thumb {
        /*background: transparent;*/
        background: #478ce2;
    }
    
    .notary-logo-<?php echo $this->metaDataId ?> {
        background: #FFF;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        margin: 13px -6px -12px -18px;
    }
    
    .cover-pic-<?php echo $this->metaDataId ?> {
        background: url(storage/uploads/img/banner-5.jpg);
        height: 280px;
        background-size: cover;
        background-repeat: no-repeat;
        border-radius: 5px;
        margin: 13px 7px 0px -3px;
    }
    
    .cv-file-button {
        position: relative;
        overflow: hidden;
        clear: left;
    }
    
    .cv-file-button input[type="file"] {
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        opacity: 0;
        font-size: 100px;
        filter: alpha(opacity=0);
        cursor: pointer;
        height: 50px;
    }
    
    .postData-<?php echo $this->metaDataId ?> {
        min-height: 50px;
        margin-top: 12px;
    }
    .socialview .comment-list-section {
        background-color: #d8d8d8;
    }
    
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .comment-list-section {
        background: #FFF;
        margin-right: 6px;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media {
        text-transform: uppercase;
        border-bottom: 1px solid #d8d8d8;
        padding-bottom: 13px;
        cursor: pointer;
        margin-top: 0px;
        background-color: #FFF;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media:hover {
        background-color: #F00;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media:hover {
        background-color: rgb(236, 236, 236);
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media:hover .card-subject-customed-name {
        color: #000 !important;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media .card-subject-customed-name {
        color: #000 !important;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .comment-list-section {
        padding: 0;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> {
        margin-top: 12px;
        margin-right: 6px;
    }
    .rightSocialViewDv-<?php echo $this->metaDataId ?> {
        margin-top: 12px;
        position: fixed;
    }
    .userProfile-<?php echo $this->metaDataId ?> {
        background: #d8d8d8;
        margin-right: -11px;
        margin-top: 13px;
        margin-left: -18px;
        padding-bottom: 10px;
    }
    .userProfile-<?php echo $this->metaDataId ?> .rounded-circle {
        bottom: 0;
        left: 0;
        margin: auto;
        max-height: 30px;
        max-width: 30px;
        position: absolute;
        right: 0;
        top: 10px;
        width: 30px;
    }
    .userProfile-<?php echo $this->metaDataId ?> .userProfileName {
        margin-left: 40px;
        text-overflow: ellipsis;
        white-space: nowrap;
        margin-top: -20px;
        font-size: 15px;
        font-weight: 700;
        color: #000;
    }
    .userProfile-<?php echo $this->metaDataId ?> .profileImage {
        height: 20px;
        margin: 4px 6px 0 2px;
        position: relative;
        width: 20px;
    }
    .userProfile-<?php echo $this->metaDataId ?>:hover {
        background-color: rgb(236, 236, 236);
    }
    .mainSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media, .rightSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media {
        margin-top: 0;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-radius: 5px;
    }
    .mainSocialViewDv-<?php echo $this->metaDataId ?> {
        margin-right: 10px;
    }
    
    .mainSocialViewDv-<?php echo $this->metaDataId ?> .media-footer {
        background: #FFF;
        margin-bottom: 15px;
        border-top-left-radius: 0;
        border-top-right-radius: 0;
        margin-top: 2px;
    }
    .mainSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media {
        margin-bottom: 0px;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        padding-bottom: 20px;
    }
    .mainSocialViewDv-<?php echo $this->metaDataId ?> .media-footer .media {
        border-bottom-left-radius: 5px !important;
        border-bottom-right-radius: 5px !important;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media.shadow.active{
        background: rgba(140, 183, 87, 0.89) !important;
    }
    .leftSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media.shadow.active .card-subject-customed-name{
        color: #FFF !important;
    }
    
    .mainSocialView .socialview .comment-list-section {
        background-color: #ebebeb;
    }
    
    .rightTopSocialViewDv-<?php echo $this->metaDataId ?> {
        max-height: 480px;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0 15px;
    }
    
    .rightCenterSocialViewDv-<?php echo $this->metaDataId ?> {
        overflow-x: hidden;
        overflow-y: auto;
        max-height: 985px;
        margin-top: 10px;
        padding-right: 5px;
        padding-left: 5px;
    }
    .rightCenterSocialViewDv-<?php echo $this->metaDataId ?> .socialview .card-subject-customed-name {
        color: #7b7878 !important;
        font-weight: normal !important;
        
        margin: 0;
    }
    .rightCenterSocialViewDv-<?php echo $this->metaDataId ?> .socialview .caption-online-status {
        background: rgb(66, 183, 42); 
        border-radius: 50%; 
        display: inline-block; 
        height: 6px; 
        margin-left: 4px; 
        width: 6px; 
        float: right; 
        margin-top: -16px;
    }
    .rightCenterSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media {
        margin-left: 15px;
        margin-right: 15px;
        max-height: 40px;
        margin-bottom: 0;
        border-bottom: 1px solid #FFF;
        border-radius: 0;
        background-color: #FFF;
    }
    .rightTopSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media {
        max-height: 130px;
        min-height: 130px;
        border-radius: 10px;
    }
    .rightTopSocialViewDv-<?php echo $this->metaDataId ?> .socialview .media-body p {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .media-body p {
        text-align: justify;
    }
    
    .createProcessTop-<?php echo $this->metaDataId ?> .socialview-title, .createProcessCenter-<?php echo $this->metaDataId ?> .socialview-title {
        padding: 13px;
        color: #5397c9;
        font-weight: bold;
        text-transform: uppercase;
        float: left;
    }
    
    .createProcessTop-<?php echo $this->metaDataId ?> .socialview-description, .createProcessCenter-<?php echo $this->metaDataId ?> .socialview-description {
        float: left;
        width: 179px;
        padding: 0 10px 0 10px;
    }
    
    .createProcessTop-<?php echo $this->metaDataId ?> .socialview-content, .createProcessCenter-<?php echo $this->metaDataId ?> .socialview-content {
        padding: 10px;
        float: right;
    }
    
    .createProcessTop-<?php echo $this->metaDataId ?> {
        background: #FFF;
        width: 100%;
        min-height: 40px;
        margin-top: 10px;
        border-radius: 10px;
    }
    
    .createProcessBottom-<?php echo $this->metaDataId ?> {
        background: #FFF;
        width: 100%;
        min-height: 40px;
        margin-top: 10px;
        border-radius: 10px;
    }
    
    .createProcessCenter-<?php echo $this->metaDataId ?> {
        background: #FFF;
        width: 100%;
        min-height: 40px;
        margin-top: 10px;
        border-radius: 10px;
    }
</style>