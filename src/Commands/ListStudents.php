<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListStudents extends Command
{
    protected static $defaultName = 'list-students';

    protected function configure()
    {
        $this->setDescription('List the students of the course')
            ->setHelp('This command allows you to list the students of your Google Classroom course.')
            ->addArgument('courseId', InputArgument::REQUIRED, 'Google Classroom course ID.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $googleClassroom = new \App\GoogleClassroom\Service;

        // Course information
        if (!$course = $googleClassroom->getCourse($output, $input->getArgument('courseId'))) {
            return COMMAND::FAILURE;
        }

        $output->writeln("<info>Course:</info> {$course->name} - {$course->getId()}");

        // Students
        if (!$results = $googleClassroom->listStudents($output, $input->getArgument('courseId'))) {
            return COMMAND::FAILURE;
        }

        if (count($results->getStudents()) == 0) {
            $output->writeln("<comment>No students found.</comment>");
            return COMMAND::FAILURE;
        }

        $output->writeln("<info>Students</info>");
        foreach ($results->getStudents() as $student) {
            $output->writeln("{$student->getProfile()->name->fullName} - {$student->getUserId()} ");
        }

        return COMMAND::SUCCESS;
    }
}
