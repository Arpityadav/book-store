# Getting started

## Installation
We recommend using Laravel Homestead

Clone the repository

    git clone git@github.com:ArpitYadav/book-store.git

Switch to the repo folder

    cd book-store

Install all the dependencies using composer

    composer install

Also run 

    npm install
    
followed by

    npm run dev

Copy the example env file and make the required configuration changes in the .env file

    cp .env.example .env

Generate a new application key

    php artisan key:generate

Run the database migrations and seeder (**Set the database connection in .env before migrating**)

    php artisan migrate --seed
**TL;DR command list**

    git clone git@github.com:ArpitYadav/book-store.git
    cd book-store
    composer install
    npm install
    npm run dev
    cp .env.example .env
    php artisan key:generate

**Make sure to use**
``SCOUT_DRIVER=elasticsearch``
in the .env

**Also make sure you set the correct database connection information before running the migrations** [Environment variables](#environment-variables)
    php artisan migrate --seed

**Using Elastic search**

Using Command line    
    
    php artisan scout:elasticsearch:create

or we can use postman to hit 

    http://url.test:9200/books

and using these raw parameters as body
    
    
    "settings": {
        "index": {
            "analysis": {
                "filter": {
                    "words_splitter": {
                        "type": "word_delimiter",
                        "preserve_original": "true",
                        "catenate.all": "true"
                    }
                },
                "analyzer": {
                    "default": {
                        "type": "custom",
                        "tokenizer": "standard",
                        "chor_filter": [
                            "html_strip"
                        ],
                        "filter": [
                            "lowercase",
                            "words_splitter"
                        ]
                    }
                }
            }
        }
    }


Finally running this command in the command line
    
    php artisan scout:import App\\Models\\Book

Postman Collection Available in the root folder.
