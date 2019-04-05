<?php

namespace App\Command;

use App\Entity\LodestoneContent;
use Doctrine\ORM\EntityManagerInterface;
use Lodestone\Api;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use XIV\XivService;

class GenerateHomepageCommand extends Command
{
    private $em;
    
    public function __construct(EntityManagerInterface $em, ?string $name = null)
    {
        $this->em = $em;
        parent::__construct($name);
    }
    
    protected function configure()
    {
        $this
            ->setName('app:homepage')
            ->setDescription('Generate Homepage')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io   = new SymfonyStyle($input, $output);
        $api  = new Api();
        $data = (Object)[];
    
        // enable some stuff
        define('BENCHMARK_ENABLED', true);
        define('LOGGER_ENABLED', true);
        define('LOGGER_ENABLE_PRINT_TIME', true);
    
        try {
            $io->text("GET: DevPosts (Slow ...)");
            $data->DevPosts    = json_decode(json_encode($api->getDevPosts()));

            $io->text("GET: DevBlogs");
            $data->DevBlogs    = json_decode(json_encode($api->getDevBlog()));
        } catch (\Exception $ex) {
            throw new $ex;
        }
    
        $io->text("GET: Topics, Notices, Maintenance, Updates, Status, Banners");
        $data->Topics      = json_decode(json_encode($api->getLodestoneTopics()));
        $data->Notices     = json_decode(json_encode($api->getLodestoneNotices()));
        $data->Maintenance = json_decode(json_encode($api->getLodestoneUpdates()));
        $data->Updates     = json_decode(json_encode($api->getLodestoneUpdates()));
        $data->Status      = json_decode(json_encode($api->getLodestoneStatus()));
        $data->Banners     = json_decode(json_encode($api->getLodestoneBanners()));
        
        // add hashes
        $io->text('Cleaning up data ...');
        $this->handle($data);
        
        // save
        $io->text('Saving lodestone content ...');
        $repo = $this->em->getRepository(LodestoneContent::class);
        foreach ($data as $contentType => $list) {
            $io->text("- {$contentType}");
            
            foreach ($list as $obj) {
                // ignore those already added
                if ($repo->findOneBy([ 'hash' => $obj->hash ])) {
                    continue;
                }
                
                $lsc = new LodestoneContent();
                $lsc->setHash($obj->hash)
                    ->setType($contentType)
                    ->setTime($obj->time)
                    ->setData(json_encode($obj));
                
                $this->em->persist($lsc);
            }
        }
        
        $this->em->flush();
        file_put_contents(__DIR__.'/GenerateHomepageCommand.json', json_encode($data, JSON_PRETTY_PRINT));
        
        // push to API
        $sdk = new XivService();
        $sdk->API->saveLodestoneData(
            json_decode(json_encode($data), true)
        );
    }
    
    private function handle($data)
    {
        foreach ($data->DevBlogs as $obj) {
            $obj->hash      = sha1($obj->id . 'DevBlog');
            $obj->content   = $this->tidy($obj->content);
            $obj->time      = (new \DateTime($obj->published))->format('U');
            $obj->url       = $obj->link->{"@attributes"}->href;
            $obj->author    = $obj->author->name;
            unset($obj->link);
        }
    
        foreach ($data->DevPosts as $obj) {
            $obj->hash    = sha1($obj->id . 'DevPost');
            $obj->content = $this->tidy($obj->content);
        }
    
        foreach ($data->Banners as $obj) {
            $obj->hash = sha1($obj->banner . 'Banner');
            $obj->time = time();
        }
        
        foreach ($data->Topics as $obj) {
            $obj->hash = sha1($obj->url . 'Topics');
        }
    
        foreach ($data->Notices as $obj) {
            $obj->hash = sha1($obj->url . 'Notices');
        }
    
        foreach ($data->Maintenance as $obj) {
            $obj->hash = sha1($obj->url . 'Maintenance');
        }
    
        foreach ($data->Updates as $obj) {
            $obj->hash = sha1($obj->url . 'Updates');
        }
    
        foreach ($data->Status as $obj) {
            $obj->hash = sha1($obj->url . 'Status');
        }
    }
    
    /**
     * Clean some html
     */
    private function tidy($html)
    {
        $tidy = new \Tidy();
        $tidy->parseString($html, [ 'indent' => false ], 'utf8');
        $tidy->cleanRepair();
    
        $html = $tidy->value;
        $html = str_ireplace(
            [ 'â—†', 'â€™', 'â€', 'œ'],[ null, "'", null, null],
            $html);
    
        // fix http to https
        $html = str_ireplace('http://', 'https://', $html);
        $html = str_ireplace("\n", " ", $html);
        return $html;
    }
}
