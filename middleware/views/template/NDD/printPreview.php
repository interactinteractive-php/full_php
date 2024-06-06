<div class="report-preview">
    <div class="report-preview-container" style="height: 540px;">
        <div id="nddContentsPre" style="display: block; position: absolute; width: 869px; height: 511px;">
            <?php echo $this->htmlTemplate; ?> 
            <div id="nddContentsPrintPrev" style="position: absolute;padding: 0;"></div>
        </div>
        <div class="clearfix w-100"></div>
    </div>
</div>
<style type="text/css">
    #nddContentsPre {
        background: white;
        width: 23cm;
        height: 13.5cm;
        padding: 0;
        margin: 0;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    .nddColPre {
        display: inline-block;
        position: absolute;
        font-size: 11px;
        color: #000;
        padding: 0;
    }    
    .nddPrintTempTable {
        font-size: 10px;
        border-collapse: collapse;
    }
    #nddContentsPrintPrev {
        border: 1px transparent solid;
        cursor: move;
    }
    #nddContentsPrintPrev:hover {
        background-color: rgba(0, 0, 0, 0.2);
        border: 1px #eee solid;
    }
    table.nddPrintTempTable, .nddPrintTempTable td, .nddPrintTempTable th {
        border: 1px solid black;
    }    
    .bosooText {
      -webkit-transform:rotate(-90deg); 
      -moz-transform:rotate(-90deg); 
      -o-transform: rotate(-90deg);
    }        
</style>

<script type="text/javascript">
var defaultSize = JSON.parse('<?php echo $this->getNDDprintPreviewJson; ?>');
var nddPrintPosition = JSON.parse('<?php echo $this->getNDDprintPosition; ?>');
var defaultTop = 0, marginTop = 0, defaultLeft1 = 0, marginLeft1 = 0, defaultLeft2 = 0, marginLeft2 = 0, defaultLeft3 = 0, marginLeft3 = 0;
var rowCount = nddPrintPosition.length; 
    
$(function(){
    
    $.each(nddPrintPosition, function(key, value) {
        if (key === 0) {
            $("#nddContentsPrintPrev").css('top', (Number(value.top) * 3.7795275590551) + 'px');
            $("#nddContentsPrintPrev").css('left', (Number(value.colOneLeft) * 3.7795275590551) + 'px');
            $("#nddContentsPrintPrev").css('height', ((Number(rowCount) * Number(defaultSize.rowHeight)) * 3.7795275590551) + 'px');
            $("#nddContentsPrintPrev").css('width', ((Number(defaultSize.col1Width) + Number(defaultSize.col2Width) + Number(defaultSize.col3Width)) * 3.7795275590551) + 'px');
            
            defaultTop = value.top;
            defaultLeft1 = value.colOneLeft;
            defaultLeft2 = value.colTwoLeft;
            defaultLeft3 = value.colThreeLeft;
            marginTop = 0;
            marginLeft1 = 0;
            marginLeft2 = (Number(value.colTwoLeft) - Number(defaultLeft1)) * 3.7795275590551;
            marginLeft3 = (Number(value.colThreeLeft) - Number(defaultLeft1)) * 3.7795275590551;
            
        } else {
            marginTop = (Number(value.top) - Number(defaultTop)) * 3.7795275590551;
            marginLeft1 = 0;
            marginLeft2 = (Number(value.colTwoLeft) - Number(defaultLeft1)) * 3.7795275590551;
            marginLeft3 = (Number(value.colThreeLeft) - Number(defaultLeft1)) * 3.7795275590551;
        }
        
        $("#nddContentsPrintPrev").append(
            "<div style='top: "+marginTop+"px; position: absolute;'>"+
                "<div class='nddColPre' style='left: "+marginLeft1+"px'>"+value.col1Data+"</div><div class='nddColPre' style='left: "+marginLeft2+"px'>"+value.col2Data+"</div><div class='nddColPre' style='left: "+marginLeft3+"px'>"+value.col3Data+"</div>"+
            "</div>"
        );
    });   
    
    $("#nddContentsPrintPrev").draggable({
        appendTo: '#nddContentsPre',
        containment: $('#nddContentsPre'),
        cursor: 'move',
        scroll: false
    });

});
</script>

