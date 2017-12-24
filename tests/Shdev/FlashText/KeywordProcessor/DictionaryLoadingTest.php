<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class DictionaryLoadingTest extends \PHPUnit_Framework_TestCase
{


/**
 *
 * def test_dictionary_loading(self):
        keyword_processor = KeywordProcessor()
        keyword_dict = {
        "java": ["java_2e", "java programing"],
        "product management": ["product management techniques", "product management"]
        }
        keyword_processor.add_keywords_from_dict(keyword_dict)

        sentence = 'I know java_2e and product management techniques'
        keywords_extracted = keyword_processor.extract_keywords(sentence)
        self.assertEqual(keywords_extracted, ['java', 'product management'],
        "Failed file format one test")
        sentence_new = keyword_processor.replace_keywords(sentence)
        self.assertEqual(sentence_new, "I know java and product management",
        "Failed file format one test")

 *
 *
 */
    public function testDictionaryLoading()
    {
        $keywordProcessor= new KeywordProcessor();

        $keywordAssocArray = [
            'java'               => ['java_2e', 'java programing'],
            'product management' => ['product management techniques', 'product management'],
        ];

        $keywordProcessor->addKeywordsFromAssocArray($keywordAssocArray);

        $sentence = 'I know java_2e and product management techniques';

        $keywordsExtracted= $keywordProcessor->extractKeywords($sentence);

        $this->assertEquals(['java', 'product management'], $keywordsExtracted,
            'Failed file format one test');

        $sentenceNew= $keywordProcessor->replaceKeywords($sentence);
        $this->assertEquals('I know java and product management', $sentenceNew,
            'Failed file format one test');
    }
}
