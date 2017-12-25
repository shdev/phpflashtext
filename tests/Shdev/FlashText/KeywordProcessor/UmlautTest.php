<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45.
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class UmlautTest extends \PHPUnit_Framework_TestCase
{
    public function testUmlaut()
    {
        $keywordProcessor = new KeywordProcessor();

        $keywordAssocArray = [
            'Kein Wort' => ['AÖÜÄß'],
            'Ein Wort' => ['Ass'],
        ];

        $keywordProcessor->addKeywordsFromAssocArray($keywordAssocArray);

        $sentence = 'Dies ist kein Wort: Aöüäß oder Assü';

        $keywordsExtracted = $keywordProcessor->extractKeywords($sentence);

        $this->assertEquals(['Kein Wort'], $keywordsExtracted);

    }
}
