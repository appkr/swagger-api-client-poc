<?php

namespace Test;

use Appkr\SwaggerPocApi\ApiException;
use Appkr\SwaggerPocApi\Model\ErrorDto;
use Appkr\SwaggerPocApi\Model\ProductDto;
use Appkr\SwaggerPocApi\Model\ProductListResponse;
use Appkr\SwaggerPocApi\ObjectSerializer;
use Illuminate\Http\Response;

class ProductApiTest extends SwaggerPocApiTester
{
    public function testCreateProduct()
    {
        $authorizationString = $this->getAuthString();
        $newProductRequest = $this->getNewProductRequest();

        $productDto = $this->getProductApi()
            ->createProduct($authorizationString, $newProductRequest);

        $this->dump($productDto);
        $this->assertInstanceOf(ProductDto::class, $productDto);
    }

    public function testUnauthorizedUserCannotCreateProduct()
    {
        $authorizationString = $this->getAuthString(
            $this->getLoginRequest([
                'email' => 'user@example.com',
                'password' => 'secret',
            ])
        );
        $newProductRequest = $this->getNewProductRequest();

        try {
            $this->getProductApi()
                ->createProduct($authorizationString, $newProductRequest);
        } catch (ApiException $e) {
            $content = json_decode($e->getResponseBody());
            /** @var ErrorDto $errorDto */
            $errorDto = ObjectSerializer::deserialize($content, ErrorDto::class);
        }

        $this->dump($errorDto);
        $this->assertInstanceOf(ErrorDto::class, $errorDto);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $errorDto->getCode());
    }

    public function testRequestValidation()
    {
        $authorizationString = $this->getAuthString();
        $newProductRequest = $this->getNewProductRequest([
            'title' => '',
        ]);

        try {
            $this->getProductApi()
                ->createProduct($authorizationString, $newProductRequest);
        } catch (ApiException $e) {
            $content = json_decode($e->getResponseBody());
            /** @var ErrorDto $errorDto */
            $errorDto = ObjectSerializer::deserialize($content, ErrorDto::class);
        }

        $this->dump($errorDto);
        $this->assertInstanceOf(ErrorDto::class, $errorDto);
        $this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $errorDto->getCode());
    }

    public function testListProducts()
    {
        $authorizationString = $this->getAuthString();

        $productListResponse = $this->getProductApi()->listProducts($authorizationString);

        $this->dump($productListResponse);
        $this->assertInstanceOf(ProductListResponse::class, $productListResponse);
    }

    public function testUpdateProduct()
    {
        $authorizationString = $this->getAuthString();
        $newProductRequest = $this->getNewProductRequest();
        $productDto = $this->getProductApi()
            ->createProduct($authorizationString, $newProductRequest);

        $modifyProductRequest = $this->getNewProductRequest([
            'title' => 'PRODUCT TITLE MODIFIED',
            'price' => 200,
        ]);

        $modifiedProductDto = $this->getProductApi()
            ->updateProduct($authorizationString, $productDto->getId(), $modifyProductRequest);

        $this->dump($modifiedProductDto);
        $this->assertInstanceOf(ProductDto::class, $modifiedProductDto);
    }

    public function testDeleteProduct()
    {
        $authorizationString = $this->getAuthString();
        $newProductRequest = $this->getNewProductRequest();
        $productDto = $this->getProductApi()
            ->createProduct($authorizationString, $newProductRequest);

        $return = $this->getProductApi()
            ->deleteProduct($authorizationString, $productDto->getId());

        $this->assertNull($return);
    }
}