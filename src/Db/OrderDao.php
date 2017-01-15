<?php

namespace Db;


use Exceptions\DbResultCountException;

class OrderDao
{
    public function find($id)
    {
        $db = Db::getInstance();

        try {
            return $db->querySingle(
                "SELECT id, bonus_id, sum, created_at, card, cvv, name, email, phone FROM orders WHERE id=? LIMIT 1",
                [$id]
            );
        } catch (DbResultCountException $e) {
            return null;
        }
    }

    public function findProducts($orderId)
    {
        $db = Db::getInstance();

        return $db->query(
            'SELECT op.qty, p.title FROM orders_product op LEFT JOIN product p ON op.product_id = p.id WHERE op.order_id = ?',
            [$orderId]
        );
    }

    public function insert($data)
    {
        $db = Db::getInstance();

        $result = $db->querySingle(
            "INSERT INTO orders(bonus_id, sum, created_at, card, cvv, name, email, phone) VALUES (?,?,?,?,?,?,?,?) RETURNING id",
            $data
        );

        return $result['id'];
    }

    public function addOrderProduct($orderId, $productId, $qty)
    {
        $db = Db::getInstance();

        $db->execute(
            'INSERT INTO orders_product (order_id, product_id, qty) VALUES (?, ?, ?)',
            [$orderId, $productId, $qty]
        );
    }
}