<?php

namespace App\Services;

/**
 * Interface ICacheService
 * 
 * Interface para o serviço de cache.
 */
interface ICacheService
{
    /**
     * Obtém um item do cache com base na chave fornecida.
     *
     * @param mixed $key A chave do item a ser obtido.
     * @return mixed|null O valor armazenado em cache correspondente à chave, ou null se não estiver presente.
     */
    public function get($key);

    /**
     * Armazena um item no cache com a chave e o valor fornecidos, com uma expiração opcional em minutos.
     *
     * @param mixed $key A chave do item a ser armazenado.
     * @param mixed $value O valor a ser armazenado no cache.
     * @param int $minutes O tempo de expiração em minutos.
     * @return void
     */
    public function put($key, $value, $minutes);
}
