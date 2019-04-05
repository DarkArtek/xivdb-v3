<?php

namespace App\Service\FreeCompany;

use App\Service\Rabbit\DefaultRabbitHandler;

/**
 * @package App\Services\FreeCompany
 */
class FreeCompanyRabbitHandler extends DefaultRabbitHandler
{
    const BUCKET = 'freecompany';

    // data
    protected $new;
    protected $old;
    protected $sync;

    public function handle(\stdClass $json)
    {
        $this->storage->setBucket(self::BUCKET);
        
        // setup data variables
        $this->new = $json->data->profile;
        $this->sync  = $json->sync;
        $this->sync->last_synced = time();
        $this->io->text("[{$json->type}] {$this->new->id}");

        // grab the old data if we're doing an update, this is performed here
        // so that character data conversion can reference it.
        if ($json->type == 'update') {
            $this->old = $this->storage->load("{$this->new->id}_data");

            if (!$this->old) {
                $this->io->error("lodestone id: {$this->new->id} has no old data during an update call. Should not happen ...");
                return;
            }
        }

        // handle based on if it's a add or update
        switch($json->type) {
            case 'add':
                $this->add();
                break;

            case 'update':
                $this->update();
                break;
        }
    }

    /**
     * Add a new character, doesn't do much as we just need it in the system
     * so that the update function can compare against it
     */
    private function add()
    {
        $this->io->text('Adding free company ...');

        // save data
        $this->storage
            ->save("{$this->new->id}_data", $this->new)
            ->save("{$this->new->id}_sync", $this->sync);

        // add to database
        $sql = "
            INSERT INTO `site_free_companies`
            (`id`,`name`,`server`,`crest`,`updated`)
            VALUES (:id,:name,:server,:crest,:updated)
            ON DUPLICATE KEY UPDATE
              name=VALUES(name),
              server=VALUES(server),
              crest=VALUES(crest),
              updated=VALUES(updated),
              elastic=VALUES(elastic)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $this->new->id,
            'name' => $this->new->name,
            'server' => $this->new->server,
            'crest' => serialize($this->new->crest),
            'updated' => $this->sync->last_updated
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
        $this->io->text('Updating free company ...');

        // save chareacter data
        $this->storage
            ->save("{$this->new->id}_data", $this->new)
            ->save("{$this->new->id}_sync", $this->sync);

        // update db entry
        $updateElastic = ($this->new->name == $this->old->name) ? 0 : 1;
        $updateElastic = ($this->new->server == $this->old->server) ? $updateElastic : 1;

        $sql = "
            UPDATE `site_free_companies`
            SET
              `name` = :name,
              `server` = :server,
              `crest` = :crest,
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
            'crest' => serialize($this->new->crest),
            'updated' => $this->sync->last_updated,
            'elastic' => $updateElastic,
        ]);

        $this->io->text([
            '✓ Complete',
            '-----------------------------------'
        ]);
    }
}
