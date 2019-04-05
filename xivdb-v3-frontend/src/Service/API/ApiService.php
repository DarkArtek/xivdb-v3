<?php

namespace App\Service\API;

use App\Service\User\LanguageService;
use XIV\XivService;
use XIVCommon\Redis\RedisCache;

class ApiService
{
    const REDIS_DURATION = (60 * 60 * 24 * 30); // 30 days
    
    /** @var RedisCache */
    private $redis;
    /** @var XivService */
    private $xiv;
    /** @var array */
    private $data;
    
    public function __construct()
    {
        $this->redis = new RedisCache();
        $this->xiv   = new XivService();
        
        $this->init();
    }
    
    /**
     * @throws
     */
    public function init()
    {
        LanguageService::init();
        
        $key = 'frontend_patchlist';
    
        // Grab latest PatchList and save it
        if (true || !$data = $this->redis->get($key)) {
            $exVersions = $this->xiv->Data->list('ExVersion', ['ID','Name'])->results;
            
            $data = $this->xiv->Data->getPatchList();
            foreach ($data as $patch) {
                $patch->ExVersion = $exVersions[$patch->ExVersion] ?? $patch->ExVersion;
            }

            $this->redis->set($key, $data, self::REDIS_DURATION);
        }
    
        foreach ($data as $patch) {
            $patch->Name = $patch->{"Name_". LANGUAGE};
        }
    
        $this->data = $data;
    }
    
    /**
     * Get the XIVDB Patch List
     */
    public function getPatchList()
    {
        // ignore the "Unknown" Patch and FFXIV 1.0
        unset($this->data[0], $this->data[1]);

        $patchList  = [];
        $exVersions = [];
        foreach (array_reverse($this->data) as $patch) {
            $patchList[$patch->ExVersion->ID][] = $patch;
            $exVersions[$patch->ExVersion->ID] = $patch->ExVersion;
        }
        
        return [
            'List'       => $patchList,
            'ExVersions' => $exVersions,
        ];
    }
    
    /**
     * Get patch info from a version
     */
    public function getPatchFromVersion($version)
    {
        foreach ($this->data as $patch) {
            if ($patch->Version == $version) {
                return $patch;
            }
        }
        
        return false;
    }
    
    /**
     * Get docs
     */
    public function getDocs($filename = null)
    {
        $folder     = __DIR__.'/docs/';
        $docs       = array_diff(scandir($folder), ['.','..']);
        $filename   = "{$filename}.md";
        $filename   = in_array($filename, $docs) ? $filename : 'Welcome.md';
        $markdown   = (new \Parsedown())->text(file_get_contents($folder . $filename));
    
        // add some css classes
        $markdown = str_ireplace([
            '<table>',
            '<blockquote>',
            '</blockquote>'
        ],[
            '<table class="table table-bordered table-striped table-dark">',
            '<div class="alert alert-warning" role="alert">',
            '</div>',
        ],
        $markdown);
        
        return [
            'markdown' => $markdown,
            'filename' => str_ireplace('.md', null, $filename),
        ];
    }
    
    /**
     * Get a list of FFXIV servers
     */
    public function getServerList($group = false)
    {
        $servers = array_filter(
            explode("\n", file_get_contents(__DIR__.'/resources/Servers.txt'))
        );
        
        $temp = [];
        if ($group) {
            foreach ($servers as $server) {
                $letter = $server[0];
                
                if (!isset($temp[$letter])) {
                    $temp[$letter] = [];
                }
    
                $temp[$letter][] = $server;
            }
            
            return $temp;
        }
        
        return $servers;
    }
}
