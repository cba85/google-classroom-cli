<?php

namespace App\GoogleClassroom;

use Google_Client;
use Google_Service_Classroom;
use Exception;

class Service
{

    protected $client;

    protected string $credentials = __DIR__ . "/../../auth/credentials.json";

    protected string $token = __DIR__ . "/../../auth/token.json";

    public function __construct()
    {
        $this->createClient();

        // Load previously authorized token from a file, if it exists. The file token.json stores the user's access and refresh tokens, and is created automatically when the authorization flow completes for the first time.
        if (file_exists($this->token)) {
            $this->getToken();
        }

        // If there is no previous token or it's expired.
        if ($this->client->isAccessTokenExpired()) {
            $this->createToken();
        }
    }

    public function getService()
    {
        return new Google_Service_Classroom($this->client);
    }

    protected function createClient()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName("Google Classroom API PHP Quickstart");
        $this->client->setScopes([
            Google_Service_Classroom::CLASSROOM_ANNOUNCEMENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSES_READONLY, Google_Service_Classroom::CLASSROOM_COURSEWORK_ME_READONLY,
            Google_Service_Classroom::CLASSROOM_COURSEWORK_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_GUARDIANLINKS_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_ROSTERS_READONLY,
            Google_Service_Classroom::CLASSROOM_STUDENT_SUBMISSIONS_STUDENTS_READONLY,
            Google_Service_Classroom::CLASSROOM_TOPICS_READONLY
        ]);
        $this->client->setAuthConfig($this->credentials);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
    }

    protected function getToken()
    {
        $accessToken = json_decode(file_get_contents($this->token), true);
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
        if (!file_exists(dirname($this->token))) {
            mkdir(dirname($this->token), 0700, true);
        }
        file_put_contents($this->token, json_encode($this->client->getAccessToken()));
    }
}
