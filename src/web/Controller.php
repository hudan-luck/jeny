<?php
namespace sf\web;

class Controller extends \sf\base\Controller
{
    /*public function render($view, $params = [])
    {   
       extract($params);
       return require '../views/'. $view . '.php';
    }*/

    public function toJson($data)
    {
        if (is_string($data)) {
            return $data;
        }
        return json_encode($data);
    }
    
    public function render($view, $params = [])
    {
        $file = '../views/' . $view . '.sf';
        $fileContent = file_get_contents($file);
        $result = '';
        foreach (token_get_all($fileContent) as $token) {
            if (is_array($token)) {
                list($id, $content) = $token;
                    if ($id ==  T_INLINE_HTML) {
                        $content = preg_replace('/{{(.*)}}/', '<?php echo $1 ?>', $content);
                    }
                $result .= $content;
            } else {
                $result .= $token; 
            }
        }    
        $generateFile = '../runtime/cache/' . md5($file);
        file_put_contents($generateFile, $result);
        extract($params);
        require_once $generateFile;
    }
} 
