<?php

namespace App\Command;

use App\Service\Mail;
use App\Service\Site;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MonitorCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:monitor:check')
            ->setDescription('Check the status of all monitored sites')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->text('Checking site pings');

        $client = new Client();

        $results = [];
        foreach (Site::LIST as $microservice) {
            [$name, $domain] = $microservice;

            try {
                // send an email
                $response = $client->get("{$domain}/ping", [
                    RequestOptions::VERIFY => false,
                ]);

                // inform state
                $status = (string)$response->getBody() === 'true' ? 'UP' : 'DOWN';
                $io->text("Status: ${name} {$domain} = {$status}");

                $results[] = [
                    'name' => $name,
                    'domain' => $domain,
                    'status' => $status
                ];
            } catch (\Exception $ex) {
                $io->text($ex->getMessage());
            }
        }

        $io->text('Finished');
        file_put_contents(
            __DIR__.'/../Service/results.json',
            json_encode($results, JSON_PRETTY_PRINT)
        );
    }
}
