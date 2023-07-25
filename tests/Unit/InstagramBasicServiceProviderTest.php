<?php

namespace Tests\Unit;

use App\Services\InstagramAPIService;
use PHPUnit\Framework\TestCase;

class InstagramBasicServiceProviderTest extends TestCase
{
    use \Tests\CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createApplication();
    }
    /**
     * Test for getting user data
     */
    public function test_get_user_data(): void
    {
        $instagramService = new InstagramAPIService();
        $response = $instagramService->getUser();
        $this->assertNotNull($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('username', $response);
        $this->assertArrayHasKey('media_count', $response);
    }

    /**
     * Test for getting user medias
     */
    public function test_get_user_medias(): void
    {
        $instagramService = new InstagramAPIService();
        $response = $instagramService->getUserMedias();
        $this->assertNotNull($response);
        $this->assertArrayHasKey('data', $response);
    }

    /**
     * Test for getting media details
     */
    public function test_get_user_media_details(): void
    {
        $instagramService = new InstagramAPIService();
        $medias = $instagramService->getUserMedias();
        $this->assertNotNull($medias);

        $response = $instagramService->getMediaDetails($medias['data'][0]['id']);
        $this->assertNotNull($response);
        $this->assertArrayHasKey('media_url', $response);
        $this->assertArrayHasKey('media_type', $response);
    }

    /**
     * Test for getting media children
     */
    public function test_get_media_children(): void
    {
        $instagramService = new InstagramAPIService();
        $medias = $instagramService->getUserMedias();
        $this->assertNotNull($medias);
        $carousels = array_filter($medias['data'], function ($media) {
            return $media['media_type'] == 'CAROUSEL_ALBUM';
        });

        if (empty($carousels)) {
            $this->markTestSkipped('No carousel media found');
        }

        $carouselToTest = $carousels[array_rand($carousels)];

        if ($carouselToTest['media_type'] == 'CAROUSEL_ALBUM') {
            $this->assertArrayHasKey('children', $carouselToTest);
            $this->assertNotNull($carouselToTest['children']);
            $response = $instagramService->getMediaChildren($medias['data'][0]['id']);
            $this->assertNotNull($response);
            $this->assertEquals(count($carouselToTest['children']['data']), count($response['data']));
        }
    }
}
