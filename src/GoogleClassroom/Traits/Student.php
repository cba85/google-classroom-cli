<?php

namespace App\GoogleClassroom\Traits;

use Symfony\Component\Console\Output\OutputInterface;
use Google_Service_Exception;

trait Student
{
    public function listStudents(OutputInterface $output, string $courseId, array $options = [])
    {
        try {
            return $this->service->courses_students->listCoursesStudents($courseId, $options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function getStudent(OutputInterface $output, string $courseId, string $userId, array $options = [])
    {
        try {
            return $this->service->courses_students->get($courseId, $userId, $options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }
}
