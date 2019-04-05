<?php

namespace XIVCommon\Logger;

class Logger
{
    const TYPE_INFO  = 'INFO';
    const TYPE_ERROR = 'ERROR';
    const TYPE_DEBUG = 'DEBUG';

    const CLEANUP_DURATION  = (60 * 60 * 24); // Keep logs for 24hrs
    const DATE_FORMAT       = 'Y-m-d H:i';
    const LOGS_FOLDER       = __DIR__ .'/logs/';
    const LOGS_FILENAME     = '{date}_{type}_{group}.json';
    const LOGS_FORMAT       = "[{date}][{group}][{type}] {message} - {json}";
    const LOGS_PLACEHOLDERS = [
        '{message}',
        '{group}',
        '{type}',
        '{date}',
        '{json}'
    ];

    /**
     * Get log files
     */
    public static function getLogFiles()
    {
        return array_diff(scandir(self::LOGS_FOLDER), ['.','..']);
    }

    /**
     * Clean up the log files
     */
    public static function cleanup($force = false)
    {
        foreach (self::getLogFiles() as $filename) {
            [$date, $type, $group] = explode('_', $filename);

            $time = strtotime($date);

            // if the log is out of date, delete it
            if ($force || time() - $time > self::CLEANUP_DURATION) {
                unlink(self::LOGS_FOLDER . $filename);
            }
        }
    }

    /**
     * Write a to a log file
     */
    public static function write(string $group, string $message, string $type, array $data = [])
    {
        if (!is_dir(self::LOGS_FOLDER)) {
            mkdir(self::LOGS_FOLDER, 0777, true);
        }

        $replace = [
            $message,
            strtolower($group),
            strtoupper($type),
            date(self::DATE_FORMAT),
            json_encode($data)
        ];

        $message    = str_ireplace(self::LOGS_PLACEHOLDERS, $replace, self::LOGS_FORMAT);
        $filename   = str_ireplace(self::LOGS_PLACEHOLDERS, $replace, self::LOGS_FILENAME);
        $lines      = file_exists($filename) ? json_decode(file_get_contents($filename), true) : [];
        $lines[]    = $message;

        file_put_contents(self::LOGS_FOLDER . $filename, json_encode($lines, JSON_PRETTY_PRINT));
    }

    /**
     * Read all logs for a given group and type
     */
    public static function read(string $findGroup, string $findType)
    {
        $logs = [];
        foreach (self::getLogFiles() as $filename) {
            [$date, $type, $group] = explode('_', $filename);
            $group = str_ireplace('.json', null, $group);

            if ($group == $findGroup && $type == $findType) {
                $logs[] = [
                    'file' => $filename,
                    'logs' => json_decode(
                        file_get_contents(self::LOGS_FOLDER. $filename)
                    )
                ];
            }
        }

        return $logs;
    }

    /**
     * Read all the logs!
     */
    public static function readAll()
    {
        $logs = [];
        foreach (self::getLogFiles() as $filename) {
            $logs[] = [
                'file' => $filename,
                'logs' => json_decode(
                    file_get_contents(self::LOGS_FOLDER. $filename)
                )
            ];
        }

        return $logs;
    }

    /**
     * Log: INFO
     */
    public static function info(string $group, string $message, array $data = [])
    {
        self::write($group, $message, self::TYPE_INFO, $data);
    }

    /**
     * Log: ERROR
     */
    public static function error(string $group, string $message, array $data = [])
    {
        self::write($group, $message, self::TYPE_ERROR, $data);
    }

    /**
     * Log: DEBUG
     */
    public static function debug(string $group, string $message, array $data = [])
    {
        self::write($group, $message, self::TYPE_DEBUG, $data);
    }
}
