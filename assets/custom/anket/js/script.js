var anketJS=function(){
  //<editor-fold defaultstate="collapsed" desc="variables">
  var $topContact=$("#topContact"),
          $jobList=$("#jobList"),
          $pagingJobList=$("#pagingJobList"),
          $panket = $('#panket'),
          page=1,
          pageSize=6,
          totalCount;
  //</editor-fold>

  //<editor-fold defaultstate="collapsed" desc="events">
  var initEvent=function(){
    initMenuEvent();
    getJobList();
  };

  var initMenuEvent=function(){
    $("#toggleMenuBtn").click(function(){
      $topContact.toggleClass('show');
    });
  };

  //<editor-fold defaultstate="collapsed" desc="job list">
  var getJobList=function(){
    var data={
      rows: pageSize,
      page: page
    };
    Core.blockUI({
      target: $jobList,
      message: 'Уншиж байна...',
      boxed: true
    });
    $.ajax({
      url: "anket/getJobList",
      type: "POST",
      data: data,
      dataType: "JSON",
      success: function(response){
        if(response.status === "success"){
          totalCount=response.totalCount;
          drawJobList(response.result);
          if( 0 < totalCount){
            $panket.addClass('d-none');
            $('.anketBriefMessage').addClass('d-none');
          }else{
            $pagingJobList.append('<p class="nonenaket">Нээлттэй ажлын байр зарлагдаагүй байна</p>');
          }
         
        }
      },
      error: function(jqXHR, exception){
        Core.unblockUI($jobList);
      }
    }).complete(function(){
      Core.unblockUI($jobList);
    });
  };

  var drawJobList=function(result){
    var html='';
    for(i=0; i < result.length; i++){
      var tmpResult=result[i];
      html+='<tr>';
      html+='<td>' + tmpResult['departmentname'] + '</td>' +
              '<td><a href="anket/detail/' + tmpResult['campaignkeyid'] + '">' + (typeof tmpResult['campaignname'] !== 'undefined' ? tmpResult['campaignname'] : '---') + '</a></td>' +
              '<td>' + tmpResult['startdate'] + '</td>' +
              '<td>' + tmpResult['enddate'] + '</td>';
      html+='</tr>';
    }

    $jobList.find('tbody').html(html);
    initPaging();
  };

  //<editor-fold defaultstate="collapsed" desc="pagination">
  var initPaging=function(){
    var htmlPaging="";
   
   
    if(page !== 1){
      htmlPaging+='<li class="prev"><i class="icon-chevron-left"></i></li>';
     
    } else {
      htmlPaging+='<li class="prev disactive"><i class="icon-chevron-left"></i></li>';
    }

    var total=decimalAdjust('ceil', parseFloat(totalCount) / parseFloat(pageSize));
    for(var i=1; i <= total; i++){
      if(page === i){
        htmlPaging+='<li class="pagination active">' + i + '</li>';
      } else {
        htmlPaging+='<li class="pagination">' + i + '</li>';
      }
    }

    if(page !== total){
      htmlPaging+='<li class="next"><i class="icon-chevron-right"></i></li>';
      
    } else {
      htmlPaging+='<li class="next disactive"><i class="icon-chevron-right"></i></li>';
     
    }

    $pagingJobList.html(htmlPaging);
    initPagingEvent();
  };

  var initPagingEvent=function(){
    $pagingJobList.find('.pagination').click(function(){
      page=parseFloat($(this).html());
      getJobList();
    });
    $pagingJobList.find('.prev').click(function(){
      page=parseInt($pagingJobList.find('.pagination.active').html()) - 1;
      getJobList();
    });
    $pagingJobList.find('.next').click(function(){
      page=parseInt($pagingJobList.find('.pagination.active').html()) + 1;
      getJobList();
    });
  };
  //</editor-fold>
  //</editor-fold>
  //</editor-fold>

  //<editor-fold defaultstate="collapsed" desc="helper">

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
  //</editor-fold>

  return {
    init: function(){
      initDecimalAdjust();
      initEvent();
    }
  };
}();