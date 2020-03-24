<?php

namespace App\Commands;

use App\GoogleClassroom\GoogleClassroom;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCourses extends Base
{
    protected static $defaultName = 'list-courses';

    protected function configure()
    {
        $this->setDescription('List your Google Classroom courses')
            ->setHelp('This command allows you to list your courses in your Google Classroom account.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$results = $this->googleClassroomService->listCourses($output)) {
            return 0;
        }

        if (count($results->getCourses()) == 0) {
            $output->writeln("<comment>No courses found.</comment>");
            return 0;
        }

        $output->writeln("<info>Courses:</info>");
        foreach ($results->getCourses() as $course) {
            $output->writeln("{$course->getName()} - {$course->getId()}");
        }

        return 0;
    }
}
