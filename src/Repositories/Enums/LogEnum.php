<?php



namespace Jiannei\Logger\Laravel\Repositories\Enums;

use Jiannei\Enum\Laravel\Enum;

class LogEnum extends Enum
{
    // 定义应用中的日志分类；以冒号区分层级
    const SQL = 'system:sql';
    const REQUEST = 'system:request';
    const EXCEPTION = 'system:exception';
}
