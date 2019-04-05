<?php

namespace App\Migration;

use App\Handler;

class Users extends Handler
{
    public function handle()
    {
        if ($this->exists('Users') && $this->io->confirm('Skip Users? (already exists)')) {
            return;
        }

        // grab comments
        $users = $this->v2('SELECT id,username,email,star FROM members WHERE banned = 0');
        $this->io->progressStart(count($users));

        $mig = [];

        // dummy user
        $mig[] = [
            'id'        => $this->uuid(0),
            'sso'       => 'system',
            'username'  => 'Adventurer',
            'email'     => 'kupo@xivdb.com',
            'is_star'   => 0,
        ];

        foreach ($users as $user) {
            $this->io->progressAdvance();
            $mig[] = [
                'id'            => $this->uuid($user->id),
                'sso'           => 'xivdbv2',
                'username'      => $user->username,
                'email'         => $user->email,
                'is_star'       => $user->star
            ];
        }

        $this->io->progressAdvance();
        $this->save('Users', $mig);
    }
}
