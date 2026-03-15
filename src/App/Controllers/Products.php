<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\ProductRepository;
use Valitron\Validator;

class Products
{
    public function __construct(private ProductRepository $repository, private Validator $validator)
    {
        $this->validator->mapFieldsRules([
            'name' => ['required'],
            'description' => ['required'],
            'size' => ['required', 'integer', ['min', 1]],
        ]);
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

        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));

            return $response->withStatus(422);
        }

        $id = $this->repository->create($body);

        $body = json_encode([
            'id' => $id,
            'message' => 'Product created successfully'
        ]);

        $response->getBody()->write($body);

        return $response->withStatus(201);
    }

    public function update(Request $request, Response $response, string $id): Response
    {
        $body = $request->getParsedBody();

        $this->validator = $this->validator->withData($body);

        if (!$this->validator->validate()) {
            $response->getBody()->write(json_encode($this->validator->errors()));

            return $response->withStatus(422);
        }

        $rows = $this->repository->update((int) $id, $body);

        $body = json_encode([
            'rows' => $rows,
            'message' => 'Product updated'
        ]);

        $response->getBody()->write($body);

        return $response;
    }

    public function delete(Request $request, Response $response, string $id): Response
    {
        $rows = $this->repository->delete($id);

        $body = json_encode([
            'rows' => $rows,
            'message' => 'Product deleted'
        ]);

        $response->getBody()->write($body);

        return $response;
    }
}