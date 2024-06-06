<div class="" id="windowid-doc-edit-comment-<?php echo $this->uniqid; ?>">
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'doc-commentsteps-form', 'method' => 'post')); ?> 
<div class="dvecommerce">
    <div class="center-sidebar col-md-12">
        <div class="row">
            <div class="col-md-2 leftsidebar">
                <div style="margin-bottom: 20px;background: #f2f2f2;margin-left: -15px;margin-right: -15px;padding-left: 15px;margin-top: -20px;padding-top: 10px;padding-bottom: 5px;" class="panel-group accordion chatvideo">
                        <div class="panel panel-default">
                                <div class="general-item-list">
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img style="width: 50px;height: 50px;" class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1454204043182617_1450680227559_1446818761697.jpg">
                                                <span class="item-label3">Үүсгэгч</span><br>
                                                <a style="font-weight: bold;" href="javascript:void(0);" class="item-name primary-link">Д.Тунгалагтамир</a>
                                                <span class="item-label">ЧПМ ХХК</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img style="width: 50px;height: 50px;" class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1523432522510230_1493021561488321.jpg">
                                                <span class="item-label3">Эзэмшигч</span><br>
                                                <a style="font-weight: bold;" href="javascript:void(0);" class="item-name primary-link">А.Ганхуяг</a>
                                                <span class="item-label">Ариун ХХК</span>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div style="margin-top: 10px;">
                                        <p><i style="color: #ff3768;" class="fa fa-envelope"></i> tungalagtamir@gmail.com</p>
                                        <p><i style="color: #ff3768;" class="fa fa-phone"></i> 9911-4589</p>
                                    </div>-->
                                </div>
                        </div>
                    </div>
                <!--<div class="hrborder"></div>-->
                <div style="padding-bottom: 10px;" class="undsenmedeelel">
                    <div class="dvecommercetitle">
                        <h3><i class='icon-info22'></i> Үндсэн мэдээлэл</h3>
                    </div>
                        <div class="undsenbox">
                            <p>Товч утга</p>
                            <input type="text" name="tovchutga" id="tovchutga" placeholder="">
                        </div>
                        <div class="undsenbox">
                            <p>Цахим эсэх</p>
                            <select>
                                <option value="0">Тийм</option>
                                <option value="1">Үгүй</option>
                            </select> 
                        </div>
                        <div class="undsenbox">
                            <p>Илгээгч</p>
                            <select>
                                <option value="0">Бичиг хэргийн ажилтан 1</option>
                                <option value="1">Бичиг хэргийн ажилтан 2</option>
                                <option value="2">Бичиг хэргийн ажилтан 3</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Түгжээтэй эсэх</p>
                            <select>
                                <option value="0">Хэвлэх</option>
                                <option value="1">Архивлах</option>
                                <option value="2">Шилжүүлэх</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Нууцлал</p>
                            <select>
                                <option value="0">Энгийн</option>
                                <option value="1">Гарт</option>
                                <option value="2">Нууцлалтай</option>
                                <option value="3">Маш нууц</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Хаана хандсан</p>
                            <select>
                                <option value="0">Сонголт 1</option>
                                <option value="1">Сонголт 2</option>
                                <option value="2">Сонголт 3</option>
                                <option value="3">Сонголт 4</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Хэнд хандсан</p>
                            <select>
                                <option value="0">Сонголт 1</option>
                                <option value="1">Сонголт 2</option>
                                <option value="2">Сонголт 3</option>
                                <option value="3">Сонголт 4</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Хүлээн авагч</p>
                            <select>
                                <option value="0">Бичиг хэргийн ажилтан 1</option>
                                <option value="1">Бичиг хэргийн ажилтан 2</option>
                                <option value="2">Бичиг хэргийн ажилтан 3</option>
                                <option value="3">Бичиг хэргийн ажилтан 4</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Хариутай эсэх</p>
                            <select>
                                <option value="0">Хариугүй</option>
                                <option value="0">Энгийн хариутай</option>
                                <option value="0">Яаралтай</option>
                                <option value="0">Хариутай</option>
                            </select>
                        </div>
                        <div class="undsenbox">
                            <p>Хариу өгөх огноо</p>
                            <input type="date" name="hariuognoo" id="hariuognoo">
                        </div>
                </div>
                <!--<div style="margin-top: 20px;" class="undsenmedeelel">
                    <div class="dvecommercetitle">
                        <h3><i class='fa icon-pin'></i> Удирдлагын заалт</h3>
                    </div>
                        <div class="undsenbox">
                            <p>Карт хаасан</p>
                            <h6>Х.Энхчимэг</h6>
                        </div>
                        <div class="undsenbox">
                            <p>Удирдлагын заалт</p>
                            <h6>Яаралтай шийдвэрлэх шаардлагатай</h6>
                        </div>
                </div>-->
            </div>
            <div style="margin-top: 30px;" class="right-sidebar-content-for-resize col-md-8">            
                <span class="doc-lazy-load"><img src="<?php echo URL; ?>assets/core/global/img/input-spinner.gif" alt="Loading"> Түр хүлээнэ үү...</span>                                
                <div id="tiny-ui">
                    <textarea class="doc-comment-editor" style="width: 100%; display: none;">
                        <?php echo $this->getFileInfo['paragraphtext']; ?>
                    </textarea>
                </div>                    
            </div>
            <div class="col-md-2 rightsidebar">
                <div style="position: relative;
    z-index: 999999;
    background: #f2f2f2;
    top: -20px;
    margin-left: -15px;
    padding-left: 15px;
    margin-right: -15px;
    padding-right: 15px;
    padding-top: 10px;margin-bottom: -15px;" class="dvecommercetitle">
                        <h3><i class="fa icon-layers"></i> Үйлдэл</h3>
                    </div>
                    <div class="btn-group btn-group-devided pr4" data-deleteprocess="[{&quot;id&quot;:&quot;1547623325611062&quot;,&quot;processId&quot;:&quot;1477644404204&quot;,&quot;processName&quot;:&quot;\u0423\u0441\u0442\u0433\u0430\u0445&quot;,&quot;icon&quot;:&quot;icon-trash&quot;,&quot;criteria&quot;:&quot;&quot;}]"><!--startbutton--><a class="btn btn-warning btn-circle btn-sm" title="" data-advanced-criteria="" data-actiontype="insert" data-dvbtn-processcode="DOC_OUTGOING_LIST_ADD_1_TST" href="javascript:;" onclick="saveDocParagraph(this, '<?php echo $this->uniqid; ?>')"><i class="fa icon-doc" style="color:#df21bb"></i> Хадгалах</a><a class="btn btn-secondary btn-circle btn-sm" title="Ирсэн албан бичиг засах" data-advanced-criteria="" onclick="transferProcessAction('', '21544118232552', '1547621996164', '200101010000011', 'toolbar', this, {callerType: 'DP_DV_LIST'}, undefined, undefined, undefined, undefined, '');" data-actiontype="update" data-dvbtn-processcode="DOC_OUTGOING_LIST_EDIT_1_TST" href="javascript:;"><i class="icon-info22" style="color:#2562ec"></i> Илгээх</a><a class="btn btn-secondary btn-circle btn-sm" title="Ирсэн албан бичиг устгах" data-advanced-criteria="" onclick="transferProcessAction('', '21544118232552', '1477644404204', '200101010000011', 'toolbar', this, {callerType: 'DP_DV_LIST'}, undefined, undefined, undefined, undefined, '');" data-actiontype="delete" data-dvbtn-processcode="DOC_DOCUMENT_DV5_005" href="javascript:;"><i class="fa icon-trash" style="color:#ee1212"></i> Хянаж зөвшөөрөх</a><a class="btn btn-secondary btn-circle btn-sm" title="" data-advanced-criteria="" onclick="htmltopdfDocDocument();" data-dvbtn-processcode="D20P_WS_001" href="javascript:;"><i class="fa icon-notebook" style="color:#4b6af1"></i> Батлах</a>                                
                    <div class="btn btn-sm btn-group workflow-btn-group-21544118232552">
                </div>
            </div>
            <div style="border-bottom: 1px solid #CCC;padding-bottom: 10px;" class="panel-group accordion chatvideo">
                        <div class="panel panel-default">
                                <div class="general-item-list">
                                	<div class="dvecommercetitle">
    				                    <h3><i class='fa icon-loop'></i> Төлөвлөх явц <!--<i style="color: #ff3768;float: right;font-size: 15px;margin-top: 8px;" class="icon-plus3 font-size-12"></i>--></h3>
    				                </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div style="margin-bottom: 5px;" class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1454204043182617_1450680227559_1446818761697.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">Б.Батдорж</a>
                                                <span class="item-label">Даргын туслах</span>
                                            </div>
                                            <div>
                                                <font style="color: #28d094;font-weight: 600;">Үүсгэсэн</font> <font style="float: right;">2019.01.20</font>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div style="margin-bottom: 5px;" class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1479814255292215_1446818761697.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">А.Тэмүүлэн</a>
                                                <span class="item-label">Хэлтсийн дарга</span>
                                            </div>
                                            <div>
                                                <font style="color: #ffc205;font-weight: 600;">Илгээсэн</font> <font style="float: right;">2019.01.20</font>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div style="margin-bottom: 5px;" class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1479814255292215_1446818761697.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">Б.Ууганбаяр</a>
                                                <span class="item-label">Ерөнхий захирал</span>
                                            </div>
                                            <div>
                                                <font style="color: #CC0000;font-weight: 600;">Хянан баталсан</font> <font style="float: right;">2019.01.20</font>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                <div style="padding-bottom: 10px;" class="panel-group accordion chatvideo">
                        <div class="panel panel-default">
                                <div class="general-item-list">
                                	<div class="dvecommercetitle">
    				                    <h3 style="line-height: 16px;"><i class='fa icon-users'></i> Харж байгаа хэрэглэгчид <!--<i style="color: #ff3768;float: right;font-size: 15px;margin-top: 8px;" class="icon-plus3 font-size-12"></i>--></h3>
    				                </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1454204043182617_1450680227559_1446818761697.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">Б.Батдорж</a>
                                                <span class="item-label">Даргын туслах</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1479814255292215_1446818761697.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">А.Тэмүүлэн</a>
                                                <span class="item-label">Хэлтсийн дарга</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1542074390627821_14930215614883211.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">Э.Дэлгэрмөрөн</a>
                                                <span class="item-label">Хууль, Эрх зүй</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1523432522510230_1493021561488321.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">Б.Батдорж</a>
                                                <span class="item-label">Тогтоол шийдвэр</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-head">
                                            <div class="item-details">
                                                <img class="item-pic" src="https://vr.veritech.mn/storage/uploads/process/file_1454272125522063_1450680227559_1446818761697.jpg">
                                                <a href="javascript:void(0);" class="item-name primary-link">А.Тэмүүлэн</a>
                                                <span class="item-label">Ерөнхий нягтлан</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <!--<div class="panel-group accordion chatvideo">
                        <div class="panel panel-default">
                                <div class="general-item-list">
                                	<div class="dvecommercetitle">
    				                    <h3><i style="font-size: 18px;" class='icon-search4'></i> Түргэн хайлт</h3>
    				                </div>
    								<a href="javascript:;" style="width:100%;border: 1px solid #ff3768;margin-bottom: 10px;" class="btn btn-circle btn-lg tab-criteria-value">
    									<div style="color: #ff3768;text-transform: none;font-weight: normal;" class="greenbtntext">Ерөнхий мэдээлэл</div>
    								</a>
    								<a href="javascript:;" style="width:100%;border: 1px solid #666ee8;margin-bottom: 10px;" class="btn btn-circle btn-lg tab-criteria-value">
    									<div style="color: #666ee8;text-transform: none;font-weight: normal;" class="greenbtntext">Хавсралт</div>
    								</a>
    								<a href="javascript:;" style="width:100%;border: 1px solid #28d094;margin-bottom: 10px;" class="btn btn-circle btn-lg tab-criteria-value">
    									<div style="color: #28d094;text-transform: none;font-weight: normal;" class="greenbtntext">Холбоотой бичиг</div>
    								</a>
    								<a href="javascript:;" style="width:100%;border: 1px solid #1e9ff2;margin-bottom: 10px;" class="btn btn-circle btn-lg tab-criteria-value">
    									<div style="color: #1e9ff2;text-transform: none;font-weight: normal;" class="greenbtntext">Тэмдэглэл</div>
    								</a>
    								<a href="javascript:;" style="width:100%;border: 1px solid #9b25af;margin-bottom: 40px;" class="btn btn-circle btn-lg tab-criteria-value">
    									<div style="color: #9b25af;text-transform: none;font-weight: normal;" class="greenbtntext">Хяналтын карт</div>
    								</a>
    				            </div>
    				    </div>
    				</div>-->
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>
</div>

<style type="text/css">
    .input-container input {
        border: none;
        box-sizing: border-box;
        outline: 0;
        padding: .75rem;
        position: relative;
        width: 100%;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        background: transparent;
        bottom: 0;
        color: transparent;
        cursor: pointer;
        height: auto;
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: auto;
    }
</style>

<style type="text/css">
	body .mce-edit-aria-container>.mce-container-body .mce-sidebar-panel {
	  width: 30%;
	  max-width: 30%;
	}
    .mce-annotations-sidebar .mce-annotations-container .mce-conversation .mce-conversation-scroll-container .mce-annotations-comment .mce-annotations-comment-user {
        padding-bottom: 0;
    }   
</style>

<script type="text/javascript">
    var $windowIdComment = $("#windowid-doc-edit-comment-<?php echo $this->uniqid; ?>");
    
    $(function(){
        $windowIdComment.find('.doc-comment-editor').css('height', $(window).height() - 300);

        $.getScript("https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=<?php echo $this->tinymceApiKey; ?>").done(function() {
            tinymce.init({
                branding: false,
                selector: '#tiny-ui .doc-comment-editor',
                theme: 'modern',
                plugins: 'tinycomments print preview fullpage powerpaste searchreplace autolink directionality advcode visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools mediaembed  linkchecker contextmenu colorpicker textpattern help',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat  | tinycomments',
                content_css: [
                    '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                    '//www.tinymce.com/css/codepen.min.css'
                ],
                content_style: '.mce-annotation { background: #fff0b7; } .tc-active-annotation {background: #ffe168; color: black; }',
                tinycomments_create: create,
                tinycomments_reply: reply,
                tinycomments_delete: del,
                tinycomments_lookup: lookup
            });        
            $windowIdComment.find('.doc-lazy-load').remove();
            $windowIdComment.find('.doc-comment-editor').show();
        });
    });

    function create(content, done, fail) {
        if (content == '' || content == null) {
            return;
        }

        fetch(
            'mddoc/docCommentCreate',
        { method: 'POST', body: content }
        ).then(function(response) {
            return response.json();
        }).then(function(json) {
            done(json.uid);
            updateDocument(json.uid);

            if (json.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: json.status,
                    text: json.message,
                    type: json.status,
                    sticker: false
                });                
            }

        }).catch(function() {
            fail(new Error('Something has gone wrong...'));
        });
    }

    function reply(uid, content, done, fail) {
        if (content == '' || content == null) {
            return;
        }

        fetch(
            'mddoc/docCommentReply',
        { method: 'POST', body: JSON.stringify({body: content, uid: uid })}
        ).then(function(response) {
            return response.json();
        }).then(function(json) {
            done(json.uid);
            // updateDocument(json.uid);

            if (json.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: json.status,
                    text: json.message,
                    type: json.status,
                    sticker: false
                });                
            }

        }).catch(function() {
            fail(new Error('Something has gone wrong...'));
        });
    }

    function del(uid, done, fail) {
        return;
        fetch(
        'http://192.168.100.114/tinymce/removeConversations/'+uid,
        { method: 'DELETE' }
        ).then(function(response) {
        if (response.ok) {
            updateDocument();
            done(true);
        } else if (response.status == 403) {
            done(false)
        } else {
            fail(new Error('Something has gone wrong...'));
        }
        });
    }

   function lookup(uid, done, fail) { 
        setTimeout(function () {
            fetch('mddoc/getDocComments/'+uid)
            .then(function(response) { 
                return response.json(); 
            })
            // .then(function(json) {
            //     var conversation = json.getcommentfromuniquedv;

            //     return Object.keys(conversation).map(function(key, index) {
            //         return {
            //             author: conversation[key].username,
            //             content: conversation[key].commenttext
            //         };
            //     });
            // })
            .then(function(comments) {
                done({ comments: '' });
                if (!$windowIdComment.find('button#mceu_41-button').parent().hasClass('mce-active')) {
                    $windowIdComment.find('button#mceu_41-button').click();
                }

                setTimeout(function () {
                    if ($windowIdComment.find('.mce-annotations-container').find('.mce-conversation-scroll-container').length) {
                        var htmlStr = '', pl;

                        for (var i = 0; i < comments.getcommentfromuniquedv.length; i++) {
                            pl = '';
                            if (comments.getcommentfromuniquedv[i].isreply === '1') {
                                pl = 'padding-left: 36px;'
                            }
                            htmlStr += '<div class="mce-annotations-comment" style="' + pl + '">'+
                                '<section class="mce-annotations-comment-user">'+
                                    '<div class="mce-annotations-comment-user-avatar mce-i-user mce-i-ico"></div>'+
                                    '<div class="mce-annotations-comment-username mce-label">'+
                                        '<h1>' + comments.getcommentfromuniquedv[i].username + '</h1>'+
                                    '</div>'+
                                '</section>'+
                                '<section class="mce-annotations-comment-content">' + comments.getcommentfromuniquedv[i].commenttext + '</section>'+
                            '</div>';
                        }

                        $windowIdComment.find('.mce-annotations-container').find('.mce-conversation-scroll-container').empty().append(htmlStr);
                    }
                }, 100);
            })
            .catch(function(err) {
                console.log(err)
            })    
        }, 300);    
    };

    function updateDocument(id) {
        setTimeout(function () {
            var content = tinymce.activeEditor.getContent();

            $.ajax({
                type: "POST",
                url: 'mddoc/docParagraphChildCreate/' + id,
                data: { content: content },
                dataType: 'json',
                success: function (data) {
                    if (data.status === 'error') {
                        PNotify.removeAll();
                        new PNotify({
                            title: data.status,
                            text: data.message,
                            type: data.status,
                            sticker: false
                        });                
                    }                    
                }
            });
        }, 100);
    }    

    function htmltopdfDocDocument() {
        var content = tinymce.activeEditor.getContent();

        $.ajax({
            type: "POST",
            url: 'mddoc/htmltopdfDocDocument',
            data: { content: content },
            dataType: 'json',
            beforeSend: function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },             
            success: function (data) {
                if (data.status === 'error') {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });                

                } else {

                    var filePath = data.filePath;
                    $.ajax({
                        type: 'post',
                        url: 'mdpki/getInformationForDocumentSign',
                        data: {filePath: filePath},
                        dataType: 'json',
                        success: function(data){
                            if (data.status === 'success') {
                                signPdfAndTextRun(data, filePath, null, function(data){
                                    if (data.status === 'success') {
                                        var $dialogName = 'dialog-documentviewer-'+getUniqueId(1);
                                        var windowHeight = $(window).height(), fileList = '';
                                        var pathStr = '<?php echo URL . UPLOADPATH . "signedDocument/"; ?>', dataViewId = '';

                                        window.open(pathStr+data.filename, '_blank');
                                        return;
                                        
                                        fileList += '<div class="popuphavsralt"><a href="javascript:void(0);" onclick="documentSingleViewer(this, \''+$dialogName+'\' ,\''+dataViewId+'\' ,\'\', \''+pathStr+data.filename+'\')"><img src="storage/uploads/process/file_1545391700200271_15452821957441.png"><h5>' + data.filename + '</h5></a></div>';

                                        if (fileList === '') {
                                            PNotify.removeAll();
                                            new PNotify({
                                                title: 'Сануулга',
                                                text: 'Файл олдсонгүй.',
                                                type: 'info',
                                                sticker: false
                                            });
                                            return;
                                        }

                                        $('<div class="modal pl0 fade modal-after-save-close" id="'+ $dialogName +'" tabindex="-1" role="dialog" aria-hidden="true">'+'<div class="modal_class1"></div>'+
                                            '<div class="modal-dialog" style="width:900px;margin-top:50px;">'+
                                                '<div class="modal-content">'+
                                            '<div class="modal-header" style="display:none;height:35px;padding-top: 6px;">'+
                                                '<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>'+
                                                '<h4 class="modal-title">Document Viewer</h4>'+
                                            '</div>'+
                                            '<div style="padding: 0;" class="modal-body"></div>'+
                                            '<div class="modal-footer">'+
                                                '<button type="button" data-dismiss="modal" class="btn blue-hoki btn-sm">' + plang.get('close_btn') + '</button>'+
                                            '</div></div><div class="popuprightsidebar">' + fileList + '</div>').appendTo('body');

                                        var $dialog = $('#' + $dialogName);

                                        $dialog.modal();
                                        $dialog.on('shown.bs.modal', function () {
                                            $dialog.find(' > .modal-dialog > .popuprightsidebar > .popuphavsralt > a:first').click();
                                            disableScrolling();
                                        });            
                                        $dialog.on('hidden.bs.modal', function () {
                                            $dialog.remove();
                                            enableScrolling();
                                        });                                       
                                    }
                                });
                            }
                        },
                        error: function(){
                            
                        }
                    });                    
                }                    
                Core.unblockUI();
            }
        });
    }     
</script>