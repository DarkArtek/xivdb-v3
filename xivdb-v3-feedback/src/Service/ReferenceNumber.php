<?php

namespace App\Service;

/**
 * Provides a unique counter number by
 * recording the last number in a file,
 * incrementing the number and updating
 * the counter file.
 */
class ReferenceNumber
{
    const COUNTER_FILE = 'counter';

    public static function generate()
    {
        $filename = __DIR__ .'/'. self::COUNTER_FILE;

        // get current count
        $current = file_exists($filename)
            ? (int)trim(file_get_contents($filename))
            : 14;

        // add on a random amount
        $current = $current + mt_rand(5,15);

        // save
        file_put_contents($filename, (int)$current);
        return "XIV-{$current}";
    }
}
