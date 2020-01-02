<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ToolRepository;
use App\Http\Requests\ToolsRequest;
use App\Http\Resources\ToolResource;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;


class ToolsController extends Controller
{

    /**
     * @var \App\Repositories\ToolRepository
     */
    private $toolRepository;

    const TOOLS_TIME_CACHE_IN_MINUTES = 60;
    const TOOLS_CACHE_KEY = 'api::tools';


    public function __construct()
    {
        $this->toolRepository = resolve(ToolRepository::class);
    }


    /**
     * Retorna a listagem de tools
     * 
     * @return array
     */
    public function index()
    {
        return $this->load_tools_from_cache(); 
    }

    /**
     * Método para retornar todos os registros a partir do cache.
     */
    public function load_tools_from_cache()
    {
        $timeCacheInMinutes = Carbon::now()->addMinutes(self::TOOLS_TIME_CACHE_IN_MINUTES);
        $tools = Cache::remember(self::TOOLS_CACHE_KEY, $timeCacheInMinutes, function () {
            $tools = $this->toolRepository->all();
            return ToolResource::collection($tools);
        });

        return $tools;
    }

    /**
     * Funcionalidade para criar um novo registro.
     * 
     * @param \App\Http\Requests\ToolsRequest $request
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
     * @param \App\Http\Requests\ToolsRequest $request
     * @return \App\Http\Resources\ToolResource
     */
    public function update(ToolsRequest $request, int $id)
    {
        $data = $request->all();
        $tool = $this->toolRepository->update($data, $id);

        return new ToolResource($tool);
    }

    /**
     * Método para deletar um registro
     * 
     * @param $id
     * 
     */
    public function destroy(int $id)
    {
        $this->toolRepository->delete($id);
        return response()->noContent();
    }
}
