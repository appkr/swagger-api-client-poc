<?php

namespace Test;

use Appkr\SwaggerPocApi\Model\ReviewDto;
use Appkr\SwaggerPocApi\Model\ReviewListResponse;

class ReviewApiTest extends SwaggerPocApiTester
{
    public function testCreateReview()
    {
        $authorizationString = $this->getAuthString();
        $productDto = $this->getAnyProductDto();

        $reviewDto = $this->getReviewApi()->createReview(
            $authorizationString,
            $productDto->getId(),
            $this->getNewReviewRequest()
        );

        $this->dump($reviewDto);
        $this->assertInstanceOf(ReviewDto::class, $reviewDto);
    }

    public function testListReviews()
    {
        $authorizationString = $this->getAuthString();
        $productDto = $this->getAnyProductDto();

        $reviewListResponse = $this->getReviewApi()
            ->listReviews($authorizationString, $productDto->getId());

        $this->dump($reviewListResponse);
        $this->assertInstanceOf(ReviewListResponse::class, $reviewListResponse);
    }

    public function testUpdateReview()
    {
        $authorizationString = $this->getAuthString();
        $productDto = $this->getAnyProductDto();
        $reviewDto = $this->getReviewApi()->createReview(
            $authorizationString,
            $productDto->getId(),
            $this->getNewReviewRequest()
        );
        $modifiedReviewRequest = $this->getNewReviewRequest([
            'title' => 'REVIEW TITLE MODIFIED',
        ]);

        $modifiedReviewDto = $this->getReviewApi()
            ->updateReview(
                $authorizationString,
                $productDto->getId(),
                $reviewDto->getId(),
                $modifiedReviewRequest
            );

        $this->dump($modifiedReviewDto);
        $this->assertInstanceOf(ReviewDto::class, $modifiedReviewDto);
    }

    public function testDeleteReview()
    {
        $authorizationString = $this->getAuthString();
        $productDto = $this->getAnyProductDto();
        $reviewDto = $this->getReviewApi()->createReview(
            $authorizationString,
            $productDto->getId(),
            $this->getNewReviewRequest()
        );

        $return = $this->getReviewApi()->deleteReview(
            $authorizationString,
            $productDto->getId(),
            $reviewDto->getId()
        );

        $this->assertNull($return);
    }
}