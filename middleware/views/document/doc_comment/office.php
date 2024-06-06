<html>
<title>View - <?php echo $this->docname; ?></title>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="icon" href="./favicon.ico" type="image/x-icon" />
    <style>
        html {
            height: 100%;
            width: 100%;
        }

        body {
            background: #fff;
            color: #333;
            font-family: Arial, Tahoma,sans-serif;
            font-size: 12px;
            font-weight: normal;
            height: 100%;
            margin: 0;
            overflow-y: hidden;
            padding: 0;
            text-decoration: none;
        }

        form {
            height: 100%;
        }

        div {
            margin: 0;
            padding: 0;
        }
    </style>
    <script type="text/javascript" src="<?php echo Config::getFromCacheDefault('DOC_SERVER', null, ''); ?>/web-apps/apps/api/documents/api.js"></script>
    <script type="text/javascript">
        var docEditor;
        var boolSignReplaced = false;
        var firstCall = true;
        var fileName = <?php echo json_encode(html_entity_decode($this->docname, ENT_QUOTES, 'UTF-8')); ?>;
        var fileType = "<?php echo $this->ext; ?>";
        var innerAlert = function (message) { if (console && console.log) console.log(message); };
        var onAppReady = function () { innerAlert("Document editor ready"); };

        window.addEventListener('message',function(message){
          if(message.data.type=="docEventCustomReplace-<?php echo $this->docId; ?>"){
            if(message.data.value){
                var text_replace = {
                    textsearch: message.data.value.search,
                    textreplace: message.data.value.replace,
                    matchcase: true,
                    matchword: true,
                    highlight: false
                };
                docEditor.onReplaceText(text_replace);
            }
          }
        });

        var onDocumentReady = function ()
        {
            innerAlert("Document ready"); 
            <?php if(!empty($this->dcode)){ ?>
            var text_replace = {
                textsearch: "#code#",
                textreplace: '<?php echo $this->dcode; ?>',
                matchcase: false,
                matchword: false,
                highlight: true
             };
            docEditor.onReplaceText(text_replace);

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = yyyy + '-' + dd + '-' + mm;

            var text_replace = {
                textsearch: "#date#",
                textreplace: today,
                matchcase: false,
                matchword: false,
                highlight: true
            };
            docEditor.onReplaceText(text_replace);
            boolSignReplaced = true;
             // docEditor.denyEditingRights();
             <?php } ?>
        };

        var onDocumentStateChange = function (event) { var title = document.title.replace(/\*$/g, ""); document.title = title + (event.data ? "*" : ""); };
        var onRequestEditRights = function () { location.href = location.href.replace(RegExp("action=view\&?", "i"), ""); };
        var onError = function (event) { if (event) innerAlert(event.data); };
        var onOutdatedVersion = function (event) { location.reload(true); };

        /*var onDocumentStateChange = function (event) {
            console.log(event, boolSignReplaced);
            if(event.data == false && boolSignReplaced == true && firstCall == true){
                firstCall = false; 
                setTimeout(function(){
                    docEditor.destroyEditor();
                    parent.postMessage({
                        type:'documentNumberChanged-<?php echo $this->docId; ?>',
                    },"<?php echo URL; ?>"); 
                }, 6000);
            }
        };*/
        var onDownloadAs = function (event) {
            console.log("ONLYOFFICE Document Editor create file: " + event.data);
            docEditor.downloadUrl = event.data;
            // return event;
        };


        var сonnectEditor = function () {
            var user = {id:"<?php echo Ue::sessionUserId(); ?>","name":"<?php echo Ue::getSessionPersonName(); ?>"};
            type = new RegExp("android|avantgo|playbook|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino", "i").test(window.navigator.userAgent) ? "mobile" : "desktop";

            docEditor = new DocsAPI.DocEditor("iframeEditor",
                {
                    width: "100%",
                    height: "100%",
                    type: type,
                    documentType: "<?php echo $this->doctype; ?>", /*text, spreadsheet, presentation*/
                    document: {
                        title: fileName,
                        url: "<?php echo (defined('URL_OFFICE') ? URL_OFFICE : URL) . $this->fullname; ?>",
                        fileType: fileType,
                        key: "<?php echo $this->dockey; ?>",
                        info: {
                            author: "Me",
                            created: new Date().toJSON().replace(/^.*(\d\d)-(\d\d)-(\d\d).*$/, '$3.$2.$1'),
                        },
                        permissions: {
                            download: true,
                            edit: <?php echo $this->edit; ?>,
                            review: <?php echo $this->review; ?>
                        }
                    },
                    editorConfig: {
                        mode: '<?php echo $this->mode; ?>',
                        lang: "en",
                        callbackUrl: "<?php echo (defined('URL_OFFICE') ? URL_OFFICE : URL) . 'mdwidget/webeditorCallBack?type=track&fullName=' . urlencode($this->fullname) . '&userAddress=' . URL; ?>", 
                        user: user,
                        embedded: {
                            toolbarDocked: "top",
                        },
                        customization: {
                            about: true,
                            feedback: true
                        },
                    },
                    events: {
                        "onDownloadAs": onDownloadAs,
                        'onAppReady': onAppReady,
                        'onDocumentReady': onDocumentReady,
                        'onDocumentStateChange': onDocumentStateChange,
                        'onRequestEditRights': onRequestEditRights,
                        'onError': onError,
                        "onDocumentStateChange": onDocumentStateChange,
                        'onOutdatedVersion': onOutdatedVersion
                    }
                });
        };

        if (window.addEventListener) {
            window.addEventListener("load", сonnectEditor);
        } else if (window.attachEvent) {
            window.attachEvent("load", сonnectEditor);
        }

    </script>
</head>
<body>
    <form id="form1">
        <div id="iframeEditor">
        </div>
    </form>
</body>
</html>
