<?php
namespace Aura\Intl;

/**
 * Test class for Package.
 * Generated by PHPUnit on 2012-10-27 at 22:46:01.
 */
class PackageLocatorTest extends \PHPUnit_Framework_TestCase
{
    protected $packages;

    protected function setUp()
    {
        parent::setUp();
        $this->packages = new PackageLocator([
            'Vendor.Foo' => [
                'en_US' => function () {
                    return new \Aura\Intl\Package;
                },
            ],
        ]);
    }

    public function testGet()
    {
        // get once to create it the first time
        $first = $this->packages->get('Vendor.Foo', 'en_US');
        $this->assertInstanceOf('Aura\Intl\Package', $first);

        // get again to make sure it's the same object
        $again = $this->packages->get('Vendor.Foo', 'en_US');
        $this->assertSame($first, $again);

        // try for an unregistered package
        $this->setExpectedException('Aura\Intl\Exception');
        $this->packages->get('Vendor.Bar', 'en_US');
    }
}