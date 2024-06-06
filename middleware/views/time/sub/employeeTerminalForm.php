<div class="col-md-12">
  <?php // var_dump($this->row); ?>
  <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1">
    <thead>
      <tr>
        <th>№</th>
        <th><?php echo 'Төхөөрөмжний нэр' ?></th>
        <th><?php echo 'Салбар, нэгж' ?></th>
        <th><?php echo 'Төрөл' ?></th>
        <th><?php echo 'Огноо' ?></th>
      </tr>
    </thead>
    <tbody>
      <?php 
      if ($this->row) {
        $i = 1;
        foreach ($this->row as $row) { ?>
      <tr>
        <td><?php echo $i++; ?></td>
        <td><?php echo $row['NAME']; ?></td>
        <td><?php echo $row['DEPARTMENT_NAME']; ?></td>
        <td><?php echo $row['TERMINAL_NAME']; ?></td>
        <td><?php echo $row['ATTENDANCE_DATE_TIME']; ?></td>
      </tr>
      <?php }
        } ?>
    </tbody>
  </table>
</div>