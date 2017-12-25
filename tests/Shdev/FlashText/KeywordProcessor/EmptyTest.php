<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class EmptyTest extends \PHPUnit_Framework_TestCase
{
    public function testDictionaryLoading()
    {
        $keywordProcessor = new KeywordProcessor();

        $this->assertEquals([],$keywordProcessor->extractKeywords(''));
        $this->assertEquals('',$keywordProcessor->replaceKeywords(''));
    }
}
