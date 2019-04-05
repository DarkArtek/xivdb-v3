<?php

namespace App\Migration;

use App\Handler;

class Screenshots extends Handler
{
    public function handle()
    {
        if ($this->exists('Screenshots') && $this->io->confirm('Skip Screenshots? (already exists)')) {
            return;
        }

        $contentNames = [
            1 => 'Item',
            3 => 'Action',
            4 => 'Quest',
            5 => 'Recipe',
            7 => 'Fate',
            8 => 'Achievement',
            11 => 'ENpcResident',
            12 => 'BNpcName',
            15 => 'Mount',
            16 => 'InstanceContent',
            18 => 'Companion',
            20 => 'Leve',

            200 => 'PlaceName',
            203 => 'Emote',
            204 => 'Status',
            205 => 'Title',
            206 => 'Weather',
            208 => 'SpecialShop',
        ];

        $contentIds = array_flip([
            'item' => 1,
            //'screenshot' => 2,
            'action' => 3,
            'quest' => 4,
            'recipe' => 5,
            //'news' => 6,
            'fate' => 7,
            'achievement' => 8,
            'huntinglog' => 9,
            //'wardrobe' => 10,
            'npc' => 11,
            'enemy' => 12,
            // 13, 14
            'mount' => 15,
            'instance' => 16,
            // 17
            'minion' => 18,
            'fcstatus' => 19,
            'leve' => 20,

            // starting high as xivdb v1 had some around 100.
            'placename' => 200,
            'shop' => 201,
            'gathering' => 202,
            'emote' => 203,
            'status' => 204,
            'title' => 205,
            'weather' => 206,
            'special-shop' => 207,

            'character' => 300,
        ]);

        // grab screenshots
        $screenshots = $this->v2('SELECT * FROM content_screenshots');
        $this->io->progressStart(count($screenshots));

        $mig  = [];
        foreach ($screenshots as $ss) {
            $this->io->progressAdvance();
            $user = $this->find('Users', [ 'id', $this->uuid($ss->member) ]);

            // if user not found, default it
            if (!$user) {
                $user = $this->find('Users', [ 'id', $this->uuid(0) ]);
            }

            $contentName = $contentNames[$ss->content] ?? false;
            if (!$contentName) {
                continue;
            }

            $cname  = $contentIds[$ss->content];
            $url    = "http://172.104.15.148/screenshots/{$cname}/{$ss->uniq}/{$ss->filename}";
            $hash = sha1_file($url);
            $directory = str_split($hash, 3);
            $directory = "/{$directory[0]}/{$directory[1]}/";

            print_r([
                $cname,
                $url,
                $hash,
                $directory
            ]);

            die;

            $mig[] = [
                'id' => $this->uuid($ss->id),
                'id_unique' => "{$contentName}:{$ss->uniq}",
                'id_user'   => $this->uuid($ss->member),
                'message'   => trim($ss->text),
                'added'     => strtotime($ss->time),
                'updated'   => strtotime($ss->time),

                'filename'  => $ss->filename,
                'filename_thumbnail' => str_ireplace('.jpg', '.thumb.jpg', $ss->filename),
                'filename_original' => $ss->filename,
                'directory' => $directory,
                'hash' => $hash,
            ];
        }

        $this->io->progressFinish();
        $this->save('Screenshots', $mig);
    }
}
