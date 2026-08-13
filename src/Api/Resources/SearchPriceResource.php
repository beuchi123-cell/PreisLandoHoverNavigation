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
     * Test-Endpunkt für die Schnellsuche.
     *
     * @param int $variationId
     */
    public function show(int $variationId)
    {
        return $this->response->json(
            [
                'success'     => true,
                'variationId' => $variationId,
                'price'       => null
            ]
        );
    }
}
