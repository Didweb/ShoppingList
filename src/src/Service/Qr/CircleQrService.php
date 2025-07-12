<?php
namespace App\Service\Qr;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\Service\Qr\QrServiceInterface;

class CircleQrService implements QrServiceInterface
{
    private const VERSION = 1;
    private const PATH_QR_CIRCLES = '/qr_codes';
    private string $pathCompleto;
    private ?string $payload = null;


    public function __construct(private string $projectDir)
    {
        $this->pathCompleto = $projectDir . '/public' . self::PATH_QR_CIRCLES;
    }

    public function payload(): string
    {
        return $this->payload;
    }

    public function generatePayload(int $circleId): string
    {
        $this->payload = json_encode([
            'type' => 'circle',
            'id' => $circleId,
            'v' => self::VERSION,
        ]);

        return $this->payload;
    }

    public function getQrImageBase64FromFile(string $filePath): string
    {
    
        if (!file_exists($this->pathCompleto)) {
            throw new \RuntimeException("Archivo QR no encontrado: $this->pathCompleto");
        }

        $pngData = file_get_contents($this->pathCompleto);
        return base64_encode($pngData);
    }

    public function generateAndSaveQrImage(int $circleId): void
    {
        $options = new QROptions([
            'outputType' => 'png',
            'scale' => 6,
        ]);

        if ($this->payload == null) {
            throw new \RuntimeException("NOT Payload: $this->pathCompleto");
        }

        $qrCode = new QRCode($options);
        $pngData = $qrCode->render($this->payload);

        $dir = dirname($this->pathCompleto);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($this->pathCompleto . '/qr_'.$circleId.'.png', $pngData);
    }

    public function parsePayload(string $payload): array
    {
        $data = json_decode($payload, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Payload JSON inválido');
        }

        
        if (!isset($data['v']) || $data['v'] !== self::VERSION) {
            throw new \InvalidArgumentException('Versión de payload no soportada');
        }

       
        if (!isset($data['type']) || $data['type'] !== 'circle') {
            throw new \InvalidArgumentException('Tipo de payload incorrecto');
        }

        if (!isset($data['id'])) {
            throw new \InvalidArgumentException('Falta id en payload');
        }

        return $data;
    }


}