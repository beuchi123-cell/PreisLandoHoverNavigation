<?php

namespace PreisLandoHoverNavigation\Api\Resources;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Response;
use IO\Services\ItemSearch\Factories\VariationSearchFactory;
use IO\Services\ItemSearch\Services\ItemSearchService;

class CategoryImageResource extends Controller
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

    public function show(int $categoryId)
    {
        try
        {
            /*
             * Erstes sichtbares/salebles Produkt
             * aus der gewünschten Kategorie.
             */
            $this->searchFactory
                ->isInCategory($categoryId)
                ->isActive()
                ->isVisibleForClient()
                ->isSalable()
                ->hasPriceForCustomer()
                ->withImages()
                ->withDefaultImage();

            /*
             * Nur 1 Treffer brauchen.
             */
            $this->searchFactory->setPage(1, 1);

            $result =
                $this->searchService->getResult(
                    $this->searchFactory
                );

            $image = null;

            if (
                isset($result['documents'][0]['data']['images']['all']) &&
                is_array(
                    $result['documents'][0]['data']['images']['all']
                )
            )
            {
                $images =
                    $result['documents'][0]['data']['images']['all'];

                if (
                    isset($images[0]['urlPreview']) &&
                    $images[0]['urlPreview']
                )
                {
                    $image =
                        $images[0]['urlPreview'];
                }
                elseif (
                    isset($images[0]['urlMiddle']) &&
                    $images[0]['urlMiddle']
                )
                {
                    $image =
                        $images[0]['urlMiddle'];
                }
                elseif (
                    isset($images[0]['url']) &&
                    $images[0]['url']
                )
                {
                    $image =
                        $images[0]['url'];
                }
            }

            return $this->response->json(
                [
                    'success'    => true,
                    'categoryId' => $categoryId,
                    'image'      => $image
                ]
            );
        }
        catch (\Throwable $e)
        {
            return $this->response->json(
                [
                    'success'    => false,
                    'categoryId' => $categoryId,
                    'image'      => null,
                    'error'      => $e->getMessage()
                ],
                500
            );
        }
    }
}
