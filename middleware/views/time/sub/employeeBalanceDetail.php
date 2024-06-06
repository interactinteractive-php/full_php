<div class="col-md-12">
    <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1">
        <thead>
            <tr>
              <th>№</th>
              <th><?php echo 'Цаг' ?></th>
              <th><?php echo 'Орсон/Гарсан' ?></th>
              <th><?php echo 'Тайлбар' ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($this->data) {
              $i = 1;
              foreach ($this->data as $row) { ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['ATTENDANCE_DATE_TIME']; ?></td>
                    <td><?php echo ($row['ACCESS_TYPE_ID'] === '1') ? 'Орсон' : 'Гарсан'; ?></td>
                    <td><?php echo $row['DESCRIPTION']; ?></td>
                </tr>
            <?php }
              } ?>
        </tbody>
    </table>
</div>