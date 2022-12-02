<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\FileSystem;

use VendorPatches202211\Nette\Utils\Strings;
use VendorPatches202211\Symplify\SmartFileSystem\SmartFileInfo;
use VendorPatches202211\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
final class PathResolver
{
    /**
     * @see https://regex101.com/r/KhzCSu/2
     * @var string
     */
    private const VENDOR_PACKAGE_DIRECTORY_REGEX = '#^(?<vendor_package_directory>.*?vendor(?:\\/|\\\)(?:\w|\.|\-)+(?:\\/|\\\)(?:\w|\.|\-)+)(?:\\/|\\\)#si';
    public function resolveVendorDirectory(SmartFileInfo $fileInfo) : string
    {
        $match = Strings::match($fileInfo->getRealPath(), self::VENDOR_PACKAGE_DIRECTORY_REGEX);
        if (!isset($match['vendor_package_directory'])) {
            throw new ShouldNotHappenException('Could not resolve vendor package directory');
        }
        return $match['vendor_package_directory'];
    }
}
