<?php

namespace Demo\Sandbox\Resource\Page\Blog\Posts;

use BEAR\Resource\ResourceObject as Page;
use BEAR\Resource\Link;
use BEAR\Sunday\Annotation\Form;
use BEAR\Sunday\Inject\AInject;
use BEAR\Sunday\Inject\ResourceInject;
use BEAR\Resource\Header;

/**
 * New post page
 */
class Newpost extends Page
{
    use ResourceInject;
    use AInject;


    /**
     * @var array
     */
    public $body = [
        'errors' => ['title' => '', 'body' => ''],
        'submit' => ['title' => '', 'body' => '']
    ];

    /**
     * @var array
     */
    public $links = [
        'back' => [Link::HREF => 'page://self/blog/posts'],
        'created' => [Link::HREF => 'page://self/blog/posts/post{?id}', Link::TEMPLATED => true],
        'create' => [Link::HREF => 'app://self/blog/posts']
    ];

    /**
     * @return Newpost
     */
    public function onGet()
    {
        return $this;
    }

    /**
     * @param string $title
     * @param string $body
     *
     * @Form
     */
    public function onPost($title, $body)
    {
        $uri = $this->links['create'][Link::HREF];
        $response = $this
            ->resource
            ->uri($uri)
            ->withQuery(
                ['title' => $title, 'body' => $body]
            )
            ->eager
            ->request();

        $this->code = $this['code'] = $response->code;
        $this['id'] = $response->headers[Header::X_ID];

        return $this;
    }
}
