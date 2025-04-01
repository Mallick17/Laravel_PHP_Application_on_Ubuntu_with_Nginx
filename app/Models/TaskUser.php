<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Log;

class TaskUser extends Pivot
{
    protected $fillable = ['task_id', 'user_id', 'status'];

    /**
     * Calculate and update the task's completion percentage.
     */


    public static function taskPercentageCalculate(TaskUser $taskUser)
    {   $taskId = $taskUser->task_id;
        $task = Task::findOrFail($taskId);
        $completedTaskCount = $task->users()->wherePivot('status', 'complete')->count();
        $totalTaskCount = $task->users()->count();

        if ($totalTaskCount > 0) {
            $percentage = ($completedTaskCount / $totalTaskCount) * 100;
            $task->completion_percentage = $percentage;

            if ($percentage == 100) {
                $task->status = 'completed';
            } else {
                $task->status = 'in-progress';

            }
            $task->save();
            Log::info("Task {$taskId} updated: ", [
                'status' => $task->status,
                'completion_percentage' => $task->completion_percentage,
                'totalTaskCount' => $totalTaskCount,
                'completedTaskCount' => $completedTaskCount
            ]);

        }
    }
    protected static function booted()
    {

        static::created(function (TaskUser $taskUser) {
            self::taskPercentageCalculate($taskUser);
            Log::info('TaskUser created');
        });
        static::updated(function (TaskUser $taskUser) {
            self::taskPercentageCalculate($taskUser);
            Log::info('TaskUser created');
        });
        static::deleted(function (TaskUser $taskUser) {
            self::taskPercentageCalculate($taskUser);
            Log::info('TaskUser created');
        });

    }


    /**
     * Define a relationship with the task model.
     */
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
