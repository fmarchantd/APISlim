<?php
 use App\Database;

 return [
     Database::class => function() {
        return new Database(host: 'localhost', name: 'slimtutorial', user: 'root', password: 'mysql123');
     }
 ];