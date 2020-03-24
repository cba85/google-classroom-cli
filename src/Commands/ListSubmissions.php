<?php

namespace App\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListSubmissions extends Base
{
    protected static $defaultName = 'list-submissions';

    protected function configure()
    {
        $this->setDescription('List the students submissions of the course')
            ->setHelp('This command allows you to list the students submissions of your Google Classroom course.')
            ->addArgument('courseId', InputArgument::REQUIRED, 'Google Classroom course ID.')
            ->addArgument('courseWorkId', InputArgument::REQUIRED, 'Google Classroom course work ID.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Course information
        if (!$course = $this->googleClassroomService->getCourse($output, $input->getArgument('courseId'))) {
            return 0;
        }

        $output->writeln("<info>Course:</info> {$course->name}");

        // Assignment information
        if (!$assignment = $this->googleClassroomService->getAssignment($output, $input->getArgument('courseId'), $input->getArgument('courseWorkId'))) {
            return 0;
        }

        $output->write("<info>Assignment:</info> ");
        $output->write(("<fg=blue>{$assignment->getTitle()}</fg=blue> ({$assignment->getState()})"));
        if ($assignment->getDueDate()) {
            $output->write((" | Due: {$assignment->getDueDate()->getDay()}/{$assignment->getDueDate()->getMonth()}/{$assignment->getDueDate()->getYear()}"));
        }
        $output->write(" | Type : {$assignment->getWorkType()}");
        $output->writeln('');

        // Submissions
        if (!$results = $this->googleClassroomService->listSubmissions($output, $input->getArgument('courseId'), $input->getArgument('courseWorkId'))) {
            return 0;
        }

        $output->writeLn("<info>Submissions:</info> ");

        foreach ($results->getStudentSubmissions() as $submission) {
            // Student information
            if (!$student = $this->googleClassroomService->getStudent($output, $input->getArgument('courseId'), $submission->getUserId())) {
                return 0;
            }

            $output->write("<fg=cyan>{$student->getProfile()->name->fullName}</>");

            // Submission
            if ($submission->courseWorkType == "SHORT_ANSWER_QUESTION") {
                $output->write(" : {$submission->shortAnswerSubmission->answer}");
                if ($submission->late) {
                    $output->write(' (late)');
                }
                $output->writeln('');
            } elseif ($submission->courseWorkType == "ASSIGNMENT") {
                $output->writeln('');
                foreach ($submission->assignmentSubmission as $attachment) {
                    // Get link only
                    $output->writeLn($attachment->link->url);
                }
            }
        }

        return 0;
    }
}
