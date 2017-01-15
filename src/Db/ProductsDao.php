<?php

namespace Db;

class ProductsDao
{
    /**
     * метод подходит для случая, если id товаров int и нет разрывов в нумерации.
     * order by random() limit $x работает медленно на больших таблицах
     */
    public function findRandom($limit)
    {
        $db = Db::getInstance();
        $minMaxId = $db->querySingle("SELECT min(id) AS minid, max(id) AS maxid FROM product");

        $ids = [];
        do {
            $randomId = random_int($minMaxId['minid'], $minMaxId['maxid']);
            if (!in_array($randomId, $ids)) {
                $ids[] = $randomId;
            }
        } while (count($ids) < $limit || count($ids) === $minMaxId['maxid']);

        $sql = sprintf(
            'SELECT id, title, image_url, price FROM product WHERE id IN(%s)',
            implode(',', array_fill(0, count($ids), '?'))
        );

        return $db->query($sql, $ids);
    }

    public function findByIds(array $ids)
    {
        $db = Db::getInstance();

        $sql = sprintf(
            'SELECT id, title, image_url, price FROM product WHERE id IN(%s)',
            implode(',', array_fill(0, count($ids), '?'))
        );

        $data =  $db->query($sql, $ids);
        $result = [];
        foreach ($data as $item) {
            $result[$item['id']] = $item;
        }

        return $result;
    }
}