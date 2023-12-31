<?php

declare(strict_types=1);

namespace Symplify\VendorPatches\Tests\Differ;

use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\VendorPatches\Differ\PatchDiffer;
use Symplify\VendorPatches\Kernel\VendorPatchesKernel;
use Symplify\VendorPatches\ValueObject\OldAndNewFileInfo;

final class PatchDifferTest extends AbstractKernelTestCase
{
    private PatchDiffer $patchDiffer;

    protected function setUp(): void
    {
        $this->bootKernel(VendorPatchesKernel::class);

        $this->patchDiffer = $this->getService(PatchDiffer::class);
    }

    public function test(): void
    {
        $oldFileInfo = new SmartFileInfo(__DIR__ . '/PatchDifferSource/vendor/some/package/file.php.old');
        $newFileInfo = new SmartFileInfo(__DIR__ . '/PatchDifferSource/vendor/some/package/file.php');

        $oldAndNewFileInfo = new OldAndNewFileInfo($oldFileInfo, $newFileInfo, 'some/package');

        $diff = $this->patchDiffer->diff($oldAndNewFileInfo);
        $this->assertStringEqualsFile(__DIR__ . '/PatchDifferFixture/expected_diff.php', $diff);
    }
}
