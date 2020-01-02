<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ToolRepository;

class ToolsController extends Controller
{


    public function __construct()
    {
        $this->toolRepository = resolve(ToolRepository::class);
    }


    public function index()
    {

        return $this->toolRepository->all();
    }
}
