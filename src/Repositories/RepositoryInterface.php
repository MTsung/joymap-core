<?php

namespace Mtsung\JoymapCore\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    function model(): Model;
}
