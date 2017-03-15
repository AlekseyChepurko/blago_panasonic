<?php
/**
 * @var $data array
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);
 
$html_class = 'main-page js-main-page';
?>
<?php require_once('include/template/header.php'); ?>
<?php
try {
    require_once('include/calculations/service.php');?>
    <h1>Офомите страховой полис для приобретенных товаров</h1>

    <? if (count($data['Sales']) > 1) { ?>
        <? require_once('include/many_items.php') ?>
    <? } else { ?>
        <? require_once('include/one_item.php') ?>
    <? } ?>
<?php
} catch (Exception $e) {
    echo '<h1>' . $e->getMessage() . '</h1>';
} ?>
<?php require_once('include/template/footer.php'); ?>