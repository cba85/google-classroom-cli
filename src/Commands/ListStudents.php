<?php

namespace App\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListStudents extends Base
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
        if (!$results = $this->googleClassroomService->listStudents($output, $input->getArgument('courseId'))) {
            return 0;
        }

        if (!$course = $this->googleClassroomService->getCourse($output, $input->getArgument('courseId'))) {
            return 0;
        }

        $output->writeln("<info>Course:</info> {$course->name}");

        if (count($results->getStudents()) == 0) {
            $output->writeln("<comment>No students found.</comment>");
            return 0;
        }

        $output->writeln("<info>Students:</info>");
        foreach ($results->getStudents() as $student) {
            $output->writeln("{$student->getProfile()->name->fullName} - {$student->getUserId()} ");
        }

        return 0;
    }
}
