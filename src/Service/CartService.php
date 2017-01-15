<?php

namespace Service;

use Controller\DefaultController;
use Db\BonusDao;
use Db\ProductsDao;

class CartService
{
    const CART_SESSION_ID = 'cart';

    const BONUS_SESSION_ID = 'bonus';

    public function getCartData()
    {
        $productsDao = new ProductsDao();

        $session = \Application::$request->getSession();

        $cartData = $session->get(self::CART_SESSION_ID, []);

        $result = [
            'items' => [],
            'sum' => 0,
            'qty' => 0,
        ];
        $products = $productsDao->findByIds(array_keys($cartData));
        foreach ($cartData as $productId => $productQty) {
            $product = $products[$productId];
            $result['items'][] = [
                'id' => $product['id'],
                'title' => $product['title'],
                'qty' => $productQty,
                'price' => $product['price'],
            ];
            $result['qty'] += $productQty;
        }

        $sum = array_reduce(
            $result['items'],
            function ($acc, $item) {
                return $acc + $item['price'] * $item['qty'];
            },
            0
        );

        $discount = $this->getQtyDiscount($result['qty']);
        $bonus = $this->getBonus();
        if ($bonus) {
            $discount += $bonus['discount_percent'] / 100;
            $result['bonus'] = $bonus;
        }

        if ($discount > 0) {
            $result['sumWithoutDiscount'] = $sum;
            $result['sum'] = round($sum - $sum * $discount, 2);
        } else {
            $result['sum'] = $sum;
        }

        return $result;
    }

    public function addToCart($productId)
    {
        $session = \Application::$request->getSession();

        $productsInCart = $session->get(self::CART_SESSION_ID, []);
        if (!isset($productsInCart[$productId])) {
            $productsInCart[$productId] = 1;
        } else {
            $productsInCart[$productId] += 1;
        }

        $session->set(self::CART_SESSION_ID, $productsInCart);
    }

    public function setQty($productId, $qty)
    {
        $session = \Application::$request->getSession();

        $productsInCart = $session->get(self::CART_SESSION_ID, []);
        if ($qty === 0 && isset($productsInCart[$productId])) {
            unset($productsInCart[$productId]);
        } else {
            $productsInCart[$productId] = $qty;
        }

        $session->set(self::CART_SESSION_ID, $productsInCart);
    }

    public function applyBonus($code)
    {
        $bonusDao = new BonusDao();

        $bonus = $bonusDao->findByCode($code);
        if ($bonus) {
            $session = \Application::$request->getSession();

            $session->set(self::BONUS_SESSION_ID, $bonus['id']);
        }
    }

    private function getQtyDiscount($qty)
    {
        if ($qty >= 3) {
            return 0.1;
        }

        return 0;
    }

    private function getBonus()
    {
        $session = \Application::$request->getSession();

        $bonusId = $session->get(self::BONUS_SESSION_ID);
        if ($bonusId) {
            $bonusDao = new BonusDao();

            return $bonusDao->find($bonusId);
        }

        return null;
    }

    public function clear()
    {
        $session = \Application::$request->getSession();
        $session->remove(self::BONUS_SESSION_ID);
        $session->remove(self::CART_SESSION_ID);
    }
}