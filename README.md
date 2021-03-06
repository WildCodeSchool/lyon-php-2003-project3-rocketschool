# Rocket School

[![Build Status](https://travis-ci.com/WildCodeSchool/lyon-php-2003-project3-rocketschool.svg?token=vxA4AusVVxs5jx1s6rqR&branch=master)](https://travis-ci.com/WildCodeSchool/lyon-php-2003-project3-rocketschool)

It's symfony website-skeleton project with some additional tools to validate code standards.

* GrumPHP, as pre-commit hook, will run 2 tools when `git commit` is run :

    * PHP_CodeSniffer to check PSR2
    * PHPStan will check PHP recommendation

  If tests fail, the commit is canceled and a warning message is displayed to developper.

* Travis CI, as Continuous Integration will be run when a branch with active pull request is updated on github. It will run :

    * Tasks to check if vendor, .idea, env.local are not versionned,
    * PHP_CodeSniffer to check PSR2,
    * PHPStan will check PHP recommendation.

### Prerequisites

1. Check composer is installed
2. Check yarn & node are installed

### Install

1. Clone this project
2. Run `composer install`
3. Run `yarn install`

### Working
1. Copy paste `.env` in new file `.env.local` configure your mysql database
2. Run `php bin/console doctrine:migration:migrate` to set the database
3. Run `php bin/console doctrine:fixtures:load` to load fixtures (make sur you have Faker required, if you don't, Run `composer req  --dev fzaninotto/faker
`)

4. Run `symfony server:start` to launch your local php web server
5. Run `yarn run dev` to launch your local server for assets

### Testing

1. Run `./bin/phpcs` to launch PHP code sniffer
2. Run `./bin/phpstan analyse src --level max` to launch PHPStan
3. Run `./bin/phpmd src text phpmd.xml` to launch PHP Mess Detector
3. Run `./bin/eslint assets/js` to launch ESLint JS linter
3. Run `./bin/sass-lint -c sass-linter.yml` to launch Sass-lint SASS/CSS linter

### Windows Users

If you develop on Windows, you should edit you git configuration to change your end of line rules with this command :

`git config --global core.autocrlf true`

## Deployment

=> Prerequisites :
    1. Check composer is installed
    2. Check yarn & node are installed

1. Clone this project
2. Run `composer install`
3. Run `yarn install`
4. Copy paste `.env` in new file `.env.local`
    - configure your mysql database
    - configure your app id and secret foreach social network
    - configure your MailerDNS
5. Run `php bin/console doctrine:database:create` to create the database
6. Run `php bin/console doctrine:migration:migrate` to set the database
7. Run `php bin/console doctrine:fixtures:load --group=groupProd` to load fixtures
8. Run `yarn encore production` to build assets, css and js
9. Start the server
Add additional notes about how to deploy this on a live system


## Built With

* [Symfony](https://github.com/symfony/symfony)
* [GrumPHP](https://github.com/phpro/grumphp)
* [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
* [PHPStan](https://github.com/phpstan/phpstan)
* [PHPMD](http://phpmd.org)
* [ESLint](https://eslint.org/)
* [Sass-Lint](https://github.com/sasstools/sass-lint)
* [Travis CI](https://github.com/marketplace/travis-ci)

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.


## Functionality for deleting users whose accounts are older than the desired number of days (100 by default)

The command to run in the terminal, so as to launch the old accounts deleting is :
    `$ php bin/console app:delete-old-accounts`

CAUTION : So as to be fully operational, it requires to use the cron program on the server :
   to open and edit cron actions in crantab file, run : 
   `$ crontab -e`
   
   add this line at the end of the file (this will delete old acounts every days at 22h00) : 
   `00 22 * * *  php bin/console app:delete-old-accounts`

## Functionality for login with social network

Set up your app on :
https://developers.google.com/
https://www.linkedin.com/developers/
https://developers.facebook.com/apps/

Get your app id and secret foreach social network
and set up in .env.local file

## Functionality for reset password

Change mail informations in src/Controller/ResetPasswordController.php line 183


## Functionality for email sending :

1 / Set up your MAILER_DSN in .env
    `MAILER_DSN=gmail://USERNAME:PASSWORD@default`

2 / configure the options of your Google account and check the "Allow less secure applications:
https://myaccount.google.com/lesssecureapps
