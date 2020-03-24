<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use App\GoogleClassroom\GoogleClassroom;

class Base extends Command
{
    protected $googleClassroomService;

    public function __construct()
    {
        parent::__construct();
        $this->googleClassroomService = new GoogleClassroom;
    }
}
