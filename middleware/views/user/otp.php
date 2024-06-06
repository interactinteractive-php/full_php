<div class="form-group otp-choose-mode">
    <label>Нэг удаагийн нууц үг хүлээн авах суваг:</label>
    <?php
    if ($this->email) {
    ?>
    <button type="button" class="btn btn-block btn-light rounded-round text-left mt-2 mb-2 otp-btn-mode" data-mode="email">
        <i class="icon-circle mr-2"></i> <?php echo maskEmail($this->email); ?>
    </button>
    <?php
    }
    if ($this->phoneNumber) {
    ?>
    <button type="button" class="btn btn-block btn-light rounded-round text-left mt-2 mb-2 otp-btn-mode" data-mode="phoneNumber">
        <i class="icon-circle mr-2"></i> <?php echo maskPhoneNumber($this->phoneNumber); ?>
    </button>
    <?php
    }
    ?>
</div>
<div class="form-group otp-input-mode d-none">
    <div class="alert alert-info alert-styled-left"></div>
    <input type="text" class="form-control p-3 my-2 text-center" placeholder="Баталгаажуулах кодоо оруулна уу">
</div>