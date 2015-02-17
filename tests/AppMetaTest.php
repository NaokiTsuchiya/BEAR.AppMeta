<?php

namespace BEAR\AppMeta;

use BEAR\AppMeta\Exception\AppNameException;

class AppMetaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AppMeta
     */
    protected $skeleton;

    protected function setUp()
    {
        parent::setUp();
        $this->skeleton = new AppMeta('FakeVendor\HelloWorld');
    }

    public function testNew()
    {
        $actual = $this->skeleton;
        $this->assertInstanceOf('\BEAR\AppMeta\AppMeta', $actual);
    }

    public function testAppReflectorResourceList()
    {
        $appMeta = new AppMeta('FakeVendor\HelloWorld');
        foreach ($appMeta->getResourceListGenerator() as list($class, $file)) {
            $classes[] = $class;
            $files[] = $file;
        }
        $expect = [
            'FakeVendor\HelloWorld\Resource\App\One',
            'FakeVendor\HelloWorld\Resource\App\Two',
            'FakeVendor\HelloWorld\Resource\App\User',
            'FakeVendor\HelloWorld\Resource\Page\Index',
            'FakeVendor\HelloWorld\Resource\App\Sub\Three',
            'FakeVendor\HelloWorld\Resource\App\Sub\Sub\Four'        ];
        $this->assertSame($expect, $classes);
        $expect = [
            $appMeta->appDir . '/src/Resource/App/One.php',
            $appMeta->appDir . '/src/Resource/App/Two.php',
            $appMeta->appDir . '/src/Resource/App/User.php',
            $appMeta->appDir . '/src/Resource/Page/Index.php',
            $appMeta->appDir . '/src/Resource/App/Sub/Three.php',
            $appMeta->appDir . '/src/Resource/App/Sub/Sub/Four.php'
        ];
        $this->assertSame($expect, $files);
    }

    public function testInvalidName()
    {
        $this->setExpectedException(AppNameException::class);
        new AppMeta('Invalid\Invalid');
    }
}
