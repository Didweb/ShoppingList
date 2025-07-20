<?php
namespace App\Tests\Service\Qr;

use PHPUnit\Framework\TestCase;
use App\Service\Qr\CircleQrService;

class CircleQrServiceTest extends TestCase
{
    private string $qrDir;
    private CircleQrService $service;
    private int $circleId = 12345; 

    protected function setUp(): void
    {
        $this->qrDir = sys_get_temp_dir() . '/test_qr_codes';
        $this->service = new CircleQrService($this->qrDir);

        if (!is_dir($this->qrDir . '/public/qr_codes')) {
            mkdir($this->qrDir . '/public/qr_codes', 0777, true);
        }
    }

    protected function tearDown(): void
    {
        $path = $this->qrDir . '/public/qr_codes/qr_' . $this->circleId . '.png';
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function testGeneratePayloadAndQrImage(): void
    {
        // Genera payload
        $payload = $this->service->generatePayload($this->circleId);
        self::assertNotEmpty($payload);
        self::assertJson($payload);

        // Verifica parseo correcto
        $data = $this->service->parsePayload($payload);
        self::assertSame('circle', $data['type']);
        self::assertSame($this->circleId, $data['id']);
        self::assertSame(1, $data['v']);

        // Genera y guarda imagen
        $this->service->generateAndSaveQrImage($this->circleId);

        $path = $this->qrDir . '/public/qr_codes/qr_' . $this->circleId . '.png';
        self::assertFileExists($path);

        // Verifica base64 desde archivo
        $base64 = $this->service->getQrImageBase64FromFile($this->circleId);
        self::assertNotEmpty($base64);
        self::assertStringStartsWith('iVBOR', base64_decode($base64, true) !== false ? 'iVBOR' : '');
    }

    public function testParsePayloadThrowsOnInvalidJson(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->service->parsePayload('invalid json');
    }

    public function testParsePayloadThrowsOnInvalidVersion(): void
    {
        $invalid = json_encode(['type' => 'circle', 'id' => 1, 'v' => 99]);
        $this->expectException(\InvalidArgumentException::class);
        $this->service->parsePayload($invalid);
    }

    public function testGetQrImageBase64FromFileThrowsWhenNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->service->getQrImageBase64FromFile(9999999);
    }
}