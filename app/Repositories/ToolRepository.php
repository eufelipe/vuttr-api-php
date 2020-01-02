<?php

namespace App\Repositories;

use App\Models\Tool;

class ToolRepository extends AbstractRepository
{
    public function model()
    {
        return Tool::class;
    }
}
