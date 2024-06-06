/* global Core, fb, cellArray, layoutId, cellArrayFromRender, PNotify */

var contentUi = function() {

  var $colorPicker,
          $uiSelectTable,
          $tmpVal,
          $contentUi,
          $contentBg,
          data=new FormData(),
          $contentSelectable,
          $colorPickerModal,
          $rowCount,
          $colCount,
          cellArray={},
          tmpMergeId=1,
          isUpdate=false,
          removedMetaDataList={};

  var content_initEvent=function() {
//    setTimeout(function() {
//      $(".fullscreen").trigger('click');
//    }, 3000);
    $contentUi=$("#contentUi");
    $colorPickerModal=$("#colorPickerModal");
    $rowCount = $("#rowCount");
    $colCount = $("#colCount");
    content_initColorPickerEvent();
    content_initChangeBorderWidthEvent();
    content_initChangeImgInputEvent();
    content_initChangeBordersInputEvent();
    content_initChangeCaptionInputEvent();
    initDecimalAdjust();
    initColorEvent($contentUi.find("#bgColorSpectrum"), $contentUi.find("#bg-color"));
    initColorEvent($contentUi.find("#borderColorSpectrum"), $contentUi.find("#border-color"));
    initColorEvent($contentUi.find("#cellBgColorSpectrum"), $contentUi.find("#background-color"));
    $("#contentUiForm").validate({errorPlacement: function(){
      }});

    if(layoutId !== 0){
      isUpdate=true;
      $contentBg=$('.content-bg_' + layoutId);
      $contentSelectable=$(".layout_" + layoutId);
      initLayoutProperties($contentSelectable);
      cellArray=cellArrayFromRender;
      setMetaDataContextMenu();
      $(".isInsert").hide();
    }
    showRenderSidebar();
    initMetaAutoComplete();

    $colCount.keydown(function(e) {
      if($("#rowCount").val().length > 0 && $(this).val().length > 0 && e.which === 13) {
        drawLayout();
      }
    });
    $colCount.change(function() {
      if($("#rowCount").val().length > 0 && $(this).val().length > 0) {
        drawLayout();
      }
    });
    $('#exitsTableRender').click(function() {
      $rowCount.val('');
      $colCount.val('');
      $('#content-container').empty();
    });
    initMetaDraggableEvent();
  };

  var initMetaAutoComplete=function(){
    $("#metaList").autocomplete({
      source: function(request, response){
        $.ajax({
          url: "mdcontentui/getMetaDataListByName/",
          type: "POST",
          dataType: "json",
          data: {metaName: request.term},
          success: function(data){
            if(data.length > 0){
              response($.map(data, function(value){
                return {
                  label: value.META_DATA_CODE + " - " + value.META_DATA_NAME,
                  value: value.META_DATA_ID
                };
              }));
            } else {
              response([{label: 'Хайлт байхгүй...'}]);
            }
          },
          error: function(){
          }
        }).complete(function(){
        });
      },
      minLength: 2,
      select: function(event, ui){
        $("#metaDataList").append('<div class="draggable ui-draggable ui-draggable-handle" ' +
                'id="' + ui.item.value + '" style="position: relative;">' + ui.item.label +
                '</div>');
        $(this).val('');
        initMetaDraggableEvent();
        event.preventDefault();
      },
      open: function(){
        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
      },
      close: function(){
        $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
      }
    });
  };

  var initMetaDraggableEvent=function(){
    $(".draggable").draggable({
      cursor: "move",
      revert: "invalid",
      start: function(){
        if($('td.ui-selected').length === 0){
          alert("Нүд сонгоно уу");
        }
      }
    });
  };

  var initColorEvent=function(target, el){
    target.spectrum({
      allowEmpty: true,
      color: "#ECC",
      showInput: true,
      containerClassName: "full-spectrum",
      showInitial: true,
      showPalette: true,
      showSelectionPalette: true,
      showAlpha: true,
      maxPaletteSize: 10,
      preferredFormat: "hex",
      localStorageKey: "spectrum.demo",
      cancelText: "Сонгох",
      chooseText: "Хаах",
      move: function(color){
        chooseColor(el, color);
      },
      show: function(){

      },
      beforeShow: function(){

      },
      hide: function(color){
        chooseColor(el, color);
      },
      palette: [
        ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
          /*"rgb(153, 153, 153)","rgb(183, 183, 183)",*/
          "rgb(204, 204, 204)",
          "rgb(217, 217, 217)", /*"rgb(239, 239, 239)", "rgb(243, 243, 243)",*/
          "rgb(255, 255, 255)"],
        ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)",
          "rgb(0, 255, 0)",
          "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)",
          "rgb(255, 0, 255)"],
        ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)",
          "rgb(217, 234, 211)",
          "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)",
          "rgb(234, 209, 220)",
          "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)",
          "rgb(182, 215, 168)",
          "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)",
          "rgb(213, 166, 189)",
          "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)",
          "rgb(147, 196, 125)",
          "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)",
          "rgb(194, 123, 160)",
          "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)",
          "rgb(106, 168, 79)",
          "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)",
          "rgb(166, 77, 121)",
          /*"rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
           "rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",*/
          "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)",
          "rgb(39, 78, 19)",
          "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)",
          "rgb(76, 17, 48)"]
      ]
    });
  };

  var drawLayout=function() {
    cellArray = {};
    var rowCount = $rowCount.val();
    var colCount = $colCount.val();
    var html =  '<div class="content-bg">' 
              + '<table id="selectable" style="border-spacing: 1px; border-collapse: separate;">';

    if (rowCount > 0) {
      var counter = 1;
      for (var rowItem = 0; rowItem < rowCount; rowItem++) {
        html += '<tr class="" id="r' + rowItem + '">';

        var tmpRowLine = {};
        for(var colItem=0; colItem < colCount; colItem++) {
          var tmpRow = {};
          tmpRow['name']    = 'c' + colItem;
          tmpRow['is_merge']= 0;
          tmpRow['is_use']  = 1;
          tmpRowLine['c' + colItem] = tmpRow;

          html +=   '<td class="ui-state-default r' + rowItem + ' c' + colItem + '" id="r' + rowItem + '_c' + colItem + '" style="">'
                  + '<div class="counter">' + counter +'</div>'
                  + '<div class="cell-width-height">'
                    + '<span class="cell-width-text"></span>'
                    + '<span class="cell-height-text"></span>'
                  + '</div>'
                  '</td>';
          counter++;
        }

        cellArray['r' + rowItem] = tmpRowLine;
        html += '</tr>';
      }

      html += '</table>'
          + '</div>';
    }
    
    // draw table
    $('#content-container').empty().html(html);
    
    $contentBg          = $('.content-bg');
    $contentSelectable  = $("#selectable");
    initLayoutProperties($contentSelectable);
    
//    $('#createBtn').remove();
  };

  var showRenderSidebar=function(){
    $(".stoggler").on("click", function(){
      var _thisToggler=$(this);
      var centersidebar=$(".center-sidebar");
      var rightsidebar=$(".right-sidebar");
      var rightsidebarstatus=rightsidebar.attr("data-status");
      if(rightsidebarstatus === "closed"){
        if(typeof $contentBg !== "undefined"){
          $contentBg.css('overflow', 'auto');
        }
        centersidebar.removeClass("col-md-12").addClass("col-md-9");
        rightsidebar.addClass("col-md-3").css("margin-top: 18px;");
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".glyphicon-chevron-left").hide();
        rightsidebar.find(".right-sidebar-content").show();
        rightsidebar.find(".glyphicon-chevron-right").parent().fadeIn();
        rightsidebar.find(".glyphicon-chevron-right").fadeIn();
        rightsidebar.attr('data-status', 'opened');
        _thisToggler.addClass("sidebar-opened");
      } else {
        if(typeof $contentBg !== "undefined"){
          $contentBg.css('overflow', 'hidden');
        }
        rightsidebar.find(".glyphicon-chevron-right").hide();
        rightsidebar.find(".glyphicon-chevron-right").parent().hide();
        rightsidebar.find(".right-sidebar-content").hide();
        centersidebar.removeClass("col-md-9").addClass("col-md-12");
        rightsidebar.removeClass("col-md-3");
        rightsidebar.find(".glyphicon-chevron-left").parent().fadeIn();
        rightsidebar.find(".glyphicon-chevron-left").fadeIn();
        rightsidebar.attr('data-status', 'closed');
        _thisToggler.removeClass("sidebar-opened");
      }
    });
    $(".stoggler").trigger('click');
    $(".stoggler").on("mouseover", function(){
      $(this).css({
        "background-color": "rgba(230, 230, 230, 0.80)",
        "border-right": "1px solid rgba(230, 230, 230, 0.80)"
      });
    });
    $(".stoggler").on("mouseleave", function(){
      $(this).css({
        "background-color": "#FFF",
        "border-right": "#FFF"
      });
    });
  };

  var initLayoutProperties = function(target) {
    target.selectable({
      filter: 'tbody tr td',
      stop: function(){
        $(".ui-selected", this).each(function(key, value){
          if($(".ui-selected").length === 1){
            if($(".ui-selected").css('background-color') === 'rgb(255, 255, 255)'){
              $(".ui-selected").css('background-color', "");
            }
            initContentTableEvent($(value));
          } else {
            $.contextMenu({
              selector: '.ui-selected',
              callback: function(key, opt){
                if(key === 'merge'){
                  merge();
                }
              },
              items: {
                "merge": {name: "Нэгтгэх"}
              }
            });
          }
        });
      }});

    $(".ui-state-default").droppable({
      drop: function(event, ui){
        renderMetaById(ui.draggable.attr('id'), ui, $('td.ui-selected'));
      }
    });

    $(".ui-state-default").resizable({
      animate: true,
      stop: function(event, ui){
        initContentTableResizable(ui);
      }
    });
  };

  var renderMetaById=function(id, ui, target){
    $.ajax({
      url: "mdcontentui/renderMetaData/",
      type: "POST",
      dataType: "html",
      data: {metaDataId: id},
      success: function(data){
        target.find(".counter, .cell-width-height").remove();
        produceSelectedCellProperties('meta_data_id', true, null, id);
        target.removeClass("ui-selected");
        target.append("<div class='cellMetaData' metaDataId=='" + id + "'>" + data +
                "</div>");
        removedMetaDataList[id]=id;
        setMetaDataContextMenu();
        ui.draggable.removeClass('ui-selected');
        ui.draggable.remove();
        initLayoutProperties($contentSelectable);
      },
      error: function(){
      }
    }).complete(function(){
    });
  };

  var setMetaDataContextMenu=function(){
    $.contextMenu({
      selector: '.cellMetaData',
      callback: function(key, opt){
        if(key === 'delete'){
          removeMetaDataId(opt.$trigger.parent('td'));
          opt.$trigger.remove();
          if(isUpdate){
            $.each($contentSelectable.find('td'), function(key, value){
              $(value).css('width', '').css('height', '30px');
            });
            isUpdate=false;
          }
        }
      },
      items: {
        "delete": {name: "Устгах", icon: "trash"}
      }
    });
  };

  var removeMetaDataId=function(target){
    var metaDataId=target.find('.cellMetaData').attr('metaDataId');

    var currentCell=getCellId(target);
    var currentRow=target.parent('tr').attr('id');
    if(typeof cellArray[currentRow][currentCell]['meta_data_id'] !== "undefined"){
      delete cellArray[currentRow][currentCell]['meta_data_id'];
    }

    var tmpMetaDataId=parseFloat(metaDataId.replace(/\D/g, ''));
    if(typeof removedMetaDataList[tmpMetaDataId] !== "undefined"){
      var html='<div class="draggable ui-draggable ui-draggable-handle" id="' + tmpMetaDataId +
              '" ' +
              'style="position: relative; border: 1px solid #ccc; margin: 0 0 5px; padding: 5px; cursor: pointer;">' +
              '<i class="fa fa-arrows"></i> ' +
              tmpMetaDataId +
              '</div>';
      $("#metaDataList").append(html);
      delete removedMetaDataList[tmpMetaDataId];
      initMetaDraggableEvent();
    }

  };

  var initContentTableEvent=function($tmpVal){
    $contentUi.find('.inner-borders').val('').css('background-color', '#fff').show();
    $contentUi.find('.inner-tds').show();
    setAndInitBorderEvent($tmpVal, "border-top");
    setAndInitBorderEvent($tmpVal, "border-left");
    setAndInitBorderEvent($tmpVal, "border-bottom");
    setAndInitBorderEvent($tmpVal, "border-right");
    setAndInitBorderEvent($tmpVal, "border-color");
    setAndInitBorderEvent($tmpVal, "background-color");
  };

  var setAndInitBorderEvent=function($tmpVal, border){
    var borderVal,
            caption;
    var id=$tmpVal.attr('id');
    if(typeof cellArray[id.substr(0, parseInt(id.length / 2))] !== "undefined"){
      if(typeof cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
              id.length / 2) + 1, id.length)] !== "undefined"){
        if(typeof cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                id.length / 2) + 1, id.length)][border] !== "undefined"){
          borderVal=cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                  id.length / 2) + 1, id.length)][border];
          $contentUi.find("#" + border).val(borderVal).css('background-color', borderVal);
        }

        if(typeof cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                id.length / 2) + 1, id.length)]['caption'] !== "undefined"){
          caption=cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                  id.length / 2) + 1, id.length)]['caption'];
          $contentUi.find("#caption").val(caption);
        }
      }
    }
  };

  var initContentTableResizable=function(ui){
    var $thisRowTds;
    $.each(ui.originalElement.parent('tr').find('td'), function(key, value){
      $thisRowTds=$(value);

      if(typeof $thisRowTds.attr('colspan') === "undefined" && typeof $thisRowTds.attr(
              'rowspan') === "undefined" && typeof ui.originalElement.attr('colspan') ===
              "undefined" && typeof ui.originalElement.attr('rowspan') ===
              "undefined"){
        $thisRowTds.css('width', '').css('height', '');
      }
    });

    $.each($contentSelectable.find('td'), function(key, value){
      $thisRowTds=$(value);
      if(parseInt($thisRowTds.attr('id').slice(-1)) === ui.originalElement.index()){
        if(typeof $thisRowTds.attr('colspan') === "undefined"){
          $thisRowTds.css('width', '');
        } else if(typeof $thisRowTds.attr('rowspan') === "undefined"){
          $thisRowTds.css('height ', '');
        }
      } else if(isUpdate){
        $thisRowTds.css('width', '').css('height', '');
      }
    });

    isUpdate=false;

    setTimeout(function(){
      $.each($contentSelectable.find('td'), function(key, value){
        $tmpVal=$(value);
        $tmpVal.find('.cell-width-text').html(Math.round10($tmpVal.width(), -1) + 'x');
        $tmpVal.find('.cell-height-text').html(Math.round10($tmpVal.height(), -1));
      });
    }, 2000);
  };

  /**
   * Decimal adjustment of a number.
   *
   * @param {String}  type  The type of adjustment.
   * @param {Number}  value The number.
   * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
   * @returns {Number} The adjusted value.
   */
  var decimalAdjust=function(type, value, exp){
    // If the exp is undefined or zero...
    if(typeof exp === 'undefined' || +exp === 0){
      return Math[type](value);
    }
    value=+value;
    exp=+exp;
    // If the value is not a number or the exp is not an integer...
    if(isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)){
      return NaN;
    }
    // Shift
    value=value.toString().split('e');
    value=Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value=value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  };

  var initDecimalAdjust=function(){
    // Decimal round
    if(!Math.round10){
      Math.round10=function(value, exp){
        return decimalAdjust('round', value, exp);
      };
    }
    // Decimal floor
    if(!Math.floor10){
      Math.floor10=function(value, exp){
        return decimalAdjust('floor', value, exp);
      };
    }
    // Decimal ceil
    if(!Math.ceil10){
      Math.ceil10=function(value, exp){
        return decimalAdjust('ceil', value, exp);
      };
    }
  };

  var chooseColor=function($target, selectedColor){
    var $uiSelected=$('td.ui-selected');
    if($target.hasClass('bgColor')){
      if(layoutId !== 0){
        if($contentBg.hasClass('content-bg_' + layoutId)){
          $contentBg.removeClass('content-bg_' + layoutId);
        }
      }
      $contentBg.css('background', selectedColor);
      $contentBg.css('background-repeat', 'repeat');
      $contentBg.css('min-height', ($('.ui-selectable').height() + 1) + "px");
      data=new FormData();
    } else if($target.hasClass('borderColor')){
      $uiSelected.css('border-color', selectedColor);
      produceSelectedCellProperties('border-color', false, $target);
    } else if($target.hasClass('backgroundColor')){
      $uiSelected.css('background', selectedColor);
      produceSelectedCellProperties('background-color', false, $target);
    }
    $target.val(selectedColor);
  };

  var content_initColorPickerEvent=function(){
    $contentUi.find('.select-color').click(function(){
      initPicker($(this));
    });
    $contentUi.find('.select-color').focus(function(){
      initPicker($(this));
    });
  };

  var initPicker=function(src){
    $colorPickerModal.find('.modal-body').empty().append('<div id="colorPicker"></div>');
    chooseColor(src);
    $colorPickerModal.modal({show: true});
  };

  var content_initChangeBorderWidthEvent=function(){
    $contentUi.find("#borderWidth").keyup(function(){
      if($(this).val()){
        $uiSelectTable=$('.ui-selectable');
        if(layoutId !== 0){
          if($uiSelectTable.hasClass('layout_' + layoutId)){
            $uiSelectTable.removeClass('layout_' + layoutId);
          }
        }
        $uiSelectTable.css('border-spacing', $(this).val() + 'px');
        $uiSelectTable.css('border-collapse', 'separate');
      }
    });
  };

  var changeContentLayoutAttr=function(val, attr){
    $uiSelectTable=$('.ui-selectable');
    var tmpStyle,
            tmpStyleArray,
            tmpStyleArrayUsing=[];
    $.each($uiSelectTable.find('td'), function(key, value){
      $tmpVal=$(value);
      tmpStyle=$tmpVal.attr('style');
      if(tmpStyle.length > 0){
        tmpStyleArray=tmpStyle.split(';');
        for(var i=0; i < tmpStyleArray.length; i++){
          if(tmpStyleArray[i].match(attr)){
            tmpStyleArrayUsing.push(tmpStyleArray[i] + "; ");
          } else {
            tmpStyleArrayUsing.push(attr + ": " + val + "; ");
          }
        }

        for(var i=0; i < tmpStyleArrayUsing.length; i++){
          tmpStyle+=tmpStyleArrayUsing[i];
        }
      } else {
        tmpStyle=attr + ": " + val;
      }
      $tmpVal.css('cssText', tmpStyle);
    });
  };

  var content_initChangeImgInputEvent=function(){
    $("#bgImage").change(function(){
      readURL(this);
    });
  };

  var readURL=function(input){
    if(input.files && input.files[0]){
      var reader=new FileReader();
      reader.onload=function(e){
        $contentBg.css('background', 'url(' + e.target.result + ')');
        $contentBg.css('background-repeat', 'repeat');
        $contentBg.css('min-height', ($('.ui-selectable').height() + 1) + "px");
        $contentUi.find("#color").val('');
      };

      reader.readAsDataURL(input.files[0]);
      data.append(0, input.files[0]);
    }
  };

  var content_initChangeBordersInputEvent=function(){
    changeBorderEvent('border-top', true);
    changeBorderEvent('border-left', true);
    changeBorderEvent('border-bottom', true);
    changeBorderEvent('border-right', true);
  };

  var changeBorderEvent=function(border, isPx){
    $contentUi.find("#" + border).keyup(function(){
      produceSelectedCellProperties(border, isPx, $(this));
    });
  };

  var produceSelectedCellProperties=function(border, isPx, source, id){
    var $uiSelected,
            val;
    if(typeof id !== "undefined"){
      val=id;
    } else {
      val=source.val();
    }

    if(val){
      $uiSelected=$('td.ui-selected');
      var id=$uiSelected.attr('id');
      if(typeof id !== "undefined"){
        if(typeof cellArray[id.substr(0, parseInt(id.length / 2))] !== "undefined"){
          if(typeof cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                  id.length / 2) + 1, id.length)] !== "undefined"){
            cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(id.length /
                    2) + 1, id.length)][border]=val;
            if(isPx){
              $uiSelected.css(border, val + "px solid #ddd");
            }
          }
        }
      }
    }
  };

  var content_initChangeCaptionInputEvent=function(){
    var source,
            $uiSelected,
            val;
    $contentUi.find("#caption").keyup(function(){
      source=$(this);
      val=source.val();
      if(val){
        $uiSelected=$('td.ui-selected');
        var id=$uiSelected.attr('id');
        if(typeof cellArray[id.substr(0, parseInt(id.length / 2))] !== "undefined"){
          if(typeof cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                  id.length / 2) + 1, id.length)] !== "undefined"){
            cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(id.length /
                    2) + 1, id.length)]['caption']=val;
          }
        }
      }
    });
  };

  var merge=function(){
    var is_merged=false;
    var chosenTd=$('.ui-selected');
    var chosenTdCount=chosenTd.length;
//    $.each(chosenTd, function(key, value){
//      // Өмнө нь merge хийгдсэн эсэх
//      if(typeof $(value).attr('alt') !== 'undefined'){
//        new PNotify({
//          title: 'Error',
//          text: 'Merge хийгдсэн мөр/багана байна.',
//          type: 'error',
//          sticker: false
//        });
//        is_merged=true;
//        return false;
//      }
//    });

    if(!is_merged){
      if(getTypeOfSelected() === 'horizontal'){
        var removeCount=0;
        $.each($('.ui-selected'), function(){
          // Begin merge
          var tmpSelectedCount=$('.ui-selected').length;
          if(tmpSelectedCount > 1){
            // mark as merge
            markAsMerged($('.ui-selected:eq(1)'));

            // remove object
            $('.ui-selected:eq(1)').remove();
            removeCount++;
          } else {
            $('.ui-selected:eq(0)').attr('colspan', chosenTdCount);
            // set width
            removeCount++;
            var newWidth=removeCount * 50;
            $('.ui-selected:eq(0)').attr('style', 'width:' + newWidth + 'px');
            markAsStartMerge($('.ui-selected:eq(0)'));
          }
        });

        tmpMergeId++;
      } else if(getTypeOfSelected() === 'vertical'){
        var removeCount=0;
        $.each($('.ui-selected'), function(){
          var tmpSelectedCount=$('.ui-selected').length;
          if(tmpSelectedCount > 1){
            // mark as merge
            markAsMerged($('.ui-selected:eq(1)'));

            $('.ui-selected:eq(1)').remove();
            removeCount++;
          } else {
            $('.ui-selected:eq(0)').attr('rowspan', chosenTdCount);
            // set heigth
            removeCount++;
            var newHeight=removeCount * 50;
            $('.ui-selected:eq(0)').attr('style', 'height:' + newHeight + 'px');
            markAsStartMerge($('.ui-selected:eq(0)'));
          }
        });
        tmpMergeId++;
      } else {
      }

      $('.ui-selected').removeClass('ui-selected');
    }
  };


  var setContentMetaData=function(){
    prepareCellArrayData();

    var cellArrayJson=JSON.stringify(cellArray);
    Core.blockUI({animate: true});
    $.ajax({
      type: 'post',
      url: 'mdcontentui/setContentMetaData',
      dataType: 'json',
      data: {
        cellArray: cellArrayJson,
        layoutId: $("#layoutId").val(),
        metaDataId: $("#metaDataId").val()
      },
      cache: false,
      success: function(data){
        produceAjaxResponseMsg(data);
      },
      error: function(){
        $.unblockUI();
      }
    }).complete(function(){
      $.unblockUI();
    });
  };

  var createLayout = function() {
    if ($("#contentUiForm").valid()) {
      prepareCellArrayData();

      var cellArrayJson=JSON.stringify(cellArray);
      var data = $("#contentUiForm").serialize();
      var formData = contentUi.getFormData();

      data += "&rowCount=" + $rowCount.val() + "&colCount=" + $colCount.val() + "&cellArray=" + cellArrayJson;

      if(layoutId !== 0){
        data+="&layoutId=" + layoutId;
      }
      formData.append('data', data);
      Core.blockUI({animate: true});
      $.ajax({
        type: 'post',
        url: 'mdcontentui/createLayout',
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {
          produceAjaxResponseMsg(data);
        },
        error: function(){
          $.unblockUI();
        }
      }).complete(function(){
        $.unblockUI();
      });
    } 
    else {
      new PNotify({
        title: 'Error',
        text: 'Мэдээллээ гүйцэд оруулана уу.',
        type: 'error',
        sticker: false
      });
    }
  };

  var prepareCellArrayData=function(){
    var $tmpVal,
            $contentSelectable=$("#selectable");
    $.each($contentSelectable.find('td'), function(key, value){
      $tmpVal=$(value);
      var id=$tmpVal.attr('id');
      var cellPercentWidth=100 * $tmpVal.width() / $contentSelectable.innerWidth();
      if(typeof cellArray[id.substr(0, parseInt(id.length / 2))] !== "undefined"){
        if(typeof cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(
                id.length / 2) + 1, id.length)] !== "undefined"){
          cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(id.length /
                  2) + 1, id.length)]['width']=Math.round10(cellPercentWidth, -1);
          cellArray[id.substr(0, parseInt(id.length / 2))][id.substr(parseInt(id.length /
                  2) + 1, id.length)]['height']=Math.round10($tmpVal.height(), -1);
          cellArray[id.substr(0, parseInt(id.length / 2))]['height']=Math.round10(
                  $tmpVal.height(),
                  -1);
        }
      }
    });
  };

  var produceAjaxResponseMsg=function(data){
    if (data.status === 'success') {
      PNotify.removeAll();
      new PNotify({
        title: 'Success',
        text: data.message,
        type: 'success',
        sticker: false
      });
    } else {
      PNotify.removeAll();
      new PNotify({
        title: 'Error',
        text: data.message,
        type: 'error',
        sticker: false
      });
    }
  };

  /** ТУСЛАХ ФУНКЦҮҮД *******************************************************/

  var markAsMerged=function(currentObject){
    var currentCell=getCellId(currentObject);
    var currentRow=currentObject.parent('tr').attr('id');
    if(typeof cellArray[currentRow][currentCell]['startMerge'] !== "undefined"){
      delete cellArray[currentRow][currentCell]['startMerge'];
    }
    cellArray[currentRow][currentCell]['is_merge']=1;
    cellArray[currentRow][currentCell]['tmpMergeId']=tmpMergeId;
    cellArray[currentRow][currentCell]['is_use']=0;
    currentObject.attr('alt', tmpMergeId);
  };

  var markAsStartMerge=function(currentObject){
    var tmpId;
    var currentCell=getCellId(currentObject);
    var currentRow=currentObject.parent('tr').attr('id');
    if(typeof cellArray[currentRow][currentCell]['tmpMergeId'] !== "undefined"){
      tmpId=cellArray[currentRow][currentCell]['tmpMergeId'];
      $.each(cellArray, function(key, value){
        $.each(value, function(k, v){
          if(v['tmpMergeId'] === tmpId){
            cellArray[key][k]['tmpMergeId']=tmpMergeId;
          }
        });
      });
    }
    cellArray[currentRow][currentCell]['startMerge']=1;
    cellArray[currentRow][currentCell]['is_merge']=1;
    cellArray[currentRow][currentCell]['tmpMergeId']=tmpMergeId;
    cellArray[currentRow][currentCell]['is_use']=1;
    currentObject.attr('alt', tmpMergeId);
  };

  var getCellId=function(currentObject){
    var currentCellId=currentObject.attr('id');
    var currentCell=currentCellId.split("_");
    if(typeof currentCell[1] !== "undefined"){
      return currentCell[1];
    } else {
      return false;
    }
  };

  var getTypeOfSelected=function(){
    var chosenTotal=$('.ui-selected').length;
    var chosenFirst=$('.ui-selected:eq(0)');
    var chosenSecond=$('.ui-selected:eq(1)');
    if(chosenTotal > 0){
      var firstTrId=chosenFirst.parent('tr').attr('id');
      var secondTrId=chosenSecond.parent('tr').attr('id');
      if(firstTrId === secondTrId){
        return 'horizontal';
      } else {
        return 'vertical';
      }
    } else {
      return 'none';
    }
  };

  var fullscreen=function(el){
    if($(el).hasClass('fullScreen')){
      $(el).html('Full screen');
      $(el).removeClass('fullScreen');
      $('#contentLayout').removeClass('card-fullscreen');
    } else {
      $(el).addClass('fullScreen');
      $('#contentLayout').addClass('card-fullscreen');
      $(el).html('Exit');
    }
  };

  return {
    init: function(){
      content_initEvent();
    },
    getFormData: function(){
      return data;
    },
    drawLayout: function(){
      drawLayout();
    },
    merge: function(){
      merge();
    },
    createLayout: function(){
      createLayout();
    },
    setContentMetaData: function(){
      setContentMetaData();
    },
    fullscreen: function(el){
      fullscreen(el);
    },
    getCellArray: function(){
      return cellArray;
    }
  };
}();