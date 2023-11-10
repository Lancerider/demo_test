# Demo Test Project

This is a simple [Laravel](https://laravel.com/docs/10.x) application created with its built-in
solution [Sail](https://laravel.com/docs/8.x/sail) for running your Laravel project
using [Docker](https://www.docker.com/).

The application has its necessary configuration in order to run my-sql container as well.

## Requirements for building and running the application

-   [Composer](https://getcomposer.org/download/)
-   [Docker](https://docs.docker.com/get-docker/)

## Application code

The url for the repository is **https://github.com/Lancerider/demo_test**

Inside your console, go to the folder you want for your project and clone the project with the line:

`git clone https://github.com/Lancerider/demo_test.git` (for Https)

`git clone git@github.com:Lancerider/demo_test.git` (for SSH connection)

## Application Build and Run

After cloning the repository get into the directory and run:

`composer install`

Clone **.env.example** and name the clone as **.env**

If you work with a Linux based OS you can run:

`./vendor/bin/sail up`

If you have a windows machine, you will need to configure WSL2 first and run the previous steps in a linux console [WSL2](https://learn.microsoft.com/es-es/windows/wsl/install)

In the case it doesn't work as expected you can use VsCode and install the WSL extension. Having the extension you use the keywords: `ctrl + shift + p` and search for WSL: Connect to WSL.

The file in **./.devcontainer/devcontainer.json** will handle the config to trigger the docker containers in your local machine.

## Then finally test the application

Go to [http://localhost](http://localhost) in order to see the application running.
