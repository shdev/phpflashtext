<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45.
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException \Shdev\FlashText\FileReadException
     */
    public function testFileReadError()
    {
        $keywordProcessor = new KeywordProcessor();

        $keywordProcessor->addKeywordFromFile('missing file');
    }
}
