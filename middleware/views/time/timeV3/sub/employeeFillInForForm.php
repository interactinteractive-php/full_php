<div class="col-md-12">
  <?php // var_dump($this->row); ?>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>№</th>
        <th><?php echo 'Орлон ажиллах албан тушаал' ?></th>
        <th><?php echo 'Салбар, нэгж' ?></th>
        <th><?php echo 'Эхлэх огноо' ?></th>
        <th><?php echo 'Дуусах огноо' ?></th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($this->row) {
        $i = 1;
        foreach ($this->row as $row) { ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $row['POSITION_NAME']; ?></td>
        <td><?php echo $row['DEPARTMENT_NAME']; ?></td>
        <td><?php echo Date::format('Y-m-d', $row['START_DATE']); ?></td>
        <td><?php echo Date::format('Y-m-d', $row['END_DATE']); ?></td>
      </tr>
      <?php }
        } ?>
    </tbody>
  </table>
</div>