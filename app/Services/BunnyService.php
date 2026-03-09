<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class BunnyService
{
    protected string $storageZoneId;
    protected string $apiKey;
    protected string $cdnUrl;
    protected string $hostname = 'storage.bunnycdn.com';

    public function __construct()
    {
        $this->storageZoneId = env('BUNNY_STORAGE_ZONE_ID');
        $this->apiKey        = env('BUNNY_API_KEY');
        $this->cdnUrl        = rtrim(env('BUNNY_CDN_URL'), '/');
    }

    /**
     * Upload a PDF file to Bunny CDN storage.
     * Returns the public CDN URL or throws on failure.
     */
    public function uploadPdf(UploadedFile $file, string $folder = 'pdf'): string
    {
        $extension = $file->getClientOriginalExtension() ?: 'pdf';
        $filename  = $folder . '/' . uniqid('book_', true) . '.' . $extension;

        $url = "https://{$this->hostname}/{$this->storageZoneId}/{$filename}";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_POSTFIELDS     => file_get_contents($file->getRealPath()),
            CURLOPT_HTTPHEADER     => [
                'AccessKey: ' . $this->apiKey,
                'Content-Type: application/pdf',
            ],
        ]);

        $response   = curl_exec($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 201) {
            throw new \Exception("Bunny CDN upload failed. HTTP {$httpCode}: {$response}");
        }

        return $this->cdnUrl . '/' . $filename;
    }

    /**
     * Delete a file from Bunny CDN storage by its full CDN URL.
     */
    public function deletePdf(string $cdnUrl): void
    {
        // Convert CDN URL back to storage path
        $path = str_replace($this->cdnUrl . '/', '', $cdnUrl);

        $url = "https://{$this->hostname}/{$this->storageZoneId}/{$path}";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_HTTPHEADER     => [
                'AccessKey: ' . $this->apiKey,
            ],
        ]);

        curl_exec($ch);
        curl_close($ch);
    }
}
