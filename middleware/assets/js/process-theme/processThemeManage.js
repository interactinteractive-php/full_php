/* global Core, themeSectionDetailData, tinymce */

var processThemeManage=function(){

  //<editor-fold defaultstate="collapsed" desc="variables">
  var $themeManageSection,
          $themeManageWrapper,
          metaThemeId,
          $metaThemeContent,
          $sidebar;
  //</editor-fold>

  //<editor-fold defaultstate="collapsed" desc="events">
  var initEvent=function(){
    metaThemeArray={},
            $themeManageSection=$("#themeManageSection"),
            $themeManageWrapper=$('#theme-manage-wrapper'),
            $metaThemeContent=$("#metaThemeContent"),
            $sidebar=$themeManageWrapper.find("#sidebar");

    $('html, body').animate({scrollTop: 0}, 0);

    initThemeChangeEvent();

    initDragStyleEvent();

    initStyleThumbView();

    initSidebarMenuEvent();
  };

  var initStyleThumbView=function(){
    $themeManageWrapper.find('.single-style').find('img.isLoadImg').click(function(){
      var dialogName='dialog-style-thumb';
      if(!$("#" + dialogName).length){
        $('<div id="' + dialogName + '" class="display-none"></div>').appendTo('body');
      }

      var $dialogName=$("#" + dialogName);

      $dialogName.html('<img style="margin: 0 auto;" class="img-fluid" src="' + $(this).attr('src') + '"/>');

      $dialogName.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: '11111',
        width: 1200,
        minWidth: 1200,
        height: 600,
        modal: false,
        open: function(){

        },
        close: function(){
          $dialogName.empty().dialog('destroy').remove();
        },
        buttons: [
          {text: 'Хаах', class: 'btn btn-sm blue-hoki', click: function(){
              $dialogName.dialog('close');
            }}
        ]
      }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": true,
        "collapsable": true,
        "dblclick": "maximize",
        "minimizeLocation": "left",
        "icons": {
          "close": "ui-icon-circle-close",
          "maximize": "ui-icon-extlink",
          "minimize": "ui-icon-minus",
          "collapse": "ui-icon-triangle-1-s",
          "restore": "ui-icon-newwin"
        }
      });
      $dialogName.dialog('open');
      $dialogName.dialogExtend("maximize");
    });
  };

  var initSidebarMenuEvent=function(){
    $themeManageWrapper.find('[rel="tooltip"]').tooltip({html: true});

    $sidebar.find('.mbcode_c_sidebar_button').click(function(){
      var $targetSidebartBtn=$(this);
      $sidebar.find('.sidebar-tabs').hide();
      $sidebar.find($targetSidebartBtn.data('target')).show();
      $sidebar.find('.mbcode_c_sidebar_button').closest('li').removeClass('active');
      $targetSidebartBtn.closest('li').addClass('active');
    });
  };

  //<editor-fold defaultstate="collapsed" desc="update mode">
  var fillUpdateMode=function(themeSectionData){
    metaThemeArray={};
    metaThemeArray[metaThemeId]={};

    $themeManageWrapper.find("#metaThemeId").select2('val', metaThemeId);

    $.each(themeSectionData, function(key, value){
        var $targetDroppableField=$metaThemeContent.find("*:contains('{{" + value.SECTION_NAME + "}}')").filter(function(){
            return $(this).children().length === 0;
        });

      getThemeStyleContent($targetDroppableField, value.FILE_NAME, value.STYLE_ID, function(){
        if(value.PARAM_PATH !== null){
          $targetDroppableField.prepend('<span class="section-groupname">' + value.PARAM_PATH + '</span>');
          if(typeof $targetDroppableField.attr('tmpSectionOrPosName') !== "undefined"){
            metaThemeArray[metaThemeId][$targetDroppableField.attr('tmpSectionOrPosName')]['paramPath']=value.PARAM_PATH;
          }
        }

        if(typeof themeSectionDetailData[value.ID] !== "undefined"){
          $.each(themeSectionDetailData[value.ID], function(index, themeSectionDetail){
            var $targetDroppablePosField=$targetDroppableField.find("*:contains('{{" +
                    themeSectionDetail.POSITION_NAME +
                    "}}')").filter(function(){
                      return $(this).children().length === 0;
                    });

            manageThemeSectionDetail($targetDroppablePosField, themeSectionDetail.PARAM_PATH, themeSectionDetail.PARAM_REAL_PATH);
          });
        }
      });
    });

    var foundin=$metaThemeContent.find("*:contains('{{section')")
            .filter(function(){
              return $(this).children().length === 0;
            });
    foundin.addClass('sectionsDroppable');

    initMetaDropEvent($metaThemeContent.find(".sectionsDroppable"), '.isStyle',
            function($targetDroppableField, $draggable){
              executeStyleDropEvent($targetDroppableField, $draggable);
            });
  };
  //</editor-fold>

  //<editor-fold defaultstate="collapsed" desc="theme солих event">
  var initThemeChangeEvent=function(){
    $themeManageWrapper.find("#metaThemeId").change(function(){
      metaThemeId=$(this).val();
      if(metaThemeId !== ''){
        var data={metaThemeId: metaThemeId};
        metaThemeArray={};
        metaThemeArray[metaThemeId]={};

        Core.blockUI();
        $.ajax({
          url: "mdtheme/getMetaThemeHtml",
          type: "POST",
          data: data,
          dataType: "JSON",
          success: function(response){
            if(typeof response.htmlContent !== "undefined"){
              setMetaThemeHtml(response.htmlContent);
            }
          },
          error: function(jqXHR, exception){
            Core.unblockUI();
          }
        }).complete(function(){
          Core.unblockUI();
        });
      } else {
        $metaThemeContent.html('');
      }
    });
  };

  var setMetaThemeHtml=function(htmlContent){
    $metaThemeContent.html(htmlContent);
    var foundin=$metaThemeContent.find("*:contains('{{section')")
            .filter(function(){
              return $(this).children().length === 0;
            });
    foundin.addClass('sectionsDroppable');

    initMetaDropEvent($metaThemeContent.find(".sectionsDroppable"), '.isStyle',
            function($targetDroppableField, $draggable){
              executeStyleDropEvent($targetDroppableField, $draggable);
            });
  };
  //</editor-fold>

  var getThemeStyleContent=function($targetDroppableField, filename, styleId, callback){
    Core.blockUI();
    $.ajax({
      url: "mdtheme/getThemeStyleContent",
      type: "POST",
      data: {
        fileName: filename
      },
      dataType: "JSON",
      success: function(response){
        if(typeof response.htmlContent !== "undefined"){
          Core.unblockUI();
          setThemeStyleContent($targetDroppableField, response, styleId, callback);
        }
      },
      error: function(jqXHR, exception){
        Core.unblockUI();
      }
    }).complete(function(){
      Core.unblockUI();
    });
  };

  var setThemeStyleContent=function($targetDroppableField, response, styleId, callback){
    var tmpSectionName;
    if(typeof $targetDroppableField.attr('tmpSectionOrPosName') !== "undefined"){
      tmpSectionName=$targetDroppableField.attr('tmpSectionOrPosName');
    } else {
      $targetDroppableField.find('.section-groupname').remove();
      tmpSectionName=$targetDroppableField.text().replace(/[{}\s]/g, '');
    }

    if(response.htmlContent !== ''){
      $targetDroppableField.html(response.htmlContent);
      var foundin=$metaThemeContent.find("*:contains('{{pos')")
              .filter(function(){
                return $(this).children().length === 0;
              });
      foundin.addClass('posDroppable');

      $targetDroppableField.attr('tmpSectionOrPosName', tmpSectionName).addClass('themeLinkSection');

      initRemoveSection($targetDroppableField);

      metaThemeArray[metaThemeId][tmpSectionName]={styleId: styleId};
      initMetaDropEvent($metaThemeContent.find(".posDroppable"), '.isMeta', function($targetDroppableField, $draggable){
        var $rowsBeginEnd=$targetDroppableField.parents('.themeLinkSection').find("*:contains('rowsbegin')");

        if(!$draggable.hasClass('isGroupChild')){
          manageThemeSectionDetail($targetDroppableField, $draggable.data('metacode'), $draggable.data('realpath'));
        } else {
          if($rowsBeginEnd.length > 0){
            manageThemeSectionDetail($targetDroppableField, $draggable.data('metacode'), $draggable.data('realpath'));
          } else {
            new PNotify({
              title: 'Error',
              text: 'Row төрөлтэй Style дээр Rows төрөлтэй мета тавих боломжгүй!',
              type: 'error',
              sticker: false
            });
          }
        }
      });

      if(typeof callback === "function"){
        callback();
      }
    }
  };

  var executeStyleDropEvent=function($targetDroppableField, $draggable){
    if ($draggable.hasClass('isGroup')) {
      $targetDroppableField.find('.section-groupname').remove();
      $targetDroppableField.prepend('<span class="section-groupname">' + $draggable.data('groupname') + '</span>');
      
      /*console.log('theme link');
      console.log((typeof $targetDroppableField.attr('tmpSectionOrPosName') !== "undefined"));
      console.log($draggable.data('metacode'));*/
      
      if (typeof $targetDroppableField.attr('tmpSectionOrPosName') !== "undefined") {
         metaThemeArray[metaThemeId][$targetDroppableField.attr('tmpSectionOrPosName')]['paramPath']=$draggable.data('metacode');
      } else {
          var sectionName = $targetDroppableField.text();
          var matches = sectionName.match(/\{{(.*?)\}}/);

          if (matches) {
            sectionName = matches[1];
            $targetDroppableField.attr('tmpSectionOrPosName', sectionName); 
            var paramPathObj = {styleId: '25', paramPath: $draggable.data('metacode')};
            metaThemeArray[metaThemeId][sectionName]=paramPathObj; 
          }
      }
    } else {
      getThemeStyleContent($targetDroppableField, $draggable.data('filename'), $draggable.data('styleid'));
    }

    $('.sectionsDroppableBordered').removeClass('sectionsDroppableBordered');
//        $targetDroppableField.addClass('sectionsDroppableBordered');
  };

  var manageThemeSectionDetail=function($targetDroppableField, metacode, realpath){
    var tmpPosName;
    if(typeof $targetDroppableField.attr('tmpSectionOrPosName') !== "undefined"){
      tmpPosName=$targetDroppableField.attr('tmpSectionOrPosName');
    } else {
      tmpPosName=$targetDroppableField.text().replace(/[{}\s]/g, '');
    }

    $targetDroppableField.attr('tmpSectionOrPosName', tmpPosName).addClass('metaThemeSectionDetail');

    var sectionName=$targetDroppableField.parents('.themeLinkSection').attr('tmpSectionOrPosName');
    if(typeof metaThemeArray[metaThemeId][sectionName] !== "undefined"){
      if(typeof metaThemeArray[metaThemeId][sectionName]['sectionDetail'] === "undefined"){
        metaThemeArray[metaThemeId][sectionName]['sectionDetail']={};
      }

      metaThemeArray[metaThemeId][sectionName]['sectionDetail'][tmpPosName]={
        paramPath: metacode,
        paramRealPath: realpath
      };
    }

    $targetDroppableField.html(realpath);
    initRemovePos($targetDroppableField);
  };

  var initRemovePos=function($targetDroppableField){
    $targetDroppableField.addClass('initContextMenuEl');
    $.contextMenu({
      selector: ".initContextMenuEl",
      callback: function(key, opt){
        var $targetParent=opt.$trigger;
        if(key === 'delete'){
          var tmmRemoveSectioOrPosName=$targetParent.attr('tmpSectionOrPosName');
          if(typeof metaThemeArray[metaThemeId][$targetParent.parents('.themeLinkSection').
                  attr('tmpSectionOrPosName')] !== "undefined"){
            if(typeof metaThemeArray[metaThemeId][$targetParent.parents('.themeLinkSection').attr(
                    'tmpSectionOrPosName')]['sectionDetail'][tmmRemoveSectioOrPosName] !== "undefined"){
              delete  metaThemeArray[metaThemeId][$targetParent.parents('.themeLinkSection').attr(
                      'tmpSectionOrPosName')]['sectionDetail'][tmmRemoveSectioOrPosName];
            }
          }

          $targetParent.html('{{' + tmmRemoveSectioOrPosName + '}}');
        }
      },
      items: {
        "delete": {name: "Устгах", icon: "trash"}
      }
    });
  };

  var initRemoveSection=function($targetDroppableField){
    $targetDroppableField.prepend('<i class="fa fa-times removeThemeContentBtn"></i>');

    $('.removeThemeContentBtn').off().on('click', function(){
      var $targetParent=$(this).parent();
      if(typeof metaThemeArray[metaThemeId][$targetParent.attr('tmpSectionOrPosName')] !== "undefined"){
        delete metaThemeArray[metaThemeId][$targetParent.attr('tmpSectionOrPosName')];
      }
      $targetParent.html('{{' + $targetParent.attr('tmpSectionOrPosName') + '}}');
    });

    $(".sectionsDroppable").hover(function(){
      $(this).find('.removeThemeContentBtn').show();
    }, function(){
      $('.removeThemeContentBtn').hide();
    }
    );
  };

  var initMetaDropEvent=function($droppableTarget, acceptableClass, callback){
    $droppableTarget.droppable({
      hoverClass: "ui-state-hover",
      activeClass: "ui-state-active",
      accept: acceptableClass,
      drop: function(event, ui){
        if(typeof callback === "function"){
          callback($(this), ui.draggable);
        }
      }
    });
  };

  //<editor-fold defaultstate="collapsed" desc="drag&drop">
  var initDragStyleEvent=function(){
    $themeManageWrapper.find(".draggableMeta").draggable({
      scroll: false,
      helper: 'clone',
      revert: 'invalid',
      appendTo: '#dialog-manage-theme',
      drag: function(event, ui){
        if(ui.helper.hasClass('isMeta')){
          ui.helper.addClass('draggingMeta');
        }
        ui.helper.html(ui.helper.attr('title'));
      }
    });
  };
  //</editor-fold>

  //</editor-fold>

  return {
    init: function(){
      initEvent();
    },
    fillUpdateMode: function(themeId, themeSectionData){
      metaThemeId=themeId;
      fillUpdateMode(themeSectionData);
    }
  };
}();