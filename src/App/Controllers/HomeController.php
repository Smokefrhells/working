<?php

namespace App\Controllers;

use App\View;

class HomeController
{
    public function index()
    {
        return new View('<h1>This is a home page</h1>');
    }
}
