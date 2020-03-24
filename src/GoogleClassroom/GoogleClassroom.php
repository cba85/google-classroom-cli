<?php

namespace App\GoogleClassroom;

use Google_Service_Exception;
use Symfony\Component\Console\Output\OutputInterface;

class GoogleClassroom
{
    protected $service;

    public function __construct()
    {
        $classroom = new Service;
        $this->service = $classroom->getService();
    }

    public function listCourses(OutputInterface $output)
    {
        try {
            return $this->service->courses->listCourses();
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function getCourse(OutputInterface $output, string $courseId)
    {
        try {
            return $this->service->courses->get($courseId);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function listStudents(OutputInterface $output, string $courseId)
    {
        try {
            return $this->service->courses_students->listCoursesStudents($courseId);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function listCourseWorks(OutputInterface $output, string $courseId)
    {
        try {
            return $this->service->courses_courseWork->listCoursesCourseWork($courseId, ['courseWorkStates' => 'DRAFT']);
        } catch (Google_Service_Exception $e) {
            return $this->displayError($output, $e);
        }
    }

    public function displayError(OutputInterface $output, $e)
    {
        $response = json_decode($e->getMessage());
        $response = json_decode($e->getMessage());
        $output->writeln("<error>{$e->getCode()} error: {$response->error->message}</error>");
        return 0;
    }
}
