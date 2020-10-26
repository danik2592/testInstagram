<?php


namespace app\commands;


use app\components\Instagram;
use app\components\InstagramPost;
use app\models\InstaPosts;
use app\models\InstaUsers;
use app\models\PostMedia;
use yii\console\Controller;

class InstagramController extends Controller
{

    public function actionGetPosts()
    {
        $users = InstaUsers::find()->all();
        $insta = new Instagram();
        /** @var InstaUsers $user */
        foreach ($users as $user){
            $posts = $insta->getPostsByUser($user->username);
            /** @var InstagramPost $post */
            foreach ($posts as $post) {
                $postModel = InstaPosts::find()->byCode($post->getCode())->one();
                if (!$postModel){
                    $postModel = new InstaPosts();
                    $postModel->text = $post->getText();
                    $postModel->type = $post->getType();
                    $postModel->shortCode = $post->getCode();
                    $postModel->timestamp = $post->getTimestamp();
                    $postModel->user_id = $user->id;
                    if($postModel->save()) {
                        foreach ($post->getMedia() as $media) {
                            $mediaModel = new PostMedia();
                            $mediaModel->url = $media['url'];
                            $mediaModel->post_id =  $postModel->id;
                            $mediaModel->save();
                        }
                    }
                }
            }
        }
    }
}