<?php

namespace InstagramScraper\Model;


class Comment extends AbstractModel
{
    /**
     * @var
     */
    protected $id;

    /**
     * @var
     */
    protected $text;

    /**
     * @var
     */
    protected $createdAt;

    /**
     * @var Account
     */
    protected $owner;

    /**
     * @var static[]
     */
    protected $childComments = [];

    /**
     * @var int
     */
    protected $childCommentsCount = 0;

    /**
     * @var bool
     */
    protected $hasMoreChildComments = false;

    /**
     * @var string
     */
    protected $childCommentsNextPage = '';

    /**
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * @var int
     */
    protected $likesCount = 0;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Account
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return static[]
     */
    public function getChildComments()
    {
        return $this->childComments;
    }

    /**
     * @return int
     */
    public function getChildCommentsCount()
    {
        return $this->childCommentsCount;
    }

    /**
     * @return bool
     */
    public function hasMoreChildComments()
    {
        return $this->hasMoreChildComments;
    }

    /**
     * @return string
     */
    public function getChildCommentsNextPage()
    {
        return $this->childCommentsNextPage;
    }

    /**
     * @param $value
     * @param $prop
     */
    protected function initPropertiesCustom($value, $prop)
    {
        switch ($prop) {
            case 'id':
            case 'pk':
                $this->id = $value;
                break;
            case 'created_at':
                $this->createdAt = $value;
                break;
            case 'text':
                $this->text = $value;
                break;
            case 'owner':
                $this->owner = Account::create($value);
                break;
            case 'edge_liked_by':
                if (isset($value['count'])) {
                    $this->likesCount = (int) $value['count'];
                }
                break;
            case 'comment_like_count':
                $this->likesCount = (int) $value;
                break;
            case 'child_comment_count':
                $this->childCommentsCount = (int)$value;
                break;
            case 'edge_threaded_comments':
                if (isset($value['count'])) {
                    $this->childCommentsCount = (int) $value['count'];
                }
                if (isset($value['edges']) && is_array($value['edges'])) {
                    foreach ($value['edges'] as $commentData) {
                        $this->childComments[] = static::create($commentData['node']);
                    }
                }
                if (isset($value['page_info']['has_next_page'])) {
                    $this->hasMoreChildComments = (bool) $value['page_info']['has_next_page'];
                }
                if (isset($value['page_info']['end_cursor'])) {
                    $this->childCommentsNextPage = (string) $value['page_info']['end_cursor'];
                }
                break;
        }
    }


    /**
     * Get the value of likesCount
     *
     * @return  int
     */
    public function getLikesCount()
    {
        return $this->likesCount;
    }

    /**
     * Set the value of likesCount
     *
     * @param  int  $likesCount
     *
     * @return  self
     */
    public function setLikesCount(int $likesCount)
    {
        $this->likesCount = $likesCount;

        return $this;
    }
}
