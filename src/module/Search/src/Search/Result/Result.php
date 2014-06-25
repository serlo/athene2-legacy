<?php
/**
 * Athene2 - Advanced Learning Resources Manager
 *
 * @author      Aeneas Rekkas (aeneas.rekkas@serlo.org)
 * @license     LGPL-3.0
 * @license     http://opensource.org/licenses/LGPL-3.0 The GNU Lesser General Public License, version 3.0
 * @link        https://github.com/serlo-org/athene2 for the canonical source repository
 */
namespace Search\Result;

class Result implements ResultInterface
{

    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $keywords;

    /**
     * @param int    $id
     * @param string $title
     * @param string $content
     * @param string $type
     * @param string $url
     * @param array  $keywords
     */
    public function __construct($id, $title, $content, $type, $url, array $keywords)
    {
        $this->id       = $id;
        $this->title    = $title;
        $this->content  = $content;
        $this->type     = $type;
        $this->url      = $url;
        $this->keywords = $keywords;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return [
            'id'      => $this->getId(),
            'title'   => $this->getTitle(),
            'content' => $this->getContent(),
            'url'     => $this->getUrl(),
            'type'    => $this->getType(),
            'keywords'
        ];
    }
}
