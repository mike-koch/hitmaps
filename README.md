# HITMAPS
HITMAPS is an interactive site for viewing maps for the Hitman series.

# Getting Started
1. Fork the repo to your own GitLab account
2. In the Config folder, make the following changes:
    1. Copy Settings.php.example to Settings.php (don't rename it as this example file should stay in source control). Then fill out the following properties:
        1. `databaseHost`: Your MySQL server's host location
        2. `databaseUser`: Your MySQL server database user
        3. `databasePassword`: The password to your MySQL database
        4. `databaseName`: The name to your MySQL database
        5. `accessKey`: A random string
        6. `loggingEnvironment`: Keep this 'development'
        7. `loggingAccessToken`: If you want to use Rollbar integration for logging,
        put your Rollbar token here.
        8. `superSecretPublicCode`: The registration code to register an account
        9. `recaptchaSiteKey`: Use '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
        10. `recaptchaSiteSecret`: Use '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'
        11. `emailMethod`: Use either 'SMTP' or 'MAILGUN'
        12. `smtp[Domain|Username|Password]`: SMTP server information if using SMTP
        13. `noReplyEmail`: The "from" address when sending out emails
        14. `bccEmail`: An email address if you want a copy of all emails sent out copied to 1 address
        15. `mailgun[ApiKey|Domain]`: Mailgun API information if using Mailgun
    2. If you want to have push notification support, you will have to create a
     Firebase account and project, and then export the `firebase-service-account.json` file to this folder
3. Copy `phinx.yml.example` to `phinx.yml` and update the `database` section with your MySQL database's information
4. Run `composer install` in the root folder to install all necessary `composer` dependencies
5. Run `vendor\bin\phinx migrate -e development` in the root folder to run all necessary database migrations
6. Run `php -S localhost:8000` to run the local PHP development server and get started with development 🙂

# Contributing
1. Open a new issue in the issues section explaining what change / feature you want to work on. That way, the project owner is aware of the change you want to make and can help with any questions, etc.
2. Make the necessary changes on your fork, making sensible commits along the way
3. Open a merge request to submit your changes once they are complete

# Legal
HITMAN™, HITMAN™ 2, the HITMAN™ logo, images, and text are the property of IO Interactive.