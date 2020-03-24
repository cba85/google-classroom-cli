# Google Classroom

A CLI to use Google Classroom API.

Fetches courses, students, assignments and student-submitted URLs from Google Classroom.

## Why?

I want to use Google Classroom to manage my classes (students and assignments).
I'd like to fetch URLs (containing Github repositories) submitted by the students for each assignments to automatically retrieve their projet.

That's why this projet only uses Google Classroom read-only API permissions and just some ressources of the API useful for myself are available.

## Install

```bash
$ composer install
$ chmod +x gclass
```

### Google Classroom API setup

1. Create an app and web client auth on Google Developer Console.
2. Download the `credentials.json` file and move it to the `auth/` folder of the application.
3. First time you launch the application, you'll have to grant access of your Google application using Google OAuth2 server to retrieve an access token. Then, enter the verification code into the CLI prompt. Your access token will be saved in `auth/token.json` file.

## Usage

```bash
./gclass list-courses # List your courses
./gclass list-students courseId # List the students of the course
./gclass list-works courseId # List the works of the course
```

## Dependencies

- [Symfony console](https://symfony.com/doc/current/components/console.html)
- [Google Classroom API](https://github.com/googleapis/google-api-php-client)

## Documentation

- https://developers.google.com/classroom/quickstart/php
- https://developers.google.com/classroom/reference/rest/
- https://github.com/googleapis/google-api-php-client-services/blob/master/src/Google/Service/Classroom.php
