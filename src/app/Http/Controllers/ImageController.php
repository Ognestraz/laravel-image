<?php namespace Ognestraz\Image\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image as Img;

class ImageController extends \Illuminate\Routing\Controller
{
   // use Traits\Sortable, Traits\Items, Traits\Act;
    
    protected $modelName = 'image';
    protected $requestParam = array('model', 'model_id', 'part');
    
    protected $makeList = ['crop'];
    protected $groupActionList = ['sort'];
    
    static protected $input_file = 'imagefile';
    static public $image_dir = 'files/image/';

    public static function getClassModel()
    {
        return config('image.model', Model\Image::class);
    }
    
    public function content($id, $view)
    {
        $model = $this->model($id);
        
        return view($this->templatePath().'.content.'.$view, array($this->model => $model));
    }     
    
    public function image($variant, $path)
    {
        $classModel = static::getClassModel();
        return $classModel::findPath($path, true, true)->show($variant);
    }
    
    public function index()
    {
        $classModel = static::getClassModel();
        $list = $classModel::orderBy('sort', 'asc')->get();
     
        return view($this->templatePath().'.index', array('list' => $list));
    }
    
    public function edit($id)
    {
        $variant = Input::get('variant', '');
        $view = !empty($variant) ? 'variant' : 'create';
        
        if ($variant === 'main') {
            $variant = '';
        }
        
        $model = $this->model($id);
        if ($model->id) {
            return view($this->templatePath().'.'.$view, array($this->modelName => $model, 'variant' => $variant));
        }

        return false;
    }    
    
    public function upload()
    {
        $classModel = static::getClassModel();
        $view = Input::get('view', 'uploader');
     
        if ($view === 'list' || $view === 'list-content') { //need fix
            
            $part = Input::get('part');
            $list = $classModel::get(Input::get('model'), Input::get('model_id', 0))->images($part)->orderBy('sort', 'asc')->get();     
            
        } else {
            
            $list = $classModel::orderBy('sort', 'asc')->get();
            
        }

        return view($this->templatePath().'.'.$view, array('list' => $list));
    }
    
    public function makeCrop($id)
    {
         $image = $this->model($id);
        
        $source = Input::get('source', 'original');
        $variant = Input::get('variant', '');
        $revariantion = Input::get('revariation', 0);
        $settings = Input::get('settings', 0);
        
        $width = (int)ceil(Input::get('x2') - Input::get('x1'));
        $height = (int)ceil(Input::get('y2') - Input::get('y1'));
        $x = (int)ceil(Input::get('x1'));
        $y = (int)ceil(Input::get('y1'));

        $img = Img::make($image->filename($source));
        
        if ($width && $height) {
            $img->crop($width, $height, $x, $y);
        }
        
        $img->save($image->filename($variant));
        
        if (!$variant && $revariantion) {
            $image->setFileAttribute($image->filename());
            $image->save();
        }
        
        if ($settings) {
            $image->saveVariant($img, $variant);
        }
        
        $this->result['action'] = 'update';
        $this->result['make'] = 'crop';
        $this->result['data']['image'] = subdomainImage($image->src($variant).'?r='.rand());
        
        return $this->result();

    }
}
