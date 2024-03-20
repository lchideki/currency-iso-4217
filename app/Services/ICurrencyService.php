<?php

namespace App\Services;

/**
 * Interface ICurrencyService
 * 
 * Interface para o serviço de manipulação de moedas.
 */
interface ICurrencyService
{
     /**
     * Encontra moedas com base nos filtros fornecidos.
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @return array|null Um array contendo as moedas encontradas ou null se não houver correspondências.
     */
    public function find(array $filter): ?array;

    /**
     * Encontra moedas realizando um processo de rastreamento (crawling).
     *
     * @param array $filter Os filtros a serem aplicados na busca.
     * @return array|null Um array contendo as moedas encontradas ou null se não houver correspondências.
     */
    public function findCrawling(array $filter): ?array;
}
