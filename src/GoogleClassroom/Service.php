<?php

namespace App\GoogleClassroom;

use Google_Client;
use Google_Service_Classroom;
use Exception;
use App\GoogleClassroom\Traits\Assignment;
use App\GoogleClassroom\Traits\Course;
use App\GoogleClassroom\Traits\Error;
use App\GoogleClassroom\Traits\Student;
use App\GoogleClassroom\Traits\Submission;

class Service
{
    use Assignment;
    use Course;
    use Error;
    use Student;
    use Submission;

    const CREDENTIALS = __DIR__ . "/../../auth/credentials.json";

    const TOKEN = __DIR__ . "/../../auth/token.json";

    const SCOPES = [
        Google_Service_Classroom::CLASSROOM_ANNOUNCEMENTS_READONLY,
        Google_Service_Classroom::CLASSROOM_COURSES_READONLY, Google_Service_Classroom::CLASSROOM_COURSEWORK_ME_READONLY,
        Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS_READONLY,
        Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS_READONLY,
        Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
        Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_STUDENTS_READONLY,
        Google_Service_Classroom::CLASSROOM_TOPICS_READONLY
    ];

    protected $client;

    public $service;

    public function __construct()
    {
        $this->createClient();

        if (file_exists(self::TOKEN)) {
            $this->getToken();
        }

        if ($this->client->isAccessTokenExpired()) {
            $this->createToken();
        }

        $this->service = new Google_Service_Classroom($this->client);
    }

    protected function createClient()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName("Google Classroom API PHP");
        $this->client->setScopes([
            Google_Service_Classroom::CLASSROOM_ANNOUNCEMENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSES_READONLY, Google_Service_Classroom::CLASSROOM_COURSEWORK_ME_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
            Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_TOPICS_READONLY
        ]);

        try {
            $this->client->setAuthConfig(self::CREDENTIALS);
        } catch (\Google\Auth\Cache\InvalidArgumentException $e) {
            return false;
        }

        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    protected function getToken()
    {
        $accessToken = json_decode(file_get_contents(self::TOKEN), true);
        $this->client->setAccessToken($accessToken);
    }

    protected function createToken()
    {
        // Refresh the token if possible, else fetch a new one.
        if ($this->client->getRefreshToken()) {
            $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $this->client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
            $this->client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname(self::TOKEN))) {
            mkdir(dirname(self::TOKEN), 0700, true);
        }
        file_put_contents(self::TOKEN, json_encode($this->client->getAccessToken()));
    }
}
