<?php

namespace App\GoogleClassroom\Traits;

use Symfony\Component\Console\Output\OutputInterface;
use Google_Service_Exception;

trait Assignment
{
    public function listAssignments(OutputInterface $output, string $courseId, array $options = [])
    {
        try {
            return $this->service->courses_courseWork->listCoursesCourseWork($courseId, $options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function getAssignment(OutputInterface $output, string $courseId, string $courseWorkId, array $options = [])
    {
        try {
            return $this->service->courses_courseWork->get($courseId, $courseWorkId, $options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }
}
