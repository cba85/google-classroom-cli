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

    public function listSubmissions(OutputInterface $output, string $courseId, string $courseWorkId, array $options = [])
    {
        try {
            return $this->service->courses_courseWork_studentSubmissions->listCoursesCourseWorkStudentSubmissions($courseId, $courseWorkId, $options);
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
