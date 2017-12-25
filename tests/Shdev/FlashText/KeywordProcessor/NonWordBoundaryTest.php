<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45.
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class NonWordBoundaryTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneral()
    {
        $keywordProcessor = new KeywordProcessor();

        $this->assertEquals(KeywordProcessor::INIT_NON_WORD_BOUNDARIES, implode('', $keywordProcessor->getNonWordBoundaries()));
        
        $keywordProcessor->setNonWordBoundaries(['a', '1']);

        $this->assertEquals(['a', '1'], $keywordProcessor->getNonWordBoundaries());

        $keywordProcessor->addNonWordBoundaries('b');
        $this->assertEquals(['a', '1', 'b'], $keywordProcessor->getNonWordBoundaries());
    }
}
