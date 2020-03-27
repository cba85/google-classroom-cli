<?php

namespace App\GoogleClassroom\Traits;

use Symfony\Component\Console\Output\OutputInterface;
use Google_Service_Exception;

trait Submission
{
    public function listSubmissions(OutputInterface $output, string $courseId, string $courseWorkId, array $options = [])
    {
        try {
            return $this->service->courses_courseWork_studentSubmissions->listCoursesCourseWorkStudentSubmissions($courseId, $courseWorkId, $options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }
}
