<?php


namespace app\components;


class InstagramPost
{
    private $type;
    private $text;
    private $media;
    private $shortCode;
    private $username;
    private $timestamp;


    public function __construct(object $node)
    {
        $this->username = $node->owner->username;
        $this->type = $node->__typename;
        $this->text = $node->edge_media_to_caption->edges[0]->node->text ?? '';
        $this->shortCode = $node->shortcode;
        $this->timestamp = $node->taken_at_timestamp;
        switch ($this->type) {
            case "GraphImage":
                $this->media[] = ['url' => $node->display_url];
                break;
            case "GraphVideo":
                $this->media[] = ['url' => $node->video_url];
                break;
            case "GraphSidecar":
                foreach ($node->edge_sidecar_to_children->edges as $row) {
                    $this->media[] = ['url' => $row->node->display_url];
                }
                break;
        }

    }

    public function getCode()
    {
        return $this->shortCode;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function getType()
    {
        return $this->type;
    }
    public function getMedia()
    {
        return $this->media;
    }


}