<?php

/**
 * @file
 * Tests event dispatching.
 */
namespace VendorPatches20220611\cweagans\Composer\Tests;

use VendorPatches20220611\cweagans\Composer\PatchEvent;
use VendorPatches20220611\cweagans\Composer\PatchEvents;
use VendorPatches20220611\Composer\Package\PackageInterface;
class PatchEventTest extends \VendorPatches20220611\PHPUnit_Framework_TestCase
{
    /**
     * Tests all the getters.
     *
     * @dataProvider patchEventDataProvider
     */
    public function testGetters($event_name, PackageInterface $package, $url, $description)
    {
        $patch_event = new PatchEvent($event_name, $package, $url, $description);
        $this->assertEquals($event_name, $patch_event->getName());
        $this->assertEquals($package, $patch_event->getPackage());
        $this->assertEquals($url, $patch_event->getUrl());
        $this->assertEquals($description, $patch_event->getDescription());
    }
    public function patchEventDataProvider()
    {
        $prophecy = $this->prophesize('VendorPatches20220611\\Composer\\Package\\PackageInterface');
        $package = $prophecy->reveal();
        return array(array(PatchEvents::PRE_PATCH_APPLY, $package, 'https://www.drupal.org', 'A test patch'), array(PatchEvents::POST_PATCH_APPLY, $package, 'https://www.drupal.org', 'A test patch'));
    }
}
