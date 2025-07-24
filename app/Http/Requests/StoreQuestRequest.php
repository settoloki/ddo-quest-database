<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // For now, allow all users to create quests
        // In production, you might want to restrict this to admins
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:quests,name',
            'slug' => 'nullable|string|max:255|unique:quests,slug',
            'heroic_level' => 'nullable|integer|between:1,30',
            'epic_level' => 'nullable|integer|between:20,33',
            'legendary_level' => 'nullable|integer|between:30,35',
            'duration_id' => 'nullable|exists:durations,id',
            'patron_id' => 'nullable|exists:patrons,id',
            'adventure_pack_id' => 'nullable|exists:adventure_packs,id',
            'location_id' => 'nullable|exists:locations,id',
            'base_favor' => 'integer|min:0|max:999',
            'extreme_challenge' => 'boolean',
            'overview' => 'nullable|string|max:5000',
            'objectives' => 'nullable|string|max:5000',
            'tips' => 'nullable|string|max:5000',
            'wiki_url' => 'nullable|url|max:512',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Quest name is required.',
            'name.unique' => 'A quest with this name already exists.',
            'heroic_level.between' => 'Heroic level must be between 1 and 30.',
            'epic_level.between' => 'Epic level must be between 20 and 33.',
            'legendary_level.between' => 'Legendary level must be between 30 and 35.',
            'duration_id.exists' => 'The selected duration does not exist.',
            'patron_id.exists' => 'The selected patron does not exist.',
            'adventure_pack_id.exists' => 'The selected adventure pack does not exist.',
            'location_id.exists' => 'The selected location does not exist.',
            'base_favor.min' => 'Base favor cannot be negative.',
            'base_favor.max' => 'Base favor cannot exceed 999.',
            'wiki_url.url' => 'Wiki URL must be a valid URL.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'duration_id' => 'duration',
            'patron_id' => 'patron',
            'adventure_pack_id' => 'adventure pack',
            'location_id' => 'location',
            'wiki_url' => 'wiki URL',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // At least one level must be specified
            if (!$this->heroic_level && !$this->epic_level && !$this->legendary_level) {
                $validator->errors()->add('level', 'At least one level type (heroic, epic, or legendary) must be specified.');
            }

            // Epic level should be higher than heroic level if both are specified
            if ($this->heroic_level && $this->epic_level && $this->epic_level <= $this->heroic_level) {
                $validator->errors()->add('epic_level', 'Epic level must be higher than heroic level.');
            }

            // Legendary level should be higher than epic level if both are specified
            if ($this->epic_level && $this->legendary_level && $this->legendary_level <= $this->epic_level) {
                $validator->errors()->add('legendary_level', 'Legendary level must be higher than epic level.');
            }
        });
    }
}
