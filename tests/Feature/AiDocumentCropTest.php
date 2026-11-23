<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerPrintController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiDocumentCropTest extends TestCase
{
    public function test_ai_endpoint_url_auto_upgrades_http_to_https_for_rootvabd()
    {
        $controller = new CustomerPrintController();
        
        // When configured with http://importentapi.rootvabd.com/python_api/proxy.php
        config(['services.python_ai.url' => 'http://importentapi.rootvabd.com/python_api/proxy.php']);
        
        $cropUrl = $controller->getAiEndpointUrl('auto-crop-document');
        $cornerUrl = $controller->getAiEndpointUrl('detect-document-corners');

        $this->assertStringStartsWith('https://importentapi.rootvabd.com', $cropUrl);
        $this->assertStringContainsString('endpoint=auto-crop-document', $cropUrl);
        $this->assertStringStartsWith('https://importentapi.rootvabd.com', $cornerUrl);
        $this->assertStringContainsString('endpoint=detect-document-corners', $cornerUrl);
    }

    public function test_ai_endpoint_url_preserves_standard_fastapi_host()
    {
        $controller = new CustomerPrintController();
        config(['services.python_ai.url' => 'http://127.0.0.1:8000']);
        
        $cropUrl = $controller->getAiEndpointUrl('auto-crop-document');
        $this->assertEquals('http://127.0.0.1:8000/auto-crop-document', $cropUrl);
    }

    public function test_auto_crop_document_endpoint_returns_base64_image_on_success()
    {
        Http::fake([
            'https://importentapi.rootvabd.com/*' => Http::response('FAKE_IMAGE_BINARY_DATA', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $file = UploadedFile::fake()->image('test_nid.jpg', 600, 400);

        $response = $this->post(route('api.ai.auto_crop'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'source' => 'python_ai',
        ]);
        $this->assertStringStartsWith('data:image/jpeg;base64,', $response->json('image_data'));
    }

    public function test_detect_corners_endpoint_returns_coordinates_on_success()
    {
        Http::fake([
            'https://importentapi.rootvabd.com/*' => Http::response([
                'success' => true,
                'corners' => [
                    ['x' => 0.1, 'y' => 0.1],
                    ['x' => 0.9, 'y' => 0.1],
                    ['x' => 0.9, 'y' => 0.9],
                    ['x' => 0.1, 'y' => 0.9],
                ],
            ], 200),
        ]);

        $file = UploadedFile::fake()->image('test_nid.jpg', 600, 400);

        $response = $this->post(route('api.ai.detect_corners'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'corners' => [
                ['x' => 0.1, 'y' => 0.1],
                ['x' => 0.9, 'y' => 0.1],
                ['x' => 0.9, 'y' => 0.9],
                ['x' => 0.1, 'y' => 0.9],
            ],
        ]);
    }

    public function test_auto_crop_fails_gracefully_when_upstream_errors()
    {
        Http::fake([
            'https://importentapi.rootvabd.com/*' => Http::response(['detail' => 'Method Not Allowed'], 405),
        ]);

        $file = UploadedFile::fake()->image('test_nid.jpg', 600, 400);

        $response = $this->post(route('api.ai.auto_crop'), [
            'file' => $file,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('405', $response->json('message'));
    }
}

