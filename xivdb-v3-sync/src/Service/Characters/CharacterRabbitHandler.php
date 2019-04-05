<?php

namespace App\Service\Characters;

use App\Service\Rabbit\DefaultRabbitHandler;
use App\Service\Characters\Data\{
    CharacterConverter,
    CharacterDataManager
};
use App\Service\Characters\Tracking\{
    CharacterTracking,
    TrackClassJobs,
    TrackCollectables,
    TrackGrandCompany,
    TrackProfile
};

/**
 * @package App\Services\Characters
 */
class CharacterRabbitHandler extends DefaultRabbitHandler
{
    const BUCKET = 'character';

    use CharacterConverter;
    use CharacterDataManager;
    use CharacterTracking;
    use TrackProfile;
    use TrackCollectables;
    use TrackGrandCompany;
    use TrackClassJobs;

    // data
    protected $new;
    protected $old;
    protected $sync;
    protected $gear;
    protected $events = [];

    /**
     * Handle a character
     */
    public function handle(\stdClass $json)
    {
        $this->storage->setBucket(self::BUCKET);

        // setup data variables (and reset them)
        $this->new = $json->data;
        $this->sync = $json->sync;
        $this->sync->last_synced = time();
        $this->gear = [];
        $this->events = [];

        $this->io->text("[{$json->type}] {$this->new->id}");

        // process based on the json payload type
        switch($json->type) {
            case 'add':
                $this
                    ->initGameData()
                    ->convertCharacterData()
                    ->add();
                break;

            case 'update':
                // grab the old data if we're doing an update, this is performed here
                // so that character data conversion can reference it.
                $this->old = $this->storage->load("{$this->new->id}_data");
                $this->gear = $this->storage->load("{$this->new->id}_gear", []);
                $this->events = $this->storage->load("{$this->new->id}_events", []);
    
                if (!$this->old) {
                    $this->io->error("lodestone id: {$this->new->id} has no old data during an update call. Should not happen ...");
                    return;
                }
                
                $this
                    ->initGameData()
                    ->convertCharacterData()
                    ->update();
                break;
                
            case 'friends':
                $this->friends();
                break;
                
            case 'achievements':
                $this->achievements();
                break;
        }
    }

    /**
     * Add a new character, doesn't do much as we just need it in the system
     * so that the update function can compare against it
     */
    private function add()
    {
        $this->io->text('Adding character ...');
    
        // ensure string due to integer overflow
        $this->new->freecompany = 'i'. $this->new->freecompany;
    
        // save character data
        $this->storage
            ->save("{$this->new->id}_data", $this->new)
            ->save("{$this->new->id}_sync", $this->sync)
            ->save("{$this->new->id}_gear", $this->gear);

        // add to database
        $sql = "
            INSERT INTO `site_characters` 
            (`id`,`name`,`server`,`avatar`,`updated`,`elastic`)
            VALUES (:id,:name,:server,:avatar,:updated,:elastic)
            ON DUPLICATE KEY UPDATE
              name=VALUES(name),
              server=VALUES(server),
              avatar=VALUES(avatar),
              updated=VALUES(updated),
              elastic=VALUES(elastic)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $this->new->id,
            'name' => $this->new->name,
            'server' => $this->new->server,
            'avatar' => $this->new->avatar,
            'updated' => $this->sync->last_updated,
            'elastic' => true,
        ]);
    
        $this->io->text([
            '✓ Complete',
            '-----------------------------------'
        ]);
    }

    /**
     * Update a character
     */
    private function update()
    {
        $this->io->text('Updating character ...');

        // record new collectables
        $this->recordMinionChanges();
        $this->recordMountChanges();

        // record new profile data
        $this->recordProfileChanges();

        // record grand company changes
        $this->recordGrandCompanyChanges();

        // record events
        $this->recordClassJobEvents();

        // save log and events data
        $this->saveTracking();
    
        // ensure string due to integer overflow
        $this->new->freecompany = 'i'. $this->new->freecompany;

        // save character data
        $this->storage
            ->save("{$this->new->id}_data", $this->new)
            ->save("{$this->new->id}_sync", $this->sync)
            ->save("{$this->new->id}_gear", $this->gear)
            ->save("{$this->new->id}_events", $this->events);

        // update db entry
        $updateElastic = ($this->new->name == $this->old->name) ? 0 : 1;
        $updateElastic = ($this->new->server == $this->old->server) ? $updateElastic : 1;

        $sql = "
            UPDATE `site_characters` 
            SET
              `name` = :name,
              `server` = :server,
              `avatar` = :avatar,
              `elastic` = :elastic,
              `updated` = :updated
            WHERE
              `id` = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $this->new->id,
            'name' => $this->new->name,
            'server' => $this->new->server,
            'avatar' => $this->new->avatar,
            'updated' => $this->sync->last_updated,
            'elastic' => $updateElastic,
        ]);

        $this->io->text([
            '✓ Complete',
            '-----------------------------------'
        ]);
    }
    
    /**
     * Store characters friends (overwrites existing)
     */
    private function friends()
    {
        $this->io->text('Adding friends ...');
        $this->storage->save("{$this->new->id}_friends", $this->new->friends);
    
        $this->io->text([
            '✓ Complete',
            '-----------------------------------'
        ]);
    }
    
    /**
     * Store characters achievements (overwrites existing)
     */
    private function achievements()
    {
        $this->io->text('Adding achievements ...');
        $this->storage->save("{$this->new->id}_achievements", $this->new->achievements);
    
        $this->io->text([
            '✓ Complete',
            '-----------------------------------'
        ]);
    }
}
