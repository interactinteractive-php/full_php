<h3 style="position: absolute; color: #CCCCCC; margin: 0;">PRINT PREVIEW</h3>
<table class="nddPrintTempTable float-left" data-width="<?php echo $this->getNDDprintPreview['width']; ?>" style="height: <?php echo ($this->getNDDprintPreview['height'] * 3.7795275590551); ?>px; width: <?php echo ($this->getNDDprintPreview['width'] * 3.7795275590551); ?>px; margin-top: <?php echo ($this->getNDDprintPreview['top'] * 3.7795275590551); ?>px; margin-left: <?php echo ($this->getNDDprintPreview['left'] * 3.7795275590551); ?>px">
  <thead style="line-height: 13px; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px">
    <tr>
        <td rowspan="2" style="text-align: center; width: 11mm; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px; font-size:8px">Сар</td>
        <td colspan="2" style="text-align: center; font-size:8px">Төлсөн шимтгэл</td>
        <td rowspan="2" style="text-align: center; font-size:8px">Тэмдэглэгээ хийсэн байцаагчийн гарын үсэг</td>
    </tr>
    <tr>
        <td style="text-align: center; width: <?php echo ($this->getNDDprintPreview['col1Width'] * 3.7795275590551); ?>px; font-size:8px">Ажил олгогчоос</td>
        <td style="text-align: center; width: <?php echo ($this->getNDDprintPreview['col2Width'] * 3.7795275590551); ?>px;font-size:8px">Даатгуу лагчаас</td>
    </tr>
  </thead>
  <tbody style="height: <?php echo (($this->getNDDprintPreview['height'] - $this->getNDDprintPreview['head_height']) * 3.7795275590551); ?>px;">
    <?php for($i = 1; $i <= 12; $i++) { ?>
        <tr>
          <td align="center"><?php echo $i; ?> сар</td>
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
        <td rowspan="2" style="text-align: center; width: 11mm; height: <?php echo ($this->getNDDprintPreview['head_height'] * 3.7795275590551); ?>px; font-size:8px">Сар</td>
        <td colspan="2" style="text-align: center; font-size:8px">Төлсөн шимтгэл</td>
        <td rowspan="2" style="text-align: center; font-size:8px">Тэмдэглэгээ хийсэн байцаагчийн гарын үсэг</td>
    </tr>
    <tr>
        <td style="text-align: center; width: <?php echo ($this->getNDDprintPreview['col1Width'] * 3.7795275590551); ?>px; font-size:8px">Ажил олгогчоос</td>
        <td style="text-align: center; width: <?php echo ($this->getNDDprintPreview['col2Width'] * 3.7795275590551); ?>px;font-size:8px">Даатгуу лагчаас</td>
    </tr>
  </thead>
  <tbody style="height: <?php echo (($this->getNDDprintPreview['height'] - $this->getNDDprintPreview['head_height']) * 3.7795275590551); ?>px;">
    <?php for($i = 1; $i <= 12; $i++) { ?>
        <tr>
            <td align="center"><?php echo $i; ?> сар</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    <?php } ?>
  </tbody>
</table>