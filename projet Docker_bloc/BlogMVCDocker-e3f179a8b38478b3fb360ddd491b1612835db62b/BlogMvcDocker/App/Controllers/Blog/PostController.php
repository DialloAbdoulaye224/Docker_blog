<?php

namespace App\Controllers\Blog;

use System\Controller;

class PostController extends Controller
{
     /**
     *
     * @param string name
     * @param int $id
     * @return mixed
     */
    public function index($title, $id)
    {
        $post = $this->load->model('Posts')->getPostWithComments($id);

        if (! $post) {
            return $this->url->redirectTo('/404');
        }

        $this->html->setTitle($post->title);

        $data['post'] = $post;

        $view = $this->view->render('blog/post', $data);

        return $this->blogLayout->render($view);
    }

     /**
     *
     * @param string $title
     * @param int $id
     * @return mixed
     */
    public function addComment($title, $id)
    {
        $comment = $this->request->post('comment');

        $postsModel = $this->load->model('Posts');
        $loginModel = $this->load->model('Login');

        $post = $postsModel->get($id);

        if (! $post OR $post->status == 'disabled' OR ! $comment OR ! $loginModel->isLogged()) {
            return $this->url->redirectTo('/404');
        }

        $user = $loginModel->user();

        $postsModel->addNewComment($id, $comment, $user->id);

        return $this->url->redirectTo('/post/' . $title . '/' . $id . '#comments');
    }

     /**
     *
     * @param \stdClass $post
     * @return string
     */
    public function box($post)
    {
        return $this->view->render('blog/post-box', compact('post'));
    }
}