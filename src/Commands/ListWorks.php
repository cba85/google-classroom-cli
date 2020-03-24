<?php

namespace App\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListWorks extends Base
{
    protected static $defaultName = 'list-works';

    protected function configure()
    {
        $this->setDescription('List the works of the course')
            ->setHelp('This command allows you to list the works of your Google Classroom course.')
            ->addArgument('courseId', InputArgument::REQUIRED, 'Google Classroom course ID.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$results = $this->googleClassroomService->listCourseWorks($output, $input->getArgument('courseId'))) {
            return 0;
        }

        if (!$course = $this->googleClassroomService->getCourse($output, $input->getArgument('courseId'))) {
            return 0;
        }

        $output->writeln("<info>Course:</info> {$course->name}");

        $output->writeln("<info>Works:</info>");
        foreach ($results->getCourseWork() as $work) {
            $output->writeLn(("<fg=blue>{$work->getTitle()}</fg=blue> ({$work->getState()})"));
            if ($work->getDueDate()) {
                $output->writeLn(("Due: {$work->getDueDate()->getDay()}/{$work->getDueDate()->getMonth()}/{$work->getDueDate()->getYear()}"));
            }
            $output->writeln("ID : {$work->getId()}");
        }

        return 0;
    }
}
