<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>

<section id="body" class="section-anket">
  <div class="form-btn">
    <div class="container">
      <?php if($this->promp && $this->promp['0']['approvel']) { ?>
        <a href="javascript:;" class="btn btn-primary btn-lg" data-toggle="modal" style="background-color: <?php echo $this->anketColor ?>" data-target="#promptext">
          Анкет бөглөх 
        </a>
      <?php } else { ?>
        <a href="anket/form/<?php echo $this->id; ?>/<?php echo $this->jobDetail['positionid']; ?>/<?php echo issetParam($this->jobDetail['kpitemplateid']); ?>" class="btn btn-primary btn-lg" style="background-color: <?php echo $this->anketColor ?>" data-target="#promptext">
          Анкет бөглөх 
        </a>
      <?php } ?>
    </div>
  </div>
  <div class="container">
    <div class="anket-desc">
      <?php echo html_entity_decode($this->jobDetail['description'], ENT_QUOTES, 'UTF-8'); ?>
    </div>
  </div>
</section>
<div class="modal fade" id="promptext" tabindex="-1" role="dialog" aria-labelledby="promptext" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
      <?php echo $this->promp['0']['approvel']; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light btn-sm" data-dismiss="modal">Хаах</button>
        <a href="anket/form/<?php echo $this->id; ?>/<?php echo $this->jobDetail['positionid']; ?>/<?php echo issetParam($this->jobDetail['kpitemplateid']); ?>" class="btn btn-primary btn-sm" style="background-color: <?php echo $this->anketColor ?>">Үргэлжлүүлэх</a> 
      </div>
    </div>
  </div>
</div>

<style>
  .anket-header {
    margin-bottom: 0 !important;
  }
</style>