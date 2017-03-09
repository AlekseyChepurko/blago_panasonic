<?php require_once('include/template/header.php'); ?>
<h1>Ошибка при совершении оплаты</h1>

<div class="payment-msg-b _error">
    <div class="payment-msg__content-b">
        <img src="./img/svg/error.svg" alt="" class="payment-msg__ok-icon">
        <div class="payment-msg__text-1">Не удалось оплатить страховку</div>
        <div class="payment-msg__text-2">Пожалуйста, повторите оплату</div>
    </div>
    <div class="payment-msg__repay-b">
        <a href="./send.php<? if (!empty($_REQUEST['data'])) { ?>?data=<?= $_REQUEST['data'] ?><? } ?>" class="payment-msg__repay-btn">Повторить попытку</a>
    </div>
</div>
<?php require_once('include/template/footer_simple.php'); ?>