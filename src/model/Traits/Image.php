<?php namespace Model\Traits;

trait Image 
{
    public function images($part = null)
    {
        $model = $this->hasMany(config('image.model', 'Model\Image'), 'model_id');
        $model->where('model', $this->table);
        
        if (empty($part)) {
            return $model;
        }

        $model->where(function($query) use ($part) {
            $partArray = explode('+', $part);
            foreach ($partArray as $key => $item) {
                $key ? $query->orWhere('part', $item) : $query->where('part', $item);
            }
        });

        return $model;
    }
    
    public function image()
    {
        return $this->images('main')->where('model', $this->table)->orderBy('sort', 'asc')->first();
    }
}
