<?php

namespace IndigoLabo\Franca\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Log;

class CalendarService
{
    private const HOLIDAYS_REDIS_KEY = '_holidays_ja_';

    /**
     * 祝日か判定する
     * @param string $date Y-m-d
     * @return bool
     */
    public static function isHoliday($date): bool
    {
        $redis = new RedisService();

        $holidays = $redis->get(self::HOLIDAYS_REDIS_KEY);
        if ($holidays === null) {
            $url = self::createUrl();
            if ($url === false) {
                Log::error('[CalendarService] URL生成エラー');
                return false;
            }
            info('[CalendarService]  Build Google Calendar API');
            $jsonHolidays = file_get_contents($url, true);
            if ($jsonHolidays === false) {
                Log::error('[CalendarService] JSON取得エラー');
                return false;
            }

            $holidays = json_decode($jsonHolidays, true);

            $redis->set(self::HOLIDAYS_REDIS_KEY, $holidays);
        }

        return array_key_exists(
            Carbon::parse($date)->format('Y-m-d'),
            self::convert($holidays)
        );
    }

    /**
     * Google Calendar API アクセス用URLを生成
     * @return string|bool URL
     */
    protected static function createUrl()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfNextYear = Carbon::now()->addYear()->endOfYear();

        return self::createUrlOfRange($startOfYear, $endOfNextYear);
    }

    /**
     * Google Calendar API アクセス用URLを期間を指定して生成
     * @param Carbon $from
     * @param Carbon $to
     * @return bool|string
     */
    protected static function createUrlOfRange($from, $to)
    {
        $apiKey = config('const.google_calendar_api_key');
        $format = 'Y-m-d\TH:i:s\Z';
        $startOfDay = $from->startOfDay()->format($format);
        $endOfDay = $to->endOfDay()->format($format);
        $calendarId = urlencode('japanese__ja@holiday.calendar.google.com');

        return "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events?key={$apiKey}&timeMin={$startOfDay}&timeMax={$endOfDay}&maxResults=50&orderBy=startTime&singleEvents=true";
    }


    /**
     * 日付をキーにして、祝日名を配列に格納
     * @param array $array
     * @return array
     */
    protected static function convert($array): array
    {
        if (!array_key_exists('items', $array)) {
            throw new \InvalidArgumentException('Item key does not exist.');
        }
        $holidays = [];
        foreach ($array['items'] as $item) {
            $date = $item['start']['date'] ?? null;
            if ($date === null) {
                continue;
            }
            $key = Carbon::parse($date)->format('Y-m-d');
            $holidays[$key] = $item['summary'] ?? '';
        }

        ksort($holidays);

        return $holidays;
    }

}
