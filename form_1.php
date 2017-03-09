<?php
/**
 * @var $data object
 */
 error_reporting(-1);
ini_set('display_errors', 'On');
echo "test";

$html_class = 'form-page js-form-page';
 print $_REQUEST['data'];
$data = json_decode($_REQUEST['data']);



$sales = @json_decode($_REQUEST['sales']);
$sales = empty($sales) ? $data->Sales : $sales;

$city = $data->Head->AdrReg->Locality;
$street = $data->Head->AdrReg->Street;
$house = $data->Head->AdrReg->House;
$corp = $data->Head->AdrReg->Corp;
$flat = $data->Head->AdrReg->Flat;
$address = $city . (empty($street) ? '' : ', ' . $street) . (empty($house) ? '' : ' ' . $house) . (empty($corp) ? '' : ', к. ' . $corp) . (empty($flat) ? '' : ', кв. ' . $flat);

header('Content-Type: application/json');
ob_start();
print_r( $data);

?>

   <h1>Офомите страховой полис для приобретенных товаров</h1>

    <!-- Форма каждого товара-->
    <div class="goods-form">
        <div class="info-line">
            <div class="info-line__text-1">Вы страхуете следующие товары</div>
            <div class="info-line__text-2">Номер интернет заказа на приобретение техники: <?= $data->Head->OrderNum ?></div>
        </div>
        <!-- Товар 1 -->
        <? foreach ($data->Sales as $key => $sale) { ?>
            <? $index = $key + 1; ?>
            <? $insurance = $_REQUEST['good-' . $index . '-insurance'] || $_REQUEST['all_goods_insurance']; ?>
            <? $protection = $_REQUEST['good-' . $index . '-protection'] || $_REQUEST['all_goods_protection']; ?>
            <? $ins_length = empty($_REQUEST['good_' . $index . '_ins_length']) ? $_REQUEST['all_goods_ins_length'] : $_REQUEST['good_' . $index . '_ins_length'] ?>
            <div class="good-line-wrap js-good-line-wrap<? if ($insurance) { ?> _ins<? } ?><? if ($protection) { ?> _protect<? } ?>">
                <div class="good-line">
                    <!--<div class="good-line__img-b">
                        <img src="/img/_content/good_2.jpg" alt="" class="good-line__img">
                    </div>-->
                    <div class="good-line__info-1-b">
                        <div class="good-line__info-1__text-1"><?= $sale->Brand ?> / <?= $sale->ModelName ?></div>
                        <div class="good-line__info-1__text-2">Кол-во: <span class="js-good-amount"><?= empty($_REQUEST['good_' . $index . '_amount']) ? $sale->Quantity : $_REQUEST['good_' . $index . '_amount'] ?></span> шт</div>
                    </div>
                    <div class="good-line__fake-b"></div>
                    <div class="good-line__info-2-b">
                        <div class="good-line__info-1__text-1 good-opt-ins">Продление гарантии на <span class="js-ins-length"><?= $ins_length ?></span> год<? if ($ins_length > 1) { ?>а<? } ?></div>
                        <? if ($protection) { ?>
                            <div class="good-line__info-1__text-1 good-opt-protect">Защита</div>
                        <? } ?>
                    </div>
                    <? if (empty($_REQUEST['good-' . $index . '-price']) && count($data->Sales) > 1) {

                    } else { ?>
                        <div class="good-line__price-b">
                            <div class="good-line__price-text">
                                <span class="good-line__price"><?= number_format(empty($_REQUEST['good-' . $index . '-price']) ? $_REQUEST['price-total'] : $_REQUEST['good-' . $index . '-price'] , 0, '.', ' ') ?></span>
                                <span class="rub"> в</span>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>
    </div>

    <form action="policy.php" method="GET" class="order-form js-order-form _only-step">  <!-- _only-step -->
        <input type="hidden" name="price-total" value="<?= $_REQUEST['price-total'] ?>">
        <input type="hidden" name="data" value='<?= json_encode($data) ?>'>
        <input type="hidden" name="sales" value='<?= json_encode($sales) ?>'>
        <!-- Блок 1 -->
        <div class="order-form__step-1">
            <div class="order-form__step-title">Информация о покупателе</div>
            <div class="order-form__step-info"><span>*</span> Обязательные для заполнения поля </div>
            <!-- Фамилия -->
            <div class="order-form-item _required js-form-item">
                <label for="last_name" class="form-label">Фамилия</label>
                <input id="last_name" name="last_name" type="text" class="form-input js-form-input js-required" data-type="name" value="<?= $data->Head->LName ?>">
            </div>
            <!-- Имя -->
            <div class="order-form-item _required js-form-item">
                <label for="first_name" class="form-label">Имя</label>
                <input id="first_name" name="first_name" type="text" class="form-input js-form-input js-required" data-type="name" value="<?= $data->Head->FName ?>">
            </div>
            <!-- Отчество -->
            <div class="order-form-item js-form-item">
                <label for="middle_name" class="form-label">Отчество</label>
                <input id="middle_name" name="middle_name" type="text" class="form-input js-form-input" data-type="text" value="<?= $data->Head->PName ?>">
            </div>
            <!-- Дата рождения -->
            <div class="order-form-item js-form-item">
                <label class="form-label">Дата рождения</label>
                <div class="order-form-item__content-wrap">
                    <!-- Селект День -->
                    <div class="basic-select js-basic-select" data-type="b-day">
                        <div class="basic-select__selected js-basic-select-selected">День</div>
                        <div class="basic-select__opts-wrap">
                            <div class="basic-select__opts-scroller js-select-scroller">
                                <? for ($day = 1; $day < 32; $day++) { ?>
                                    <div class="basic-select__opt js-basic-select-opt" data-val="<?= $day ?>"><?= $day ?></div>
                                <? } ?>
                            </div>
                            <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                                <div class="basic-select__bar js-select-scroller-bar"></div>
                            </div>
                        </div>
                        <input type="hidden" name="birth_day" class="js-basic-select-input js-form-input" value="">
                    </div>

                    <!-- Селект Месяц -->
                    <div class="basic-select js-basic-select" data-type="b-month">
                        <div class="basic-select__selected js-basic-select-selected">Месяц</div>
                        <div class="basic-select__opts-wrap">
                            <div class="basic-select__opts-scroller js-select-scroller">
                                <div class="basic-select__opt js-basic-select-opt" data-val="1">январь</div>  <!-- _selected -->
                                <div class="basic-select__opt js-basic-select-opt" data-val="2">февраль</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="3">март</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="4">апрель</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="5">май</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="6">июнь</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="7">июль</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="8">август</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="9">сентябрь</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="10">октябрь</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="11">ноябрь</div>
                                <div class="basic-select__opt js-basic-select-opt" data-val="12">декабрь</div>
                            </div>
                            <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                                <div class="basic-select__bar js-select-scroller-bar"></div>
                            </div>
                        </div>
                        <input type="hidden" name="birth_month" class="js-basic-select-input js-form-input" value="">
                    </div>

                    <!-- Селект Год -->
                    <div class="basic-select js-basic-select" data-type="b-year">
                        <div class="basic-select__selected js-basic-select-selected">Год</div>
                        <div class="basic-select__opts-wrap">
                            <div class="basic-select__opts-scroller js-select-scroller">
                                <? for ($year = date('Y'); $year >= (date('Y') - 100); $year--) { ?>
                                    <div class="basic-select__opt js-basic-select-opt" data-val="<?= $year ?>"><?= $year ?></div>
                                <? } ?>
                            </div>
                            <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                                <div class="basic-select__bar js-select-scroller-bar"></div>
                            </div>
                        </div>
                        <input type="hidden" name="birth_year" class="js-basic-select-input js-form-input" value="">
                    </div>
                </div>
            </div>
            <!-- Телефон -->
            <div class="order-form-item _required js-form-item">
                <label for="phone" class="form-label">Телефон</label>
                <input id="phone" name="phone" type="text" class="form-input js-form-input js-required js-phone" data-type="phone" value="<?= $data->Head->Telephone ?>">
            </div>
            <!-- E-mail -->
            <div class="order-form-item _required js-form-item">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" name="email" type="text" class="form-input js-form-input js-required" data-type="email" value="<?= $data->Head->Email ?>">
            </div>

            <? if ($_REQUEST['price-total'] > 15000) { ?>
                <!-- Блок 2 -->
                <div class="order-form__step-2">
                    <div class="order-form__step-title">Паспортные данные</div>
                    <!-- Серия и номер -->
                    <div class="order-form-item js-form-item">
                        <label for="pass_series" class="form-label">Серия и номер</label>
                        <div class="order-form-item__content-wrap">
                            <input id="pass_series" name="pass_series" type="text" class="form-input _pass-series js-form-input js-pass-series" data-type="text" value="<?= $data->Head->PassportSerial ?>">
                            <div class="pass-input-separator"></div>
                            <input id="pass_number" name="pass_number" type="text" class="form-input _pass-number js-form-input js-pass-number" data-type="text" value="<?= $data->Head->PassportNumber ?>">
                        </div>
                    </div>
                    <!-- Дата выдачи -->
                    <div class="order-form-item js-form-item">
                        <label class="form-label">Дата выдачи</label>
                        <div class="order-form-item__content-wrap">
                            <!-- Селект День -->
                            <div class="basic-select js-basic-select" data-type="b-day">
                                <div class="basic-select__selected js-basic-select-selected">День</div>
                                <div class="basic-select__opts-wrap">
                                    <div class="basic-select__opts-scroller js-select-scroller">
                                        <? for ($day = 1; $day < 32; $day++) { ?>
                                            <div class="basic-select__opt js-basic-select-opt" data-val="<?= $day ?>"><?= $day ?></div>
                                        <? } ?>
                                    </div>
                                    <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                                        <div class="basic-select__bar js-select-scroller-bar"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="pass_day" class="js-basic-select-input js-form-input" value="">
                            </div>

                            <!-- Селект Месяц -->
                            <div class="basic-select js-basic-select" data-type="b-month">
                                <div class="basic-select__selected js-basic-select-selected">Месяц</div>
                                <div class="basic-select__opts-wrap">
                                    <div class="basic-select__opts-scroller js-select-scroller">
                                        <div class="basic-select__opt js-basic-select-opt" data-val="1">январь</div>  <!-- _selected -->
                                        <div class="basic-select__opt js-basic-select-opt" data-val="2">февраль</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="3">март</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="4">апрель</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="5">май</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="6">июнь</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="7">июль</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="8">август</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="9">сентябрь</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="10">октябрь</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="11">ноябрь</div>
                                        <div class="basic-select__opt js-basic-select-opt" data-val="12">декабрь</div>
                                    </div>
                                    <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                                        <div class="basic-select__bar js-select-scroller-bar"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="pass_month" class="js-basic-select-input js-form-input" value="">
                            </div>

                            <!-- Селект Год -->
                            <div class="basic-select js-basic-select" data-type="b-year">
                                <div class="basic-select__selected js-basic-select-selected">Год</div>
                                <div class="basic-select__opts-wrap">
                                    <div class="basic-select__opts-scroller js-select-scroller">
                                        <? for ($year = date('Y'); $year >= (date('Y') - 100); $year--) { ?>
                                            <div class="basic-select__opt js-basic-select-opt" data-val="<?= $year ?>"><?= $year ?></div>
                                        <? } ?>
                                    </div>
                                    <div class="basic-select__bar-wrap js-select-scroller-bar-wrap">
                                        <div class="basic-select__bar js-select-scroller-bar"></div>
                                    </div>
                                </div>
                                <input type="hidden" name="pass_year" class="js-basic-select-input js-form-input" value="">
                            </div>
                        </div>
                    </div>
                    <!-- Наименование подразделения выдавшего документ -->
                    <div class="order-form-item js-form-item">
                        <label for="pass_giver" class="form-label _pass-giver-label">Наименование<br>подразделения<br>выдавшего документ</label>
                        <input id="pass_giver" name="pass_giver" type="text" class="form-input js-form-input" data-type="text" value="<?= $data->Head->PassportPdrName ?>">
                    </div>
                    <!-- Код подразделения выдавшего документ -->
                    <div class="order-form-item js-form-item _pass-dep-item">
                        <label for="pass_dep" class="form-label _pass-dep-label">Код подразделения<br>выдавшего документ</label>
                        <input id="pass_dep" name="pass_dep" type="text" class="form-input js-form-input js-pass-dep" data-type="text" value="<?= $data->Head->PassportPdrCode ?>">
                    </div>
                </div>

                <!-- Блок 3 -->
                <div class="order-form__step-3">
                    <div class="order-form__step-title">Адрес регистрации</div>
                    <!-- Индекс -->
                    <div class="order-form-item js-form-item">
                        <label for="index" class="form-label">Индекс</label>
                        <input id="index" name="index" type="text" class="form-input js-form-input js-pass-index" data-type="text" value="<?= $data->Head->AdrReg->PIndex ?>">
                    </div>
                    <!-- Адрес -->
                    <div class="order-form-item js-form-item">
                        <label for="address" class="form-label">Адрес</label>
                        <input id="address" name="address" type="text" class="form-input js-form-input js-suggestions" data-type="text" autocomplete="off" value="<?= $address ?>">
                    </div>
                </div>
            <? } ?>
        </div>

        <!-- Оформить -->
        <div class="goods-separate-send-b">
            <button type="submit" class="goods-separate-send">Оплатить страховку</button>
            <div class="goods-send-text">Общая стоимость страховки:</div>
            <div class="goods-send__final-price">
                <span class="js-final-price"><?= number_format($_REQUEST['price-total'], 0, '.', ' ') ?></span>
                <span class="rub"> в</span>
            </div>
        </div>
    </form>
