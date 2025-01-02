<?php
/**
 * Created by PhpStorm.
 * User: The Customer
 * Date: 26/10/2019
 * Time: 06:52
 */

class MenuProvider
{

    //-----------------------------MENU DYN AMIQUE
    public static function dynamicMenu()
    {
        
    }

    //----------------------------------------VERSION DAARA LUQMAN
    public static function rubricTitle($rubricName='Rubrique'){
        echo "<div class='pcoded-navigatio-lavel'>$rubricName</div>";
    }
    public static function menuRubric($rubricName='Rubrique',$icon='icon-home', $tabItems = []){
        MenuProvider::menuDropDown($rubricName,$icon,false,$tabItems);
    }
    public static function menuDropDown($menuName='Home',$menuIcon='icon-home',$openList=false, $tabItems=[])
    {
        $trigger = $openList ? 'pcoded-trigger' : '';
        echo "
        <ul class='pcoded-item pcoded-left-item'>
        <li class='pcoded-hasmenu active $trigger'>
            <a href='javascript:void(0)'>
                <span class='pcoded-micon'><i class='$menuIcon'></i></span>
                <span class='pcoded-mtext'>$menuName</span>
            </a>
            ";
            MenuProvider::menuSub('#',false, $tabItems);
            echo"
        </li>
    </ul>";
    }
    public static function menuSub($href='#',$active=false, $tabItems=[])
    {
        $class = $active ? 'active' :'';
        echo "<ul class='pcoded-submenu'>";
        foreach($tabItems as $item){
            
            MenuProvider::menuList($item);
        }
        echo "</ul>";
    }
    
    public static function menuList($param = ['itemName' => 'Default','href' => '#','badge' => false,'badgeName' => "New",'active' => false])
    {
        $param['href'] = URLROOT."/".$param['href'];
        $class = $param['active'] ? 'active' : '';
        $badgeHtml = $param['badge'] && $param['badgeName'] ? "<span class='pcoded-badge label label-info'>".$param['badgeName']."</span>" : '';
        echo "<li class='$class'>
        <a href='".$param['href']."'>
            <span class='pcoded-mtext'>".$param['itemName']."</span>
            ".$badgeHtml."
        </a>
    </li>";
    }
    //----------------------------------------VERSION DAARA U17
    public static function menuStart($class='')
    {
        return "
            <ul class='$class'>
        ";
    }
    //
    public static function menuEnd()
    {
        return "</ul>";
    }
    //
    public static function logo($logoUrl,$route='#')
    {
        $route = URLROOT."/".$route;
        return "
            <li class='logo-sn waves-effect py-3'>
                <div class='text-center'>
                    <a href='$route' class='pl-0'><img width='100px' src='$logoUrl'></a>
                </div>
            </li>
        ";
    }

    public static function menuDropDwnStart($title, $icon)
    {
        return "
                <li>
                    <a class='collapsible-header waves-effect arrow-r'>
                        <i class='w-fa fas fa-$icon'></i>$title<i class='fas fa-angle-down rotate-icon'></i>
                    </a>
                    <div class='collapsible-body'>
                        <ul>
        ";
    }

    public static function menuDropDwnEnd()
    {
        return "
                </ul></div></li>
                ";
    }

    public static function item($title, $route='#')
    {
        $route = URLROOT."/".$route;
        return "
            <li>
                <a href='$route' class='waves-effect'>$title</a>
            </li>
        ";
    }

    /**
     * @param $title
     * @param $icon : fas icon
     * @param string $route : Controleur
     */
    public static function itemIconed($title, $icon, $route='#')
    {
        $route = URLROOT."/".$route;
        echo "
            <li>
                <a href='$route' class='collapsible-header waves-effect'><i
                        class='w-fa fas fa-$icon'></i>$title</a>
            </li>
        ";
    }

    public static function items($itemsArray)
    {
        if (!is_array($itemsArray)){return '';}
        $html = '';
        foreach ($itemsArray as $item){
            if (is_array($item))
                $html .= self::item($item[0],$item[1]);
        }
        return $html;
    }

    public static function menuGroup($title,$icon,$dataArray)
    {
        if (is_array($dataArray)){
            echo self::menuDropDwnStart($title,$icon);
            echo self::items($dataArray);
            echo self::menuDropDwnEnd();
        }
    }

}