<?php

namespace Db;

use Exceptions\DbResultCountException;

class BonusDao
{
    public function findByCode($code)
    {
        $db = Db::getInstance();

        try {
            return $db->querySingle("SELECT id, discount_percent, code FROM bonus WHERE code=? LIMIT 1", [$code]);
        } catch (DbResultCountException $e) {
            return null;
        }
    }

    public function find($id)
    {
        $db = Db::getInstance();

        try {
            return $db->querySingle("SELECT id, discount_percent, code FROM bonus WHERE id=? LIMIT 1", [$id]);
        } catch (DbResultCountException $e) {
            return null;
        }
    }
}