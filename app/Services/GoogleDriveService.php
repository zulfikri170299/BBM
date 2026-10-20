<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleDriveService
{
    /**
     * Ekstrak Google Drive Folder ID dari berbagai format link atau raw ID.
     */
    public static function extractFolderId(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }

        $input = trim($input);

        // Format: https://drive.google.com/drive/folders/{FOLDER_ID}
        // Format: https://drive.google.com/drive/u/0/folders/{FOLDER_ID}?usp=sharing
        if (preg_match('/folders\/([a-zA-Z0-9_-]+)/', $input, $matches)) {
            return $matches[1];
        }

        // Format: https://drive.google.com/open?id={FOLDER_ID}
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $input, $matches)) {
            return $matches[1];
        }

        // Jika user memasukkan langsung alphanumeric ID (biasanya 25-45 karakter)
        if (preg_match('/^[a-zA-Z0-9_-]{15,}$/', $input)) {
            return $input;
        }

        return null;
    }

    /**
     * Dapatkan Google OAuth2 Access Token menggunakan Google Service Account JSON (RS256 JWT).
     */
    public static function getAccessTokenFromServiceAccount(array $credentials): string
    {
        if (empty($credentials['client_email']) || empty($credentials['private_key'])) {
            throw new Exception('Format kredensial Service Account tidak valid. Harus memiliki client_email dan private_key.');
        }

        $clientEmail = $credentials['client_email'];
        $privateKey = $credentials['private_key'];
        $tokenUri = $credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token';

        $now = time();
        $jwtHeader = self::base64UrlEncode(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ]));

        $jwtPayload = self::base64UrlEncode(json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive',
            'aud' => $tokenUri,
            'exp' => $now + 3600,
            'iat' => $now,
        ]));

        $dataToSign = $jwtHeader . '.' . $jwtPayload;
        $signature = '';

        $keyResource = openssl_pkey_get_private($privateKey);
        if (!$keyResource) {
            throw new Exception('Private key Service Account tidak dapat dibaca oleh OpenSSL. Pastikan format key utuh.');
        }

        if (!openssl_sign($dataToSign, $signature, $keyResource, OPENSSL_ALGO_SHA256)) {
            throw new Exception('Gagal menandatangani JWT Service Account: ' . openssl_error_string());
        }

        $jwtSignature = self::base64UrlEncode($signature);
        $assertion = $dataToSign . '.' . $jwtSignature;

        $response = Http::asForm()->timeout(20)->post($tokenUri, [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $assertion,
        ]);

        if (!$response->successful()) {
            $err = $response->json('error_description') ?? $response->body();
            throw new Exception('Gagal menukarkan token Google: ' . $err);
        }

        $accessToken = $response->json('access_token');
        if (!$accessToken) {
            throw new Exception('Google tidak mengembalikan access_token.');
        }

        return $accessToken;
    }

    /**
     * Upload file ke folder Google Drive menggunakan Service Account.
     */
    public static function uploadViaServiceAccount(string $filePath, string $fileName, string $folderId, array $credentials): array
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');

        if (!file_exists($filePath)) {
            throw new Exception("File backup lokal tidak ditemukan di: {$filePath}");
        }

        $accessToken = self::getAccessTokenFromServiceAccount($credentials);
        $fileSize = filesize($filePath);
        $fileContent = file_get_contents($filePath);
        $mimeType = str_ends_with($fileName, '.sqlite') ? 'application/x-sqlite3' : 'application/sql';

        $metadata = [
            'name' => $fileName,
            'parents' => [$folderId],
        ];

        // Buat multipart payload standar Google Drive API
        $boundary = '-------' . md5(time()) . '-------';
        $delimiter = "\r\n--" . $boundary . "\r\n";
        $closeDelimiter = "\r\n--" . $boundary . "--";

        $multipartBody = $delimiter
            . "Content-Type: application/json; charset=UTF-8\r\n\r\n"
            . json_encode($metadata)
            . $delimiter
            . "Content-Type: {$mimeType}\r\n"
            . "Content-Transfer-Encoding: base64\r\n\r\n"
            . chunk_split(base64_encode($fileContent))
            . $closeDelimiter;

        $uploadUrl = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'multipart/related; boundary=' . $boundary,
            'Content-Length' => strlen($multipartBody),
        ])->timeout(180)->withBody($multipartBody, 'multipart/related; boundary=' . $boundary)->post($uploadUrl);

        if (!$response->successful()) {
            $errorData = $response->json('error') ?? [];
            $message = $errorData['message'] ?? $response->body();

            if (str_contains($message, 'File not found') || str_contains($message, 'parents')) {
                throw new Exception("Folder Google Drive tidak ditemukan atau Service Account belum diberikan akses Editor ke folder tersebut. Silakan bagikan (Share) folder Drive ke email Service Account: {$credentials['client_email']} (sebagai Editor).");
            }

            throw new Exception("Gagal mengunggah file ke Google Drive: {$message}");
        }

        return [
            'file_id' => $response->json('id'),
            'file_name' => $response->json('name'),
            'file_size' => $fileSize,
        ];
    }

    /**
     * Upload melalui Google Apps Script Web App (Simpel tanpa Google Cloud Console).
     */
    public static function uploadViaWebhook(string $filePath, string $fileName, string $folderId, string $webhookUrl): array
    {
        @set_time_limit(300);
        @ini_set('max_execution_time', '300');

        if (!file_exists($filePath)) {
            throw new Exception("File backup lokal tidak ditemukan: {$filePath}");
        }

        $fileContent = base64_encode(file_get_contents($filePath));
        $mimeType = str_ends_with($fileName, '.sqlite') ? 'application/x-sqlite3' : 'application/sql';

        try {
            // Gunakan allow_redirects false agar kita dapat menangani redirect 302 dari Google Apps Script secara presisi via GET
            $response = Http::withOptions([
                'allow_redirects' => false,
                'connect_timeout' => 30,
            ])->timeout(240)->post($webhookUrl, [
                'filename' => $fileName,
                'folder_id' => $folderId,
                'mimetype' => $mimeType,
                'file_data' => $fileContent,
            ]);

            // Jika Google Apps Script mengembalikan 301/302/303 redirect, ikuti URL redirect tersebut menggunakan method GET
            if ($response->status() >= 300 && $response->status() < 400 && $response->header('Location')) {
                $redirectUrl = $response->header('Location');
                $response = Http::timeout(60)->get($redirectUrl);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new Exception('Koneksi timeout saat mengunggah ke Google Drive. Proses memakan waktu lebih dari batas toleransi atau koneksi internet terputus. Silakan coba lagi.');
        } catch (\Throwable $e) {
            throw new Exception('Gagal menghubungi Web App Google Drive: ' . $e->getMessage());
        }

        if (!$response->successful()) {
            throw new Exception('Gagal menghubungi Web App Google Drive (Status ' . $response->status() . '): ' . substr($response->body(), 0, 200));
        }

        $res = $response->json();
        if (!$res) {
            if (str_contains($response->body(), 'success')) {
                return [
                    'file_id' => 'uploaded',
                    'file_name' => $fileName,
                    'file_size' => filesize($filePath),
                ];
            }
            throw new Exception('Respons Web App Google tidak valid: ' . substr($response->body(), 0, 150));
        }

        if (isset($res['status']) && $res['status'] === 'error') {
            throw new Exception('Google Drive gagal menyimpan file: ' . ($res['message'] ?? 'Unknown error'));
        }

        return [
            'file_id' => $res['id'] ?? 'gdrive_uploaded',
            'file_name' => $fileName,
            'file_size' => filesize($filePath),
        ];
    }

    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
