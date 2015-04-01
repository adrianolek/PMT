Project Management Tool
=======================

[![Build Status](https://secure.travis-ci.org/adrianolek/PMT.png)](http://travis-ci.org/adrianolek/PMT)


A simple Project Management Tool providing following features:

* Users management
* Projects
* Tasks management
* Comments
* File uploads
* Time tracking
* API

Quick start
-----------

The fastest way to get the application running is to use [Docker](https://www.docker.io).
After [installing Docker](https://docs.docker.com/installation/#installation) you should run

    ./docker/docker.sh build

which will build docker images, and then

    ./docker/docker.sh start

which will start the application.

You should be able to access the application under [http://localhost](http://localhost) url.

Running tests
-------------

To run the test suite via Docker use

    ./docker/docker.sh test

If you don't have Docker installed, then use PHPUnit directly

    ./bin/phpunit -c app

Additional commands
-------------------

To see available commands run

    ./docker/docker.sh

To see available Ant targets run

    ./docker/docker.sh ant -p

Chrome App client
-----------------

There is a time tracking [client app](https://github.com/adrianolek/PMT-Client), which uses the API.
