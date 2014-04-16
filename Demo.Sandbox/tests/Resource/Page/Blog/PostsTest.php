<?php
namespace Sandbox\tests\Resource\Page\Blog;

use Doctrine\Common\Cache\ArrayCache;
use Ray\Di\Injector;

class PostsTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * Resource client
     *
     * @var \BEAR\Resource\Resource
     */
    private $resource;

    /**
     * @return \PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    public function getConnection()
    {
        $pdo = require $GLOBALS['APP_DIR'] . '/tests/scripts/db.php';

        return $this->createDefaultDBConnection($pdo, 'sq_lite');
    }

    /**
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createFlatXmlDataSet($GLOBALS['APP_DIR'] .'/tests/mock/seed.xml');
    }

    protected function setUp()
    {
        parent::setUp();
        $this->resource = clone $GLOBALS['RESOURCE'];
    }

    /**
     * page://self/blog/posts
     *
     * @test
     */
    public function resource()
    {
        // resource request
        $page = $this->resource->get->uri('page://self/blog/posts')->eager->request();
        $this->assertSame(200, $page->code);

        return $page;
    }

    /**
     * Has page app resource ?
     *
     * @depends resource
     */
    public function test_Graph($page)
    {
        $this->assertArrayHasKey('posts', $page->body);
    }

    /**
     * Is app resource request?
     *
     * @depends resource
     */
    public function test_AppResourceType($page)
    {
        $this->assertInstanceOf('BEAR\Resource\Request', $page->body['posts']);
    }

    /**
     * Is valid app resource uri ?
     *
     * @depends resource
     */
    public function test_AppResourceUri($page)
    {
        $posts = $page->body['posts'];
        /** @var $posts \BEAR\Resource\Request */
        $this->assertSame('app://self/blog/posts', $posts->toUri());
    }

    /**
     * Renderable ?
     *
     * @depends resource
     */
    public function test_Render($page)
    {
        $html = (string)$page;
        $this->assertInternalType('string', $html);
    }

    /**
     * Html Rendered ?
     *
     * @depends resource
     */
    public function test_RenderHtml($page)
    {
        $html = (string)$page;
        $this->assertContains('</html>', $html);
    }

}
