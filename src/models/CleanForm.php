<?php
// +----------------------------------------------------------------------
// | 日志 - 模型
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月27日
// +----------------------------------------------------------------------

namespace lengnuan\logReader\models;

use yii\base\Model;
use lengnuan\logReader\Log;

class CleanForm extends Model
{
    public $log;

    public $start;

    public $end;

    public function rules()
    {
        return [
            [['start', 'end'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'start' => '开始日期',
            'end' => '结束日期',
        ];
    }

    public function init()
    {
        parent::init();
        $this->start = date('Y-m-01', strtotime('-1 month'));
        $this->end = date('Y-m-01');
    }

    /**
     * @return bool
     */
    public function clean()
    {
        $log        = $this->log;
        $startStamp = date('Ymd', strtotime($this->start));
        $endStamp   = date('Ymd', strtotime($this->end));
        $logs       = [];
        foreach (glob(Log::extractFileName($log->alias, '*')) as $fileName) {
            $logEnd = Log::extractFileStamp($log->alias, $fileName);
            // 被自动切割的log文件可能为：jd.log.20181109.1
            if ($arr = explode('.', $logEnd)) {
                $logEnd = $arr[0];
            }
            $stamp = date('Ymd', strtotime($logEnd));
            if ($stamp >= $startStamp && $stamp < $endStamp) {
                $log = new Log($log->name, $log->alias, Log::extractFileStamp($log->alias, $fileName));
                if (!$log->isZip) {
                    $logs[] = $log;
                }
            }
        }
        foreach ($logs as $log) {
            unlink($log->fileName);
        }
        return true;
    }
}
