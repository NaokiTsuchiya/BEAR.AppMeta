<?php
declare(strict_types=1);
/**
 * This file is part of the BEAR.AppMeta package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\AppMeta;

use Koriym\Psr4List\Psr4List;

abstract class AbstractAppMeta
{
    /**
     * Application name "{Vendor}\{Project}"
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $appDir;

    /**
     * @var string
     */
    public $tmpDir;

    /**
     * @var string
     */
    public $logDir;

    public function getResourceListGenerator() : \Generator
    {
        $list = new Psr4List;
        $resourceListGenerator = $list($this->name . '\Resource', $this->appDir . '/src/Resource');

        return $resourceListGenerator;
    }

    /**
     * @param string $scheme 'app' | 'page' | '*'
     */
    public function getUris(string $scheme = '*') : \Generator
    {
        foreach ($this->getResourceListGenerator() as list($class, $file)) {
            $paths = explode('\\', $class);
            $path = array_slice($paths, 3);
            array_walk($path, [$this, 'camel2snake']);
            if ($scheme === '*') {
                $uri = sprintf('%s://self/%s', $path[0], implode('/', array_slice($path, 1)));

                yield $uri;
            }
            if ($scheme === $path[0]) {
                $uri = sprintf('/%s', implode('/', array_slice($path, 1)));

                yield $uri;
            }
        }
    }

    private function camel2snake(&$str)
    {
        $str = ltrim(strtolower(preg_replace('/[A-Z]/', '_\0', $str)), '_');
    }
}
