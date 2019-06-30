# Soonr
## Minimal Runtime Environment [![Build Status](https://travis-ci.org/tulik/symfony-docker.svg?branch=master)](https://travis-ci.org/tulik/symfony-docker)  [![symfony 4 docker](https://img.shields.io/badge/dev-symfony%204-F7CA18.svg?style=flat)](https://symfony.com/4) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tulik/symfony-4-docker-runtime-env/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tulik/symfony-docker?branch=master)



<p align="center">
  <img src="https://raw.githubusercontent.com/tulik/symfony-4-docker-runtime-env/master/documentation/images/logo.png">
</p>

# Table of content
1. [See it working! Deployed with Travis and Kubernetes](#see-it-working)
2. [Environment architecture](#environment-architecture)
3. [Quick start](#quick-start)
4. [Configure Xdebug](#configure-xdebug)
5. [Directory structure](#directory-structure)

## See it working! 
**[Deployed with Travis and Kubernetes](https://symfony-4-docker-runtime-env.tulik.info/)**
 - Further documentation about deployment will be provied.
 
# Environment architecture

<p align="center">
  <img src="https://raw.githubusercontent.com/tulik/symfony-4-docker-runtime-env/master/documentation/images/schema.png">
</p>

Now it has mocked support for MySQL databases.

# Quick start

```
$ git clone https://github.com/lolerki/soonr.git
$ cd soorn
$ docker-compose up -d --build
```
Wait for containers to start, then to [http://localhost:8080](http://localhost:8080)

# Configure Xdebug
If you use another IDE than [PHPStorm](https://www.jetbrains.com/phpstorm/), go to the [remote debugging](https://xdebug.org/docs/remote) section of Xdebug documentation.

For a better integration of Docker to PHPStorm, use the [documentation](https://github.com/woprrr/symfony-4-skeleton-docker/blob/master/doc/phpstorm-macosx.md).

1. Edit docker-compose file `docker-compose.yml` edit/adjust the configuration as needed for `XDEBUG_CONFIG` AND `PHP_IDE_CONFIG` environment variables.

2. If needed add a server for PHP as explained @see [Add a debug server section](https://github.com/woprrr/symfony-4-skeleton-docker/blob/master/doc/phpstorm-macosx.md#add-a-debug-server).

# Directory structure
```
soonr
├── h2-proxy
│   └── conf.d
├── helm
│   └── symfony
│       ├── charts
│       └── templates
└── symfony
    ├── assets
    │   ├── js
    │   └── scss
    ├── bin
    ├── config
    │   ├── packages
    │   │   ├── dev
    │   │   ├── prod
    │   │   └── test
    │   └── routes
    ├── docker
    │   ├── nginx
    │   │   └── conf.d
    │   └── php
    ├── public
    └── src
        ├── Controller
        ├── Entity
        ├── Migrations
        └── Repository
        
```

<sub><sub>
<hr noshade color="#FFFFFF" width="100%" size="1" style="padding:0; margin:8px 0 8px 0; border:none; width:100%; height: 1px; color:#FFFFFF; background-color: #FFFFFF" />
