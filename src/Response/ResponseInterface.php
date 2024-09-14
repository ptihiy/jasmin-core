<?php

namespace Jasmin\Core\Response;

interface ResponseInterface
{
    public function __construct($data);

    public function setHeaders(): void;

    public function data(): mixed;
}
