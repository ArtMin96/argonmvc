<?php

namespace Core;
use App\Models\Users;

class Helper {
    
    public static function dnd($data) {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }

    public static function currentPage() {
        $currentPage = $_SERVER['REQUEST_URI'];
        if($currentPage == PROOT || $currentPage == PROOT.strtolower(DEFAULT_CONTROLLER).'/index') {
          $currentPage = PROOT.strtolower(DEFAULT_CONTROLLER);
        }
        return $currentPage;
    }

    public static function getObjectProperties($object){
        return get_object_vars($object);
    }
    
    public static function posted_values($post) {
        $clean_ary = [];
        foreach($post as $key => $value) {
          $clean_ary[$key] = FormHelper::sanitize($value);
        }
        return $clean_ary;
    }

    public static function buildMenuListItems($menu, $dropdownClass = ""){
        ob_start();
        $currentPage = self::currentPage();
        foreach($menu as $key => $val):
            $active = '';
            if($key == '%USERNAME%'){
                $key = (Users::currentUser())? "Hello " .Users::currentUser()->fname : $key;
            }
            if(is_array($val)): ?>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$key?></a>
                    <div class="dropdown-menu <?=$dropdownClass?>">
                        <?php foreach($val as $k => $v):
                            $active = ($v == $currentPage)? 'active':''; ?>
                        <?php if(substr($k, 0,9) == 'separator'): ?>
                            <div role="separator" class="dropdown-divider"></div>
                        <?php else: ?>
                            <a class="dropdown-item <?=$active?>" href="<?=$v?>"><?=$k?></a>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </li>
            <?php else:
            $active = ($val == $currentPage)? 'active':''; ?>
            <li class="nav-item"><a class="nav-link <?=$active?>" href="<?=$val?>"><?=$key?></a></li>
            <?php endif; ?>
        <?php endforeach;
        return ob_get_clean();
    }
}

