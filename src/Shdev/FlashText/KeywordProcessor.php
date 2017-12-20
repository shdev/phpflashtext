<?php
/**
 * Created by PhpStorm.
 * User: sh
 * Date: 20.12.17
 * Time: 19:28.
 */

namespace Shdev\FlashText;

class KeywordProcessor implements \ArrayAccess
{
    /** @var bool */
    private $caseSensitiv;

    /** @var string */
    private $keyword = '_keyword_';

    /** @var string[] */
    private $whiteSpaceChars = ['.', "\t", "\n", "\a", ' ', ','];

    /** @var string[] */
    private $nonWordBoundaries = [
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '_',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S',
        'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z',
    ];

    /** @var array */
    private $keywordTrieDict = [];

    /** @var int */
    private $termsInTrie = 0;

    /**
     * KeywordProcessor constructor.
     *
     * @param bool $caseSensitiv
     */
    public function __construct($caseSensitiv = false)
    {
        $this->caseSensitiv = $caseSensitiv;
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->termsInTrie;
    }

    /**
     * @param $word
     *
     * @return bool
     */
    public function contains($word)
    {
        if (!$this->caseSensitiv) {
            $word = mb_strtolower($word);
        }
        $currentDict = $this->keywordTrieDict;
        $lenCovered = 0;

        $chars = str_split($word);
        foreach ($chars as $char) {
            if (isset($currentDict[$char])) {
                $currentDict = $currentDict[$char];
                ++$lenCovered;
            } else {
                break;
            }
        }

        return isset($currentDict[$this->keyword]) && $lenCovered === strlen($word);
    }

    /**
     * @param $word
     *
     * @return string|null
     */
    public function getItem($word)
    {
        if (!$this->caseSensitiv) {
            $word = mb_strtolower($word);
        }
        $currentDict = $this->keywordTrieDict;
        $lenCovered = 0;
        $chars = str_split($word);
        foreach ($chars as $char) {
            if (isset($currentDict[$char])) {
                $currentDict = $currentDict[$char];
                ++$lenCovered;
            } else {
                break;
            }
        }

        if (isset($currentDict[$this->keyword]) && $lenCovered === strlen($word)) {
            return $currentDict[$this->keyword];
        }

        return null;
    }

    /**
     * @param string      $keyword
     * @param null|string $cleanName
     *
     * @return bool
     */
    public function setItem($keyword, $cleanName = null)
    {
        $status = false;

        if (!$cleanName && $keyword) {
            $cleanName = $keyword;
        }

        if ($keyword && $cleanName) {
            if (!$this->caseSensitiv) {
                $keyword = mb_strtolower($keyword);
            }
            $currentDict = &$this->keywordTrieDict;

            $chars = str_split($keyword);
            foreach ($chars as $char) {
                if (!isset($currentDict[$char])) {
                    $currentDict[$char] = [];
                }
                $currentDict = &$currentDict[$char];
            }

            if (!isset($currentDict[$this->keyword])) {
                $status = true;
                ++$this->termsInTrie;
            }

            $currentDict[$this->keyword] = $cleanName;
        }

        return $status;
    }

    public function delItem($keyword)
    {
        $status = false;

        if ($keyword) {
            if (!$this->caseSensitiv) {
                $keyword = mb_strtolower($keyword);
            }
            $currentDict = &$this->keywordTrieDict;

            $characterTrieList = [];

            $chars = str_split($keyword);
            foreach ($chars as $char) {
                if (isset($currentDict[$char])) {
                    $characterTrieList[] = [$char, $currentDict];
                    $currentDict = &$currentDict[$char];
                }
            }

            if (isset($currentDict[$this->keyword])) {
                $characterTrieList[] = [$this->keywordTrieDict, &$currentDict];
                $characterTrieList = array_reverse($characterTrieList);

                foreach ($characterTrieList as $item) {
                    list($keyToRemove, $dictPointer) = $item;

                    if (1 === count(array_keys($dictPointer))) {
                        unset($dictPointer[$keyToRemove]);
                    } else {
                        unset($dictPointer[$keyToRemove]);
                        break;
                    }
                }

                $status = true;
                --$this->termsInTrie;
            }
        }

        return $status;
    }


    /**
     * @param string[] $nonWordBoundaries
     * @return KeywordProcessor
     */
    public function setNonWordBoundaries($nonWordBoundaries)
    {
        $this->nonWordBoundaries = $nonWordBoundaries;

        return $this;
    }

    /**
     * @param string $nonWordBoundary
     * @return KeywordProcessor
     */
    public function addNonWordBoundaries($nonWordBoundary)
    {
        $this->nonWordBoundaries[] = $nonWordBoundary;

        return $this;
    }

    /**
     * @param string $keyword
     * @param string|null $cleanName
     * @return bool
     */
    public function addKeyword($keyword, $cleanName = null)
    {
        return $this->setItem($keyword, $cleanName);
    }

    /**
     * @param string $keyword
     * @return bool
     */
    public function removeKeyword($keyword)
    {
        return $this->delItem($keyword);
    }

    public function getKeyword($word)
    {
        return $this->getItem($word);    
    }

    public function addKeywordFromFile($keywordFile)
    {
        $fileContent = file_get_contents($keywordFile);
        $lines = explode(PHP_EOL, $fileContent);

        foreach ($lines as $line) {
            $keyword = null;
            $cleanName = null;
            if (false === strpos($line, '=>')) {
                $keyword = trim($line);
            } else {
                list($keyword, $cleanName) = explode('=>', $line, 2);
                $keyword = trim($keyword);
                $cleanName = trim($cleanName);
            }
            $this->addKeyword($keyword, $cleanName);
        }

        return $this;
    }

    /**
     * @param string[] $array
     * @return KeywordProcessor
     */
    public function addKeywordsFromAssocArray(array $array)
    {
        foreach ($array as $cleanName => $keywords) {
            foreach ((array)$keywords as $keyword) {
                $this->addKeyword($keyword, $cleanName);
            }
        }

        return $this;
    }

    /**
     * @param string[] $array
     * @return $this
     */
    public function removeKeywordsFromAssocArray(array $array)
    {
        foreach ($array as $cleanName => $keywords) {
            foreach ((array)$keywords as $keyword) {
                $this->removeKeyword($keyword);
            }
        }

        return $this;
    }

    /**
     * @param array $keywords
     * @return $this
     */
    public function addKeywordsFromArray(array $keywords)
    {
        foreach ($keywords as $keyword) {
            $this->addKeyword($keyword);
        }

        return $this;
    }

    /**
     * @param array $keywords
     * @return $this
     */
    public function removeKeywordFromArray(array $keywords)
    {
        foreach ($keywords as $keyword) {
            $this->removeKeyword($keyword);
        }

        return  $this;
    }

    /**
     * @param string $termSoFar
     * @param string[]|null $currentDict
     *
     * @return string[]
     */
    public function getAllKeywords($termSoFar = '', array &$currentDict = null)
    {
        $termPresent = [];

        if (!$termSoFar) {
            $termSoFar = '';
        }
        if (null === $currentDict) {
            $currentDict = &$this->keywordTrieDict;
        }

        foreach ($currentDict as $key => $value) {
            if ($this->keyword === $key) {
                $termPresent[$termSoFar] = $value;
            } else {
                $subValues = $this->getAllKeywords($termSoFar . $key, $currentDict[$key]);
                foreach ($subValues as $subKey => $subValue) {
                    $termPresent[$key] = $subValue;
                }
            }
        }

        return $termPresent;
    }

    /**
     * @param string $word
     *
     * @return bool
     */
    public function offsetExists($word)
    {
        return $this->contains($word);
    }

    /**
     * @param string $word
     *
     * @return mixed|null|string
     */
    public function offsetGet($word)
    {
        return $this->getItem($word);
    }

    /**
     * @param string $keyword
     * @param string $cleanName
     *
     * @return bool|void
     */
    public function offsetSet($keyword, $cleanName)
    {
        $this->setItem($keyword, $cleanName);
    }

    /**
     * @param string $word
     *
     * @return bool
     */
    public function offsetUnset($word)
    {
        return $this->delItem($word);
    }


}
