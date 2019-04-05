<?php

namespace App\Migration;

use App\Handler;

class Comments extends Handler
{
    public function handle()
    {
        if ($this->exists('Comments') && $this->io->confirm('Skip Comments? (already exists)')) {
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

        // grab comments
        $comments = $this->v2('SELECT * FROM content_comments');
        $this->io->progressStart(count($comments));

        $mig  = [];
        foreach ($comments as $cmt) {
            $this->io->progressAdvance();
            $user = $this->find('Users', [ 'id', $this->uuid($cmt->member) ]);

            // if user not found, default it
            if (!$user) {
                $user = $this->find('Users', [ 'id', $this->uuid(0) ]);
            }

            $contentName = $contentNames[$cmt->content] ?? false;
            if (!$contentName) {
                continue;
            }

            $mig[] = [
                'id' => $this->uuid($cmt->id),
                'id_unique' => "{$contentName}:{$cmt->uniq}",
                'id_user'   => $this->uuid($cmt->member),
                'id_reply'  => $this->uuid($cmt->reply_to),
                'message'   => trim($cmt->text),
                'deleted'   => $cmt->deleted,
                'added'     => strtotime($cmt->time),
                'updated'   => strtotime($cmt->time),
            ];
        }

        $this->io->progressFinish();
        $this->save('Comments', $mig);
    }
}
