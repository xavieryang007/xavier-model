<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:31
 * Email:499873958@qq.com
 */

namespace Xavier\Model\Driver;


class HasMany extends Relation
{
    public function get()
    {
        return $this->third_model->findAll();
    }

    public function merge($key)
    {
        if ($this->list_model === null) {
            $this->model->$key = $this->get();
        } else {
            $third_arr = $this->get()->pluck($this->third_column, true, true);
            foreach ($this->list_model as $val) {
                $k         = $val[$this->self_column];
                $val->$key = isset($third_arr[$k]) ? new ListModel($third_arr[$k]) : new ListModel([]);
            }
        }
        unset($this->model, $this->third_model);
    }

}