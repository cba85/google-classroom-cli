<?php

namespace App\GoogleClassroom\Traits;

use Symfony\Component\Console\Output\OutputInterface;
use Google_Service_Exception;

trait Course
{
    public function listCourses(OutputInterface $output, array $options = [])
    {
        try {
            return $this->service->courses->listCourses($options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function getCourse(OutputInterface $output, string $courseId, array $options = [])
    {
        try {
            return $this->service->courses->get($courseId, $options);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }
}
