<?php

declare(strict_types=1);

namespace BombenProdukt\GeoIp2\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PharData;
use Throwable;

final class DownloadDatabases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geoip2:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize GeoIP2 databases';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        File::ensureDirectoryExists(Config::get('geoip2.storage_path'));

        foreach (Config::get('geoip2.editions') as $edition) {
            try {
                $this->info(\sprintf('Downloading %s', $edition));

                $this->download($edition);

                $this->decompress($edition);

                $this->extract($edition);
            } catch (Throwable $th) {
                $this->error($th->getMessage());
            }
        }
    }

    private function download(string $edition): void
    {
        File::put(
            $this->getCompressedPath($edition),
            Http::get($this->getLink($edition))->body(),
        );
    }

    private function decompress(string $edition): void
    {
        $phar = new PharData($this->getCompressedPath($edition));
        $phar->decompress();

        File::delete($this->getCompressedPath($edition));
    }

    private function extract(string $edition): void
    {
        $phar = new PharData($this->getDecompressedPath($edition));
        $phar->extractTo($this->getExtractPath($edition));

        File::delete($this->getDecompressedPath($edition));
    }

    private function getCompressedPath(string $edition): string
    {
        return \sprintf('%s/%s.tar.gz', Config::get('geoip2.storage_path'), $edition);
    }

    private function getDecompressedPath(string $edition): string
    {
        return \sprintf('%s/%s.tar', Config::get('geoip2.storage_path'), $edition);
    }

    private function getExtractPath(string $edition): string
    {
        return \sprintf('%s/%s', Config::get('geoip2.storage_path'), $edition);
    }

    private function getLink(string $edition): string
    {
        return \sprintf('https://download.maxmind.com/app/geoip_download?edition_id=%s&license_key=%s&suffix=tar.gz', $edition, Config::get('geoip2.license_key'));
    }
}
