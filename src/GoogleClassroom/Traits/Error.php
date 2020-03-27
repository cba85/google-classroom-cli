<?php

namespace App\GoogleClassroom\Traits;

use Symfony\Component\Console\Output\OutputInterface;

trait Error
{
    public function displayError(OutputInterface $output, $e)
    {
        $response = json_decode($e->getMessage());
        $response = json_decode($e->getMessage());
        $output->writeln("<error>{$e->getCode()} error: {$response->error->message}</error>");
        return 0;
    }
}
