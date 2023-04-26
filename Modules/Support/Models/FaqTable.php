<?php

namespace Modules\Support\Models;

use Illuminate\Database\Eloquent\Model;

class FaqTable extends Model
{
    protected $table = "piospa_faq";
    protected $primaryKey = "faq_id";

    const IS_ACTIVE = 1;
    const IS_DELETED = 0;
    /**
     * Danh sách câu hỏi
     *
     * @return mixed
     */
    public function getFaq()
    {
        return $this
            ->select(
                "{$this->table}.faq_id",
                "{$this->table}.faq_title_en",
                "{$this->table}.faq_title_vi",
                "{$this->table}.faq_content_vi",
                "{$this->table}.faq_content_en",
            )
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::IS_DELETED)
            ->get();
    }
}