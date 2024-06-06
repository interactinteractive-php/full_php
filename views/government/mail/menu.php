<div class="card">
    <div class="card-body p-0">
        <ul class="nav nav-sidebar" data-nav-type="accordion">
            <?php if ($this->mainData) {
                foreach ($this->mainData as $key => $row) {
                    $rowJson = htmlentities(json_encode($row), ENT_QUOTES, 'UTF-8');
                    ?>
                    <li class="nav-item <?php echo $row['status'] ?>-li" data-rowdata="<?php echo $rowJson; ?>">
                        <a href="javascript:void(0);" class="nav-link <?php echo $row['status'] ?>"
                            <?php echo $row['typeid'] == '0' ? 'onclick="modalshow_'. $this->uniqId .'()" data-target1="#modal_theme_primary_'. $this->uniqId .'"' : '' ?>>
                            <?php echo $row['icon']; ?>
                            <?php echo $row['name'];
                            if ($row['count']) { ?>
                                <span class="badge badge-pill ml-auto" style="background: #1ebcec;"><?php echo $row['count']; ?></span>
                            <?php } ?>
                        </a>
                    </li>
                <?php }
            } ?>
        </ul>
    </div>
</div>