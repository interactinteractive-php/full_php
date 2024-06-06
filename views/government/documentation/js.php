<script>
    $.getScript(URL_APP + 'assets/custom/addon/plugins/pdfobject/pdfobject.min.js', function () {});
    var firstdocumentclicked = 0;
    $('.intranet_<?php echo $this->uniqId ?> ul.nav.nav-sidebar.soyombo-fil > li:eq(0) > a').trigger('click');
    
    
    function getCategory_<?php echo $this->uniqId ?>(id) {
        $.ajax({
            type: 'post',
            url: 'government/documentationContent',
            data: {
                id: id,
                uniqId: '<?php echo $this->uniqId ?>'
            },
            dataType: 'json',
            beforeSend: function () {
                blockContent_<?php echo $this->uniqId ?> ('.sidebar-content_<?php echo $this->uniqId ?>');
            },
            success: function (data) {
                $("#content_<?php echo $this->uniqId ?>").empty().append(data.Html).promise().done(function () {
                    if(firstdocumentclicked === 0) {
                        firstdocumentclicked = 1;
                        $('ul#content_<?php echo $this->uniqId ?> > li:eq(0) > a').trigger('click');
                    } 
                    Core.unblockUI('.sidebar-content_<?php echo $this->uniqId ?>');
                });
            },
            error: function () {
                alert('Error');
                Core.unblockUI('.sidebar-content_<?php echo $this->uniqId ?>');
            }
        });
    }
    
    function getContentDetail<?php echo $this->uniqId ?>(id, file, video) {
        
        $('#play').attr('data-target','#video'+id);
        
        if(video.indexOf("mp4") > 0) {
            var html = '<video width="100%" height="100%" controls>'+
                                    '<source src="'+video+'" type="video/mp4">'+
                                'Your browser does not support the video tag.'+
                            '</video>';
            $("#file_viewer_<?php echo $this->uniqId ?>").empty().append(html);
        } else {
            if (file !== '') {
                $("#file_viewer_<?php echo $this->uniqId ?>").empty().append('<iframe src="<?php echo URL ?>api/pdf/web/viewer.html?file=<?php echo URL ?>'+ file +'" frameborder="0" style="width: 100%;height: 80vh;"></iframe>');
            } else {
                alert('Файл хавсаргаагүй байна');
            }
        }
    
    }
    
    function blockContent_<?php echo $this->uniqId ?> (mainSelector) {
        $(mainSelector).block({
            message: '<i class="icon-spinner4 spinner"></i>',
            centerX: 0,
            centerY: 0,
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                width: 16,
                top: '15px',
                left: '',
                right: '15px',
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        }); 
    }
    
    /*
    * 
    * 

    function fileview_1572602620612102(element, $contentId) {
        var $this = $(element);
        var $mainSelector = $this.closest('div#filelibrarybody');
        var $cpContent = $mainSelector.find('.fileviewcontent_' + $contentId).html();

        $mainSelector.find('.fileviewer').empty().append($mainSelector.find('.fileviewcontent_' + $contentId).html());
    }

     */
</script>