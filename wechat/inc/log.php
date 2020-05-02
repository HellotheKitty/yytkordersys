<?php
/**
 * Created by PhpStorm.
 * User: GyCCo
 * Date: 8/6/15
 * Time: 7:25 PM
 */

class Log {

    public static function DEBUG($content) {
        self::write(1, $content);
    }

    public static function WARNING($content) {
        self::write(2, $content);
    }

    public static function NOTICE($content) {
        self::write(3, $content);
    }

    public static function INFO($content) {
        self::write(4, $content);
    }

    public static function ERROR($content) {
        self::write(8, $content);
    }

    private static function getLevelStr($level) {
        switch ($level) {
            case 1:
                return 'DEBUG';
                break;
            case 2:
                return 'WARNING';
                break;
            case 3:
                return 'NOTICE';
                break;
            case 4:
                return 'INFO';
                break;
            case 8:
                return 'ERROR';
                break;
            default:
                return '';
                break;
        }
    }

    private static function write($level, $content) {

        $logDir = 'logs';
        if (!file_exists($logDir)) {
            @mkdir($logDir);
        }

        $levelStr = self::getLevelStr($level);

        if ($levelStr == 'DEBUG') {
            $path = $logDir.'/debug.log';
        } else {
            $path = $logDir.'/xLog.log';
        }
        $date = date('Y-m-d H:i:s');
        $data = '['.$date.']['.$levelStr.'] '.$content."\n";
        file_put_contents($path, $data, FILE_APPEND);
    }
}