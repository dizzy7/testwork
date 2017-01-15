<?php

namespace Helper;

class Html
{
    public static function e($html) {
        return htmlspecialchars($html);
    }
}