<?php

namespace App\Http\Requests\Tasks;

use App\CustomField;
use App\Http\Requests\CoreRequest;
use App\Project;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use App\Task;

class StoreTask extends CoreRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $setting = global_setting();
        $user = auth()->user();
        $rules = [
            'heading' => 'required',
            // 'due_date' => ['required' , new CheckDateFormat(null,$setting->date_format) , new CheckEqualAfterDate('start_date',$setting->date_format)],
            // 'start_date' => 'required',
            'client_id' => 'required',          
            
        ];
   
        if ($this->has('dependent') && $this->dependent == 'yes' && $this->dependent_task_id != '') {
            $dependentTask = Task::find($this->dependent_task_id);
            $endDate = $dependentTask->due_date->format($setting->date_format);
            $rules['start_date'] = ['required', new CheckDateFormat(null,$setting->date_format), new CheckEqualAfterDate('',$setting->date_format, $endDate, __('messages.taskDateValidation', ['date' => $endDate]) )];
            $rules['due_date'] = ['required' , new CheckDateFormat(null,$setting->date_format) , new CheckEqualAfterDate('start_date',$setting->date_format)],
            
        }

        if ($user->can('add_tasks') || $user->hasRole('admin') || $user->hasRole('client')) {
            $rules['user_id'] = 'required';
        }

        
        return $rules;

    }

    public function messages()
    {
        return [
            'project_id.required' => __('messages.chooseProject'),
            'user_id.required' => 'Choose an assignee',
            'start_date.required' => 'Select date',
            'client_id.required' => 'Select client',
           // 'user_id.required' => 'Choose an assignee',
        ];
    }

}
