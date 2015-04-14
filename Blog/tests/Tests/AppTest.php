<?php
/**
 * Created by PhpStorm.
 * User: doncredas
 * Date: 06/04/15
 * Time: 12.18
 */

namespace Blog\Tests;

require_once __DIR__ . '/../../../vendor/autoload.php';

use Silex\Application;
use Silex\WebTestCase;

class AppTest extends WebTestCase{

    /**
     * Basic, application-wide functional test inspired by Symfony best practices.
     * Simply checks that all application URLs load successfully.
     * During test execution, this method is called for each URL returned by the provideUrls method.
     *
     * @dataProvider provideUrls
     */
    public function testPageIsSuccessful($url)
    {
        $client = $this->createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    /**
     * {@inheritDoc}
     */
    public function createApplication()
    {
        $app = new Application();

        require __DIR__ . '/../../app/config/dev.php';
        require __DIR__ . '/../../app/app.php';
        require __DIR__ . '/../../app/routes.php';

        // Generate raw exceptions instead of HTML pages if errors occur
        $app['exception_handler']->disable();
        // Simulate sessions for testing
        $app['session.test'] = true;
        // Enable anonymous access to admin zone
        $app['security.access_rules'] = array();

        return $app;
    }

    /**
     * Provides all valid application URLs.
     *
     * @return array The list of all valid application URLs.
     */
    public function provideUrls()
    {
        return array(
            array('/'),
            array('/about'),
            array('/profile'),
            array('/article/3'),
            array('/login'),
            array('/admin'),
            array('/admin/article/add'),
            array('/admin/article/4/edit'),
            array('/admin/comment/5/edit'),
            array('/admin/user/add'),
            array('/admin/user/13/edit'),
            array('/api/articles'),
            array('/api/article/10'),
        );
    }

}