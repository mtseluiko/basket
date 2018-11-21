<?php

namespace App\Http\Listeners;

use App\Http\Exceptions\RequestParsingException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class JsonRequestListener
{

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $content = $request->getContent();
        if (!empty($content) && $this->isJson($request)) {
            $params = $this->getJsonParams($request);
            $this->setRequestAttributes($request, $params);
        }

    }


    private function isJson(Request $request): bool
    {
        return $request->getContentType() === 'json';
    }


    private function getJsonParams(Request $request): array
    {
        $data = json_decode($request->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RequestParsingException;
        }
        return $data;
    }

    private function setRequestAttributes(Request $request, array $params): void
    {
        $request->attributes->add($params);
    }
}