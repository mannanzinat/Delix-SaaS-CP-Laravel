<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchService
{
    private $months = [
        'January'   => 'Jan',
        'February'  => 'Feb',
        'March'     => 'Mar',
        'April'     => 'Apr',
        'May'       => 'May',
        'June'      => 'Jun',
        'July'      => 'Jul',
        'August'    => 'Aug',
        'September' => 'Sep',
        'October'   => 'Oct',
        'November'  => 'Nov',
        'December'  => 'Dec',
    ];

    public function totalBranch()
    {
        $data = [];

        $now = date('Y-m-d');

        $query = Branch::where('status', 'active')
            ->select(
                DB::raw('MONTHNAME(created_at) as month_name'),
                DB::raw('COUNT(*) as data')
            )
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();



        foreach ($this->months as $full_month => $sort_month) {
            $enrol = $query->firstWhere('month_name', $full_month);
            $data[] = $enrol ? $enrol->data : 0;
        }

        return $data;
    }
}
