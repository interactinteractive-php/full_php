<h3 style="position: absolute; color: #CCCCCC; margin: 0;">PRINT PREVIEW</h3>
<table class="nddPrintTempTable float-left" data-width="<?php echo $this->getNDDprintPreview['width']; ?>" style="height: <?php echo ($this->getNDDprintPreview['height'] * 3.7795275590551); ?>px; width: <?php echo ($this->getNDDprintPreview['width'] * 3.7795275590551); ?>px; margin-top: <?php echo ($this->getNDDprintPreview['top'] * 3.7795275590551); ?>px; margin-left: <?php echo ($this->getNDDprintPreview['left'] * 3.7795275590551); ?>px">
  <thead style="line-height: 13px; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px">
    <tr>
      <td rowspan="3" class="bosooText" style="text-align: center; width: 6.5mm; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px; font-size:8px">Сар</td>
      <td rowspan="3" style="text-align: center; width: <?php echo ($this->getNDDprintPreview['col1Width'] * 3.7795275590551); ?>px; font-size:8px">Сарын хөдөлмө-рийн хөлс, түүнтэй адилтгах орлого</td>  
      <td colspan="2" style="text-align: center;font-size:8px">Төлсөн шимтгэл</td>
      <td rowspan="3" style="text-align: center;font-size:8px">Бичилт хийсэн нягтлан бодогчийн гарын үсэг /тэмдэг/</td>
      <td rowspan="3" style="text-align: center;font-size:8px">Шалгасан нийгмийн даатгалын байцааг-чийн гарын үсэг /тэмдэг/</td>
    </tr>
    <tr>
      <td rowspan="2" class="bosooText" style="text-align: center;width: <?php echo ($this->getNDDprintPreview['col2Width'] * 3.7795275590551); ?>px;font-size:8px">Бүгд</td>  
      <td style="text-align: center;width: <?php echo ($this->getNDDprintPreview['col3Width'] * 3.7795275590551); ?>px; height: 10px;font-size:8px;">Үүнээс</td>  
    </tr>
    <tr>
        <td class="bosooText" style="text-align: center;font-size:8px">Даатгуу-лагчаас</td>
    </tr>
  </thead>
  <tbody style="height: <?php echo (($this->getNDDprintPreview['height'] - $this->getNDDprintPreview['head_height']) * 3.7795275590551); ?>px;">
    <?php 
    $rowHeight = $this->getNDDprintPreview['rowHeight'] * 3.7795275590551; 
    for ($i = 1; $i <= 12; $i++) { 
    ?>
    <tr>
        <td align="center" style="height: <?php echo $rowHeight; ?>px; line-height: <?php echo $rowHeight; ?>px"><?php echo $i; ?></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<table class="nddPrintTempTable float-left" style="height: <?php echo ($this->getNDDprintPreview['height'] * 3.7795275590551); ?>px; width: <?php echo ($this->getNDDprintPreview['width'] * 3.7795275590551); ?>px; margin-top: <?php echo ($this->getNDDprintPreview['top'] * 3.7795275590551); ?>px; margin-left: <?php echo ($this->getNDDprintPreview['between'] * 3.7795275590551); ?>px">
  <thead style="line-height: 13px; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px">
    <tr>
      <td rowspan="3" class="bosooText" style="text-align: center;width: 6.5mm; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px;font-size:8px">Сар</td>
      <td rowspan="3" style="text-align: center;width: <?php echo ($this->getNDDprintPreview['col1Width'] * 3.7795275590551); ?>px;font-size:8px">Сарын хөдөлмө-рийн хөлс, түүнтэй адилтгах орлого</td>  
      <td colspan="2" style="text-align: center;font-size:8px">Төлсөн шимтгэл</td>
      <td rowspan="3" style="text-align: center;font-size:8px">Бичилт хийсэн нягтлан бодогчийн гарын үсэг /тэмдэг/</td>
      <td rowspan="3" style="text-align: center;font-size:8px">Шалгасан нийгмийн даатгалын байцааг-чийн гарын үсэг /тэмдэг/</td>
    </tr>
    <tr>
      <td rowspan="2" class="bosooText" style="text-align: center;width: <?php echo ($this->getNDDprintPreview['col2Width'] * 3.7795275590551); ?>px;font-size:8px">Бүгд</td>  
      <td style="text-align: center;width: <?php echo ($this->getNDDprintPreview['col3Width'] * 3.7795275590551); ?>px; height: 10px;font-size:8px;">Үүнээс</td>  
    </tr>
    <tr> 
        <td class="bosooText" style="text-align: center;font-size:8px">Даатгуу-лагчаас</td>
    </tr>
  </thead>
  <tbody style="height: <?php echo (($this->getNDDprintPreview['height'] - $this->getNDDprintPreview['head_height']) * 3.7795275590551); ?>px;">
    <?php for($i = 1; $i <= 12; $i++) { ?>
        <tr>
            <td align="center" style="height: <?php echo $rowHeight; ?>px; line-height: <?php echo $rowHeight; ?>px"><?php echo $i; ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php } ?>
  </tbody>
</table>