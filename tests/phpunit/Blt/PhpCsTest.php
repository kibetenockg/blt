<?php

namespace Acquia\Blt\Tests\Blt;

use Acquia\Blt\Tests\BltTestBase;
use Symfony\Component\Process\Process;

/**
 * Class DrupalVM.
 *
 * Verifies that PhpCs works as expected.
 */
class PhpCsTest extends BltTestBase {

  /**
   * @group blt
   *
   * @dataProvider providerPhpCsFilesBootstrapProvider
   */
  public function testPhpCsFilesBootstrap($filename, $needle, $contains) {
    $process = new Process("./vendor/bin/phpcs -v $filename");
    $process->setWorkingDirectory($this->bltDirectory);
    $process->run();
    $output = $process->getOutput();
    if ($contains) {
      $this->assertContains($needle, $output);
    }
    else {
      $this->assertNotContains($needle, $output);
    }
  }

  /**
   * @return array
   */
  public function providerPhpCsFilesBootstrapProvider() {
    return [
      // Test ignored extension.
      ['CHANGELOG.md', 'Processing CHANGELOG.md', FALSE],
      // Test included file.
      ['RoboFile.php', 'Processing RoboFile.php', TRUE],
      // Test ignored directory.
      [
        'vendor/composer/autoload_classmap.php',
        'Processing RoboFile.php',
        FALSE,
      ],
    ];
  }

}
