<?php

namespace Ecentura\Blog\Controller\Adminhtml\Post;

use Ecentura\Blog\Model\PostFactory;
use Ecentura\Blog\Model\ResourceModel\Post\CollectionFactory;
use Magento\Backend\App\Action;

class Save extends Action
{
    private $_postFactory;
    protected $data;
    protected $_collectionFactory;

    public function __construct(
        Action\Context $context,
        PostFactory $postFactory,
        CollectionFactory $collectionFactory
    )

    {
        parent::__construct($context);
        $this->_postFactory = $postFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->data = $this->getRequest()->getPostValue();
//        echo "<pre>";
//        print_r($this->data);die();
    }

    public function getId()
    {
        $id = !empty($this->data['post_id']) ? $this->data['post_id'] : null;
        return $id;
    }

    public function dataPost()
    {
        $newData = [
            'title' => $this->data['title'],
            'status' => $this->data['status'],
            'url_key' => $this->createUrlKey(),
            'meta_title' => $this->data['meta_title'],
            'meta_keyword' => $this->data['meta_keyword'],
            'meta_description' => $this->data['meta_description'],
            'image_description' => $this->data['image_description'],
            'description' => $this->data['description'],
            'content' => $this->data['content'],
            'author_id' => $this->data['author_id'],
            'path' => $this->getPath(),
            'feature_image' => isset($this->data['feature_image'][0]) ? $this->data['feature_image'][0]['name'] : null,
            'publish_time' => $this->data['publish_time'] ? $this->data['publish_time'] : date("Y-m-d", time())
        ];
        return $newData;
    }

    public function execute()
    {
        $post = $this->_postFactory->create();

        if ($this->getId()) {
            $post->load($this->getId());
        }

        try {
            //save data
            $post->addData($this->dataPost());
            $post->save();
            $this->messageManager->addSuccessMessage(__('Save Success'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }

        return $this->redirectToIndex();
    }

    public function createUrlKey()
    {
        if (empty($this->data['url_key'])) {
            $url_key = preg_replace('#[^0-9a-z]+#i', '-', $this->data['title']);
        }else{
            $url_key = preg_replace('#[^0-9a-z]+#i', '-', $this->data['url_key']);
        }

        return strtolower($url_key);
    }

    public function redirectToIndex()
    {
        return $this->resultRedirectFactory->create()->setPath('blog/post/index');
    }

    public function getPath()
    {
        if (isset($this->data['path'])) {
            if (count($this->data['path']) < 2) {
                $path = $this->data['path'][0];
            } else {
                $pathArr = [];
                foreach ($this->data['path'] as $pathItem) {
                    array_push($pathArr, $pathItem);
                }

                $path = implode("/", $pathArr);
            }
            return $path;
        }
        return null;
    }

    //Check rule (ACL)
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ecentura_Blog::blog');
    }
}
