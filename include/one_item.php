<?
$sale = reset($data['Sales']);
?>
<form action="./form.php" method="POST" class="goods-form js-goods-form _one">
    <input type="hidden" name="data" value='<?= json_encode($data) ?>'>
    <!-- Товар 1 -->
    <div class="good-line-wrap js-good-line-wrap _open">
        <div class="good-line">
            <!--<div class="good-line__img-b">
                <img src="../img/_content/good_1.jpg" alt="" class="good-line__img">
            </div>-->
            <div class="good-line__info-1-b">
                <div class="good-line__info-1__text-1"><? if (!empty($sale->Brand)) { ?><?= $sale->Brand ?> / <? } ?><?= $sale->ModelName ?></div>
                <div class="good-line__info-1__text-2">Кол-во: <span class="js-good-amount">1</span> шт</div>
            </div>
            <div class="good-line__info-2-b">
                <div class="good-line__info-1__text-1 good-opt-ins">Продление гарантии на <span class="js-ins-length">3</span> года</div>
                <div class="good-line__info-1__text-1 good-opt-protect">Защита</div>
            </div>
            <div class="good-line__insurance-b js-good-ins-toggle">Выбрать страховку</div>
        </div>

        <? if (!empty($sale->ResultExt)) { ?>
            <!-- Товар 1 Продление гарантии -->
            <div class="good-opt-b good-opt-ins-b js-good-opt-b js-insurance" data-price="<?= $sale->ResultExt ?>">
                <div class="good-opt-b__toggle-b js-good-opt-toggle">
                    <div class="good-opt-b__toggle-b__slider"></div>
                    <div class="good-opt-b__toggle-b__text">Продление гарантии</div>
                </div>
                <div class="good-opt-b__radio-b">
                    <div class="good-opt__radio-item">
                        <input class="basic-radio js-opt-input" id="good_1_year_1" type="radio" name="good_1_ins_length" value="1" checked>
                        <label class="good-opt__radio-text" for="good_1_year_1">1 год</label>
                    </div>
                    <div class="good-opt__radio-item">
                        <input class="basic-radio js-opt-input" id="good_1_year_2" type="radio" name="good_1_ins_length" value="2">
                        <label class="good-opt__radio-text" for="good_1_year_2">2 года</label>
                    </div>
                    <div class="good-opt__radio-item">
                        <input class="basic-radio js-opt-input" id="good_1_year_3" type="radio" name="good_1_ins_length" value="3">
                        <label class="good-opt__radio-text" for="good_1_year_3">3 года</label>
                    </div>
                </div>
                <div class="opt-b-price">
                    <span class="opt-price-text js-opt-price"><?= number_format($sale->ResultExt, 0, '.', ' ') ?></span>
                    <span class="rub"> в</span>
                </div>
                <input type="hidden" name="good-1-insurance" value="0" class="js-opt-input-hidden">
            </div>
        <? } ?>
        <? if (!empty($sale->ResultSav)) { ?>
            <!-- Товар 1 Защита -->
            <div class="good-opt-b good-opt-portect-b js-good-opt-b js-protect" data-price="<?= $sale->ResultSav ?>">
                <div class="good-opt-b__toggle-b js-good-opt-toggle">
                    <div class="good-opt-b__toggle-b__slider"></div>
                    <div class="good-opt-b__toggle-b__text">Защита</div>
                </div>
                <div class="opt-b-price">
                    <span class="opt-price-text js-opt-price"><?= number_format($sale->ResultSav, 0, '.', ' ') ?></span>
                    <span class="rub"> в</span>
                </div>
                <input type="hidden" name="good-1-protection" class="js-opt-input-hidden" value="0">
            </div>
        <? } ?>
    </div>

    <!-- Оформить -->
    <div class="goods-separate-send-b">
        <button type="submit" class="goods-separate-send">Оформить страховку</button>
        <div class="goods-send-text">Общая стоимость страховки:</div>
        <div class="goods-send__final-price">
            <span class="js-final-price">0</span>
            <span class="rub"> в</span>
        </div>
    </div>
    <input type="hidden" name="price-total" class="js-price-total" value="0">
</form>