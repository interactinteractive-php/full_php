<html>
    <head>
        <base href="<?php echo URL ?>">
        <title>Хэвлэх хуудас</title>
        <link href="<?php  echo autoVersion('assets/core/css/core.css');  ?>" rel="stylesheet" />
        <link href="<?php  echo autoVersion('assets/custom/css/plugins.css');  ?>" rel="stylesheet" />
    </head>
    <body>
        <style>
            @media print  {
                #printablePage  {
                    visibility:visible;
                    position:absolute;
                    padding-top:20px;
                    margin:57px 20px 20px 20px;
                    left:0px;
                    top:0px;
                }
                .topRow a  {
                    display:none;
                }
                #mainTable td  {
                    border:1px solid gray !important;
                }
                tr.keyRow td,
                tr.keyRow td.yellow,
                tr.keyRow td.pink,
                tr.keyRow td.green,
                tr.itemRow td,
                tr.depRow td  {
                    color:black !important;
                }
            }
            @page  {
                margin:10mm;
            }

            body  {
                margin:0px;
            }
            #fixedHeader  {
                display:none;
                z-index:1;
            }
            #reportBox  {
                overflow-y:auto;
            }
            #printablePage  {
                margin-top:7px;
            }
            #printablePage table, #fixedHeader table  {
                border-collapse:collapse;
                border:0px;
                padding:20px;
            }
            tr.keyRow  {
                cursor:default;
                background-color:#e5f0ff;
            }
            tr.keyRow td  {
                border-top:1px solid #c5d4e8;
                border-bottom:1px solid #c5d4e8;
                color:#255e92;
            }
            tr.keyRow td.over  {
                background-color:#d0e5ef;
            }
            tr.keyRow td.yellow  {
                color:#96532b;
                background-color:#fff3d0;
                border-top:1px solid #f3dba1;
                border-bottom:1px solid #f3dba1;
            }
            tr.keyRow td.yellow.over  {
                background-color:#ffe5bf;
            }
            tr.keyRow td.pink  {
                color:#953030;
                background-color:#fed5d5;
                border-top:1px solid #edb3b3 !important;
                border-bottom:1px solid #edb3b3 !important;
            }
            tr.keyRow td.pink.over  {
                background-color:#febebe;
            }
            tr.keyRow td.green  {
                color:#1a6c11;
                background-color:#c4ffcf;
                border-top:1px solid #a2e8af;
                border-bottom:1px solid #a2e8af;
            }
            tr.keyRow td.green.over  {
                background-color:#b1f6bd;
            }
            tr.keyRow.green  {
                background-color:#c4ffcf;
            }
            tr.keyRow.green td  {
                color:#1a6c11;
                border-top:1px solid #a2e8af;
                border-bottom:1px solid #a2e8af;
            }

            #mainTable tr.itemRow:nth-child(odd)  {
                background-color:#fafafa;
            }
            #mainTable tr.itemRow.over,
            #mainTable tr.sumRow.over  {
                background-color:#f2f2f2;
            }
            #mainTable tr.itemRow td,
            #mainTable tr.sumRow td  {
                color:#2b2b2b;
                vertical-align:top;
            }
            #mainTable tr.itemRow td.over,
            #mainTable tr.sumRow td.over  {
                background-color:#f3f3f3;
            }
            #newMainTable {
                overflow: hidden;
                z-index: 1;
            }
            #newMainTable tr.itemRow:hover td:not([rowspan]) {background: #f3f3f3}
            #newMainTable tr.itemRow:hover td[rowspan]:hover ~ td {background: none;}
            #printablePage .depRow  {
                background-color:#fffea3;
                height:25px;
            }
            #printablePage .depRow td  {
                color:#804f0f;
                border:1px solid #f9f2a1;
            }
            #printablePage td, #fixedHeader td  {
                font:12px Arial;
                padding:3px 6px 4px 6px;
                vertical-align:middle;
            }
            #printablePage .13px  {
                font:13px Arial;
            }
            #printablePage .counter  {
                background-color:#fffdc0;
                width:20px;
            }
            #printablePage .topRow  {
                color:black;
                font-size:14px;
                border-bottom:1px solid black;
                padding:0px 10px 8px 10px;
            }
            #printablePage .topRow .hidden  {
                display:none;
            }
            #printablePage .topRow .printDate  {
                float:right;
                padding:4px 0px 0px 0px;
            }
            #printablePage .topRow a  {
                text-decoration:none;
            }
            #printablePage .topRow .button  {
                cursor: pointer;
                font: 12px Arial;
                border: 1px solid #4CAF50;
                padding: 3px 6px 3px 6px;
                margin-left: 3px;
                background-color: #4CAF50;
                color: #ffffff;
                float: right;
                -moz-border-radius: 1px;
                -webkit-border-radius: 1px;
                border-radius: 4px;
            }
            #printablePage .topRow .printButton  {
                margin-left:8px;
            }
            #printablePage .topRow .printButton.over  {
                background-color:#bdd9ff;
            }
            #printablePage .topRow .printButton.down  {
                background-color:#3d8fff;
                border-color:white;
                color:white;
            }
            #printablePage .topRow .export  {
                background-color:pink;
                border:1px solid #ff4c6b;
                color:#ba0021;
            }
            #printablePage .topRow .export.over  {
                background-color:#ff8fa3;
            }
            #printablePage .topRow .export.down  {
                background-color:#ff002d;
                border-color:white;
                color:white;
            }
            #printablePage .title  {
                color:black;
                padding-top:15px;
                text-align:center;
                font:300 20px "wf_SegoeUILight","wf_SegoeUI","Segoe UI Light","Segoe WP Light","Segoe UI","Segoe","Segoe WP","Tahoma","Verdana","Arial","sans-serif";
            }
            #printablePage .subTitle  {
                color:black;
                font-size:15px;
                padding-bottom:10px;
            }
            #printablePage .footerComName {
                font: 14px Arial;
                text-align: left;
                padding: 20px 0px 25px 0px;
                color: #5d5d5d;
                text-transform: uppercase;
            }
            #printablePage .footerRow  {
                font:12px Arial;
                padding:20px 0px 25px 0px;
                text-align:right;
                color:gray;
            }
            #printablePage .crossTd, #fixedHeader .crossTd  {
                -webkit-transform:rotate(-90deg);
                -moz-transform:rotate(-90deg);
                -o-transform:rotate(-90deg);
                margin-bottom:7px;
                width:0px;
                height:0px;
            }

            #commentBox  {
                overflow:auto;
                padding:10px 20px 20px 16px;
            }
            #commentBox .cmtRow  {
                cursor:default;
            }
            #commentBox .cmtRow .userIcon  {
                width:38px;
                height:38px;
                background:url(/var/www/intranet/programs/style/img/manIcon.gif) no-repeat 3px 3px;
                border:1px solid #e1e1e1;
                padding:3px 2px 0px 1px;
                margin:3px 3px 0px 0px;
                -moz-border-radius:1px; -webkit-border-radius:1px; border-radius:1px;
            }
            #commentBox .cmtRow .blueUserSIcon  {
                background-position:3px -35px;
            }
            #commentBox .cmtRow .greenUserSIcon  {
                background-position:3px -73px;
            }
            #commentBox .cmtRow .cmtTitle  {
                background:url(img/bndBottom.gif) repeat-x 0px -9px;
            }
            #commentBox .cmtRow .cmtTitle div  {
                color:#4661a0;
                background-color:white;
                padding:1px 3px 0px 3px;
                float:left;
            }
            #commentBox .cmtRow .cmtTitle .autorName  {
                color:blue;
                cursor:pointer;
                padding:1px 4px 2px 4px;
                -moz-border-radius:1px; -webkit-border-radius:1px; border-radius:1px;
            }
            #commentBox .cmtRow .cmtTitle .autorOver  {
                background-color:#ceddff;
            }
            #commentBox .cmtRow .cmtTitle .autorDown  {
                background-color:blue;
                color:#e2e7ff;
            }
            #commentBox .cmtRow .cmtTitle .links  {
                float:right;
                background-color:white;
                padding-left:5px;
            }
            #commentBox .cmtTd  {
                width:100%;
                color:#232323;
                padding:3px 20pxx 0px 4px;
                vertical-align:top;
                text-align:justify;
                cursor:default;
            }
            #commentBox .dateBox  {
                font:11px Arial;
                cursor:default;
                padding:0px 1px 1px 1px;
                -moz-border-radius:1px; -webkit-border-radius:1px; border-radius:1px;
            }
            #commentBox .dateFirst  {
                color:green;
                margin:3px 0px 0px 2px;
            }
            #commentBox .firstOver  {
                background-color:#a8f8ba;
            }
            #commentBox .dateLast  {
                color:#f44a4a;
                margin-left:2px;
            }
            #commentBox .lastOver  {
                background-color:#ffd2da;
            }
        
            .votetitle {
                font:300 20px "wf_SegoeUILight","wf_SegoeUI","Segoe UI Light","Segoe WP Light","Segoe UI","Segoe","Segoe WP","Tahoma","Verdana","Arial","sans-serif";
            }

            .fs-9 {
                font-size: 9px !important;
            }
            </style>
        <div id="printablePage">
            <table id="header" width="800" align="center">
                <tbody>
                    <tr>
                        <td class="topRow" style="text-align:left; font-size:13px">
                            Хэвлэсэн 
                        </td>
                        <td class="topRow">
                            <!--<a href="main.php?p=cHiVbGlj&amp;m=bm3Vcw==&amp;g=ZG93bvxmYWRQcmludA==&amp;itemId=3055&amp;type=doc"><div class="button export" title="MS.Word файл руу хөрвүүлэх">MS-Word</div></a>-->
                            <!--<a href="main.php?p=cHiVbGlj&amp;m=bm3Vcw==&amp;g=ZG93bvxmYWRQcmludA==&amp;itemId=3055&amp;type=xls"><div class="button export" title="MS.Excel файл руу хөрвүүлэх">MS-Excel</div></a1>-->
                            <a href="javascript:print();"><div class="button printButton" title="Хуудсыг хэвлэх">Хэвлэх</div></a>
                            <div class="printDate"><?php echo Date::format('Y/m/d l H:i', Date::currentDate(), true) ?></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table align="center" width="800">
                <tbody>
                    <tr>
                        <td>
                            <table cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td style="font-size:18px; color:black; padding:18px; border:1px solid #dedede; text-align:left;">
                                            <?php echo $this->content ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table id="footer" align="center">
                <tbody>
                    <tr>
                        <td class="footerComName" style="width: 62%;">
                            <center><?php echo Config::getFromCache('system_footer_name') ?><br><?php echo Date::currentDate('Y') ?> он</center>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>

