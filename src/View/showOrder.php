<div class="container">
    <div class="row">
        <h2>Заказ №<?php echo $data['order']['id'] ?> от <?php echo $data['order']['created_at'] ?> на
            сумму <?php echo $data['order']['sum'] ?></h2>

        <hr>

        Имя: <?php echo $data['order']['name'] ?><br>
        Email: <?php echo $data['order']['email'] ?><br>
        Телефон: <?php echo $data['order']['phone'] ?><br>
        Карта: <?php echo $data['order']['card'] ?><br>
        CVV: <?php echo $data['order']['cvv'] ?><br>
    </div>

    <div class="row">
        <h2>Состав заказа</h2>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Товар</th>
                <th>Количество</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($data['products'] as $row): ?>
                <tr>
                    <td><?php echo $row['title'] ?></td>
                    <td><?php echo $row['qty'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div><br>
