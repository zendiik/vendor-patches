<?php

declare (strict_types=1);
namespace Symplify\VendorPatches\Differ;

use VendorPatches202211\Nette\Utils\Strings;
use VendorPatches202211\SebastianBergmann\Diff\Differ;
use VendorPatches202211\Symplify\SmartFileSystem\SmartFileInfo;
use VendorPatches202211\Symplify\SymplifyKernel\Exception\ShouldNotHappenException;
use Symplify\VendorPatches\ValueObject\OldAndNewFileInfo;
/**
 * @see \Symplify\VendorPatches\Tests\Differ\PatchDifferTest
 */
final class PatchDiffer
{
    /**
     * @see https://regex101.com/r/0O5NO1/5
     * @var string
     */
    private const LOCAL_PATH_REGEX = '#vendor(?:\\\|\\/)[^\\\\\/]+(?:\\\|\\/)[^\\\\\/]+(?:\\\|\\/)(?<local_path>.*?)$#is';
    /**
     * @see https://regex101.com/r/vNa7PO/1
     * @var string
     */
    private const START_ORIGINAL_REGEX = '#^--- Original#';
    /**
     * @see https://regex101.com/r/o8C90E/1
     * @var string
     */
    private const START_NEW_REGEX = '#^\\+\\+\\+ New#m';
    /**
     * @var \SebastianBergmann\Diff\Differ
     */
    private $differ;
    public function __construct(Differ $differ)
    {
        $this->differ = $differ;
    }
    public function diff(OldAndNewFileInfo $oldAndNewFileInfo) : string
    {
        $oldFileInfo = $oldAndNewFileInfo->getOldFileInfo();
        $newFileInfo = $oldAndNewFileInfo->getNewFileInfo();
        $diff = $this->differ->diff($oldFileInfo->getContents(), $newFileInfo->getContents());
        $patchedFileRelativePath = $this->resolveFileInfoPathRelativeFilePath($newFileInfo);
        $clearedDiff = Strings::replace($diff, self::START_ORIGINAL_REGEX, '--- /dev/null');
        return Strings::replace($clearedDiff, self::START_NEW_REGEX, '+++ ' . $patchedFileRelativePath);
    }
    private function resolveFileInfoPathRelativeFilePath(SmartFileInfo $beforeFileInfo) : string
    {
        $match = Strings::match($beforeFileInfo->getRealPath(), self::LOCAL_PATH_REGEX);
        if (!isset($match['local_path'])) {
            throw new ShouldNotHappenException();
        }
        return '../' . $match['local_path'];
    }
}
