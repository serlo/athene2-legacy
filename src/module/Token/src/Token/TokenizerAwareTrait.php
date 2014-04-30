<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Token;

trait TokenizerAwareTrait
{

    /**
     * @var TokenizerInterface
     */
    protected $tokenizer;

    /**
     * @return TokenizerInterface $tokenizer
     */
    public function getTokenizer()
    {
        return $this->tokenizer;
    }

    /**
     * @param TokenizerInterface $tokenizer
     * @return self
     */
    public function setTokenizer(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
        return $this;
    }
}
