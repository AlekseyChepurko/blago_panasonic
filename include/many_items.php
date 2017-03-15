<?php
/**
 * @var $data array
 * @var $sale object
 * @var $sales array
 */
?>
<div class="linked-btns-b">
    <div class="linked-btn js-linked-btn" data-type="all">Cтрахование всех товаров</div>
    <div class="linked-btn js-linked-btn _active" data-type="separate">Cтрахование для каждого товара</div>
</div>

<!-- Форма всех товаров -->
<form action="./form.php" method="POST" class="goods-form _all js-goods-form _many">
    <input type="hidden" name="data" value='<?= json_encode($data) ?>'>
    <input type="hidden" name="sales" value='<?= json_encode($sales) ?>'>
    <!-- Товар -->
    <div class="good-line-wrap js-good-line-wrap _open">
        <div class="good-line">
            <div class="all-goods-line__info-1-b">
                <? foreach ($data['Sales'] as $sale) { ?>
                    <div class="good-line__info-1__text-1"><? if (!empty($sale->Brand)) { ?><?= $sale->Brand ?> / <? } ?><?= $sale->ModelName ?></div>
                <? } ?>
            </div>
            <div class="all-goods-line__info-2-b">
                <? foreach ($data['Sales'] as $sale) { ?>
                    <div class="good-line__info-1__text-2">Кол-во: <span class="js-good-amount"><?= $sale->Quantity ?></span> шт</div>
                <? } ?>
            </div>
        </div>

        <? if ($ext_total > 0) { ?>
            <!-- Товар Продление гарантии -->
            <div class="good-opt-b good-opt-ins-b js-good-opt-b js-insurance" data-price="<?= $ext_total ?>">
                <div class="good-opt-b__toggle-b js-good-opt-toggle">
                    <div class="good-opt-b__toggle-b__slider"></div>
                    <div class="good-opt-b__toggle-b__text">Продление гарантии</div>
                </div>
                <div class="good-opt-b__radio-b">
                    <? foreach($years as $key => $year) { ?>
                        <div class="good-opt__radio-item">
                            <input class="basic-radio js-opt-input" id="all_goods_year_<?= $year ?>" type="radio" name="all_goods_ins_length" value="<?= $year ?>"<? if (!$key) { ?> checked<? } ?>>
                            <label class="good-opt__radio-text" for="all_goods_year_<?= $year ?>"><?= $year ?> год<? if ($year > 1) { ?>а<? } ?></label>
                        </div>
                    <? } ?>
                </div>
                <div class="opt-b-price">
                    <span class="opt-price-text js-opt-price"><?= $ext_total ?></span>
                    <span class="rub"> в</span>
                </div>
                <input type="hidden" name="all_goods_insurance" value="0" class="js-opt-input-hidden">
            </div>
        <? } ?>
        <? if ($sav_total > 0) { ?>
            <!-- Товар Защита -->
            <div class="good-opt-b good-opt-portect-b js-good-opt-b js-protect" data-price="<?= $sav_total ?>">
                <div class="good-opt-b__toggle-b js-good-opt-toggle">
                    <div class="good-opt-b__toggle-b__slider"></div>
                    <div class="good-opt-b__toggle-b__text">Защита</div>
                </div>
                <div class="opt-b-price">
                    <span class="opt-price-text js-opt-price"><?= $sav_total ?></span>
                    <span class="rub"> в</span>
                </div>
                <input type="hidden" name="all_goods_protection" class="js-opt-input-hidden" value="0">
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

<!-- Форма каждого товара-->
<form action="./form.php" method="POST" class="goods-form _separate js-goods-form _many">
    <input type="hidden" name="data" value='<?= json_encode($data) ?>'>
    <input type="hidden" name="sales" value='<?= json_encode($sales) ?>'>
    <? foreach ($data['Sales'] as $key => $sale) { ?>
        <!-- Товар -->
        <div class="good-line-wrap js-good-line-wrap">
            <div class="good-line">
                <!--<div class="good-line__img-b">
                    <img src="../img/_content/good_1.jpg" alt="" class="good-line__img">
                </div>-->
                <div class="good-line__info-1-b">
                    <div class="good-line__info-1__text-1"><? if (!empty($sale->Brand)) { ?><?= $sale->Brand ?> / <? } ?><?= $sale->ModelName ?></div>
                    <div class="good-line__info-1__text-2">Кол-во: <span class="js-good-amount"><?= $sale->Quantity ?></span> шт</div>
                </div>
                <div class="good-line__info-2-b">
                    <div class="good-line__info-1__text-1 good-opt-ins">Продление гарантии на <span class="js-ins-length">3</span> года
                    </div>
                    <div class="good-line__info-1__text-1 good-opt-protect">Защита</div>
                </div>
                <div class="good-line__insurance-b js-good-ins-toggle">Выбрать страховку</div>
            </div>

            <!-- Товар Продление гарантии -->
            <div class="good-opt-b good-opt-ins-b js-good-opt-b js-insurance" data-price="<?= isset($sale->ResultExt) ? $sale->ResultExt : 0 ?>">
                <div class="good-opt-b__toggle-b js-good-opt-toggle">
                    <div class="good-opt-b__toggle-b__slider"></div>
                    <div class="good-opt-b__toggle-b__text">Продление гарантии</div>
                </div>
                <div class="good-opt-b__radio-b">
                    <div class="good-opt__radio-item">
                        <input class="basic-radio js-opt-input" id="good_<?= $key + 1 ?>_year_1" type="radio" name="good_<?= $key + 1 ?>_ins_length" value="1" checked>
                        <label class="good-opt__radio-text" for="good_<?= $key + 1 ?>_year_1">1 год</label>
                    </div>
                    <div class="good-opt__radio-item">
                        <input class="basic-radio js-opt-input" id="good_<?= $key + 1 ?>_year_2" type="radio" name="good_<?= $key + 1 ?>_ins_length" value="2">
                        <label class="good-opt__radio-text" for="good_<?= $key + 1 ?>_year_2">2 года</label>
                    </div>
                    <div class="good-opt__radio-item">
                        <input class="basic-radio js-opt-input" id="good_<?= $key + 1 ?>_year_3" type="radio" name="good_<?= $key + 1 ?>_ins_length" value="3">
                        <label class="good-opt__radio-text" for="good_<?= $key + 1 ?>_year_3">3 года</label>
                    </div>
                </div>

                <!-- Селект количество -->
                <div class="basic-select js-basic-select">
                    <div class="basic-select__selected js-basic-select-selected"><?= $sale->Quantity ?> шт</div>
                    <div class="basic-select__opts-wrap">
                        <div class="basic-select__opts-scroller js-select-scroller">
                            <? for ($i = 1; $i <= $sale->Quantity; $i++) { ?>
                                <div class="basic-select__opt js-basic-select-opt<? if ($i == $sale->Quantity) { ?> _selected<? } ?>" data-val="<?= $i ?>"><?= $i ?> шт</div>
                            <? } ?>
                        </div>
                        <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                            <div class="basic-select__bar js-select-scroller-bar"></div>
                        </div>
                    </div>
                    <input type="hidden" name="good_<?= $key + 1 ?>_amount" class="js-basic-select-input" value="1">
                </div>

                <div class="opt-b-price">
                    <span class="opt-price-text js-opt-price"><?= number_format(isset($sale->ResultExt) ? $sale->ResultExt : 0, 0, '.', ' ') ?></span>
                    <span class="rub"> в</span>
                </div>
                <input type="hidden" name="good-<?= $key + 1 ?>-insurance" value="0" class="js-opt-input-hidden">
            </div>
            <!-- Товар Защита -->
            <? if (!empty($sale->ResultSav)) { ?>
                <div class="good-opt-b good-opt-portect-b js-good-opt-b js-protect" data-price="<?= (isset($sale->ResultSav) ? $sale->ResultSav : 0) * $sale->Quantity ?>">
                    <div class="good-opt-b__toggle-b js-good-opt-toggle">
                        <div class="good-opt-b__toggle-b__slider"></div>
                        <div class="good-opt-b__toggle-b__text">Защита</div>
                    </div>
                    <div class="opt-b-price">
                        <span class="opt-price-text js-opt-price"><?= number_format((isset($sale->ResultSav) ? $sale->ResultSav : 0) * $sale->Quantity, 0, '.', ' ') ?></span>
                        <span class="rub"> в</span>
                    </div>
                    <input type="hidden" name="good-<?= $key + 1 ?>-protection" class="js-opt-input-hidden" value="0">
                </div>
            <? } ?>
            <input type="hidden" name="good-<?= $key + 1 ?>-price" value="0" class="js-good-price">
        </div>
    <? } ?>

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