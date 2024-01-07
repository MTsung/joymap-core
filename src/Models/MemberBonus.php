<?php

namespace Mtsung\JoymapCore\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class MemberBonus extends Model
{
    use HasFactory;

    protected $table = 'member_bonus';

    protected $guarded = ['id'];

    // 排程中
    public const STATUS_SCHEDULED = 0;
    // 發放中
    public const STATUS_ONGOING = 1;
    // 已發放
    public const STATUS_COMPLETED = 2;
    // 失敗
    public const STATUS_FAILED = 3;
    // 未達標
    public const STATUS_NOT_REACHED = 4;
    // 退刷
    public const STATUS_REFUNDED = 5;

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function payLog(): BelongsTo
    {
        return $this->belongsTo(PayLog::class, 'pay_log_id', 'id');
    }

    /**
     * MemberMonthBonusPayAmount
     * 取得每一個有獲得分潤的會員該月實際刷卡金額和分潤金額
     * @param string $year 年份
     * @param string $month 月份
     * @param null $memberId
     * @param null $status
     * @return mixed
     */
    public static function MemberMonthBonusPayAmount(string $year, string $month, $memberId = null, $status = null): mixed
    {
        $date = Carbon::createFromDate($year, $month);
        $firstDayOfMonth = $date->startOfMonth()->toDateTimeString();
        $lastDayOfMonth = $date->endOfMonth()->toDateTimeString();

        if (!isset($status)) {
            $status = 0;
        }
        $condition = "
                status = $status
            AND
                year = $year
            AND
                month = $month
        ";

        if (isset($memberId)) {
            $condition .= " AND member_id = $memberId ";
        }
        //先抓經銷身分
        $memberDealerBonus = self::selectBonusPayAmount($condition, 1, $firstDayOfMonth, $lastDayOfMonth);
        //再抓樂粉身分
        $condition .= " AND relation_level < 5 ";
        $memberBonus = self::selectBonusPayAmount($condition, 0, $firstDayOfMonth, $lastDayOfMonth);
        //合併
        return $memberBonus->concat($memberDealerBonus)->load(['member']);
    }

    /**
     * 執行每一個有獲得分潤的會員該月實際刷卡金額和分潤金額
     * @param string $condition 語法
     * @param int $dealerGrade 經銷商身分
     * @param string $firstDayOfMonth 月份(月初)
     * @param string $lastDayOfMonth 月份(月底)
     * @return mixed
     */
    protected static function selectBonusPayAmount(string $condition, int $dealerGrade, string $firstDayOfMonth, string $lastDayOfMonth): mixed
    {
        return self::hydrate(DB::select("
            SELECT
                mb.member_id as member_id,
                IFNULL(SUM(pl.user_pay_amount),
                0) AS user_pay_amount,
                mb.total_bonus as total_bonus
            FROM
                (
                    SELECT
                        member_id,
                        SUM(bonus_jcoin) as total_bonus
                    FROM
                        `member_bonus`
                    WHERE
                        $condition
                    GROUP BY
                        member_id
                ) AS mb
                LEFT JOIN(
                    SELECT
                        member_id,
                        user_pay_amount
                    FROM
                        pay_logs
                    WHERE
                        created_at BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'
                    AND
                        user_pay_status = 1
                ) AS pl
                ON
                    mb.member_id = pl.member_id
                LEFT JOIN members AS m
                ON
                    mb.member_id = m.id
                WHERE m.is_joy_dealer = $dealerGrade
                GROUP BY
                    mb.member_id
                ORDER BY mb.member_id ASC
            "));
    }
}
