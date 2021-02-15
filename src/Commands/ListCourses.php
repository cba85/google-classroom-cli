<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCourses extends Command
{
    protected static $defaultName = 'list-courses';

    protected function configure()
    {
        $this->setDescription('List your Google Classroom courses')
            ->setHelp('This command allows you to list your courses in your Google Classroom account.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $googleClassroom = new \App\GoogleClassroom\Service;

        if (!$results = $googleClassroom->listCourses($output)) {
            return COMMAND::FAILURE;
        }

        if (count($results->getCourses()) == 0) {
            $output->writeln("<comment>No courses found.</comment>");
            return COMMAND::FAILURE;
        }

        $output->writeln("<info>Courses</info>");
        foreach ($results->getCourses() as $course) {
            $output->writeln("{$course->getName()}: {$course->getId()}");
        }

        return COMMAND::SUCCESS;
    }
}
