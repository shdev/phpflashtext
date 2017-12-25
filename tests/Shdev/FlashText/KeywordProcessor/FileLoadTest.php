<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class FileLoadTest extends \PHPUnit_Framework_TestCase
{

    public function testFileFormatOne()
    {
        $keywordProcessor = new KeywordProcessor();
        $keywordProcessor->addKeywordFromFile(__DIR__ . '/keywords_format_one.txt');

        $sentence = 'I know java_2e and product management techniques';
        $keywordExtracted = $keywordProcessor->extractKeywords($sentence);
        $this->assertEquals(['java', 'product management'], $keywordExtracted);

        $sentenceNew = $keywordProcessor->replaceKeywords($sentence);
        $this->assertEquals('I know java and product management', $sentenceNew);
    }

    public function testFileFormatTwo()
    {
        $keywordProcessor = new KeywordProcessor();
        $keywordProcessor->addKeywordFromFile(__DIR__ . '/keywords_format_two.txt');

        $sentence = 'I know java and product management';
        $keywordExtracted = $keywordProcessor->extractKeywords($sentence);
        $this->assertEquals(['java', 'product management'], $keywordExtracted);

        $sentenceNew = $keywordProcessor->replaceKeywords($sentence);
        $this->assertEquals('I know java and product management', $sentenceNew);
    }
}
