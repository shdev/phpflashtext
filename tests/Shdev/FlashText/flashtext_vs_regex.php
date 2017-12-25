<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 25.12.17
 * Time: 11:04.
 */
const KEYWORDS_COUNT = 10000;
const MIN_WORD_LENGTH = 3;
const MAX_WORD_COUNT = 12;
const DOCUMENT_COUNT = 100;
const WORDS_PER_DOCUMENT_COUNT = 10000;

const KEYWORD_STEPS = [10, 50, 100, 200, 300, 400, 500, 1000];
const KEYWORD_CHUNK_SIZE = 5;
require __DIR__.'/../../../vendor/autoload.php';

use Symfony\Component\Stopwatch\Stopwatch;

$stopwatch = new Stopwatch();

$alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$alphabetLength = strlen($alphabet);

mt_srand(23423);

$stopwatch->start('generate keywords');
$keywords = [];

for ($i = 0; $i < KEYWORDS_COUNT; ++$i) {
    $keywordLength = mt_rand(MIN_WORD_LENGTH, MAX_WORD_COUNT);
    $keyword = '';
    for ($wordPos = 0; $wordPos < $keywordLength; ++$wordPos) {
        $keyword .= $alphabet[mt_rand(0, $alphabetLength - 1)];
    }
    $keywords[] = $keyword;
}

$keywords = array_values(array_unique($keywords));

while (count($keywords) < KEYWORDS_COUNT) {
    $keywordLength = mt_rand(MIN_WORD_LENGTH, MAX_WORD_COUNT);
    $keyword = '';
    for ($wordPos = 0; $wordPos < $keywordLength; ++$wordPos) {
        $keyword .= $alphabet[mt_rand(0, $alphabetLength - 1)];
    }
    $keywords[] = $keyword;

    $keywords = array_values(array_unique($keywords));
}

$keywords = array_values(array_unique($keywords));

$event = $stopwatch->stop('generate keywords');
echo 'generate keywords '.$event->getDuration()."ms\n";
$stopwatch->start('generate documents');
$documents = [];
for ($i = 0; $i < DOCUMENT_COUNT; ++$i) {
    $document = [];
    for ($wordIdx = 0; $wordIdx < WORDS_PER_DOCUMENT_COUNT; ++$wordIdx) {
        $document[] = $keywords[mt_rand(0, KEYWORDS_COUNT - 1)];
    }
    $documents[] = $document;
}
$event = $stopwatch->stop('generate documents');
echo 'generate documents '.$event->getDuration()."ms\n";
$stopwatch->start('generate keywords per documents');
$keywordPerDocument = [];
$maxKeywords = [];
foreach ($documents as $index => $document) {
    $uniqueWords = array_values(array_unique($document));
    $keywordPerDocument[$index] = [];
    foreach (KEYWORD_STEPS as $keywordStep) {
        $keywordChunks = array_chunk(array_slice($uniqueWords, 0, $keywordStep * KEYWORD_CHUNK_SIZE), KEYWORD_CHUNK_SIZE);

        $keywordPerDocument[$index][$keywordStep] = [];

        foreach ($keywordChunks as $keywordChunk) {
            $keywordPerDocument[$index][$keywordStep][$keywordChunk[0]] = $keywordChunk;
        }
    }
    $maxKeywords[] = count($uniqueWords);
}
$event = $stopwatch->stop('generate keywords per documents');
echo 'generate keywords per documents '.$event->getDuration()."ms\n";
echo "\n";

$strPattern = '%6s | %17s | %17s | %17s'."\n";
printf($strPattern, 'count', 'flashtext', 'flashtext_replace', 'regex');
echo str_repeat('-', 66)."\n";
$times = [];
foreach (KEYWORD_STEPS as $keywordStep) {
    $stopwatch->openSection();
    foreach ($documents as $key => $document) {
        $keywordProcessor = new \Shdev\FlashText\KeywordProcessor();
        $keywordProcessor->addKeywordsFromAssocArray($keywordPerDocument[$key][$keywordStep]);
        $sentence = implode(' ', $document);
        $stopwatch->start('flashtext', $keywordStep);
        $keywordProcessor->extractKeywords($sentence);
        $event = $stopwatch->stop('flashtext', $keywordStep);
    }
    foreach ($documents as $key => $document) {
        $keywordProcessor = new \Shdev\FlashText\KeywordProcessor();
        $keywordProcessor->addKeywordsFromAssocArray($keywordPerDocument[$key][$keywordStep]);
        $sentence = implode(' ', $document);
        $stopwatch->start('flashtext_replace', $keywordStep);
        $keywordProcessor->replaceKeywords($sentence);
        $event = $stopwatch->stop('flashtext_replace', $keywordStep);
    }
    foreach ($documents as $key => $document) {
        $pattern = [];
        foreach ($keywordPerDocument[$key][$keywordStep] as $keywordChunk) {
            $pattern[] = implode('|', $keywordChunk);
        }
        $pattern = '/'.implode('|', $pattern).'/i';
        $sentence = implode(' ', $document);
        // warmup then pattern
        @preg_match_all($pattern, '23423', $matches);
        $stopwatch->start('regex', $keywordStep);
        @preg_match_all($pattern, $sentence, $matches);
        $event = $stopwatch->stop('regex', $keywordStep);
    }
    $stopwatch->stopSection($keywordStep);

    $events = $stopwatch->getSectionEvents($keywordStep);

    printf(
        $strPattern,
        $keywordStep,
        $events['flashtext']->getDuration() / DOCUMENT_COUNT,
        $events['flashtext_replace']->getDuration() / DOCUMENT_COUNT,
        $events['regex']->getDuration() / DOCUMENT_COUNT
    );
}
