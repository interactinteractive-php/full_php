<div class="col-md-12">
  <?php // var_dump($this->row); ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>№</th>
        <th><?php echo 'Эхлэх огноо' ?></th>
        <th><?php echo 'Дуусах огноо' ?></th>
        <th><?php echo 'Өдөр' ?></th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($this->row) {
        $i = 1;
        foreach ($this->row as $row) { ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $row['START_DATE']; ?></td>
        <td><?php echo $row['END_DATE']; ?></td>
        <td><?php echo $row['DAY_DIFF']; ?></td>
      </tr>
      <?php }
        } ?>
    </tbody>
  </table>
</div>