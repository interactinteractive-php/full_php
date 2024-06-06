<style type="text/css">
    .select2-results {
        padding: 0;
    }
    .select2-results .select2-result-label {
        padding: 10px 5px 10px;
    }
    .dvecommerce .dvecommercetitle a {
        padding: 0;
        margin-bottom: 15px;
    }
    .chat-container, .chat-container-parent {
        display: table;
        padding: 8px 10px 0px 10px;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        background-color: rgb(170, 103, 8);
        color: #fff;
        margin-left: 45px;
        margin-bottom: 2px;
    }
    .chat-container-self, .chat-container-parent-self {
        display: table;
        padding: 8px 10px 0px 10px;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        -moz-border-radius: 10px;
        background-color: #E2E2E2;
        margin-bottom: 2px;
    }
    .chat-container-parent {
        position: relative;
        border-top-left-radius: 0;
        -webkit-border-top-left-radius: 0;
        -moz-border-top-left-radius: 0;
    }
    .chat-container-parent-self {
        position: relative;
        border-bottom-right-radius: 0;
        -webkit-border-bottom-right-radius: 0;
        -moz-border-bottom-right-radius: 0;
    }
    .chat-container-parent:after {
        content: '';
        display: block;
        position: absolute;
        right: 100%;
        top: 10px;
        margin-top: -10px;
        width: 0;
        height: 0;
        border-top: 0px solid transparent;
        border-right: 4px solid rgb(170, 103, 8);
        border-bottom: 6px solid transparent;
        border-left: 6px solid transparent;
    }
    .chat-container-parent-self:after {
        content: '';
        display: block;
        position: absolute;
        left: 100%;
        bottom: 0;
        margin-top: -10px;
        width: 0;
        height: 0;
        border-top: 6px solid transparent;
        border-left: 4px solid #E2E2E2;
        border-bottom: 0px solid transparent;
        border-right: 6px solid transparent;
    }
    .chat-container-child {
        margin-top: 1px;
    }
    .chat-user-img {
        width: 40px;
    }
    .chat-user-img.chat-user-date {
        margin-top: 0;
    }
    .chat-created-date {
        margin-bottom: 2px;
        margin-top: 2px;
        color: #9E9E9E;
        font-size: 10px;
    }
    .thumbnail_custom {
        height: 32px;
        width: 32px;
    }
    .panel-body-scroll {
        overflow-y: auto;    
        overflow-x: hidden;
        visibility: hidden;
        margin-right: -15px;
        padding-right: 15px;
    }
    #chatMessages {
        padding-bottom: 0px !important;
        background-color: #fff;
    }
    #chat-body-container {
        visibility: visible;
    }
    .panel-body-scroll:hover {
        visibility: visible;
    }
    .user-active-img {
        height: 6px;
        width: 6px;
        position: absolute;
        margin-top: 16px;
        margin-left: -10px;
        background: #fff url(../images/user_active_img.png) no-repeat;
    }
    .required {
        color: #a94442;
    }
    .custom-modal-header {
        padding: 10px;
        border-bottom: 1px solid #e5e5e5;
        height: 42px;
    }
    .custom-modal-body {
        position: relative;
        padding: 10px;    
    }
    .custom-modal-footer {
        padding: 6px 10px 10px 10px;
        text-align: right;
        border-top: 1px solid #e5e5e5;
        height: 42px;
    }
    .custom-modal-header .modal-title {
        font-size: 15px;
        font-weight: bold;
    }
    .modal-350 {
        width: 350px;
    }
    label {
        font-weight: normal !important;
    }
    .cropImgWrap {
        background: transparent;
        overflow: hidden;
        width: 468px;
        height: 320px;
        margin-left: -27px;
    }
    img-crop {
        margin-top: 6px;
    }
    .media {
        margin-top: 0;
    }
    .media-heading {
        margin-bottom: 0;
    }
    .badge-custom-background {
        background-color: #a0a0a0 !important;
    }
    .userActive {
        border-left: 2px solid #337AB7;
        font-weight: bold;
        font-family: "Roboto", "Helvetica Neue",Helvetica,Arial,sans-serif;
        background-color: #F5F5F5;
    }
    .user-profile-link {
        position: absolute;
        right: 0;
        padding-right: 48px;
        margin-top: 11px;    
        width: 160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;        
        text-align: right;
    }
    .user-signout {
        position: absolute;
        right: 0;
        margin-right: 17px;
        margin-top: 11px; 
    }
    .loader-typing:before,
    .loader-typing:after,
    .loader-typing {
        border-radius: 50%;
        width: 2.6em;
        height: 2.6em;
        -webkit-animation-fill-mode: both;
        animation-fill-mode: both;
        -webkit-animation: load7 1.6s infinite ease-in-out;
        animation: load7 1.6s infinite ease-in-out;
    }
    .loader-typing {
        color: #009900;
        font-size: 2.4px;
        margin: 0px 12px 12px 10px;
        position: relative;
        text-indent: -9999em;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation-delay: -0.16s;
        animation-delay: -0.16s;
    }
    .loader-typing:before {
        left: -3.5em;
        -webkit-animation-delay: -0.32s;
        animation-delay: -0.32s;
    }
    .loader-typing:after {
        left: 3.5em;
    }
    .loader-typing:before,
    .loader-typing:after {
        content: '';
        position: absolute;
        top: 0;
    }
    @-webkit-keyframes load7 {
        0%,
        80%,
        100% {
            box-shadow: 0 2.5em 0 -1.3em;
        }
        40% {
            box-shadow: 0 2.5em 0 0;
        }
    }
    @keyframes load7 {
        0%,
        80%,
        100% {
            box-shadow: 0 2.5em 0 -1.3em;
        }
        40% {
            box-shadow: 0 2.5em 0 0;
        }
    }
    label.custom-file-input input[type="file"] {
        position: fixed;
        top: -1000px;
    }
    label.custom-file-input span {
        opacity: 0.6;
        font-size: 15px;
        color: black;
    }
    label.custom-file-input span:hover {
        display: inline-block;
        opacity: 1;
    }
    .user-list-right-section {
        overflow-y: auto;
        overflow-x: hidden;
    }
    .emoji-wysiwyg-editor {
        height: 38px !important;
        padding-left: 11px !important;
        padding-top: 9px !important;
        font-size: 15px !important;
        overflow: hidden !important;
    }
    .emoji-picker-icon {
        position: inherit !important;
        opacity: 0.6 !important;
        font-size: 17px !important;
        cursor: default !important;
    }
    .emoji-picker-icon:hover {
        opacity: 1 !important;
    }
    .main-wrap .panel-title .chat-header-title {
        font-size: 14px !important;
    }
    .main-wrap .panel {
        margin-bottom: 0px !important;
    }
    .tooltip.customTooltipClass .tooltip-inner {
        font-size: 11px;
    }

    .dvecommerce-body .ui-dialog .ui-widget-header {
        height: 40px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-title {
        line-height: 24px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane button {
        padding: 5px 20px;
        text-transform: uppercase;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-buttonpane {
        margin-top: 0;
        background: #DDD;
        border: 0;
        padding: 5px 10px;
    }
    .dvecommerce-body .ui-dialog .ui-dialog-content {
        padding: 10px 15px 0;
    }
    .dvecommerce .xs-form .form-group .col-md-12.pl0.pr0 {
        width: 238px;
    }
    .dropdown-toggle-hide.dropdown-toggle::after {
        display: none;
    }
    <?php if (isset($this->useBasket) && $this->useBasket) { ?>
        .ecommerce_listwidget .datagrid-pager {
            display: none;
        }
    <?php } ?>
</style>