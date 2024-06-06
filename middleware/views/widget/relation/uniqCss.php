
<style type="text/css">

    div[aria-describedby="dialog-widgetrender-<?php echo $this->mainIndicatorId ?>"] {
        .wg-form-paper {
            background-image: url('middleware/assets/img/process/background/back.png');
            background-repeat: no-repeat;
            background-position: top center;
            background-attachment: fixed;
            background-color: #ededed;
            background-size: cover;

            .wg-form {
                position: relative;
                width: 1000px;
                /* min-height: calc(100vh - 126px); */
                margin-left: auto;
                margin-right: auto;
                .card-side  {
                    margin: 0px;
                    padding: 0px;
                    box-shadow: none;
    
                    .nav-link.active {
                        color: #699BF7 !important;
                        font-size: 18px;
                    }
                    
                    .nav-link {
                        font-size: 18px;
                        font-weight: 500;
                        line-height: 21px;
                        letter-spacing: 0px;
                        text-align: left;
                        padding: 20px;
                    }
    
                    .nav-tabs-bottom .nav-link.active:before {
                        background-color: #699BF7 !important;
                    }
    
                    .headerTitle {
                        font-size: 24px;
                        font-weight: 700;
                        line-height: 28px;
                        letter-spacing: 0px;
                        text-align: left;
                    }
    
                    .nav-tabs-bottom {
                        box-shadow: 0px 5px 10px 0px #0000001A;
                    }
    
                    .bp-btn-save {
                        padding: 12px 15px 10px 15px;
                        border-radius: 20px;
                        gap: 5px;
                        background: #468CE2;
                        font-size: 14px;
                        line-height: 16px;
                        letter-spacing: 0em;
                        text-align: center;
                    }
                    
                    .form-control {
                        border-radius: 20px;
                        border: 0.5px;
                        padding: 11px 20px;
                        font-size: 16px;
                        font-weight: 600;
                        line-height: 24px;
                        letter-spacing: 0px;
                        text-align: left;
                        height: 46px !important;
                        color: #585858;
                    }
    
                    .form-label {
                        font-size: 16px;
                        font-weight: 400;
                        line-height: 24px;
                        letter-spacing: 0px;
                        text-align: left;
                    }
    
                    .padding-content {
                        padding: 24px 11px 0 11px;
                    }
    
                    .mv-checklist-menu {
                        overflow-y: auto;
                        overflow-x: hidden;

                        .nav-group-sub-mv-opened .nav-group-sub {
                            display: block;
                        }
                        .nav-sidebar .nav-item:not(.nav-item-header):last-child {
                            padding-bottom: 0 !important;
                        }
                        .nav-item-submenu.nav-group-sub-mv-opened>.nav-link:after {
                            -webkit-transform: rotate(90deg);
                            transform: rotate(90deg);
                        }
                        .nav-group-sub .nav-link {
                            padding-left: 20px;
                        }
                        .nav-item-submenu>.nav-link.mv_checklist_02_groupname:after {
                            margin-top: -6px;
                        }
                        .nav-link.mv_checklist_02_groupname {
                            font-size: 13px;
                            color: #333 !important;
                            font-weight: bold !important;
                            padding-top: 5px;
                            padding-bottom: 5px;
                            text-transform: none !important;
                        }    
                        .nav-link.mv_checklist_02_sub {
                            padding-top: 2px;
                            padding-bottom: 2px;
                            font-size: 12px;
                        }    
                        .nav-link.mv_checklist_02_sub i {
                            color: #1B84FF !important;
                            margin-top: 2px;
                            font-size: 18px;    
                            margin-right: 13px;
                        }    
                    }
                    
                    .main-content {
                        background-color: #F9F9F9;
                        overflow-y: auto;
                        overflow-x: hidden;
                    }

                    .mv-checklist-render-comment {

                        .question-txt {
                            font-size: 28px;
                            font-weight: 500;
                            line-height: 32px;
                            letter-spacing: 0px;
                            text-align: center;
                            color: #3C3C3C;
                            margin-top: 100px;
                            margin-bottom: 20px;
                        }

                        .comment-txt {
                            font-size: 18px;
                            font-weight: 500;
                            line-height: 21px;
                            letter-spacing: 0px;
                            text-align: center;
                            color: #67748E;
                            margin-bottom: 20px;
                            width: 440px;
                            margin: 0 auto;
                        }
                        
                        .answer-txt:not(.answer-txt-nopadding) {
                            font-size: 12px;
                            font-weight: 400;
                            line-height: 14px;
                            letter-spacing: 0px;
                            text-align: left !important;
                            padding: 15px 25px 15px 25px !important;
                            border-radius: 50px !important;
                            gap: 10px !important;
                            margin-bottom: 15px;
                            background-color: #FFF !important;
                            border-color: #FFF;
                            color: #585858 !important;
                        }
                        .answer-txt-nopadding {
                            padding: 0 0 0 0.5rem !important;
                        }
                        .answer-txt:hover {
                            color: #FFF !important;
                            background: linear-gradient(90deg, #468CE2 0%, rgba(70, 140, 226, 0.52) 100%);
                        }
                        
                    }
                }

                .position-timer {
                    margin-left: 10px;
                    margin-top: 10%;

                    .card-body {
                        width: 300px;
                        height: 140px;
                        padding: 10px;
                        border-radius: 20px;
                        border: none;
                        box-shadow: 0px 5px 10px 0px #0000001A;
                        
                        .timer {

                            font-size: 38px;
                            font-weight: 400;
                            line-height: 38px;
                            letter-spacing: 0px;
                            text-align: center;
                            color: #585858;

                            .num {
                                font-size: 38px;
                                font-weight: 600;
                                line-height: 38px;
                                letter-spacing: 0px;
                                text-align: center;
                                color: #699BF7;
                            }
                            .txt {
                                font-size: 12px;
                                font-weight: 400;
                                line-height: 14px;
                                letter-spacing: 0px;
                                text-align: center;
                                color: #585858;
                            }
                            
                            .all {
                                display: grid;
                                padding: 0 10px;
                            }
                        }
                    }
                }
                .vid-component {
                    padding: 10px;

                    .detail_cart_slider_imagevideo,
                    #bp-video-id {
                        border-radius: 7.5px;
                        border: 1px solid #f5f5f5;
                        box-shadow: 0px 5px 10px 0px #0000001A;
                        min-height: 500px;
                    }
                }
                
                .col-component {
                    flex-wrap: nowrap;
                    grid-gap: 10px;
                    max-height: 225px;
                    padding: 10px;

                    .media-component {
                        width: 225px;
                        min-height: 125px;
                        border-radius: 7.5px;
                        border: 1px solid #f5f5f5;
                        box-shadow: 0px 5px 10px 0px #0000001A;
                        padding: 8px;
                        display: grid;
                        /* img {
                            height: 125px;
                        } */
                    }
                    .media-component:hover {
                        cursor: pointer;
                    }
                }
            }   
        }

        #dialog-widgetrender-<?php echo $this->mainIndicatorId ?> {
            padding-left: 0;
            padding-right: 0;
        }

        .ui-dialog-titlebar {
            border: none;
        }
    }
    
</style>