<p class="consts-title mt5">Хуудасны тохиргоо</p>
<div class="panel panel-default bg-inverse mb0" id="tmpPageOption">
    <table class="table sheetTable" style="table-layout: fixed">
        <tbody>     
            <tr>
                <td style="width: 110px; height: 30px;" class="left-padding"><label for="rtMarginTop">Margin top:</label></td>
                <td>
                    <input type="text" name="rtMarginTop" id="rtMarginTop" class="form-control form-control-sm stringInit" placeholder="Margin top" value="<?php echo $this->row['PAGE_MARGIN_TOP']; ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="rtMarginLeft">Margin left:</label></td>
                <td>
                    <input type="text" name="rtMarginLeft" id="rtMarginLeft" class="form-control form-control-sm stringInit" placeholder="Margin left" value="<?php echo $this->row['PAGE_MARGIN_LEFT']; ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="rtMarginRight">Margin right:</label></td>
                <td>
                    <input type="text" name="rtMarginRight" id="rtMarginRight" class="form-control form-control-sm stringInit" placeholder="Margin right" value="<?php echo $this->row['PAGE_MARGIN_RIGHT']; ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="rtMarginBottom">Margin bottom:</label></td>
                <td>
                    <input type="text" name="rtMarginBottom" id="rtMarginBottom" class="form-control form-control-sm stringInit" placeholder="Margin bottom" value="<?php echo $this->row['PAGE_MARGIN_BOTTOM']; ?>">
                </td>
            </tr>
            <tr>
                <td style="height: 30px;" class="left-padding"><label for="rtArchiveWfmCode">Preview wfm code:</label></td>
                <td>
                    <input type="text" name="rtArchiveWfmCode" id="rtArchiveWfmCode" class="form-control form-control-sm stringInit" placeholder="Төлөвийн код" value="<?php echo $this->row['ARCHIVE_WFM_STATUS_CODE']; ?>">
                </td>
            </tr>
        </tbody>
    </table>
</div>
