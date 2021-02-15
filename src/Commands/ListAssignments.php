<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListAssignments extends Command
{
    protected static $defaultName = 'list-assignments';

    protected function configure()
    {
        $this->setDescription('List the assignments of the course')
            ->setHelp('This command allows you to list the assignments of your Google Classroom course.')
            ->addArgument('courseId', InputArgument::REQUIRED, 'Google Classroom course ID.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $googleClassroom = new \App\GoogleClassroom\Service;

        // Course information
        if (!$course = $googleClassroom->getCourse($output, $input->getArgument('courseId'))) {
            return COMMAND::FAILURE;
        }

        $output->writeln("<info>Course:</info> {$course->name}");

        // Assignment
        if (!$results = $googleClassroom->listAssignments($output, $input->getArgument('courseId'))) {
            return COMMAND::FAILURE;
        }

        $output->writeln("<info>Assignment</info>");
        foreach ($results->getCourseWork() as $assignment) {
            $output->writeLn(("<fg=blue>{$assignment->getTitle()}</fg=blue> ({$assignment->getState()}) - {$assignment->getId()}"));
            if ($assignment->getDueDate()) {
                $output->writeLn(("Due: {$assignment->getDueDate()->getDay()}/{$assignment->getDueDate()->getMonth()}/{$assignment->getDueDate()->getYear()}"));
            }
            $output->writeln("Type : {$assignment->getWorkType()}");
        }

        return COMMAND::SUCCESS;
    }
}
