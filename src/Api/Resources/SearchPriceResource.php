<?php

namespace PreisLandoHoverNavigation\Api\Resources;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Response;
use Plenty\Plugin\Http\Request;

class SearchPriceResource extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct(
        Request $request,
        Response $response
    )
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Preis für einen Treffer der Schnellsuche zurückgeben.
     *
     * @param int $variationId
     * @return Response
     */
    public function show(int $variationId): Response
    {
        /*
         * Im ersten Schritt testen wir bewusst nur,
         * ob unser eigener öffentlicher Plugin-Endpunkt
         * korrekt funktioniert.
         *
         * Die eigentliche plentyShop-Preisermittlung
         * kommt danach hinein.
         */
        return $this->response->json(
            [
                'success'     => true,
                'variationId' => $variationId,
                'price'       => null
            ]
        );
    }
}
