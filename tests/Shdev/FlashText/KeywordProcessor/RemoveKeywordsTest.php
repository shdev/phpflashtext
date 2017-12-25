<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class RemoveKeywordsTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $testData;

    public function testRemoveKeywords()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor= new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordProcessor->removeKeywordsFromAssocArray($testCase['remove_keyword_dict']);

            $keywordsExtracted = $keywordProcessor->extractKeywords($testCase['sentence']);

            $this->assertEquals($testCase['keywords'], $keywordsExtracted, sprintf('keywords_extracted don\'t match the expected results for test case: %s', $testId));
        }
    }

    public function testRemoveKeywordsUsingList()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);

            foreach ($testCase['remove_keyword_dict'] as $values) {
                $keywordProcessor->removeKeywordFromArray($values);
            }

            $keywordsExtracted= $keywordProcessor->extractKeywords($testCase['sentence']);
            $this->assertEquals($testCase['keywords'], $keywordsExtracted, sprintf('keywords_extracted don\'t match the expected results for test case: %s', $testId));
        }
    }

    public function testRemoveKeywordsDictionaryCompare()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordProcessor->removeKeywordsFromAssocArray($testCase['remove_keyword_dict']);

            $keywordTrieDict = $keywordProcessor->getKeywordTrieDict();

            $newDictionary= [];
            foreach ($testCase['keyword_dict'] as $key => $values) {
                foreach ($values as $value) {
                    if (!(isset($testCase['remove_keyword_dict'][$key]) && in_array($value, $testCase['remove_keyword_dict'][$key], true)) ) {
                        if (isset($newDictionary[$key])) {
                            $newDictionary[$key][] = $value;
                        } else {
                            $newDictionary[$key] = [$value];
                        }
                    }
                }
            }

            $keywordProcessorTwo = new KeywordProcessor();
            $keywordProcessorTwo->addKeywordsFromAssocArray($newDictionary);
            $keywordTrieDictTwo = $keywordProcessorTwo->getKeywordTrieDict();
            $this->assertEquals($keywordTrieDict, $keywordTrieDictTwo, sprintf('keywords_extracted don\'t match the expected results for test case:  %s', $testId));
        }
    }


    protected function setUp()
    {
        $testData = file_get_contents(__DIR__ . '/keyword_remover_test_cases.json');
        $this->testData = json_decode($testData, true);
    }

}
