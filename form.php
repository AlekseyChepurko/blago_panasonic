<?php
/**
 * @var $data object
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

$html_class = 'form-page js-form-page';
$all_data = array_merge($_REQUEST, $_POST);
$data = json_decode($all_data['data']);
$sales = @json_decode($all_data['sales']);
$sales = empty($sales) ? $data->Sales : $sales;

$city = !empty($data->Head->AdrReg->Locality) ? $data->Head->AdrReg->Locality : '';
$street = !empty($data->Head->AdrReg->Street) ? $data->Head->AdrReg->Street : '';
$house = !empty($data->Head->AdrReg->House) ? $data->Head->AdrReg->House : '';
$corp = !empty($data->Head->AdrReg->Corp) ? $data->Head->AdrReg->Corp : '';
$flat = !empty($data->Head->AdrReg->Flat) ? $data->Head->AdrReg->Flat : '';
$address = $city . (empty($street) ? '' : ', ' . $street) . (empty($house) ? '' : ' ' . $house) . (empty($corp) ? '' : ', к. ' . $corp) . (empty($flat) ? '' : ', кв. ' . $flat);

$filled_data = (array)$data->Head;

header('Content-Type: application/json');
ob_start();
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
            <? $insurance = !empty($all_data['good-' . $index . '-insurance']) || !empty($all_data['all_goods_insurance']); ?>
            <? $protection = !empty($all_data['good-' . $index . '-protection']) || !empty($all_data['all_goods_protection']); ?>
            <? $ins_length = empty($all_data['good_' . $index . '_ins_length']) ? $all_data['all_goods_ins_length'] : $all_data['good_' . $index . '_ins_length'] ?>
            <div class="good-line-wrap js-good-line-wrap<? if ($insurance) { ?> _ins<? } ?><? if ($protection) { ?> _protect<? } ?>">
                <div class="good-line">
                    <!--<div class="good-line__img-b">
                        <img src="/img/_content/good_2.jpg" alt="" class="good-line__img">
                    </div>-->
                    <div class="good-line__info-1-b">
                        <div class="good-line__info-1__text-1"><? if (!empty($sale->Brand)) { ?><?= $sale->Brand ?> / <? } ?><?= $sale->ModelName ?></div>
                        <div class="good-line__info-1__text-2">Кол-во: <span class="js-good-amount"><?= empty($all_data['good_' . $index . '_amount']) ? $sale->Quantity : $all_data['good_' . $index . '_amount'] ?></span> шт</div>
                    </div>
                    <div class="good-line__fake-b"></div>
                    <div class="good-line__info-2-b">
                        <div class="good-line__info-1__text-1 good-opt-ins">Продление гарантии на <span class="js-ins-length"><?= $ins_length ?></span> год<? if ($ins_length > 1) { ?>а<? } ?></div>
                        <? if ($protection) { ?>
                            <div class="good-line__info-1__text-1 good-opt-protect">Защита</div>
                        <? } ?>
                    </div>
                    <? if (empty($all_data['good-' . $index . '-price']) && count($data->Sales) > 1) {

                    } else { ?>
                        <div class="good-line__price-b">
                            <div class="good-line__price-text">
                                <span class="good-line__price"><?= number_format(empty($all_data['good-' . $index . '-price']) ? $all_data['price-total'] : $all_data['good-' . $index . '-price'] , 0, '.', ' ') ?></span>
                                <span class="rub"> в</span>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>
    </div>

    <form action="./policy.php" method="POST" class="order-form js-order-form _only-step">  <!-- _only-step -->
        <input type="hidden" name="price-total" value="<?= $all_data['price-total'] ?>">
        <input type="hidden" name="all_data" value='<?= json_encode($all_data) ?>'>
        <!-- Блок 1 -->
        <div class="order-form__step-1">
            <div class="order-form__step-title">Информация о покупателе</div>
            <div class="order-form__step-info"><span>*</span> Обязательные для заполнения поля </div>
            <!-- Фамилия -->
            <div class="order-form-item _required js-form-item<? if (!empty($filled_data['LName'])) { ?> _ok<? } ?>">
                <label for="last_name" class="form-label">Фамилия</label>
                <input id="last_name" name="last_name" type="text" class="form-input js-form-input js-required" data-type="name" value="<?= !empty($filled_data['LName']) ? $filled_data['LName'] : '' ?>">
            </div>
            <!-- Имя -->
            <div class="order-form-item _required js-form-item<? if (!empty($filled_data['FName'])) { ?> _ok<? } ?>">
                <label for="first_name" class="form-label">Имя</label>
                <input id="first_name" name="first_name" type="text" class="form-input js-form-input js-required" data-type="name" value="<?= !empty($filled_data['FName']) ? $filled_data['FName'] : '' ?>">
            </div>
            <!-- Отчество -->
            <div class="order-form-item js-form-item">
                <label for="middle_name" class="form-label">Отчество</label>
                <input id="middle_name" name="middle_name" type="text" class="form-input js-form-input" data-type="text" value="<?= !empty($filled_data['PName']) ? $filled_data['PName'] : '' ?>">
            </div>
            <!-- Дата рождения -->
            <div class="order-form-item _required">
                <label class="form-label">Дата рождения</label>
                <div class="order-form-item__content-wrap">
                    <!-- Селект День -->
                    <div class="basic-select js-basic-select js-form-input _required js-form-item"  data-type="b-day">
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
                        <input type="hidden" name="birth_day" class="js-basic-select-input js-required js-form-input" data-type="b-day">
                    </div>

                    <!-- Селект Месяц -->
                    <div class="basic-select js-basic-select js-form-input _required js-form-item" data-type="b-month">
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
                        <input type="hidden" name="birth_month"  class="js-basic-select-input js-required js-form-input" data-type="b-month">
                    </div>

                    <!-- Селект Год -->
                    <div class="basic-select js-basic-select js-form-input _required js-form-item" data-type="b-year">
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
                        <input type="hidden" name="birth_year" class="js-basic-select-input js-required js-form-input" data-type="b-year">
                    </div>
                </div>
            </div>
            <!-- Телефон -->
            <div class="order-form-item _required js-form-item<? if (!empty($filled_data['Telephone'])) { ?> _ok<? } ?>">
                <label for="phone" class="form-label">Телефон</label>
                <input id="phone" name="phone" type="text" class="form-input js-form-input js-required js-phone" data-type="phone" value="<?= !empty($filled_data['Telephone']) ? $filled_data['Telephone'] : '' ?>">
            </div>
            <!-- E-mail -->
            <div class="order-form-item _required js-form-item<? if (!empty($filled_data['Email'])) { ?> _ok<? } ?>">
                <label for="email" class="form-label">E-mail</label>
                <input id="email" name="email" type="text" class="form-input js-form-input js-required" data-type="email" value="<?= !empty($filled_data['Email']) ? $filled_data['Email'] : '' ?>">
            </div>

            <? if ($all_data['price-total'] > 15000) { ?>
                <!-- Блок 2 -->
                <div class="order-form__step-2">
                    <div class="order-form__step-title">Паспортные данные</div>
                    <!-- Серия и номер -->
                    <div class="order-form-item js-form-item">
                        <label for="pass_series" class="form-label">Серия и номер</label>
                        <div class="order-form-item__content-wrap">
                            <input id="pass_series" name="pass_series" type="text" class="form-input _pass-series js-form-input js-pass-series" data-type="text" value="<?= !empty($filled_data['PassportSerial']) ? $filled_data['PassportSerial'] : '' ?>">
                            <div class="pass-input-separator"></div>
                            <input id="pass_number" name="pass_number" type="text" class="form-input _pass-number js-form-input js-pass-number" data-type="text" value="<?= !empty($filled_data['PassportNumber']) ? $filled_data['PassportNumber'] : '' ?>">
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
                        <input id="pass_giver" name="pass_giver" type="text" class="form-input js-form-input" data-type="text" value="<?= !empty($filled_data['PassportPdrName']) ? $filled_data['PassportPdrName'] : '' ?>">
                    </div>
                    <!-- Код подразделения выдавшего документ -->
                    <div class="order-form-item js-form-item _pass-dep-item">
                        <label for="pass_dep" class="form-label _pass-dep-label">Код подразделения<br>выдавшего документ</label>
                        <input id="pass_dep" name="pass_dep" type="text" class="form-input js-form-input js-pass-dep" data-type="text" value="<?= !empty($filled_data['PassportPdrCode']) ? $filled_data['PassportPdrCode'] : '' ?>">
                    </div>
                </div>

                <!-- Блок 3 -->
                <div class="order-form__step-3">
                    <div class="order-form__step-title">Адрес регистрации</div>
                    <!-- Индекс -->
                    <div class="order-form-item js-form-item">
                        <label for="index" class="form-label">Индекс</label>
                        <input id="index" name="index" type="text" class="form-input js-form-input js-pass-index" data-type="text" value="<?= !empty($filled_data['AdrReg']->PIndex) ? $filled_data['AdrReg']->PIndex : '' ?>">
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
                <span class="js-final-price"><?= number_format($all_data['price-total'], 0, '.', ' ') ?></span>
                <span class="rub"> в</span>
            </div>
        </div>
    </form>
<? $html = ob_get_clean();
echo json_encode(array(
    'success' => true,
    'class' => $html_class,
    'html' => $html,
))
?>