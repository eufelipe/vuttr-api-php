<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ToolRepository;
use App\Http\Requests\ToolsRequest;
use App\Http\Resources\ToolResource;

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



    /**
     * Funcionalidade para criar um novo registro.
     * 
     * @param \App\Http\Requests\ToolsRequest
     * @return \App\Http\Resources\ToolResource
     */
    public function store(ToolsRequest $request)
    {
        $data = $request->all();
        $tool = $this->toolRepository->create($data);

        return new ToolResource($tool);
    }


    /**
     * Método para atualizar um registro.
     * 
     * @param \App\Http\Requests\ToolsRequest
     * @return \App\Http\Resources\ToolResource
     */
    public function update(ToolsRequest $request, int $id)
    {
        $data = $request->all();
        $tool = $this->toolRepository->update($data, $id);

        return new ToolResource($tool);
    }
}
