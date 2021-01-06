<?php
namespace Concrete\Package\NewspushClient\Api\Page;
use Concrete\Core\Api\ApiController;
use Concrete\Core\Application\Application;
use Concrete\Core\Page\Page;
use Concrete\Core\Http\Request;
use Concrete\Core\Page\Template;
use Concrete\Core\Page\Type\Composer\FormLayoutSet;
use Concrete\Core\Page\Type\Composer\FormLayoutSetControl;
use Concrete\Core\Page\Type\Type;
use Symfony\Component\HttpFoundation\JsonResponse;



class PagesController extends ApiController
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Return detailed information about a page.
     * 
     * @return \League\Fractal\Resource\Item|\Symfony\Component\HttpFoundation\JsonResponse
     */

    public function write()
    {
        $jsonObject = json_decode($this->request->getContent(), false);
        if (!$jsonObject) {
            return $this->error(t('Bad Request'), 400);
        }

        $blogPage = Page::getByPath('/blog');
        $blogType = Type::getByHandle('blog_entry');
        $pageTemplate = Template::getByHandle('right_sidebar');
        // Add our page to our parent page
        $entry = $blogPage->add($blogType,
            [
                'cName' => $jsonObject->blogTitle,
                'uID'=> $jsonObject->userID,
                'cDescription'=>$jsonObject->blogDesc,
                'cIsActive'=> 1,
                'cAcquireComposerOutputControls' => 1
            ],
            $pageTemplate);
            $formLayoutSetList = FormLayoutSet::getList($entry->getPageTypeObject());
            foreach ($formLayoutSetList as $formLayoutSet) {
                $controls = FormLayoutSetControl::getList($formLayoutSet);
                foreach ($controls as $outputControl) {
                if ($outputControl->getPageTypeComposerControlObject() instanceof \Concrete\Core\Page\Type\Composer\Control\BlockControl && $outputControl->getPageTypeComposerControlObject()->getBlockTypeObject()->getBlockTypeHandle() == 'content') {
                        $blockControl = $outputControl->getPageTypeComposerControlObject();
                        break;
                    }
                }
            }
            if (is_object($blockControl)) {
                $data = ['content'=>$jsonObject->blogContent];
                $blockControl->publishToPage($entry, $data, $controls);
            }
        return new JsonResponse(t('Successfully Posted A New Blog'));
    }
}