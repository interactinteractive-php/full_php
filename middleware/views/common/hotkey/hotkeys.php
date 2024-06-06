<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div id="fz-hotkeys" class="freeze-overflow-xy-auto" style="border: 1px solid #ddd;">
        <table class="table table-sm table-bordered table-hover mb0" id="hotkeys-tbl">
            <thead>
                <tr>
                    <th class="text-center" style="vertical-align: middle; width: 25px">№</th>
                    <th class="text-center" style="vertical-align: middle; width: 28%">Үйлдэл</th>
                    <th class="text-center" style="width: 90px">Тайлбар</th>
                    <th class="text-center" style="width: 90px">Hotkey</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Хадгалах</td>
                    <td>Бүх процессийн цонх хадгалах үйлдэл хийнэ. Шинэ болон засах горимоос</td>
                    <td>CTRL+S</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Хадгалаад нэмэх</td>
                    <td>Хадгалаад нэмэх үйлдэл хийнэ.</td>
                    <td>CTRL+SHIFT+S</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Хадгалаад хэвлэх</td>
                    <td>Хадгалаад хэвлэх үйлдэл хийнэ.</td>
                    <td>CTRL+SHIFT+P</td>
                </tr>
                <?php
                if (!Config::getFromCache('CONFIG_IS_CLOSE_ON_ESCAPE')) {
                ?>
                <tr>
                    <td>4</td>
                    <td>Гарах/Хаах</td>
                    <td>Нээгдсэн байгаа идэвхтэй цонхыг хаана</td>
                    <td>SHIFT+Esc</td>
                </tr>
                <?php
                }
                ?>
                <tr>
                        <td>5</td>
                        <td>Шүүлтийн нөхцөл оруулах</td>
                        <td>Тайлан болон жагсаалтын Шүүлт хийх талбаруудыг харуулна</td>
                        <td>F5</td>
                </tr>
                <tr>
                        <td>6</td>
                        <td>Сэргээх</td>
                        <td>Жагсаалтыг сэргээнэ.</td>
                        <td>SHIFT+F4</td>
                </tr>
                <tr>
                        <td>6</td>
                        <td>Шүүлт хийх</td>
                        <td>Жагсаалт, тайлан дээр нөхцөлийн дагуу шүүнэ. Шүүлт товч</td>
                        <td>SHIFT+F5</td>
                </tr>
                <tr>
                        <td>7</td>
                        <td>Шинэ</td>
                        <td>
                                Жагсаалт дээр ажиллаж байх явцдаа шинэ горимыг дуудна. Тухайн цонхонд олон төрлийн шинэ процесс нэмэх сонголттой бол жижиг цонхонд шинэ процессуд гарч ирж аль нэгийг сонгоно
                        </td>
                        <td>ALT+N</td>
                </tr>
                <tr>
                        <td>8</td>
                        <td>Засах</td>
                        <td>Жагсаалтаас сонгосон мөрийг Засах горимд дуудна. Хэрэв олон засварын горимтой бол баруун товчны сонголт харагдаж түүнээс сонголт хийнэ.</td>
                        <td>Enter</td>
                </tr>
                <tr>
                        <td>9</td>
                        <td>Мөр шилжих</td>
                        <td>Жагсаалтын цонхонд (бүх төрлийн жагсаалт) дээш доош сумаар мөр шилжиж идэвхжүүлэх</td>
                        <td>Дээш сум, доош сум</td>
                </tr>
                <tr>
                        <td>
                                11
                        </td>
                        <td>
                                Popup дуудах
                        </td>
                        <td>
                                Жишээ нь, барааны код оруулах талбарт кодыг нь мэдэхгүй бол товч дарж барааны жагсаалтын цонх гаргаж ирэх
                        </td>
                        <td>
                                F2
                        </td>
                </tr>
                <tr>
                        <td>
                                12
                        </td>
                        <td>
                                Олноор нэмэх popup дуудна
                        </td>
                        <td>
                                Олноор нэмэх цонх дуудна
                        </td>
                        <td>
                                F3
                        </td>
                </tr>
                <tr>
                        <td>
                                13
                        </td>
                        <td>
                                Сагсанд нэмэх
                        </td>
                        <td>
                                Мөрөнд сонгосон утгуудыг сагсанд нэмэх
                        </td>
                        <td>
                                Enter
                        </td>
                </tr>
                <tr>
                        <td>
                                14
                        </td>
                        <td>
                                Сонгох
                        </td>
                        <td>
                                Popup жагсаалтны Сонгох товч ажиллана
                        </td>
                        <td>
                                Shift+F2
                        </td>
                </tr>
                <tr>
                        <td>
                                15
                        </td>
                        <td>
                                Мөр нэмэх
                        </td>
                        <td>
                                Процессийн цонхонд мөр нэмэх
                        </td>
                        <td>
                                CTRL++
                        </td>
                </tr>
                <tr>
                        <td>
                                16
                        </td>
                        <td>
                                Баганы дээд талын шүүлт рүү шилжих
                        </td>
                        <td>
                                Жагсаалтанд байгаа мөрнүүд дээр ажиллаж байгаад баганы шүүлтийн талбар руу шилжих үед ашиглана
                        </td>
                        <td>
                                Ctrl + Up (дээшээ сум)
                        </td>
                </tr>
                <tr>
                        <td>
                                17
                        </td>
                        <td>
                                Subgrid &ndash;тэй жагсаалтыг задлах
                        </td>
                        <td>
                                Урдаа нэмэх (+) товчтой мөрийг задлана
                        </td>
                        <td>
                                -
                        </td>
                </tr>
                <tr>
                        <td>
                                18
                        </td>
                        <td>
                                Subgrid &ndash;тэй жагсаалтыг хураах
                        </td>
                        <td>
                                Урдаа нэмэх (+) товчтой мөрийг хураана
                        </td>
                        <td>
                                +
                        </td>
                </tr>
                <tr>
                        <td>
                                19
                        </td>
                        <td>
                                Нээсэн олон таб дотор урагш хойш шилжих
                        </td>
                        <td>
                                Олон таб нээж ажиллаж байгаа үед гараас шууд шилжих боломжтой байх
                        </td>
                        <td>
                                Ctrl + Shift + баруун сум /зүүн сум/
                        </td>
                </tr>
                <tr>
                        <td>
                                20
                        </td>
                        <td>
                                Олон мөр сонгож доош Select хийх
                        </td>
                        <td>
                                Дараалласан олон мөр сонгох үед ашиглагдана
                        </td>
                        <td>
                                Shift + доош сум, дээш сум
                        </td>
                </tr>
                <tr>
                        <td>
                                21
                        </td>
                        <td>
                                Жагсаалтаас мөр устгах
                        </td>
                        <td>
                                Сонгосон мөрүүдийг устгана
                        </td>
                        <td>
                                Del
                        </td>
                </tr>
                <tr>
                        <td>
                                21
                        </td>
                        <td>
                                Процессын цонхноос мөр устгах
                        </td>
                        <td>
                                Сонгосон мөрүүдийг устгана
                        </td>
                        <td>
                                Shift + Del
                        </td>
                </tr>
                <tr>
                        <td>
                                22
                        </td>
                        <td>
                                Dtl -ийн олон мөр нэмэх
                        </td>
                        <td>
                                Нэг дор олон мөр оруулах үед ашиглана
                        </td>
                        <td>
                                Мөрийн тоо оруулаад Enter
                        </td>
                </tr>
                <tr>
                        <td>
                                23
                        </td>
                        <td>
                                Баримт хэвлэх, тайлан хэвлэх
                        </td>
                        <td>
                                Баримт болон тайлан хэвлэнэ
                        </td>
                        <td>
                                CTRL+P
                        </td>
                </tr>
                <tr>
                        <td>
                                24
                        </td>
                        <td>
                                GL мөр нэмэх
                        </td>
                        <td>
                                GL мөр нэмэх
                        </td>
                        <td>
                                Ctrl + M
                        </td>
                </tr>
                <tr>
                        <td>
                                25
                        </td>
                        <td>
                                GL мөр нэмэх
                        </td>
                        <td>
                                GL мөр нэмэх /дансгүй/
                        </td>
                        <td>
                                Ctrl + Shift + Up Arrow
                        </td>
                </tr>
                <tr>
                        <td>
                                26
                        </td>
                        <td>
                                Process Tab
                        </td>
                        <td>
                                Process Tab шилжих /зүүн/
                        </td>
                        <td>
                                Ctrl + Left Arrow
                        </td>
                </tr>
                <tr>
                        <td>
                                27
                        </td>
                        <td>
                                Process Tab
                        </td>
                        <td>
                                Process Tab шилжих /баруун/
                        </td>
                        <td>
                                Ctrl + Right Arrow
                        </td>
                </tr>
            </tbody>
        </table>
        </div>     
    </div>    
</div>

<style type="text/css">
table#hotkeys-tbl > tbody > tr > td:first-child {
    text-align: center;
}
</style>

<script type="text/javascript">
disableScrolling();

$(function() {
    $('div#fz-hotkeys').css({"maxHeight": '450px'});
    var $hotKeyTable = $('table', 'div#fz-hotkeys');
    $hotKeyTable.tableHeadFixer({'head': true, 'z-index': 9}); 
    
    var $hotKeyRows = $hotKeyTable.find('tbody > tr:visible');
    var $hotKeyRowsLen = $hotKeyRows.length, $hotKeyI = 0;
    for ($hotKeyI; $hotKeyI < $hotKeyRowsLen; $hotKeyI++) { 
        $($hotKeyRows[$hotKeyI]).find('td:first').text($hotKeyI + 1);
    }
});
</script>


