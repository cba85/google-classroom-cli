<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListSubmittedUrls extends Command
{
    protected static $defaultName = 'list-submitted-urls';

    protected function configure()
    {
        $this->setDescription('List the urls submitted by the students of the course')
            ->setHelp('This command allows you to listthe urls submitted by the students of your Google Classroom course.')
            ->addArgument('courseId', InputArgument::REQUIRED, 'Google Classroom course ID.')
            ->addArgument('courseWorkId', InputArgument::REQUIRED, 'Google Classroom course work ID.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $googleClassroom = new \App\GoogleClassroom\Service;

        // Course information
        if (!$course = $googleClassroom->getCourse($output, $input->getArgument('courseId'))) {
            return COMMAND::FAILURE;
        }

        $output->writeln("<info>Course:</info> {$course->name} - {$course->getId()}");

        // Assignment information
        if (!$assignment = $googleClassroom->getAssignment($output, $input->getArgument('courseId'), $input->getArgument('courseWorkId'))) {
            return COMMAND::FAILURE;
        }

        $output->write("<info>Assignment:</info> ");
        $output->write(("<fg=blue>{$assignment->getTitle()}</fg=blue> ({$assignment->getState()})"));
        if ($assignment->getDueDate()) {
            $output->write((" | Due: {$assignment->getDueDate()->getDay()}/{$assignment->getDueDate()->getMonth()}/{$assignment->getDueDate()->getYear()}"));
        }
        $output->write(" | Type : {$assignment->getWorkType()}");
        $output->writeln('');

        // Submissions
        if (!$results = $googleClassroom->listSubmissions($output, $input->getArgument('courseId'), $input->getArgument('courseWorkId'))) {
            return COMMAND::FAILURE;
        }

        $output->writeLn("<info>Submitted urls:</info> ");

        foreach ($results->getStudentSubmissions() as $submission) {
            // Student information
            if (!$student = $googleClassroom->getStudent($output, $input->getArgument('courseId'), $submission->getUserId())) {
                return COMMAND::FAILURE;
            }

            $output->write("<fg=cyan>{$student->getProfile()->name->fullName}</>");
            if ($submission->late) {
                $output->write(' (late)');
            }
            $output->writeln('');

            // Submitted urls
            if ($submission->courseWorkType == "SHORT_ANSWER_QUESTION") {
                $output->writeLn($submission->shortAnswerSubmission->answer);
            } elseif ($submission->courseWorkType == "ASSIGNMENT") {
                foreach ($submission->assignmentSubmission as $attachment) {
                    // Get link only
                    if (!empty($attachment->link)) {
                        $output->writeLn($attachment->link->url);
                    }
                }
            }
        }

        return COMMAND::SUCCESS;
    }
}
