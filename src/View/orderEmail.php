Создан заказ. Общая сумма: <?php echo $data['cart']['sum']?>

Данные заказа:

Имя: <?php echo $data['form']['name']?>

Email: <?php echo $data['form']['email']?>

Телефон: <?php echo $data['form']['phone']?>

Карта: <?php echo $data['form']['card']?>

CVV: <?php echo $data['form']['cvv']?>

Заказ:
<?php foreach ($data['cart']['items'] as $item):?>
    <?php echo $item['title']?>, количество <?php echo $item['qty']?>

<?php endforeach;?>
Ссылка для просмотра заказа: <?php echo \Application::$request->getSchemeAndHttpHost() . '/show?id=' . $data['orderId'] ?>
