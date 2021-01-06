<?php
namespace Concrete\Package\NewspushClient;

use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Application as ApplicationFacade;

class Controller extends Package
{
    protected $pkgHandle = 'newspush_client';
    protected $appVersionRequired = '8.4';
    protected $pkgVersion = '0.9.5';

    protected $pkgAutoloaderRegistries = [
        'src/NewspushClient' => '\Concrete\Package\NewspushClient',
    ];

    public function getPackageDescription()
    {
        return t("Create a page from a REST API request");
    }

    public function getPackageName()
    {
        return t("Newspush Client");
    }

    public function install()
    {
        $app = ApplicationFacade::getFacadeApplication();
        $pkg = parent::install();
        $this->addScopes();
    }

    public function upgrade()
    {
        parent::upgrade();
        $this->addScopes();
    }

    public function uninstall()
    {
        parent::uninstall();
        $this->removeScopes();
    }

    public function on_start() {

        $config = $this->app->make("config");
        if ($this->app->isInstalled() && $config->get('concrete.api.enabled')) {
            $router = $this->app->make('router');
            $list = new RouteList();
            $list->loadRoutes($router);
        }
    }

    private function addScope($scope, $description)
    {
        $db = $this->app->make('database')->connection();

        $existingScope = $db->fetchColumn('select identifier from OAuth2Scope where identifier = ?', [
            $scope
        ]);
        if (!$existingScope) {
            $db->insert('OAuth2Scope', ['identifier' => $scope, 'description' => $description]);
        }
    }

    private function removeScope($scope)
    {
        $db = $this->app->make('database')->connection();

        $db->execute('delete from OAuth2Scope where identifier = ?', [
            $scope
        ]);
    }

    private function addScopes()
    {
        $this->addScope('pages:write', t('Publish a Page'));
    }

    private function removeScopes()
    {
        $this->removeScope('pages:write');
    }
}
