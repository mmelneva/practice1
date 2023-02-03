<?php namespace Diol\LaravelSweetTooltip\Controllers;

use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class AssetsController extends Controller
{
    public function css()
    {
        $content = file_get_contents(__DIR__ . '/../resources/sweet-tooltip.css');
        $response = new Response($content, 200, ['Content-Type' => 'text/css']);

        return $this->cacheResponse($response);
    }

    public function js()
    {
        $content = file_get_contents(__DIR__ . '/../resources/sweet-tooltip.js');
        $response = new Response($content, 200, ['Content-Type' => 'text/javascript']);

        return $this->cacheResponse($response);
    }


    protected function cacheResponse(Response $response)
    {
        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }
}
