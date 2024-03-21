<?php

namespace App\Controllers;

use App\View;

class UsersController
{
    public function index()
    {
        return new View('<h1>This is a users page</h1>');
    }
}
