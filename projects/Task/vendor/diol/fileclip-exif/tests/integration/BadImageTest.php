<?php namespace Diol\FileclipExifTests\integration;

use Diol\FileclipExifTests\models\Node;
use Illuminate\Support\Facades\Log as Log;
use Mockery as m;

class BadImageTest extends IntegrationTestCase
{
    /**
     * @after
     */
    public function deleteNodes()
    {
        foreach (Node::all() as $node) {
            $node->delete();
        }
    }

    /**
     * This should not rise exception.
     */
    public function testBadImage()
    {
        $node = new Node(
            [
                'name' => 'Имя',
                'meta_title' => 'Тайтл',
                'header' => 'Заголовок',
                'image_file' => __DIR__ . '/../data/fixtures/bad_image.jpg',
            ]
        );

        Log::swap($log = m::mock('LogMock'));
        $log->shouldReceive('warning')->once();

        $node->save();
    }
}
