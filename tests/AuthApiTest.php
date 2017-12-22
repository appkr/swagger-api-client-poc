<?php

namespace Test;

use Appkr\SwaggerPocApi\ApiException;
use Appkr\SwaggerPocApi\Model\AccessToken;
use Appkr\SwaggerPocApi\Model\ErrorDto;
use Appkr\SwaggerPocApi\Model\UserDto;
use Illuminate\Http\Response;

class AuthApiTest extends SwaggerPocApiTester
{
    public function testLoginOk()
    {
        $accessToken = $this->login();

        $this->dump($accessToken);
        $this->assertInstanceOf(AccessToken::class, $accessToken);
    }

    public function testInvalidCredential()
    {
        try {
            $this->login($this->getLoginRequest([
                'email' => 'not-existing@example.com',
                'password' => 'wrong-password',
            ]));
        } catch (ApiException $e) {
            /** @var ErrorDto $errorDto */
            $errorDto = $e->getResponseObject();
        }

        $this->dump($errorDto);
        $this->assertInstanceOf(ErrorDto::class, $errorDto);
        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $errorDto->getCode());
    }

    public function testLogout()
    {
        $authorizationString = $this->getAuthString();

        $return = $this->getAuthApi()->logout($authorizationString);

        $this->assertNull($return);
    }

    public function testRefresh()
    {
        $authorizationString = $this->getAuthString();

        $accessToken = $this->getAuthApi()->refreshToken($authorizationString);

        $this->dump($accessToken);
        $this->assertInstanceOf(AccessToken::class, $accessToken);
    }

    public function testMe()
    {
        $authorizationString = $this->getAuthString();

        $me = $this->getAuthApi()->me($authorizationString);

        $this->dump($me);
        $this->assertInstanceOf(UserDto::class, $me);
    }
}