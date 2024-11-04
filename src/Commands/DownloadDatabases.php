<?php

declare(strict_types=1);

namespace BaseCodeOy\GeoIp2\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use PharData;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
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

                if (\str_ends_with($edition, '-CSV')) {
                    if (File::isDirectory($this->getExtractPath($edition))) {
                        File::deleteDirectory($this->getExtractPath($edition));
                    }

                    $phar = new PharData($this->getCompressed($edition));
                    $phar->extractTo($this->getExtractPath($edition));

                    File::delete($this->getCompressed($edition));
                } else {
                    $this->decompress($edition);

                    $this->extract($edition);
                }

                $this->move($edition);
            } catch (Throwable $th) {
                $this->error($th->getMessage());
            }
        }
    }

    private function download(string $edition): void
    {
        if (File::exists($this->getCompressed($edition))) {
            File::delete($this->getCompressed($edition));
        }

        File::put(
            $this->getCompressed($edition),
            Http::get($this->getLink($edition))->throw()->body(),
        );
    }

    private function decompress(string $edition): void
    {
        if (File::exists($this->getDecompressed($edition))) {
            File::delete($this->getDecompressed($edition));
        }

        $phar = new PharData($this->getCompressed($edition));
        $phar->decompress();

        File::delete($this->getCompressed($edition));
    }

    private function extract(string $edition): void
    {
        if (File::isDirectory($this->getExtractPath($edition))) {
            File::deleteDirectory($this->getExtractPath($edition));
        }

        $phar = new PharData($this->getDecompressed($edition));
        $phar->extractTo($this->getExtractPath($edition));

        File::delete($this->getDecompressed($edition));
    }

    private function move(string $edition): void
    {
        $extension = 'mmdb';

        if (\str_ends_with($edition, '-CSV')) {
            $extension = 'csv';
        }

        $finder = Finder::create();
        $finder
            ->in($this->getExtractPath($edition))
            ->depth(1)
            ->filter(fn (SplFileInfo $file) => \str_ends_with($file->getPathname(), '.'.$extension));

        $iterator = $finder->getIterator();
        $iterator->rewind();

        File::move(
            $iterator->current()->getPathname(),
            $this->getDecompressedFilePath($edition),
        );

        File::deleteDirectory($this->getExtractPath($edition));
    }

    private function getCompressed(string $edition): string
    {
        if (\str_ends_with($edition, '-CSV')) {
            return \sprintf('%s/%s.zip', Config::get('geoip2.storage_path'), $edition);
        }

        return \sprintf('%s/%s.tar.gz', Config::get('geoip2.storage_path'), $edition);
    }

    private function getDecompressed(string $edition): string
    {
        return \sprintf('%s/%s.tar', Config::get('geoip2.storage_path'), $edition);
    }

    private function getDecompressedFilePath(string $edition): string
    {
        if (\str_ends_with($edition, '-CSV')) {
            return \sprintf('%s/%s.csv', Config::get('geoip2.storage_path'), $edition);
        }

        return \sprintf('%s/%s.mmdb', Config::get('geoip2.storage_path'), $edition);
    }

    private function getExtractPath(string $edition): string
    {
        return \sprintf('%s/%s', Config::get('geoip2.storage_path'), $edition);
    }

    private function getLink(string $edition): string
    {
        if (\str_ends_with($edition, '-CSV')) {
            return \sprintf('https://download.maxmind.com/app/geoip_download?edition_id=%s&license_key=%s&suffix=zip', $edition, Config::get('geoip2.license_key'));
        }

        return \sprintf('https://download.maxmind.com/app/geoip_download?edition_id=%s&license_key=%s&suffix=tar.gz', $edition, Config::get('geoip2.license_key'));
    }
}
