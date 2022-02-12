<?php
namespace Ecentura\Blog\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

class CustomRouter implements RouterInterface
{
    /**
     * @var ActionFactory
     */
    protected $actionFactory;
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;
    /**
     * @var Url
     */
    protected $url;
    /**
     * @param Url                   $url
     * @param ActionFactory         $actionFactory
     * @param EventManagerInterface $eventManager
     */
    public function __construct(
        ActionFactory $actionFactory,
        EventManagerInterface $eventManager,
        \Ecentura\Blog\Helper\Url $url
    ) {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->url = $url;
    }
    /**
     * {@inheritdoc}
     */

    public function match(RequestInterface $request)
    {
        /** @var \Magento\Framework\App\Request\Http $request */
        $pathInfo = $request->getPathInfo();
        $pathKeyPost = str_ireplace( array( '/blog/post/', '.html'), '', $pathInfo);
        $pathKeyCategory = str_ireplace( array( '/blog/category/', '.html'), '', $pathInfo);
        $post = $this->url->getPost($pathKeyPost);
        $category = $this->url->getCagetory($pathKeyCategory);

        if($post !== false){
            if(!$post->isEmpty()){
                $params['id'] = $post->getData()['post_id'];
                $request
                    ->setModuleName('blog')
                    ->setControllerName('post')
                    ->setActionName('index')
                    ->setParams($params);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Forward',
                    ['request' => $request]
                );
            }
        }

        if('/blog/posts.html' == $pathInfo || "/blog/posts.html/" == $pathInfo.'/'){
            $request
                ->setModuleName('blog')
                ->setControllerName('post')
                ->setActionName('all');
            return $this->actionFactory->create(
                'Magento\Framework\App\Action\Forward',
                ['request' => $request]
            );
        }

        if($category !== false){
            if(!$category->isEmpty()){
                $params['id'] = $category->getData()['category_id'];
                $request
                    ->setModuleName('blog')
                    ->setControllerName('category')
                    ->setActionName('index')
                    ->setParams($params);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Forward',
                    ['request' => $request]
                );
            }
        }
        return false;
    }
}
