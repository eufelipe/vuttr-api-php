<?php

namespace App\Observers;

use App\Models\Tool;
use Illuminate\Support\Facades\Cache;

class ToolObserver
{

    const TOOLS_CACHE_KEY = 'api::tools';

    /**
     * Método para limpar (zerar) os registros no cache.
     */
    public function clear_cache()
    {
        Cache::forget(self::TOOLS_CACHE_KEY);
    }

    /**
     * Handle the tool "created" event.
     *
     * @param  \App\Tool  $tool
     * @return void
     */
    public function created(Tool $tool)
    {
        $this->clear_cache();
    }

    /**
     * Handle the tool "updated" event.
     *
     * @param  \App\Tool  $tool
     * @return void
     */
    public function updated(Tool $tool)
    {
        $this->clear_cache();
    }

    /**
     * Handle the tool "deleted" event.
     *
     * @param  \App\Tool  $tool
     * @return void
     */
    public function deleted(Tool $tool)
    {
        $this->clear_cache();
    }

    /**
     * Handle the tool "restored" event.
     *
     * @param  \App\Tool  $tool
     * @return void
     */
    public function restored(Tool $tool)
    {
        $this->clear_cache();
    }

    /**
     * Handle the tool "force deleted" event.
     *
     * @param  \App\Tool  $tool
     * @return void
     */
    public function forceDeleted(Tool $tool)
    {
        $this->clear_cache();
    }
}
