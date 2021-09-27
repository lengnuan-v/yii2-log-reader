<?php
// +----------------------------------------------------------------------
// | 日志模块
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月27日
// +----------------------------------------------------------------------

namespace lengnuan\logReader;

use yii\web\Application;
use yii\web\GroupUrlRule;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $layout;

    public $aliases = [];

    public $levelClasses = [
        'trace'   => 'label-default',
        'info'    => 'label-info',
        'warning' => 'label-warning',
        'error'   => 'label-danger',
    ];

    public $defaultLevelClass = 'label-default';

    public $defaultTailLine = 100;

    /**
     * @param \yii\base\Application $app
     * @throws InvalidConfigException
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $app->getUrlManager()->addRules([[
                'class' => GroupUrlRule::class,
                'prefix' => $this->id,
                'rules' => [
                    '' => 'default/index',
                    '<action:\w+>/<slug:[\w-]+>' => 'default/<action>',
                    '<action:\w+>' => 'default/<action>',
                ],
            ]], false);
        } else {
            throw new InvalidConfigException('Can use for web application only.');
        }
    }

    /**
     * @return array
     */
    public function getLogs()
    {
        $logs = [];
        foreach ($this->aliases as $name => $alias) {
            $logs[] = new Log($name, $alias);
        }
        return $logs;
    }

    /**
     * @param $slug
     * @param $stamp
     * @return Log|null
     */
    public function findLog($slug, $stamp)
    {
        foreach ($this->aliases as $name => $alias) {
            if ($slug === Log::extractSlug($name)) {
                return new Log($name, $alias, $stamp);
            }
        }
        return null;
    }

    /**
     * @param Log $log
     * @return array
     */
    public function getHistory(Log $log)
    {
        $logs = [];
        foreach (glob(Log::extractFileName($log->alias, '*')) as $fileName) {
            $logs[] = new Log($log->name, $log->alias, Log::extractFileStamp($log->alias, $fileName));
        }
        return $logs;
    }

    /**
     * @return int|mixed
     */
    public function getTotalCount()
    {
        $total = 0;
        foreach ($this->getLogs() as $log) {
            foreach ($log->getCounts() as $count) {
                $total += $count;
            }
        }
        return $total;
    }
}
