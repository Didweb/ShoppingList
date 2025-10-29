<?php
namespace App\Service\Qr;

interface QrServiceInterface
{
    public function generatePayload(int $circleId): string;

    public function getQrImageBase64FromFile(int $idCircle): string;

    public function generateAndSaveQrImage(int $circleId): void;

    public function parsePayload(string $payload): array;    
}