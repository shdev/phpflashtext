<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class ReplacerTest extends \PHPUnit_Framework_TestCase
{

    /** @var array */
    private $testData;

    public function testReplaceKeywords()
    {
        foreach ($this->testData as $testId => $testCase) {
            $keywordReplacer = new KeywordProcessor();
            foreach ($testCase['keyword_dict'] as $key => $values) {
                foreach ($values as $value) {
                    $keywordReplacer->addKeyword($value, str_replace(' ', '_', $key));
                }
            }
            $newSentence= $keywordReplacer->replaceKeywords($testCase['sentence']);

            $replacedSentence= $testCase['sentence'];
            $keywordMapping= [];
            foreach ($testCase['keyword_dict'] as $key => $values) {
                foreach ($values as $value) {
                    $keywordMapping[$value] = str_replace(" ", "_", $key);
                }
            }

            $keys = array_keys($keywordMapping);

            uasort($keys, function ($a, $b) {
                if (strlen($a) == strlen($b)) {
                    return 0;
                }
                return (strlen($a) < strlen($b)) ? -1 : 1;
            });

            foreach (array_reverse($keys) as $key) {
                $lowerCase = sprintf('/(?<!\w)%s(?!\w)/', preg_quote($key, '/'));

                $replacedSentence = preg_replace($lowerCase, $keywordMapping[$key], $replacedSentence);
            }



            $this->assertEquals($replacedSentence, $newSentence, sprintf('new_sentence don\'t match the expected results for test case: %s', $testId));
        }
    }

    protected function setUp()
    {
        $testData = file_get_contents(__DIR__ . '/keyword_extractor_test_cases.json');
        $this->testData = json_decode($testData, true);
    }


}
