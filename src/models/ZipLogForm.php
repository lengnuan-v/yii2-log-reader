<?php
// +----------------------------------------------------------------------
// | 日志 - 模型
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2021年09月27日
// +----------------------------------------------------------------------

namespace lengnuan\logReader\models;

use ZipArchive;
use yii\base\Model;
use lengnuan\logReader\Log;

class ZipLogForm extends Model
{
    public $log;

    public $start;

    public $end;

    public $deleteAfterZip = 0;

    public function rules()
    {
        return [
            [['start', 'end'], 'string'],
            [['deleteAfterZip'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'start'          => '开始日期',
            'end'            => '结束日期',
            'deleteAfterZip' => '打包后删除',
        ];
    }

    public function init()
    {
        parent::init();
        $this->start = date('Y-m-01', strtotime('-1 month'));
        $this->end   = date('Y-m-01');
    }

    public function zip()
    {
        $log        = $this->log;
        $startStamp = date('Ymd', strtotime($this->start));
        $endStamp   = date('Ymd', strtotime($this->end));
        $logs       = [];
        foreach (glob(Log::extractFileName($log->alias, '*')) as $fileName) {
            $logEnd = Log::extractFileStamp($log->alias, $fileName);
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
        $current  = date('YmdHis');
        $fileName = Log::extractFileName($log->alias, "{$startStamp}-{$endStamp}-{$current}.zip");
        $zip      = new ZipArchive();
        if ($zip->open($fileName, ZipArchive::CREATE) !== true) {
            $this->addError('log', 'cannot open zip file, do you have permission?');
            return false;
        }
        foreach ($logs as $log) {
            $zip->addFile($log->fileName, basename($log->fileName));
        }
        $zip->close();
        // 删除已打包的文件
        if ($this->deleteAfterZip) {
            foreach ($logs as $log) {
                unlink($log->fileName);
            }
        }
        return true;
    }
}
