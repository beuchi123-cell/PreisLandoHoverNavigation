<?php

namespace PreisLandoHoverNavigation\Api\Resources;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Response;
use IO\Services\ItemSearch\Factories\VariationSearchFactory;

class SearchPriceResource extends Controller
{
    /**
     * @var Response
     */
    private $response;

    /**
     * @var VariationSearchFactory
     */
    private $searchFactory;

    public function __construct(
        Response $response,
        VariationSearchFactory $searchFactory
    )
    {
        $this->response = $response;
        $this->searchFactory = $searchFactory;
    }

    public function show(int $variationId)
    {
        try
        {
            /*
             * Nur die gewünschte Variante suchen
             */
            $this->searchFactory
                ->hasVariationId($variationId)
                ->hasPriceForCustomer()
                ->withPrices([]);

            /*
             * Suche ausführen
             */
            $result = $this->searchFactory->search();

            /*
             * Zunächst komplette Struktur zurückgeben.
             * Damit sehen wir exakt, wo der Preis bei
             * deiner plentyShop-Version liegt.
             */
            return $this->response->json(
                [
                    'success'     => true,
                    'variationId' => $variationId,
                    'result'      => $result
                ]
            );
        }
        catch (\Throwable $e)
        {
            return $this->response->json(
                [
                    'success' => false,
                    'variationId' => $variationId,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
