<?php

namespace PreisLandoHoverNavigation\Api\Resources;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Response;
use IO\Services\ItemSearch\Factories\VariationSearchFactory;
use IO\Services\ItemSearch\Services\ItemSearchService;

class SearchPriceResource extends Controller
{
    private $response;
    private $searchFactory;
    private $searchService;

    public function __construct(
        Response $response,
        VariationSearchFactory $searchFactory,
        ItemSearchService $searchService
    )
    {
        $this->response = $response;
        $this->searchFactory = $searchFactory;
        $this->searchService = $searchService;
    }

    public function show(int $variationId)
    {
        try
        {
            /*
             * Suche für genau diese Variante aufbauen.
             * Preis wird für den aktuellen Shopkunden
             * berechnet und dem Ergebnis hinzugefügt.
             */
            $this->searchFactory
                ->hasVariationId($variationId)
                ->hasPriceForCustomer()
                ->withPrices([]);

            /*
             * Die Factory baut nur die Suche.
             * ItemSearchService führt sie tatsächlich aus.
             */
            $result = $this->searchService->getResult(
                $this->searchFactory
            );

            /*
             * Vorerst das vollständige Ergebnis zurückgeben.
             * Danach greifen wir gezielt den Preis heraus.
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
                    'success'     => false,
                    'variationId' => $variationId,
                    'error'       => $e->getMessage()
                ],
                500
            );
        }
    }
}
