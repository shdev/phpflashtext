<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 23:45
 */

namespace Tests\Shdev\FlashText\KeywordProcessor;

use Shdev\FlashText\KeywordProcessor;

class GetAllKeywordsTest extends \PHPUnit_Framework_TestCase
{

    public function testGetAllKeywords()
    {
        $keywordProcessor = new KeywordProcessor();
        $keywordProcessor->addKeyword('colour', 'color');
        $keywordProcessor->addKeyword('j2ee', 'Java');

        $this->assertEquals(['colour' => 'color', 'j2ee' => 'Java'], $keywordProcessor->getAllKeywords(), 'get_all_keywords didn\'t match expected results.');
    }


}
