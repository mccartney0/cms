<?php

namespace App\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function view(string $view, array $data = []): void
    {
        $this->response->view($view, $data);
    }

    protected function redirect(string $to): void
    {
        $this->response->redirect($to);
    }
}
