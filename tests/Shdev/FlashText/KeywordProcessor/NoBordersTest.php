<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45.
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class NoBordersTest extends \PHPUnit_Framework_TestCase
{
    public function testNoBorders()
    {
        $keywordProcessor = new KeywordProcessor();

        $keywordProcessor->setNonWordBoundaries('');
        $keywordAssocArray = [
            'word1' => ['word1'],
            'word2' => ['word2'],
            'word3' => ['word3'],
        ];

        $keywordProcessor->addKeywordsFromAssocArray($keywordAssocArray);

        $sentence = 'word1word2word3';

        $keywordsExtracted = $keywordProcessor->extractKeywords($sentence);

        $this->assertEquals(['word1', 'word2', 'word3'], $keywordsExtracted);
    }
}
