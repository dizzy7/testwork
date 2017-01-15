<div class="container">
    <div class="row">
        <?php foreach ($data['products'] as $product): ?>
            <div class="col-sm-4">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $product['title'] ?></div>
                    <div class="panel-body"><img src="<?php echo $product['image_url'] ?>" class="img-responsive"
                                                 style="width:100%" alt="Image"></div>
                    <div class="panel-footer">
                        Цена: <?php echo $product['price'] ?>
                        <form method="post" action="/addToCart">
                            <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
                            <button class="btn btn-default" type="submit">Купить</button>
                        </form>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

    <div class="row">
        <h2>Корзина</h2>
        <?php if ($data['cartData']['items']): ?>
            <form method="post" action="/cartEdit">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Количество</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data['cartData']['items'] as $row): ?>
                        <tr>
                            <td>
                                <?php echo $row['title'] ?>
                            </td>
                            <td><input name="product_<?php echo $row['id'] ?>" type="text"
                                       value="<?php echo $row['qty'] ?>">
                                <button type="button" class="btn btn-danger cart-remove">удалить</button>
                            </td>
                            <td><?php echo $row['price'] ?></td>
                            <td><?php echo $row['price'] * $row['qty'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Всего:</td>
                        <td><?php echo $data['cartData']['qty'] ?></td>
                        <td></td>
                        <td>
                            <?php if (isset($data['cartData']['sumWithoutDiscount'])): ?>
                                <s class="discount"><?php echo $data['cartData']['sumWithoutDiscount'] ?></s> <?php echo $data['cartData']['sum'] ?>
                            <?php else: ?>
                                <?php echo $data['cartData']['sum'] ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                <button type="submit" class="btn btn-warning">Пересчитать</button>
                <a href="/order" class="btn btn-success">Оформить</a>
            </form>
        <?php else: ?>
            Корзина пуста
        <?php endif; ?>
    </div>
</div><br>
