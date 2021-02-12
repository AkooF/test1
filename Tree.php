<?php


/*
на основании этой таблицы построить дерево (каталог), вложенность неограничена

Ну и пару методов для работы с этим каталогом:
 - добавление нового узла;
 - удаление узла;
 - построить ветку от любого заданного узла;
 - найти верхнеуровневого родителя заданного узла;
*/

class Tree
{
    protected $data;
    protected $tree = [];
    protected $count = 0;

    public function __construct(array $arr) {
        $this->data = $arr;
        $this->tree = $this->buildTree(0, $this->data);
    }


    /**
     * @param $parentId - node id to push child
     * @param $name - content for field 'name'
     */
    public function addNode($parentId, $name) {
        $this->searchNode($parentId, $this->tree, 'add', $name);
    }

    /**
     * @param $parentId - node id for deleting
     */
    public function delNode($parentId) {
        $this->searchNode($parentId, $this->tree, 'del');
    }

    /**
     * - Show all tree with HTML tags
     */
    public function showAll() {
        try {
            $this->showNodeField($this->tree, 'name', 0);
        }
        catch ( Exception $exception) {
            var_dump($exception);
        }
    }

    /**
     *  - Show selected Node with HTML tags
     * @param $id
     */
    public function showNode($id){
        $this->searchNode($id, $this->tree, 'limb');
    }

    /**
     * @param $id - node id
     * @param null $name - field name
     * @return mixed|null - one field content
     */
    public function searchParent($id, $name = null){
        return $this->searchParentNode($id, $this->tree, $name);
    }

    /**
     * @param $parentId - parent node id
     * @param $array
     * @return array
     */
    private function buildTree($parentId, $array) {
        $node = [];
        foreach ($array as $el) {
            if ($el['parent_id'] == $parentId) {
                $this->count++;
                $element = [
                    "id" => $el['id'],
                    "name" => $el['name'],
                    "parent_id" => $el['parent_id'],
                    "children" => $this->buildTree($el['id'], $array)
                ];
                $node[] = $element;
            }
        }
        return $node;
    }

    /**
     * @param $arr
     * @param $newParentId
     * @return array
     */
    private function changeParents(&$arr, $newParentId){
        if (is_array($arr) && count($arr) > 0) {
            foreach ($arr as &$node) {
                if (isset($node['parent_id'])) {
                    $node['parent_id'] = $newParentId;
                }
            }
        }
        return $arr;
    }

    /**
     * @param $id - search id
     * @param $arr
     * @param $action - [add, del, limb]
     * @param null $name - field name
     */
    private function searchNode($id, &$arr, $action, $name = null){
        foreach ($arr as $key => &$node) {
            if ($node['id'] == $id) {
                switch ($action) {
                    case 'add':
                        $element = [
                            "id" => ++$this->count,
                            "name" => $name,
                            "parent_id" => $id,
                            "children" => []
                        ];
                        array_push($node['children'], $element);
                        break;
                    case 'del':
                        $children = ($this->changeParents($node['children'], $node['parent_id']));
                        unset($arr[$key]);
                        $this->count--;
                        if (is_array($children) && count($children) > 0) {
                            foreach ($children as $child){
                                $arr[] = $child;
                            }
                        }
                        break;
                    case 'limb':
                        echo 'Limb:'.PHP_EOL;
                        $test[] = $node;
                        $this->showNodeField($test, 'name', 0);
                        break;
                }
            }
            else if (isset($node['children']) && count($node['children']) > 0){
                $this->searchNode($id, $node['children'], $action, $name);
            }
        }
    }

    /**
     * @param $id - node id
     * @param $tree
     * @param null $name - field name
     * @return mixed|null
     */
    private function searchParentNode($id, $tree, $name = null){
        foreach ($tree as $key => $node) {
            if (isset($node['children']) && count($node['children']) > 0){
                if ($this->checkChildren($node['children'], $id)) {
                    if ($name == null) {
                        return $node;
                    }
                    else {
                        return $node[$name];
                    }
                } else {
                    return $this->searchParentNode($id, $node['children'], $name);
                }
            }
        }
        return null;
    }

    /**
     * @param $arr
     * @param $id - node id
     * @return bool
     */
    private function checkChildren($arr, $id){
        foreach ($arr as $node) {
            if ($node['id'] == $id) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $tree - array
     * @param $field - field name to show
     * @param int $lvl - start of branch depth (not used)
     */
    private function showNodeField($tree, $field, $lvl = 0) {
        echo '<ul>'.PHP_EOL;
        foreach ($tree as $node ) {
            echo '<li>'.$node['id'].':'.$node[$field].'</li>'.PHP_EOL;
            if (count($node['children']) > 0) {
                $this->showNodeField($node['children'], $field, ++$lvl);
            }
        }
        echo '</ul>'.PHP_EOL;
    }
}