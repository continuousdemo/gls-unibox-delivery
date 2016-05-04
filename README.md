# gls-unibox-delivery

[![License](https://img.shields.io/badge/license-MIT-blue.svg)]()
[![Build Status](https://status.continuousphp.com/git-hub/Power-LAB/gls-unibox-delivery?token=a4835457-770c-407c-99c7-43fc08781204)](https://continuousphp.com/git-hub/Power-LAB/gls-unibox-delivery)

This repository are an quick application let you able to build GLS GROUP parcels PDF 
through an light api request.

But you can also use it as an library and build yourself the PDF inside your application 
or build your own Generator for transform the GLS response to an other label format or tempalte. 

**:warning: The API doesn't contain any token or authentication process, 
if you want use it we recommend you to put an PAM auth on your webserver 
or run inside private subnet not accessible from the public endpoint.**

## Installation

- Create docker container

```bash
$ docker-compose up -d
```

## Examples

Once the container are start, you can immediately test the PDF generation by
execute the `parcel-request.sh` script who push recipients address from `playload.json`

```bash
$ cd tests/scratch && sh parcel-request.sh
{"file":"..... BASE_64 .....","parcels":[{"reference":"00015","tracking":"002EU1SJ"},{"reference":"1032548","tracking":"002EU1SK"}]}}
```

The default settings inside `src/public/app.php` have constant `__DEV_MODE__`.
If this constant exist, the parcel api resource will create you a `gls.pdf` and `gls.json` file inside the `public` directory
with the result of request.

## Standard

This project was build with all the standard recommendation and standard of GLS GROUP Documentation `GLS BOX FR V05.00 15-09-2014`

## Author

+ Tomasina Pierre