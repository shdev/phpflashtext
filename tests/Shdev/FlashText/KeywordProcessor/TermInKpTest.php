<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class TermInKpTest extends \PHPUnit_Framework_TestCase
{

    public function testTermInDictionary()
    {
        $keywordProcessor= new KeywordProcessor();
        $keywordProcessor->addKeyword('j2ee', 'Java');
        $keywordProcessor->addKeyword('colour', 'color');
        $keywordProcessor->getKeyword('j2ee');

        $this->assertEquals('Java', $keywordProcessor->getKeyword('j2ee'));
        $this->assertEquals('color', $keywordProcessor->getKeyword('colour'));
        $this->assertEquals(null, $keywordProcessor->getKeyword('Test'));
        $this->assertTrue($keywordProcessor->contains('colour'));
        $this->assertFalse($keywordProcessor->contains('Test'));
    }
}
