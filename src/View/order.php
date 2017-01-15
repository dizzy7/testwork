<div class="container">
    <div class="row">
        <h2>Корзина</h2>
        <?php if ($data['cartData']['items']): ?>
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
                        <td><?php echo $row['qty'] ?></td>
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
            <?php if (isset($data['cartData']['bonus'])): ?>
                Применён бонус: <?php echo $data['cartData']['bonus']['code'] ?> (<?php echo $data['cartData']['bonus']['discount_percent'] ?>%)
            <?php else: ?>
            <form method="post" action="/applyBonus">
                <label for="bonus"">Бонус-код</label>
                <input type="text"  id="bonus" name="bonus">
                <button type="submit" class="btn btn-success">Применить</button>
            </form>
            <?php endif; ?>

            <h3>Оформление заказа</h3>
            <div class="errors">
                <?php echo implode('<br>', $data['errors']) ?>
            </div>
            <form method="post" action="/order">
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input class="form-control" type="text" id="name" name="order[name]" value="<?php echo \Application::$request->request->get('order')['name'] ?>">
                </div>
                <div class="form-group">
                    <label for="email">email</label>
                    <input class="form-control" type="email" id="email" name="order[email]" value="<?php echo \Application::$request->request->get('order')['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="phone">телефон</label>
                    <input class="form-control" type="text" id="phone" name="order[phone]" value="<?php echo \Application::$request->request->get('order')['phone'] ?>">
                </div>
                <div class="form-group">
                    <label for="card">Номер банковской карты</label>
                    <input class="form-control" type="text" id="card" name="order[card]" minlength="16" maxlength="16" value="<?php echo \Application::$request->request->get('order')['card'] ?>">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV-код</label>
                    <input class="form-control" type="text" id="cvv" name="order[cvv]" minlength="3" maxlength="3" value="<?php echo \Application::$request->request->get('order')['cvv'] ?>">
                </div>
                <input type="hidden" name="order[csrfToken]" value="<?php echo $data['csrfToken'] ?>">
                <button type="submit" class="btn btn-success">Оформить</button>
            </form>
        <?php else: ?>
            Корзина пуста
        <?php endif; ?>
    </div>
</div><br>
