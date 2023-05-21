<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\TaskTag;
use App\Models\TaskTagContent;

class TagContentsTaskHasLessThanLimit implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $selected_tag_ids = TaskTagContent::whereIn('id', $value)->pluck('tag_id')->toArray();
        $selected_contents_count = array_count_values($selected_tag_ids);

        $tags = TaskTag::select(['id', 'max_select_content', 'name'])->whereIn('id', $selected_tag_ids)->get();
        foreach($tags as $tag){
            if($tag->max_select_content >= 1){
                if($selected_contents_count[$tag->id] > $tag->max_select_content){
                    $fail($tag->name.": cannot select tag contents more than ".(string)($tag->max_select_content + 1).".");
                }
            }
        }
    }
}
