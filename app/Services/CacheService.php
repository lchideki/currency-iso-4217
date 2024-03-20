<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Services\ICacheService;

/**
 * Class CacheService
 * 
 * Serviço para interagir com o sistema de cache do Laravel.
 */
class CacheService implements ICacheService
{
    /**
     * Obtém o valor armazenado no cache com a chave especificada.
     *
     * @param string $key A chave do cache.
     * @return mixed|null O valor armazenado no cache, ou null se não encontrado.
     */
    public function get($key)
    {
        return Cache::get($key);   
    }

    /**
     * Armazena um valor no cache com a chave e o tempo de expiração especificados.
     *
     * @param string $key A chave para armazenar o valor no cache.
     * @param mixed $value O valor a ser armazenado no cache.
     * @param int $timing O tempo de vida do cache em minutos.
     * @return void
     */
    public function put($key, $value, $timing)
    {
        Cache::put($key, $value, $timing); 
    }
}
