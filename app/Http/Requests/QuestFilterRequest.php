<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Level filters
            'level' => 'nullable|integer|between:1,35',
            'heroic_level' => 'nullable|integer|between:1,30',
            'epic_level' => 'nullable|integer|between:20,33',
            'legendary_level' => 'nullable|integer|between:30,35',
            'level_range' => 'nullable|string|regex:/^\d+-\d+$/',
            'min_level' => 'nullable|integer|between:1,35',
            'max_level' => 'nullable|integer|between:1,35|gte:min_level',

            // Relationship filters
            'patron' => 'nullable|string|max:100',
            'patron_id' => 'nullable|exists:patrons,id',
            'duration' => 'nullable|string|max:50',
            'duration_id' => 'nullable|exists:durations,id',
            'adventure_pack' => 'nullable|string|max:100',
            'adventure_pack_id' => 'nullable|exists:adventure_packs,id',
            'location' => 'nullable|string|max:100',
            'location_id' => 'nullable|exists:locations,id',

            // Boolean filters
            'free_to_play' => 'nullable|boolean',
            'extreme_challenge' => 'nullable|boolean',
            'has_epic' => 'nullable|boolean',
            'has_legendary' => 'nullable|boolean',

            // Search filters
            'search' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',

            // Sorting
            'sort_by' => ['nullable', 'string', Rule::in([
                'name', 'heroic_level', 'epic_level', 'legendary_level', 
                'base_favor', 'created_at', 'updated_at'
            ])],
            'sort_direction' => ['nullable', 'string', Rule::in(['asc', 'desc'])],

            // Pagination
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|between:1,100',

            // Include relationships
            'include' => 'nullable|string|max:500',
            'include_quest_count' => 'nullable|boolean',
            'include_xp_rewards' => 'nullable|boolean',

            // XP and favor filters
            'min_favor' => 'nullable|integer|min:0',
            'max_favor' => 'nullable|integer|min:0|gte:min_favor',
            'min_xp' => 'nullable|integer|min:0',
            'max_xp' => 'nullable|integer|min:0|gte:min_xp',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'level.between' => 'Level must be between 1 and 35.',
            'heroic_level.between' => 'Heroic level must be between 1 and 30.',
            'epic_level.between' => 'Epic level must be between 20 and 33.',
            'legendary_level.between' => 'Legendary level must be between 30 and 35.',
            'level_range.regex' => 'Level range must be in format "min-max" (e.g., "1-10").',
            'max_level.gte' => 'Maximum level must be greater than or equal to minimum level.',
            'per_page.between' => 'Results per page must be between 1 and 100.',
            'sort_by.in' => 'Invalid sort field. Allowed fields: name, heroic_level, epic_level, legendary_level, base_favor, created_at, updated_at.',
            'sort_direction.in' => 'Sort direction must be either "asc" or "desc".',
            'max_favor.gte' => 'Maximum favor must be greater than or equal to minimum favor.',
            'max_xp.gte' => 'Maximum XP must be greater than or equal to minimum XP.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validate level_range format if provided
            if ($this->level_range) {
                $parts = explode('-', $this->level_range);
                if (count($parts) === 2) {
                    $min = (int) $parts[0];
                    $max = (int) $parts[1];
                    
                    if ($min > $max) {
                        $validator->errors()->add('level_range', 'Minimum level cannot be greater than maximum level in range.');
                    }
                    
                    if ($min < 1 || $max > 35) {
                        $validator->errors()->add('level_range', 'Level range must be between 1 and 35.');
                    }
                }
            }

            // Validate include parameter
            if ($this->include) {
                $allowedIncludes = [
                    'duration', 'patron', 'adventurePack', 'location', 
                    'xpRewards', 'xpRewards.difficulty'
                ];
                $includes = explode(',', $this->include);
                
                foreach ($includes as $include) {
                    $include = trim($include);
                    if (!in_array($include, $allowedIncludes)) {
                        $validator->errors()->add('include', "Invalid include: {$include}. Allowed: " . implode(', ', $allowedIncludes));
                        break;
                    }
                }
            }
        });
    }

    /**
     * Get validated filter data with defaults.
     */
    public function getFilterData(): array
    {
        $validated = $this->validated();
        
        return array_merge([
            'sort_by' => 'name',
            'sort_direction' => 'asc',
            'per_page' => 15,
            'page' => 1,
        ], $validated);
    }
}
