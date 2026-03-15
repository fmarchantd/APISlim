<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\ProductRepository;

class Products
{
    public function __construct(private ProductRepository $repository)
    {
    }
    public function show(Request $request, Response $response, string $id): Response
    {
        $product = $request->getAttribute('product');

        $body = json_encode($product);

        $response->getBody()->write($body);

        return $response;
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $request->getParsedBody();

        $id = $this->repository->create($body);

        $body = json_encode([
            'id' => $id,
            'message' => 'Product created successfully'
        ]);

        $response->getBody()->write($body);

        return $response->withStatus(201);
    }
}