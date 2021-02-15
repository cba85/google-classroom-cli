# Google Classroom

A CLI to use Google Classroom API.

Fetches courses, students, assignments and student-submitted URLs from Google Classroom.

## Why?

I want to use Google Classroom to manage my classes (students and assignments).
I'd like to fetch URLs (containing Github repositories) submitted by the students for each assignments to automatically retrieve their projet.

That's why this projet only uses Google Classroom read-only API permissions and just some ressources of the API useful for myself are available.

## Requirements

### Composer

Make sure [Composer](https://getcomposer.org/download/) is installed globally.

## Install

```bash
$ composer global require cba85/google-classroom-cli
```

Then make sure you have the global Composer binaries directory in your PATH.

This directory is platform-dependent, see [Composer documentation](https://getcomposer.org/doc/03-cli.md#composer-home) for details.

### Update

```bash
$ composer global update cba85/google-classroom-cli
```

### Google Classroom API setup

1. [Create an app and web client auth on Google Developer Console](https://developers.google.com/classroom/quickstart/php)
2. Download the `credentials.json` file and move it to the `auth/` folder of the application.
3. First time you launch the application, you'll have to grant access of your Google application using Google OAuth2 server to retrieve an access token. Then, enter the verification code into the CLI prompt. Your access token will be saved in `auth/token.json` file.

## Usage

```bash
$ gclass list-courses
$ gclass list-students courseId
$ gclass list-assignments courseId
$ gclass list-submissions courseId assignmentId
$ gclass list-submitted-urls courseId assignmentId
```

## Notes

- `list-submitted-urls` command will list all the urls submitted by the students if the "course work" type is "assignment". If the "course work" type is a "short answer question", it will retrieve the answer and expect it to be an url.

## Dependencies

- [Symfony console](https://symfony.com/doc/current/components/console.html)
- [Google Classroom API](https://github.com/googleapis/google-api-php-client)

## Documentation

- https://developers.google.com/classroom/quickstart/php
- https://developers.google.com/classroom/reference/rest/
- https://github.com/googleapis/google-api-php-client-services/blob/master/src/Google/Service/Classroom.php
