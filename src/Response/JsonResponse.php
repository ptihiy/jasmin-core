<?php

namespace Jasmin\Core\Response;

class JsonResponse implements ResponseInterface
{
    protected ?array $headers = ['Content-type: application/json'];

    public function __construct(protected mixed $data)
    {
    }

    public function setHeaders(): void
    {
        array_map('header', $this->headers);
    }

    public function data(): mixed
    {
        return json_encode($this->data);
    }
}
