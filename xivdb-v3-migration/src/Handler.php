<?php

namespace App;

use App\Migration\Comments;
use App\Migration\Screenshots;
use App\Migration\Users;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Style\SymfonyStyle;

class Handler
{
    /** @var SymfonyStyle */
    public $io;
    /** @var \PDO */
    public $pdo;
    /** array */
    public $data;

    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    public function go()
    {
        (new Users($this->io))->handle();
        (new Comments($this->io))->handle();
        (new Screenshots($this->io))->handle();
    }

    /**
     * Query data from v2
     */
    public function v2($sql)
    {
        $key = 'eng983qn4g98q3n59g8bq4hpwoeirj9p384bg39q84gnqh358';
        $sql = base64_encode($sql);
        $url = "http://172.104.15.148/dev/migrations?key={$key}&sql={$sql}";
        $this->io->text($url);
        return json_decode(file_get_contents($url));
    }

    /**
     * Save a migration file
     */
    public function save(string $filename, array $data)
    {
        $this->data[$filename] = $data;
        $filename = __DIR__.'/MigrationDocuments/'. $filename .'.json';
        file_put_contents($filename, json_encode($data));
        $this->io->text([
            '', 'Saved: '. $filename, ''
        ]);
    }

    /**
     * Check if a migration file has already been generated
     */
    public function exists(string $filename)
    {
        $filename = __DIR__.'/MigrationDocuments/'. $filename .'.json';
        return file_exists($filename);
    }

    /**
     * Find a value previously fetched
     */
    public function find(string $filename, array $filter)
    {
        $filename = __DIR__.'/MigrationDocuments/'. $filename .'.json';

        if (!isset($this->data[$filename])) {
            $this->data[$filename] = json_decode(file_get_contents($filename));
        }

        [$field, $value] = $filter;

        foreach ($this->data[$filename] as $obj) {
            if ($obj->{$field} == $value) {
                return $obj;
            }
        }

        return false;
    }

    public function uuid($int)
    {
        if ($int === null) {
            return null;
        }

        return Uuid::uuid3(Uuid::NAMESPACE_DNS, 'xiv:'. $int)->toString();
    }
}
