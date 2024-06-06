<script src="assets/core/js/main/jquery.min.js"></script>
<script src="assets/core/js/main/jquery-migrate.min.js" type="text/javascript"></script>
<script src="assets/core/js/plugins/extensions/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
<script src="assets/core/js/main/bootstrap.bundle.min.js"></script>
<script src="assets/custom/js/core.js" type="text/javascript"></script>
<script src="assets/custom/js/plugins.min.js" type="text/javascript"></script>

<section id="body" class="section-anket">
    <div class="container joblist choose mt-2 p-0">
        <div class="title-common">
            <div class="section-title">
                <span>Сонгон шалгаруулалтын үе шат</span>
            </div>
        </div>
        <div class="step-5 p-3 row">
            <div class="col steps">
                <div class="img-bg">
                    <img src="assets/custom/anket/img/1.png">
                </div>
                <div class="title">Анкет хүлээн<br>авах</div>
            </div>
            <div class="col steps">
                <div class="img-bg">
                    <img src="assets/custom/anket/img/2.png">
                </div>
                <div class="title">Анкетын<br>шалгаруулалт</div>
            </div>
            <div class="col steps">
                <div class="img-bg">
                    <img src="assets/custom/anket/img/3.png">
                </div>
                <div class="title">Шалгалт<br>Тест</div>
            </div>
            <div class="col steps">
                <div class="img-bg">
                    <img src="assets/custom/anket/img/4.png">
                </div>
                <div class="title">Ярилцлага<br>уулзалт</div>
            </div>
            <div class="col steps">
                <div class="img-bg">
                    <img src="assets/custom/anket/img/5.png">
                </div>
                <div class="title">Ажилд авах<br>санал</div>
            </div>
        </div>
    </div>
    <div class="container joblist mt-2 p-0">
        <div class="title-common">
            <div class="section-title">
                <span>Ажлын байр</span>
            </div>
        </div>
        <?php if($this->anketBriefMessage) { ?>
            <div class="alert alert-primary border-0 alert-dismissible mb0 anketBriefMessage">
                <?php echo html_entity_decode($this->anketBriefMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>        
        <?php } ?>
        <?php
            // if ($this->publicAnket == '1') {
                // echo  '<a href="anket/form/1584965352571" class="button2 new" style="background-color: #034BA7">Өргөдлийн маягтыг энд дарж бөглөнө үү</a>';
            // } else {    
        ?>
        <div id ="panket">
            <?php if($this->promp == '') { ?>
                <a href="anket/form/1584965352571" class="btn ml20 mt20 mt10 sss" data-toggle="modal" data-target="#promptext" style="background-color: #034BA7">
                Анкет бөглөх 
                </a>
            <?php } elseif ($this->publicAnket == '1') { ?>
                <a href="anket/form/1584965352571" class="btn ml20 mt20 mt10" style="background-color: #405e7c;color:#fff"> Анкет бөглөх </a>
            <?php } ?>
        </div>
        <div class="p-3">
            <table class="table table-striped anket-table" id="jobList">
                <thead>
                    <th>Газар, Хэлтэс</th>
                    <th>Ажлын байрны нэр</th>
                    <th>Нээлтийн огноо</th>
                    <th>Хаалтын огноо</th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <ul class="anket-pagination list-inline" id="pagingJobList"></ul>
        <div class="modal fade" id="promptext" tabindex="-1" role="dialog" aria-labelledby="promptext" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <?php echo $this->promp['0']['approvel']; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn button2 btn-secondary" data-dismiss="modal" style="border: 0;border-radius: 0; background: #258cab;">Хаах </button>
                        <a href="anket/form/1584965352571" class="button2" style="background-color: <?php echo $this->anketColor ?>">Үргэлжлүүлэх</a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>