<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class LenTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    private $testData;

    public function testRemoveKeywordsLen()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            
            $kpCount= $keywordProcessor->count();
            $kpCountExpected= array_reduce($testCase['keyword_dict'], function ($carry , $item ) {
                return $carry + count($item);
            });
                
            $this->assertEquals($kpCountExpected, $kpCount, sprintf('keyword processor length doesn\'t match for Text ID %s', $testId));
            
            $keywordProcessor->removeKeywordsFromAssocArray($testCase['remove_keyword_dict']);

            $kpDecressedCount= $keywordProcessor->count();
            $kpDecressedCountExpected = array_reduce($testCase['remove_keyword_dict'], function ($carry , $item ) {
                return $carry + count($item);
            });

            $this->assertEquals($kpCountExpected - $kpDecressedCountExpected, $kpDecressedCount, sprintf('keyword processor length doesn\'t match for Text ID %s', $testId));
        }
    }

    public function testRemoveKeywordsDictionaryLen()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordProcessor = new KeywordProcessor();
            $keywordProcessor->addKeywordsFromAssocArray($testCase['keyword_dict']);
            $keywordProcessor->removeKeywordsFromAssocArray($testCase['remove_keyword_dict']);

            $kpCount= $keywordProcessor->count();

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
            $kpCountTwo = $keywordProcessorTwo->count();
            $this->assertEquals($kpCountTwo, $kpCount, sprintf('keyword processor length doesn\'t match for Text ID %s', $testId));
        }
    }


    protected function setUp()
    {
        $testData = file_get_contents(__DIR__ . '/keyword_remover_test_cases.json');
        $this->testData = json_decode($testData, true);
    }


}
