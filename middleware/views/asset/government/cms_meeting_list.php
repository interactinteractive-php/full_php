<div class="w-100 bg-white p-3 eedit">
    <div class="top-background">
        <div class="row">
            <div class="col">
                <span class="first">Эхэлсэн:</span> <span class="second">08:00:44</span>
                <span class="first">Завсарласан:</span> <span class="second">00 цаг 00 мин</span>
                <span class="first">Үргэлжилсэн:</span> <span class="second">5 цаг 20 мин</span>
                <span class="first">Дууссан:</span> <span class="second">13:20:55</span>
                <span class="first">Ирцийн хувь:</span> <span class="second">87.5%</span>
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-sm btn-circle hide btn-success mr5 bp-btn-saveedit" onclick="runAutoEditBusinessProcess(this, '1559809605021332', '1573441697300548', true);" data-dm-id="1559809605021332"><i class="fa fa-pencil"></i> Хадгалаад засах</button>
                <button type="button" class="btn btn-sm btn-circle btn-success bpMainSaveButton bp-btn-save " onclick="runBusinessProcess(this, '1559809605021332', '1573441697300548', true);" data-dm-id="1559809605021332"><i class="icon-checkmark-circle2"></i> Хадгалах</button>
                <button type="button" class="btn btn-sm btn-circle purple-plum ml5 bp-btn-saveprint" onclick="runBusinessProcess(this, '1559809605021332', '1573441697300548', true, 'saveprint');" data-dm-id="1559809605021332"><i class="fa fa-print"></i> Хадгалаад хэвлэх</button>
                <button type="button" class="btn btn-sm btn-circle green ml5 bp-btn-print " id="printReportProcess" onclick="processPrintPreview(this, '1570442015252',  '1572352003853', '');"><i class="fa fa-print"></i> Тайлан хэвлэх</button>
            </div>
        </div>
    </div>
    <!-- <div class="top-background v2">
        <div class="row">
            <div class="col-10">
                <span class="first">Эхэлсэн:</span> <span class="second"><input type="time" class=""></span>
                <span class="first">Завсарласан:</span> <span class="second"><input type="time" class=""></span>
                <span class="first">Үргэлжилсэн:</span> <span class="second"><input type="time" class=""></span>
                <span class="first">Дууссан:</span> <span class="second"><input type="time" class=""></span>
                <span class="first">Ирцийн хувь:</span> <span class="second"><input type="text" class="" placeholder="89%"></span>
            </div>
            <div class="col-2 text-right">
                <span class="first text-uppercase" style="line-height: 25px;">2019 оны 06 сарын 05 Лхагва гариг</span>
            </div>
        </div>
    </div> -->
    <div class="row justify-content-center mt-2 mb-2">
        <div style="width:600px;">
            <center><img src="assets/custom/img/mgl-soyombo.png" height="50" class="mb-1"></center>
            <h5 class="text-uppercase text-center mb-0" style="color:#2b3d87;">Монгол улсын засгийн газрын 25-р хуралдааны ирц</h5>
        </div>
    </div>
    <div class="mb-4">
        <h5 class="mb-0 ml-1">Хуралдаанд оролцогчид</h5>
        <div class="table-responsive" id="task-list">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th>Дугаар</th>
                        <th>Засгийн газрын гишүүд</th>
                        <th><span class="mr70">Ирсэн</span><span>Завсарлаад ирсэн</span></th>
                        <th><span class="mr13">Чөлөө авч явсан</span><span>Ирсэн</span></th>
                        <th><span class="mr13">Чөлөө авч явсан</span><span>Ирсэн</span></th>
                        <th>Тайлбар</th>
                        <th>Төлөв</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-blue pl25">1</td>
                        <td class="membername">
                            У.Хүрэлсүх
                        </td>
                        <td>
                            <input type="time" class="" value="16:55">
                            <input type="time" class="" value="06:19">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td class="desc">
                            <textarea rows="4" cols="50"></textarea>
                        </td>
                        <td>
                            <select class="slct">
                                <option value="1" name="" class="">Ирсэн</option>
                                <option value="2" name="" class="" selected>Хоцорсон</option>
                                <option value="3" name="" class="">Тасалсан</option>
                                <option value="4" name="" class="">Өвчтэй</option>
                                <option value="5" name="" class="">Чөлөөтэй</option>
                                <option value="6" name="" class="">Хоцорсон-Чөлөөтэй</option>
                                <option value="7" name="" class="">Дотоод томилттой</option>
                                <option value="8" name="" class="">Гадаад томилттой</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-blue pl25">2</td>
                        <td class="membername">
                            Ц.Нямдорж
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td class="desc">
                            <textarea rows="4" cols="50"></textarea>
                        </td>
                        <td>
                            <select class="slct">
                                <option value="1" name="" class="">Ирсэн</option>
                                <option value="2" name="" class="">Хоцорсон</option>
                                <option value="3" name="" class="">Тасалсан</option>
                                <option value="4" name="" class="">Өвчтэй</option>
                                <option value="5" name="" class="">Чөлөөтэй</option>
                                <option value="6" name="" class="">Хоцорсон-Чөлөөтэй</option>
                                <option value="7" name="" class="" selected>Дотоод томилттой</option>
                                <option value="8" name="" class="">Гадаад томилттой</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-blue pl25">3</td>
                        <td class="membername">
                            Ө.Энхтүвшин
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td class="desc">
                            <textarea rows="4" cols="50"></textarea>
                        </td>
                        <td>
                            <select class="slct">
                                <option value="1" name="" class="">Ирсэн</option>
                                <option value="2" name="" class="">Хоцорсон</option>
                                <option value="3" name="" class="">Тасалсан</option>
                                <option value="4" name="" class="">Өвчтэй</option>
                                <option value="5" name="" class="" selected>Чөлөөтэй</option>
                                <option value="6" name="" class="">Хоцорсон-Чөлөөтэй</option>
                                <option value="7" name="" class="">Дотоод томилттой</option>
                                <option value="8" name="" class="">Гадаад томилттой</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <h5 class="mb-0 ml-1">Бусад оролцогчид</h5>
        <div class="table-responsive" id="task-list">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th>Дугаар</th>
                        <th>Засгийн газрын гишүүд</th>
                        <th><span class="mr70">Ирсэн</span><span>Завсарлаад ирсэн</span></th>
                        <th><span class="mr13">Чөлөө авч явсан</span><span>Ирсэн</span></th>
                        <th><span class="mr13">Чөлөө авч явсан</span><span>Ирсэн</span></th>
                        <th>Тайлбар</th>
                        <th>Төлөв</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-blue pl25">1</td>
                        <td class="membername">
                            Л.Оюун-Эрдэнэ
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td class="desc">
                            <textarea rows="4" cols="50"></textarea>
                        </td>
                        <td>
                            <select class="slct">
                                <option value="1" name="" class="">Ирсэн</option>
                                <option value="2" name="" class="">Хоцорсон</option>
                                <option value="3" name="" class="">Тасалсан</option>
                                <option value="4" name="" class="" selected>Өвчтэй</option>
                                <option value="5" name="" class="">Чөлөөтэй</option>
                                <option value="6" name="" class="">Хоцорсон-Чөлөөтэй</option>
                                <option value="7" name="" class="">Дотоод томилттой</option>
                                <option value="8" name="" class="">Гадаад томилттой</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-blue pl25">2</td>
                        <td class="membername">
                            Н.Цэрэнбат
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td class="desc">
                            <textarea rows="4" cols="50"></textarea>
                        </td>
                        <td>
                            <select class="slct">
                                <option value="1" name="" class="">Ирсэн</option>
                                <option value="2" name="" class="">Хоцорсон</option>
                                <option value="3" name="" class="">Тасалсан</option>
                                <option value="4" name="" class="">Өвчтэй</option>
                                <option value="5" name="" class="">Чөлөөтэй</option>
                                <option value="6" name="" class="">Хоцорсон-Чөлөөтэй</option>
                                <option value="7" name="" class="" selected>Дотоод томилттой</option>
                                <option value="8" name="" class="">Гадаад томилттой</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-blue pl25">3</td>
                        <td class="membername">
                            Н.Энхболд
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td>
                            <input type="time" class="">
                            <input type="time" class="">
                        </td>
                        <td class="desc">
                            <textarea rows="4" cols="50"></textarea>
                        </td>
                        <td>
                            <select class="slct">
                                <option value="1" name="" class="">Ирсэн</option>
                                <option value="2" name="" class="">Хоцорсон</option>
                                <option value="3" name="" class="">Тасалсан</option>
                                <option value="4" name="" class="">Өвчтэй</option>
                                <option value="5" name="" class="" selected>Чөлөөтэй</option>
                                <option value="6" name="" class="">Хоцорсон-Чөлөөтэй</option>
                                <option value="7" name="" class="">Дотоод томилттой</option>
                                <option value="8" name="" class="">Гадаад томилттой</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="task-modal-detail-22" class="modal fade task-list-modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $row['taskname']; ?></h5>
                    <button type="button" class="close" data-dismiss="modal"><i class="icon-cross2 font-size-base"></i></button>
                </div>
                <div class="modal-body p-0"></div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-primary" data-dismiss="modal">Хаах</button>
                </div>
            </div>
        </div>
    </div>
</div>