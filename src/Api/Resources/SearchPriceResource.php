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
             * Exakt die gewünschte Variante suchen
             * und den für den aktuellen Shopkunden
             * gültigen Preis anhängen.
             */
            $this->searchFactory
                ->hasVariationId($variationId)
                ->hasPriceForCustomer()
                ->withPrices([]);

            $result = $this->searchService->getResult(
                $this->searchFactory
            );

            /*
             * Keine Variante gefunden
             */
            if (
                !isset($result['documents']) ||
                count($result['documents']) === 0
            )
            {
                return $this->response->json(
                    [
                        'success'     => false,
                        'variationId' => $variationId,
                        'price'       => null,
                        'error'       => 'Variation not found'
                    ],
                    404
                );
            }

            $document = $result['documents'][0];

            /*
             * Preis aus dem plentyShop-Suchergebnis.
             *
             * Erwartete Struktur:
             *
             * data
             *   -> prices
             *      -> default
             *         -> price
             *            -> formatted
             */
            $price = null;

            if (
                isset($document['data']['prices']['default']['price']['formatted'])
            )
            {
                $price =
                    $document['data']['prices']['default']['price']['formatted'];
            }

            return $this->response->json(
                [
                    'success'     => true,
                    'variationId' => $variationId,
                    'price'       => $price
                ]
            );
        }
        catch (\Throwable $e)
        {
            return $this->response->json(
                [
                    'success'     => false,
                    'variationId' => $variationId,
                    'price'       => null,
                    'error'       => $e->getMessage()
                ],
                500
            );
        }
    }
}
