<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class LogoHelper
{

    public static function addTeamLogos(
        array $teams,
    ): array {

        return self::addLocalLogos(
            $teams,
            'teams',
        );
    }

    public static function addLeagueLogos(
        array $leagues,
    ): array {

        return self::addLocalLogos(
            $leagues,
            'leagues',
        );
    }
    private static function addLocalLogos(
        array $items,
        string $directory,
    ): array {

        logger('addLocalLogos');
        try {
            foreach ($items as &$item) {
                $localPath = public_path("logos/{$directory}/{$item['id']}.png");
                $item['local_logo'] = file_exists($localPath)
                    ? asset("logos/{$directory}/{$item['id']}.png")
                    : self::downloadAndSaveLogo($item['logo'], $localPath, $directory, $item['id']);
                ;
            }
        } catch (\Exception $e) {
            \Log::error('Error adding local logos: ' . $e->getMessage());
        }

        return $items;
    }

    private static function downloadAndSaveLogo(
        string $url,
        string $localPath,
        string $directory,
        int $id
    ): ?string {

        logger($url);
        logger($localPath);
        logger($directory);
        logger($id);

        try {
            File::ensureDirectoryExists(public_path("logos/{$directory}"));

            // Download the logo from the API
            $response = Http::get($url);

            if ($response->successful()) {
                File::put($localPath, $response->body());
                return asset("logos/{$directory}/{$id}.png");
            }
        } catch (\Exception $e) {
            \Log::error("Failed to download logo for ID {$id}: " . $e->getMessage());
        }

        return null; // Return null if the logo could not be downloaded
    }
}