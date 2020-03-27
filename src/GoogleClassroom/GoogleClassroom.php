<?php

namespace App\GoogleClassroom;

use App\GoogleClassroom\Traits\Assignment;
use App\GoogleClassroom\Traits\Course;
use App\GoogleClassroom\Traits\Error;
use App\GoogleClassroom\Traits\Student;
use App\GoogleClassroom\Traits\Submission;

class GoogleClassroom
{
    use Assignment;
    use Course;
    use Error;
    use Student;
    use Submission;

    protected $service;

    public function __construct()
    {
        $classroom = new Service;
        $this->service = $classroom->getService();
    }
}
