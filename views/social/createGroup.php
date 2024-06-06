<form class="form-horizontal" method="post" enctype="multipart/form-data" action="social/createGroup" id="scl-create-group">
    <div class="form-body">
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">* Бүлгийн нэр</label>
            <div class="col-md-12">
                <input type="text" name="name" class="form-control" required="required">
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">* Бүлгийн тухай</label>
            <div class="col-md-12">
                <textarea name="descr" class="form-control" rows="3" required="required"></textarea>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">* Бүлгийн нууцлал</label>
            <div class="col-md-6">
                <select name="privacyType" class="form-control" required="required">
                    <option value="public">Нээлттэй</option>
                    <option value="closed">Хаалттай</option>
                </select>
            </div>
        </div>
        <div class="form-group row fom-row">
            <label class="col-md-12 font-weight-bold">Бүлгийн зураг</label>
            <div class="col-md-12">
                <input type="file" name="cover" class="form-control" accept="image/jpeg,image/png,image/gif">
            </div>
        </div>
    </div>
</form>