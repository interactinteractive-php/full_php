<div class="alert bg-warning text-white alert-styled-left alert-dismissible text-center">
    <h2>Өгөгдлийн сангийн шинэчлэлт хийгдсэн эсэх</h2>
    <button type="button" class="btn btn-primary rounded-round" id="sysupdate-confirm-button">Тийм асуудалгүй</button>
</div>
<div class="form-group mb-3 mb-md-2" style="display: none" id="sysupdate-codenames">
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="frontend-full"> (Frond End) Full
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="frontend-assets"> (Frond End) Assets
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="frontend-helper"> (Frond End) Helper
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="frontend-libs"> (Frond End) Libs
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="frontend-middleware"> (Frond End) Middleware
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="backend"> Back End
        </label>
    </div>
    <div class="form-check form-check-inline">
        <label class="form-check-label">
            <input type="checkbox" name="codeNames[]" value="frontend-custom-files"> Frond End /custom files/
        </label>
    </div>
    
    <div class="clearfix"></div>
    <button type="button" class="btn bg-teal mt-4" id="sysupdate-start-button" style="display: none">Шинэчлэх</button>
</div>